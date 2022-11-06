<?php
session_start();

// Redirecció per sessió activa
if (isset($_SESSION["user"]) && time() - $_SESSION["user"]["login_time_stamp"] < 60) {
    header("Location: hola.php", true, 302);
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <title>Accés</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="process.php" method="post">
                <h1>Registra't</h1>
                <span>crea un compte d'usuari</span>
                <input type="hidden" name="method" value="signup" />
                <input type="text" name="name" placeholder="Nom" />
                <input type="email" name="email" placeholder="Correu electronic" />
                <input type="password" name="password" placeholder="Contrasenya" />
                <button>Registra't</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="process.php" method="post">
                <h1>Inicia la sessió</h1>
                <span>introdueix les teves credencials</span>
                <input type="hidden" name="method" value="signin" />
                <input type="email" name="email" placeholder="Correu electronic" />
                <input type="password" name="password" placeholder="Contrasenya" />
                <button>Inicia la sessió</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Ja tens un compte?</h1>
                    <p>Introdueix les teves dades per connectar-nos de nou</p>
                    <button class="ghost" id="signIn">Inicia la sessió</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Primera vegada per aquí?</h1>
                    <p>Introdueix les teves dades i crea un nou compte d'usuari</p>
                    <button class="ghost" id="signUp">Registra't</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container-notifications">
        <?php
        if (isset($_GET['error'])) {
            $msg = match ($_GET['error']) {
                "signin_email_error" => 'El correu no és vàlid',
                "signin_password_error" => 'Contrasenya incorrecte',
                "signup_exist_error" => 'El correu ja existeix',
                "timeout" => 'La sessió ha caducat',
                "logoff" => '',
                "database_error" => 'Error en la base de dades',
                default => 'S\'ha produït un error inesperat',
            };

            if ($msg) {
                echo "<p class='hide' id='message'> $msg </p>";
            }
        }
        ?>
    </div>
</body>
<script>
    function clear_gets() {
        window.history.replaceState(null, null, window.location.pathname)
    }

    function amagaError() {
        if (document.getElementById("message"))
            document.getElementById("message").style.opacity = "0"
        clear_gets()
    }

    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    signUpButton.addEventListener('click', () => {
        container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
        container.classList.remove("right-panel-active");
    });

    window.onload = () => {
        setTimeout(amagaError, 2000)
    }
</script>

</html>