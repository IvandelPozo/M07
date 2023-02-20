import { EstocInstance } from "../model/Estoc";
import { ProducteInstance } from "../model/Producte";

const getAllProductes = async () => {
    const allProductes = await ProducteInstance.findAll({ where: {} });
    return allProductes;
};

const getOneProducte = async (id: string) => {
    const oneProducte = await ProducteInstance.findOne({ where: { id } });
    return oneProducte;
};

const getEstocsFromProducte = async (filters:any) => {
    const allEstocs = await EstocInstance.findAll({ where: filters });
    return allEstocs;
};

const createNewProducte = async (newProducte: any) => {
    const producteToInsert = {
        ...newProducte
    };

    const createdProducte = await ProducteInstance.create(producteToInsert);
    return createdProducte;
};

const updateOneProducte = async (changes: any, id: string) => {
    const updatedProducte = await ProducteInstance.update(changes, { where: { id } });
    return updatedProducte;
};

const deleteOneProducte = async (id: string) => {
   const deletedProducte = await ProducteInstance.destroy({ where: { id } });
   return deletedProducte;
};

export default {
    getAllProductes,
    getOneProducte,
    getEstocsFromProducte,
    createNewProducte,
    updateOneProducte,
    deleteOneProducte
};