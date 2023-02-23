import express from "express";
import taskController from "../../controllers/taskController";

const router = express.Router();

router
  .get("/:id", taskController.getOneTask)
  .post("/", taskController.createNewTask)
  .patch("/:id", taskController.updateOneTask)
  .delete("/:id", taskController.deleteOneTask)

export default router;