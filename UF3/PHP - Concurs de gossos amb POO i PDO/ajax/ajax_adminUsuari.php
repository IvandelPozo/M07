<?php
require_once '../classes/classeUsuari.php';

$rebut = array();
$rebut["error"] = "";
$rebut["valid"] = "";

if (isset($_POST["nom"], $_POST["contrasenya"])) {
    if ($_POST["nom"] != "" && $_POST["contrasenya"] != "") {

        $usuari = new Usuari($_POST["nom"], $_POST["contrasenya"]);

        try {
            if ($usuari->insertarUsuari()) {
                $rebut["valid"] = "<div id='green-alert'>Usuari creat amb èxit.</div>";
            } else {
                $rebut["error"] = "<div id='red-alert'>Error en la Base de Dades.</div>";
            }
        } catch (Exception $e) {
            $rebut["error"] = "<div id='red-alert'>Usuari ja creat!</div>";
        }
    } else {
        $rebut["error"] = "<div id='gray-alert'>Falten camps per emplenar.</div>";
    }
}

echo (json_encode($rebut));
