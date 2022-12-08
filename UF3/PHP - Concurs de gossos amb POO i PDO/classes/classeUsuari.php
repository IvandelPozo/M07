<?php
require_once 'classePDOConn.php';

//Definim la classe Usuari
class Usuari
{

    //PROPIETATS
    //private: només permet accedir-hi des de la pròpia classe
    public string $nom;
    public string $contrasenya;

    //CONSTRUCTOR: s'executa quan es crea l'objecte
    public function __construct($nom = "", $contrasenya = "")
    {
        $this->nom = $nom;
        $this->contrasenya = $contrasenya;
    }

    //MÈTODES

    /**
     * Insereix un usuari a la Base de Dades amb encriptació MD5 per la contrasenya.
     * 
     * @return bool
     * @return array
     */
    public function insertarUsuari(): bool | array
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("INSERT INTO usuari (`nom`, `contrasenya`) VALUES(?, MD5(?))");
        return $sentencia->execute(array($this->nom, $this->contrasenya));
    }

    /**
     * Cerca un usuari a la Base de Dades per comprovar credencials al Login.
     * 
     * @param string $nom
     * @return bool
     * @return Usuari
     */
    static function trobarUsuari(string $nom): bool | Usuari
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("SELECT `nom`, `contrasenya` FROM usuari WHERE `nom`= ?");

        $sentencia->execute(array($nom));
        $sentencia->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Usuari');
        return $sentencia->fetch();
    }
}

/**
 * By: 01001001 01110110 01100001 01101110
 */