<?php
require_once '../classes/classeVot.php';

$rebut = array();
$rebut["error"] = "";
$rebut["valid"] = "";

if (isset($_POST["action"])) {
    if ($_POST["action"] == "borrar") {
        if (isset($_POST["numFase"])) {
            if ($_POST["numFase"] != "") {
                $numFase = $_POST["numFase"];

                $votFase = new Vot($numFase, "", "");

                try {
                    if ($votFase->eliminarVotsFase()) {
                        $rebut["valid"] = "<div id='green-alert'>Vots per la fase introduïda, esborrats.</div>";
                    } else {
                        $rebut["error"] = "<div id='red-alert'>Error en la Base de Dades.</div>";
                    }
                } catch (Exception $e) {
                    $rebut["error"] = "<div id='red-alert'>Error en la Base de Dades.</div>";
                }
            } else {
                $rebut["error"] = "<div id='gray-alert'>Falten camps per emplenar.</div>";
            }
        }
    } elseif ($_POST["action"] == "borrarTots") {

        try {
            if (Vot::eliminarTotsVots()) {
                $rebut["valid"] = "<div id='green-alert'>Tots els vots de totes les fases, esborrats.</div>";
            } else {
                $rebut["error"] = "<div id='red-alert'>Error en la Base de Dades.</div>";
            }
        } catch (Exception $e) {
            $rebut["error"] = "<div id='red-alert'>Error en la Base de Dades.</div>";
        }
    }
}

echo (json_encode($rebut));
