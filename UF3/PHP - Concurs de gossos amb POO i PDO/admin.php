<?php
session_start();
require_once './utils/funcions.php';

// Redirecció per sessió no activa
if (!isset($_SESSION["usuariAdmin"])) {
    header("Location: login.php?error=timeout", true, 303);
}

establirSeed();
canviFase();
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN - Concurs Internacional de Gossos d'Atura</title>
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <div class="wrapper medium">
        <header>ADMINISTRADOR - Concurs Internacional de Gossos d'Atura</header>
        <div class="admin">
            <div class="admin-row">
                <div class="gossos">
                    <?php

                    // Resultats parcials

                    $faseActual = Fase::obtenirFaseActual($_SESSION["data"]);

                    if ($faseActual) {

                        if ($faseActual->numero == 1) {
                            $concursants = Gos::obtenirConcursants();
                        } else {
                            $concursants = Gos::obtenirConcursantsFase($faseActual->numero - 1);
                        }

                        $arrayBots = array();
                        $arrayConcursants = array();
                        if ($concursants) {
                            foreach ($concursants as $valorsFase) {

                                $contadorVots = Vot::contadorVots($faseActual->numero, $valorsFase->id);
                                $obtenirPercentatge = Vot::percentatgeVots($faseActual->numero, $valorsFase->id);

                                if ($obtenirPercentatge) {
                                    if ($obtenirPercentatge["PERCENTATGE"] !== null) {
                                        $actualitzarPercentatge = Gos::insertarPercentatges($obtenirPercentatge["PERCENTATGE"], $valorsFase->id, $faseActual->numero);
                                    }
                                }

                                foreach ($contadorVots as $key => $valorsVots) {
                                    if ($valorsVots["contador"] == 0) {
                                        $arrayBots[] = ["id" => $valorsFase->id, "nom" => $valorsFase->nom, "imatge" => $valorsFase->imatge, "amo" => $valorsFase->amo, "raça" => $valorsFase->raça, "numero_fase" => $faseActual->numero, "vots" => $valorsVots["contador"], "percentatge" => $obtenirPercentatge["PERCENTATGE"]];
                                    } else {
                                        $arrayConcursants[] = ["id" => $valorsFase->id, "nom" => $valorsFase->nom, "imatge" => $valorsFase->imatge, "amo" => $valorsFase->amo, "raça" => $valorsFase->raça, "numero_fase" => $faseActual->numero, "vots" => $valorsVots["contador"], "percentatge" => $obtenirPercentatge["PERCENTATGE"]];
                                    }
                                }
                            }
                        }

                        $duplicitatPercentatges = Gos::veureDuplicitatPercentatges($faseActual->numero);

                        if ($arrayConcursants !== null) {
                            if ($duplicitatPercentatges && $arrayBots !== null && !empty($arrayBots)) {
                                $eliminat = competir(count($arrayBots) - 1);
                                echo "<h3>RESULTAT PARCIAL: FASE " . $faseActual->numero . "</h3>";
                                echo "<br>";
                                foreach ($arrayConcursants as $key => $gos) {
                                    echo '<img class="dog" alt="' . $gos["nom"] . '" title="' . $gos["nom"] . " " . $gos["percentatge"] . '%" src="' . $gos["imatge"] . '">';
                                }
                                foreach ($arrayBots as $key => $gos) {
                                    if ($arrayBots[$eliminat] == $arrayBots[$key]) {
                                        echo '<img class="dog eliminat" alt="' . $gos["nom"] . '" title="' . $gos["nom"] . ' ELIMINAT" src="' . $gos["imatge"] . '">';
                                    } else {
                                        echo '<img class="dog" alt="' . $gos["nom"] . '" title="' . $gos["nom"] . ' 0%" src="' . $gos["imatge"] . '">';
                                    }
                                }
                                echo "<br>";
                            } elseif ($duplicitatPercentatges) {
                                $eliminat = competir(count($duplicitatPercentatges) - 1);
                                echo "<h3>RESULTAT PARCIAL: FASE " . $faseActual->numero . "</h3>";
                                echo "<br>";
                                foreach ($arrayConcursants as $key => $gos) {
                                    if ($duplicitatPercentatges[$eliminat]["id_concursant"] == $gos["id"]) {
                                        echo '<img class="dog eliminat" alt="' . $gos["nom"] . '" title="' . $gos["nom"] . ' ELIMINAT" src="' . $gos["imatge"] . '">';
                                    } else {
                                        echo '<img class="dog" alt="' . $gos["nom"] . '" title="' . $gos["nom"] . " " . $gos["percentatge"] . '%" src="' . $gos["imatge"] . '">';
                                    }
                                }
                                echo "<br>";
                            } elseif (empty($arrayBots)) {
                                $eliminat = competir(count($arrayConcursants) - 1);
                                echo "<h3>RESULTAT PARCIAL: FASE " . $faseActual->numero . "</h3>";
                                echo "<br>";
                                foreach ($arrayConcursants as $key => $gos) {
                                    if ($arrayConcursants[$eliminat] == $arrayConcursants[$key]) {

                                        echo '<img class="dog eliminat" alt="' . $gos["nom"] . '" title="' . $gos["nom"] . ' ELIMINAT" src="' . $gos["imatge"] . '">';
                                    } else {
                                        echo '<img class="dog" alt="' . $gos["nom"] . '" title="' . $gos["nom"] . " " . $gos["percentatge"] . '%" src="' . $gos["imatge"] . '">';
                                    }
                                }
                                echo "<br>";
                            } elseif ($arrayBots !== null && !empty($arrayBots)) {
                                $eliminat = competir(count($arrayBots) - 1);
                                echo "<h3>RESULTAT PARCIAL: FASE " . $faseActual->numero . "</h3>";
                                echo "<br>";
                                foreach ($arrayConcursants as $key => $gos) {
                                    echo '<img class="dog" alt="' . $gos["nom"] . '" title="' . $gos["nom"] . " " . $gos["percentatge"] . '%" src="' . $gos["imatge"] . '">';
                                }
                                foreach ($arrayBots as $key => $gos) {
                                    if ($arrayBots[$eliminat] == $arrayBots[$key]) {
                                        echo '<img class="dog eliminat" alt="' . $gos["nom"] . '" title="' . $gos["nom"] . ' ELIMINAT" src="' . $gos["imatge"] . '">';
                                    } else {
                                        echo '<img class="dog" alt="' . $gos["nom"] . '" title="' . $gos["nom"] . ' 0%" src="' . $gos["imatge"] . '">';
                                    }
                                }
                                echo "<br>";
                            }
                        }
                    } else {
                        echo "<h4 id='message'>No hi han vots parcials per mostrar</h4>";
                    }

                    /**
                     * By: 01001001 01110110 01100001 01101110
                     */
                    ?>
                </div>
            </div>
            <div class="admin-row">
                <h1> Nou usuari: </h1>
                <form>
                    <input id="nomUsuari" type="text" placeholder="Nom">
                    <input id="contrasenya" type="password" placeholder="Contrasenya">
                    <input id="botoCrearUsuari" type="button" value="Crea usuari">
                </form>
                <br>
                <div id="resultatUsuari"></div>
            </div>
            <div class="admin-row">
                <h1> Fases: </h1>
                <?php
                echo "<div id=\"formulariFases\">
                        " . formulariFases();
                "
                           </div>";
                ?>
                <br>
                <div id="mostrarFases"></div>
            </div>

            <div class="admin-row">
                <h1> Concursants: </h1>
                <?php
                echo "<div id=\"formulariConcursants\">
                        " . formulariConcursants();
                "
                    </div>";
                ?>
                <br>
                <div id="mostrarConcursants"></div>
            </div>
            <div class="admin-row">
                <h1> Inscripció de Concursants: </h1>
                <?php
                echo "<div id=\"formulariConcursantsInscripcio\">
                        " . formulariConcursantsInscripcio() .
                    "
                    </div>";
                ?>
                <br>
                <div id="mostrarInscripcioConcursants"></div>
            </div>

            <div class="admin-row">
                <h1> Altres operacions: </h1>
                <form id="formBorrarVots">
                    Esborra els vots de la fase
                    <input type="hidden" name="action" value="borrar">
                    <input id="numFase" type="number" placeholder="Fase" min="1" name="numFase">
                    <input id="botoEsborrarVotsFase" type="button" value="Esborra">
                </form>
                <br>
                <div id="mostrarVotFase"></div>
                <br>
                <form id="formBorrarTotsVots">
                    Esborra tots els vots
                    <input type="hidden" name="action" value="borrarTots">
                    <input id="botoEsborrarTotsVots" type="button" value="Esborra">
                </form>
                <br>
                <div id="mostrarTotsVotFase"></div>
            </div>
        </div>
    </div>
    <script>
        const botoCrearUsuari = document.getElementById("botoCrearUsuari");
        const botoAfegirConcursant = document.getElementById("botoAfegirConcursant");
        const botoEsborrarVotsFase = document.getElementById("botoEsborrarVotsFase");
        const botoEsborrarTotsVots = document.getElementById("botoEsborrarTotsVots");

        botoCrearUsuari.addEventListener('click', () => {
            let nom = document.getElementById("nomUsuari");
            let contrasenya = document.getElementById("contrasenya");

            $.ajax({
                type: "POST",
                url: "./ajax/ajax_adminUsuari.php",
                dataType: "text",
                data: {
                    nom: nom.value,
                    contrasenya: contrasenya.value
                },
                success: function(response) {
                    console.log("DADES ENVIADES");
                    missatge = JSON.parse(response);

                    if (nom.value != "" && contrasenya.value != "") {
                        nom.value = "";
                        contrasenya.value = "";
                    }

                    if (missatge["error"] != "") {
                        $("#resultatUsuari").html(missatge["error"]).show().delay(2000).fadeOut();
                    } else if (missatge["valid"] != "") {
                        $("#resultatUsuari").html(missatge["valid"]).show().delay(2000).fadeOut();
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

        $("#botoAfegirConcursant").click(function() {
            let nomGos = document.getElementById("nomGos");
            let imatgeGos = document.getElementById("imatgeGos");
            let amoGos = document.getElementById("amoGos");
            let raçaGos = document.getElementById("raçaGos");

            // Get form
            var form = document.getElementById('formInscripcio');

            // FormData object 
            var dadesFormulari = new FormData(form);

            $.ajax({
                type: "POST",
                url: "./ajax/ajax_adminConcursant.php",
                data: dadesFormulari,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log("DADES ENVIADES");
                    missatge = JSON.parse(response);

                    if (nomGos.value != "" && imatgeGos.value != "" && amoGos.value != "" && raçaGos.value != "") {
                        nomGos.value = "";
                        imatgeGos.value = "";
                        amoGos.value = "";
                        raçaGos.value = "";
                    }

                    if (missatge["error"] != "") {
                        $("#mostrarInscripcioConcursants").html(missatge["error"]).show().delay(2000).fadeOut();
                    } else if (missatge["valid"] != "") {
                        $("#mostrarInscripcioConcursants").html(missatge["valid"]).show().delay(2000).fadeOut();
                        if (missatge["taulaConcursants"] != "" && missatge["inscripcio"] != "") {
                            $("#formulariConcursants").html(missatge["taulaConcursants"]);
                            $("#formulariConcursantsInscripcio").html(missatge["inscripcio"]);
                        }
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

        function actualitzarFase(num) {
            // Get form
            var form = document.getElementById(`formFase-${num}`);

            // FormData object 
            var dadesFormulari = new FormData(form);
            $.ajax({
                type: "POST",
                url: "./ajax/ajax_adminFase.php",
                data: dadesFormulari,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log("DADES ENVIADES");
                    missatge = JSON.parse(response);

                    if (missatge["error"] != "") {
                        $("#mostrarFases").html(missatge["error"]).show().delay(2000).fadeOut();
                    } else if (missatge["valid"] != "") {
                        $("#mostrarFases").html(missatge["valid"]).show().delay(2000).fadeOut();
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function actualitzarGos(num) {
            // Get form
            var form = document.getElementById(`formGos-${num}`);

            // FormData object 
            var dadesFormulari = new FormData(form);
            $.ajax({
                type: "POST",
                url: "./ajax/ajax_adminConcursant.php",
                data: dadesFormulari,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log("DADES ENVIADES");
                    missatge = JSON.parse(response);

                    if (missatge["error"] != "") {
                        $("#mostrarConcursants").html(missatge["error"]).show().delay(2000).fadeOut();
                    } else if (missatge["valid"] != "") {
                        $("#mostrarConcursants").html(missatge["valid"]).show().delay(2000).fadeOut();
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        botoEsborrarVotsFase.addEventListener('click', () => {
            let numFase = document.getElementById("numFase");

            // Get form
            var form = document.getElementById('formBorrarVots');

            // FormData object 
            var dadesFormulari = new FormData(form);

            $.ajax({
                type: "POST",
                url: "./ajax/ajax_adminVot.php",
                data: dadesFormulari,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log("DADES ENVIADES");
                    missatge = JSON.parse(response);

                    if (numFase.value != "") {
                        numFase.value = "";
                    }

                    if (missatge["error"] != "") {
                        $("#mostrarVotFase").html(missatge["error"]).show().delay(2000).fadeOut();
                    } else if (missatge["valid"] != "") {
                        $("#mostrarVotFase").html(missatge["valid"]).show().delay(2000).fadeOut();
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });

        botoEsborrarTotsVots.addEventListener('click', () => {
            // Get form
            var form = document.getElementById('formBorrarTotsVots');

            // FormData object 
            var dadesFormulari = new FormData(form);

            $.ajax({
                type: "POST",
                url: "./ajax/ajax_adminVot.php",
                data: dadesFormulari,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log("DADES ENVIADES");
                    missatge = JSON.parse(response);

                    if (missatge["error"] != "") {
                        $("#mostrarTotsVotFase").html(missatge["error"]).show().delay(2000).fadeOut();
                    } else if (missatge["valid"] != "") {
                        $("#mostrarTotsVotFase").html(missatge["valid"]).show().delay(2000).fadeOut();
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    </script>
</body>

</html>