<?php
require_once "session.class.php";
require_once "utilisateurs.class.php";
Session::start();
if(!isset($_SESSION['user'])){
    $user = new Utilisateur();
    $_SESSION['user'] = $user;
}
else{
    $user = $_SESSION['user'];
}

?>
