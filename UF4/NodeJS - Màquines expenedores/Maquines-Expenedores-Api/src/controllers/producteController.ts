import { Request, Response } from "express";
import { Op } from "sequelize";
import { v4 as uuid } from "uuid";
import producteService from "../services/producteService";

const getAllProductes = async (req: Request, res: Response) => {

  try {
    const allProductes = await producteService.getAllProductes();
    res.status(200).json({ status: 'OK', data: allProductes });
  } catch (error) {
    res.status(500).json({ status: 'ERROR', data: error });
  }
};

const getOneProducte = async (req: Request, res: Response) => {
  const { id } = req.params;

  if(!id) {
    res.status(400).json({ status: 'ERROR', data: 'No hi ha identificador donat.' });
    return;
  }

  try {
    const oneProducte = await producteService.getOneProducte(id);
    res.status(200).json({ status: 'OK', data: oneProducte });
  } catch (error) {
    res.status(500).json({ status: 'ERROR', data: error });
  }
};

const getEstocsFromProducte = async (req: Request, res: Response) => {
  const disponible: any = typeof(req.query.disponible) === 'string' ? true : undefined;
  const id: any = req.params.id;
  let filters: any = {};

  if(!id) {
    res.status(400).json({ status: 'ERROR', data: 'No hi ha identificador donat.' });
    return;
  }

  if (disponible) {
    filters = { dataVenda: { [Op.is]: null } };
  }

  filters.producte = id;

  try {
    const allEstocs = await producteService.getEstocsFromProducte(filters);
    res.status(200).json({ status: 'OK', data: allEstocs });
  } catch (error) {
    res.status(500).json({ status: 'ERROR', data: error });
  }
};

const createNewProducte = async (req: Request, res: Response) => {
  const { body } = req;

  if (!body.nom || !body.tipus || !body.preu || !body.categoria) {
    res.status(400).json({ status: 'ERROR', data: 'Insereix nom, tipus, preu i categoria.' });
    return;
  }

  const newProducte = {
    id: uuid(),
    nom: body.nom,
    tipus: body.tipus,
    preu: body.preu,
    categoria: body.categoria
  };

  try {
    const createdProducte = await producteService.createNewProducte(newProducte);
    res.status(201).json({ status: 'OK', data: createdProducte });
  } catch (error) {
    res.status(500).json({ status: 'ERROR', data: error });
  }
};

const updateOneProducte = async (req: Request, res: Response) => {
  const {
    body,
    params: { id },
  } = req;

  if (!id) {
    res.status(400).json({ status: 'ERROR', data: 'No hi ha identificador donat.' });
    return;
  }

  try {
    const updatedProducte = await producteService.updateOneProducte(body, id);
    res.status(200).json({ status: 'OK', data: updatedProducte });
  } catch (error) {
    res.status(500).json({ status: 'ERROR', data: error });
  }
};

const deleteOneProducte = async (req: Request, res: Response) => {

  const {
    params: { id },
  } = req;

  if (!id) {
    res.status(400).json({ status: 'ERROR', data: 'No hi ha identificador donat.' });
    return;
  }

  try {
    const deletedProducte = await producteService.deleteOneProducte(id);
    res.status(200).json({ status: 'OK', data: deletedProducte });
  } catch (error) {
    res.status(500).json({ status: 'ERROR', data: error });
  }
};

export default {
  getAllProductes,
  getOneProducte,
  getEstocsFromProducte,
  createNewProducte,
  updateOneProducte,
  deleteOneProducte
};