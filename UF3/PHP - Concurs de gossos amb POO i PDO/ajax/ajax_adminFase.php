<?php
require_once '../classes/classeFase.php';

$rebut = array();
$rebut["error"] = "";
$rebut["valid"] = "";

if (isset($_POST["numero"], $_POST["dataInici"], $_POST["dataFi"])) {
    if ($_POST["numero"] != "" && $_POST["dataInici"] != "" && $_POST["dataFi"] != "") {
        $numero = $_POST["numero"];
        $dataInici = $_POST["dataInici"];
        $dataFi = $_POST["dataFi"];

        $fase = new Fase($numero, $dataInici, $dataFi);

        try {
            if (!$fase->comprovarDataFase()) {
                $rebut["valid"] = "<div id='green-alert'>Data de la Fase <b>$numero</b> canviada.</div>";
                $fase->actualitzarDataFase();
            } else {
                $rebut["error"] = "<div id='red-alert'>Aquesta data es sobreposa amb altres!</div>";
            }
        } catch (Exception $e) {
            $rebut["error"] = "<div id='red-alert'>Error en la Base de Dades.</div>";
        }
    } else {
        $rebut["error"] = "<div id='gray-alert'>Falten camps per emplenar o les dates no són equivalents.</div>";
    }
}


echo (json_encode($rebut));
