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
        <h1 class="col-lg-12" ><a href="index.php">Coloc'O'max</a></h1>
    </div>
</header>

<section id="sign-up">
    <p class="landing-text">Lorem Ipsum Very mucho doggo</p>
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6 form-div">
            <p>Lorem Ipsum</p><br>
            <p>Lorem Ipsum</p><br>
            <p>Lorem Ipsum</p><br>
            <p>Lorem Ipsum</p><br>
            <p>Lorem Ipsum</p><br>
        </div>
        <div class="col-lg-3"></div>
    </div>
</section>

HTML
);


echo $p->toHTML() ;