<?php

require_once 'php/utilisateurs.class.php';
require_once 'php/myPDO.mysql.colocomax.php';
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

$_SESSION['user']->saveLogTime();



/* Filler variable pour genre pré-remplie
================================================*/
$male = $female = $other = '';
if(!is_null($_SESSION['user']->getSexe())){
    $sexe = $_SESSION['user']->getSexe();
    if($sexe == 'M'){
        $male = "checked";
    }
    elseif($sexe == 'F'){
        $female = "checked";
    }
    else{
        $other = "checked";
    }
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
        $dateNais = explode("/", $_SESSION['user']->getDateDeNaissance());
        if($jourNais === $dateNais[0] && $moisNais === $dateNais[1] && $anneeNais === $dateNais[2]){
        } else{
            $_SESSION['user']->setDateDeNaissance($jourNais, $moisNais, $anneeNais);
        }
    }
   

    //Si la partie Genre est remplie.
    if(isset($_POST['gender']) && $_POST['gender'] != $_SESSION['user']->getSexe()){
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
                else{
                    $x1 = $centreX - $centreX;
                    $x2 = $centreX + $centreX;
                    $y1 = $centreY - $centreY;
                    $y2 = $centreY + $centreY;
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

/* Filler variable pour date de naissance pré-remplie
================================================*/
if(!is_null($_SESSION['user']->getDateDeNaissance())){
    $dateNais = explode("/", $_SESSION['user']->getDateDeNaissance());
    $moisArray = array("", "", "", "", "", "", "", "", "", "", "", "", "");
    foreach($moisArray as $i => $mois){
        if($i == $dateNais[1]){
            $moisArray[$i] = "selected";
        }
    }
}
else{
    $dateNais = null;
    $moisArray = array("selected", "", "", "", "", "", "", "", "", "", "", "", "");
}

/* redéfinition des mots de passes dans l'onglet de sécurité
================================================*/

         // tu récupère lancien mot de passe dans la bdd
		$result=false;

        if(isset($_POST['submit'])){
        	
        $passwd_old=$_POST['passwd_old'];
        $new_passwd=$_POST['new_passwd'];
        $new_passwd_conf=$_POST['new_passwd_conf'];
        $pseudo = $_SESSION['user'] -> getPseudo();

        $PDO = myPdo::getInstance()->prepare(
                "SELECT passwd FROM utilisateurs WHERE pseudo='$pseudo'");
        $PDO -> setFetchMode(PDO::FETCH_ASSOC);
        $PDO -> execute();
        while ($pass_act = $PDO->fetch()) {
        	//echo $pass_act['passwd'];

        	$passwd_old = password_hash($passwd_old, PASSWORD_DEFAULT);


            if (($passwd_old!='')&&($new_passwd!='')&&($new_passwd_conf!='')) {
                if (password_verify(var_dump($passwd_old,$pass_act['passwd']))) {
                    if($new_passwd==$new_passwd_conf){

                    		$sql="UPDATE utilisateurs SET passwd='$new_passwd' WHERE pseudo= '$pseudo'";
                    		$result=mysql_query($sql);

                    		echo 'Modification du mot de passe effectuee avec succes';
                    		$_pass_act = password_hash($new_passwd, PASSWORD_DEFAULT);
                    } 

                    else {
                        echo 'Erreur entre le nouveau mot de passe entr&eacute; et la verification';
                   		 }
                	} 
                else {
                    echo 'Le mot de passe actuel n\'est pas valide';
                     }
            } 
            else 
            {
                echo 'Veuillez remplir tous les champs';
            }
        } 

        }

       $PDO -> closeCursor();
       
        
      /*	$request = "SELECT passwd FROM utilisateurs WHERE pseudo='$pseudo'";
		$reponse -> mysql_query($request);
		while ($pass_act = $reponse->fetch())
		{
    		echo $pass_act['passwd'] . '<br />';
		}
   
		$reponse->closeCursor(); */

		
    
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
                <input id="jourNais" class="form-control dateNais" type="text" name="jourNais" placeholder="Jour" pattern="[0-9]{1,2}" value="{$dateNais[0]}">
            </div>
            <div class="col-lg-3">
            <select id="moisNais" class="dateNais custom-select" name="moisNais">
                    <option value="" disabled {$moisArray[0]}> - Mois - </option>
                    <option value="01" {$moisArray[1]}>Janvier</option>
                    <option value="02" {$moisArray[2]}>Février</option>
                    <option value="03" {$moisArray[3]}>Mars</option>
                    <option value="04" {$moisArray[4]}>Avril</option>
                    <option value="05" {$moisArray[5]}>Mai</option>
                    <option value="06" {$moisArray[6]}>Juin</option>
                    <option value="07" {$moisArray[7]}>Juillet</option>
                    <option value="08" {$moisArray[8]}>Août</option>
                    <option value="09" {$moisArray[9]}>Septembre</option>
                    <option value="10" {$moisArray[10]}>Octobre</option>
                    <option value="11" {$moisArray[11]}>Novembre</option>
                    <option value="12" {$moisArray[12]}>Decembre</option>
                </select>
            </div>
            <div class="col-lg-2">
                <input id="anneeNais" class="form-control dateNais" type="text" name="anneeNais" placeholder="Année" pattern="(?:19|20)[0-9]{2}" value="{$dateNais[2]}">
            </div>
        </div>
        <hr/>
        <label class="form-label" for="gender">Votre genre :</label>
        <br/>
        <div class="form-check form-check-inline">
            <input class="form-input form-check-input" type="radio" name="gender" id="gender-M" value="M" {$male}>
            <label class="form-check-label" for="inlineRadio1">Masculin</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-input form-check-input" type="radio" name="gender" id="gender-F" value="F" {$female}>
            <label class="form-check-label" for="inlineRadio2">Féminin</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-input form-check-input" type="radio" name="gender" id="gender-A" value="A" {$other}>
            <label class="form-check-label" for="inlineRadio3">Autre</label>
        </div>
        <small id="genderHelpBlock" class="form-text text-muted"> Vous pourrez changez ce paramètres à tout moment.</small>
        <input class="btn btn-primary float-right" type='submit' name='save' value="Enregistrer les paramètres">
    </form>
  </section>
    
  <section id="content2">
    <p>
      	Vous pouvez avoir recours à nos services pour toutes sortes de raisons : pour rechercher et partager des informations, pour communiquer avec d'autres personnes ou pour créer des contenus. En nous transmettant des informations, par exemple en créant un compte, vous nous permettez d'améliorer nos services. Nous pouvons notamment afficher des annonces et des résultats de recherche plus pertinents et vous aider à échanger avec d'autres personnes ou à simplifier et accélérer le partage avec d'autres internautes. Nous souhaitons que vous, en tant qu'utilisateur de nos services, compreniez comment nous utilisons vos données et de quelles manières vous pouvez protéger votre vie privée. 
    </p>
    <p>
	Nos Règles de confidentialité expliquent&nbsp; :
	
			<ul classe = "pull-left" style = "margin:auto 10%">
			<li>
    			les données que nous collectons et les raisons de cette collecte.
			</li> 
			
			
			<li>
    			la façon dont nous utilisons ces données.
			</li>
		
			
			<li>
    			les fonctionnalités que nous vous proposons, y compris comment accéder à vos données et comment les mettre à jour.
			</li>
	</ul>
			
    </p>
    <p>
	Nous nous efforçons d’être le plus clair possible. Toutefois, si vous n’êtes pas familier, par exemple, des termes “cookies”, “adresses IP” ou “navigateurs”, renseignez-vous préalablement sur ces termes clés. nous sommes soucieux de préserver la confidentialité de vos données privées. Ainsi, que vous soyez nouvel utilisateur ou un habitué, prenez le temps de découvrir nos pratiques et, si vous avez des questions, n’hésitez pas à nous contacter. 
    </p>
     
  </section>
    
  <section id="content3">
		<form action="" method="post">
		<fieldset classe = "pull-left" style = "margin:auto 10%">
		<legend>Modifier votre mot de passe dés à prèsent :</legend>


			Mot de passe actuel : <input type="password"  name="passwd_old" class="form-control mot_de_passe" required><br />

			Nouveau mot de passe : <input type="password"  name="new_passwd" class="form-control mot_de_passe" required><br />

			Confirmez votre nouveau mot de passe : <input type="password" name="new_passwd_conf" class="form-control mot_de_passe" required><br />
		
		</fieldset>

			<div align="center">
				<input type="submit" name="submit"  class="btn btn-primary float-right" value="Changer mon mot de passe" />
			</div>
		</form><!-- Fin du formulaire -->
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