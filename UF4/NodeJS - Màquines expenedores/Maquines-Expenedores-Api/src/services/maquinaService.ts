import { Op } from "sequelize";
import { CalaixInstance } from "../model/Calaix";
import { EstocInstance } from "../model/Estoc";
import { MaquinaInstance } from "../model/Maquina";

const getAllMaquines = async () => {
    const allMaquines = await MaquinaInstance.findAll({ where: {} });
    return allMaquines;
};

const getOneMaquina = async (id: string) => {
    const oneMaquina = await MaquinaInstance.findOne({ where: { id } });
    return oneMaquina;
};

/**
 * Obtenir tots els estocs d'una màquina
 * 
 * @param any filters 
 * @return allEstocs  
 */

const getEstocsFromMaquina = async (filters: any) => {

    let allEstocs: CalaixInstance[];

    if (filters.disponible) {
        allEstocs = await CalaixInstance.findAll({
            include: [
                {
                    model: EstocInstance,
                    required: true,
                },
            ],
            where: {
                [Op.and]: [
                    { '$CalaixInstance.maquina$': filters.maquina },
                    { '$Estocs.dataVenda$': { [Op.is]: null } }
                ],
            },
        })
    } else {
        allEstocs = await CalaixInstance.findAll({
            include: [
                {
                    model: EstocInstance,
                    required: false,
                },
            ],
            where: {
                '$CalaixInstance.maquina$': filters.maquina,
            },
        });
    }

    return allEstocs;
};

/**
 * Obtenir tots els calaixos d'una màquina
 * 
 * @param any filters 
 * @return allCalaixos  
 */

const getCalaixosFromMaquina = async (filters: any) => {

    let allCalaixos: CalaixInstance[];

    if (filters.buits) {
        allCalaixos = await CalaixInstance.findAll({
            include: [
                {
                    model: EstocInstance,
                    required: false,
                },
            ],
            where: {
                [Op.and]: [
                    { '$CalaixInstance.maquina$': filters.maquina },
                    { '$Estocs.ubicacio$': { [Op.is]: null } }
                ],
            },
        });
    } else {
        allCalaixos = await CalaixInstance.findAll({ where: filters });
    }

    return allCalaixos;
};


export default {
    getAllMaquines,
    getOneMaquina,
    getEstocsFromMaquina,
    getCalaixosFromMaquina
};