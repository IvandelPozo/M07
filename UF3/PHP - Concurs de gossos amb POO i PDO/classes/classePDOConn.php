<?php
//Definim la classe PDOConn
class PDOConn
{
    //PROPIETATS
    //private: només permet accedir-hi des de la pròpia classe
    private $hostname;
    private $dbname;
    private $username;
    private $pw;

    //CONSTRUCTOR: s'executa quan es crea l'objecte
    public function __construct()
    {
        $this->hostname = "localhost";
        $this->dbname = "dwes-ivandelpozo-gossosatura";
        $this->username = "dwes-user";
        $this->pw = "dwes-pass";
    }

    //MÈTODES

    /**
     * Creació de l'string de connexió de la Base de Dades.
     * 
     * @return bool
     * @return PDO
     */

    public function establirConnexio(): bool | PDO
    {
        /**
         * Dades i String Connexió
         */
        try {
            return new PDO("mysql:host=" . $this->hostname . ";dbname=" . $this->dbname . "", "" . $this->username . "", "" . $this->pw . "");
        } catch (PDOException $e) {

            return false;
        }
    }
}

/**
 * By: 01001001 01110110 01100001 01101110
 */