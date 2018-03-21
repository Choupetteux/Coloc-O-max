<?php

require_once 'myPDO.mysql.colocomax.php';
require_once 'colocation.class.php';


Class Utilisateur{

    private $utilisateur_id = null;
    private $nom = null;
    private $prenom = null;
    private $date_de_naissance = null;
    private $sexe = null;
    private $pseudo = null;
    private $passwd = null;
    private $colocation_id = null;
    private $avatar = null;
    private $balance = null;

    //Sauvegarde l'instance d'utilisateur dans la session actuelle.
    public function saveIntoSession(){
        Session::start();
        $_SESSION['user'] = this;
    }

    //Lis les données de la session actuelle.
    public static function readFromSession(){
        Session::start();
        if(isset($_SESSION['user']) && $_SESSION['user'] instanceof self){
            return $_SESSION['user'];
        }
    }

    //Récupère une instance d'utilisateur à partir de son ID.
    public static function getUtilisateurFromID($id){
        $PDO = myPdo::getInstance()->prepare(
                "SELECT utilisateur_id, nom, prenom, DATE_FORMAT(date_de_naissance,'%d/%m/%Y') AS \"date_de_naissance\", sexe, pseudo, passwd, colocation_id, avatar
                FROM utilisateurs
                WHERE utilisateur_id = ?");
        $PDO->setFetchMode(PDO::FETCH_CLASS,__CLASS__);
        $PDO->execute(array($id));
        $user = $PDO->fetch();
        return $user;
    }

    //Récupère une instance d'utilisateur à partir de son pseudo. Chaque pseudo est censé être unique.
    public static function getUtilisateurFromPseudo($pseudo){
        $PDO = myPdo::getInstance()->prepare(
                "SELECT utilisateur_id, nom, prenom, DATE_FORMAT(date_de_naissance,'%d/%m/%Y') AS \"date_de_naissance\", sexe, pseudo, passwd, colocation_id, avatar
                FROM utilisateurs
                WHERE pseudo = ?");
        $PDO->setFetchMode(PDO::FETCH_CLASS,__CLASS__);
        $PDO->execute(array($pseudo));
        $user = $PDO->fetch();
        return $user;
    }
    

    //Inscrit un utilisateur dans la base de données et hash son password
    //TODO: Maybe change if time le hash
    public function inscription($nom, $prenom, $pseudo, $mdp){
        try{
            if(!self::getUtilisateurFromPseudo($pseudo)){
                $hashPass = password_hash($mdp, PASSWORD_DEFAULT);
                $PDO = myPdo::getInstance()->prepare(<<<SQL
                    INSERT INTO utilisateurs (nom, prenom, pseudo, passwd) values (?, ?, ?, ?);
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

    //Permet à l'utilisateur de se connecter à partir de son pseudo et son mot de passe.
    public function connexion($pseudo, $mdp){
        try{
            $user = Utilisateur::getUtilisateurFromPseudo($pseudo);
            if($user){
                if(password_verify($mdp, $user->getPass())){
                    $_SESSION['loggedin'] = true;
                    $user->setPass();
                    $_SESSION['user'] = $user;
                    return true;
                }
                else{
                    return false;
                }
            }
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    //Redirige l'utilisateur vers une url.
    public function redirection($url){
        header("Location: $url");
    }

    //Déconnecte l'utilisateur.
    public function deconnexion(){
        unset($_SESSION['user']);
        unset($_SESSION['loggedin']);
        return true;
    }

    //Retourne si l'utilisateur à une colocation.
    public function hasColocation(){
        return !is_null($this->colocation_id);
    }

    //Permet de rejoindre une colocation à partir du pass de cette colocation.
    public function rejoindreColocation($pass){
        $PDO = myPdo::getInstance()->prepare(
            "UPDATE utilisateurs
            set colocation_id = ?
            WHERE utilisateur_id = ?");
        $PDO->execute(array(Colocation::getColocationFromPass($pass)->getColocationId(), $this->utilisateur_id));
        $this->colocation_id = Colocation::getColocationFromPass($pass)->getColocationId();
    }

    //Fais quitter la colocation à l'utilisateur actuelle.
    public function quitterColocation(){
        $PDO = myPdo::getInstance()->prepare(
            "UPDATE utilisateurs
            set colocation_id = null
            WHERE utilisateur_id = ?"
        );
        $PDO->execute(array($this->utilisateur_id));
        $this->colocation_id = null;
    }

    //Récupère la colocation depuis l'id de la colocation de l'utilisateur.
    public function getColocation(){
        if(is_null($this->colocation_id)){
            return null;
        }
        else{
            return Colocation::getColocationFromId($this->colocation_id);
        }
    }


    //Retourne le mot de passe hashé de l'utilisateur.
    public function getPass(){
        return $this->passwd;
    }

    //Retourne le pseudo de l'utilisateur.
    public function getPseudo(){
        return $this->pseudo;
    }

    public function getId(){
        return $this->utilisateur_id;
    }

    public function getNom(){
        return $this->nom;
    }

    public function getPrenom(){
        return $this->prenom;
    }

    public function getAvatarPath(){
        return "assets/uploaded_avatar/" . $this->avatar;
    }

    public function getAvatar(){
        return $this->avatar;
    }

    public function setPass($value = null){
        $this->passwd = $value;
    }

    public function getSexe(){
        return $this->sexe;
    }

    public function getBalance(){
        return $this->balance;
    }

    public function saveLogTime(){
        $PDO = myPdo::getInstance()->prepare(
                "UPDATE utilisateurs
                 SET LASTTIMESEEN = CURRENT_TIMESTAMP()
                 WHERE utilisateur_id = ?");
        $PDO->execute(array($this->utilisateur_id));
    }

    public function isOnline(){
        $PDO = myPdo::getInstance()->prepare(
                "SELECT utilisateur_id
				FROM `utilisateurs`
                WHERE TIMESTAMPDIFF(SECOND, LASTTIMESEEN, CURRENT_TIMESTAMP) < 300
                AND utilisateur_id = ?");
        $PDO->execute(array($this->utilisateur_id));
        $pseudo = $PDO->fetch();
        return $pseudo;
    }

    public function getBalanceEnvers($utilisateur_id){
        $PDO = myPdo::getInstance()->prepare(
            "SELECT SUM(par.montant) AS \"montant\"
             FROM   participer par, paiements pai
             WHERE  par.paiement_id = pai.paiement_id
             AND    par.utilisateur_id = ?
             AND    pai.utilisateur_id = ?
             AND    pai.typePaiement IN ('depense', 'remboursement', 'avance')");
        $PDO->execute(array($this->utilisateur_id, $utilisateur_id));
        $pret = $PDO->fetch()['montant'];
        $PDO = myPdo::getInstance()->prepare(
            "SELECT SUM(par.montant) AS \"montant\"
             FROM   participer par, paiements pai
             WHERE  par.paiement_id = pai.paiement_id
             AND    par.utilisateur_id = ?
             AND    pai.utilisateur_id = ?
             AND    pai.typePaiement IN ('depense', 'remboursement', 'avance')");
        $PDO->execute(array($utilisateur_id, $this->utilisateur_id));
        $remboursement = $PDO->fetch()['montant'];
        if($pret > $remboursement){
            return $pret - $remboursement;
        }
        elseif ($pret < $remboursement) {
            return -($remboursement - $pret);
        }
    }

    /**
     * Récupérer la date de naissance
     * @param string $type le contenu de la date de naissance peut être 
     *
     * @return string La date de naissance au format "DD-MM-YYYY"
     */
    public function getDateDeNaissance(){
        return $this->date_de_naissance;
    }

    public function setAvatar($name){
        $this->avatar = $name;
        $PDO = myPdo::getInstance()->prepare(
            "UPDATE utilisateurs
            set avatar = ?
            WHERE utilisateur_id = ?"
        );
        $PDO->execute(array($name, $this->utilisateur_id));
    }

    public function setSexe($sex){
        $this->sexe = $sex;
        $PDO = myPdo::getInstance()->prepare(
            "UPDATE utilisateurs
            set sexe = ?
            WHERE utilisateur_id = ?"
        );
        $PDO->execute(array($sex, $this->utilisateur_id));
    }

    public function setDateDeNaissance($jourNais, $moisNais, $anneeNais){
        $this->date_de_naissance = $jourNais . "/" . $moisNais . "/" . $anneeNais;
        $PDO = myPdo::getInstance()->prepare(
            "UPDATE utilisateurs
            set date_de_naissance = STR_TO_DATE(?, '%d/%m/%Y')
            WHERE utilisateur_id = ?"
        );
        $PDO->execute(array($this->date_de_naissance, $this->utilisateur_id));
    }

    public function setBalance($value){
        $this->balance = $value;
    }

    public function getPaiementsHistory(){
        $PDO = myPdo::getInstance()->prepare(
            "SELECT DISTINCT pai.paiement_id, pai.montant, pai.raison, pai.typePaiement, pai.utilisateur_id
             FROM   participer par, paiements pai
             WHERE  par.paiement_id = pai.paiement_id
             AND    par.utilisateur_id = ?
             OR     pai.utilisateur_id = ?");
        $PDO->setFetchMode(PDO::FETCH_CLASS,'Paiement');
        $PDO->execute(array($this->utilisateur_id, $this->utilisateur_id));
        $history = $PDO->fetchAll();
        return $history;
    }

    /*PDO Request Format
    $PDO = myPdo::getInstance()->prepare(***REQUEST***);
    $PDO->execute(array($idAnn, $this->NUMMEMB, $texte));
    */
}