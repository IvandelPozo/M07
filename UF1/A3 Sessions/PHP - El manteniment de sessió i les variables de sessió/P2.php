<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
</head>
<body>
<h1>P2</h1>

<?php
session_start();

echo $_SESSION["nom"];
echo "<br/>";
echo $_SESSION["sess"];
echo "<br/>";
echo $_SESSION["color"];


?>

</body>
</html>