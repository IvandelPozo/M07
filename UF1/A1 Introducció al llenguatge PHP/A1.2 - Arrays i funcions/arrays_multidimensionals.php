<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<style>
		table {
			border-collapse: collapse;
		}
		
		td	{
			border: 1px solid #000;
			padding: 5px;
		}
		</style>
	</head>
<body>

<?php

/*
===============
	CREA MATRIU
===============
*/

function creaMatriu(int $n):array {

	$arrayVuit = array();

	for($i = 0; $i < $n; $i++) {
		array_push($arrayVuit, array());
	}

	$comptarArrays = count($arrayVuit);

	for($i = 0; $i < $comptarArrays; $i++) {

		for($j = 0; $j < $comptarArrays; $j++) {
    
			if($i == $j) {
				
				$arrayVuit[$i][$j] = '*';
			} elseif ($i>$j) {
				
				$arrayVuit[$i][$j] = rand(10,20);
			} else {
				
				$arrayVuit[$i][$j] = $i+$j;
			}
		}
	}
	return $arrayVuit;
}

/*
=================
	MOSTRA MATRIU
=================
*/

function mostraMatriu(array $obtenirArray, bool $taula = true):string {
    
	$sortida = '';
    
	foreach ($obtenirArray as $clau => $valor) {
        if (is_array($valor)) {
			array_keys($valor);
            $sortida .= '<tr>';
            $sortida .= mostraMatriu($valor, false);
            $sortida .= '</tr>';
        } else {
            $sortida .= "<td>".htmlspecialchars($valor)."</td>";
        }
    }

    if ($taula) {
        return '<table>' . $sortida . '</table>';
    } else {
        return $sortida;
    }
}

/*
====================
	TRANSPOSA MATRIU
====================
*/

function transposaMatriu(array $repArray):array {

	$comptarArrays = count($repArray);
   
    for($i = 0; $i < $comptarArrays; $i++) {
     
		for($j = 0; $j < $comptarArrays; $j++) {
     
			if($i > $j) {
       
			$aux = $repArray[$i][$j];
            
			$repArray[$i][$j] = $repArray[$j][$i];
            
			$repArray[$j][$i] = $aux;
			}
		} 
	}
	return $repArray; 
}

/*
=====================
	RESULTATS MATRIUS
=====================
*/

$matriu = creaMatriu(4);

echo "<pre>";
echo mostraMatriu($matriu);
echo "<br>";
echo mostraMatriu(transposaMatriu($matriu));
echo "</pre>";

?>

</body>
</html>
