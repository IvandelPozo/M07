import { Request, Response } from "express";
import { UniqueConstraintError } from 'sequelize';
import userService from "../services/userService";
import { v4 as uuid } from "uuid";

const getAllUsers = async (req: Request, res: Response) => {

    try {
        const allUsers = await userService.getAllUsers();
        res.status(200).json({ status: 'OK', data: allUsers });
    } catch (error) {
        res.status(500).json({ status: 'ERROR', data: error });
    }
};

const getTasksFromUser = async (req: Request, res: Response) => {
    const createdAt: any = req.query.createdAt;
    const status: any = req.query.status;
    const id: any = req.params.id;
    let filters: any = {};

    if (!id) {
        res.status(400).json({ status: 'ERROR', data: 'No hi ha identificador donat.' });
        return;
    }

    if (status) filters.status = status; filters.user = id;
    if (createdAt) filters.createdAt = createdAt; filters.user = id;

    filters.user = id;

    try {
        const allTasks = await userService.getTasksFromUser(filters);
        res.status(200).json({ status: 'OK', data: allTasks });
    } catch (error) {
        res.status(500).json({ status: 'ERROR', data: error });
    }
};

const createNewUser = async (req: Request, res: Response) => {
    const { body } = req;

    if (!body.username || !body.fullName) {
        res.status(400).json({ status: 'ERROR', data: 'Insereix username i fullName.' });
        return;
    }

    const newUser = {
        id: uuid(),
        username: body.username,
        fullName: body.fullName
    };

    try {
        const createdUser = await userService.createNewUser(newUser);
        res.status(201).json({ status: 'OK', data: createdUser });
    } catch (error) {
        if (error instanceof UniqueConstraintError) {
            res.status(409).json({ status: 'ERROR', data: 'A user with that username already exists.' });
        } else {
            res.status(500).json({ status: 'ERROR', data: error });
        }
    }
};

const deleteOneUser = async (req: Request, res: Response) => {

    const {
        params: { id },
    } = req;

    if (!id) {
        res.status(400).json({ status: 'ERROR', data: 'No hi ha identificador donat.' });
        return;
    }

    try {
        const deletedUser = await userService.deleteOneUser(id);
        res.status(200).json({ status: 'OK', data: deletedUser });
    } catch (error: any) {
        res.status(500).json({ status: 'ERROR', data: error.message });
    }
};

export default {
    getAllUsers,
    getTasksFromUser,
    createNewUser,
    deleteOneUser
};
