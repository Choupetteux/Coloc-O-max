<?php

require_once 'myPDO.mysql.colocomax.php';

Class Colocation{

	private $colocation_id = null;
	private $colocation_nom = null;
	private $adresse = null;
	private $ville = null;
	private $colocation_pass = null;

	public static function generateColocationPass() {
		$str = "";
		$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
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
		return $str;
	}


}