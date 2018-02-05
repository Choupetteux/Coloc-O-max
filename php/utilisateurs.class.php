<?php

require_once 'myPDO.mysql.colocomax.php';


Class Utilisateur{

    private $UTILISATEUR_ID = null;
    private $NOM = null;
    private $PRENOM = null;
    private $DATE_DE_NAISSANCE = null;
    private $SEXE = null;
    private $PSEUDO = null;
    private $COLOCATION_ID = null;

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

    public static function getPseudo(){
        $PDO = myPDO::getInstance()->prepare(<<<'SQL'
        SELECT PSEUDO
        FROM UTILISATEURS
        WHERE UTILISATEUR_ID = 1
SQL
    );
        $PDO->execute();
        $pseudo = $PDO->fetch();
        return $pseudo;
    }

    /*PDO Request Format
    $PDO = myPdo::getInstance()->prepare(***REQUEST***);
    $PDO->execute(array($idAnn, $this->NUMMEMB, $texte));
    */
}