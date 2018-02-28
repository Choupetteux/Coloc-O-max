<?php

require_once '../utilisateurs.class.php';
require_once '../session.class.php';
Session::start();

//Si la partie date de naissance est remplie.
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
if (file_exists($_FILES['pic']['tmp_name']) || is_uploaded_file($_FILES['pic']['tmp_name'])) 
{
    $type = explode("/", mime_content_type($_FILES['pic']['tmp_name']))[1];
    if($type == "jpg" || $type == "jpeg" || $type == "png"){
        //Upload
        //Vérifie si l'utilisateur à déjà une photo de profil
        $alreadyHasPic = $_SESSION['user']->getAvatar() != "placeholder.jpg";
        if($_FILES['pic']['size'] < 2000000){
            $namefile = hash('sha256', openssl_random_pseudo_bytes(8)) . $type;
            $target_file = "../../assets/uploaded_avatar/" . $namefile;
            if (move_uploaded_file($_FILES["pic"]["tmp_name"], $target_file)) {
                if($alreadyHasPic){
                    $unlink("../../assets/uploaded_avatar/" . $_SESSION['user']->getAvatar());
                }
                $PDO = myPdo::getInstance()->prepare(
                    "UPDATE Utilisateurs
                    set avatar = ?
                    WHERE utilisateur_id = ?"
                );
                $PDO->execute(array($namefile, $_SESSION['user']->getId()));
                $_SESSION['user']->setAvatar($namefile);
                echo "Votre photo de profil a été mise à jour.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
        else{
            //Echo trop gros
            echo "trop gros";
        }
    }
    else{
        //Votre image n'est pas valide (REDIRECTION ? ECHO HTML ?)
        echo "Votre image n'est pas valide";
    }
}

?>