<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
</head>
<body>
<h1>P1</h1>

<?php

$valor = 100;
$valorActualitzat = 101;

if (isset($_COOKIE['laMevaCookie'])) {
	
	setcookie("laMevaCookie", $valorActualitzat);
	
} else {
	
	setcookie("laMevaCookie", $valor);
	
}


?>

</body>
</html>