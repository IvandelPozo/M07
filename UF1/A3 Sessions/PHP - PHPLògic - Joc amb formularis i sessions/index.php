<?php 
session_start();
date_default_timezone_set('Europe/Madrid');

if(!isset($_SESSION['paraules'])) {
	$_SESSION['paraules'] = array();
}

/* SEED */

if(isset($_SESSION['Data'])) {
	$dataAbans = $_SESSION['Data'];
	establirEncertsBuit($dataAbans);
}
establirData();
neteja();
srand($_SESSION['Data']);

/* --- */

/** 
* Funció que s'encarrega de comprovar si la data introduïda és correcte o no per $_GET.
*
* @param string,string $formatData Format en el que està la data. $dataEntrant Entrar la data.
* @return bool $data Retorna true o false segons si es correcte.
*/

function comprovacioData(string $formatData, string $dataEntrant):bool {
	$data = date_create_from_format($formatData, $dataEntrant);
	return $data && ($data->format($formatData) === $dataEntrant);
}

/** 
* Funció que s'encarrega d'establir la data correcte a $_GET per la Seed o si,
* la SESSION està buida, omplir-la amb el format i data actual.
*
* EXEMPLE UTILITZACIÓ: data=20221018
*
*/

function establirData() {
	if (isset($_GET['data'])) {
		$comprovarData = comprovacioData('Ymd', $_GET['data']);
		
		if ($comprovarData == true) {
			$_SESSION['Data'] = $_GET['data'];
			$_SESSION['paraules'] = array();
		}
	} elseif (empty($_SESSION['Data'])) {
		$_SESSION['Data'] = date('Ymd');
	}
}

/** 
* Funció que s'encarrega d'establir els encerts a 0 al canviar de data.
*
* @param string $dataAbans Entrar la data d'abans per comprovar si és diferent a l'actual.
* 
*/

function establirEncertsBuit(string $dataAbans) {
	if (!isset($_SESSION['paraules']) || $dataAbans != $_SESSION['Data']) {
		$_SESSION['paraules'] = array();
	}
}

/** 
* Funció que s'encarrega de netejar les funcions encertades amb el $_GET.
*/

function neteja() {
	if (isset($_GET['neteja'])) {
		$_SESSION['paraules'] = array();
	}
}

/** 
* Funció que s'encarrega de comprovar les funcions de PHP que tinguin fins a 7 lletres diferents i
* sense números.
*
* @param array $funcions Entrada de l'array de funcions de PHP.
* @return array $arrayNomFuncions Retorna un array amb les funcions que tinguin fins a 7 lletres diferens i sense números.
*/

function funcionsPossibles(array $funcions):array {
	
$contador = count($funcions)-1;
$arrayNomFuncions = [];

	for ($i = 0; $i <= $contador; $i++) {

		$recompteLletres = strlen(count_chars($funcions[$i],3));
		
		if( $recompteLletres <= 7 && !preg_match('/[0-9]+/', $funcions[$i]) ) {
			$arrayNomFuncions[] = $funcions[$i];
		}
	}
	return $arrayNomFuncions;
}

/** 
* Funció que s'encarrega de generar lletres i/o un signe de forma aleatòria.
*
* @param int $numLletres Número de lletres aleatòries a generar.
* @return array $lletresTretes Retorna un array amb les lletres i/o signe generats aleatòriament.
*/

function generarLletres(int $numLletres):array {

  $lletres = range('a', 'z');
  $lletres[] = "_";
   
  $lletresTretes = [];

  for ($i = 0; $i < $numLletres; $i++) {

      $contador = count($lletres)-1;

      $aleatori = rand(0, $contador);

      $lletra = implode(array_splice($lletres,$aleatori,1));    

      $lletresTretes[] = $lletra;
  }
  return $lletresTretes;
}

/** 
* Funció que s'encarrega de generar un màxim de paraules PHP establert a partir dels intents de les lletres que surten de forma aleatòria.
* Aquestes paraules han te formar-se tenint en compte la lletra del mig.
*
* @param array,int $funcionsPossibles Llista de funcions possibles a partir de la funció funcionsPossibles();. $maxParaules Màxim de paraules a formar.
*/

function mirarParaula(array $funcionsPossibles, int $maxParaules) {
	
	$contador = 0;
	
	while ($contador < $maxParaules) {
		$contador = 0;
		$lletres = implode(generarLletres(7));
		$lletraMig = substr($lletres, 3,1);
		
		foreach ($funcionsPossibles as $funcio) {
		
			if (preg_match('/^['.$lletres.']+$/', ($funcio)) && preg_match('/'.$lletraMig.'/', ($funcio))) {
						
				$contador++;
				$_SESSION['funcionsCorrectes'][] = $funcio;
				$_SESSION['lletraMig'] = $lletraMig;
			}
		}
		if($contador >= $maxParaules){

			$_SESSION['lletres'] =  str_split($lletres);
			break;
		} else {
			$_SESSION['funcionsCorrectes'] = array();
		} 
	}
}

$funcions = get_defined_functions();
$funcionsphp = $funcions['internal'];
$_SESSION['funcionsPossibles'] = funcionsPossibles($funcionsphp);

if (!isset($_SESSION['lletres']) || $dataAbans != $_SESSION['Data']) {
	mirarParaula($_SESSION['funcionsPossibles'], 10);
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <title>PHPògic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Juga al PHPLògic.">
    <meta name="description" content="Juga al PHPLògic.">
    <link href="//fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body data-joc=<?php if (isset($_SESSION['Data'])) {echo $_SESSION['Data'];}?>>
	<form action="process.php" method="post">
		<div class="main">
			<h1>
				<a href=""><img src="logo.png" height="54" class="logo" alt="PHPlògic"></a>
			</h1>			
			<?php
				if(isset($_GET['sol']) && isset($_SESSION['lletres'])) {
					
					$fCorrectes = $_SESSION['funcionsCorrectes'];
					
					echo "
						<p><b>SOLUCIONARI: </b></p> 
					";

					for ($i=0; $i < count($fCorrectes); $i++) { 
						echo $fCorrectes[$i]." | ";
					}
				}
			?>

			<div class="container-notifications">
				<?php

					/** 
					* Codis d'error per $_GET. 
					*/

					if(isset($_GET['error'])) {
						$error = $_GET['error'];
					
						switch ($error) {
							case "JAHIES":
								echo '<p class="hide" id="message">Ja hi ha la resposta.</p>';
								break;
							case "NOFUNCIO":
								echo '<p class="hide" id="message">'.$_SESSION['textObtingut'].'</p>';
								break;						
							case "NOLLETRAMIG":
								echo '<p class="hide" id="message">Falta la lletra del mig.</p>';
								break;
						}
					}
				?>
			</div>
			
			<div class="cursor-container">
				<p id="input-word"><span id="test-word"></span><span id="cursor">|</span></p>
				<input type="hidden" name="pantalla" id="pantalla">
			</div>
			<?php 

				/**
				* Generar hexàgons.
				*/				

				echo'<div class="container-hexgrid">
						<ul id="hex-grid">';
				 
				for ($i = 0; $i <= 6; $i++) {
		
					$tallarParaules = implode(array_slice( $_SESSION["lletres"],$i,1));            
		
					if ($i == 3) {
						echo '<li class="hex">
								<div class="hex-in"><a class="hex-link" data-lletra='.$tallarParaules.' id="center-letter"><p>'.$tallarParaules.'</p></a></div>
							 </li>';
					} else {
						echo '<li class="hex">
								<div class="hex-in"><a class="hex-link" data-lletra='.$tallarParaules.'><p>'.$tallarParaules.'</p></a></div>
							</li>';
					}
				}
				
				echo '</ul>
					</div>';
			?>
			<div class="button-container">
				<button id="delete-button" type="button" title="Suprimeix l'última lletra" onclick="suprimeix()"> Suprimeix</button>
				<button id="shuffle-button" type="button" class="icon" aria-label="Barreja les lletres" title="Barreja les lletres">
					<svg width="16" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg"
						viewBox="0 0 512 512">
						<path fill="currentColor"
							d="M370.72 133.28C339.458 104.008 298.888 87.962 255.848 88c-77.458.068-144.328 53.178-162.791 126.85-1.344 5.363-6.122 9.15-11.651 9.15H24.103c-7.498 0-13.194-6.807-11.807-14.176C33.933 94.924 134.813 8 256 8c66.448 0 126.791 26.136 171.315 68.685L463.03 40.97C478.149 25.851 504 36.559 504 57.941V192c0 13.255-10.745 24-24 24H345.941c-21.382 0-32.09-25.851-16.971-40.971l41.75-41.749zM32 296h134.059c21.382 0 32.09 25.851 16.971 40.971l-41.75 41.75c31.262 29.273 71.835 45.319 114.876 45.28 77.418-.07 144.315-53.144 162.787-126.849 1.344-5.363 6.122-9.15 11.651-9.15h57.304c7.498 0 13.194 6.807 11.807 14.176C478.067 417.076 377.187 504 256 504c-66.448 0-126.791-26.136-171.315-68.685L48.97 471.03C33.851 486.149 8 475.441 8 454.059V320c0-13.255 10.745-24 24-24z"></path>
					</svg>
				</button>
				<button id="submit-button" type="submit" title="Introdueix la paraula">Introdueix</button>
			</div>
	</form>
    <div class="scoreboard">
        <div>Has trobat <span id="letters-found"><?php if (isset($_SESSION['paraules'])){echo count($_SESSION['paraules']);} else { echo "0"; } ?></span> <span id="found-suffix"><?php if (isset($_SESSION['paraules']) && count($_SESSION['paraules']) == 1){echo "funció";} else {echo "funcions";} ?></span><span id="discovered-text">: <?php if (isset($_SESSION['paraules'])){echo ("<b>".implode(" ", $_SESSION['paraules'])."</b>");} ?></span>
        </div>
        <div id="score"></div>
        <div id="level"></div>
    </div>
</div>
<script>
    
    function amagaError(){
        if(document.getElementById("message"))
            document.getElementById("message").style.opacity = "0"
    }

    function afegeixLletra(lletra){
        document.getElementById("test-word").innerHTML += lletra
        document.getElementById("pantalla").value += lletra

    }

    function suprimeix(){
        document.getElementById("test-word").innerHTML = document.getElementById("test-word").innerHTML.slice(0, -1)
        document.getElementById("pantalla").value = document.getElementById("pantalla").value.slice(0, -1)
    }

    window.onload = () => {
        // Afegeix funcionalitat al click de les lletres
        Array.from(document.getElementsByClassName("hex-link")).forEach((el) => {
            el.onclick = ()=>{afegeixLletra(el.getAttribute("data-lletra"))}
        })

        setTimeout(amagaError, 2000)

        //Anima el cursor
        let estat_cursor = true;
        setInterval(()=>{
            document.getElementById("cursor").style.opacity = estat_cursor ? "1": "0"
            estat_cursor = !estat_cursor
        }, 500)
    }

	// Recarrega la pàgina a les 0:00 i estableix nova Data per la Seed.

	setInterval(function() {
		let Hora = new Date().getHours()
		let Minuts = new Date().getMinutes()
		let Segons = new Date().getSeconds()

		if (Hora == 0 && Minuts == 00 && Segons == 00) {
			location.reload();
		}
	}, 1000);

</script>
</body>
</html>