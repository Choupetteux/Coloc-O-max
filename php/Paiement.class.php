<?php

require_once 'myPDO.mysql.colocomax.php';
require_once 'utilisateurs.class.php';
require_once 'Participer.class.php';

Class Paiement{

    private $paiement_id = null;
    private $montant = null;
    private $raison = null; 
    private $typePaiement = null;
    private $utilisateur_id = null;

    public function getPaiementId(){
        return $this->paiement_id;
    }

    public function getMontant(){
        return $this->montant;
    }

    public function getRaison(){
        return $this->raison;
    }

    public function getUtilisateurId(){
        return $this->utilisateur_id;
    }

    public function getTypePaiement(){
        return $this->typePaiement;
    }

    /**
     * Retourne les paiements émis par un utilisateur donné.
     *
     * @param string id de l'utilisateur qui a émis les paiements
     * @return array Liste des paiements émis par l'utilisateur
     */
    public static function getPaiementSentBy($id){
        $PDO = myPdo::getInstance()->prepare(
            "SELECT paiement_id, montant, raison, typePaiement, utilisateur_id
            FROM paiements
            WHERE utilisateur_id = ?");
        $PDO->setFetchMode(PDO::FETCH_CLASS,__CLASS__);
        $PDO->execute(array($id));
        $paiements = $PDO->fetchAll();
        return $paiements;
    }

    public static function getUtilisateurFromPaiementId($id){
        $PDO = myPdo::getInstance()->prepare(
            "SELECT u.utilisateur_id, nom, prenom, DATE_FORMAT(date_de_naissance,'%d/%m/%Y') AS \"date_de_naissance\", sexe, pseudo, passwd, colocation_id, avatar
            FROM utilisateurs u, paiements p
            WHERE p.paiement_id = ?
            AND   u.utilisateur_id = p.utilisateur_id");
        $PDO->setFetchMode(PDO::FETCH_CLASS, 'utilisateur');
        $PDO->execute(array($id));
        $user = $PDO->fetch();
        return $user;
    }

    public static function getRemboursementsSentBy($utilisateur_id){
        $PDO = myPdo::getInstance()->prepare(
            "SELECT paiement_id, montant, raison, typePaiement, utilisateur_id
            FROM paiements
            WHERE utilisateur_id = ?
            AND typePaiement = ?");
        $PDO->setFetchMode(PDO::FETCH_CLASS,__CLASS__);
        $PDO->execute(array($id, "remboursement"));
        $paiements = $PDO->fetchAll();
        return $paiements;
    }

    public static function createNewPaiement($montant, $raison, $typePaiement, $idCreateur){
        if($montant <= 0){
            throw new Exception("Le montant ne peut pas nul ou négatif.");
        }
        elseif(is_null(Utilisateur::getUtilisateurFromID($idCreateur))){
            throw new Exception("Le créateur du paiement n'existe pas");
        }
        else{
            try{
                $PDO = myPdo::getInstance()->prepare(
                    "INSERT INTO paiements (montant, raison, typePaiement, utilisateur_id)
                    VALUES (?, ?, ?, ?)");
                $PDO->execute(array($montant, $raison, $typePaiement, $idCreateur));
                return myPdo::getInstance()->lastInsertId();
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
                "UPDATE paiements
                set raison = ?
                WHERE paiement_id = ?"
            );
            $PDO->execute(array($this->raison, $this->paiement_id));
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public static function supprimerPaiement($paiement_id){
        try{
            $PDO = myPdo::getInstance()->prepare(
                "DELETE FROM paiements
                WHERE paiement_id = ?"
            );
            $PDO->execute(array($paiement_id));
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }


}

?>