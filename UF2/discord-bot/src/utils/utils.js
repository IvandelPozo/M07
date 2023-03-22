const fs = require('fs');
const { EmbedBuilder } = require("discord.js");
const config = require("../../config.json");
var debug = require('debug')('utils');

/**  GUARDAR JSON  **/

/**
 * Escriu la informació de les tasques pendents a un fitxer JSON
 * @param {string} data 
 * @param {string} nomFitxer 
 */

function escriureDataJson(data, nomFitxer) {

    const jsonData = {
        tasques: []
    };

    for (const titolTasca in data) {
        if (data.hasOwnProperty(titolTasca)) {
            const usuaris = data[titolTasca];
            const novaTasca = {
                titol: titolTasca,
                usuaris: usuaris
            };
            jsonData.tasques.push(novaTasca);
        }
    }

    fs.writeFile(`./src/jsonDB/${nomFitxer}`, JSON.stringify(jsonData, null, 2), err => {
        if (err) {
            debug(err);
            return;
        }
        debug(`Informació escrita a fitxer -> '${nomFitxer}'`);
    });
}

/**
 * Afegeix o Actualitza el fitxer JSON de configuració
 * @param {string} data 
 */

function escriureConfigJson(data) {

    fs.writeFile('./config.json', JSON.stringify(data), (err) => {
        if (err) throw err;
        debug('Configuració Actualitzada!');
    });
}

/**
 * Escriu la nota de les tasques enviades a un fitxer JSON
 * @param {string} data 
 */

function escriureDataUsersDiscordJson(data) {
    fs.readFile('./src/jsonDB/dataUsersDiscord.json', (err, jsonData) => {
        if (err) throw err;

        try {
            const parsedData = JSON.parse(jsonData);
            if (!parsedData) {
                // Si parsedData está vacío o es nulo, entonces inicialice el objeto JSON
                const newData = { submissions: [] };
                fs.writeFile('./src/jsonDB/dataUsersDiscord.json', JSON.stringify(newData), (err) => {
                    if (err) throw err;
                    debug('Informació escrita a fitxer -> dataUsersDiscord.json!');
                });
            } else {
                // Si parsedData contiene datos JSON válidos, entonces actualice el objeto JSON
                parsedData.submissions = data.submissions;

                fs.writeFile('./src/jsonDB/dataUsersDiscord.json', JSON.stringify(parsedData), (err) => {
                    if (err) throw err;
                    debug('Informació escrita a fitxer -> dataUsersDiscord.json!');
                });
            }
        } catch (err) {
            debug(err);
        }
    });
}

/**
 * Escriu els correus dels usuaris a un fitxer JSON
 * @param {string} correus 
 */

function escriureCorreusDataUsersDiscordJson(correus) {
    const jsonData = require('../jsonDB/dataUsersDiscord.json');
    Object.assign(jsonData, correus);

    fs.writeFile('./src/jsonDB/dataUsersDiscord.json', JSON.stringify(jsonData), (err) => {
        if (err) throw err;
        debug('Informació escrita a fitxer -> dataUsersDiscord.json!');
    });
}

/**  EMBEDS  **/

/**
 * Escriu un embed amb les tasques pendents dels usuaris
 * @param {Client} client 
 * @param {string} channelId 
 * @param {string} titolTasca 
 * @param {Array<string>} tasquesUsuaris 
 */

function embedEntreguesPendents(client, channelId, titolTasca, tasquesUsuaris) {
    if (!channelId) {
        debug('Channel ID és undefined.');
        return;
    }

    const channel = client.channels.cache.get(channelId);

    const embedClassroom = new EmbedBuilder()
        .setColor(0x0099FF)
        .setTitle('Entregues Pendents')
        .setThumbnail('https://upload.wikimedia.org/wikipedia/commons/thumb/1/19/Google_Classroom_Logo.svg/512px-Google_Classroom_Logo.svg.png')
        .addFields(
            { name: 'Nom de la Tasca:', value: titolTasca },
            { name: '\u200B', value: '\u200B' },
            { name: 'Usuaris pendents d\'entrega:', value: ' ' },
            ...tasquesUsuaris.map((usuari) => ({ name: ' ', value: usuari })),
        )
        .setTimestamp();

    channel.send({ embeds: [embedClassroom] });
}

/**
 * Escriu un embed amb les notes de les tasques enviades
 * @param {string} tasca 
 * @param {string} nom 
 * @param {float} nota 
 * @return {EmbedBuilder} embedClassroom
 */

async function embedNota(tasca, nom, nota) {

    const embedClassroom = new EmbedBuilder()
        .setColor(0xFF6F11)
        .setTitle('Qualificació de Nota')
        .setDescription(`${nom}, s'ha posat la nota a una de les teves tasques entregades.`)
        .setThumbnail('https://pixy.org/src/457/4576578.png')
        .addFields(
            { name: 'Nom de la Tasca:', value: tasca },
            { name: '\u200B', value: '\u200B' },
            { name: 'Nota:', value: nota.toString() }
        )
        .setTimestamp();

    return embedClassroom;
}


/**  FUNCIONS PER SETINTERVAL  **/

/**
 * Comprova si hi ha canvis en les tasques pendents, si n'hi ha, envia un embed amb les tasques pendents.
 * Al principi, enviarà un embed amb totes les tasques pendents actuals.
 * Si un usuari ha entregat una tasca, envia un embed amb els usuaris pendents d'entrega.
 * Si un usuari que havia entregat una tasca, ha tornat a enviar-la, envia un embed amb els usuaris pendents d'entrega.
 * @param {Client} client
 */

const dadesAnteriors = { tasques: [] };

async function comprovarCanvisTasques(client) {
    const { authorize, getTasquesNoCompletades } = require('../classroom.js');

    if (config["CHANNEL_ID"] != "") {

        authorize().then(getTasquesNoCompletades).catch(debug);
        debug('Comprovant canvis...');

        try {
            const dades = await fs.promises.readFile('src/jsonDB/data.json');
            const dadesNoves = { tasques: [] };
            let jsonData;

            try {
                jsonData = JSON.parse(dades);
            } catch (err) {
                debug(`Sense dades al fitxer 'data.json'`);
                return;
            }

            // Comprova tasques noves i nous usuaris a tasques existents

            for (const tasca of jsonData.tasques) {
                const tascaAnterior = dadesAnteriors.tasques.find((t) => t.titol === tasca.titol) || {};

                if (!tascaAnterior.hasOwnProperty('titol')) {
                    debug(`Nova tasca afegida: ${tasca.titol} amb usuaris: ${tasca.usuaris}`);
                    dadesNoves.tasques.push({ titol: tasca.titol, usuaris: tasca.usuaris });
                    embedEntreguesPendents(client, config.CHANNEL_ID, tasca.titol, tasca.usuaris);
                } else {
                    const usuarisEliminats = tascaAnterior.usuaris.filter((u) => !tasca.usuaris.includes(u));
                    const usuarisRestants = tascaAnterior.usuaris.filter((u) => !usuarisEliminats.includes(u));

                    if (usuarisEliminats.length > 0) {
                        debug(`Usuari/s eliminat/s de la tasca '${tasca.titol}', usuaris restants: ${usuarisRestants}`);
                        dadesNoves.tasques.push({ titol: tasca.titol, usuaris: usuarisRestants });
                        embedEntreguesPendents(client, config.CHANNEL_ID, tasca.titol, usuarisRestants);
                    } else {
                        // Comprova nous usuaris afegits

                        const nousUsuaris = tasca.usuaris.filter((u) => !tascaAnterior.usuaris.includes(u));
                        if (nousUsuaris.length > 0) {
                            debug(`Nou/s usuari/s afegit/s a la tasca '${tasca.titol}': ${nousUsuaris}`);
                            dadesNoves.tasques.push({ titol: tasca.titol, usuaris: tasca.usuaris });
                            embedEntreguesPendents(client, config.CHANNEL_ID, tasca.titol, tasca.usuaris);
                        } else {
                            dadesNoves.tasques.push(tascaAnterior);
                        }
                    }

                }
            }

            dadesAnteriors.tasques = dadesNoves.tasques;

        } catch (err) {
            debug(err);
        }
    } else {
        debug('No hi ha cap canal definit per enviar els missatges.');
    }
}

/**  FUNCIONS GENERALS  **/

/**
 * Envia un missatge privat a l'usuari amb la nota de la tasca
 * @param {Client} client 
 * @param {string} email 
 * @param {string} tasca 
 * @param {string} nom 
 * @param {float} nota 
 */

async function enviarNota(client, email, tasca, nom, nota) {
    let data = require("../jsonDB/dataUsersDiscord.json");

    if (data[email] == undefined) {
        debug(`El correu: ${email}, no s'ha trobat al fitxer JSON. No s'ha pogut enviar la nota per privat!`);
    } else {
        let userId = data[email];
        try {
            const user = await client.users.fetch(userId); // Fetch the user object
            const embed = await embedNota(tasca, nom, nota); // Create the embed using the embedNota function
            await user.send({ embeds: [embed] }); // Send the message and embed to the user
            debug(`Enviant missatge privat a ${user.username}...`);
        } catch (error) {
            debug(`Error enviant el missatge privat per l'usuari amb ID ${userId}:`, error);
        }
    }
}

/**  COMANDES  **/

/**
 * Afegeix les comandes al bot amb les seves descripcions i opcions.
 * @param {Client} client 
 */

function addCommands(client) {

    const commands = [
        {
            name: 'setup',
            description: 'Estableix el canal de text on es publicaran les tasques.'
        }, {
            name: 'tasquespendents',
            description: 'Força la comprovació de tasques pendents.'
        }, {
            name: 'qualificacions',
            description: 'Força la comprovació de qualificacions.'
        }, {
            name: 'establircorreu',
            description: 'Estableix el teu correu electrònic per enllaçar l\'usuari de Discord i el de Classroom.',
            options: [
                {
                    name: 'correu',
                    description: 'El teu correu electrònic.',
                    type: 3,
                    required: true
                }
            ]
        }
    ];

    commands.forEach(command => {
        client.application.commands.create(command);
    });
}

module.exports = {
    escriureDataJson,
    escriureConfigJson,
    escriureDataUsersDiscordJson,
    comprovarCanvisTasques,
    addCommands,
    enviarNota,
    escriureCorreusDataUsersDiscordJson
};