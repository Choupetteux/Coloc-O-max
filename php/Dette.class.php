<?php
require_once 'myPDO.mysql.colocomax.home.php';
require_once 'utilisateurs.class.php';

Class Dette{

    private $dette_id = null;
    private $montant = null;
    private $raison = null;
    private $utilisateur_id_preteur = null;
    private $utilisateur_id_endette = null;

    public function getDetteId(){
        return $this->dette_id;
    }

    public function getMontant(){
        return $this->montant;
    }

    public function getRaison(){
        return $this->raison;
    }

    public function getIdPreteur(){
        return $this->utilisateur_id_preteur;
    }

    public function getIdEndette(){
        return $this->utilisateur_id_endette;
    }

    public function setRaison($newRaison){
        $this->raison = $newRaison;
        try{
            $PDO = myPdo::getInstance()->prepare(
                "UPDATE Dettes
                set raison = ?
                WHERE dette_id = ?"
            );
            $PDO->execute(array($this->raison, $this->dette_id));
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }


}

?>
?>