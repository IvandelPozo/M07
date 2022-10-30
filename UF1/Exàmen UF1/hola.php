<?php
session_start();

/**
 * Establiment timezone per defecte.
 */
date_default_timezone_set('Europe/Madrid');

/**
 * Llegeix les dades del fitxer. Si el document no existeix torna un array buit.
 *
 * @param string $file
 * @return array
 */
function llegeix(string $file): array
{
    $var = [];
    if ( file_exists($file) ) {
        $var = json_decode(file_get_contents($file), true);
    }
    return $var;
}

/**
 * Guarda les dades a un fitxer
 *
 * @param array $dades
 * @param string $file
 */
function escriu(array $dades, string $file): void
{
    file_put_contents($file,json_encode($dades, JSON_PRETTY_PRINT));
}

/**
 * Donat un array amb informació del logoff, llegeix el fitxer de connexions i afegeix
 * la informació del fitxer connexions i l'array actual, en un array i el guarda de nou a 
 * connexions.json cridant la funció escriu().
 *
 * @param array $connNouLogOff
 */
function escriureConnexions(array $connNouLogOff): void {
	$connexions = llegeix('connexions.json');
	$connexions[] = $connNouLogOff;
	escriu($connexions, 'connexions.json');
}

/**
 * Creem una variable de SESSIÓ amb el temps actual + 60 segons = 1 minut. Aquesta variable de SESSIÓ ens serveix per la
 * redirecció en aquesta pàgina hola.php des de l'index.php. En cas que el temps actual sigui més gran que el de la 
 * variable de SESSIÓ, vol dir que ha de fer logoff del usuari i redirecció a index.php amb eliminació de la sessió. 
 * Un cop fet el logoff, es crida la funció escriureConnexions(), per afegir el logoff a connexions.json.
 */
function afegirTimer(): void {
	if(!isset($_SESSION['alCapMinut'])) {
		$_SESSION['alCapMinut'] = time()+60;
	}
	
	if(isset($_SESSION['alCapMinut'])) {
		if(time() > $_SESSION['alCapMinut']) {
			$horaIData = date('Y-m-d H:i:s', time());
			$connNouLogOff = array("ip" => $_SERVER['SERVER_ADDR'], "user" => $_SESSION['correuUsuari'], "time" => $horaIData, "status" => "logoff");
			escriureConnexions($connNouLogOff);
	
			session_destroy();
			header("Location: index.php", TRUE, 302);
		}
	}
}

/**
 * Si aquesta SESSIÓ conté "SI" rebut de l'Inici de Sessió o Registre de forma exitosa, crida a afegirTimer(),
 * per establir el minut d'autentificació sense desconnexió. En cas d'accedir en aquesta pàgina de forma manual, 
 * ens redirigeix a index.php.
 */
if(isset($_SESSION['signIn'])) {
	if($_SESSION['signIn'] == "SI") {
		afegirTimer();
	} 
} else {
	header("Location: index.php", TRUE, 303);
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <title>Benvingut</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">

</head>
<body>
<div class="container noheight" id="container">
    <div class="welcome-container">
        <h1>Benvingut!</h1>
        <div>Hola <?php if(isset($_SESSION['nomUsuari'])) { echo "<b>".$_SESSION['nomUsuari']."</b>"; }?>, les teves darreres connexions són:</div>
		<br/>
		<div>
		<?php 
			/**
			 * Llegir el fitxer de connexions.json. En cas de tenir la variable de SESSIÓ amb el correu de l'usuari que ha fet
			 * Sign In o Registre, es busquen els seus accessos correctes i es mostren.
			 */

			$connexions = llegeix('connexions.json');
			if(isset($_SESSION['correuUsuari'])) {
				foreach ($connexions as $key=> $value) {
					if($value['user'] == $_SESSION['correuUsuari'] && ($value['status'] == "signin_success" || $value['status'] == "signup_success")) {
						echo ($value['ip']." | ".$value['time']." | ".$value['status']);
						echo "<br/>";
					} 
				}
			}
		?>
		</div>
		<br/>
        <form action="process.php" method="post">
			<input type="hidden" name="tancarSessio"/>
            <button>Tanca la sessió</button>
        </form>
    </div>
</div>
</body>
</html>