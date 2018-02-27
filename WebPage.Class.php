<?php

class WebPage {
    /**
     * @var string Texte compris entre <head> et </head>
     */
    private $head  = null ;
    /**
     * @var string Texte compris entre <title> et </title>
     */
    private $title = null ;
    /**
     * @var string Texte compris entre <body> et </body>
     */
    private $body  = null ;

    private $menu = null;

    /**
     * Constructeur
     * @param string $title Titre de la page
     */
    public function __construct($loggedin, $title=null) {
        if($loggedin){
            $this->appendContent(<<<HTML
                <header id="header">
                    <div id="menu" class="row animated fadeIn">
                        <h1 class="col-lg-3" ><a href="">Coloc'O'max</a></h1>
                        <div id="navbar" class="col-lg-6">
                            <div class="row">
                                <h3 id="dashboard"><a href="dashboard.php" class="col-lg-2">Dashboard</a></h3>
                                <h3 id="depense"><a href="" class="col-lg-2">Dépenses</a></h3>
                                <h3 id="colocs"><a href="colocs.php" class="col-lg-2">Colocs</a></h3>
                                <h3 id="agenda"><a href="" class="col-lg-2">Agenda</a></h3>
                            </div>  
                        </div>
                        <div id="profile" class="col-lg-3">
                            <p id="username" class="btn dropdown-toggle" data-toggle="dropdown">{$_SESSION['user']->getPseudo()}</p>
                            <img class="img-fluid" id="avatar" src="img/lily.jpg"><a href=#></a></img>
                            <ul class="dropdown-menu">
                                <li><a href="profil?id=">Modifier votre profil</a></li>
                                <li><a href="#">Option 2</a></li>
                                <li><a href="deconnexion.php">Se déconnecter</a></li>
                            </ul>
                        </div>
                     </div>
                </header>
HTML
            );
        }
        else{
            $this->appendContent(<<<HTML
                <header id="header">
                        <div class="row">
                            <h1 class="col-lg-10" ><a href="" class="scrollto">Coloc'O'max</a></h1>
                            <div id="profile" class="col-lg-2">
                                <p id="username"><a class="connect" href='connexion.php'>Se connecter</a></p>
                                <img class="img-fluid" id="avatar" src="img/blank-user.png"><a href=#></a></img>
                            </div>
                        </div>
                </header>
HTML
            );
        }

        $this->setTitle($title) ;
    }

    /**
     * Retourner le contenu de $this->body
     *
     * @return string
     */
    public function body() {
        return $this->body ;
    }

    public function menu() {
        return $this->menu;
    }

    /**
     * Retourner le contenu de $this->head
     *
     * @return string
     */
    public function head($contenuEntete) {
        return $this->head .= $contenuEntete;
    }

    /**
     * Donner la dernière modification du script principal
     * @link http://php.net/manual/en/function.getlastmod.php
     * @link http://php.net/manual/en/function.strftime.php
     *
     * @return string
     */
    public function getLastModification() {
        return strftime("Dernière modification de cette page le %d/%m/%Y à %Hh%M", getlastmod()) ;
    }

    /**
     * Protéger les caractères spéciaux pouvant dégrader la page Web
     * @see http://php.net/manual/en/function.htmlentities.php
     * @param string $string La chaîne à protéger
     *
     * @return string La chaîne protégée
     */
    public static function escapeString($string) {
        return htmlentities($string, ENT_QUOTES|ENT_HTML5, "utf-8") ;
    }

    /**
     * Affecter le titre de la page
     * @param string $title Le titre
     */
    public function setTitle($title) {
        $this->title = $title ;
    }

    /**
     * Ajouter un contenu dans head
     * @param string $content Le contenu à ajouter
     *
     * @return void
     */
    public function appendToHead($content) {
        $this->head .= $content ;
    }

    /**
     * Ajouter un contenu CSS dans head
     * @param string $css Le contenu CSS à ajouter
     *
     * @return void
     */
    public function appendCss($css) {
        $this->appendToHead(<<<HTML
    <style type='text/css'>
    $css
    </style>

HTML
    );
    }

    /**
     * Ajouter l'URL d'un script CSS dans head
     * @param string $url L'URL du script CSS
     *
     * @return void
     */
    public function appendCssUrl($url) {
        $this->appendToHead(<<<HTML
    <link rel="stylesheet" type="text/css" href="{$url}">

HTML
) ;
    }

    /**
     * Ajouter un contenu JavaScript dans head
     * @param string $js Le contenu JavaScript à ajouter
     *
     * @return void
     */
    public function appendJs($js) {
        $this->appendToHead(<<<HTML
    <script type='text/javascript'>
    $js
    </script>

HTML
) ;
    }

    /**
     * Ajouter l'URL d'un script JavaScript dans head
     * @param string $url L'URL du script JavaScript
     *
     * @return void
     */
    public function appendJsUrl($url) {
        $this->appendToHead(<<<HTML
    <script type='text/javascript' src='$url'></script>

HTML
) ;
    }

    /**
     * Ajouter un contenu dans body
     * @param string $content Le contenu à ajouter
     *
     * @return void
     */
    public function appendContent($content) {
        $this->body .= $content ;
    }

    /**
     * Produire la page Web complète
     *
     * @return string
     * @throws Exception si title n'est pas défini
     */
    public function toHTML() {
        if (is_null($this->title)) {
            throw new Exception(__CLASS__ . ": title not set") ;
        }

        return <<<HTML
<!doctype html>
<html lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>{$this->title}</title>
{$this->head($var=null)}
    </head>
    <body>
        <div>
        {$this->menu()}
        {$this->body()}
        </div>
    </body>
</html>
HTML;
    }
}
