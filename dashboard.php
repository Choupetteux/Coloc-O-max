<?php

require_once 'php/utilisateurs.class.php';
require_once 'WebPage.Class.php' ;
require_once 'php/session.class.php' ;
require_once 'php/visiteur.php' ;
require_once 'php/Paiement.class.php';
require_once 'php/Participer.class.php';

try {
    Session::start();
} catch (SessionException $e) {
    echo $e->getMessage();
}

$loggedin = isset($_SESSION['loggedin']);
if(!$loggedin){
    $_SESSION['user']->redirection("index.php");
}

$_SESSION['user']->saveLogTime();

$p = new WebPage($loggedin, "ColocOmax") ;

$p->setTitle('ColocOmax') ;

$p->appendCssUrl("css/general-style.css") ;
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
    /** @var Utilisateur $coloc */
    foreach ($colocataires as $key => $coloc) {
        if ($coloc->isOnline()) {
            $p->appendContent(<<<HTML
        <div class="col-lg-2 col-centered text-center avatar-bloc" id="avatar-bloc-{$key}">
          <img class="img-fluid dash-avatar" id="avatar-{$key}" src="{$coloc->getAvatarPath()}"><a href=#></a></img>
          <div class="full-height"></div>
          <p style="opacity:0;" class="name-avatar" id="name-{$key}">{$coloc->getPseudo()}</p>
        </div>
HTML
            );
        } else {
            $p->appendContent(<<<HTML
        <div class="col-lg-2 col-centered text-center avatar-bloc" id="avatar-bloc-{$key}">
          <img class="img-fluid dash-avatar" id="avatar-{$key}" src="{$coloc->getAvatarPath()}"><a href=#></a></img>
          <p style="opacity:0;" class="name-avatar" id="name-{$key}">{$coloc->getPseudo()}</p>
        </div>
HTML
            );
        }

        //Append le Jquery pour afficher le pseudo on hover pour chaque bloc de colocataire
        $p->appendJs(<<<JS
      $(document).ready(function() {
         var profileShown{$key} = false;
        $("#avatar-{$key}").on({
          mouseenter: function () {
            $("#name-{$key}").stop(true, true).fadeTo(200, 1);
          },
          mouseleave: function () {
              $("#name-{$key}").fadeTo(200, 0);
          }
        });
        
        $("#avatar-{$key}").on("click", function() {
                var calc = ({$key} * - 60) + 100;
                $('#box-2').removeClass('animated fadeInUp').addClass('animated fadeOutDown');
                $('#box-1').removeClass('animated fadeInLeft').addClass('animated fadeOutLeft');
                $('#box-3').removeClass('animated fadeInRight').addClass('animated fadeOutRight');
                setTimeout(() => {
                    $('#profile-{$key}').removeClass('fadeOut').addClass('animated fadeIn').show();
                }, 700);
                $("#avatar-bloc-{$key}").css('transform',"translate(" + calc + "%, 354%)");
        });
        
         $("#big-avatar-{$key}").on("click", function() {
            $('#profile-{$key}').removeClass('animated fadeIn').addClass('animated fadeOut');
            setTimeout(() => {
                $('#profile-{$key}').hide();
            }, 700);
            setTimeout(() => {
                $('#box-2').removeClass('animated fadeOutDown').addClass('animated fadeInUp');
                $('#box-1').removeClass('animated fadeOutLeft').addClass('animated fadeInLeft');
                $('#box-3').removeClass('animated fadeOutRight').addClass('animated fadeInRight');
            }, 700);
            $("#avatar-bloc-{$key}").css('transform','none');
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

    foreach ($colocataires as $i => $coloc) {
        $balance = null;
        if ($coloc != null) {
            $user = $coloc;
            if (is_null($user->getDateDeNaissance())) {
                $dateNaissance = "Non renseignée";
            } else {
                $dateNaissance = $user->getDateDeNaissance();
            }

            if ($user->getId() != $_SESSION['user']->getId()) {
                $balance = $user->getBalanceEnvers($_SESSION['user']->getId());
            }

            $p->appendContent(<<<HTML
    <div class="row" style="display:none" id="profile-{$i}">
        <div class="col-lg-3"></div>

        <div class="col-lg-6 box-profil"> 
            <div class="row">
                <div class="col-lg-6 profil-avatar">
                    <img class="avatar-pic" id="big-avatar-{$i}" src="{$user->getAvatarPath()}"/>
                </div>
                <div class="col-lg-6">
                    <h2 class="box-title-profil">{$user->getPseudo()} ({$user->getColocation()->getColocationNom()})</h2>
                    <hr style="border-top:2px solid rgba(0,0,0,.85); margin-right: 3%;">
                    <div class="profil-infos">
                        <table>
                            <tr>
                                <th class="box-content" scope="row">Nom : </th>
                                <td>{$user->getNom()}</td>
                            </tr>
                            <tr>
                                <th class="box-content" scope="row">Prénom : </th>
                                <td>{$user->getPrenom()}</td>
                            </tr>
                            <tr>
                                <th class="box-content" scope="row">Date de naissance : </th>
                                <td>{$dateNaissance}</td>
                            </tr>
HTML
            );
            if (!is_null($balance)) {
                if ($balance > 0) {
                    $p->appendContent(<<<HTML
                <tr>
                    <th class="box-content" scope="row"> Vous doit : </th>
                    <td class="balance-positive">{$balance} €</td>
                </tr>
            </table>
HTML
                    );
                } elseif ($balance < 0) {
                    $balance = abs($balance);
                    $p->appendContent(<<<HTML
                <tr>
                    <th class="box-content" scope="row"> Vous lui devez : </th>
                    <td class="balance-négative">{$balance} €</td>
                </tr>
            </table>
HTML
                    );
                }
            } else {
                if ($user->getId() == $_SESSION['user']->getId()) {
                    $p->appendContent(<<<HTML
                </table>
HTML
                    );
                } else {
                    $p->appendContent(<<<HTML
                        </table>
                        <p class="balance-neutre">{$user->getPseudo()} ne vous doit rien !</p>
HTML
                    );
                }
            }
        } 


        $p->appendContent(<<<HTML
                            <p class="date-member">Membre depuis le {$user->getDateInscription()}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3"></div>
        </div>
HTML
        );
    }

//Afficher les 3 blocs contenant les dépenses, activités, agenda
    $p->appendContent(<<<HTML
<div class="row box-wrapper">
  <div class="col-lg-3 box-event" id="box-1">
    <h2 class="box-title">Sommaire</h2>
    <hr style="border-top:2px solid rgba(0,0,0,.85); margin-top:0;">

HTML
    );

    foreach ($colocataires as $i => $coloc) {
        $activity = false;
        if($coloc->getId() != $_SESSION['user']->getId()){
            $activity = true;
        }
        $balance = $_SESSION['user']->getBalanceEnvers($coloc->getId());
        if ($coloc->getId() != $_SESSION['user']->getId()) {
            if ($balance < 0) {
                $balance = str_replace("-", "", $balance);
                $p->appendContent(<<<HTML
        <div class="dep-row">
          <div class="row">
            <div class="col-lg-3 dash-event-avatar"><img class="img-fluid avatar-event" src="assets/uploaded_avatar/{$coloc->getAvatar()}"/></div>
            <div class="col-lg-6 dash-event"><p class="text-event">{$coloc->getPseudo()} vous doit :</p></div>
            <div class="col-lg-3 dash-event"><span class="span-event positive">{$balance}€</span></div>
          </div>
        </div>
        <hr/>
HTML
                );
            } elseif ($balance > 0) {
                $p->appendContent(<<<HTML
        <div class="dep-row">
          <div class="row">
            <div class="col-lg-3 dash-event-avatar"><img class="img-fluid avatar-event" src="assets/uploaded_avatar/{$coloc->getAvatar()}"/></div>
            <div class="col-lg-6 dash-event"><p class="text-event">Vous devez à {$coloc->getPseudo()} :</p></div>
            <div class="col-lg-3 dash-event"><span class="span-event negative">{$balance}€</span></div>
          </div>
        </div>
        <hr/>
HTML
                );
            } elseif ($balance == 0) {
                $p->appendContent(<<<HTML
    <div class="dep-row">
        <div class="row">
          <div class="col-lg-3 dash-event-avatar"><img class="img-fluid avatar-event" src="assets/uploaded_avatar/{$coloc->getAvatar()}"/></div>
          <div class="col-lg-8 dash-event"><p class="text-event">Vous ne devez rien à {$coloc->getPseudo()}</p></div>
        </div>
      </div>
      <hr/>
HTML
                );
            }
        }
    }
    
    if(!$activity){
            $p->appendContent(<<<HTML
          <div class="dep-row">
              <div class="row">
                <div class="col-lg-3 dash-event-avatar"><img class="img-fluid bubble-disclaimer" src="img/speech-bubble.png"/></div>
                <div class="col-lg-9 dash-event"><p class="text-event">Pas d'activités récentes</p></div>
              </div>
            </div>
HTML
            );
    }


    $p->appendContent(<<<HTML
</div>
  <div class="col-lg-3 box-event" id="box-2">
    <h2 class="box-title">Activités</h2>
    <hr style="border-top:2px solid rgba(0,0,0,.85); margin-top:0;">
HTML
    );
//Remplissage de la partie Activités
    $historique = $_SESSION['user']->getPaiementsHistory();
    if (!empty($historique)) {
        $i = 0;
        $max = 10;
        /** @var Paiement $paiement */
        foreach ($historique as $key => $paiement) {
            if ($i < $max) {
                //
                // TODO : Faire une méthode qui permettrait de gérer plus proprement l'historique.
                //
                //Si autre utilisateur reponsable du paiement
                if ($paiement->getUtilisateurId() != $_SESSION['user']->getId()) {
                    $participationMontant = Participer::getParticipationFromIds($paiement->getPaiementId(), $_SESSION['user']->getId())['montant'];
                    $user = Paiement::getUtilisateurFromPaiementId($paiement->getPaiementId());
                    if ($paiement->getTypePaiement() == 'Dépense') {
                        $name = $paiement->getTypePaiement() . ' de la part de ' . $user->getPseudo();
                        $msg = 'Vous avez participé à une dépense d\'un montant total de ' . $paiement->getMontant() . ' €';
                        $sign = 'negative';
                    } elseif ($paiement->getTypePaiement() == 'Remboursement') {
                        $name = $paiement->getTypePaiement() . ' de la part de ' . $user->getPseudo();
                        $msg = 'Vous avez été remboursé d\'un montant de ' . $participationMontant . ' €';
                        $sign = 'positive';
                    } elseif ($paiement->getTypePaiement() == 'Avance') {
                        $name = $paiement->getTypePaiement() . ' de la part de ' . $user->getPseudo();
                        $msg = 'Vous avez été avancé d\'un montant de ' . $participationMontant . ' €';
                        $sign = 'positive';
                    }
                    if (!empty($paiement->getRaison())) {
                        $raison = 'pour la raison suivante :<br><em>' . $paiement->getRaison() . '</em>';
                    } else {
                        $raison = '';
                    }
                } //Si l'utilisateur est responsable du paiement
                elseif ($paiement->getUtilisateurId() == $_SESSION['user']->getId()) {
                    $user = $_SESSION['user'];
                    if ($paiement->getTypePaiement() == 'Dépense') {
                        $participationMontant = Participer::getParticipationFromIds($paiement->getPaiementId(), $_SESSION['user']->getId())['montant'];
                        $name = 'Vous avez créé une dépense';
                        $msg = 'Vous avez créé une dépense d\'un montant total de ' . $paiement->getMontant() . ' €';
                        $sign = 'negative';
                    } elseif ($paiement->getTypePaiement() == 'Remboursement') {
                        $participationMontant = $paiement->getMontant();
                        $name = 'Vous avez envoyé un remboursement.';
                        $msg = 'Vous avez envoyé un remboursement d\'un montant de ' . $participationMontant . ' €';
                        $sign = 'negative';
                    } elseif ($paiement->getTypePaiement() == 'Avance') {
                        $participationMontant = $paiement->getMontant();
                        $name = 'Vous avez avancé de l\'argent';
                        $msg = 'Vous avez avancé un montant de ' . $participationMontant . ' €';
                        $sign = 'negative';
                    }
                    if (!empty($paiement->getRaison())) {
                        $raison = 'pour la raison suivante :<br><em>' . $paiement->getRaison() . '</em>';
                    } else {
                        $raison = '';
                    }
                }
          $p->appendContent(<<<HTML
          <div class="dep-row">
          <div class="row">
            <div class="col-lg-3 dash-event-avatar"><img class="img-fluid avatar-event" src="assets/uploaded_avatar/{$user->getAvatar()}"/></div>
            <div class="col-lg-7 dash-event"><p class="text-event">{$name}</p></div>
            <div class="col-lg-2 dash-event"><span class="span-event {$sign}">{$participationMontant}€</span></div>
          </div>
        </div>
        <hr/>
HTML
                );
                $i++;
            } else {
                break;
            }
        }
    } else {
        $p->appendContent(<<<HTML
      <div class="dep-row">
          <div class="row">
            <div class="col-lg-3 dash-event-avatar"><img class="img-fluid bubble-disclaimer" src="img/speech-bubble.png"/></div>
            <div class="col-lg-9 dash-event"><p class="text-event">Pas d'activités récentes</p></div>
          </div>
        </div>
HTML
        );
    }


    $p->appendContent(<<<HTML
</div>
  <div class="col-lg-3 box-event" id="box-3">
    <h2 class="box-title">Agenda</h2>
 <hr style="border-top:2px solid rgba(0,0,0,.85); margin-top:0;">
          <div class="dep-row">
              <div class="row">
                <div class="col-lg-3 dash-event-avatar"><img class="img-fluid bubble-disclaimer" src="img/speech-bubble.png"/></div>
                <div class="col-lg-9 dash-event"><p class="text-event">Pas d'activités récentes</p></div>
              </div>
            </div>
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
    }
    

    $(window).scroll(function() {
    if ($(this).scrollTop() > 50) {
      $('#header').addClass('header-fixed');
    } else {
      $('#header').removeClass('header-fixed');
    }
    });
    
  })
JS
);

try {
    echo $p->toHTML();
} catch (Exception $e) {
    echo $e->getMessage();
}