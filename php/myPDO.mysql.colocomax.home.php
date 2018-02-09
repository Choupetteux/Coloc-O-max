<?php
// Singleton de connexion à une base de données
require_once 'myPDO.class.php' ;
// Paramètre de connexion
myPDO::setConfiguration('mysql:host=localhost;dbname=colocomax;charset=utf8', 'root', '') ;

?>
