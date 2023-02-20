import { Request, Response } from "express";
import maquinaService from "../services/maquinaService";


const getAllMaquines = async (req: Request, res: Response) => {

    try {
        const allMaquines = await maquinaService.getAllMaquines();
        res.status(200).json({ status: 'OK', data: allMaquines });
    } catch (error) {
        res.status(500).json({ status: 'ERROR', data: error });
    }
};

const getOneMaquina = async (req: Request, res: Response) => {
    const { id } = req.params;

    if (!id) {
        res.status(400).json({ status: 'ERROR', data: 'No hi ha identificador donat.' });
        return;
    }

    try {
        const oneMaquina = await maquinaService.getOneMaquina(id);
        res.status(200).json({ status: 'OK', data: oneMaquina });
    } catch (error) {
        res.status(500).json({ status: 'ERROR', data: error });
    }
};

const getEstocsFromMaquina = async (req: Request, res: Response) => {
    const disponible: any = typeof(req.query.disponible) === 'string' ? true : undefined;
    const id: any = req.params.id;
    let filters: any = {};

    if (!id) {
        res.status(400).json({ status: 'ERROR', data: 'No hi ha identificador donat.' });
        return;
    }

    if (disponible) {
        filters.disponible = true;
    }

    filters.maquina = id;

    try {
        const allEstocs = await maquinaService.getEstocsFromMaquina(filters);
        res.status(200).json({ status: 'OK', data: allEstocs });
    } catch (error) {
        res.status(500).json({ status: 'ERROR', data: error });
    }
};

const getCalaixosFromMaquina = async (req: Request, res: Response) => {
    const buits: any = typeof(req.query.buits) === 'string' ? true : undefined;
    const id: any = req.params.id;
    let filters: any = {};

    if (!id) {
        res.status(400).json({ status: 'ERROR', data: 'No hi ha identificador donat.' });
        return;
    }
    
    if (buits) {
        filters.buits = true;
    }

    filters.maquina = id;

    try {
        const allCalaixos = await maquinaService.getCalaixosFromMaquina(filters);
        res.status(200).json({ status: 'OK', data: allCalaixos });
    } catch (error) {
        res.status(500).json({ status: 'ERROR', data: error });
    }
};

export default {
    getAllMaquines,
    getOneMaquina,
    getEstocsFromMaquina,
    getCalaixosFromMaquina
};