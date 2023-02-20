import { DataTypes, Model } from 'sequelize';
import db from '../config/database.config';
interface MaquinaAttributes {
    id: string;
    municipi: string;
    adreça: string;
}

export class MaquinaInstance extends Model<MaquinaAttributes> { }

MaquinaInstance.init(
    {
        id: {
            type: DataTypes.UUID,
            primaryKey: true,
            allowNull: false,
        },
        municipi: {
            type: DataTypes.STRING,
            allowNull: false,
        },
        adreça: {
            type: DataTypes.STRING,
            allowNull: false,
        }
    },
    {
        name: {
            singular: 'maquines',
            plural: 'maquines',
        },
        timestamps: true,
        sequelize: db,
        tableName: 'maquines',
        freezeTableName: true,
    }
);