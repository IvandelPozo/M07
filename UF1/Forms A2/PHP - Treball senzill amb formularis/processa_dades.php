<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
		<title>Processa Dades</title>
	</head>

	<body>
		<div>
	
				<?php
					echo "El valor de text és " . $_REQUEST["mytext"] . "<br/>";
					echo "El valor de radiobutton és " . $_REQUEST["myradio"] . "<br/>";

					foreach($_REQUEST["mycheckbox"] as $clau => $valor) {
						echo "El valor de checkbox[$clau] és " . $valor . "<br/>"; 
					}
				
					echo "El valor de select és " . $_REQUEST["myselect"] . "<br/>";
					echo "El valor de textarea és " . $_REQUEST["mytextarea"] . "<br/>";
				?>
		
		</div>
	</body>
</html>