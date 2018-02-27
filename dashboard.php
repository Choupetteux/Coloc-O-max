<?php

require_once 'php/utilisateurs.class.php';

require_once 'WebPage.Class.php' ;
require_once 'php/session.class.php' ;
require_once 'php/visiteur.php' ;

Session::start();

$loggedin = isset($_SESSION['loggedin']);
if(!$loggedin){
    $_SESSION['user']->redirection("index.php");
}

$p = new WebPage($loggedin, "ColocOmax") ;

$p->setTitle('ColocOmax') ;

$p->appendCssUrl("css/style-dash.css") ;

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
//Liste colocataires

//Ajoute le premier tag du div landing
if($_SESSION['user']->hasColocation()){
$p->appendContent(<<<HTML
<div id="dash-colocataires" class="row">
HTML
);

//Création du html affichant chaque colocataire et le script jquery associé
  $colocataires = $_SESSION['user']->getColocation()->getListeColocataire();
  foreach($colocataires as $key => $coloc){
    $p->appendContent(<<<HTML
      <div class="col-lg-2 col-centered text-center">
        <img class="img-fluid dash-avatar" id="avatar-{$key}" src="img/lily.jpg"><a href=#></a></img>
        <p class="name-avatar hidden" id="name-{$key}">{$coloc->getPseudo()}</p>
      </div>
HTML
  );
    //Append le Jquery pour afficher le pseudo on hover pour chaque bloc de colocataire
    $p->appendJs(<<<JS
      $(document).ready(function() { 
        $("#avatar-{$key}").on({
          mouseenter: function () {
              $("#name-{$key}").stop(true, true);
              $("#name-{$key}").fadeIn(200).removeClass("hidden")
          },
          mouseleave: function () {
              $("#name-{$key}").fadeOut(200).addClass("hidden");
          }
        });
      });
JS
    );
}
//Fin de tag du div landing
$p->appendContent(<<<HTML
</div>
HTML
);

//Afficher les 3 blocs contenant les dépenses, activités, agenda
$p->appendContent(<<<HTML
<div class="row">
  <div class="col-lg-3 box-event">
    
  </div>
  <div class="col-lg-3 box-event">

  </div>
  <div class="col-lg-3 box-event">

  </div>
</div>
HTML
);

}
//Sinon si l'utilisateur n'as pas de colocation, afficher un message de bienvenue.
else{
  $p->appendContent(<<<HTML
<div class="landing-text">
    <div class="row">
        <div class="col-lg-3"></div>

        <div class="col-lg-6">
            <h1 class="title animated zoomIn">Vous n'êtes pas dans une colocation !</h1>
            <p class="animated zoomIn">Rejoignez celle de vos colocataires, ou alors créez la votre et partagez la.</p>
            <a href="colocs.php" class="btn-sign-up">Créer ou rejoindre une colocation</a>
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

HTML
);
}


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
    

    $(window).scroll(function() {
    if ($(this).scrollTop() > 50) {
      $('.back-to-top').fadeIn('slow');
      $('#header').addClass('header-fixed');
    } else {
      $('.back-to-top').fadeOut('slow');
      $('#header').removeClass('header-fixed');
    }
    });
    $('.back-to-top').click(function() {
      $('html, body').animate({
        scrollTop: 0
      }, 1500, 'easeInOutExpo');
      return false;
    });
  })
JS
);

echo $p->toHTML() ;
?>