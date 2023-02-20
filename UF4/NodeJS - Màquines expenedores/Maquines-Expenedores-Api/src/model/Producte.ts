import { DataTypes, Model } from 'sequelize';
import db from '../config/database.config';
import { CategoriaInstance } from './Categoria';
interface ProducteAttributes {
    id: string;
    nom: string;
    tipus: string;
    preu: number;
    categoria: string;
}

export class ProducteInstance extends Model<ProducteAttributes> { }

ProducteInstance.init(
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
        tipus: {
            type: DataTypes.STRING,
            allowNull: false,
        },
        preu: {
            type: DataTypes.FLOAT,
            allowNull: false,
        },
        categoria: {
            type: DataTypes.UUID,
            allowNull: false,
            references: { model: CategoriaInstance, key: 'id' }
        }
    },
    {
        name: {
            singular: 'productes',
            plural: 'productes',
        },
        timestamps: true,
        sequelize: db,
        tableName: 'productes',
        freezeTableName: true,
    }
);
