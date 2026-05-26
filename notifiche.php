<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=utf-8");

ini_set('display_errors', 0); 
error_reporting(E_ALL);

require __DIR__ . "/connessioneDB.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_utente'])) {
    echo json_encode(["like" => 0, "match" => 0, "messaggi" => 0, "lista" => [], "errore" => "Sessione scaduta"]);
    exit;
}

$id_utente = $_SESSION['id_utente'];

//seleziono le prime 20 notifiche dell'utente che verranno mostrate a prescindere
// Modifica la query intorno alla riga 23 di notifiche.php
$sql = "SELECT n.*, u.nome, u.cognome 
        FROM notifica n LEFT JOIN datiRegistrazione u ON n.id_mit = u.id_utente
        WHERE n.id_dest = :id_dest
        ORDER BY n.data DESC
        LIMIT 20";
            
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id_dest' => $id_utente
]);
$righe = $stmt->fetchAll(PDO::FETCH_ASSOC);

$likes = 0; $messaggi = 0; $match = 0;
    
// Il contatore del badge incrementa SOLO se la notifica è nuova (letto == 0)
foreach ($righe as $r) {
    if ((int)$r['letto'] === 0) {
        if ($r['tipo'] === 'like') $likes++;
        if ($r['tipo'] === 'match') $match++;
        if ($r['tipo'] === 'messaggio') $messaggi++;
    }
}

// Restituiamo i contatori corretti (delle nuove) e l'intera lista delle ultime 20
echo json_encode([
    "like" => $likes, 
    "match" => $match, 
    "messaggi" => $messaggi, 
    "lista" => $righe
]);
exit;