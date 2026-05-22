<?php
require 'connessioneDB.php';
session_start();

$id_utente = $_SESSION['id_utente'];

// ARRAY RISULTATI
$utenti = [];

// 1) LIKE ricevuti
$sql = "SELECT id_mit as altro
        FROM notifica 
        WHERE id_dest = :id_utente AND tipo = 'like'";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id_utente' => $id_utente
    ]);
$riga=$stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($riga as $r) {
    $utenti[$r['altro']] = ['tipo' => 'like'];
}

// 2) MATCH
$sql = "SELECT id_mit as altro
        FROM notifica 
        WHERE id_dest = :id_utente AND tipo = 'match'";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id_utente' => $id_utente
    ]);
$riga=$stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($riga as $r) {
    $utenti[$r['altro']] = ['tipo' => 'match'];
}

// 3) CHAT attive
$sql = "SELECT DISTINCT 
            IF(id_mit = :id_utente, id_dest, id_mit) AS altro
        FROM messaggi
        WHERE id_mit = :id_utente OR id_dest = :id_utente";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id_utente' => $id_utente
    ]);
$riga=$stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($riga as $r) {
    if (!isset($utenti[$r['altro']])) {
        $utenti[$r['altro']] = ['tipo' => 'chat'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Le tue chat</title>

    <!-- includo Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body class="pb-5">

<h2 class="text-center mt-3">Le tue chat</h2>

<div class="container mt-4">

<?php foreach ($utenti as $id_altro => $info): ?>

    <?php
    // Recupero nome e cognome
    $sql = "SELECT nome, cognome FROM datiregistrazione WHERE id_utente = :id_altro";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_altro' => $id_altro
    ]);
    $dati = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>

    <div class="p-3 border rounded mb-3">

        <!-- NOME → link al profilo -->
        <a href="profiloUtente.php?id=<?= $id_altro ?>" 
           class="fw-bold text-decoration-none" 
           style="color:#a31f5f;">
            <?= $dati['nome'] . " " . $dati['cognome'] ?>
        </a>

        <br>

        <!-- TIPO DI NOTIFICA -->
        <?php if ($info['tipo'] === 'like'): ?>
            ❤️ Ti ha messo like
            <br>
            <a href="azione.php?id=<?= $id_altro ?>&azione=like" 
               class="btn btn-sm mt-2" style="border: 2px solid #a31f5f; background-color:#a31f5f; color:white;">
               Ricambia 🤍
            </a>

        <?php elseif ($info['tipo'] === 'match'): ?>
            ❤️‍🔥 Match!
            <br>
            <a href="chat.php?id=<?= $id_altro ?>" 
               class="btn btn-primary btn-sm mt-2">
               Apri chat 💬
            </a>

        <?php elseif ($info['tipo'] === 'chat'): ?>
            <a href="chat.php?id=<?= $id_altro ?>" 
               class="btn btn-primary btn-sm mt-2">
               Apri chat 💬
            </a>
        <?php endif; ?>

    </div>

<?php endforeach; ?>

</div>

<!-- NAVBAR -->
<nav class="navbar fixed-bottom bg-white border-top">
    <div class="container-fluid">
        <div class="row text-center w-100">

            <div class="col">
                <a href="match.php" class="text-decoration-none text-dark">
                    <?php include "cupido.php"; ?>
                </a>
            </div>

            <div class="col">
                <a href="cerca.php" class="text-decoration-none text-dark">
                    <i class="bi bi-search-heart fs-3" style="color:#a31f5f;"></i>
                </a>
            </div>

            <div class="col">
                <a href="chatList.php" class="text-decoration-none text-dark">
                    <i class="bi bi-chat-heart fs-3" style="color:#a31f5f;"></i>
                </a>
            </div>

            <div class="col">
                <a href="profilo.php" class="text-decoration-none text-dark">
                    <i class="bi bi-person-circle fs-3" style="color:#a31f5f;"></i>
                </a>
            </div>

        </div>
    </div>
</nav>

</body>
</html>
