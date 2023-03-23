const { Client, GatewayIntentBits } = require("discord.js");
let config = require("../config.json");
const { authorize, getTasquesNoCompletades, getQualificacio } = require('./classroom.js');
const { escriureConfigJson, escriureCorreusDataUsersDiscordJson, comprovarCanvisTasques, addCommands } = require('./utils/utils.js');
var debug = require('debug')('bot');

/**
 * Crea el client de Discord
 * @type {Client}
 */
//Intents (https://discord.com/developers/docs/topics/gateway)

const client = new Client({
    intents: [
        GatewayIntentBits.Guilds,
        GatewayIntentBits.GuildMessages,
        GatewayIntentBits.MessageContent,
        GatewayIntentBits.DirectMessages,
        GatewayIntentBits.GuildMembers,
    ]
});

//Events

/**
 * Esdeveniment que s'activa quan el bot s'ha connectat al servidor o s'ha iniciat
 */

client.on("ready", () => {
    debug(`booting ${client.user.tag}`);

    authorize().then(getTasquesNoCompletades).catch(debug);
    authorize().then((auth) => {
        getQualificacio(auth, client)
    }).catch(debug);

    addCommands(client);
});

/**
 * Esdeveniment que s'activa quan es produeix un error no controlat pel bot
 */

process.on('uncaughtException', (error) => {
    debug(error);
});

/**
 * Esdeveniment que s'activa quan es reben comandes pel chat
 */

client.on('interactionCreate', async (interaction) => {

    const fs = require('fs');

    let data = {};

    // Llegir JSON dataUsersDiscord

    let dadesUsuaris = fs.readFileSync('./src/jsonDB/dataUsersDiscord.json');
    if (dadesUsuaris.length > 0) {
        data = JSON.parse(dadesUsuaris);
    }

    // Llegir JSON config

    const rawConfig = fs.readFileSync('./config.json');
    const conf = JSON.parse(rawConfig);

    // Definició de comandes

    if (interaction.isChatInputCommand()) {

        // SETUP

        if (interaction.commandName == 'setup') {

            const nousServidors = {
                [interaction.guild.id]: {
                    CHANNEL_ID: interaction.channel.id,
                },
            };

            escriureConfigJson(nousServidors);

            await interaction.reply({
                content: '>>> Canal d\'informació de Classroom establert!',
            });

        // TASQUES PENDENTS

        } else if (interaction.commandName == 'tasquespendents') {

            if (!conf['servers'][interaction.guild.id]) {
                await interaction.reply({
                    content: '>>> Defineix un canal de setup...',
                });
            } else {

                authorize().then(getTasquesNoCompletades).catch(debug);
                comprovarCanvisTasques(client);

                await interaction.reply({
                    content: '>>> Cercant tasques pendents...',
                });
            }

        // ESTABLIR CORREU

        } else if (interaction.commandName == 'establircorreu') {
            const memberId = interaction.user.id;
            const email = interaction.options.getString('correu');

            if (data.hasOwnProperty(email)) {
                data[email].memberId = memberId;
            } else {
                data[memberId] = email;
                data[email] = memberId;
            }

            escriureCorreusDataUsersDiscordJson(data);

            await interaction.reply({
                content: '>>> Email registrat correctament!',
            });

        // QUALIFICACIONS

        } else if (interaction.commandName == 'qualificacions') {

            authorize().then((auth) => {
                getQualificacio(auth, client)
            }).catch(debug);

            await interaction.reply({
                content: '>>> Enviant qualificacions...',
            });
        }
    }
});

// Inici del bot

client.login(config.BOT_TOKEN);

// Comprovació de tasques pendents cada 1 hora
setInterval(() => comprovarCanvisTasques(client), 4 * 1000);
setInterval(() => authorize().then((auth) => { getQualificacio(auth, client) }).catch(debug), 60 * 60 * 1000);