<?php
require 'connessioneDB.php';
session_start();

// Se l'utente non è loggato → torna al login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['user_id'];

/* 1) DATI PERSONALI */
$sql = "SELECT * FROM datiregistrazione WHERE id_utente = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$utente = $stmt->fetch();

/* 2) FOTO PROFILO */
$sqlFotoProfilo = "SELECT percorso FROM foto_utenti 
                   WHERE id_utente = :id AND tipo = 'profilo' LIMIT 1";
$stmtFoto = $pdo->prepare($sqlFotoProfilo);
$stmtFoto->execute([':id' => $id]);
$fotoProfilo = $stmtFoto->fetch();

/* 3) FOTO GALLERIA */
$sqlGalleria = "SELECT percorso FROM foto_utenti 
                WHERE id_utente = :id AND tipo = 'galleria'";
$stmtGall = $pdo->prepare($sqlGalleria);
$stmtGall->execute([':id' => $id]);
$galleria = $stmtGall->fetchAll();

/* 4) INTERESSI */
$sqlInteressi = "SELECT * FROM interessi WHERE id_utente = :id";
$stmtInt = $pdo->prepare($sqlInteressi);
$stmtInt->execute([':id' => $id]);
$interessi = $stmtInt->fetch();

/* 5) AGGETTIVI */
$sqlAgg = "SELECT * FROM aggettivi WHERE id_utente = :id";
$stmtAgg = $pdo->prepare($sqlAgg);
$stmtAgg->execute([':id' => $id]);
$aggettivi = $stmtAgg->fetch();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Profilo Utente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary-color: #c62874; }
        body { background-color: #fce4ec; font-family: 'Montserrat'; }
        .profile-card {
            background: white; border-radius: 20px; padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .profile-img {
            width: 180px; height: 180px; border-radius: 50%;
            object-fit: cover; border: 5px solid var(--primary-color);
        }
        .tag {
            background: var(--primary-color); color: white;
            padding: 5px 12px; border-radius: 20px;
            margin: 3px; display: inline-block;
        }
    </style>
</head>

<body>
<div class="container py-5">
    <div class="profile-card mx-auto col-lg-8">

        <!-- FOTO PROFILO -->
        <div class="text-center mb-4">
            <img src="<?= $fotoProfilo['percorso'] ?? 'default.jpg' ?>" class="profile-img">
            <h2 class="mt-3"><?= $utente['nome'] . " " . $utente['cognome'] ?></h2>
            <p class="text-muted"><?= $utente['citta'] ?> • <?= $utente['eta'] ?> anni</p>
        </div>

        <hr>

        <!-- DATI PERSONALI -->
        <h4 style="color: var(--primary-color);">Informazioni personali</h4>
        <p><strong>Email:</strong> <?= $utente['email'] ?></p>
        <p><strong>Sesso:</strong> <?= $utente['sesso'] ?></p>
        <p><strong>Distanza max:</strong> <?= $utente['distanza'] ?> km</p>
        <p><strong>Età partner max:</strong> <?= $utente['maxEta'] ?></p>

        <hr>

        <!-- INTERESSI -->
        <h4 style="color: var(--primary-color);">Interessi</h4>
        <?php foreach ($interessi as $chiave => $valore): ?>
            <?php if ($chiave != "id_utente" && $valore == 1): ?>
                <span class="tag"><?= ucfirst($chiave) ?></span>
            <?php endif; ?>
        <?php endforeach; ?>

        <hr>

        <!-- AGGETTIVI -->
        <h4 style="color: var(--primary-color);">Come mi descrivo</h4>
        <?php foreach ($aggettivi as $chiave => $valore): ?>
            <?php if ($chiave != "id_utente" && $valore == 1): ?>
                <span class="tag"><?= ucfirst($chiave) ?></span>
            <?php endif; ?>
        <?php endforeach; ?>

        <hr>

        <!-- GALLERIA FOTO -->
        <h4 style="color: var(--primary-color);">Galleria foto</h4>
        <div class="row">
            <?php foreach ($galleria as $foto): ?>
                <div class="col-4 mb-3">
                    <img src="<?= $foto['percorso'] ?>" class="img-fluid rounded">
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-4">
            <a href="home.php" class="btn btn-secondary">Torna alla Home</a>
        </div>

    </div>
</div>
</body>
</html>
