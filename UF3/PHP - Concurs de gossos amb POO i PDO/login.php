<?php
session_start();
require_once './utils/funcions.php';

// Redirecció per sessió activa
if (isset($_SESSION["usuariAdmin"])) {
    header("Location: admin.php", true, 302);
}
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultat votació popular Concurs Internacional de Gossos d'Atura</title>
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <div class="wrapper large" id="login-align">
        <div class="form-container sign-in-container">
            <?php
                if (isset($_GET['error'])) {
                    $msg = match ($_GET['error']) {
                        "signin_user_error" => 'L\'usuari no és vàlid',
                        "signin_password_error" => 'Contrasenya incorrecta',
                        "timeout" => 'La sessió ha caducat',
                        default => 'S\'ha produït un error inesperat',
                    };
        
                    if ($msg) {
                        echo "<p class='hide' id='message'> $msg </p>";
                    }
                }
            echo formulariIniciSessio();
            ?>
        </div>
    </div>
</body>

</html>