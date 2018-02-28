<?php

require_once 'php/utilisateurs.class.php';
require_once 'php/colocation.class.php';
require_once 'php/ImageManipulator.class.php';

require_once 'WebPage.Class.php' ;
require_once 'php/session.class.php' ;
require_once 'php/visiteur.php' ;


Session::start();

//Redirige l'utilisateur si il n'est pas connecté.
$loggedin = isset($_SESSION['loggedin']);
if(!$loggedin){
    $_SESSION['user']->redirection("index.php");
}

//===================================================================
//================== Gestion du formulaire profile ==================
//===================================================================
//Si la partie date de naissance est remplie.
if(isset($_POST['save'])){
    if(isset($_POST['jourNais']) && isset($_POST['moisNais']) && isset($_POST['anneeNais'])){
        $jourNais = htmlspecialchars($_POST['jourNais']);
        $moisNais = htmlspecialchars($_POST['moisNais']);
        $anneeNais = htmlspecialchars($_POST['anneeNais']);
        $_SESSION['user']->setDateDeNaissance($jourNais, $moisNais, $anneeNais);
    }

    //Si la partie Genre est remplie.
    if(isset($_POST['gender'])){
        $gender = htmlspecialchars($_POST['gender']);
        $_SESSION['user']->setSexe($gender);
    }

    //Si un fichier à été envoyé
    if (file_exists($_FILES['pic']['tmp_name']) || is_uploaded_file($_FILES['pic']['tmp_name'])) {
        $type = explode("/", mime_content_type($_FILES['pic']['tmp_name']))[1];
        if($type == "jpg" || $type == "jpeg" || $type == "png"){
            //Upload
            //Vérifie si l'utilisateur à déjà une photo de profil
            $alreadyHasPic = $_SESSION['user']->getAvatar() != "placeholder.jpg";
            if($_FILES['pic']['size'] < 2000000){
                $namefile = hash('sha256', openssl_random_pseudo_bytes(8)) . "." . $type;
                $target_file = "assets/uploaded_avatar/" . $namefile;
                $manipulator = new ImageManipulator($_FILES['pic']['tmp_name']);
                $width  = $manipulator->getWidth();
                $height = $manipulator->getHeight();
                $centreX = round($width / 2);
                $centreY = round($height / 2);
                // Les dimensions doivent être égale en largeur et hauteur
                if($width > $height){
                    $diff = $width - $height;
                    $diff = $diff / 2;
                    $y1 = $centreY - $centreY;
                    $y2 = $centreY + $centreY;
                    $x1 = $centreX - $centreX + $diff;
                    $x2 = $centreX + $centreX - $diff;
                }
                elseif($height > $width){
                    $diff = $height - $width;
                    $diff = $diff / 2;
                    $x1 = $centreX - $centreX;
                    $x2 = $centreX + $centreX;
                    $y1 = $centreY - $centreY + $diff;
                    $y2 = $centreY + $centreY - $diff;
                }
                //Crop automatiquement pour faire un carré
                $manipulator = $manipulator->crop($x1, $y1, $x2, $y2);
                $manipulator->save("assets/uploaded_avatar/" . $namefile);
                if($alreadyHasPic){
                    unlink("assets/uploaded_avatar/" . $_SESSION['user']->getAvatar());
                }
                $_SESSION['user']->setAvatar($namefile);
                echo "<p> Votre photo à été mise à jour avec succès !</p>";
            }
            else{
                //Echo trop gros
                echo "<p>Votre image est trop volumineuse.</p>";
            }
        }
        else{
            //Votre image n'est pas valide (REDIRECTION ? ECHO HTML ?)
            echo "<p>Votre image n'est pas valide.</p>";
        }
    }
}
//===================================================================
//=============== Fin Gestion du formulaire profil ==================
//===================================================================

$p = new WebPage($loggedin, "Paramètres | ColocOmax") ;

$p->appendCssUrl("css/general-style.css") ;
$p->appendCssUrl("css/style-settings.css") ;
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

<main>
  
  <input id="tab1" type="radio" name="tabs" checked>
  <label class="tab" for="tab1">Profil</label>
    
  <input id="tab2" type="radio" name="tabs">
  <label class="tab" for="tab2">Confidentialité</label>
    
  <input id="tab3" type="radio" name="tabs">
  <label class="tab" for="tab3">Sécurité</label>
    
  <section id="content1">
    <label class="form-label">Votre pseudonyme :</label>
    <input type="text" id="pseudo" readonly class="form-control-plaintext" value="{$_SESSION['user']->getPseudo()}">
    <hr/>
    <label class="form-label">Votre photo de profil :</label>
    <form method='POST' enctype='multipart/form-data'>
        <img class="avatar" src="{$_SESSION['user']->getAvatarPath()}"/>
        <label for="pic" class="label-file" id="label-pic">Changer votre photo</label>
        <input type='file' name='pic' id='pic'/>
        <small id="photoHelpBlock" class="form-text text-muted"> Votre image ne doit pas dépassez 2Mo et doit être envoyé en format JPG, JPEG ou PNG.</small>
        <hr/>
        <label class="form-label" for="dateNaiss">Votre date de naissance :</label>
        <br/>
        <div class="form-row">
            <div class="col-lg-2">
                <input id="jourNais" class="form-control dateNais" type="text" name="jourNais" placeholder="Jour" pattern="[0-9]{1,2}">
            </div>
            <div class="col-lg-3">
                <select id="moisNais" class="dateNais custom-select" name="moisNais" value="">
                    <option value="" disabled selected> - Mois - </option>
                    <option value="01">Janvier</option>
                    <option value="02">Février</option>
                    <option value="03">Mars</option>
                    <option value="04">Avril</option>
                    <option value="05">Mai</option>
                    <option value="06">Juin</option>
                    <option value="07">Juillet</option>
                    <option value="08">Août</option>
                    <option value="09">Septembre</option>
                    <option value="10">Octobre</option>
                    <option value="11">Novembre</option>
                    <option value="12">Decembre</option>
                </select>
            </div>
            <div class="col-lg-2">
                <input id="anneeNais" class="form-control dateNais" type="text" name="anneeNais" placeholder="Année" pattern="(?:19|20)[0-9]{2}">
            </div>
        </div>
        <hr/>
        <label class="form-label" for="gender">Votre genre :</label>
        <br/>
        <div class="form-check form-check-inline">
            <input class="form-input form-check-input" type="radio" name="gender" id="gender-M" value="M">
            <label class="form-check-label" for="inlineRadio1">Masculin</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-input form-check-input" type="radio" name="gender" id="gender-F" value="F">
            <label class="form-check-label" for="inlineRadio2">Féminin</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-input form-check-input" type="radio" name="gender" id="gender-A" value="A">
            <label class="form-check-label" for="inlineRadio3">Autre</label>
        </div>
        <small id="genderHelpBlock" class="form-text text-muted"> Vous pourrez changez ce paramètres à tout moment.</small>
        <input class="btn btn-primary float-right" type='submit' name='save' value="Enregistrer les paramètres">
    </form>
  </section>
    
  <section id="content2">
    <p>
      Bacon ipsum dolor sit amet landjaeger sausage brisket, jerky drumstick fatback boudin ball tip turducken. Pork belly meatball t-bone bresaola tail filet mignon kevin turkey ribeye shank flank doner cow kielbasa shankle. Pig swine chicken hamburger, tenderloin turkey rump ball tip sirloin frankfurter meatloaf boudin brisket ham hock. Hamburger venison brisket tri-tip andouille pork belly ball tip short ribs biltong meatball chuck. Pork chop ribeye tail short ribs, beef hamburger meatball kielbasa rump corned beef porchetta landjaeger flank. Doner rump frankfurter meatball meatloaf, cow kevin pork pork loin venison fatback spare ribs salami beef ribs.
    </p>
    <p>
      Jerky jowl pork chop tongue, kielbasa shank venison. Capicola shank pig ribeye leberkas filet mignon brisket beef kevin tenderloin porchetta. Capicola fatback venison shank kielbasa, drumstick ribeye landjaeger beef kevin tail meatball pastrami prosciutto pancetta. Tail kevin spare ribs ground round ham ham hock brisket shoulder. Corned beef tri-tip leberkas flank sausage ham hock filet mignon beef ribs pancetta turkey.
    </p>
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

    $("#pic").change(function (){
        $("#label-pic").empty();
        $("#label-pic").append("Votre photo est prête à être envoyée  ")
        $("#label-pic").addClass("label-pic");
     });
  })
JS
);


echo $p->toHTML() ;
?>