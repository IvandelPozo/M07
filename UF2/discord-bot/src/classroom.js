const fs = require('fs').promises;
const path = require('path');
const process = require('process');
const { authenticate } = require('@google-cloud/local-auth');
const { google } = require('googleapis');
const { escriureDataJson, escriureDataUsersDiscordJson, enviarNota } = require('./utils/utils.js');
const config = require("../config.json");
var debug = require('debug')('classroom');

const SCOPES = [
  'https://www.googleapis.com/auth/classroom.courses',
  'https://www.googleapis.com/auth/classroom.courses.readonly',
  'https://www.googleapis.com/auth/classroom.coursework.me',
  'https://www.googleapis.com/auth/classroom.coursework.me.readonly',
  'https://www.googleapis.com/auth/classroom.coursework.students',
  'https://www.googleapis.com/auth/classroom.coursework.students.readonly',
  'https://www.googleapis.com/auth/classroom.announcements',
  'https://www.googleapis.com/auth/classroom.announcements.readonly',
  'https://www.googleapis.com/auth/classroom.rosters',
  'https://www.googleapis.com/auth/classroom.rosters.readonly',
  'https://www.googleapis.com/auth/classroom.profile.emails',
  'https://www.googleapis.com/auth/classroom.profile.photos',
];

// Carrega les credencials de l'aplicació
// Si no existeixen, les crea, les guarda i les carrega.
const TOKEN_PATH = path.join(process.cwd(), 'token.json');
const CREDENTIALS_PATH = path.join(process.cwd(), 'credentials.json');

/**
 * Llegeix les credencials per poder accedir a les dades de google classroom
 *
 * @return {Promise<OAuth2Client|null>}
 */

async function loadSavedCredentialsIfExist() {
  try {
    const content = await fs.readFile(TOKEN_PATH);
    const credentials = JSON.parse(content);
    return google.auth.fromJSON(credentials);
  } catch (err) {
    return null;
  }
}

/**
 * Serialitza les credencials per poder accedir a les dades de google classroom
 *
 * @param {OAuth2Client} client
 */

async function saveCredentials(client) {
  const content = await fs.readFile(CREDENTIALS_PATH);
  const keys = JSON.parse(content);
  const key = keys.installed || keys.web;
  const payload = JSON.stringify({
    type: 'authorized_user',
    client_id: key.client_id,
    client_secret: key.client_secret,
    refresh_token: client.credentials.refresh_token,
  });
  await fs.writeFile(TOKEN_PATH, payload);
}

/**
 * Autoritza l'aplicació per poder accedir a les dades de google classroom
 * 
 * @returns {Promise<OAuth2Client>}
 */

async function authorize() {
  let client = await loadSavedCredentialsIfExist();
  if (client) {
    return client;
  }
  client = await authenticate({
    scopes: SCOPES,
    keyfilePath: CREDENTIALS_PATH,
  });
  if (client.credentials) {
    await saveCredentials(client);
  }
  return client;
}

/**
 * Llista les tasques del curs "Discord" de google classroom
 * 
 * @param {OAuth2Client} auth
 * @return courseWork
 */

async function getCourseWork(auth) {
  const courseId = '543091498649';

  // Create Classroom API client
  const classroom = google.classroom({ version: 'v1', auth });

  try {
    // Obtenir les tasques del curs especificat pel seu ID
    const resposta = await classroom.courses.courseWork.list({
      courseId,
    });

    // Retornar la llista de tasques del curs
    const tasquesCurs = resposta.data.courseWork;

    return tasquesCurs;
  } catch (err) {
    debug(err);
    throw new Error('No s\'han pogut obtenir les tasques del curs');
  }
}

/**
 * Obtenir les tasques no entregades del curs "Discord" de google classroom i els seus alumnes.
 * Obtenim les tasques que no estan entregades, les que no están retornades, les que no han estat corregides i les que no han passat la data de lliurament
 * amb els seus alumnes.
 * 
 * @param {OAuth2Client} auth
 * @return tasquesNoCompletades
 */

async function getTasquesNoCompletades(auth) {
  const courseId = '543091498649';
  const classroom = google.classroom({ version: 'v1', auth });

  // Obtenir totes les tasques del curs "Discord" de google classroom
  const respostaTasques = await classroom.courses.courseWork.list({
    courseId,
  });
  const tasques = respostaTasques.data.courseWork;

  // Obtenir tots els estutiants per cada tasca
  const tasquesNoCompletades = {};
  for (const tasca of tasques) {
    const respostaLliuraments = await classroom.courses.courseWork.studentSubmissions.list({
      courseId,
      courseWorkId: tasca.id,
    });
    const lliuraments = respostaLliuraments.data.studentSubmissions;

    // Filtra les tasques que no estan entregades, les que no están retornades, les que no han estat corregides i les que no han passat la data de lliurament
    const noCompletades = lliuraments.filter(submission =>
      submission.state !== 'TURNED_IN' &&
      submission.state !== 'RETURNED' &&
      (!submission.stateHistory || !submission.stateHistory.some(state => state.state === 'GRADED')) &&
      (!tasca.dueDate || Date.now() < new Date(tasca.dueDate).getTime()));

    // Afegeix les tasques no entregades i els seus alumnes a l'objecte de resultats
    if (noCompletades.length > 0) {
      const respostaEstudiants = await classroom.courses.students.list({ courseId });
      const estudiants = respostaEstudiants.data.students;
      for (const lliurament of noCompletades) {
        const estudiant = estudiants.find(estudiant => estudiant.userId === lliurament.userId);
        if (!tasquesNoCompletades[tasca.title]) {
          tasquesNoCompletades[tasca.title] = [];
        }
        if (estudiant && estudiant.profile.emailAddress) {
          if (!tasquesNoCompletades[tasca.title].includes(estudiant.profile.emailAddress)) {
            tasquesNoCompletades[tasca.title].push(estudiant.profile.emailAddress);
          }
        }
      }
    }
  }

  if (config["CHANNEL_ID"] != "") {

    escriureDataJson(tasquesNoCompletades, 'data.json');
    return tasquesNoCompletades;

  } else {
    debug("No hi ha cap canal de discord configurat");
  }
}

/**
 * Obtenir les notes i dades dels alumnes del curs "Discord" de google classroom
 * 
 * @param {OAuth2Client} auth
 * @param {Client} client 
 */

async function getQualificacio(auth, client) {
  const courseId = '543091498649';
  const classroom = google.classroom({ version: 'v1', auth });
  const arrayTasques = await getCourseWork(auth).catch(debug);
  let data = require('./jsonDB/dataUsersDiscord.json');

  if (data["submissions"] == undefined) {
    data["submissions"] = {};
  }

  // Recorre totes les tasques del curs "Discord" de google classroom

  arrayTasques.forEach(async t => {
    const lliuraments = await classroom.courses.courseWork.studentSubmissions.list({
      courseId: courseId,
      courseWorkId: t.id
    });

    if (data["submissions"][t.id] == undefined) {
      data["submissions"][t.id] = {};
    }

    escriureDataUsersDiscordJson(data);

    // Recorre totes les lliuraments de cada tasca

    const dadesLliuramentsUsuaris = lliuraments.data.studentSubmissions;

    await dadesLliuramentsUsuaris.forEach(async s => {
      const lliurament = await classroom.courses.courseWork.studentSubmissions.get({
        courseId: courseId,
        courseWorkId: t.id,
        id: s.id
      });

      const dadesLliurament = lliurament.data;

      // Obtenir les dades de l'estudiant

      const estudiant = await classroom.courses.students.get({
        courseId: courseId,
        userId: dadesLliurament.userId
      });

      const dadesEstudiant = estudiant.data;

      // Si l'estudiant no ha entregat la tasca, no s'envia cap nota, però s'afegeix a la base de dades, per si l'estudiant la entrega després, que s'enviï la nota, i no s'enviï una nota repetida

      if (data["submissions"][t.id][s.id] == undefined) {
        data["submissions"][t.id][s.id] = {};
        if (dadesLliurament.assignedGrade != undefined) {
          enviarNota(client, dadesEstudiant.profile.emailAddress, t.title, dadesEstudiant.profile.name.fullName, dadesLliurament.assignedGrade);
        }
      }
      data["submissions"][t.id][s.id] = dadesLliurament.assignedGrade;
      escriureDataUsersDiscordJson(data);
    });
  });
}

module.exports = {
  authorize,
  getTasquesNoCompletades,
  getQualificacio
};