import { TaskInstance } from "../model/Task";

const getOneTask = async (id: string) => {
    const oneTask = await TaskInstance.findOne({ where: { id } });
    return oneTask;
};

const createNewTask = async (newTask: any) => {
    const taskToInsert = {
        ...newTask
    };

    const createdTask = await TaskInstance.create(taskToInsert);
    return createdTask;
};

const updateOneTask = async (changes: any, id: string) => {
    const updatedTask = await TaskInstance.update(changes, { where: { id } });
    return updatedTask;
};

const deleteOneTask = async (id: string) => {
    const deletedTask = await TaskInstance.destroy({ where: { id } });
    return deletedTask;
};

export default {
    getOneTask,
    createNewTask,
    updateOneTask,
    deleteOneTask
};