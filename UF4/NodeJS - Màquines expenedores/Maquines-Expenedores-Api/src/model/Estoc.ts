import { DataTypes, Model } from 'sequelize';
import db from '../config/database.config';
import { CalaixInstance } from './Calaix';
import { ProducteInstance } from './Producte';
interface EstocAttributes {
    id: string;
    producte: string;
    caducitat: Date;
    dataVenda: Date;
    ubicacio: string;
}

export class EstocInstance extends Model<EstocAttributes> { }

EstocInstance.init(
    {
        id: {
            type: DataTypes.UUID,
            primaryKey: true,
            allowNull: false
        },
        producte: {
            type: DataTypes.UUID,
            allowNull: false,
            references: { model: ProducteInstance, key: 'id' }
        },
        caducitat: {
            type: DataTypes.DATEONLY,
            allowNull: false,
        },
        dataVenda: {
            type: DataTypes.DATEONLY,
            allowNull: true,
            defaultValue: null,
        },
        ubicacio: {
            type: DataTypes.UUID,
            allowNull: false,
            references: { model: CalaixInstance, key: 'id' }
        }
    },
    {
        name: {
            singular: 'estocs',
            plural: 'estocs',
        },
        timestamps: true,
        sequelize: db,
        tableName: 'estocs',
        freezeTableName: true,
    }
);