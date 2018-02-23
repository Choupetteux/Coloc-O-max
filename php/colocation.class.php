<?php

require_once 'myPDO.mysql.colocomax.php';

Class Colocation{

	private $colocation_id = null;
	private $colocation_nom = null;
	private $adresse = null;
	private $ville = null;
	private $colocation_pass = null;

	/*
		Génère un code a 9 lettre séparé tout les 3 caractères par un tiret, le code est régénéré si il existe déjà.
	*/
	public static function generateColocationPass() {
		$str = "";
		$alreadyExist = true;
		$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
		while($alreadyExist == true){
			$max = count($characters) - 1;
			for ($i = 0; $i < 11; $i++) {
				if($i == 3 || $i == 7) {
					$str .= '-';
				}
				else {
					$rand = mt_rand(0, $max);
					$str .= $characters[$rand];
				}
			}
			if(Colocation::getColocationFromPass($str) == false){
				$alreadyExist = false;
			}
		}
		return $str;
	}

	/*
		Retourne un objet colocation récupéré à partir de son pass passé en paramètre.
	*/
	public static function getColocationFromPass($pass) {
	$PDO = myPdo::getInstance()->prepare(
		"SELECT colocation_id, colocation_nom, adresse, ville, colocation_pass
		FROM Colocations
		WHERE colocation_pass = ?");
	$PDO->setFetchMode(PDO::FETCH_CLASS,__CLASS__);
	$PDO->execute(array($pass));
	$coloc = $PDO->fetch();
	return $coloc;
	}

	/*
		Retourne un objet colocation récupéré à partir de son id passé en paramètre
	*/
	public static function getColocationFromId($id) {
		$PDO = myPdo::getInstance()->prepare(
			"SELECT colocation_id, colocation_nom, adresse, ville, colocation_pass
			FROM Colocations
			WHERE colocation_id = ?");
		$PDO->setFetchMode(PDO::FETCH_CLASS,__CLASS__);
		$PDO->execute(array($id));
		$coloc = $PDO->fetch();
		return $coloc;
	}

	/*
		Créer une nouvelle colocation, si le paramètre 'adresse' est vide, la valeur NULL est assigné au champs adresse de la base de données.
		Cette méthode utilise le code généré par la méthode statique generateColocationPass pour donner un pass à chaque colocation.
	*/
	public static function createNewColocation($nom, $ville, $adresse){
		$pass = Colocation::generateColocationPass();
		if(!empty($adresse)){
			$PDO = myPdo::getInstance()->prepare(
				"INSERT INTO Colocations (colocation_nom, adresse, ville, colocation_pass)
				VALUES (?, ?, ?, ?)");
			$PDO->execute(array($nom, $adresse, $ville, $pass));
		}
		else{
			$PDO = myPdo::getInstance()->prepare(
				"INSERT INTO Colocations (colocation_nom, adresse, ville, colocation_pass)
				VALUES (?, ?, ?, ?)");
			$PDO->execute(array($nom, NULL, $ville, $pass));
		}
		return $pass;
	}

	//Retourne l'id de la colocation.
	public function getColocationId(){
		return $this->colocation_id;
	}

	//Retourne le nom de la colocation.
	public function getColocationNom(){
		return $this->colocation_nom;
	}


}