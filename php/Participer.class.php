<?php

require_once 'myPDO.mysql.colocomax.php';
require_once 'utilisateurs.class.php';
require_once 'Paiement.class.php';

Class Participer{

    private $type= null;
    private $montant = null;
    private $paiement_id = null; 
    private $utilisateur_id = null;

    public function getPaiementId(){
        return $this->paiement_id;
    }

    public function getMontant(){
        return $this->montant;
    }

    public function getType(){
        return $this->type;
    }

    public function getUtilisateurId(){
        return $this->utilisateur_id;
    }

    public static function createNewParticipation($type, $montant, $paiement_id, $utilisateur_id){
        try{
            $PDO = myPdo::getInstance()->prepare(
                "INSERT INTO participer (type, montant, paiement_id, utilisateur_id)
                VALUES (?, ?, ?, ?)");
            $PDO->execute(array($type, $montant, $paiement_id, $utilisateur_id));
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public static function getEveryParticipation($paiement_id){
        $PDO = myPdo::getInstance()->prepare(
            "SELECT type, montant, paiement_id, utilisateur_id
            FROM participer
            WHERE paiement_id = ?");
        $PDO->setFetchMode(PDO::FETCH_CLASS,__CLASS__);
        $PDO->execute(array($paiement_id));
        $participations = $PDO->fetchAll();
        return $participations;
    }

    public static function getParticipationFromIds($paiement_id, $utilisateur_id){
        $PDO = myPdo::getInstance()->prepare(
            "SELECT par.type, par.montant, par.paiement_id, par.utilisateur_id
            FROM  participer par
            WHERE par.utilisateur_id = ?
            AND   par.paiement_id = ?");
        $PDO->setFetchMode(PDO::FETCH_CLASS,__CLASS__);
        $PDO->execute(array($utilisateur_id, $paiement_id));
        $participation = $PDO->fetch();
        return $participation;
    }

}


?>