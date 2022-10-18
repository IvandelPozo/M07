<?php
session_start();

$reboDades = ($_SERVER['REQUEST_METHOD'] == 'POST');
$dadesOk = $reboDades && isset($_POST['pantalla']);

if(isset($_POST['pantalla'])) {
	$_SESSION['textObtingut'] = $_POST['pantalla'];
	$paraula = $_POST['pantalla'];

	if(isset($_SESSION['paraules'])) {
		$fEncertades = $_SESSION['paraules'];

		if ($dadesOk) {
			if(in_array($paraula, $fEncertades)) {
				header("Location: index.php?error=JAHIES", TRUE, 303);
				die();
			} elseif(!preg_match('/'.$_SESSION['lletres'][3].'/', ($paraula))) {
				header("Location: index.php?error=NOLLETRAMIG", TRUE, 303);
				die();
			} elseif (!paraulaCorrecte($paraula)) {
				header("Location: index.php?error=NOFUNCIO", TRUE, 303);
				die();
			} elseif (paraulaCorrecte($paraula)) {
				$_SESSION['paraules'][] = $paraula;
				header("Location: index.php", TRUE, 302);
			}
		}
	}
}

/** 
* Funció que s'encarrega de comprovar si una funció és correcte o no.
*
* @param string $paraula Paraula llegida formada a partir de fer clic als hexàgons i enviar-la.
* @return bool true/false Depenent si la paraula és correcte o no, torna fals o cert.
*/

function paraulaCorrecte(string $paraula):bool {
	
	if(isset($_SESSION['funcionsCorrectes']) && isset($_SESSION['paraules'])) {
		if (in_array($paraula, $_SESSION['funcionsCorrectes'])) {			
			if (!in_array($paraula, $_SESSION['paraules'])) {
				return true;
			}
		} 	
	}
	return false;
}
?>