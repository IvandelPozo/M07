<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>

	<body>
	
		<?php
			// creo un array amb 3 elements
			$a = array( 5,7,11);
			print_r( $a );
			echo "<br>";

			//afegeixo més elements a l'array
			$a[] = 13;  
			$a[] = 17;
			print_r( $a );
			echo "<br>";

			//encara un altre
			array_push ($a, 23);
			print_r( $a );
			echo "<br>";

			//pinto elements de l'array
			echo "El valor de del tercer element de l'array és " . $a[2];
			echo "<br>";

			unset($a[0]); 
			unset($a[1]); // el valor 7 seguia a la possició 1
			print_r( $a );
			echo "<br>";
		?>
	
	</body>
</html>