<?php
/**
 * Created by PhpStorm.
 * User: choup
 * Date: 23/03/2018
 * Time: 22:19
 */
require_once 'myPDO.mysql.colocomax.php';
require_once 'utilisateurs.class.php';
require_once 'Contribuer.class.php';

class Facture
{
    private $facture_id;
    private $libelle;
    private $montant;
    private $utilisateur_id;
    private $dateFacture;

    /**
     * @param $lib String Libellé de la facture
     * @param $montant integer Montant de la facture
     * @param $utilisateur_id integer Créateur de la facture
     * @param $dateFacture String Date à laquelle la facture prend effet
     * @param $contribueursId String[] id utilisateur clé => montant de la facture
     */
    public static function createFacture($lib, $montant, $utilisateur_id, $dateFacture, $contribueursId)
    {
        $date = date("Y-m-d H:i:s", strtotime($dateFacture));
        try {
            $PDO = myPdo::getInstance()->prepare(
                "INSERT INTO factures (libelle, montant, utilisateur_id, dateFacture) 
                    VALUES (?, ?, ?, ?)");
            $PDO->execute(array($lib, $montant, $utilisateur_id, $date));
            $lastId = myPdo::getInstance()->lastInsertId();
            foreach ($contribueursId as $contribueur => $montantContrib) {
                Contribuer::createNewContribution($montantContrib, $lastId, $contribueur);
            }
            $PDO = myPdo::getInstance()->prepare(
                <<<SQL
                    CREATE EVENT `Facture-{$lastId}` ON SCHEDULE
                    EVERY 1 MONTH STARTS ?
                    ON COMPLETION PRESERVE
                    DO
                     BEGIN
                      INSERT INTO `paiements`( `montant`, `raison`, `typePaiement`, `datePaiement`, `paiement_id`, `utilisateur_id`)
                      VALUES (?,?,'Dépense', CURRENT_TIMESTAMP, NULL,?);
                      call facture_proc_{$lastId}($lastId, LAST_INSERT_ID());
                     END
                        
SQL
            );
            $PDO->execute(array($date, $montant, $lib, $utilisateur_id));
            $PDO = myPdo::getInstance()->prepare(
                <<<SQL
                CREATE PROCEDURE `facture_proc_{$lastId}`(IN `factureID` INT, IN `paiementID` INT) NOT DETERMINISTIC CONTAINS SQL SQL SECURITY DEFINER
                BEGIN
                    DECLARE finished INTEGER DEFAULT 0;
                    DECLARE userId INT(11) DEFAULT 0;
                    DECLARE v_montant DOUBLE DEFAULT 0;
                    DECLARE contrib_cursor CURSOR FOR SELECT utilisateur_id, montant FROM contribuer WHERE facture_id = factureID;
                    DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1;
                    OPEN contrib_cursor;
                    contrib_cursor: LOOP FETCH contrib_cursor into userId, v_montant;
                    IF finished = 1 THEN
                        LEAVE contrib_cursor;
                    END IF;
                    INSERT INTO participer (type, montant, paiement_id, utilisateur_id) VALUES ('Dépense',v_montant, paiementID, userId);
                    END LOOP;
                    CLOSE contrib_cursor;
                    END
SQL
            );

            $PDO->execute(array($dateFacture, $montant, $lib, $utilisateur_id));
        } catch (Exception $e) {
            echo $e->getMessage();
        }
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
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param mixed $libelle
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
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

    /**
     * @return mixed
     */
    public function getDateFacture()
    {
        return $this->dateFacture;
    }

    /**
     * @param mixed $dateFacture
     */
    public function setDateFacture($dateFacture)
    {
        $this->dateFacture = $dateFacture;
    }
}