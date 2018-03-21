<?php

require_once 'php/utilisateurs.class.php';
require_once 'php/colocation.class.php';

require_once 'WebPage.Class.php' ;
require_once 'php/session.class.php' ;
require_once 'php/visiteur.php' ;

Session::start();

//Redirige l'utilisateur si il n'est pas connecté.
$loggedin = isset($_SESSION['loggedin']);
if(!$loggedin){
    $_SESSION['user']->redirection("index.php");
}

$_SESSION['user']->saveLogTime();

$p = new WebPage($loggedin, "Profil | ColocOmax") ;

$p->setTitle('Profil | ColocOmax') ;

$p->appendCssUrl("css/general-style.css");
$p->appendCssUrl("css/style-profil.css");

$p->appendJsUrl("lib/jquery/jquery.min.js");
$p->appendJsUrl("lib/jquery/jquery-migrate.min.js");
$p->appendJsUrl("lib/bootstrap/js/bootstrap.bundle.min.js");
$p->appendJsUrl("lib/easing/easing.min.js");
$p->appendJsUrl("lib/wow/wow.min.js");
$p->appendJsUrl("lib/jquery/jquery-currentpage.js");

$s = WebPage::escapeString('Vous êtes à la fin de <body>.') ;

$p->appendToHead(<<<HTML
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">

  <!--Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
  
  <!-- End Fonts -->

  <link href="/img/favicon.png" rel="icon">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700|Open+Sans:300,300i,400,400i,700,700i" rel="stylesheet">

  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
HTML
);

// exemple récupération donnée utilisateur
// <h1>{$_SESSION['user']->getId()}</h1>

if (isset($_GET['id'])) {
    $user = $_GET['id'];
} else {
    $user = null;
}

$coloc = $_SESSION['user']->getColocation()->getColocationNom();
if($coloc == null){
    $coloc = "Pas encore de coloc'";
}

$p->appendContent(<<<HTML
<div class="row">
    <div class="col-lg-3"></div>

    <div class="col-lg-6 box-profil"> 
        <div class="row">
            <div class="col-lg-6 profil-avatar">
                <img class="avatar-pic" src="{$_SESSION['user']->getAvatarPath()}"></img>
            </div>
            <div class="col-lg-6">
                <h2 class="box-title">{$_SESSION['user']->getPseudo()} ({$coloc})</h2>
                <hr style="border-top:2px solid rgba(0,0,0,.85); margin-right: 3%;">
                <div class="profil-infos">
                    <table>
                        <tr>
                            <th class="box-content" scope="row">Nom : </th>
                            <td>{$_SESSION['user']->getNom()}</td>
                        </tr>
                        <tr>
                            <th class="box-content" scope="row">Prénom : </th>
                            <td>{$_SESSION['user']->getPrenom()}</td>
                        </tr>
                        <tr>
                            <th class="box-content" scope="row">Date de naissance : </th>
                            <td>{$_SESSION['user']->getDateDeNaissance()}</td>
                        </tr>
                    </table>
                    <p class="date-member">Membre depuis le ...</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3"></div>
</div>
HTML
);

echo $p->toHTML() ;
?>
