<?php
require_once 'utils.php';
session_start();

// Redirecció per sessió no activa
if(!isset($_SESSION["user"]) || time()-$_SESSION["user"]["login_time_stamp"] >60)
{
    header("Location: index.php?error=timeout", true, 303);
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <title>Benvingut</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
</head>
<body>
<div class="container noheight" id="container">
    <div class="welcome-container">
        <h1>Benvingut!</h1>
        <div>Hola <?php if(isset($_SESSION["nomUsuari"])) { echo $_SESSION["nomUsuari"]; } ?>, les teves darreres connexions són:</div>
        <div class="connections"><?php if(isset($_SESSION["nomUsuari"])) { echo(print_conns($_SESSION["emailUsuari"])); } ?>
    </div>
        <form action="process.php" method="post">
            <input type="hidden" name="method" value="logoff"/>
            <button>Tanca la sessió</button>
        </form>
    </div>
</div>
</body>
</html>