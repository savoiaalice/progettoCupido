<?php
session_start();
require 'connessioneDB.php';

if (!isset($_SESSION['id_utente']) || !isset($_GET['id'])) {
    exit; // Chiude silenziosamente se mancano i dati
}

$id_utente = $_SESSION['id_utente'];
$id_altro = $_GET['id'];

// Seleziono tutti i messaggi scambiati tra i due utenti usando i segnaposto ?
$sql = "SELECT * FROM messaggi 
        WHERE (id_mit = ? AND id_dest = ?) 
           OR (id_mit = ? AND id_dest = ?) 
        ORDER BY data ASC"; // Controlla se la colonna si chiama 'data' o 'data_invio'

$stmt = $pdo->prepare($sql);

// Passiamo le variabili nell'ordine esatto in cui compaiono i punti interrogativi
$stmt->execute([
    $id_utente, // Primo ?: id_mit = io
    $id_altro,  // Secondo ?: id_dest = altro
    $id_altro,  // Terzo ?: id_mit = altro
    $id_utente  // Quarto ?: id_dest = io
]);

$messaggi = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($messaggi as $messaggio) {
    // Se il mittente sono io la classe è "mio", altrimenti è "suo"
    $classe = ($messaggio['id_mit'] == $id_utente) ? "mio" : "suo";
    echo "<div class='$classe'>" . htmlspecialchars($messaggio['testo']) . "</div>";
}
?>