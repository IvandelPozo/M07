<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>

	<body>
	
		<?php
		
		error_reporting(0);
		
		function encriptar($dada){
			$ip = $_SERVER['REMOTE_ADDR'];
			$encode64 = base64_encode($dada);
			$bin2hex = bin2hex($encode64);

			$ipSensePunts = str_replace(".", "", $ip);			
			
			$fraseSumada = $bin2hex.$ipSensePunts;
			
			return $fraseSumada;
		}
		
		
		function desencriptar($dada){
			
			$treureDeu = substr($dada, 0, -10);
			$hex2bin = hex2bin($treureDeu);
			$decode64 = base64_decode($hex2bin);
			
			return $decode64;
		}
				
		
		$frase="hola 12414199530 QéTAl..,, []^^´.";
		
		echo "<b>Frase -></b> ".$frase;
		echo "<br></br>";
		echo "<b>Encriptada -></b> ".encriptar($frase);
		echo "<br></br>";
		echo "<b>Desencriptada -></b> ".desencriptar(encriptar($frase));
		
		?>
	
	</body>
</html>