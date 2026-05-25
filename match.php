<?php

require __DIR__ . "/connessioneDB.php";
require __DIR__ . "/funzioniMatch.php";


if (!isset($_SESSION['id_utente'])) {
    die("ERRORE: utente non loggato.");
}

$id_utente = $_SESSION['id_utente'];

$sql = "SELECT * FROM datiregistrazione WHERE id_utente != :id_utente";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id_utente' => $id_utente]);
$altri = $stmt->fetchAll(PDO::FETCH_ASSOC);

$matchTrovato = false;

foreach ($altri as $utente) {

    $id_altro = $utente['id_utente'];

    if (calcolaMatch($id_utente, $id_altro, $pdo)) {

        $info = getInformazioni($id_altro, $pdo);
        $nome = $info['nome'];
        $cognome = $info['cognome'];
        $eta = $info['eta'];
        $citta = $info['citta'];

        $fotos = getFoto($id_altro, $pdo);

        $matchTrovato = true;
        break;
    }
}


if (!$matchTrovato) {
    $fotos = [];
    echo "
    <script>
        alert('Nessun match trovato al momento.');
        window.location.href = 'profilo.php';
    </script>
    ";
}


include "card.php";