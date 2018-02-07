<?php

require_once 'php/utilisateurs.class.php';

require_once 'WebPage.Class.php' ;
require_once 'php/session.class.php' ;
require_once 'php/visiteur.php' ;

Session::start();

$p = new WebPage() ;

$p->setTitle('Inscription | ColocOmax') ;

$p->appendCssUrl("css/style-sign-up.css") ;

/*
$p->appendCSS(<<<CSS

CSS
);
*/
$p->appendJsUrl("js/main.js");
$p->appendJsUrl("lib/jquery/jquery.min.js");
$p->appendJsUrl("lib/jquery/jquery-migrate.min.js");
$p->appendJsUrl("lib/bootstrap/js/bootstrap.bundle.min.js");
$p->appendJsUrl("lib/easing/easing.min.js");
$p->appendJsUrl("lib/wow/wow.min.js");
$p->appendJsUrl("lib/jquery/login_effect.js");

$s = WebPage::escapeString('Vous êtes à la fin de <body>.') ;

if(isset($_POST['signup'])){
    $champs = array('nom', 'prenom', 'pseudo', 'mdp');
    $newpost = array_map ( 'htmlspecialchars' , $_POST );
    $erreur = false;
    foreach($champs AS $nomChamps){
        if(!isset($newpost[$nomChamps]) || empty($newpost[$nomChamps])){
            echo 'Le champ' . $nomChamps . 'est manquant. <br>';
            $erreur = true;
            }
    }
    if(!$erreur){
        $_SESSION['user']->inscription($newpost['nom'],$newpost['prenom'],$newpost['pseudo'],$newpost['mdp']);
    }
}

$p->appendToHead(<<<HTML
  <meta charset="utf-8">
  <title>Inscription | ColocOmax</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">

  <!--Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
  
  <!-- End Fonts -->

  <link href="img/favicon.png" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">

  <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700|Open+Sans:300,300i,400,400i,700,700i" rel="stylesheet">

  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="lib/magnific-popup/magnific-popup.css" rel="stylesheet">
HTML
);

$p->appendContent(<<<HTML
<header id="banner">
    <div class="row">
        <h1 class="col-xs-12 col-lg-12" ><a href="index.php">Coloc'O'max</a></h1>
    </div>
</header>

<section id="sign-up">
    <p class="landing-text">Rejoignez-nous et profitez d'une colocation sans stress !</p>
    <div class="row">
        <div class="col-xs-2 col-lg-5"></div>
        <div class="col-xs-8 col-lg-2 form-div">
            <form id="form-sign-up" method="post">
                <label for="nom">Nom :</label><br>
                <input name="nom" id="nom" type="text" required>
                <br>
                <label for="prenom">Prenom :</label><br>
                <input name="prenom" id="prenom" type="text" required>
                <br>
                <label for="pseudo">Pseudo :</label><br>
                <input name="pseudo" id="pseudo" type="text" required>
                <br>
                <label for="mdp">Mot de passe :</label><br>
                <input name="mdp" id="mdp" type="password" required>
                <br>
                <br>
                <input class="btn btn-primary" name="signup" type="submit" value="S'inscrire">
                <br>
                <hr>
                <p> Déjà inscrit ? <a href="connexion.php">Connectez-vous !</a></p>
        </div>
        <div class="col-xs-2 col-lg-5"></div>
    </div>
</section>

HTML
);


echo $p->toHTML() ;