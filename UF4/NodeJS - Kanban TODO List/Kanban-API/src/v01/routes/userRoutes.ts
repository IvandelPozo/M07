import express from "express";
import userController from "../../controllers/userController";

const router = express.Router();

router
  .get("/", userController.getAllUsers)
  .get("/:id", userController.getTasksFromUser)
  .post("/", userController.createNewUser)
  .delete("/:id", userController.deleteOneUser)

export default router;