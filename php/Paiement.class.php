<?php

require_once 'myPDO.mysql.colocomax.home.php';
require_once 'utilisateurs.class.php';

Class Paiement{

    private $paiement_id = null;
    private $montant = null;
    private $raison = null;
    private $utilisateur_id_payeur = null;
    private $utilisateur_id_receveur = null;

    public function getPaiementId(){
        return $this->paiement_id;
    }

    public function getMontant(){
        return $this->montant;
    }

    public function getRaison(){
        return $this->raison;
    }

    public function getIdPayeur(){
        return $this->utilisateur_id_payeur;
    }

    public function getIdReceveur(){
        return $this->utilisateur_id_receveur;
    }

    /**
     * Retourne les paiements envoyés à un utilisateur donné.
     *
     * @param string id de l'utilisateur qui a reçus les paiements
     * @return array Liste des paiements reçus par l'utilisateur
     */
    public static function getPaiementSentTo($idReceveur){
        $PDO = myPdo::getInstance()->prepare(
            "SELECT paiement_id, montant, raison, utilisateur_id_payeur, utilisateur_id_receveur
            FROM Paiements
            WHERE utilisateur_id_receveur = ?");
        $PDO->setFetchMode(PDO::FETCH_CLASS,__CLASS__);
        $PDO->execute(array($idReceveur));
        $paiements = $PDO->fetchAll();
        return $paiements;
    }

    /**
     * Retourne les paiements émis par un utilisateur donné.
     *
     * @param string id de l'utilisateur qui a émis les paiements
     * @return array Liste des paiements émis par l'utilisateur
     */
    public static function getPaiementSentBy($idPayeur){

    }

    public static function createNewPaiement($montant, $raison, $idPayeur, $idReceveur){
        if($montant <= 0){
            throw new Exception("Le montant ne peut pas nul ou négatif.");
        }
        elseif(is_null(Utilisateur::getUtilisateurFromID($idPayeur)) || is_null(Utilisateur::getUtilisateurFromID($idReceveur))){
            throw new Exception("Le payeur ou le receveur n'existe pas.");
        }
        else{
            try{
                $PDO = myPdo::getInstance()->prepare(
                    "INSERT INTO Paiements (montant, raison, utilisateur_id, utilisateur_receveur)
                    VALUES (?, ?, ?, ?)");
                $PDO->execute(array($montant, $raison, $idPayeur, $idReceveur));
            }
            catch(PDOException $e){
                echo $e->getMessage();
            }
        }
    }

    public function setRaison($newRaison){
        $this->raison = $newRaison;
        try{
            $PDO = myPdo::getInstance()->prepare(
                "UPDATE Paiements
                set raison = ?
                WHERE paiement_id = ?"
            );
            $PDO->execute(array($this->raison, $this->paiement_id));
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }


}

?>