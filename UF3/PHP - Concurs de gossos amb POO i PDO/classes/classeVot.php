<?php
require_once 'classePDOConn.php';

//Definim la classe Vot
class Vot
{

    //PROPIETATS
    //private: només permet accedir-hi des de la pròpia classe
    public string $idFase;
    public string $idConcursant;
    public string $sessionId;

    //CONSTRUCTOR: s'executa quan es crea l'objecte
    public function __construct($idFase = "", $idConcursant = "", $sessionId = "")
    {
        $this->idFase = $idFase;
        $this->idConcursant = $idConcursant;
        $this->sessionId = $sessionId;
    }

    //MÈTODES

    /**
     * Elimina els vots d'una determinada fase.
     * 
     * @return bool
     */
    public function eliminarVotsFase(): bool
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("DELETE FROM vot WHERE `num_fase` = ?");
        return $sentencia->execute(array($this->idFase));
    }

    /**
     * Elimina tots els vots de totes les fases.
     * 
     * @return bool
     */
    static function eliminarTotsVots(): bool
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("DELETE FROM vot");
        return $sentencia->execute();
    }

    /**
     * Insereix un Vot a la taula de vots, en cas que el mateix usuari, 
     * canviï de concursant votat, s'actualitza al nou.
     * 
     * @return bool
     * @return array
     */
    public function establirVot(): bool | array
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("INSERT INTO vot (`num_fase`, `id_concursant`, `session_id`) VALUES(?, ?, ?) ON DUPLICATE KEY UPDATE `id_concursant`= ?");
        return $sentencia->execute(array($this->idFase, $this->idConcursant, $this->sessionId, $this->idConcursant));
    }

    /**
     * Contador de Vots totals donada una fase i un concursant específic.
     * 
     * @param string $numFase
     * @param string $idConcursant
     * @return bool
     * @return array
     */
    static function contadorVots(string $numFase, string $idConcursant): bool | array
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("SELECT `num_fase`, `id_concursant`, COUNT(`session_id`) AS 'contador' FROM vot WHERE `num_fase` = ? AND `id_concursant` = ?");
        $sentencia->execute(array($numFase, $idConcursant));
        return $sentencia->fetchAll();
    }

    /**
     * Obtenir percentatge del total de vots d'una determinada fase per una fase determinada i un concursant.
     * 
     * @param string $numFase
     * @param string $idConcursant
     * @return bool
     * @return array
     */
    static function percentatgeVots(string $numFase, string $idConcursant): bool | array
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("SELECT ROUND(COUNT(`session_id`)*100/(SELECT COUNT(`session_id`) AS 'VOTS TOTALS' FROM `vot` WHERE `num_fase`= ?)) AS 'PERCENTATGE' FROM `vot` WHERE `num_fase`= ? AND `id_concursant`= ?");
        $sentencia->execute(array($numFase, $numFase, $idConcursant));
        return $sentencia->fetch();
    }
}

/**
 * By: 01001001 01110110 01100001 01101110
 */
