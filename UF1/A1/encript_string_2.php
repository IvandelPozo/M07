<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>

	<body>
	
		<?php

			// Frase a Xifrar
			$frase = "Hola Xifratge";
	
			// Mètode Emmagatzematge del Xifrat
			$xifrat = "BF-CBC";

			// Mètode d'encriptació OpenSSl
			$iv_allargada = openssl_cipher_iv_length($xifrat);
			$opcions   = 0;
			
		
			// [ENCRIPTACIÓ]
		
			// Valors aleatoris de 16 dígits 
			$encriptada_iv = random_bytes($iv_allargada);

			// 16 dígits per caracters o números iv
			$clau_encriptada = openssl_digest(php_uname(), 'MD5', TRUE);

			// Procés d'encriptació
			$encriptada = openssl_encrypt($frase, $xifrat, $clau_encriptada, $opcions, $encriptada_iv);


			// [DESENCRIPTACIÓ]
			

			// Valors aleatoris de 16 dígits 
			$decryption_iv = random_bytes($iv_allargada);

			// Guarda la clau de desencriptació
			$clau_desencriptada = openssl_digest(php_uname(), 'MD5', TRUE);

			// Desencriptació
			$desencriptada = openssl_decrypt($encriptada, $xifrat, $clau_desencriptada, $opcions, $encriptada_iv);


			
			echo "<b>Original:</b> " . $frase;
			echo "<br>";
			echo "<b>Encriptada:</b> " . $encriptada;
			echo "<br>";
			echo "<b>Desencriptada:</b> " . $desencriptada;			

		?>
	
	</body>
</html>