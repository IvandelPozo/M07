import { EstocInstance } from "../model/Estoc";

const getAllEstocs = async (filter: any) => {
    const allEstocs = await EstocInstance.findAll({ where: filter });
    return allEstocs;
};

const getOneEstoc = async (id: string) => {
    const oneEstoc = await EstocInstance.findOne({ where: { id } });
    return oneEstoc;
};

const createNewEstoc = async (newEstoc: any) => {
    const estocToInsert = {
        ...newEstoc
    };

    const createdEstoc = await EstocInstance.create(estocToInsert);
    return createdEstoc;
};

const updateOneEstoc = async (changes: any, id: string) => {
    const updatedEstoc = await EstocInstance.update(changes, { where: { id } });
    return updatedEstoc;
};

const deleteOneEstoc = async (id: string) => {
   const deletedEstoc = await EstocInstance.destroy({ where: { id } });
   return deletedEstoc;
};

export default {
    getAllEstocs,
    getOneEstoc,
    createNewEstoc,
    updateOneEstoc,
    deleteOneEstoc
};