<?php
require_once 'php/utilisateurs.class.php';

require_once 'WebPage.Class.php' ;
require_once 'php/session.class.php' ;
require_once 'php/visiteur.php' ;
require_once 'php/Paiement.class.php';
require_once 'php/Participer.class.php';

Session::start();

$loggedin = isset($_SESSION['loggedin']);
if(!$loggedin){
    $_SESSION['user']->redirection("index.php");
}

$_SESSION['user']->saveLogTime();

if(isset($_POST['delete'])){
    Paiement::supprimerPaiement($_POST['paiement']);
}

if(isset($_POST['submit'])){
    $newpost = array_map ( 'htmlspecialchars' , $_POST );
    $erreur = false;
    if( !isset($newpost["payeur"])  ||
        !isset($newpost["typeDep"]) ||
        !isset($newpost["montant"]) ||
        !isset($newpost["typePart"]) ) {
            $erreur = true;
    }
    else{
        foreach($newpost as $i => $champ){
            if($champ == 'on'){
                $arrayId[$i] = str_replace("€", "", $newpost["montant-" . $i]);
            }
        }
        $paiement_id = Paiement::createNewPaiement($newpost['montant'], $newpost['raison'], $newpost['typeDep'], $newpost['payeur']);
        foreach($arrayId as $id => $montant){
            Participer::createNewParticipation($newpost['typePart'], $montant, $paiement_id, $id);
        }
    }
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

<!--Historique des paiements -->
  <section id="content1">
  <div id="accordion" class="col-lg-10 col-centered">
HTML
);
$historique = $_SESSION['user']->getPaiementsHistory();
if(!empty($historique)){
    $i = 0;
    $max = 10;
    /** @var Paiement $paiement */
    foreach($historique as $key => $paiement){
        if($i < $max){
            //
            // TODO : Faire une méthode qui permettrait de gérer plus proprement l'historique.
            //
            $user = Paiement::getUtilisateurFromPaiementId($paiement->getPaiementId());
            //Si autre utilisateur reponsable du paiement
            if ($paiement->getUtilisateurId() != $_SESSION['user']->getId()){
                $deletebtn = "";
                $participationMontant = Participer::getParticipationFromIds($paiement->getPaiementId(), $_SESSION['user']->getId())['montant'];
                if ($paiement->getTypePaiement() == 'Dépense'){
                    $name = $paiement->getTypePaiement() . ' de la part de ' . $user->getPseudo();
                    $msg = 'Vous avez participé à une dépense d\'un montant total de ' . $paiement->getMontant() . ' €';
                    $sign = 'negative';
                } elseif ($paiement->getTypePaiement() == 'Remboursement'){
                    $name = $paiement->getTypePaiement() . ' de la part de ' . $user->getPseudo();
                    $msg = 'Vous avez été remboursé d\'un montant de ' . $participationMontant . ' €';
                    $sign = 'positive';
                } elseif ($paiement->getTypePaiement() == 'Avance'){
                    $name = $paiement->getTypePaiement() . ' de la part de ' . $user->getPseudo();
                    $msg = 'Vous avez été avancé d\'un montant de ' . $participationMontant . ' €';
                    $sign = 'positive';
                }
                if (!empty($paiement->getRaison())){
                    $raison = 'pour la raison suivante :<br><em>' . $paiement->getRaison() . '</em>';
                } else{
                    $raison = '';
                }
            }
            //Si l'utilisateur est responsable du paiement
            elseif($paiement->getUtilisateurId() == $_SESSION['user']->getId()){
                $deletebtn = <<<HTML
                <form method="post">
                <input type="hidden" name="paiement" value="{$paiement->getPaiementId()}"/>
                <input name="delete" type="submit" id="delete-btn" class="btn btn-danger col-centered float-right" value="Supprimer">
                </form>
HTML
;
                if ($paiement->getTypePaiement() == 'Dépense'){
                    $participationMontant = Participer::getParticipationFromIds($paiement->getPaiementId(), $_SESSION['user']->getId())['montant'];
                    $name = 'Vous avez créé une dépense';
                    $msg = 'Vous avez créé une dépense d\'un montant total de ' . $paiement->getMontant() . ' €';
                    $sign = 'negative';
                } elseif ($paiement->getTypePaiement() == 'Remboursement'){
                    $participationMontant = $paiement->getMontant();
                    $name = 'Vous avez envoyé un remboursement.';
                    $msg = 'Vous avez envoyé un remboursement d\'un montant de ' . $participationMontant . ' €';
                    $sign = 'negative';
                } elseif ($paiement->getTypePaiement() == 'Avance'){
                    $participationMontant = $paiement->getMontant();
                    $name = 'Vous avez avancé de l\'argent';
                    $msg = 'Vous avez avancé un montant de ' . $participationMontant . ' €';
                    $sign = 'negative';
                }
                if (!empty($paiement->getRaison())){
                    $raison = 'pour la raison suivante :<br><em>' . $paiement->getRaison() . '</em>';
                } else{
                    $raison = '';
                }
            }

            $p->appendContent(<<<HTML
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link col-lg-12" data-toggle="collapse" data-target="#collapse-{$key}" aria-expanded="true" aria-controls="collapse-{$key}">
                        <span class="float-left">{$name}</span><span class="float-right {$sign}">{$participationMontant} €</span>
                        </button>
                    </h5>
                </div>

                <div id="collapse-{$key}" class="collapse hide" aria-labelledby="heading-{$key}" data-parent="#accordion">
                    <div class="card-body">
                        <p class="depense-msg">{$msg} {$raison}</p>
                        {$deletebtn}
                    </div>
                </div>
        </div>
HTML
            );
            $i++;
        }
        else{
            break;
        }
    }
}

else{
    $p->appendContent(<<<HTML
        <p>Vous n'avez pas encore créé ou participé à une dépenses.</p>  
HTML
);
}

$p->appendContent(<<<HTML
    </div>
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
                <option value="Dépense">dépensé</option>
                <option value="Remboursement">remboursé</option>
                <option value="Avance">avancé</option>
            </select></label>
            <div class="col-lg-5"></div>
            <div class="col-lg-5"></div>
            <div class="input-group col-lg-2">
                <input class="form-control" id="montant" type="text" name="montant" pattern="[1-9]\d*(\.\d{2}$)?" required>
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
            <div class="col-lg-3"></div>
            <label for="typeDep" id="participation-msg" class="col-lg-3 form-label">Elles participents à
            </label>
            <select class="col-lg-2" id="type-participation" type ="select" name="typePart">
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
        <input class="coloc-checkbox" type="checkbox" id="check-{$key}" name="{$coloc->getId()}">
        <label class="label-coloc" for="check-{$key}"><img class="img-fluid dash-avatar" id="avatar-{$key}" src="{$coloc->getAvatarPath()}"></img></label>
        <div class="full-height"></div>
        <p class="name-avatar" id="name-{$key}">{$coloc->getPseudo()}</p>
        <input readonly class="form-control-plaintext money-avatar" name="montant-{$coloc->getId()}" style="opacity:0;" id="money-{$key}" pattern="([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9])?€?" value="0" disabled>
        <div class="input-group" style="display:none;" id="percent-div-{$key}">
            <input class="form-control percent-avatar" id="percent-{$key}">
            <div class="input-group-prepend">
                    <div class="input-group-text">%</div>
            </div>
        </div>
      </div>
HTML
    );

    $p->appendJs(<<<JS
        $(document).ready(function() { 
            $("#check-{$key}").change(function() {
                if($(this).is(":checked")){
                    $("#money-{$key}").fadeTo(200, 1);
                    $("#money-{$key}").removeAttr("disabled");
                }
                else{
                    $("#money-{$key}").fadeTo(200, 0);
                    setTimeout(() => {
                        $("#money-{$key}").val("0");
                    }, 100);
                    $("#percent-div-{$key}").hide();
                    
                }
            })
        });
JS
    );
}

//Fin de tag du div landing
$p->appendContent(<<<HTML
<label for="submit" class="form-label col-lg-12" id="save-label">Le total des pourcentages doit être égal à 100.</label>

</div>
<input name="submit" type="submit" id="save-btn" class="btn btn-primary col-centered save-button" value="Enregistrer la dépense" disabled>
</form>

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
            $("#choose-msg").empty().append("Choisissez les personnes qui participent à cette dépense :");
            $("#participation-msg").empty().append("Elles participent à");
        }
        else if($(this).val() === 'remboursement'){
            $("#choose-msg").empty().append("Choisissez la ou les personnes remboursées :");
            $("#participation-msg").empty().append("Elles sont remboursées à");
        }
        else if($(this).val() === 'avance') {
            $("#choose-msg").empty().append("Choisissez la ou les personnes avancées :");
            $("#participation-msg").empty().append("Elles sont avancées à");
        }
    });


    $("input:checkbox").change(function() {
        if($("#type-participation").val() === 'partegale'){
            var numberChecked = $("input:checkbox:checked").length;
            var montant = $("#montant").val();
            $("input:checkbox:checked").each(function() {
                var key = $(this).attr('id').split("-")[1];
                $("#money-"+key).val((montant / numberChecked).toFixed(2) + "€");
            });
            if($("input:checkbox:checked").length === 0){
                $("#save-btn").attr("disabled", true);
            }
            else{
                $("#save-btn").removeAttr("disabled");
            }
        }
        else if($("#type-participation").val() === "montant"){
            var montantTotal = 0;
            $("input:checkbox:checked").each(function() {
                var key = $(this).attr('id').split("-")[1];
                montantTotal = montantTotal + parseFloat($("#money-"+key).val());
            });
            $("#montant").val(montantTotal);
            if(montantTotal === 0 || isNaN(montantTotal)){
                $('#save-label').empty().append("Le total des montants ne peut-être égal a 0.").show();
                $("#save-btn").attr("disabled", true);
            }
            else{
                $('#save-label').empty().hide();
                $("#save-btn").removeAttr("disabled");
            }
        }
        else if($("#type-participation").val() === "pourcentage"){
            var numberChecked = $("input:checkbox:checked").length;
            var montant = $("#montant").val();
            $("input:checkbox:checked").each(function() {
                var key = $(this).attr('id').split("-")[1];
                var percentage = $("#percent-" + key).val();
                $("#money-"+key).val(((montant * percentage) / 100).toFixed(2) + "€");
                $("#percent-div-"+key).fadeTo(200, 1);
            });
        }
    });

    $("#montant").on('keyup', function() {
        if($("#type-participation").val() === "partegale"){
            var numberChecked = $("input:checkbox:checked").length;
            var montant = $("#montant").val();
            $("input:checkbox:checked").each(function() {
                var key = $(this).attr('id').split("-")[1];
                var percentage = $("#percent-" + key).val();
                $("#money-"+key).val(((montant * percentage) / 100).toFixed(2) + "€");
                $("#money-"+key).val((montant / numberChecked).toFixed(2) + "€");
            })
            if($("input:checkbox:checked").length === 0){
                $("#save-btn").attr("disabled", true);
            }
            else{
                $("#save-btn").removeAttr("disabled");
            }
        }
    });


    $("#type-participation").change(function() {
        if($("#type-participation").val() === "partegale"){
            $('#save-label').empty().hide();
            $(".money-avatar").removeClass("form-control").addClass("form-control-plaintext").attr("readonly", true);
            $("#montant").removeAttr("readonly");
            $("input:checkbox:checked").each(function() {
                var key = $(this).attr('id').split("-")[1];
                $("#money-"+key).removeAttr("required");
                $("#percent-div-"+key).fadeTo(200, 0);
            });
        }
        else if($("#type-participation").val() === "montant"){
            $(".money-avatar").removeClass("form-control-plaintext").addClass("form-control").removeAttr("readonly");
            $("#montant").attr("readonly", true);
            var montantTotal = 0;
            $("input:checkbox:checked").each(function() {
                var key = $(this).attr('id').split("-")[1];
                montantTotal = montantTotal + parseFloat($("#money-"+key).val());
                $("#money-"+key).attr("required",true);
                $("#percent-div-"+key).fadeTo(200, 0);
            });
            if(montantTotal === 0 || montantTotal === ""){
                $('#save-label').empty().append("Le total des montants ne peut-être égal a 0.").show();
                $("#save-btn").attr("disabled", true);
            }
            else{
                $('#save-label').empty().hide();
                $("#save-btn").removeAttr("disabled");
            }
        }
        else if($("#type-participation").val() === "pourcentage"){
            $(".money-avatar").removeClass("form-control").addClass("form-control-plaintext").attr("readonly", true);
            $("#montant").removeAttr("readonly");
            $("input:checkbox:checked").each(function() {
                var key = $(this).attr('id').split("-")[1];
                $("#money-"+key).removeAttr("required");
                $("#percent-div-"+key).fadeTo(200, 1);
            });
        }
    })

    $(".money-avatar").on('keyup', function(){
        if($("#type-participation").val() === "montant"){
            var montantTotal = 0;
            $("input:checkbox:checked").each(function() {
                var key = $(this).attr('id').split("-")[1];
                montantTotal = montantTotal + parseFloat($("#money-"+key).val());
            });
            $("#montant").val(montantTotal);
            if(montantTotal === 0 || isNaN(montantTotal)){
                $('#save-label').empty().append("Le total des montants ne peut-être égal a 0.").show();
                $("#save-btn").attr("disabled", true);
            }
            else{
                $('#save-label').empty().hide();
                $("#save-btn").removeAttr("disabled");
            }
        }
    })

    $(".percent-avatar").on('keyup', function(){
        var montant = $("#montant").val();
        var percentageTotal = 0;
        $("input:checkbox:checked").each(function() {
            var key = $(this).attr('id').split("-")[1];
            var percentage = $("#percent-" + key).val();
            percentageTotal = percentageTotal + parseFloat(percentage);
            $("#money-"+key).val(((montant * percentage) / 100).toFixed(2) + "€");
        });
        if(percentageTotal === 100){
            $('#save-label').hide();
            $("#save-btn").removeAttr("disabled");
        }
        else{
            $('#save-label').show().empty().append("Le total des pourcentages doit être égal à 100.");
            $("#save-btn").attr("disabled", true);
        }
    })

    
  })
JS
);

echo $p->toHTML() ;


$_SESSION['user']->getPaiementsHistory();
?>
