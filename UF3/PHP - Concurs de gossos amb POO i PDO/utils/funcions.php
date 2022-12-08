<?php
require_once './classes/classeGos.php';
require_once './classes/classeFase.php';
require_once './classes/classeVot.php';

/** 
 * Forma el formulari de Fases de la part Administrativa
 * 
 * @return string
 * @return bool
 */
function formulariFases(): string | bool
{
    $fases = Fase::obtenirFases();

    if ($fases) {
        $html = "";
        foreach ($fases as $fase) {
            $html .= '<form id="formFase-' . $fase->numero . '">
                    <input type="hidden" value="' . $fase->numero . '" name="numero" style="width: 3em">
                    Fase <input type="text" value="' . $fase->numero . '" disabled style="width: 3em">
                    del <input type="date" value="' .  $fase->dataInici . '" name="dataInici" placeholder="Inici">
                    al <input type="date" value="' . $fase->dataFi . '" name="dataFi" placeholder="Fi">
                    <input type="button" value="Modifica" onclick="actualitzarFase(' . $fase->numero . ');">
            </form>';
        }
        return $html;
    }
    return false;
}

/**
 * Forma el formulari de Concursants de la part Administrativa
 * 
 * @return string
 * @return bool
 */
function formulariConcursants(): string | bool
{
    $gossos = Gos::obtenirConcursants();

    if ($gossos) {
        $html = "";
        foreach ($gossos as $gos) {
            $html .= '<form id="formGos-' . $gos->id . '">
                <input type="hidden" name="action" value="modificar">
                <input type="hidden" name="Id" value="' . $gos->id . '">
                <input type="text" value="' . $gos->nom . '" name="Nom" placeholder="Nom">
                <input type="text" value="' . $gos->imatge . '" name="Imatge" placeholder="Imatge">
                <input type="text" value="' . $gos->amo . '" name="Amo" placeholder="Amo">
                <input type="text" value="' . $gos->raça . '" name="Raça" placeholder="Raça">
                <input type="button" value="Modifica" onclick="actualitzarGos(' . $gos->id . ');">
            </form>';
        }
        return $html;
    }
    return false;
}


/**
 * Forma el formulari d'Inscripció de Concursants de la part Administrativa.
 * En cas de superar els 9 concursants totals, el botó d'afegir, desapareix.
 * 
 * @return string
 */
function formulariConcursantsInscripcio(): string
{
    $html = '<form id="formInscripcio">
        <input type="hidden" name="action" value="insertar">
        <input id="nomGos" type="text" name="Nom" placeholder="Nom">
        <input id="imatgeGos" type="text" name="Imatge" placeholder="Imatge">
        <input id="amoGos" type="text" name="Amo" placeholder="Amo">
        <input id="raçaGos" type="text" name="Raça" placeholder="Raça">';

    $numConcursants = Gos::contadorConcursants();

    if ($numConcursants) {
        if ($numConcursants["CONTADOR"] < 9) {
            $html .= '<input id="botoAfegirConcursant" type="button" value="Afegeix">';
        }
    }
    $html .= '</form>';

    return $html;
}

/**
 * Forma el formulari de Votació del Index depenent la fase a Votar
 * 
 * @param string $id
 * @return string
 * @return bool
 */
function formulariVotacio(string $id = ""): string | bool
{
    $canviSigne = str_replace(".", "-", $_SESSION['data']);
    $data = new Datetime($canviSigne);
    $data->modify('-1 months');
    $establirData = $data->format('Y-m-d');

    $faseActual = Fase::obtenirFaseActual($_SESSION["data"]);
    if ($faseActual->numero == 1) {
        $gossos = Gos::obtenirConcursants();
    } else {
        $gossos = Gos::obtenirConcursantsPerFase($establirData);
    }

    if ($gossos) {
        $html = "";
        foreach ($gossos as $gos) {
            $jaSeleccionat = $gos->id == $id ? "selected" : "";
            $html .= '<form id="formVot-' . $gos->id . '">
                <input type="hidden" name="numFase" value="' . $faseActual->numero . '">
                <input type="hidden" name="idConcursant" value="' . $gos->id . '">
                <input type="hidden" name="sessionId" value="' . session_id() . '">
                <input type="hidden" name="nomGos" value="' . $gos->nom . '">
                <label id="opt-' . $gos->id . '" class="' . $jaSeleccionat . ' opt-' . $gos->id . '" onclick="posarVot(' . $gos->id . ')">
                    <div class="row">
                        <div class="column">
                            <div class="right">
                                <span class="circle"></span>
                                <span class="text">' . $gos->nom . '</span>
                            </div>
                            <img class="dog" alt="' . $gos->nom . '" src="' . $gos->imatge . '">
                        </div>
                    </div>
                </label>
            </form>';
        }
        return $html;
    }
    return false;
}

/**
 * Forma el formulari de Login
 * 
 * @return string
 */
function formulariIniciSessio(): string
{
    $html = '<form action="./utils/processLogin.php" method="post">
        <h1>Inicia la sessió</h1>
        <img src="img/g1.png" alt="" height="120" width="120">
        <br>
        <span>introdueix les teves credencials</span>
        <br>
        <input type="hidden" name="method" value="signin" />
        <input type="text" name="nom" placeholder="Nom" />
        <br>
        <input type="password" name="contrasenya" placeholder="Contrasenya" />
        <br></br>
        <button type="submit">Inicia la sessió</button>
    </form>';

    return $html;
}

/**
 * Estableix el Seed dels números aleatoris a partir de la data agafada per $_GET.
 * La data per $_GET queda guardada en una SESSIÓ. En cas de no ser vàlida la data,
 * la SESSIÓ guardarà la data actual.
 * 
 * @return void
 */
function establirSeed(): void
{
    if (isset($_GET["data"])) {
        if (validarData($_GET["data"])) {
            $_SESSION["data"] = $_GET["data"];
        } else {
            $_SESSION["data"] = date("Y.m.d");
        }
    } elseif (!isset($_SESSION["data"])) {
        $_SESSION["data"] = date("Y.m.d");
    }

    srand(777888999);
}

/**
 * Valida la data establerta per $_GET.
 * 
 * @param string $data
 * @param string $format
 * @return string
 */
function validarData(string $data, string $format = 'Y.m.d'): string
{
    $d = DateTime::createFromFormat($format, $data);
    return $d && $d->format($format) === $data;
}

/**
 * Els concursants competeixen per un número aleatori per saber el concursant 
 * descartat del array.
 * 
 * @param int $numTotalCompetidors
 * @return int
 */
function competir(int $numTotalCompetidors): int
{
    return rand(0, $numTotalCompetidors);
}

/**
 * Executa els resultats al canviar de fase per data $_GET.
 * Fa que en Administració i a l'Index generin contingut de la fase modificada per $_GET.
 * 
 * @return void
 */
function canviFase(): void
{
    $eliminarGuanyadors = Gos::eliminarGuanyadors();

    for ($i = 1; $i <= 8 && Fase::fiDeFase($i, $_SESSION["data"]); $i++) {

        $faseActual = Fase::obtenirFase($i);

        if ($faseActual) {

            if ($i == 1) {
                $concursants = Gos::obtenirConcursants();
            } else {
                $concursants = Gos::obtenirConcursantsFase($i - 1);
            }

            $arrayBots = array();
            $arrayConcursants = array();
            if ($concursants) {
                foreach ($concursants as $valorsFase) {

                    $contadorVots = Vot::contadorVots($i, $valorsFase->id);
                    $obtenirPercentatge = Vot::percentatgeVots($i, $valorsFase->id);

                    if ($obtenirPercentatge) {
                        if ($obtenirPercentatge["PERCENTATGE"] !== null) {
                            $actualitzarPercentatge = Gos::insertarPercentatges($obtenirPercentatge["PERCENTATGE"], $valorsFase->id, $i);
                        }
                    }

                    foreach ($contadorVots as $key => $valorsVots) {
                        if ($valorsVots["contador"] == 0) {
                            $arrayBots[] = ["id" => $valorsFase->id, "nom" => $valorsFase->nom, "imatge" => $valorsFase->imatge, "amo" => $valorsFase->amo, "raça" => $valorsFase->raça, "numero_fase" => $i, "vots" => $valorsVots["contador"], "percentatge" => $obtenirPercentatge["PERCENTATGE"]];
                        } else {
                            $arrayConcursants[] = ["id" => $valorsFase->id, "nom" => $valorsFase->nom, "imatge" => $valorsFase->imatge, "amo" => $valorsFase->amo, "raça" => $valorsFase->raça, "numero_fase" => $i, "vots" => $valorsVots["contador"], "percentatge" => $obtenirPercentatge["PERCENTATGE"]];
                        }
                    }
                }
            }

            $duplicitatPercentatges = Gos::veureDuplicitatPercentatges($faseActual->numero);

            if ($arrayConcursants !== null) {
                if ($duplicitatPercentatges && $arrayBots !== null && !empty($arrayBots)) {
                    $eliminat = competir(count($arrayBots) - 1);

                    foreach ($arrayConcursants as $key => $gos) {

                        $establirSeguentFase = Gos::insertarSeguentFase($gos["id"], $faseActual->numero);
                    }
                    foreach ($arrayBots as $key => $gos) {
                        if ($arrayBots[$eliminat] == $arrayBots[$key]) {
                            $establirEliminiat = Gos::insertarEliminat($gos["id"], $faseActual->numero);
                        } else {

                            $establirSeguentFase = Gos::insertarSeguentFase($gos["id"], $faseActual->numero);
                        }
                    }
                } elseif ($duplicitatPercentatges) {
                    $eliminat = competir(count($duplicitatPercentatges) - 1);

                    foreach ($arrayConcursants as $key => $gos) {
                        if ($duplicitatPercentatges[$eliminat]["id_concursant"] == $gos["id"]) {
                            $establirEliminiat = Gos::insertarEliminat($gos["id"], $faseActual->numero);
                        } else {

                            $establirSeguentFase = Gos::insertarSeguentFase($gos["id"], $faseActual->numero);
                        }
                    }
                } elseif (empty($arrayBots)) {
                    $eliminat = competir(count($arrayConcursants) - 1);;
                    foreach ($arrayConcursants as $key => $gos) {
                        if ($arrayConcursants[$eliminat] == $arrayConcursants[$key]) {
                            $establirEliminiat = Gos::insertarEliminat($gos["id"], $faseActual->numero);
                        } else {

                            $establirSeguentFase = Gos::insertarSeguentFase($gos["id"], $faseActual->numero);
                        }
                    }
                } elseif ($arrayBots !== null && !empty($arrayBots)) {
                    $eliminat = competir(count($arrayBots) - 1);

                    foreach ($arrayConcursants as $key => $gos) {

                        $establirSeguentFase = Gos::insertarSeguentFase($gos["id"], $faseActual->numero);
                    }
                    foreach ($arrayBots as $key => $gos) {
                        if ($arrayBots[$eliminat] == $arrayBots[$key]) {
                            $establirEliminiat = Gos::insertarEliminat($gos["id"], $faseActual->numero);
                        } else {

                            $establirSeguentFase = Gos::insertarSeguentFase($gos["id"], $faseActual->numero);
                        }
                    }
                }
            }
        }
    }
}

/**
 * By: 01001001 01110110 01100001 01101110
 */
