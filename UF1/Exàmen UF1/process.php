<?php
session_start();

/**
 * Establiment timezone per defecte.
 */
date_default_timezone_set('Europe/Madrid');
/**
 * Establiment hora i data actual amb format específic.
 */
$horaIData = date('Y-m-d H:i:s', time());

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
*	Mètode POST i dades OK rebudes pels diversos formularis.
*/

$reboDades = ($_SERVER['REQUEST_METHOD'] == 'POST');
$dadesOkRegistre = $reboDades && isset($_POST['RegistreNom']) && isset($_POST['RegistreCorreu']) && isset($_POST['RegistrePass']);
$dadesOkLogin = $reboDades && isset($_POST['LoginCorreu']) && isset($_POST['LoginPass']);
$dadesOkHola = $reboDades && isset($_POST['tancarSessio']);
 
/**
 * Redirecció a index.php en cas d'entrar de forma manual a process.php.
 */

 if(!$reboDades) {
	header("Location: index.php", TRUE, 303); 
 }


 /**
 * 
 * Creació d'usuaris al Registre. Si amb mètode POST, rebem les dades del formulari de registre, es comprova que el mateix
 * correu no existeixi i que els camps estiguin tots emplenats del formulari, sinó, retorna error per $_GET. Si tot és correcte, 
 * s'escriu al fitxer de connexions un "signup_success" i s'afegeixen variables de SESSIÓ per saber el nom d'usuari, correu i si 
 * ha fet signIn. I la redirecció cap a hola.php, ja que la creació d'un nou usuari es considera una autentificació correcta.
 * 
 */

if ($dadesOkRegistre) {

	$usuaris = llegeix('users.json');
	$connexions = llegeix('connexions.json');
	$keyArray = $_POST['RegistreCorreu'];

	if (isset($usuaris[$keyArray])) {
		header("Location: index.php?error=JAREGISTRAT", TRUE, 303);
	} elseif($_POST['RegistreNom'] == "" || $_POST['RegistreCorreu'] == "" || $_POST['RegistrePass'] == "") {
		header("Location: index.php?error=REGISTRENOVALID", TRUE, 303);
	} else {
		$nouUsuari = array("email" => $_POST['RegistreCorreu'], "password" => $_POST['RegistrePass'], "name" => $_POST['RegistreNom']);
		$connNouRegistre = array("ip" => $_SERVER['REMOTE_ADDR'], "user" => $_POST['RegistreCorreu'], "time" => $horaIData, "status" => "signup_success");
		
		$usuaris[$keyArray] = $nouUsuari;

		escriureConnexions($connNouRegistre);
		escriu($usuaris, 'users.json');
		
		$_SESSION['nomUsuari'] = $_POST['RegistreNom'];
		$_SESSION['correuUsuari'] = $_POST['RegistreCorreu'];
		$_SESSION['signIn'] = "SI";
		
		header("Location: hola.php", TRUE, 302);
	}
}

/**
 * 
 * Validació de Login. Comprova cada compte d'usuari registrat, si coincideix el correu amb la seva contrasenya, la validació
 * serà exitosa afegint variables de SESSIÓ per saber el nom d'usuari, correu i si ha fet Sign In, en canvi, d'afegir un correu que 
 * no existeixi o una contrasenya equivocada, enviarà error per $_GET i al fitxer de connexions.
 * 
 */

if ($dadesOkLogin) {

	$usuaris = llegeix('users.json');
	$keyArray = $_POST['LoginCorreu'];
	$passwordEntrada = $_POST['LoginPass'];
	
	foreach ($usuaris as $key=> $value) {
		if($usuaris[$key]['email'] == $keyArray && $usuaris[$key]['password'] == $passwordEntrada) {
			$correu = $usuaris[$key]['email'];
			$pass = $usuaris[$key]['password'];
			$nom = $usuaris[$key]['name'];
		} 
	}
	
	if (isset($usuaris[$keyArray])) {
		if($correu == $keyArray && $pass == $passwordEntrada) {
			
			$_SESSION['nomUsuari'] = $nom;
			$_SESSION['correuUsuari'] = $correu;
			$_SESSION['signIn'] = "SI";
			
			$connNouLogin = array("ip" => $_SERVER['SERVER_ADDR'], "user" => $_SESSION['correuUsuari'], "time" => $horaIData, "status" => "signin_success");
			escriureConnexions($connNouLogin);
			header("Location: hola.php", TRUE, 302);
		} else {
			$connNouNoPass = array("ip" => $_SERVER['SERVER_ADDR'], "user" => $_POST['LoginCorreu'], "time" => $horaIData, "status" => "signin_password_error");
			escriureConnexions($connNouNoPass);
			header("Location: index.php?error=NOLOGINPASS", TRUE, 303);
		}
	} else {
		$connNouNoCorreu = array("ip" => $_SERVER['SERVER_ADDR'], "user" => $_POST['LoginCorreu'], "time" => $horaIData, "status" => "signin_email_error");
		escriureConnexions($connNouNoCorreu);
		header("Location: index.php?error=NOLOGINCORREU", TRUE, 303);
	}	
}

/**
 * 
 * Tancament de Sessió. Si el botó de tancar sessió es prem, s'envia un "logoff" al fitxer de connexions i
 * s'elimina la sessió, i s'envia l'usuari a l'index.php.
 * 
 */

if ($dadesOkHola) {

	$connNouLogOff = array("ip" => $_SERVER['SERVER_ADDR'], "user" => $_SESSION['correuUsuari'], "time" => $horaIData, "status" => "logoff");
	escriureConnexions($connNouLogOff);
	
	session_destroy();
	header("Location: index.php", TRUE, 302);
}

/**
 * Donat un array amb informació de signup_success, signin_success, signin_password_error, signin_email_error o logoff,
 * llegeix el fitxer de connexions i afegeix la informació del fitxer connexions i l'array actual, en un array i el guarda de nou a 
 * connexions.json cridant la funció escriu().
 *
 * @param array $connNoves
 */

function escriureConnexions(array $connNoves): void {
	$connexions = llegeix('connexions.json');
	$connexions[] = $connNoves;
	escriu($connexions, 'connexions.json');
}	
?>