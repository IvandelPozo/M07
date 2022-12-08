<?php
session_start();
require_once '../classes/classeFase.php';
require_once '../utils/funcionsAjaxConcursant.php';

$faseActual = Fase::obtenirFaseActual($_SESSION["data"]);

$rebut = array();
$rebut["error"] = "";
$rebut["valid"] = "";
$rebut["taulaConcursants"] = "";
$rebut["inscripcio"] = "";

if (isset($_POST["Nom"], $_POST["Imatge"], $_POST["Amo"], $_POST["Raça"])) {
    if ($_POST["Nom"] != "" && $_POST["Imatge"] != "" && $_POST["Amo"] != "" && $_POST["Raça"] != "") {
        $Nom = $_POST["Nom"];
        $Imatge = $_POST["Imatge"];
        $Amo = $_POST["Amo"];
        $Raça = $_POST["Raça"];

        if (isset($_POST["action"])) {
            if ($_POST["action"] == "modificar") {
                if (isset($_POST["Id"])) {
                    $Id = $_POST["Id"];
                    $concursant = new Gos($Id, $Nom, $Imatge, $Amo, $Raça);

                    try {
                        if ($concursant->actualitzarConcursant()) {
                            $rebut["valid"] = "<div id='green-alert'>Concursant actualitzat amb èxit.</div>";
                        } else {
                            $rebut["error"] = "<div id='red-alert'>Error en la Base de Dades.</div>";
                        }
                    } catch (Exception $e) {
                        $rebut["error"] = "<div id='red-alert'>Error en la Base de Dades.</div>";
                    }
                }
            } elseif ($_POST["action"] == "insertar") {

                $concursant = new Gos("", $Nom, $Imatge, $Amo, $Raça);

                try {
                    if ($concursant->insertarConcursant()) {
                        $rebut["valid"] = "<div id='green-alert'>Concursant creat amb èxit.</div>";
                        $rebut["taulaConcursants"] = formulariConcursants();
                        $rebut["inscripcio"] = formulariConcursantsInscripcio();
                    } else {
                        $rebut["error"] = "<div id='red-alert'>Error en la Base de Dades.</div>";
                    }
                } catch (Exception $e) {
                    $rebut["error"] = "<div id='red-alert'>Error en la Base de Dades.</div>";
                }
            }
        }
    } else {
        $rebut["error"] = "<div id='gray-alert'>Falten camps per emplenar.</div>";
    }
}

echo (json_encode($rebut));
