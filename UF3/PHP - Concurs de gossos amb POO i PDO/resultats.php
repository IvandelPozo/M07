<?php
session_start();
require_once './utils/funcions.php';

establirSeed();
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultat votació popular Concurs Internacional de Gossos d'Atura</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <div class="wrapper large">
        <header>Resultat de la votació popular del Concurs Internacional de Gossos d'Atura 2023</header>
        <div class="results">
            <?php
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
                            echo "<h3>RESULTAT FASE " . $i . "</h3>";
                            echo "<br>";
                            foreach ($arrayConcursants as $key => $gos) {

                                $establirSeguentFase = Gos::insertarSeguentFase($gos["id"], $faseActual->numero);

                                echo '<img class="dog" alt="' . $gos["nom"] . '" title="' . $gos["nom"] . " " . $gos["percentatge"] . '%" src="' . $gos["imatge"] . '">';
                            }
                            foreach ($arrayBots as $key => $gos) {
                                if ($arrayBots[$eliminat] == $arrayBots[$key]) {
                                    $establirEliminiat = Gos::insertarEliminat($gos["id"], $faseActual->numero);
                                    echo '<img class="dog eliminat" alt="' . $gos["nom"] . '" title="' . $gos["nom"] . ' ELIMINAT" src="' . $gos["imatge"] . '">';
                                } else {

                                    $establirSeguentFase = Gos::insertarSeguentFase($gos["id"], $faseActual->numero);

                                    echo '<img class="dog" alt="' . $gos["nom"] . '" title="' . $gos["nom"] . ' 0%" src="' . $gos["imatge"] . '">';
                                }
                            }
                            echo "<br>";
                        } elseif ($duplicitatPercentatges) {
                            $eliminat = competir(count($duplicitatPercentatges) - 1);
                            echo "<h3>RESULTAT FASE " . $i . "</h3>";
                            echo "<br>";
                            foreach ($arrayConcursants as $key => $gos) {
                                if ($duplicitatPercentatges[$eliminat]["id_concursant"] == $gos["id"]) {
                                    $establirEliminiat = Gos::insertarEliminat($gos["id"], $faseActual->numero);
                                    echo '<img class="dog eliminat" alt="' . $gos["nom"] . '" title="' . $gos["nom"] . ' ELIMINAT" src="' . $gos["imatge"] . '">';
                                } else {

                                    $establirSeguentFase = Gos::insertarSeguentFase($gos["id"], $faseActual->numero);

                                    echo '<img class="dog" alt="' . $gos["nom"] . '" title="' . $gos["nom"] . " " . $gos["percentatge"] . '%" src="' . $gos["imatge"] . '">';
                                }
                            }
                            echo "<br>";
                        } elseif (empty($arrayBots)) {
                            $eliminat = competir(count($arrayConcursants) - 1);
                            echo "<h3>RESULTAT FASE " . $i . "</h3>";
                            echo "<br>";
                            foreach ($arrayConcursants as $key => $gos) {
                                if ($arrayConcursants[$eliminat] == $arrayConcursants[$key]) {
                                    $establirEliminiat = Gos::insertarEliminat($gos["id"], $faseActual->numero);
                                    echo '<img class="dog eliminat" alt="' . $gos["nom"] . '" title="' . $gos["nom"] . ' ELIMINAT" src="' . $gos["imatge"] . '">';
                                } else {

                                    $establirSeguentFase = Gos::insertarSeguentFase($gos["id"], $faseActual->numero);

                                    echo '<img class="dog" alt="' . $gos["nom"] . '" title="' . $gos["nom"] . " " . $gos["percentatge"] . '%" src="' . $gos["imatge"] . '">';
                                }
                            }
                            echo "<br>";
                        } elseif ($arrayBots !== null && !empty($arrayBots)) {
                            $eliminat = competir(count($arrayBots) - 1);
                            echo "<h3>RESULTAT FASE " . $i . "</h3>";
                            echo "<br>";
                            foreach ($arrayConcursants as $key => $gos) {

                                $establirSeguentFase = Gos::insertarSeguentFase($gos["id"], $faseActual->numero);
                                echo '<img class="dog" alt="' . $gos["nom"] . '" title="' . $gos["nom"] . " " . $gos["percentatge"] . '%" src="' . $gos["imatge"] . '">';
                            }
                            foreach ($arrayBots as $key => $gos) {
                                if ($arrayBots[$eliminat] == $arrayBots[$key]) {
                                    $establirEliminiat = Gos::insertarEliminat($gos["id"], $faseActual->numero);


                                    echo '<img class="dog eliminat" alt="' . $gos["nom"] . '" title="' . $gos["nom"] . ' ELIMINAT" src="' . $gos["imatge"] . '">';
                                } else {

                                    $establirSeguentFase = Gos::insertarSeguentFase($gos["id"], $faseActual->numero);
                                    echo '<img class="dog" alt="' . $gos["nom"] . '" title="' . $gos["nom"] . ' 0%" src="' . $gos["imatge"] . '">';
                                }
                            }
                            echo "<br>";
                        }
                    }
                }
            }

            $contadorConcursantFase = Gos::contadorConcursantsFase();

            if ($contadorConcursantFase) {

                if ($contadorConcursantFase["CONTADOR"] == 0) {
                    echo "<br>
                        <h2 id='message'>Encara no hi han resultats!</h2>";
                }
            }

            /**
             * By: 01001001 01110110 01100001 01101110
             */
            ?>
        </div>
    </div>
</body>

</html>