<!DOCTYPE html>
<html lang="en">
<?php
require __DIR__ . "/connessioneDB.php";

session_start();

// Controllo di sicurezza: l'utente deve essere loggato
if (!isset($_SESSION['id_utente'])) {
    header("Location: index.php");
    exit();
}

$id_loggato = $_SESSION['id_utente'];

// Inizializziamo l'array degli utenti trovati
$utenti_trovati = [];

// Gestiamo la ricerca quando l'utente compila il form
$citta_cercata = isset($_GET['citta']) ? trim($_GET['citta']) : '';

if (!empty($citta_cercata)) {
    // Cerchiamo gli utenti della città specificata, escludendo se stessi
    // Nota: adegua i nomi delle colonne se nel tuo DB sono diversi
    $stmt = $pdo->prepare("SELECT * FROM datiregistrazione WHERE citta LIKE :citta AND id_utente != :id_loggato");
    $stmt->execute([
        ':citta' => '%' . $citta_cercata . '%',
        ':id_loggato' => $id_loggato
    ]);
    $utenti_trovati = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Se non ha ancora cercato nulla, possiamo mostrare ad esempio gli ultimi iscritti (opzionale)
    $stmt = $pdo->prepare("SELECT * FROM datiregistrazione WHERE id_utente != :id_loggato ORDER BY id_utente DESC LIMIT 6");
    $stmt->execute([':id_loggato' => $id_loggato]);
    $utenti_trovati = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cupido - Cerca Anime Gemelle</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #c62874;
            --accent-color: #fce4ec;
        }
        body {
            background-color: var(--accent-color);
            font-family: 'Montserrat', sans-serif;
            padding-bottom: 100px;
        }
        .search-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            margin-top: 2rem;
        }
        .user-result-card {
            background: white;
            border-radius: 15px;
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
        }
        .user-result-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .btn-custom {
            background-color: var(--primary-color);
            color: white;
            border: none;
            font-weight: 600;
        }
        .btn-custom:hover {
            background-color: #a31f5f;
            color: white;
        }
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(198, 40, 116, 0.25);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="search-card p-4 mb-4">
                <h3 class="fw-bold mb-3" style="color: var(--primary-color);"><i class="bi bi-search-heart"></i> Trova la tua persona </h3>
                <form action="cerca.php" method="GET" class="row g-2">
                    <div class="col-9">
                        <input type="text" name="citta" class="form-control form-control-lg" placeholder="Scrivi una città (es. Milano)..." value="<?= htmlspecialchars($citta_cercata) ?>">
                    </div>
                    <div class="col-3">
                        <button type="submit" class="btn btn-custom btn-lg w-100">Cerca</button>
                    </div>
                </form>
            </div>

            <h4 class="fw-bold mb-3 text-dark">Persone che potrebbero interessarti:</h4>
            
            <?php if (empty($utenti_trovati)): ?>
                <div class="alert alert-light text-center py-4 shadow-sm rounded-4">
                    <i class="bi bi-emoji-frown fs-2 text-muted"></i>
                    <p class="text-muted mt-2 mb-0">Nessun utente trovato con i filtri selezionati.</p>
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($utenti_trovati as $utente): ?>
                        <div class="col-12 col-sm-6">
                            <div class="card user-result-card p-3 shadow-sm">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-person-circle fs-1" style="color: var(--primary-color);"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold mb-0"><?= htmlspecialchars($utente['nome'] . " " . $utente['cognome']) ?></h5>
                                        <small class="text-muted">
                                            <i class="bi bi-geo-alt-fill text-muted"></i> <?= htmlspecialchars($utente['citta']) ?> • <?= htmlspecialchars($utente['eta']) ?> anni
                                        </small>
                                    </div>
                                    <div>
                                        <a href="profiloUtente.php?id=<?= urlencode($utente['id_utente']) ?>" class="btn btn-sm btn-outline-secondary rounded-pill">
                                            Vedi <i class="bi bi-arrow-right-short"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>