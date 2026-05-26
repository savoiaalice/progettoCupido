<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . "/connessioneDB.php";
require __DIR__ . "/funzioniMatch.php";

// Se l'utente non è loggato, reindirizza alla pagina di login/index
if (!isset($_SESSION['id_utente'])) {
    header("Location: index.php"); 
    exit;
}

$id_utente = $_SESSION['id_utente'];

$matchTrovato = false;
$fotos = [];
$nome = '';
$cognome = '';
$eta = '';
$citta = '';
$id_altro = null;

// Recupera tutti i dati degli utenti diversi da quello in sessione
$sql = "SELECT * FROM datiregistrazione 
WHERE id_utente != :id_utente";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id_utente' => $id_utente
]);
$altri = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cerca il primo match disponibile
foreach ($altri as $utente) {
    $id_altro = $utente['id_utente'];

    if (calcolaMatch($id_utente, $id_altro, $pdo)) {
        $info = getInformazioni($id_altro, $pdo);
        $nome = $info['nome'] ?? '';
        $cognome = $info['cognome'] ?? '';
        $eta = $info['eta'] ?? '';
        $citta = $info['citta'] ?? '';

        $fotos = getFoto($id_altro, $pdo) ?? [];

        $matchTrovato = true;
        break;
    }
}

// Se non c'è nessun match, reindirizziamo a livello di server
if ($matchTrovato) {
    include "card.php";
    exit;
}
//se non viene trovato un match mostro un popup che reindirizza alla pagina cerca
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nessun Match</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #fce4ec;
            font-family: "Montserrat", sans-serif;
        }
    </style>
</head>
<body>

    <div class="modal fade" id="noMatchModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="noMatchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.2);">
                <div class="modal-header border-0 justify-content-center pt-4">
                    <h3 class="modal-title fw-bold text-center" id="noMatchModalLabel" style="color: #a31f5f;">
                        🔍 Nessun Match Trovato
                    </h3>
                </div>
                <div class="modal-body text-center text-muted px-4">
                    <p style="font-size: 1.1rem;">
                        Al momento non ci sono profili automatici disponibili per te. 
                    </p>
                    <p>
                        Vuoi provare a cercare persone usando i tuoi <b>filtri personalizzati</b> o preferisci tornare al tuo profilo?
                    </p>
                </div>
                <div class="modal-footer border-0 d-flex flex-column gap-2 pb-4 px-4">
                    <a href="cerca.php" class="btn btn-lg w-100 text-white fw-bold" style="background-color: #a31f5f; border-radius: 10px;">
                        Usa i Filtri di Ricerca 🎯
                    </a>
                    <a href="profilo.php" class="btn btn-light w-100 border text-secondary" style="border-radius: 10px;">
                        Torna al Profilo
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Inizializza e mostra immediatamente la modale appena la pagina si carica
            const mioPopup = new bootstrap.Modal(document.getElementById('noMatchModal'));
            mioPopup.show();
        });
    </script>
</body>
</html>


?>