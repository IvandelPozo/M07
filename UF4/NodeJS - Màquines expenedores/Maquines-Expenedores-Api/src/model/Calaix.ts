import { DataTypes, Model } from 'sequelize';
import db from '../config/database.config';
import { MaquinaInstance } from './Maquina';
interface CalaixAttributes {
    id: string;
    maquina: string;
    casella: string;
}

export class CalaixInstance extends Model<CalaixAttributes> { }

CalaixInstance.init(
    {
        id: {
            type: DataTypes.UUID,
            primaryKey: true,
            allowNull: false,
        },
        maquina: {
            type: DataTypes.UUID,
            allowNull: false,
            references: { model: MaquinaInstance, key: 'id' }
        },
        casella: {
            type: DataTypes.STRING,
            allowNull: false,
        }
    },
    {
        name: {
            singular: 'calaixos',
            plural: 'calaixos',
        },
        timestamps: true,
        sequelize: db,
        tableName: 'calaixos',
        freezeTableName: true,
    }
);