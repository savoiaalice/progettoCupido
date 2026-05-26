<?php
session_start(); // <-- 1. PRIMISSIMA COSA IN ASSOLUTO!
require __DIR__ . "/connessioneDB.php";

// controllo se login è giusto
if (!isset($_SESSION['id_utente']) || empty($_SESSION['id_utente'])) {
    header("Location: index.php");
    exit();
}
// Recuperiamo l'ID dell'utente di cui vogliamo vedere il profilo dall'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Se non c'è l'ID nell'URL, torna alla home
    header("Location: home.php");
    exit();
}
// inizia la sessione e prendo i dati dal database
$id = $_GET['id']; 

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
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
            min-height: auto;
            display: block;
            align-items: center;
            justify-content: center;
            padding: 1rem 0 80px 0;
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
            background: white; 
            border-radius: 20px; 
            padding: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
            margin: 0 10px;
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
            object-position: center;
            transition: transform 0.3s ease;
        }
        .galleria:hover .galleria-imm {
            transform: scale(1.05);
        }
         .cupido-header {
            background: var(--primary-color);
            color: white;
            padding: 15px;
            font-size: 20px;
            position: relative;
        }
        /* ICONA NOTIFICHE */
        .notifiche-icon {
            position: absolute;
            right: 15px;
            top: 15px;
            font-size: 1.8rem;
            color: white;
            cursor: pointer;
        }

        .notifiche-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background: red;
            color: white;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 50%;
            display: none;
        }
    </style>
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
    <h5 class="fw-bold mb-2" style="color:#a31f5f;">Notifiche</h5>
    <div id="contenutoNotifiche" style="max-height:300px; overflow-y:auto; font-size: 0.9rem;"></div>
    <button onclick="chiudiPopup()" style="margin-top:10px; width:100%; background:#a31f5f; color:white; border:none; padding:8px; border-radius:8px;">
        Chiudi
    </button>
</div>
<div class="container py-5">
    <div class="profile-card mx-auto col-lg-8">
        <div class="text-center mb-4">
        
            <div class="d-inline-block position-relative"
                
                data-bs-toggle="modal"
                data-bs-target="#visualizzaFoto" 
                data-bs-remote="<?= htmlspecialchars($fotoProfilo['percorso']) ?>"
                style="cursor: pointer;">    
                
                <img src ="<?= htmlspecialchars($fotoProfilo['percorso']) ?>" class="profile-img">
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
                <p class="text-muted small px-3">Non ho ancora caricato nessuna foto...</p>
            <?php endif; ?>
        </div>
        <hr>
        <!-- Informazioni personali -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4 style="color: var(--primary-color);">Informazioni personali</h4>
        </div>
        <div class="row">
            <!-- <p><strong>Email:</strong> <?= htmlspecialchars($utente['email']) ?></p> -->
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
                <a href="card.php" class="text-decoration-none text-dark">
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
<script>
    const badge = document.getElementById("badgeNotifiche");
    const popup = document.getElementById("popupNotifiche");
    const contenuto = document.getElementById("contenutoNotifiche");
    let popupAperto = false;

    // Funzione per generare la grafica delle notifiche con il link al profilo
    function generaHtmlNotifiche(lista) {
        let html = "";
        if (!lista || lista.length === 0) {
            return '<p class="text-muted m-0">Nessuna notifica presente</p>';
        }
       
        lista.forEach(n => {
            let mit = n.nome + " " + n.cognome;
            // Se la notifica è vecchia (letto == 1) la rendiamo leggermente opaca
            let stileLetta = (parseInt(n.letto) === 1) ? 'style="opacity: 0.55;"' : '';
           
            // CREAZIONE DEL LINK AL PROFILO: usiamo id_mit per identificare l'utente
            let linkProfilo = `<a href="profiloUtente.php?id=${n.id_mit}" class="text-decoration-none fw-bold" style="color: #a31f5f;">${mit}</a>`;

            if (n.tipo === "like") {
                html += `
                <div class="mb-2 pb-2 border-bottom d-flex justify-content-between align-items-center" ${stileLetta}>
                    <span>❤️ <b>${linkProfilo}</b> ti ha messo like</span>
                    <div class="azione-container">
                        ${parseInt(n.letto) === 0 ? `
                        <button onclick="ricambiaLike(this, '${n.id_mit}')" class="btn btn-sm text-white" style="background-color:#a31f5f; font-size:0.75rem;">
                            Ricambia
                        </button>` : '<span class="text-muted" style="font-size:0.75rem;">Letta</span>'}
                    </div>
                </div>`;
            }
            if (n.tipo === "match") {
                html += `
                    <div class="mb-2 pb-2 border-bottom d-flex justify-content-between align-items-center" ${stileLetta}>
                        <span>❤️‍🔥 Match con <b>${linkProfilo}</b>!</span>
                        <a href="chat.php?id=${n.id_mit}" class="btn btn-sm btn-primary" style="font-size:0.72rem; padding: 3px 8px;">Chatta 💬</a>
                    </div>`;
            }
            if (n.tipo === "messaggio") {
                html += `<div class="mb-2 pb-2 border-bottom" ${stileLetta}>💬 Nuovo messaggio da <b>${linkProfilo}</b></div>`;
            }
        });
        return html;
    }

    // Aggiornamento del badge in background (ogni 5 secondi)
    function aggiornaNotifiche() {
        if (popupAperto) return;

        fetch("notifiche.php")
            .then(r => r.json())
            .then(data => {
                const totale = data.like + data.match + data.messaggi;
                if (totale > 0) {
                    badge.style.display = "inline-block";
                    badge.textContent = totale;
                } else {
                    badge.style.display = "none";
                }
            })
    }
    //funzione per ricambiare il like
    function ricambiaLike(bottone, idMit) {
        // Disabilitiamo il bottone immediatamente per evitare click doppi/multipli
        bottone.disabled = true;
        bottone.textContent = "Attendere...";

        // Eseguiamo la chiamata asincrona ad azione.php
        fetch(`azione.php?id=${encodeURIComponent(idMit)}&azione=like`)
            .then(r => {
                if (!r.ok) throw new Error("Errore di rete");
                // Gestisci qui se azione.php risponde in JSON o testo semplice.
                // Assumiamo che l'azione vada a buon fine se il server risponde status 200.
                return r.text();
            })
            .then(() => {
                // Troviamo il contenitore del bottone cliccato e sostituiamo il contenuto
                const container = bottone.closest(".azione-container");
                if (container) {
                    container.innerHTML = '<span class="text-muted" style="font-size:0.75rem;">Letta</span>';
                }
            })
            .catch(err => {
                console.error("Errore durante il ricambio del like:", err);
                alert("Impossibile ricambiare il like in questo momento. Riprova.");
                bottone.disabled = false;
                bottone.textContent = "Ricambia";
            });
    }

    // Gestione del click sulla campanella
    document.querySelector(".notifiche-icon").addEventListener("click", function() {
        fetch("notifiche.php")
            .then(r => r.json())
            .then(data => {
                // Carica l'HTML della lista (comprendente le ultime 20 tra lette e non lette)
                contenuto.innerHTML = generaHtmlNotifiche(data.lista);
                popup.style.display = "block";
                badge.style.display = "none"; // Nasconde graficamente il numero all'apertura
                popupAperto = true; // Blocca il timer in background
            })
    });

    // Chiusura del popup
    function chiudiPopup() {
        popup.style.display = "none";
       
        badge.style.display = "none";
        badge.textContent = "0";

        // Invia il segnale al database per marcare tutto come letto
        fetch("notifiche_lette.php")
            .then(r => r.json())
            .then(res => {
                if(res.success) {
                    popupAperto = false;
                   
                    // Sovrascrive la lista nel popup con quella aggiornata dal server (dove tutto è ora opaco)
                    contenuto.innerHTML = generaHtmlNotifiche(res.lista);
                   
                    aggiornaNotifiche();
                }
            })
    }

    // Inizializzazione timer automatico
    setInterval(aggiornaNotifiche, 5000);
    aggiornaNotifiche();
</script>
</body>
</html>