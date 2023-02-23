import { Request, Response } from "express";
import taskService from "../services/taskService";
import { ValidationError } from 'sequelize';
import { v4 as uuid } from "uuid";

const getOneTask = async (req: Request, res: Response) => {
    const { id } = req.params;

    if (!id) {
        res.status(400).json({ status: 'ERROR', data: 'No hi ha identificador donat.' });
        return;
    }

    try {
        const oneTask = await taskService.getOneTask(id);
        res.status(200).json({ status: 'OK', data: oneTask });
    } catch (error) {
        res.status(500).json({ status: 'ERROR', data: error });
    }
};

const createNewTask = async (req: Request, res: Response) => {
    const { body } = req;

    if (!body.user || !body.title || !body.description || !body.status) {
        res.status(400).json({ status: 'ERROR', data: 'Insereix user, title, description i status.' });
        return;
    }

    const newTask = {
        id: uuid(),
        user: body.user,
        title: body.title,
        description: body.description,
        status: body.status
    };

    try {
        const createdTask = await taskService.createNewTask(newTask);
        res.status(201).json({ status: 'OK', data: createdTask });
    } catch (error) {
        if (error instanceof ValidationError) {
            res.status(400).json({ status: 'ERROR', data: "Insereix TODO, DOING o DONE" });
        } else {
            res.status(500).json({ status: 'ERROR', data: error });
        }
    }
};

const updateOneTask = async (req: Request, res: Response) => {
    const {
        body,
        params: { id },
    } = req;

    if (!id) {
        res.status(400).json({ status: 'ERROR', data: 'No hi ha identificador donat.' });
        return;
    }

    try {
        const updatedTask = await taskService.updateOneTask(body, id);
        res.status(200).json({ status: 'OK', data: updatedTask });
    } catch (error) {
        if (error instanceof ValidationError) {
            res.status(400).json({ status: 'ERROR', data: "Insereix TODO, DOING o DONE" });
        } else {
            res.status(500).json({ status: 'ERROR', data: error });
        }
    }
};

const deleteOneTask = async (req: Request, res: Response) => {

    const {
        params: { id },
    } = req;

    if (!id) {
        res.status(400).json({ status: 'ERROR', data: 'No hi ha identificador donat.' });
        return;
    }

    try {
        const deletedTask = await taskService.deleteOneTask(id);
        res.status(200).json({ status: 'OK', data: deletedTask });
    } catch (error) {
        res.status(500).json({ status: 'ERROR', data: error });
    }
};

export default {
    getOneTask,
    createNewTask,
    updateOneTask,
    deleteOneTask
};
