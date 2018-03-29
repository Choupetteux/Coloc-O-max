<?php

require_once 'php/utilisateurs.class.php';
require_once 'php/colocation.class.php';

require_once 'WebPage.Class.php' ;
require_once 'php/session.class.php' ;
require_once 'php/visiteur.php' ;

Session::start();

//Redirige l'utilisateur si il n'est pas connecté.
$loggedin = isset($_SESSION['loggedin']);
if(!$loggedin){
    $_SESSION['user']->redirection("index.php");
}

$_SESSION['user']->saveLogTime();

$p = new WebPage($loggedin, "Profil | ColocOmax") ;

$p->setTitle('Profil | ColocOmax') ;

$p->appendCssUrl("css/general-style.css");
$p->appendCssUrl("css/style-profil.css");

$p->appendJsUrl("lib/jquery/jquery.min.js");
$p->appendJsUrl("lib/jquery/jquery-migrate.min.js");
$p->appendJsUrl("lib/bootstrap/js/bootstrap.bundle.min.js");
$p->appendJsUrl("lib/easing/easing.min.js");
$p->appendJsUrl("lib/wow/wow.min.js");
$p->appendJsUrl("lib/jquery/jquery-currentpage.js");

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

// exemple récupération donnée utilisateur
// <h1>{$_SESSION['user']->getId()}</h1>


// serie de tests sur les informations renseignées

$user = null;
if (isset($_GET['id'])) {
    $colocataires = $_SESSION['user']->getColocation()->getListeColocataire();
    /** @var Utilisateur $coloc */
    foreach($colocataires as $key => $coloc){
        if($coloc->getId() == $_GET['id']){
            $user = $coloc;
        }
        else{
            $erreur = "Vous ne pouvez voir que les profils de vos colocs' !";
        }
    }
}

$balance = null;

if ($user != null ) {

    if(is_null($user->getDateDeNaissance())) {
        $dateNaissance = "Non renseignée";
    } else {
        $dateNaissance = $user->getDateDeNaissance();
    }

    if ($user->getId() != $_SESSION['user']->getId()){
        $balance = $user->getBalanceEnvers($_SESSION['user']->getId());
    }

    $p->appendContent(<<<HTML
    <div class="row">
        <div class="col-lg-3"></div>

        <div class="col-lg-6 box-profil"> 
            <div class="row">
                <div class="col-lg-6 profil-avatar">
                    <img class="avatar-pic" src="{$user->getAvatarPath()}"/>
                </div>
                <div class="col-lg-6">
                    <h2 class="box-title">{$user->getPseudo()} ({$coloc->getColocation()->getColocationNom()})</h2>
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
        if(!is_null($balance)){
            if ($balance > 0) {
                $p->appendContent(<<<HTML
                <tr>
                    <th class="box-content" scope="row"> Vous doit : </th>
                    <td class="balance-positive">{$balance} €</td>
                </tr>
            </table>
HTML
);
            } elseif($balance < 0) {
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
} else {
    $p->appendContent(<<<HTML
    <div class="row">
        <div class="col-lg-3"></div>
            <div class="col-lg-6 box-profil"> 
                 <p class="error"> {$erreur} </p>
            </div>
        </div>
        <div class="col-lg-3"></div>
    </div>
HTML
);
}

echo $p->toHTML() ;

