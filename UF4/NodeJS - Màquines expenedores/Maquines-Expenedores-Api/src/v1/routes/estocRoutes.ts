import express from "express";
import estocController from "../../controllers/estocController";

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
  * 
  *   /api/v1/estocs:
  *   get:
  *     summary: Retorna tots els estocs.
  *     tags:
  *       -  Estocs
  *     description: Retorna tots els estocs.
  *     parameters:
  *       - in: query
  *         name: disponible
  *         schema:
  *           type: boolean
  *         required: false
  *         allowEmptyValue: true
  *         description: Retorna tots els estocs disponibles d'una maquina.
  *       - in: query
  *         name: venda
  *         schema:
  *           type: string
  *         required: false
  *         allowEmptyValue: true
  *         description: Retorna tots els estocs que s'han venut a la data indicada.
  *     responses:
  *       200:
 *         description: OK
 *         content:
 *           application/json:
 *             schema:
 *               $ref: '#/components/schemas/EstocInstance'
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
  .get("/", estocController.getAllEstocs)
  /**
   * @openapi
   * 
   *   /api/v1/estocs/{id}:
   *   get:
   *     summary: Retorna un estoc.
   *     tags:
   *       -  Estocs
   *     description: Retorna un estoc.
   *     parameters:
   *       - in: path
   *         name: id
   *         schema:
   *           type: string
   *         required: true
   *         description: Identificador de l'estoc.
   *     responses:
   *       200:
   *         description: OK
   *         content:
   *           application/json:
   *             schema:
   *               $ref: '#/components/schemas/EstocInstance'
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
  .get("/:id", estocController.getOneEstoc)
  /**
   * @openapi
   * 
   *  /api/v1/estocs:
   *   post:
   *     summary: Afegir un estoc.
   *     tags:
   *       -  Estocs
   *     description: Afegir un estoc.
   *     requestBody:
   *       required: true
   *       content:
   *         application/json:
   *           schema:
   *             type: object
   *             properties:
   *               producte:
   *                 type: string
   *                 description: Nom del producte.
   *                 example: Coca-Cola
   *               caducitat:
   *                 type: string
   *                 description: Data de Caducitat.
   *                 example: 2023-01-02
   *               dataVenda:
   *                 type: string
   *                 description: Data de Venda.
   *                 example: 2023-01-02
   *               ubicacio:
   *                 type: string
   *                 description: UUID del calaix al qual pertany el producte.
   *                 example: 1f131cc4-5192-4046-9f4e-0f5c1798ff06
   *     responses:
   *       201:
   *         description: OK
   *         content:
   *           application/json:
   *             schema:
   *               $ref: '#/components/schemas/EstocInstance'
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
  .post("/", estocController.createNewEstoc)
  /**
   * @openapi
   * 
   *   /api/v1/estocs/{id}:
   *   patch:
   *     summary: Modificar un estoc.
   *     tags:
   *       -  Estocs
   *     description: Modificar un estoc.
   *     parameters:
   *       - in: path
   *         name: id
   *         schema:
   *           type: string
   *         required: true
   *         description: Identificador de l'estoc.
   *     requestBody:
   *       required: true
   *       content:
   *         application/json:
   *           schema:
   *             type: object
   *             properties:
   *               producte:
   *                 type: string
   *                 description: Nom del producte.
   *                 example: Coca-Cola
   *               caducitat:
   *                 type: string
   *                 description: Data de Caducitat.
   *                 example: 2023-01-02
   *               dataVenda:
   *                 type: string
   *                 description: Data de Venda.
   *                 example: 2023-01-02
   *               ubicacio:
   *                 type: string
   *                 description: UUID del calaix al qual pertany el producte.
   *                 example: 1f131cc4-5192-4046-9f4e-0f5c1798ff06
   *     responses:
   *       200:
   *         description: OK
   *         content:
   *           application/json:
   *             schema:
   *               $ref: '#/components/schemas/EstocInstance'
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
  .patch("/:id", estocController.updateOneEstoc)
  /**
   * @openapi
   * 
   *   /api/v1/estocs/{id}:
   *   delete:
   *     summary: Esborrar un estoc.
   *     tags:
   *       -  Estocs
   *     description: Esborrar un estoc.
   *     parameters:
   *       - in: path
   *         name: estocId
   *         schema:
   *           type: string
   *         required: true
   *         description: Identificador de l'estoc.
   *     responses:
   *       200:
   *         description: OK
   *         content:
   *           application/json:
   *             schema:
   *               $ref: '#/components/schemas/EstocInstance'
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
  .delete("/:id", estocController.deleteOneEstoc);

export default router;