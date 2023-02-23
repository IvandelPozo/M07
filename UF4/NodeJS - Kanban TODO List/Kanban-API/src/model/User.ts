import { DataTypes, Model } from 'sequelize';
import db from '../config/database.config';

interface UserAttributes {
    id: string;
    username: string;
    fullName: string;
}

export class UserInstance extends Model<UserAttributes> { }

UserInstance.init(
    {
        id: {
            type: DataTypes.UUID,
            primaryKey: true,
            allowNull: false,
        },
        username: {
            type: DataTypes.STRING,
            allowNull: false,
            unique: true
        },
        fullName: {
            type: DataTypes.STRING,
            allowNull: false,
        }
    },
    {
        name: {
            singular: 'users',
            plural: 'users',
        },
        timestamps: true,
        sequelize: db,
        tableName: 'users',
        freezeTableName: true,
    }
);