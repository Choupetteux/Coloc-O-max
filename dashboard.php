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
$p->appendContent(<<<HTML
<div id="landing">
  <div id="dash-colocataires" class="row">
HTML
);

//Insérer la page ici
if(is_null($_SESSION['user']->getColocation())){

}
else{ 
  $colocataires = $_SESSION['user']->getColocation()->getListeColocataire();
  foreach($colocataires as $key => $coloc){
    $p->appendContent(<<<HTML
      <div class="col-lg-1 col-centered">
        <img class="img-fluid dash-avatar" id="avatar-{$key}" src="img/lily.jpg"><a href=#></a></img>
        <p class="name-avatar hidden" id="name-{$key}">{$coloc->getPseudo()}</p>
      </div>
HTML
  );
    $p->appendJs(<<<JS
      $(document).ready(function() { 
        $("#avatar-{$key}").on({
          mouseenter: function () {
              $("#name-{$key}").fadeIn(500).removeClass("hidden")
          },
          mouseleave: function () {
              $("#name-{$key}").fadeOut(500).addClass("hidden");
          }
        });
      });
JS
    );
  }
}
//Fin de tag du div landing
$p->appendContent(<<<HTML
  </div>
</div>
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