import express from "express";
import db from './config/database.config';
import v01UserRouter from './v01/routes/userRoutes';
import v01TaskRouter from './v01/routes/taskRoutes';
import { TaskInstance } from "./model/Task";
import { UserInstance } from "./model/User";

// Definició de relacions entre models.

db.beforeSync(() => {
    UserInstance.hasMany(TaskInstance, { foreignKey: 'user' });
    TaskInstance.belongsTo(UserInstance, { foreignKey: 'user' });
});


// Connexió Database i sincronització.

db.sync().then(result => {
    console.log("Database connected");
});

const app = express();
const PORT = process.env.PORT || 3000;

app.use(express.json());

// Rutes

app.use("/api/v01/users", v01UserRouter);
app.use("/api/v01/tasks", v01TaskRouter);

// Inici del servidor.

app.listen(PORT, () => {
    console.log(`🚀 API is listening on port ${PORT}`);
});