<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>

	<body>
	
		<?php
		
		error_reporting(0);

		function decrypt($frase) {

			$frase = str_split($frase,3);
			$talls_frase = array();
			$abecedari = range('a', 'z');

			foreach ($frase as &$valor) {
				array_push($talls_frase, strrev($valor)."");
			}
		 
			$frase_girada = implode("", $talls_frase);
		 
			$talls_frase = str_split($frase_girada,1);
		 
			$total_index_talls = count($talls_frase);
			$total_index_abecedari = count($abecedari)-1;
			$final = "";
		 
			for ($i = 0; $i <= $total_index_talls; $i++) {
				for ($j = 0; $j <= $total_index_abecedari; $j++) {
					if($talls_frase[$i] == $abecedari[$j]) {
			   
						$cerca = array_search($talls_frase[$i], $abecedari);
			   
						$lletra_trobada = $total_index_abecedari-$cerca;
					   
						$final = $final.$abecedari[$lletra_trobada];
					}
				}
			}
		 
			return $final;
		}


		function decript($frase) {

			$frase = str_split($frase,3);
			$talls_frase = array();
			$abecedari = range('a', 'z');

			foreach ($frase as &$valor) {
				array_push($talls_frase, strrev($valor)."");
			}
		 
			$frase_girada = implode("", $talls_frase);
		 
			$talls_frase = str_split($frase_girada,1);

			$total_index_talls = count($talls_frase);
			$total_index_abecedari = count($abecedari)-1;
			$final = "";
		 
			for ($i = 0; $i <= $total_index_talls; $i++) {
				if($talls_frase[$i] == " " || $talls_frase[$i] == "," || $talls_frase[$i] == ".") {
		   
				$final = $final.$talls_frase[$i];
				}
		   
				for ($j = 0; $j <= $total_index_abecedari; $j++) {
					if ($talls_frase[$i] == $abecedari[$j]) {
			   
						$cerca = array_search($talls_frase[$i], $abecedari);
				   
						$lletra_trobada = $total_index_abecedari-$cerca;
			 
						$final = $final.$abecedari[$lletra_trobada];
					}
				}
			}
		 
			return $final;
		}


		$sp = "kfhxivrozziuortghrvxrrkcrozxlwflrh";
		$mr = " hv ovxozwozv vj o vfrfjvivfj h vmzvlo e hrxvhlmov oz ozx.vw z xve hv loqvn il hv lmnlg izxvwrhrvml ,hv b lh mv,rhhv mf w zrxvlrh.m";

		echo decrypt($sp);
		echo "<br>";
		echo decript($mr);

		?>
	
	</body>
</html>