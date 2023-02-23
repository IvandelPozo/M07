import {Sequelize} from 'sequelize';

const db = new Sequelize("app","","",{
    storage: "./database.sqlite",
    dialect: "sqlite",
    logging: console.log,
    dialectOptions: {
        pragma: {
            foreign_keys: true
        }
    }
});

export default db;