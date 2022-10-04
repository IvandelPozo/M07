<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>

	<body>
	
		<?php
		
			$factorial = array(5, 10, 12, 15);
		
			// COMPROVAR VALORS NEGATIUS
		
			function check($n):bool {
				return is_numeric($n) && $n >= 0;
			}
			
			// FACTORIAL ARRAY
			
			function factorialArray($array):array|bool {
				
				if (is_array($array) == 1 && (count($array) == count(array_filter($array, 'check')))) {
				
					$allargadaArray = count($array)-1;
					$numFactorial = 1;
					
					$arrayRetornat = array();
					
					for($i = 0; $i <= $allargadaArray; $i++) {
					
						array_push($arrayRetornat, factorial($array[$i]));
					}				 
				
				return $arrayRetornat;
				
				} else {
					
					return false;
				}
			}
			
			// CALCUL FACTORIAL
			
			function factorial(int $num):int {
				
				$factorial=1;
	
				for ($i = $num; $i > 0; $i--) {
					
					$factorial *= $i;
				}
				
				return $factorial;
			}
			
			
			print_r($factorial);
			echo "<br>";
			var_dump(factorialArray($factorial));

		?>
	
	</body>
</html>