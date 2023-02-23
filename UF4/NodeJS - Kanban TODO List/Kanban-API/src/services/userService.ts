import { Op } from "sequelize";
import { TaskInstance } from "../model/Task";
import { UserInstance } from "../model/User";

const getAllUsers = async () => {
    const allUsers = await UserInstance.findAll({ where: {} });
    return allUsers;
};

const getTasksFromUser = async (filters: any) => {

    if (filters.status) {
        const allTasks = await TaskInstance.findAll({
            where: {
                status: filters.status,
                user: filters.user
            },
        });
        return allTasks;
    } else {
        const allTasks = await TaskInstance.findAll({ where: filters });
        return allTasks;
    }
};

const createNewUser = async (newUser: any) => {
    const userToInsert = {
        ...newUser
    };

    const createdUser = await UserInstance.create(userToInsert);
    return createdUser;
};

const deleteOneUser = async (id: string) => {
    
    const userTasks = await TaskInstance.findAll({ where: { user: id } });
    
    if (userTasks.length > 0) {
      throw new Error('User has tasks and cannot be deleted');
    }
  
    const deletedUser = await UserInstance.destroy({ where: { id } });
    return deletedUser;
  };

export default {
    getAllUsers,
    getTasksFromUser,
    createNewUser,
    deleteOneUser
};