<?php
/**
 * Created by PhpStorm.
 * User: choup
 * Date: 23/03/2018
 * Time: 23:26
 */

class Contribuer
{
    private $montant;
    private $facture_id;
    private $utilisateur_id;

    public static function createNewContribution($montant, $facture_id, $utilisateur_id)
    {
        try {
            $PDO = myPdo::getInstance()->prepare(
                "INSERT INTO contribuer ( montant, facture_id, utilisateur_id)
                VALUES ( ?, ?, ?)");
            $PDO->execute(array($montant, $facture_id, $utilisateur_id));
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @return mixed
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * @param mixed $montant
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;
    }

    /**
     * @return mixed
     */
    public function getFactureId()
    {
        return $this->facture_id;
    }

    /**
     * @param mixed $facture_id
     */
    public function setFactureId($facture_id)
    {
        $this->facture_id = $facture_id;
    }

    /**
     * @return mixed
     */
    public function getUtilisateurId()
    {
        return $this->utilisateur_id;
    }

    /**
     * @param mixed $utilisateur_id
     */
    public function setUtilisateurId($utilisateur_id)
    {
        $this->utilisateur_id = $utilisateur_id;
    }
}