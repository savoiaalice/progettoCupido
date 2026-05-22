<?php
require 'connessioneDB.php';
session_start();

$id_mit = $_SESSION['id_utente'];
$id_dest = $_POST['id_dest'];
$testo = trim($_POST['testo']);

if ($testo == "") {
    exit;
}

// Inserisco il messaggio
$sql = "INSERT INTO messaggi (id_mit, id_dest, testo)
        VALUES (:id_mit, :id_dest, :testo)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id_mit' => $id_mit,
    ':id_dest' => $id_dest,
    ':testo' => $testo
]);

// Inserisco la notifica (ordine corretto delle colonne!)
$sql = "INSERT INTO notifica (id_dest, tipo, id_mit, letto, testo)
        VALUES (:id_dest, 'messaggio', :id_mit, 0, :testo_notifica)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id_dest' => $id_dest,
    ':id_mit' => $id_mit,
    ':testo_notifica' => "Nuovo messaggio da $id_mit"
]);
?>
