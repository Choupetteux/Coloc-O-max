<?php

require_once 'php/utilisateurs.class.php';

require_once 'WebPage.Class.php' ;
require_once 'php/session.class.php' ;
require_once 'php/visiteur.php' ;

Session::start();

$loggedin = isset($_SESSION['loggedin']);


$p = new WebPage($loggedin, "Connexion | ColocOmax") ;

$p->setTitle('Connexion | ColocOmax') ;

$p->appendCssUrl("css/general-style.css");
$p->appendCssUrl("css/style-sign-in.css");

/*
$p->appendCSS(<<<CSS

CSS
);
*/
$p->appendJsUrl("lib/jquery/jquery.min.js");
$p->appendJsUrl("lib/jquery/jquery-migrate.min.js");
$p->appendJsUrl("lib/bootstrap/js/bootstrap.bundle.min.js");
$p->appendJsUrl("lib/easing/easing.min.js");
$p->appendJsUrl("lib/wow/wow.min.js");

$s = WebPage::escapeString('Vous êtes à la fin de <body>.') ;

$mauvais="";
if(isset($_POST['signin'])){
    if($_SESSION['user']->connexion($_POST['pseudo'], $_POST['mdp'])){
        $_SESSION['user']->redirection('index.php');
    }
    else{
        $mauvais = "Mauvais pseudo ou mot de passe.<br/>";
    }
}

$p->appendToHead(<<<HTML
  <meta charset="utf-8">
  <title></title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">

  <!--Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
  
  <!-- End Fonts -->

  <link href="img/favicon.png" rel="icon">

  <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700|Open+Sans:300,300i,400,400i,700,700i" rel="stylesheet">

  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
HTML
);

$p->appendContent(<<<HTML
<header id="banner">
    <div class="row">
        <h1 class="col-xs-12 col-lg-12 animated flipInX" ><a href="index.php">Coloc'O'max</a></h1>
    </div>
</header>

<section id="sign-in">
    <p class="landing-text animated flipInX">Connectez-vous pour pouvoir utiliser toutes nos fonctionnalités.</p>
    
    <div class="row">
        <div class="col-xs-2 col-lg-5"></div>
        <div class="col-xs-8 col-lg-2 form-div animated flipInX">
            <form id="form-sign-in" method="post">
                <label for="pseudo">Pseudo :</label><br>
                <input name="pseudo" id="pseudo" type="text" required>
                <br>
                <label for="mdp">Mot de passe :</label><br>
                <input name="mdp" id="mdp" type="password" required>
                <br>
                <br>
                <input class="btn btn-primary" name="signin" type="submit" value="Se connecter">
                <br>
                <hr>
                <p> Pas de compte ? <a href="inscription.php">Inscrivez-vous !</a></p>
                </form>
            </div>
        <div class="col-xs-2 col-lg-5"></div>
    </div>
    {$mauvais}
</section>

HTML
);

$p->appendJs(<<<JS
  $(document).ready(function() { 
    $("#header").hide();
  })
JS
);

echo $p->toHTML();

//$html = file_get_contents('path/to/somefile.htm');