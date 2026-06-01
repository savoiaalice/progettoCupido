<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . "/connessioneDB.php";
// require __DIR__ . "/controllo_sessione.php";

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

$sqlGalleria = "SELECT id_foto, percorso FROM foto_utenti 
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Profilo Utente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="stile.css">
</head>

<body>
<div class="cupido-header position-relative">
    <h2 class="fw-bold h2" style="letter-spacing: 2px; margin: 0;">CUPIDO</h2>

    <div class="notifiche-icon">
        <i class="bi bi-bell"></i>
        <span id="badgeNotifiche" class="notifiche-badge">0</span>
    </div>
</div>

<div id="popupNotifiche" style="display:none; position:fixed; top:70px; right:15px; width:280px; background:white; border-radius:12px; box-shadow:0 5px 20px rgba(0,0,0,0.2); z-index:9999; padding:15px;">
    <h5 class="fw-bold mb-2" style="color:#8d0c0c;">Notifiche</h5>
    <div id="contenutoNotifiche" style="max-height:300px; overflow-y:auto; font-size: 0.9rem;"></div>
    <button onclick="chiudiPopup()" style="margin-top:10px; width:100%; background:#8d0c0c; color:white; border:none; padding:8px; border-radius:8px;">
        Chiudi
    </button>
</div>
<div class="container py-5 mb-5">
    <div class="profile-card mx-auto col-lg-8" style="position: relative;">
        
            <a href="logout.php" 
            class="btn btn-cursore position-absolute btn-sm rounded-pill px-3 py-1 shadow-sm text-decoration-none"
   style="top:24px; right: 24px; border-color: var(--primary-color); color: var(--primary-color); font-weight: 500; z-index: 10;">
                Logout
            </a>
    
        <div class="text-center mb-4">
            <div class="d-inline-block position-relative" 
                 style="cursor: pointer;" 
                 data-bs-toggle="modal" 
                 data-bs-target="#visualizzaFoto"
                 data-bs-tipo="profilo"
                 data-bs-remote="<?= htmlspecialchars($fotoProfilo['percorso'] ?? 'default.jpg') ?>">
                <img src="<?= $fotoProfilo['percorso'] ?? 'default.jpg' ?>" class="profile-img shadow">
            </div>

            <h2 class="mt-3"><?= htmlspecialchars($utente['nome'] . " " . $utente['cognome']) ?></h2>
            <p class="text-muted mb-2">
                <i class="bi bi-geo-alt-fill" style="color: var(--primary-color);"></i><?= htmlspecialchars($utente['citta']) ?> • <?= htmlspecialchars($utente['eta']) ?> anni
            </p>

            <button type="button" 
                    class="btn btn-cursore btn-sm rounded-pill px-3 py-1 shadow-sm mt-1"
                    style="border-color: var(--primary-color); color: var(--primary-color); font-weight: 500;"
                    data-bs-toggle="modal" 
                    data-bs-target="#caricaNuovoProfilo">
                <i class="bi bi-pencil me-1"></i> Modifica foto profilo
            </button>
        </div>
    
        <hr>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 style="color: var(--primary-color);">Galleria foto</h4>
            
            <button class="btn btn-cursore btn-sm rounded-pill px-3 py-1 shadow-sm mt-1"
                    style="border-color: var(--primary-color); color: var(--primary-color); font-weight: 500;"
                    data-bs-toggle="modal"
                    data-bs-target="#carica_nuova_foto">
                    <i class="bi bi-plus"></i>
                    Aggiungi foto
                
            </button>
        </div>
        
        <div class="row">
            <?php if (!empty($galleria)): ?>
                <?php foreach ($galleria as $foto): ?>
                    <div class="col-4 mb-3">
                        <div class="galleria" 
                             data-bs-tipo="galleria"
                             data-bs-id="<?= $foto['id_foto'] ?? '' ?>"
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
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4 style="color: var(--primary-color);">Informazioni personali</h4>
            <button class="btn btn-cursore btn-sm rounded-pill px-3 py-1 shadow-sm mt-1"
                    style="border-color: var(--primary-color); color: var(--primary-color); font-weight: 500;"
                    data-bs-toggle="modal"
                    data-bs-target="#modifica_dati">
                    <i class="bi bi-pencil me-1"></i>Modifica
            </button>
        </div>
        <div class="row">
            <p><strong>Email:</strong> <?= htmlspecialchars($utente['email']) ?></p>
            <p><strong>Genere:</strong> <?= htmlspecialchars($utente['sesso']) ?></p>
            <?php if($utente['distanza'] == 1): ?>
                <p><strong>Sono aperta ad una relazione a distanza</strong></p>
            <?php endif; ?>
           
            <p><strong>Partner deve essere più grande/piccolo di me di: </strong> <?= htmlspecialchars($utente['maxEta']) ?> anni</p>
        </div>
        <hr>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 style="color: var(--primary-color);">Interessi</h4>
            <button class="btn btn-cursore btn-sm rounded-pill px-3 py-1 shadow-sm mt-1"
                    style="border-color: var(--primary-color); color: var(--primary-color); font-weight: 500;"
                    data-bs-toggle="modal"
                    data-bs-target="#modificaInteressi">
                    <i class="bi bi-pencil me-1"></i>Modifica
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
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 style="color: var(--primary-color);">Come mi descrivo</h4>
            <button class="btn btn-cursore btn-sm rounded-pill px-3 py-1 shadow-sm mt-1"
                    style="border-color: var(--primary-color); color: var(--primary-color); font-weight: 500;"
                    data-bs-toggle="modal"
                    data-bs-target="#modificaAggettivi">
                    <i class="bi bi-pencil me-1"></i>Modifica
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
        <hr>
        <button type="button" 
                    class="btn btn-cursore btn-sm rounded-pill px-3 py-1 shadow-sm mt-1"
                    style="border-color: var(--primary-color); color: var(--primary-color); font-weight: 500;"
                    data-bs-toggle="modal" 
                    data-bs-target="#cambiaPassword">
                <i class="bi bi-pencil me-1"></i> Cambia Password
            </button>
    </div>
</div>


<div class="modal fade" id="cambiaPassword" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content content-box">
            <form action="azioni_modifica.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="azione" value="cambia_password">
                <div class="modal-header border-0">
                    <h5 class="form-title">Cambia password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body auth-section text-center">
                    <div class="mb-4">
                        <label>Vecchia password</label>
                        <input type="password" name="password" class="form-control" required>
                        <label>Nuova password</label>
                        <input type="password" name="nuovaPassword" class="form-control" required>
                        <label>Conferma password</label>
                        <input type="password" name="confermaPassword" class="form-control" required>
                        
                    </div>
                    <button type="submit" class="btn btn-primary-action w-100 rounded-pill py-2 shadow mb-2">
                        Salva 
                    </button>
                </div>
            </form>
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
<div class="modal fade" id="modifica_dati" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content content-box border-0 shadow-lg">
            <form action="azioni_modifica.php" method="POST">
                <input type="hidden" name="azione" value="modifica_dati">
                
                <div class="modal-header border-0 pb-0">
                    <h5 class="form-title w-100 text-center fw-bold" style="color: var(--primary-color);">Modifica Dati</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold">Nome</label>
                            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($utente['nome']) ?>">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold">Cognome</label>
                            <input type="text" name="cognome" class="form-control" value="<?= htmlspecialchars($utente['cognome']) ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($utente['email']) ?>">
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold">Città</label>
                            <input type="text" id="citta" name="citta" class="form-control" value="<?= htmlspecialchars($utente['citta']) ?>">
                            <div id="listaSuggerimenti" class="list-group" style="position: absolute; z-index: 1050; width: 93%;"></div>
                        </div>
                        <input type="hidden" id="latitudine" name="latitudine">
                        <input type="hidden" id="longitudine" name="longitudine">
                        
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold">Età</label>
                            <input type="number" name="eta" class="form-control" value="<?= htmlspecialchars($utente['eta']) ?>">
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold">Genere</label>
                            <select class="form-select" name="sesso">
                                <option value="uomo" <?= $utente['sesso'] == 'uomo' ? 'selected' : '' ?>>Uomo</option>
                                <option value="donna" <?= $utente['sesso'] == 'donna' ? 'selected' : '' ?>>Donna</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold">Partner</label>
                            <select class="form-select" name="sessoP">
                                <option value="uomo" <?= $utente['sessoP'] == 'uomo' ? 'selected' : '' ?>>Uomo</option>
                                <option value="donna" <?= $utente['sessoP'] == 'donna' ? 'selected' : '' ?>>Donna</option>
                                <option value="entrambi" <?= $utente['sessoP'] == 'entrambi' ? 'selected' : '' ?>>Entrambi</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tipo di relazione</label>
                        <select class="form-select shadow-sm" name="relazione" required>
                            <option value="seria"<?= (isset($utente['relazione']) && $utente['relazione'] == 'seria') ? 'selected' : '' ?>>Relazione seria</option>
                            <option value="aperta"<?= (isset($utente['relazione']) && $utente['relazione'] == 'aperta') ? 'selected' : '' ?>>Relazione aperta</option>
                            <option value="amicizia"<?= (isset($utente['relazione']) && $utente['relazione'] == 'amicizia') ? 'selected' : '' ?>>Amicizia</option>
                        </select>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-4 px-2">
                        <label class="form-label small fw-bold mb-0">Relazione a distanza</label>
                        <input class="form-check-input" type="checkbox" name="distanza" value="1" <?= $utente['distanza'] == 1 ? 'checked' : '' ?>>
                    </div>

                    <button type="submit" class="btn btn-primary-action text-white w-100 py-2 fw-bold">SALVA MODIFICHE</button>
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
            
            <div class="d-flex align-items-center gap-3" style="position: absolute; top: -50px; right: 0; z-index: 1100;">
                <form id="rimuoviFoto" action="azioni_modifica.php" method="POST" onsubmit="return confirm('Sicuro di voler cancellare questa foto?')">
                    <input type="hidden" name="azione" value="elimina_foto">
                    <input type="hidden" name="id_foto" id="idFotoEliminare" value="">
                    <button type="submit" class="btn text-white"><i class="bi bi-trash fs-4"></i></button>  
                </form>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <div id="carouselModal" class="carousel slide" data-bs-interval="false">
                <div class="carousel-inner" id="modalCarouselInner"></div>
                
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselModal" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselModal" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const listaGalleria = <?php echo json_encode($galleria); ?>;
const modalFoto = document.getElementById('visualizzaFoto');
const carouselInner = document.getElementById('modalCarouselInner');
const carouselElement = document.getElementById('carouselModal');

modalFoto.addEventListener('show.bs.modal', function (event) {
    const trigger = event.relatedTarget;
    const srcCliccato = trigger.getAttribute('data-bs-remote');
    
    carouselInner.innerHTML = '';

    // Controlliamo se la foto cliccata è nella galleria
    const isGalleria = listaGalleria.some(f => f.percorso === srcCliccato);

    if (!isGalleria) {
        // È la foto profilo: nascondiamo le frecce
        carouselElement.classList.add('nascondi-frecce');
        carouselInner.innerHTML = `
            <div class="carousel-item active">
                <img src="${srcCliccato}" class="d-block w-100" style="max-height: 80vh; object-fit: contain;">
            </div>`;
    } else {
        // È una foto galleria: mostriamo le frecce
        carouselElement.classList.remove('nascondi-frecce');
        listaGalleria.forEach((foto) => {
            const isActive = (foto.percorso === srcCliccato) ? 'active' : '';
            carouselInner.innerHTML += `
                <div class="carousel-item ${isActive}" data-id="${foto.id}">
                    <img src="${foto.percorso}" class="d-block w-100" style="max-height: 80vh; object-fit: contain;">
                </div>`;
        });
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalVisualizza = document.getElementById('visualizzaFoto');
    if(modalVisualizza){
        modalVisualizza.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const percorsoFoto = button.getAttribute('data-bs-remote');
            const tipoFoto = button.getAttribute('data-bs-tipo');

            document.getElementById('fotoIngrandita').src = percorsoFoto;

            const formRimuovi = document.getElementById('rimuoviFoto');
            const idFotoElimina = document.getElementById('idFotoEliminare');

            if(tipoFoto === 'profilo'){
                if (formRimuovi) {
                    formRimuovi.style.setProperty('display', 'none', 'important');
                }
            } else {
                if (formRimuovi) {
                    formRimuovi.style.setProperty('display', 'inline-block', 'important');
                }
                const idFoto = button.getAttribute('data-bs-id');
                if(idFotoElimina){
                    idFotoElimina.value = idFoto;
                }
            }
        });
    }
});
</script>

<?php include "fondoPagina.php"; ?>
<script src="script.js"></script>
</body>
</html>