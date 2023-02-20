import express from "express";
import producteController from "../../controllers/producteController";

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
   * /api/v1/productes:
   *   get:
   *     summary: Retorna tots els productes
   *     tags:
   *       - Productes
   *     description: Retorna tots els productes
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
   *                 productes:
   *                   type: array
   *                   items:
   *                     $ref: '#/components/schemas/ProducteInstance'
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
  .get("/", producteController.getAllProductes)
    /**
   * @openapi
   * /api/v1/productes/{id}:
   *   get:
   *     summary: Retorna un producte.
   *     tags:
   *       - Productes
   *     description: Retorna un producte.
   *     parameters:
   *       - in: path
   *         name: id
   *         schema:
   *           type: string
   *           format: uuid
   *         required: true
   *         description: Identificador del producte.
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
   *                 productes:
   *                   type: array
   *                   items:
   *                     $ref: '#/components/schemas/ProducteInstance'
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
  .get("/:id", producteController.getOneProducte)
    /**
   * @openapi
   * /api/v1/productes/{id}/estocs:
   *   get:
   *     summary: Retorna tots els estocs d'un producte.
   *     tags:
   *       - Productes
   *     description: Retorna tots els estocs d'un producte.
   *     parameters:
   *       - in: path
   *         name: id
   *         schema:
   *           type: string
   *           format: uuid
   *         required: true
   *         description: Identificador del producte.
   *       - in: query
   *         name: disponibles
   *         schema:
   *           type: boolean
   *         required: false
   *         allowEmptyValue: true
   *         description: Retorna tots els estocs disponibles d'un producte.
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
   *                 productes:
   *                   type: array
   *                   items:
   *                     $ref: '#/components/schemas/ProducteInstance'
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
  .get("/:id/estocs", producteController.getEstocsFromProducte)
  /**
   * @openapi
   * /api/v1/productes:
   *   post:
   *     summary: Afegir un producte.
   *     tags:
   *       - Productes
   *     description: Afegir un producte.
   *     requestBody:
   *       required: true
   *       content:
   *         application/json:
   *           schema:
   *             type: object
   *             properties:
   *               nom:
   *                 type: string
   *                 description: Nom del producte.
   *                 example: Fanta
   *               tipus:
   *                 type: string
   *                 description: Tipus de producte.
   *                 example: Aliment
   *               preu:
   *                 type: number
   *                 format: float
   *                 description: Preu del producte.
   *                 example: 1.5
   *               categoria:
   *                 type: string
   *                 description: UUID de la categoria a la qual pertany el producte.
   *                 example: f7ad7d25-4c1f-4aed-916b-e5b13c87acc3
   *     responses:
   *       '201':
   *         description: Producte creat.
   *         content:
   *           application/json:
   *             schema:
   *               type: object
   *               properties:
   *                 status:
   *                   type: string
   *                   description: Estat de la resposta
   *                   example: OK
   *                 data:
   *                   $ref: '#/components/schemas/ProducteInstance'
   *                   description: Dades del producte creat
   *       '400':
   *         description: Dades del producte incompletes o incorrectes.
   *         content:
   *           application/json:
   *             schema:
   *               type: object
   *               properties:
   *                 status:
   *                   type: string
   *                   description: Estat de la resposta
   *                   example: ERROR
   *                 data:
   *                   type: string
   *                   description: Missatge d'error
   *                   example: Insereix nom, tipus, preu i categoria.
   *       '500':
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
  .post("/", producteController.createNewProducte)
  /**
   * @openapi
   * /api/v1/productes/{id}:
   *   patch:
   *     summary: Modificar un producte.
   *     tags:
   *       - Productes
   *     description: Modificar un producte.
   *     parameters:
   *       - in: path
   *         name: id
   *         schema:
   *           type: string
   *           format: uuid
   *         required: true
   *         description: Identificador del producte.
   *     requestBody:
   *       required: true
   *       content:
   *         application/json:
   *           schema:
   *             type: object
   *             properties:
   *               nom:
   *                 type: string
   *                 description: Nom del producte.
   *                 example: Fanta
   *               tipus:
   *                 type: string
   *                 description: Tipus de producte.
   *                 example: Aliment
   *               preu:
   *                 type: number
   *                 format: float
   *                 description: Preu del producte.
   *                 example: 1.5
   *               categoria:
   *                 type: string
   *                 description: UUID de la categoria a la qual pertany el producte.
   *                 example: f7ad7d25-4c1f-4aed-916b-e5b13c87acc3
   *     responses:
   *       '200':
   *         description: Producte actualitzat.
   *         content:
   *           application/json:
   *             schema:
   *               type: object
   *               properties:
   *                 status:
   *                   type: string
   *                   description: Estat de la resposta
   *                   example: OK
   *                 data:
   *                   $ref: '#/components/schemas/ProducteInstance'
   *                   description: Dades del producte actualitzat
   *       '400':
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
   *       '500':
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
  .patch("/:id", producteController.updateOneProducte)
  /**
   * @openapi
   * /api/v1/productes/{id}:
   *   delete:
   *     summary: Esborrar un producte.
   *     tags:
   *       - Productes
   *     description: Esborrar un producte.
   *     parameters:
   *       - in: path
   *         name: id
   *         schema:
   *           type: string
   *           format: uuid
   *         required: true
   *         description: Identificador del producte.
   *     responses:
   *       '200':
   *         description: Producte eliminat.
   *         content:
   *           application/json:
   *             schema:
   *               $ref: '#/components/schemas/Producte'
   *       '400':
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
   *       '500':
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
  .delete("/:id", producteController.deleteOneProducte);

export default router;