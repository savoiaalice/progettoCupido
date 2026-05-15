<?php
require __DIR__ . "/connessioneDB.php";
session_start();

// controllo se loginn è giusto
if (!isset($_SESSION['id_utente'])) {
    header("Location: index.php");
    exit();
}
// inizia la sessione e riprendo i dati dal database
$id = $_SESSION['id_utente'];

$sql = "SELECT * FROM datiregistrazione WHERE id_utente = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$utente = $stmt->fetch();


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


$sqlInteressi = "SELECT * FROM interessi WHERE id_utente = :id";
$stmtInt = $pdo->prepare($sqlInteressi);
$stmtInt->execute([':id' => $id]);
$interessi = $stmtInt->fetch();


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
        :root {
            --primary-color: #c62874;
            --accent-color: #fce4ec;
            --text-main: #333;
        }

        body {
            background-color: var(--accent-color);
            font-family: 'Monteserrat';
        }

        .main-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
            /* Spazio extra per scroll su schermi piccoli */
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
        <div class="text-center mb-4">
            <div class="d-inline-block position-relative" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modificaFotoProfilo">
                <img src="<?= $fotoProfilo['percorso'] ?? 'default.jpg' ?>" class="profile-img shadow">
                </div>

            <h2 class="mt-3"><?= $utente['nome'] . " " . $utente['cognome'] ?></h2>
            <p class="text-muted"><?= $utente['citta'] ?> • <?= $utente['eta'] ?> anni</p>
        </div>
    </div >

        <hr>

        <!-- INFO PERSONALI -->
 
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 style="color: var(--primary-color);">Informazioni personali</h4>
            <button class="btn btn-sm btn-outline-secondary mb-3"
                    data-bs-toggle="modal"
                    data-bs-target="#modificaInformazioni">
                    Modifica
            </button>
        </div>
        <div class="row">
            <p><strong>Email:</strong> <?= $utente['email'] ?></p>
            <p><strong>Sesso:</strong> <?= $utente['sesso'] ?></p>
            <?php if($utente['distanza'] == 1): ?>
                <p><strong>Sono aperta ad una relazione a distanza</strong></p>
            <?php endif; ?>
           
            <p><strong>Età partner max: </strong> <?= $utente['maxEta'] ?></p>
            </div>
        <hr>

        <!-- INTERESSI -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 style="color: var(--primary-color);">Interessi</h4>
            <button class="btn btn-sm btn-outline-secondary mb-3"
                    data-bs-toggle="modal"
                    data-bs-target="#modificaInteressi">
                    Modifica
            </button>
        </div>
       
        <div class="mb-4">
            <?php foreach ($interessi as $chiave => $valore): ?>
                <?php if (!is_numeric($chiave) && ($chiave != "id_utente" && $valore == 1)): ?>
                    <span class="tag"><?= ucfirst($chiave) ?></span> <!-- ucfirst mette la prima lettera in maiuscolo -->
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <hr>

        <!-- AGGETTIVI -->
         <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 style="color: var(--primary-color);">Come mi descrivo</h4>
            <button class="btn btn-sm btn-outline-secondary mb-3"
                    data-bs-toggle="modal"
                    data-bs-target="#modificaAggettivi">
                    Modifica
            </button>
        </div>
        <div class="mb-4">
        <?php foreach ($aggettivi as $chiave => $valore): ?>
            <?php if (!is_numeric($chiave) && ($chiave != "id_utente" && $valore == 1)): ?>
                <span class="tag"><?= ucfirst($chiave) ?></span>
            <?php endif; ?>
        <?php endforeach; ?>
        </div>
        <hr>

        <!-- GALLERIA FOTO -->
<div class="d-flex justify-content-between align-items-center mb-3">
        <h4 style="color: var(--primary-color);">Galleria foto</h4>

    <button class="btn btn-outline-custom rounded-pill px-3"
            data-bs-toggle="modal"
            data-bs-target="#carica_nuova_foto">
        Aggiungi foto
    </button>
</div>
        <div class="row">
            <?php foreach ($galleria as $foto): ?>
                <div class="col-4 mb-3">
                    <img src="<?= $foto['percorso'] ?>" class="img-fluid rounded">
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-4 btn-primary-action ">
            <a href="home.php" class="btn btn-secondary">Torna alla Home</a>
        </div>

    </div>
</div>

<!-- cosa fanno i modal -->
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
</div>

    <div class="modal fade" id="caricaNuovoProfilo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content content-box">
            <form action="azioni_utente.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="azione" value="carica_foto_profilo">
                <div class="modal-header border-0 .btn-primary-action:hover">
                    <h5 class="form-title ">Nuova Foto Profilo</h5>
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

<div class="modal fade" id="modificaInformazioni" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content content-box">
            <form action="azioni_utente.php" method="POST">
                <input type="hidden" name="azione" value="modifica_profilo">
                <div class="modal-header border-0">
                    <h5 class="form-title">Modifica Dati</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body auth-section">
                    <div class="mb-3">
                        <label>Nome</label>
                        <input type="text" name="nome" class="form-control" value="<?= $utente['nome'] ?>">
                    </div>
                    <div class="mb-3">
                        <label>Cognome</label>
                        <input type="text" name="cognome" class="form-control" value="<?= $utente['cognome'] ?>">
                    </div>
                    <div class="mb-3">
                        <label>Città</label>
                        <input type="text" name="citta" class="form-control" value="<?= $utente['citta'] ?>">
                    </div>
                    <div class="mb-3">
                        <label>Età</label>
                        <input type="number" name="eta" class="form-control" value="<?= $utente['eta'] ?>">
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
            <form action="azioni_itente.php" method="POST">
                <input type="hidden" name="azione" value="registrazione_interessi">
                <div class="modal-header border-0">
                    <h5 class="form-title">Modifica Interessi</h5>
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
            <form action="azioni_utente.php" method="POST">
                <input type="hidden" name="azione" value="registrazione_agettivi">
                <div class="modal-header border-0">
                    <h5 class="form-title">Modifica Aggettivi</h5>
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
            <form action="azioni_utente.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="azione" value="carica_nuova_foto">

                    <h5 class="form-title">Aggiungi nuova foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                <div class="modal-body auth-section text-center">
                    <div class="mb-4">
                        <label class="form-label text-muted small">
                            Seleziona un file immagine (JPG, PNG)
                        </label>
                        <input type="file" name="foto" class="form-control rounded-pill" accept="image/*">
                    </div>

                    <button type="submit" class="btn btn-primary-action w-100 rounded-pill py-2 shadow mb-2">
                        Salva
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
    </div>
</body>
</html>
