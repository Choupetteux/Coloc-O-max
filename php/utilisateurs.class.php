<?php

require_once 'myPDO.mysql.colocomax.home.php';


Class Utilisateur{

    private $utilisateur_id = null;
    private $nom = null;
    private $prenom = null;
    private $date_de_naissance = null;
    private $sexe = null;
    private $pseudo = null;
    private $colocation_id = null;

    public function saveIntoSession(){
        Session::start();
        $_SESSION['user'] = this;
    }

    public static function readFromSession(){
        Session::start();
        if(isset($_SESSION['user']) && $_SESSION['user'] instanceof self){
            return $_SESSION['user'];
        }
    }

    public static function getUtilisateurFromID($id){
        $PDO = myPdo::getInstance()->prepare(
                "SELECT *
                FROM Utilisateurs
                WHERE utilisateur_id = ?");
        $PDO->setFetchMode(PDO::FETCH_CLASS,__CLASS__);
        $PDO->execute(array($id));
        $user = $PDO->fetch();
        return $user;
    }

    public static function getUtilisateurFromPseudo($pseudo){
        $PDO = myPdo::getInstance()->prepare(
                "SELECT *
                FROM Utilisateurs
                WHERE pseudo = ?");
        $PDO->setFetchMode(PDO::FETCH_CLASS,__CLASS__);
        $PDO->execute(array($pseudo));
        $user = $PDO->fetch();
        return $user;
    }
    
    public function getPseudo(){
        return $this->pseudo;
    }

    public function inscription($nom, $prenom, $pseudo, $mdp){
        try{
            if(!self::getUtilisateurFromPseudo($pseudo)){
                $hashPass = password_hash($mdp, PASSWORD_DEFAULT);
                $PDO = myPdo::getInstance()->prepare(<<<SQL
                    INSERT INTO UTILISATEURS (nom, prenom, pseudo, passwd) values (?, ?, ?, ?);
SQL
                 );
                 $PDO->execute(array($nom, $prenom, $pseudo, $hashPass));
                 return true;
            }
            else{
                return false;
            }
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    /*PDO Request Format
    $PDO = myPdo::getInstance()->prepare(***REQUEST***);
    $PDO->execute(array($idAnn, $this->NUMMEMB, $texte));
    */
}