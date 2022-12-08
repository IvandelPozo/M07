<?php
session_start();
require_once("../classes/classeUsuari.php");
$status = "error"; // per defecte error genèric

// Resposta al formulari de SIGNIN
if (isset($_POST["method"]) && $_POST["method"] == "signin") {

    $nom = $_POST["nom"];
    $data = Usuari::trobarUsuari($nom);

    if ($data === false) { // Comprova si existeix l'usuari
        $status = "signin_user_error";
    } elseif ($data->contrasenya != md5($_POST["contrasenya"])) { // Comprova la contrasenya
        $status = "signin_password_error";
    } else { // Tot correcte
        $status = "signin_success";
        $_SESSION["usuariAdmin"] = $data->nom;
    }
}

// Redireciona PGR a on toqui
if (str_contains($status, "success")) {
    header("Location: ../admin.php"); // per defecte 302
} else {
    header("Location: ../login.php?error=$status", true, 303);
}

/**
 * By: 01001001 01110110 01100001 01101110
 */
