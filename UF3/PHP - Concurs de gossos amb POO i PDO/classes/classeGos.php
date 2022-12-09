<?php
require_once 'classePDOConn.php';

//Definim la classe Gos
class Gos
{
    //PROPIETATS
    //private: només permet accedir-hi des de la pròpia classe
    public string $id;
    public string $nom;
    public string $imatge;
    public string $amo;
    public string $raça;

    //CONSTRUCTOR: s'executa quan es crea l'objecte
    public function __construct($id = "", $nom = "", $imatge = "", $amo = "", $raça = "")
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->imatge = $imatge;
        $this->amo = $amo;
        $this->raça = $raça;
    }

    //MÈTODES

    /**
     * Obtenir tots els concursants participants.
     * 
     * @return bool
     * @return array
     */
    static function obtenirConcursants(): bool | array
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("SELECT `id`, `nom`, `imatge`, `amo`, `raça` FROM concursant");

        $sentencia->execute();
        $sentencia->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Gos');
        return $sentencia->fetchAll();
    }

    /**
     * Obtenir tots els concursants d'una determinada fase per la data donada.
     * 
     * @param string $data
     * @return bool
     * @return array
     */
    static function obtenirConcursantsPerFase(string $data): bool | array
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("SELECT `id`, `nom`, `imatge`, `amo`, `raça` FROM concursant 
        INNER JOIN concursantfase on concursant.id = concursantfase.ID_CONCURSANT 
        INNER JOIN fase ON concursantfase.NUMERO_FASE = fase.NUMERO 
        WHERE ? BETWEEN fase.DATAINICI AND fase.DATAFI");

        $sentencia->execute(array($data));
        $sentencia->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Gos');
        return $sentencia->fetchAll();
    }

    /**
     * Obtenir el número total de concursants participants.
     * 
     * @return bool
     * @return array
     */
    static function contadorConcursants(): bool | array
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("SELECT COUNT(`nom`) AS 'CONTADOR' FROM concursant");

        $sentencia->execute();
        return $sentencia->fetch();
    }

    /**
     * Insereix un nou concursant a la Base de Dades.
     * 
     * @return bool
     * @return array
     */
    public function insertarConcursant(): bool | array
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("INSERT INTO concursant (`nom`, `imatge`, `amo`, `raça`) VALUES(?, ?, ?, ?)");
        return $sentencia->execute(array($this->nom, $this->imatge, $this->amo, $this->raça));
    }

    /**
     * Actualitza les noves dades del concursant donat.
     * 
     * @return bool
     * @return array
     */
    public function actualitzarConcursant(): bool | array
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("UPDATE concursant SET nom=?,imatge=?,amo=?,raça=? WHERE `id`= ?");
        return $sentencia->execute(array($this->nom, $this->imatge, $this->amo, $this->raça, $this->id));
    }

    /**
     * Conèixer el vot de l'usuari de la sessió actual i fase actual.
     * 
     * @param string $faseActual
     * @param string $sessionId
     * @return bool
     * @return Gos
     */
    static function saberConcursantVotat(string $faseActual, string $sessionId): bool | Gos
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("SELECT concursant.id, concursant.nom, concursant.imatge, concursant.amo, concursant.raça FROM concursant INNER JOIN vot ON vot.id_concursant = concursant.id 
        WHERE `num_fase`= ? AND `session_id`= ?");

        $sentencia->execute(array($faseActual, $sessionId));
        $sentencia->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Gos');
        return $sentencia->fetch();
    }

    /**
     * Insereix percentatges als concursants depenent el número de fase.
     * 
     * @param string $percentatge
     * @param string $idConcursant
     * @param string $numFase
     * @return bool
     * @return array
     */
    static function insertarPercentatges(string $percentatge, string $idConcursant, string $numFase): bool | array
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("UPDATE concursantfase SET `percentatge`= ? WHERE `id_concursant`= ? AND `numero_fase`= ?");
        return $sentencia->execute(array($percentatge, $idConcursant, $numFase));
    }

    /**
     * Saber si hi han percentatges iguals en aquella Fase.
     * 
     * @param string $numFase
     * @return bool
     * @return array
     */
    static function veureDuplicitatPercentatges(string $numFase): bool | array
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("SELECT id_concursant, numero_fase, percentatge FROM concursantfase WHERE percentatge in (SELECT percentatge FROM concursantfase GROUP BY percentatge HAVING COUNT(*) > 1) AND `numero_fase`= ?");
        $sentencia->execute(array($numFase));
        return $sentencia->fetchAll();
    }

    /**
     * Insereix el concursant eliminat d'aquella fase.
     * 
     * @param string $idConcursant
     * @param string $numFase
     * @return bool
     * @return array
     */
    static function insertarEliminat(string $idConcursant, string $numFase): bool | array
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("UPDATE concursantfase SET `eliminat`= ? WHERE `id_concursant`= ? AND `numero_fase`= ?");
        return $sentencia->execute(array(true, $idConcursant, $numFase));
    }

    /**
     * Insereix els concursants guanyadors i per la següent fase obtenir resultats a la pàgina de resultats.
     * 
     * @param string $idConcursant
     * @param string $numFase
     * @return bool
     * @return array
     */
    static function insertarSeguentFase(string $idConcursant, string $numFase): bool | array
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("INSERT INTO concursantfase (`id_concursant`, `numero_fase`, `percentatge`, `eliminat`) VALUES(?,?,?,?)");
        return $sentencia->execute(array($idConcursant, $numFase, 0,0));
    }

    /**
     * Obtenir els concursants d'una determinada fase.
     * 
     * @param string $numFase
     * @return bool
     * @return array
     */
    static function obtenirConcursantsFase(string $numFase): bool | array
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("SELECT `id`, `nom`, `imatge`, `amo`, `raça` FROM concursant 
        INNER JOIN concursantFase on concursant.id = concursantFase.ID_CONCURSANT 
        INNER JOIN fase ON concursantFase.NUMERO_FASE = fase.NUMERO 
        WHERE concursantFase.NUMERO_FASE= ?");

        $sentencia->execute(array($numFase));
        $sentencia->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Gos');
        return $sentencia->fetchAll();
    }

    /**
     * Elimina tots els concursants guanyadors de la taula de guanyadors.
     * 
     * @return bool
     */
    static function eliminarGuanyadors(): bool
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("DELETE FROM concursantfase");
        return $sentencia->execute();
    }

    /**
     * Contador de concursants guanyadors.
     * 
     * @return bool
     * @return array
     */
    static function contadorConcursantsFase(): bool | array
    {
        $crearConnexio = new PDOConn();
        $connexio = $crearConnexio->establirConnexio();

        if (!$connexio) return false;

        $sentencia = $connexio->prepare("SELECT COUNT(`id_concursant`) AS 'CONTADOR' FROM concursantfase");

        $sentencia->execute();
        return $sentencia->fetch();
    }
}

/**
 * By: 01001001 01110110 01100001 01101110
 */