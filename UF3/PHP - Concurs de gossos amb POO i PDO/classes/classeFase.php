<?php
require_once 'classePDOConn.php';

//Definim la classe Fase
class Fase
{

    //PROPIETATS
    //private: només permet accedir-hi des de la pròpia classe
    public string $numero;
    public string $dataInici;
    public string $dataFi;

    //CONSTRUCTOR: s'executa quan es crea l'objecte
    public function __construct($numero = "", $dataInici = "", $dataFi = "")
    {
        $this->numero = $numero;
        $this->dataInici = $dataInici;
        $this->dataFi = $dataFi;
    }

    //MÈTODES

    /**
     * Validació de data per fase. Evita sobreposar una data amb un altre ja establerta en un altra fase.
     * 
     * @return bool
     * @return array
     */
    public function comprovarDataFase(): bool | array
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("SELECT `numero`, `dataInici`, `dataFi` FROM fase WHERE (`dataInici` <= ? AND `dataFi` >= ? AND `numero` != ?) OR (`dataFi` >= ? AND `dataInici` <= ? AND `numero` != ?)");
        $sentencia->execute(array($this->dataInici, $this->dataInici, $this->numero, $this->dataFi, $this->dataFi, $this->numero));
        return $sentencia->fetch();
    }

    /**
     * Actualitzar la data d'una fase.
     * 
     * @return bool
     * @return array
     */
    public function actualitzarDataFase(): bool | array
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("UPDATE fase SET `dataInici`= ?, `dataFi`=? WHERE `numero`= ?");
        return $sentencia->execute(array($this->dataInici, $this->dataFi, $this->numero));
    }

    /**
     * Obtenir totes les fases amb les seves respectives dates d'inici i fi.
     * 
     * @return bool
     * @return array
     */
    static function obtenirFases(): bool | array
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("SELECT `numero`, `dataInici`, `dataFi` FROM fase");
        $sentencia->execute();
        $sentencia->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Fase');
        return $sentencia->fetchAll();
    }

    /**
     * Obtenir la fase actual donada una data i comparada amb data d'inici i fi.
     * 
     * @param string $data
     * @return bool
     * @return Fase
     */
    static function obtenirFaseActual(string $data): bool | Fase
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("SELECT `numero` FROM fase WHERE ? BETWEEN `dataInici` AND `dataFi`");
        $sentencia->execute(array($data));
        $sentencia->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Fase');
        return $sentencia->fetch();
    }

    /**
     * Obtenir la data final donat el número d'una fase.
     * 
     * @param string $numFase
     * @return bool
     * @return Fase
     */
    static function obtenirDataFiFaseActual(string $numFase): bool | Fase
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("SELECT `dataFi` FROM fase WHERE `numero`= ?");
        $sentencia->execute(array($numFase));
        $sentencia->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Fase');
        return $sentencia->fetch();
    }

    /**
     * Obtenir l'acabament d'una fase, si la fase i la data actual supera la data final.
     * 
     * @param string $numFase
     * @param string $dataActual
     * @return bool
     * @return array
     */
    static function fiDeFase(string $numFase, string $dataActual): bool | array
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("SELECT `numero` FROM fase WHERE `numero`= ? AND ? > `dataFi`");
        $sentencia->execute(array($numFase, $dataActual));
        return $sentencia->fetch();
    }

    /**
     * Obtenir els valors d'una fase donat el número de fase.
     * 
     * @param string $numFase
     * @return bool
     * @return Fase
     */
    static function obtenirFase(string $numFase): bool | Fase
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("SELECT `numero`, `dataInici`, `dataFi` FROM fase WHERE `numero`= ? ");
        $sentencia->execute(array($numFase));
        $sentencia->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Fase');
        return $sentencia->fetch();
    }
}

/**
 * By: 01001001 01110110 01100001 01101110
 */