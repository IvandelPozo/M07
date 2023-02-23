import { DataTypes, Model } from 'sequelize';
import db from '../config/database.config';
import { UserInstance } from './User';

interface TaskAttributes {
    id: string;
    user: string;
    title: string;
    description: string;
    status: string;
}

export class TaskInstance extends Model<TaskAttributes> { }

TaskInstance.init(
    {
        id: {
            type: DataTypes.UUID,
            primaryKey: true,
            allowNull: false,
        },
        user: {
            type: DataTypes.STRING,
            allowNull: false,
            references: { model: UserInstance, key: 'id' }
        },
        title: {
            type: DataTypes.STRING,
            allowNull: false,
        },
        description: {
            type: DataTypes.STRING,
            allowNull: false,
        },
        status: {
            type: DataTypes.STRING,
            allowNull: false,
            validate: {
                isIn: [['TODO', 'DOING', 'DONE']]
            }
        }
    },
    {
        name: {
            singular: 'tasks',
            plural: 'tasks',
        },
        timestamps: true,
        sequelize: db,
        tableName: 'tasks',
        freezeTableName: true,
    }
);