<?php

require_once 'php/utilisateurs.class.php';

require_once 'WebPage.Class.php' ;
require_once 'php/session.class.php' ;
require_once 'php/visiteur.php' ;

Session::start();



$p = new WebPage() ;

$p->setTitle('ColocOmax') ;

$p->appendCssUrl("css/style.css") ;

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
$p->appendJsUrl("lib/jquery/login_effect.js");


/*
$p->appendJS(<<<JAVASCRIPT

JAVASCRIPT
);
*/

$s = WebPage::escapeString('Vous êtes à la fin de <body>.') ;

$p->appendToHead(<<<HTML
  <meta charset="utf-8">
  <title>ColocOmax</title>
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

if(isset($_SESSION['loggedin'])){
    include_once('assets/menu/menu_loggedin.html');
}

$p->appendContent(<<<HTML
 
 <section id="landing">
<div class="landing-text">
    <div class="row">
        <div class="col-lg-3"></div>

        <div class="col-lg-6">
            <h1 class="title animated zoomIn">Prêt pour une coloc' ?</h1>
            <p class="animated zoomIn">Revenez plus tard, nous sommes en train de paufiner la future plateforme qui facilitera les points importants de votre colocation !</p>
            <a href="inscription.php" class="btn-sign-up">S'inscrire</a>
        </div>

        <div class="col-lg-3"></div>   
    </div> 
</div>
<div class="landing-img">
    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-lg-8"></div>
        <div class="col-lg-2"></div>
    </div>
</div>
</section>

HTML
);




echo $p->toHTML() ;
?>