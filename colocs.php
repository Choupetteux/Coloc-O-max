<?php

require_once 'php/utilisateurs.class.php';
require_once 'php/colocation.class.php';

require_once 'WebPage.Class.php' ;
require_once 'php/session.class.php' ;
require_once 'php/visiteur.php' ;


Session::start();

$loggedin = isset($_SESSION['loggedin']);
if(!$loggedin){
    $_SESSION['user']->redirection("index.php");
}

$p = new WebPage($loggedin, "Colocations | ColocOmax") ;

$p->appendCssUrl("css/style-coloc.css") ;

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
$p->appendJsUrl("lib/jquery/jquery-currentpage.js");


/*
$p->appendJS(<<<JAVASCRIPT

JAVASCRIPT
);
*/

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

$p->appendContent(<<<HTML
 
<section id="landing">
  <div class="landing-text">
    <div class="row">
        <div class="col-lg-3"></div>

        <div class="col-lg-6">
            <h3 class="title">Il semblerait que vous n'ayez pas encore enregistré dans une colocation ! </h3>
            <p> Vous pouvez en rejoindre une ou une créez une, faites votre choix.</p>

        </div>

        <div class="col-lg-3"></div>   
    </div>
    <div class="row">
      <div class="col-lg-1"></div>
      <div class="col-lg-5">
        <button type="button" id="create-btn" class="btn btn-primary btn-lg btn-block">Créer une colocation</button>
      </div>
      <div class="col-lg-5">
        <button type="button" id="join-btn" class="btn btn-success btn-lg btn-block">Rejoindre une colocation</button>
      </div>
      <div class="col-lg-1"></div>
    </div>

    <div class="row">
    <div class="col-lg-1"></div>
    

<div class="col-lg-5">
    <div id="form-create" style="display:none;" class="row">
      <div class="col-xs-2 col-lg-4"></div>
        <div class="col-xs-8 col-lg-4 form-div animated flipInX">
            <form id="form-create" method="post">
                <label for="nom">Nom de la colocation*</label><br>
                <input name="nom" id="nom" type="text" required>
                <br>
                <label for="ville">Ville*</label><br>
                <input name="ville" id="ville" type="text" required>
                <br>
                <label for="ville">Adresse</label><br>
                <input name="ville" id="ville" type="text" required>
                <br>
                <input class="btn btn-primary" name="join" type="submit" value="Créer">
                <br>
                </form>
            </div>
        <div class="col-xs-2 col-lg-5"></div>
    </div>
    </div>
    <div class="col-lg-5">

      <div id="form-join" style="display:none;" class="row">
        <div class="col-xs-2 col-lg-4"></div>
          <div class="col-xs-8 col-lg-4 form-div animated flipInX">
              <form id="form-join" method="post">
                  <label for="code">Code de la colocation*</label><br>
                  <input name="code" id="code" type="text" placeholder="AAA-BBB-CCC" required>
                  <br>
                  <input class="btn btn-primary" name="join" type="submit" value="Rejoindre">
                  <br>
                  </form>
              </div>
          <div class="col-xs-2 col-lg-5"></div>
    </div>
</div>
</div>
  </div>
</section>

HTML
);

$p->appendJS(<<<JS
  $(document).ready(function() { 
    var url = window.location.pathname.split("/");
    url = url.splice(url.length-1,1)[0].split(".").splice(0,1);
    switch(url[0]){
    case 'dashboard':
      $('#dashboard').addClass("current-page");
      break;
    case 'colocs':
      $('#colocs').addClass("current-page");
      break;
    }
    
    $('#username').text("@{$_SESSION['user']->getPseudo()}");

    $("#join-btn").click(function(){
        $("#form-join").toggle();
    });

    $("#create-btn").click(function(){
        $("#form-create").toggle();
    });
  })
JS
);


echo $p->toHTML() ;

?>