<?php
session_start(); // <-- 1. PRIMISSIMA COSA IN ASSOLUTO!
require __DIR__ . "/connessioneDB.php";

// controllo se login è giusto
if (!isset($_SESSION['id_utente']) || empty($_SESSION['id_utente'])) {
    header("Location: index.php");
    exit();
}

// inizia la sessione e prendo i dati dal database
$id = $_SESSION['id_utente'];

// tutte query per prendere le informazioni dal database 
$sql = "SELECT * FROM datiregistrazione WHERE id_utente = :id";
$stmt = $pdo->prepare($sql); //pdo permette cnnessione al databse
$stmt->execute([':id' => $id]); //esegue l'estrapolazione secondo i parametri della query
$utente = $stmt->fetch(); //prende i dati e li mette nelle variabili 

// Se l'utente non esiste chiude la sessione e va in home
if (!$utente) {
    session_destroy();
    header("Location: home.php");
    exit();
}

$sqlFotoProfilo = "SELECT percorso FROM foto_utenti 
                WHERE id_utente = :id AND tipo = 'profilo' LIMIT 1";
$stmtFoto = $pdo->prepare($sqlFotoProfilo);
$stmtFoto->execute([':id' => $id]);
$fotoProfilo = $stmtFoto->fetch();

$sqlGalleria = "SELECT percorso FROM foto_utenti 
                WHERE id_utente = :id AND tipo = 'galleria'";
$stmtGall = $pdo->prepare($sqlGalleria);
$stmtGall->execute([':id' => $id]);
$galleria = $stmtGall->fetchAll();

$sqlInteressi = "SELECT * FROM interessi 
                WHERE id_utente = :id";
$stmtInt = $pdo->prepare($sqlInteressi);
$stmtInt->execute([':id' => $id]);
$interessi = $stmtInt->fetch();

$sqlAgg = "SELECT * FROM aggettivi 
                WHERE id_utente = :id";
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #c62874;
            --accent-color: #fce4ec;
            --text-main: #333;
        }

        body {
            background-color: var(--accent-color);
            font-family: 'Montserrat', sans-serif;
        }

        .main-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .hero-section {
            background: linear-gradient(rgba(198, 40, 116, 0.6), rgba(0, 0, 0, 0.6)),
                url('https://images.unsplash.com/photo-1511988617509-a57c8a288659?q=80&w=1471&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 3rem;
        }

        .auth-section {
            background-color: #ffffff;
            padding: 3rem;
        }

        .form-title {
            color: var(--primary-color);
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .btn-primary-action {
            background-color: var(--primary-color);
            border: none;
            color: white;
            padding: 12px;
            font-weight: 600;
            transition: opacity 0.3s;
        }

        .btn-primary-action:hover {
            background-color: #a31f5f;
            opacity: 0.9;
            color: white;
        }

        .content-box {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .item-ancorato{
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .form-check-input:checked{
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        .form-check-input:focus{
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(198, 40, 116, 0.25);
        }

        .profile-card {
            background: white; border-radius: 20px; padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
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
        
       
        .galleria {
            width: 100%; 
            padding-top: 100%; 
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        .galleria-imm {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .galleria:hover .galleria-imm {
            transform: scale(1.05);
        }
    </style>
</head>

<body>
<div class="container py-5">
    <div class="profile_card mx-auto col-lg-8" style="position: relative;">
        <a href="logout.php" class="btn btn-primary-action position-absolute text-white btn-sm px-2 py-0"
           style="top:24px; right: 24px; font-size: 12px; padding: 5px 12px; z-index: 10;">
            Logout
        </a>
    </div>
    <div class="profile-card mx-auto col-lg-8">
        <div class="text-center mb-4">
            <div class="d-inline-block position-relative" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modificaFotoProfilo">
                <img src="<?= $fotoProfilo['percorso'] ?? 'default.jpg' ?>" class="profile-img shadow">
            </div>

            <!-- usiamo htmlspecialchars per stabilizzare il layout, 
             questa funzione prende tutti i caratteri inseriti da tastiera dall'utente compresi 
             caratteri speciali e li rende stringa senza fare "capricci"  -->
            <h2 class="mt-3"><?= htmlspecialchars($utente['nome'] . " " . $utente['cognome']) ?></h2>
            <p class="text-muted">
                <i class="bi bi-geo-alt-fill" style="color: var(--primary-color);"></i><?= htmlspecialchars($utente['citta']) ?> • <?= htmlspecialchars($utente['eta']) ?> anni</p>
        </div>
    
        <hr>
              <!-- galleria foto -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 style="color: var(--primary-color);">Galleria foto</h4>
            
            <button class="btn btn-primary-action text-white btn-sm px-2 py-0"
                    data-bs-toggle="modal"
                    data-bs-target="#carica_nuova_foto">
                Aggiungi foto
            </button>
        </div>
        
        <div class="row">
            <?php if (!empty($galleria)): ?>
                <?php foreach ($galleria as $foto): ?>
                    <div class="col-4 mb-3">
                        <div class="galleria" 
                             data-bs-toggle="modal" 
                             data-bs-target="#visualizzaFoto" 
                             data-bs-remote="<?= htmlspecialchars($foto['percorso']) ?>"
                             style="cursor: pointer;">
                            <img src="<?= htmlspecialchars($foto['percorso']) ?>" class="galleria-imm">
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted small px-3">Aggiungi la tua prima foto!</p>
            <?php endif; ?>
        </div>
        <hr>
        <!-- Informazioni personali -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4 style="color: var(--primary-color);">Informazioni personali</h4>
            <button class="btn btn-primary-action text-white btn-sm px-2 py-0" 
                    data-bs-toggle="modal"
                    data-bs-target="#modificaInformazioni">
                    Modifica
            </button>
        </div>
        <div class="row">
            <p><strong>Email:</strong> <?= htmlspecialchars($utente['email']) ?></p>
            <p><strong>Sesso:</strong> <?= htmlspecialchars($utente['sesso']) ?></p>
            <?php if($utente['distanza'] == 1): ?>
                <p><strong>Sono aperta ad una relazione a distanza</strong></p>
            <?php endif; ?>
           
            <p><strong>Età partner max: </strong> <?= htmlspecialchars($utente['maxEta']) ?></p>
        </div>
        <hr>

        <!-- Interessi -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 style="color: var(--primary-color);">Interessi</h4>
            <button class="btn btn-primary-action text-white btn-sm px-2 py-0"
                    data-bs-toggle="modal"
                    data-bs-target="#modificaInteressi">
                    Modifica
            </button>
        </div>
       
        <div class="mb-4">
            <?php if ($interessi): ?>
                <?php foreach ($interessi as $chiave => $valore): ?>
                    <?php if (!is_numeric($chiave) && ($chiave != "id_utente" && $valore == 1)): ?>
                        <span class="tag"><?= ucfirst(htmlspecialchars($chiave)) ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <hr>
        <!-- Aggettivi --> 
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 style="color: var(--primary-color);">Come mi descrivo</h4>
            <button class="btn btn-primary-action text-white btn-sm px-2 py-0"
                    data-bs-toggle="modal"
                    data-bs-target="#modificaAggettivi">
                    Modifica
            </button>
        </div>
        <div class="mb-4">
        
            <?php if ($aggettivi): ?>
                <?php foreach ($aggettivi as $chiave => $valore): ?>
                    <?php if (!is_numeric($chiave) && ($chiave != "id_utente" && $valore == 1)): ?>
                        <span class="tag"><?= ucfirst(htmlspecialchars($chiave)) ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
       
        </div>

    </div>
</div>

<!-- azioni modal -->
<div class="modal fade" id="modificaFotoProfilo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content shadow border-0" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="form-title w-100 text-center" style="font-size: 1.5rem;">Foto profilo</h5>
            </div>
            <hr class="mx-4 my-3">
            <div class="modal-body pt-0 px-4 pb-4">
                <div class="d-grid gap-3">
                    <a href="<?= $fotoProfilo['percorso'] ?? 'default.jpg' ?>"
                       target="_blank"
                       class="btn btn-outline-primary rounded-pill py-2 text-decoration-none shadow-sm"
                       style="border-color: var(--primary-color); color: var(--primary-color); font-weight: 500;">
                        Visualizza foto profilo
                    </a>
                    <button type="button"
                            class="btn btn-primary-action rounded-pill py-2 shadow"
                            style="font-weight: 500;"
                            data-bs-toggle="modal"
                            data-bs-target="#caricaNuovoProfilo">
                        Modifica foto profilo
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="caricaNuovoProfilo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content content-box">
            <form action="azioni_modifica.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="azione" value="carica_foto_profilo">
                <div class="modal-header border-0">
                    <h5 class="form-title">Nuova Foto Profilo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body auth-section text-center">
                    <div class="mb-4">
                        <label class="form-label text-muted small">Seleziona un file immagine (JPG, PNG)</label>
                        <input type="file" name="foto" class="form-control rounded-pill" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary-action w-100 rounded-pill py-2 shadow mb-2">
                        Salva nuova foto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- aggiungo funzione registrazione1 per vedere se posso riusarla per rendere il codice modulare
 la inserisco in un form
 forse mi basta questo che c'è già agiungendoci una action -->
<div class="modal fade" id="modificaInformazioni" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content content-box">
            <form action="azioni_modifica.php" method="POST">
                <input type="hidden" name="azione" value="modifica_dati">
                <div class="modal-header border-0">
                    <h5 class="form-title w-100 text-center" style="font-size: 1.5rem">Modifica Dati</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body auth-section">
                    <div class="mb-3">
                        <label>Nome</label>
                        <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($utente['nome']) ?>">
                    </div>
                    <div class="mb-3">
                        <label>Cognome</label>
                        <input type="text" name="cognome" class="form-control" value="<?= htmlspecialchars($utente['cognome']) ?>">
                    </div>
                    <div class="mb-3">
                        <label>Città</label>
                        <input type="text" name="citta" class="form-control" value="<?= htmlspecialchars($utente['citta']) ?>">
                    </div>
                    <div class="mb-3">
                        <label>Età</label>
                        <input type="number" name="eta" class="form-control" value="<?= htmlspecialchars($utente['eta']) ?>">
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($utente['email']) ?>">
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" value="<?= htmlspecialchars($utente['password']) ?>">
                    </div>
                    <div class="mb-3">
                        <label>Sesso</label>
                        <select class="form-select" name="sesso">
                            <option value="uomo" <?= $utente['sesso'] == 'uomo' ? 'selected' : '' ?>>Uomo</option>
                            <option value="donna" <?= $utente['sesso'] == 'donna' ? 'selected' : '' ?>>Donna</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Relazione a distanza</label>
                        <input class="form-check-input" type="checkbox" name="distanza" value="1" <?= $utente['distanza'] == 1 ? 'checked' : '' ?>>
                    </div>     
                    <div class="mb-3">
                        <label>Differenza eta</label>
                        <input type="number" name="maxEta" class="form-control" value="<?= htmlspecialchars($utente['maxEta']) ?>">
                    </div>
                    <div class="mb-3">
                        <label>Relazione</label>
                        <input type="text" name="relazione" class="form-control" value="<?= htmlspecialchars($utente['relazione']) ?>">
                    </div>
                    <button type="submit" class="btn btn-primary-action w-100">Salva Modifiche</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modificaInteressi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content content-box">
            <form action="azioni_modifica.php" method="POST">
                <input type="hidden" name="azione" value="registrazione_interessi">
                <!-- aggiungo un hide con nome provenienza per capire da dove arriva il codice, non solo per dirgi dove andare
                 sto rendendo modulare il codice, quindi usando le stesse funzioni per la registrazione e la modifica del profilo
                 devo dire all'utente a quale pagina recarsi dopo in base alla casistica di partenza -->
                <input type="hidden" name="provenienza" value="profilo">
                <div class="modal-header border-0">
                    <h5 class="form-title w-100 text-center" style="font-size: 1.5rem">Modifica Interessi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body auth-section">
                    <div class="row">
                        <?php foreach ($interessi as $chiave => $valore): ?>
                            <?php if (!is_numeric($chiave) && $chiave != "id_utente"): ?>
                                <div class="col-6 col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="<?= $chiave ?>" id="int_<?= $chiave ?>" <?= $valore == 1 ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="int_<?= $chiave ?>"><?= ucfirst($chiave) ?></label>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" class="btn btn-primary-action w-100 mt-3">Aggiorna Interessi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modificaAggettivi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content content-box">
            <form action="azioni_modifica.php" method="POST">
                <input type="hidden" name="azione" value="registrazione_aggettivi">
                <input type="hidden" name="provenienza" value="profilo">
                <div class="modal-header border-0">
                    <h5 class="form-title w-100 text-center" style="font-size: 1.5rem">Modifica Aggettivi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body auth-section">
                    <div class="row">
                         <?php foreach ($aggettivi as $chiave => $valore): ?>
                            <?php if (!is_numeric($chiave) && $chiave != "id_utente"): ?>
                                <div class="col-6 col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="<?= $chiave ?>" id="int_<?= $chiave ?>" <?= $valore == 1 ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="int_<?= $chiave ?>"><?= ucfirst($chiave) ?></label>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" class="btn btn-primary-action w-100 mt-3">Aggiorna aggettivi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="carica_nuova_foto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content content-box">
            <form action="azioni_modifica.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="azione" value="carica_nuova_foto">
                <div class="modal-header border-0">
                    <h5 class="form-title w-100 text-center" style="font-size: 1.5rem">Aggiungi nuova foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body auth-section text-center">
                    <div class="mb-4">
                        <label class="form-label text-muted small">Seleziona file immagine (JPG, PNG)</label>
                        <input type="file" name="foto[]" class="form-control rounded-pill" accept="image/*" multiple required>
                    </div>
                    <button type="submit" class="btn btn-primary-action w-100 rounded-pill py-2 shadow mb-2">Salva</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="visualizzaFoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0 position-relative">
            <button type="button" class="btn-close btn-close-white position-absolute" data-bs-dismiss="modal" style="top: -30px; right: 0; z-index: 1100;"></button>
            <div class="modal-body p-0 text-center">
                <img src="" id="fotoIngrandita" class="img-fluid rounded shadow-lg" style="max-height: 80vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- script per permettere di aprire le foto della galleria, senza uno script dovremmo crare un modal per ogni foto caricata dall'utente -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modalFoto = document.getElementById('visualizzaFoto');
    if (modalFoto) {
        modalFoto.addEventListener('show.bs.modal', function (event) {
            var contenitoreCliccato = event.relatedTarget;
            var percorsoFile = contenitoreCliccato.getAttribute('data-bs-remote');
            var imgTarget = document.getElementById('fotoIngrandita');
            if (imgTarget) {
                imgTarget.src = percorsoFile;
            }
        });
    }
});

</script>
    <nav class="navbar fixed-bottom bg-white border-top">
    <div class="container-fluid">
        <div class="row text-center w-100">

            <div class="col">
                <a href="home.php" class="text-decoration-none text-dark">
                    <?php include "cupido.php"; ?>
                </a>
            </div>
            <div class="col">
                <a href="cerca.php" class="text-decoration-none text-dark">
                    <i class="bi bi-search-heart fs-3" style="color:#a31f5f;"></i>
                </a>
            </div>

            <div class="col">
                <a href="match.php" class="text-decoration-none text-dark">
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