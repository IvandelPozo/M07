<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>

	<body>

		<?php
		$i = 12;
		$tipus_de_i = gettype( $i );
		echo "La variable \$i conté el valor $i i és del tipus $tipus_de_i <br>";		
		
		$d = 2.2;
		$tipus_de_d = gettype( $d );
		echo "La variable \$d conté el valor $d i és del tipus $tipus_de_d <br>";		
		
		$b = true;
		$tipus_de_b = gettype( $b );
		echo "La variable \$b conté el valor $b i és del tipus $tipus_de_b <br>";		
		
		$t = "test";
		$tipus_de_t = gettype( $t );
		echo "La variable \$t conté el valor $t i és del tipus $tipus_de_t";
		?>

	</body>
</html>