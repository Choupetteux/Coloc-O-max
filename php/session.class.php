<?php

/**
 * Classe d'exception associée aux problèmes de session
 */
class SessionException extends Exception {

    public function __SessionException(){

    }
}


/**
 * Classe associée à la gestion de la session
 */
class Session {

   /**
    * Démarrer une session
    *
    * @see session_status()
    * @see headers_sent($file, $line)
    * @see session_start()
    *
    * @throws SessionException si la session ne peut être démarrée
    * @throws RuntimeException si le résultat de session_status() est incohérent
    *
    * @return void
    */
    static public function start() {
        //avant la correction
        //if( ( session_status() == PHP_SESSION_DISABLED || session_status() == PHP_SESSION_NONE )
        //  && headers_sent() )
        //  {
        //      session_start();
        //  }
        //else
        //{
        //    throw new SessionException();
        //}

        //après la correction
        switch(session_status()){
          case PHP_SESSION_DISABLED:
            throw new SessionException('Session disabled');
            break;
          case PHP_SESSION_ACTIVE:
              
            break;
          case PHP_SESSION_NONE:
            if(headers_sent($file, $line)){
              throw new SessionException($file .$line.'headers déjà envoyés fichier {$file}, ligne {$line}');
            }
            session_start();
            break;
        }
    }
}
