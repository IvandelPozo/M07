<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html" charset="UTF-8">

<title>Conjetura Collatz</title>

</head>

<body>

<div style="margin: 30px 10%;">
<h3>Insertar Número</h3>
<form action="conjetura_collatz.php" method="post" id="myform" name="myform">

	<label>N</label> <input type="text" value="" size="30" maxlength="100" name="mytext"/><br/><br/>
   
	<button id="mysubmit" type="submit" onclick="hola()">Submit</button><br /><br />

</form>

<?php

function hola(){
	alert("test");
}

	function collatz($n) {
		
		$cadena = '';
		
		while ($n > 1) {
				
			
			if ($n%2 == 0) {
				$n = $n/2;
				$cadena += (int)$n;
				
			} else {
				$n = $n*3+1;
				$cadena += (int)$n;
			}
		}
		return $cadena;
	}

		collatz(20);

?>

</div>

</body>
</html>