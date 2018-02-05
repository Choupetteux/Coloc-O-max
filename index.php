<?php

require_once 'php/utilisateurs.class.php';

require_once 'WebPage.Class.php' ;

$username = Utilisateur::getPseudo();

$p = new WebPage() ;

$p->setTitle('ColocOmax') ;

$p->appendCssUrl("css/style.css") ;

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


/*
$p->appendJS(<<<JAVASCRIPT

JAVASCRIPT
);
*/

$s = WebPage::escapeString('Vous êtes à la fin de <body>.') ;

$p->appendToHead(<<<HTML
  <meta charset="utf-8">
  <title>Ribbi Luigi's Landing page</title>
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
<header id="header">
        <div class="row">
            <h1 class="col-lg-4" ><a href="#landing" class="scrollto">Coloc'O'max</a></h1>
            <div id="navbar" class="col-lg-6">
                <div class="row">
                    <h3><a href="" class="col-lg-2">Dashboard</a></h3>
                    <h3><a href="" class="col-lg-2">Dépenses</a></h3>
                    <h3><a href="" class="col-lg-2">Colocs</a></h3>
                    <h3><a href="" class="col-lg-2">Agenda</a></h3>
                    <h3><a href="" class="col-lg-2">Paramètres</a></h3>
                </div>  
            </div>
            <div id="profile" class="col-lg-2">
                <p id="username">@{$username}</p>
                <img class="img-fluid" id="avatar" src="img/lily.jpg"><a href=#></a></img>
            </div>
        </div>
    </header>

<section id="landing">
<div class="landing-text">
    <div class="row">
        <div class="col-lg-2"></div>

        <div class="col-lg-8">
            <h1 class="title animated zoomIn">Prêt pour une coloc' ?</h1>
            <p class="animated zoomIn">Revenez plus tard, nous sommes en train de paufiner la future plateforme qui facilitera les points importants de votre colocation !</p>
        </div>

        <div class="col-lg-2"></div>   
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