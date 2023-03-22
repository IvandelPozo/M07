const { Client, GatewayIntentBits } = require("discord.js");
const config = require("../config.json");
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

    // Llegir JSON

    let dadesUsuaris = fs.readFileSync('./src/jsonDB/dataUsersDiscord.json');
    if (dadesUsuaris.length > 0) {
        data = JSON.parse(dadesUsuaris);
    }

    // Definició de comandes

    if (interaction.isChatInputCommand()) {

        if (interaction.commandName == 'setup') {

            config['CHANNEL_ID'] = interaction.channel.id;
            escriureConfigJson(config);

            await interaction.reply({
                content: '>>> Canal d\'informació de Classroom establert!',
            });
        } else if (interaction.commandName == 'tasquespendents') {

            if (config['CHANNEL_ID'] == "") {
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

// Comprovació de tasques pendents cada 5 segons
setInterval(() => comprovarCanvisTasques(client), 90 * 1000);
setInterval(() => authorize().then((auth) => { getQualificacio(auth, client) }).catch(debug), 90 * 1000);