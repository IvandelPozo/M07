<?php
/**
 * Dades i String Connexió
 */
try {
    $hostname = "localhost";
    $dbname = "dwes-ivandelpozo-autpdo";
    $username = "dwes-user";
    $pw = "dwes-pass";
    $conn = new PDO("mysql:host=$hostname;dbname=$dbname", "$username", "$pw");
} catch (PDOException $e) {
    echo "Failed to get DB handle: " . $e->getMessage() . "\n";
    header("Location: index.php?error=database_error", true, 303);
    exit;
}

/**
 * Mostra les connexions d'un usuari amb status success
 *
 * @param string $email
 * @return string
 */
function print_conns(string $email): string
{
    $output = "";
    $data = obtenirDadesConnexions($email);

    foreach ($data as $vals) {
        if ($vals["user"] == $email && str_contains($vals["status"], "success"))
            $output .= "Connexió des de " . $vals["ip"] . " amb data " . $vals["time"] . "<br>\n";
    }

    return $output;
}

/**
 * Inserta un usuari a la Base de Dades
 *
 * @param string $email, $password, $name
 * @return void
 */
function insertarUsuaris(string $email, string $password, string $name): void
{
    global $conn;

    //cadascun d'aquests interrogants serà substituit per un paràmetre.
    $stmt = $conn->prepare("INSERT INTO users (`email`, `password`, `name`) VALUES(?, MD5(?), ?)");
    //a l'execució de la sentència li passem els paràmetres amb un array 
    $stmt->execute(array($email, $password, $name));
}

/**
 * Obtenir les dades de la Base de Dades d'un usuari a partir del seu email
 *
 * @param string $email
 * @return array|bool $row
 */
function obtenirDadesUsuari(string $email): array | bool
{
    global $conn;

    $stmt = $conn->prepare("SELECT `email`,`password`,`name` FROM users WHERE email = ?");
    //a l'execució de la sentència li passem els paràmetres amb un array 
    $stmt->execute(array($email));
    $row = $stmt->fetch();

    return $row;
}

/**
 * Inserta les diverses connexions dels usuaris a la Base de Dades
 *
 * @param string $ip, $user, $time, $status
 * @return void
 */
function insertarConnexions(string $ip, string $user, string $time, string $status): void
{
    global $conn;

    //cadascun d'aquests interrogants serà substituit per un paràmetre.
    $stmt = $conn->prepare("INSERT INTO connections (`ip`, `user`, `time`, `status`) VALUES(?, ?, ?, ?)");
    //a l'execució de la sentència li passem els paràmetres amb un array 
    $stmt->execute(array($ip, $user, $time, $status));
}

/**
 * Obtenir les dades de la Base de Dades de les connexions a partir del seu email
 *
 * @param string $user
 * @return array $row
 */
function obtenirDadesConnexions(string $user): array
{
    global $conn;

    $stmt = $conn->prepare("SELECT `ip`,`user`,`time`,`status` FROM connections WHERE user = ?");
    //a l'execució de la sentència li passem els paràmetres amb un array 
    $stmt->execute(array($user));
    $row = $stmt->fetchAll();

    return $row;
}
