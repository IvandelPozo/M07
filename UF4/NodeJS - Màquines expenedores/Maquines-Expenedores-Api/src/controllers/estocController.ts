import { Request, Response } from "express";
import { Op } from "sequelize";
import { v4 as uuid } from "uuid";
import estocService from "../services/estocService";

const getAllEstocs = async (req: Request, res: Response) => {
  const venda: any = req.query.venda;
  const disponible: any = typeof(req.query.disponible) === 'string' ? true : undefined;
  let filters = {};

  if (venda) {
    const data = new Date(venda);
    filters = { dataVenda: data };
  } else if (disponible) {
    filters = { dataVenda: { [Op.is]: null } };
  }

  try {
    const allEstocs = await estocService.getAllEstocs(filters);
    res.status(200).json({ status: 'OK', data: allEstocs });
  } catch (error) {
    res.status(500).json({ status: 'ERROR', data: error });
  }
};

const getOneEstoc = async (req: Request, res: Response) => {
  const { id } = req.params;

  if (!id) {
    res.status(400).json({ status: 'ERROR', data: 'No hi ha identificador donat.' });
    return;
  }

  try {
    const oneEstoc = await estocService.getOneEstoc(id);
    res.status(200).json({ status: 'OK', data: oneEstoc });
  } catch (error) {
    res.status(500).json({ status: 'ERROR', data: error });
  }
};

const createNewEstoc = async (req: Request, res: Response) => {
  const { body } = req;

  if (!body.producte || !body.caducitat || !body.ubicacio) {
    res.status(400).json({ status: 'ERROR', data: 'Insereix producte, caducitat i ubicació.' });
    return;
  }

  const newEstoc = {
    id: uuid(),
    producte: body.producte,
    caducitat: body.caducitat,
    ubicacio: body.ubicacio
  };

  try {
    const createdEstoc = await estocService.createNewEstoc(newEstoc);
    res.status(201).json({ status: 'OK', data: createdEstoc });
  } catch (error) {
    res.status(500).json({ status: 'ERROR', data: error });
  }
};

const updateOneEstoc = async (req: Request, res: Response) => {
  const {
    body,
    params: { id },
  } = req;

  if (!id) {
    res.status(400).json({ status: 'ERROR', data: 'No hi ha identificador donat.' });
    return;
  }

  try {
    const updatedEstoc = await estocService.updateOneEstoc(body, id);
    res.status(200).json({ status: 'OK', data: updatedEstoc });
  } catch (error) {
    res.status(500).json({ status: 'ERROR', data: error });
  }
};

const deleteOneEstoc = async (req: Request, res: Response) => {
  const {
    params: { id },
  } = req;

  if (!id) {
    res.status(400).json({ status: 'ERROR', data: 'No hi ha identificador donat.' });
    return;
  }

  try {
    const deletedEstoc = await estocService.deleteOneEstoc(id);
    res.status(200).json({ status: 'OK', data: deletedEstoc });
  } catch (error) {
    res.status(500).json({ status: 'ERROR', data: error });
  }
};

export default {
  getAllEstocs,
  getOneEstoc,
  createNewEstoc,
  updateOneEstoc,
  deleteOneEstoc
};