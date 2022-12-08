<?php
session_start();
require_once './utils/funcions.php';

establirSeed();

$faseActual = Fase::obtenirFaseActual($_SESSION["data"]);

if ($faseActual) {
    $dataFi = Fase::obtenirDataFiFaseActual($faseActual->numero);
    $concursantVotat = Gos::saberConcursantVotat($faseActual->numero, session_id());
}

?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votació popular Concurs Internacional de Gossos d'Atura 2023</title>
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <div class="wrapper">
        <?php
        if ($faseActual) {
            echo "<header>Votació popular del Concurs Internacional de Gossos d'Atura 2023- FASE <span>" . $faseActual->numero . "</span></header>
            <p class='info'> Podeu votar fins el dia $dataFi->dataFi</p>";
            if ($concursantVotat) {
                $concursantVotatId = $concursantVotat->id;
                echo "<br>
                <p class='amagar' id='green-alert'> Ja has votat al gos <b>$concursantVotat->nom</b>. Es modificarà la teva resposta</p>";
            } else {
                $concursantVotatId = "";
            }
        ?>
            <div id="resultatVot"></div>
            <div class="poll-area">
                <?php
                canviFase();
                echo formulariVotacio($concursantVotatId);
                ?>
            </div>
        <?php
        } else {
            echo "<header>Votacions no disponibles
                    <div>
                        <h3>Data: </h3>".$_SESSION["data"]."</div>
                </header> 
                <br>";
        }
        ?>
        <p> Mostra els <a href="resultats.php">resultats</a> de les fases anteriors.</p>
    </div>
    <script>
        $("label").on('click', function() {

            $("label").removeClass("selected");
            $("#" + this.id).addClass("selected");
        });

        function posarVot(num) {
            // Get form
            var form = document.getElementById(`formVot-${num}`);

            // FormData object 
            var dadesFormulari = new FormData(form);
            $.ajax({
                type: "POST",
                url: "./ajax/ajax_indexVot.php",
                data: dadesFormulari,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log("DADES ENVIADES");
                    missatge = JSON.parse(response);

                    if (missatge["error"] != "") {
                        $("#resultatVot").html(missatge["error"]).show().delay(2000).fadeOut();
                    } else if (missatge["valid"] != "") {
                        $(".amagar").remove();
                        $("#resultatVot").html(missatge["valid"]).show();
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    </script>
</body>

</html>