<?php
require_once '../classes/classeVot.php';

$rebut = array();
$rebut["error"] = "";
$rebut["valid"] = "";

if (isset($_POST["numFase"], $_POST["idConcursant"], $_POST["sessionId"], $_POST["nomGos"])) {

    $numFase = $_POST["numFase"];
    $idConcursant = $_POST["idConcursant"];
    $sessionId = $_POST["sessionId"];
    $nomGos = $_POST["nomGos"];

    $votFase = new Vot($numFase, $idConcursant, $sessionId);

    try {
        if ($votFase->establirVot()) {
            $rebut["valid"] = "<div id='green-alert'>Ja has votat al gos <b>" . $nomGos . "</b>. Es modificarà la teva resposta.</div>";
        } else {
            $rebut["error"] = "<div id='red-alert'>Error en la Base de Dades.</div>";
        }
    } catch (Exception $e) {
        $rebut["error"] = "<div id='red-alert'>Error en la Base de Dades.</div>";
    }
}

echo (json_encode($rebut));
