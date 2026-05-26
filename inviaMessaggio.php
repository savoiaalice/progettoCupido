<?php
require 'connessioneDB.php';
session_start();

// Controllo di sicurezza: l'utente deve essere loggato e i dati POST devono esistere
if (!isset($_SESSION['id_utente']) || !isset($_POST['id_dest']) || !isset($_POST['testo'])) {
    exit;
}

$id_mit = $_SESSION['id_utente'];
$id_dest = trim($_POST['id_dest']);
$testo = trim($_POST['testo']);

if ($testo == "" || $id_dest == "") {
    exit;
}


// Inserisco il messaggio nella tabella messaggi
$sql_msg = "INSERT INTO messaggi (id_mit, id_dest, testo) VALUES (:id_mit, :id_dest, :testo)";
$stmt_msg = $pdo->prepare($sql_msg);
$stmt_msg->execute([
    ':id_mit'=>$id_mit, 
    ':id_dest'=>$id_dest, 
    ':testo'=>$testo
]);

//Inserisco la notifica
$testo_notifica = "Nuovo messaggio da " . $id_mit;
    
$sql_notifica = "INSERT INTO notifica (`id_dest`, `tipo`, `id_mit`, `letto`, testo) VALUES (:id_dest, 'messaggio', :id_mit, 0, :testo)";
$stmt_notifica = $pdo->prepare($sql_notifica);
    
$stmt_notifica->execute([
    ':id_dest'=>$id_dest,
    ':id_mit'=>$id_mit,
    ':testo'=>$testo_notifica
    ]);
?>