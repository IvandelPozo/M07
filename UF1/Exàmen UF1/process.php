<?php
session_start();

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

/*
*
*	Mètode POST i dades OK rebudes pels diversos formularis.
*
*/

$reboDades = ($_SERVER['REQUEST_METHOD'] == 'POST');
$dadesOkRegistre = $reboDades && isset($_POST['RegistreNom']) && isset($_POST['RegistreCorreu']) && isset($_POST['RegistrePass']);
$dadesOkLogin = $reboDades && isset($_POST['LoginCorreu']) && isset($_POST['LoginPass']);
$dadesOkHola = $reboDades && isset($_POST['tancarSessio']);


/**
 * 
 * Creació d'usuaris al Registre, si existeix el fitxer intenta afegir, si no, crea l'usuari.
 * 
 */

if ($dadesOkRegistre) {
	$llegirUsuaris = array();
	$keyArray = array();

	if (file_exists('users.json')) {
		$llegirUsuaris = llegeix('users.json');
		$keyArray = array_keys($llegirUsuaris);
	
		foreach ($llegirUsuaris as $key => $value) {
			if($key == $_POST['RegistreCorreu']) {
				header("Location: index.php?error=NOREGISTRE", TRUE, 303);
				die();
			} else {
				$registrarUsuari = array( $_POST['RegistreCorreu'] => array("email" => $_POST['RegistreCorreu'], "password" => $_POST['RegistrePass'], "name" => $_POST['RegistreNom']));
				$llegirUsuaris[implode($keyArray)] = $registrarUsuari;
				escriu($registrarUsuari, 'users.json');
				header("Location: index.php", TRUE, 302);
				die();
			}
		}
	} else {
		$registrarUsuari = array( $_POST['RegistreCorreu'] => array("email" => $_POST['RegistreCorreu'], "password" => $_POST['RegistrePass'], "name" => $_POST['RegistreNom']));
		escriu($registrarUsuari, 'users.json');
		header("Location: index.php", TRUE, 302);
		die();
	}
	
}

/**
 * 
 * Validació de Login.
 * 
 */

if ($dadesOkLogin) {
	$llegirUsuaris = array();
	
	if (file_exists('users.json')) {
		$llegirUsuaris = llegeix('users.json');
	}

	foreach ($llegirUsuaris as $key => $value) {
		if($key == $_POST['LoginCorreu'] && ($llegirUsuaris[$key]["password"]) == $_POST['LoginPass']) {
			$_SESSION['nomUsuari'] = $llegirUsuaris[$key]["name"];
			header("Location: hola.php", TRUE, 302);
			die();
		} else {
			header("Location: index.php?error=NOLOGIN", TRUE, 303);
			die();
		}
	}
}

/**
 * 
 * Tancament de Sessió.
 * 
 */

if ($dadesOkHola) {
	header("Location: index.php", TRUE, 302);
	session_destroy();
	die();
}



/*
$llegirUsuaris = llegeix('users.json');
$keyArray = array_keys($llegirUsuaris);

$registrarUsuari = array( 'test@test.cat' => array("email" => 'test@test.cat', "password" => '1234', "name" => 'nom'));

$llegirUsuaris[] = $registrarUsuari;

print_r($llegirUsuaris);
escriu($llegirUsuaris, 'hola.json');

foreach ($llegirUsuaris as $key => $value) {
	if($key == "gtest@gmail.cat") {
		echo "si";
	} else {
		echo "no";
	}
}
*/

/*


		$llegirUsuaris = llegeix('users.json');
		$obtenirCorreu = $llegirUsuaris;

		print_r($obtenirCorreu);
		
		
		$llegirUsuaris = llegeix('users.json');
		$keyArray = array_keys($llegirUsuaris);
*/
/*
$test = llegeix('users.json');
print_r($test);
print_r(array_keys($test));


if (array_key_exists('test@gmail.cat', $test)) {
	echo "ya creada";
} else {
	echo "es pot crear";
}

if (file_exists('users.json')) {
	echo "yes";
} else {
echo "no";
}
*/	
?>