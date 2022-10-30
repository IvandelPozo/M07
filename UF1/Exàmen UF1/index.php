<?php
session_start();

/**
 * Funció que redirigeix a la pàgina hola.php si el temps actual és més petit que el donat pel timer
 * afegit de la pàgina hola.php per SESSIÓ.
 * 
 */

function redireccioSignIn(): void {
	
	if(isset($_SESSION['alCapMinut'])) {
		if(time() < $_SESSION['alCapMinut']) {
			header("Location: hola.php", TRUE, 302);
		}
	}
}

/**
 * Si aquesta SESSIÓ conté "SI" rebut del Inici de Sessió o Registre de forma exitosa, ens redirigeix a 
 * hola.php cridant la funció redireccioSignIn(), ja que a hola.php, també s'afegeix el timer.
 */

if(isset($_SESSION['signIn'])) {
	if($_SESSION['signIn'] == "SI") {
		redireccioSignIn();
	}
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
	<?php
		
		/** 
		* Codis d'error per $_GET. 
		*/

		if(isset($_GET['error'])) {
			$error = $_GET['error'];
		
			switch ($error) {
				case "REGISTRENOVALID":
					echo '<div><p id="errors">Falten camps per emplenar!</p></div>';
					echo '<style>#errors{visibility: visible !important;}</style>';
					break;	
				case "JAREGISTRAT":
					echo '<div><p id="errors">Usuari ja registrat!</p></div>';
					echo '<style>#errors{visibility: visible !important;}</style>';
					break;					
				case "NOLOGINCORREU":
					echo '<div><p id="errors">Correu incorrecte!</p></div>';
					echo '<style>#errors{visibility: visible !important;}</style>';
					break;				
				case "NOLOGINPASS":
					echo '<div><p id="errors">Contrasenya incorrecte!</p></div>';
					echo '<style>#errors{visibility: visible !important;}</style>';
					break;						
			}
		}
	?>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="process.php" method="post">
                <h1>Registra't</h1>
                <span>crea un compte d'usuari</span>
                <input type="hidden" name="method" value="signup"/>
                <input type="text" placeholder="Nom" name="RegistreNom"/>
                <input type="email" placeholder="Correu electronic" name="RegistreCorreu"/>
                <input type="password" placeholder="Contrasenya" name="RegistrePass"/>
                <button>Registra't</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="process.php" method="post">
                <h1>Inicia la sessió</h1>
                <span>introdueix les teves credencials</span>
                <input type="hidden" name="method" value="signin"/>
                <input type="email" placeholder="Correu electronic" name="LoginCorreu"/>
                <input type="password" placeholder="Contrasenya" name="LoginPass"/>
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
</body>
<script>
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    signUpButton.addEventListener('click', () => {
        container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
        container.classList.remove("right-panel-active");
    });
</script>
</html>