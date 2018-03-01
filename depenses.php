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

$p->setTitle('Dépenses | ColocOmax') ;

$p->appendCssUrl("css/general-style.css") ;
$p->appendCssUrl("css/style-depenses.css") ;

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

//Ajoute le premier tag du div landing
if($_SESSION['user']->hasColocation()){
$p->appendContent(<<<HTML
<main>
  
  <input class="tab-input" id="tab1" type="radio" name="tabs" checked>
  <label class="tab" id="tab1-label" for="tab1">Journal des dépenses</label>
    
  <input class="tab-input" id="tab2" type="radio" name="tabs">
  <label class="tab" id="tab2-label" for="tab2">Créer une nouvelle dépense</label>
    
  <input class="tab-input" id="tab3" type="radio" name="tabs">
  <label class="tab" id="tab3-label" for="tab3">Créer une facture récurrente</label>
    
  <section id="content1">
    
  </section>
    
  <section id="content2">
      
    <form method="POST">
        <div class="row">
            <div class="col-lg-4"></div>
            <h4 class="col-lg-4">Ajouter une dépense</h4> 
            <div class="col-lg-4"></div>
        </div>
        <div class="col-lg-12">
            <hr/>
        </div>
        <div class="align-items-center form-row">
            <div class="col-lg-5"></div>
            <select id="payeur" type='select' name='payeur' class="form-control col-lg-2 col-centered text-center">
                <option value="{$_SESSION['user']->getId()}" > {$_SESSION['user']->getPseudo()} </option>
HTML
);
$colocataires = $_SESSION['user']->getColocation()->getListeColocataire();
foreach($colocataires as $key => $coloc){
    if($coloc->getId() == $_SESSION['user']->getId()){
        //Dun do nothin'
    }
    else{
    $p->appendContent(<<<HTML
                <option value="{$coloc->getId()}"> {$coloc->getPseudo()} </option>
HTML
    );
    }
}

$p->appendContent(<<<HTML
            </select>
            <div class="col-lg-5"></div>
            <div class="col-lg-5"></div>
            <label class="col-lg-2 col-centered text-center form-label">a
            <select class="col-lg-8" id="type-depense" type ="select" name="typeDep">
                <option value="depense">dépensé</option>
                <option value="remboursement">remboursé</option>
                <option value="avance">avancé</option>
            </select></label>
            <div class="col-lg-5"></div>
            <div class="col-lg-5"></div>
            <div class="input-group col-lg-2">
                <input class="form-control" id="montant" type="text" name="montant" pattern="[1-9]{1,9}">
                <div class="input-group-prepend">
                    <div class="input-group-text">€</div>
                </div>
            </div>
            <div class="col-lg-5"></div>
            <div class="col-lg-5"></div>
            <label class="col-lg-2 form-label"> pour : </label>
            <div class="col-lg-5"></div>
            <div class="col-lg-4"></div>
            <input class="form-control col-lg-4" id="raison" type="text" name="raison" placeholder="Raison" >
            <div class="col-lg-4"></div>
            <div class="col-lg-12">
                <hr/>
            </div>
            <div class="col-lg-3"></div>
            <label id="choose-msg" class="col-lg-6 form-label"> Choisissez les personnes qui participent à cette dépense :</label>
            <div class="col-lg-3"></div>
            <div class="col-lg-4"></div>
            <label for="typeDep" id="participation-msg" class="col-lg-3 form-label">Elles participents à
            </label>
            <select class="col-lg-2" id="type-depense" type ="select" name="typeDep">
                <option value="partegale">parts égales</option>
                <option value="montant">montants fixes</option>
                <option value="pourcentage">pourcentage fixes</option>
            </select>
            <div id="dash-colocataires" class="row col-lg-12">
HTML
);
  foreach($colocataires as $key => $coloc){
    $p->appendContent(<<<HTML
      <div class="col-lg-2 col-centered text-center">
        <input class="coloc-checkbox" type="checkbox" id="{$coloc->getPseudo()}" name="{$coloc->getPseudo()}">
        <label class="label-coloc" for="{$coloc->getPseudo()}"><img class="img-fluid dash-avatar" id="avatar-{$key}" src="{$coloc->getAvatarPath()}"></img></label>
        <div class="full-height"></div>
        <p class="name-avatar" id="name-{$key}">{$coloc->getPseudo()}</p>
      </div>
HTML
    );
  }

//Fin de tag du div landing
$p->appendContent(<<<HTML
</div>
  </section>
    
  <section id="content3">
    <p>
      Bacon ipsum dolor sit amet beef venison beef ribs kielbasa. Sausage pig leberkas, t-bone sirloin shoulder bresaola. Frankfurter rump porchetta ham. Pork belly prosciutto brisket meatloaf short ribs.
    </p>
    <p>
      Brisket meatball turkey short loin boudin leberkas meatloaf chuck andouille pork loin pastrami spare ribs pancetta rump. Frankfurter corned beef beef tenderloin short loin meatloaf swine ground round venison.
    </p>
  </section>
HTML
);
}

//Sinon si l'utilisateur n'as pas de colocation, afficher un message de bienvenue.
else{
  $p->appendContent(<<<HTML
<div id="no-coloc" class="landing-text">
    <div class="row">
        <div class="col-lg-3"></div>

        <div class="col-lg-6">
            <h1 class="title animated zoomIn">Vous n'êtes pas dans une colocation !</h1>
            <p style="color:#FFF;" class="animated zoomIn">Rejoignez celle de vos colocataires, ou alors créez la votre et partagez la.</p>
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
    case 'depenses':
      $('#depense').addClass("current-page");
      break;
    }
    

    $(window).scroll(function() {
    if ($(this).scrollTop() > 50) {
      $('#header').addClass('header-fixed');
    } else {
      $('#header').removeClass('header-fixed');
    }
    });

    $("#box1").scroll(function() {
    if ($(this).scrollTop() < 100) {
      $('#header').addClass('header-fixed');
    } else {
      $('#header').removeClass('header-fixed');
    }
    });

    $(window).resize(function(){
        if(window.innerWidth < 1200) {
            $(".tab").empty();
        }
        else{
            $(".tab").empty();
            $("#tab1-label").append("Journal des dépenses");
            $("#tab2-label").append("Créer une nouvelle dépense");
            $("#tab3-label").append("Créer une facture récurrente");
        }
    });

    $('#type-depense').change(function() {
        if ($(this).val() === 'depense') {
            $("#choose-msg").empty();
            $("#choose-msg").append("Choisissez les personnes qui participent à cette dépense :");
        }
        else if($(this).val() === 'remboursement'){
            $("#choose-msg").empty();
            $("#choose-msg").append("Choisissez la ou les personnes remboursées :");
            $("#participation-msg").empty();
            $("#participation-msg").append("Elles sont remboursées à");
        }
        else if($(this).val() === 'avance') {
            $("#choose-msg").empty();
            $("#choose-msg").append("Choisissez la ou les personnes avancées :");
            $("#participation-msg").empty();
            $("#participation-msg").append("Elles sont avancées à");
        }
    });
    
  })
JS
);

echo $p->toHTML() ;
?>