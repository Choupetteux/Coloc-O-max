<?php

use PHP_CodeSniffer\Tokenizers\JS;

require_once 'php/utilisateurs.class.php';

require_once 'WebPage.Class.php' ;
require_once 'php/session.class.php' ;
require_once 'php/visiteur.php' ;

Session::start();

$loggedin = isset($_SESSION['loggedin']);
if($loggedin){
    $_SESSION['user']->redirection("dashboard.php");
}

$p = new WebPage($loggedin, "ColocOmax") ;

$p->setTitle('ColocOmax') ;

$p->appendCssUrl("css/general-style.css") ;
$p->appendCssUrl("css/style.css") ;

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



$p->appendJS(<<<JAVASCRIPT
$(document).ready(function() {
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
JAVASCRIPT
);


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
<div class="landing-text">
<div class="row" id="text-landing" style="display:none;">

        <div class="col-lg-6 col-centered" style="margin-top:13%;">
            <h1 class="title">Prêt pour une coloc' ?</h1>
            <p>Inscrivez vous gratuitement pour faciliter les points importants de votre colocation !</p>
            <a href="inscription.php" class="btn-sign-up">S'inscrire</a>
        </div>
    </div> 
    <div class="row">
    <section id="key" class="row" style="width: 1920px; height: 974px;">
		<div class="videocontainer col-centered" style="width: 1280px; height: 720px; top: -53px; left: 0px; opacity: 1;">
			<div id="video01"></div>
		</div>
	</section>
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

$p->appendJs(<<<JS
    
        
    var tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        var player;
        function onYouTubeIframeAPIReady() {

	        player = new YT.Player(
		    "video01",
            {
                height: '100%',
                width: '100%',
                videoId: 'ZbYBBcBzibQ',
                wmode: 'transparent',
                playerVars:{
                    controls:0,
                    showinfo:0,
                    disablekb:1,
                    autoplay:1,
                    rel:0,
                    modestbranding:1,
                    frameborder:"0",
                    playsinline:1
                },
                events:{
                    'onReady': function (event) {
                        console.log("ready");
                        perfume.isLoadedVideo = true;
                    },
                    'onStateChange': function (event) {
                        if(event.data == YT.PlayerState.PLAYING ){

                        }else if(event.data == YT.PlayerState.ENDED){
                            $('#video01').hide();
                            $('#text-landing').addClass("animated fadeInUpBig").show();
                        }
                    }
                }
            }
            );
        }
JS
);

echo $p->toHTML() ;