import express from "express";
import maquinaController from "../../controllers/maquinaController";

/**
 * @openapi
 * components:
 *   schemas:
 *     CalaixInstance:
 *       type: object
 *       properties:
 *         id:
 *           type: string
 *           format: uuid
 *           primaryKey: true
 *           allowNull: false
 *         maquina:
 *           type: string
 *           format: uuid
 *           allowNull: false
 *           $ref: '#/components/schemas/MaquinaInstance'
 *         casella:
 *           type: string
 *           allowNull: false
 *       required:
 *         - id
 *         - maquina
 *         - casella
 *       name:
 *         singular: 'calaixos'
 *         plural: 'calaixos'
 *     CategoriaInstance:
 *       type: object
 *       properties:
 *         id:
 *           type: string
 *           format: uuid
 *           primaryKey: true
 *           allowNull: false
 *         nom:
 *           type: string
 *           allowNull: false
 *         iva:
 *           type: integer
 *           allowNull: false
 *       required:
 *         - id
 *         - nom
 *         - iva
 *       name:
 *         singular: 'categories'
 *         plural: 'categories'
 *     EstocInstance:
 *       type: object
 *       properties:
 *         id:
 *           type: string
 *           format: uuid
 *           primaryKey: true
 *           allowNull: false
 *         producte:
 *           type: string
 *           format: uuid
 *           allowNull: false
 *           $ref: '#/components/schemas/ProducteInstance'
 *         caducitat:
 *           type: string
 *           format: date
 *           allowNull: false
 *         dataVenda:
 *           type: string
 *           format: date
 *           allowNull: true
 *           default: null
 *         ubicacio:
 *           type: string
 *           format: uuid
 *           allowNull: false
 *           $ref: '#/components/schemas/CalaixInstance'
 *       required:
 *         - id
 *         - producte
 *         - caducitat
 *         - ubicacio
 *       name:
 *         singular: 'estocs'
 *         plural: 'estocs'
 *     MaquinaInstance:
 *       type: object
 *       properties:
 *         id:
 *           type: string
 *           format: uuid
 *           primaryKey: true
 *           allowNull: false
 *         municipi:
 *           type: string
 *           allowNull: false
 *         adreça:
 *           type: string
 *           allowNull: false
 *       required:
 *         - id
 *         - municipi
 *         - adreça
 *       name:
 *         singular: 'maquines'
 *         plural: 'maquines'
 *     ProducteInstance:
 *       type: object
 *       properties:
 *         id:
 *           type: string
 *           format: uuid
 *           primaryKey: true
 *           allowNull: false
 *         nom:
 *           type: string
 *           allowNull: false
 *         tipus:
 *           type: string
 *           allowNull: false
 *         preu:
 *           type: number
 *           format: float
 *           allowNull: false
 *         categoria:
 *           type: string
 *           format: uuid
 *           allowNull: false
 *           $ref: '#/components/schemas/CategoriaInstance'
 *       required:
 *         - id
 *         - nom
 *         - tipus
 *         - preu
 *         - categoria
 *       name:
 *         singular: 'productes'
 *         plural: 'productes'
*/

const router = express.Router();

router
/**
 * @openapi
 * /api/v1/maquines:
 *   get:
 *     summary: Retorna totes les màquines.
 *     tags:
 *       - Màquines
 *     description: Retorna totes les màquines.
 *     responses:
 *       200:
 *         description: OK
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 status:
 *                   type: string
 *                   example: OK
 *                 maquines:
 *                   type: array
 *                   items:
 *                     $ref: '#/components/schemas/Maquina'
 *       500:
 *         description: FAILED
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 status: 
 *                   type: string
 *                   example: FAILED
 *                 data:
 *                   type: object
 *                   properties:
 *                     error:
 *                       type: string 
 *                       example: "Error d'API"
 */
  .get("/", maquinaController.getAllMaquines)
/**
 * @openapi
 * /api/v1/maquines/{id}:
 *   get:
 *     summary: Retorna una màquina.
 *     tags:
 *       - Màquines
 *     description: Retorna una màquina.
 *     parameters:
 *       - in: path
 *         name: id
 *         schema:
 *           type: string
 *           format: uuid
 *         required: true
 *         description: Identificador de la màquina.
 *     responses:
 *       200:
 *         description: OK
 *         content:
 *           application/json:
 *             schema:
 *               $ref: '#/components/schemas/Maquina'
 *       400:
 *         description: No hi ha identificador donat.
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 status: 
 *                   type: string
 *                   example: FAILED
 *                 message:
 *                   type: string
 *                   example: No hi ha identificador donat.
 *       500:
 *         description: FAILED
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 status: 
 *                   type: string
 *                   example: FAILED
 *                 data:
 *                   type: object
 *                   properties:
 *                     error:
 *                       type: string 
 *                       example: "Error d'API"
 */
  .get("/:id", maquinaController.getOneMaquina)
  /**
   * @openapi
 * /api/v1/maquines/{id}/estocs:
 *   get:
 *     summary: Retorna tots els estocs que es troben una màquina concreta(tant els que s'han venut com els que no).
 *     tags:
 *       - Màquines
 *     description: Retorna tots els estocs que es troben una màquina concreta(tant els que s'han venut com els que no).
 *     parameters:
 *       - in: path
 *         name: id
 *         schema:
 *           type: string
 *           format: uuid
 *         required: true
 *         description: Identificador de la màquina
 *       - in: query
 *         name: disponibles
 *         schema:
 *           type: boolean
 *         required: false
 *         allowEmptyValue: true
 *         description: Retorna tots els estocs que no s'han venut i es troben a una màquina concreta.
 *     responses:
 *       200:
 *         description: OK
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 status:
 *                   type: string
 *                   example: OK
 *                 data:
 *                   type: array
 *                   items:
 *                     $ref: '#/components/schemas/Maquina'
 *       400:
 *         description: Insereix producte, caducitat i ubicació.
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 status:
 *                   type: string
 *                   example: FAILED
 *                 message:
 *                   type: string
 *                   example: Insereix producte, caducitat i ubicació.
 *       500:
 *         description: FAILED
 *         content:
 *           application/json:
 *             schema:
 *               type: object
 *               properties:
 *                 status:
 *                   type: string
 *                   example: FAILED
 *                 data:
 *                   type: object
 *                   properties:
 *                     error:
 *                       type: string
 *                       example: "Error d'API"
   */
  .get("/:id/estocs", maquinaController.getEstocsFromMaquina)
  /**
   * @openapi
   * /api/v1/maquines/{id}/calaixos:
   *   get:
 *     summary: Retorna la llista de calaixos d'una màquina.
   *     tags:
   *       - Màquines
   *     description: Retorna la llista de calaixos d'una màquina.
   *     parameters:
   *       - in: path
   *         name: id
   *         schema:
   *           type: string
   *           format: uuid
   *         required: true
   *         description: Identificador de la màquina.
   *       - in: query
   *         name: disponibles
   *         schema:
   *           type: boolean
   *         required: false
   *         allowEmptyValue: true
   *         description: Retorna tots els calaixos buits d'una màquina.
   *     responses:
   *       200:
   *         description: OK
   *         content:
   *           application/json:
   *             schema:
   *               type: object
   *               properties:
   *                 status:
   *                   type: string
   *                   example: OK
   *                 data:
   *                   type: array
   *                   items:
   *                     $ref: '#/components/schemas/Maquina'
   *       400:
   *         description: No hi ha identificador donat.
   *         content:
   *           application/json:
   *             schema:
   *               type: object
   *               properties:
   *                 status: 
   *                   type: string
   *                   example: FAILED
   *                 message:
   *                   type: string
   *                   example: No hi ha identificador donat.
   *       500:
   *         description: FAILED
   *         content:
   *           application/json:
   *             schema:
   *               type: object
   *               properties:
   *                 status: 
   *                   type: string
   *                   example: FAILED
   *                 data:
   *                   type: object
   *                   properties:
   *                     error:
   *                       type: string 
   *                       example: "Error d'API"
   */
  .get("/:id/calaixos", maquinaController.getCalaixosFromMaquina)

export default router;