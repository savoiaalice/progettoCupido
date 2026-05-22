<?php
session_start();
// crea cookie automatici con nome PHPSESSID e un valore che indica l'id_utente che si trova nella sessione
//con il logout facciamo in modo che lo dimentichi
$_SESSION = array();

if(ini_get("session.use_cookies")){
    $params = session_get_cookie_params();
    setcookie(session_name(),
     '',  //scuota i cookie, valore vuoto
     time() - 42000, // data di scadenza dei cookie, stiamo mettendo la scadenza nel passato, in modo che il browser capisca che la sessione è finita
     $params["path"], //dice in quali cartelle il cookieè valido
     $params["domain"], //spcifica l'indirizzo localhost del sito
     $params["secure"], 
     $params["httponly"]); //impedisce a codici esterni di recuperare/rubare i cookie dal browser 
}
session_destroy(); 
// cancella i dati dal server, lasciando i cookie nel browser 

header("Location: home.php");
exit();

?>
