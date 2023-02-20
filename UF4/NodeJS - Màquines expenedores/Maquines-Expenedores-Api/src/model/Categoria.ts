import { DataTypes, Model } from 'sequelize';
import db from '../config/database.config';
interface CategoriaAttributes {
    id: string;
    nom: string;
    iva: number;
}

export class CategoriaInstance extends Model<CategoriaAttributes> { }

CategoriaInstance.init(
    {
        id: {
            type: DataTypes.UUID,
            primaryKey: true,
            allowNull: false,
        },
        nom: {
            type: DataTypes.STRING,
            allowNull: false,
        },
        iva: {
            type: DataTypes.INTEGER,
            allowNull: false,
        }
    },
    {
        name: {
            singular: 'categories',
            plural: 'categories',
        },
        timestamps: true,
        sequelize: db,
        tableName: 'categories',
        freezeTableName: true,
    }
);