<?php
    require 'connessineDB.php';
    session_start();

    switch ($azione){
        case 'carica_foto_profilo':

            break;

        case 'carica_foto_card':

            break;
        
        default:
            header("Location: home.php");
            exit();
    }

    // funzioni per caricare foto 
    
?>