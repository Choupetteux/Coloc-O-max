<?php
// Singleton de connexion à une base de données
require_once 'myPDO.class.php' ;
// Paramètre de connexion
myPDO::setConfiguration('mysql:host=mysql;dbname=ribb0001;charset=utf8', 'ribb0001', 'Lu19Ri02') ;

?>
