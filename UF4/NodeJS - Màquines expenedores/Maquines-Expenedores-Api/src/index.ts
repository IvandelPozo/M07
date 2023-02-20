import express from "express";
import db from './config/database.config';
import v1ProducteRouter from "./v1/routes/producteRoutes";
import v1EstocRouter from "./v1/routes/estocRoutes";
import v1MaquinaRouter from "./v1/routes/maquinaRoutes";
import { MaquinaInstance } from "./model/Maquina";
import { CalaixInstance } from "./model/Calaix";
import { EstocInstance } from "./model/Estoc";
import { V1SwaggerDocs } from "./v1/swagger";

// Definició de relacions entre models.

db.beforeSync(() => {
    MaquinaInstance.hasMany(CalaixInstance, { foreignKey: 'maquina' });
    CalaixInstance.belongsTo(MaquinaInstance, { foreignKey: 'maquina' });
    CalaixInstance.hasMany(EstocInstance, { foreignKey: 'ubicacio' });
    EstocInstance.belongsTo(CalaixInstance, { foreignKey: 'ubicacio' });
});

// Connexió Database i sincronització.

db.sync().then(result => {
    console.log("Database connected");
});

const app = express();
const PORT = process.env.PORT || 3000;

app.use(express.json());

// Rutes

app.use("/api/v1/productes", v1ProducteRouter);
app.use("/api/v1/estocs", v1EstocRouter);
app.use("/api/v1/maquines", v1MaquinaRouter);

// Swagger i inici del servidor.

app.listen(PORT, () => {
    console.log(`🚀 API is listening on port ${PORT}`);
    V1SwaggerDocs.swaggerDocs(app,PORT);
});