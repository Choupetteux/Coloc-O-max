<?php

require_once 'php/utilisateurs.class.php';

require_once 'WebPage.Class.php' ;
require_once 'php/session.class.php' ;
require_once 'php/visiteur.php' ;

Session::start();

$loggedin = isset($_SESSION['loggedin']);

$p = new WebPage($loggedin, "Inscription | ColocOmax") ;

$p->appendCssUrl("css/general-style.css") ;
$p->appendCssUrl("css/style-sign-up.css") ;

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
        $result = $_SESSION['user']->inscription($newpost['nom'],$newpost['prenom'],$newpost['pseudo'],$newpost['mdp']);
    }
}

if(isset($result)){
    if($result){
        $confirm = <<<HTML
            <div id="confirm-div" class="row">
            <div class="col-lg-5"></div>
            <div class="col-lg-2 confirm">
            <h3 style='text-align:center;color:#2B2735;font-size:1.5em;' class='sign-up-confirm'>Votre compte a bien été crée ! </h3>
            </div>
            <div class"col-lg-5"</div>
        </div>
HTML;
    }
    else{
        $confirm = <<<HTML
            <div id="error-div" class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4 error">
            <h3 style='text-align:center;color:#2B2735;font-size:1.5em;' class='sign-up-confirm'> Le pseudo est déjà utilisé, veuillez en choisir un autre. </h3>
            </div>
            <div class"col-lg-4"</div>
        </div>
HTML;
    }
}
else{
    $confirm = "";
}

$p->appendToHead(<<<HTML
  <meta charset="utf-8">
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

<section id="sign-up">
    <p class="landing-text animated flipInX">Rejoignez-nous et profitez d'une colocation sans stress !</p>
    
    <div class="row">
        <div class="col-xs-2 col-lg-5"></div>
        <div class="col-xs-8 col-lg-2 form-div animated flipInX">
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
                </form>
            </div>
        <div class="col-xs-2 col-lg-5"></div>
    </div>
    {$confirm}
</section>

HTML
);

$p->appendJs(<<<JS
  $(document).ready(function() { 
    $("#header").hide();
  })

  $(window).scroll(function() {
    if ($(this).scrollTop() > 50) {
      $('.back-to-top').fadeIn('slow');
      $('#banner').addClass('header-fixed');
    } else {
      $('.back-to-top').fadeOut('slow');
      $('#banner').removeClass('header-fixed');
    }
    });
    $('.back-to-top').click(function() {
      $('html, body').animate({
        scrollTop: 0
      }, 1500, 'easeInOutExpo');
      return false;
    });
JS
);

echo $p->toHTML() ;