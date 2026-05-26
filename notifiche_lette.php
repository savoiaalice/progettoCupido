<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . "/connessioneDB.php";

if (!isset($_SESSION['id_utente'])) {
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "error" => "Sessione mancante", "lista" => []]);
    exit;
}

$id_utente = $_SESSION['id_utente'];

//aggiorno le notifiche che leggo
$sql_update = "UPDATE notifica 
                SET letto = 1 
                WHERE id_dest = :id_dest";
$stmt_update = $pdo->prepare($sql_update);
$stmt_update->execute([
    ':id_dest' => $id_utente
]);

//Recupera la lista aggiornata delle ultime 20 notifiche (ora saranno tutte con letto = 1)
$sql_select = "SELECT n.*, u.nome, u.cognome 
                FROM notifica n 
                JOIN datiRegistrazione u ON n.id_mit = u.id_utente
                WHERE n.id_dest = :id_dest 
                ORDER BY n.data DESC 
                LIMIT 20";
                   
$stmt_select = $pdo->prepare($sql_select);
$stmt_select->execute([
    ':id_dest' => $id_utente
]);
$lista_aggiornata = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

//Invia la risposta JSON pulita con il successo e la lista dei dati
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    "success" => true,
    "lista"   => $lista_aggiornata
]);
    exit;
?>