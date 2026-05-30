<?php
require __DIR__ . "/connessioneDB.php"; 

if (session_status() === PHP_SESSION_NONE) session_start();

// 1. RESET
if (isset($_GET['reset'])) {
    unset($_SESSION['filtri']);
    header("Location: cerca.php");
    exit;
}

// 2. ACQUISIZIONE
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['nomeUtente'])) $_SESSION['filtri']['nomeUtente'] = trim($_GET['nomeUtente']);
    if (isset($_GET['eta']))        $_SESSION['filtri']['eta']        = intval($_GET['eta']);
    if (isset($_GET['citta']))      $_SESSION['filtri']['citta']      = trim($_GET['citta']);
}


$nomeUtente = $_SESSION['filtri']['nomeUtente'] ?? '';
$eta  = $_SESSION['filtri']['eta'] ?? 0;
$citta = $_SESSION['filtri']['citta'] ?? '';

$sql = "SELECT * FROM datiregistrazione WHERE id_utente != :id_utente";
$params = [':id_utente' => $_SESSION['id_utente']];

if (!empty($nomeUtente)) {
    $sql .= " AND CONCAT_WS (' ', nome, cognome) LIKE :nomeUtente";
    $params[':nomeUtente'] = '%' . $nomeUtente . '%';
}

if ($eta > 18) {
    $sql .= " AND eta = :eta";
    $params[':eta'] = $eta;
}
if (!empty($citta)) {
    $sql .= " AND citta LIKE :citta";
    $params[':citta'] = '%' . $citta . '%';
}

$sql .= " ORDER BY id_utente DESC LIMIT 50";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$utenti_trovati = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <h5 class="fw-bold mb-2" style="color:#a31f5f;">Notifiche</h5>
    <div id="contenutoNotifiche" style="max-height:300px; overflow-y:auto; font-size: 0.9rem;"></div>
    <button onclick="chiudiPopup()" style="margin-top:10px; width:100%; background:#a31f5f; color:white; border:none; padding:8px; border-radius:8px;">
        Chiudi
    </button>
</div>


<div class="container py-4">
<form action="cerca.php" method="GET">
    <div class="row">
        <div class="col-12 col-md-3">
    <!-- barra verticale dei filtri -->
            <div class="card p-4 filter-card mb-4">
                <h5 class="fw-bold mb-3" style="color: var(--primary-color)"><i class="bi bi-funnel"></i> Filtri Avanzati</h5>
                <label class="form-label small fw-bold" style="color: var(--text-main)">Persone vicino a me</label>
                <button type="button" id="btn-gps" class="btn rounded-start-pill border border-2 border-end-0 bg-white" title="Rileva posizione">
                    <i class="bi bi-geo-alt-fill bi-crosshairs" style="color: var(--primary-color);"></i>
                </button>
                    <div class="mb-3">
                        <label class="form-label small fw-bold" style="color: var(--text-main)">Età</label>
                        <input type="number" name="eta" class="form-control" placeholder="Es. 25" value="<?= htmlspecialchars($_SESSION['filtri']['eta'] ?? '') ?>">
                    
                        <label class="form-label small fw-bold" style="color: var(--text-main)">Città</label>
                        <input type="text" id="citta-field" name="citta" class="form-control" placeholder="Cerca città..." value="<?= htmlspecialchars($_SESSION['filtri']['citta'] ?? '') ?>" autocomplete="off">
                        <div id="suggerimento" class="list-group position-absolute w-100 shadow" style="z-index: 1000;"></div>
                    </div>
                    <button type="submit" class="btn btn-primary-action w-100">Applica Filtri</button>
                    <a href="cerca.php?reset= 1" class="btn w-100 text-muted small" style="color: var(--text-main)">Reset</a>
                
            </div>
        </div>



        <div class="col-12 col-md-9">
            <div class="search-card p-4 mb-2">
                <h3 class="fw-bold mb-3" style="color: var(--primary-color);"> Cerca la tua persona </h3>
                <div class="item-ancorato">
                    <input type="text" name="nomeUtente" class="form-control" placeholder="Cerca per nome..." 
                    value="<?= htmlspecialchars($_SESSION['filtri']['nomeUtente'] ?? '') ?>">
                    <button type="submit" class="btn btn-primary-action"><i class="bi bi-search-heart"></i></button>
                </div>
            </div>            
            
                <?php if (empty($utenti_trovati)): ?>
                
                <div class="alert alert-light text-center py-4 shadow-sm rounded-4">
                    <i class="bi bi-emoji-frown fs-2 text-muted"></i>
                    <p class="text-muted mt-2 mb-0">Nessun utente trovato con i filtri selezionati.</p>
                </div>
            <?php else: ?>
                <div class="row g-3 mb-5">

                <?php foreach($utenti_trovati as $utente): ?>
                    <?php $sqlFotoAvatar = "SELECT percorso FROM foto_utenti
                    WHERE id_utente = :id_cercato AND tipo = 'profilo' LIMIT 1";

                    $stmtFotoAvatar = $pdo->prepare($sqlFotoAvatar);
                    $stmtFotoAvatar->execute([':id_cercato'=>$utente['id_utente']]);
                    $fotoAvatar = $stmtFotoAvatar->fetch();
                    ?>
               
                        <div class="col-12 col-sm-6">
                            <a href="profiloUtente.php?id=<?= urlencode($utente['id_utente']) ?>" class="text-decoration-none text-dark" >
                            <div class="profile-card p-3 shadow-sm h-100 w-100 d-flex flex-row align-items-center" style="cursor-pointer;">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <img src="<?= htmlspecialchars($fotoAvatar['percorso'] ?? 'default.jpg')?>" class="avatar">
                                        

                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold mb-0"><?= htmlspecialchars($utente['nome'] . " " . $utente['cognome']) ?></h5>
                                        <small class="me-3">
                                            <i class="bi bi-geo-alt-fill" style="color: var(--primary-color);"></i> <?= htmlspecialchars($utente['citta']) ?> • <?= htmlspecialchars($utente['eta']) ?> anni
                                        </small>
                                    </div>
                                    
                                </div>
                            </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
                    
</form>            
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
                <a href="chat_completa.php" class="text-decoration-none text-dark">
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

<script>
    const inputCitta = document.getElementById('citta-field');
    const lista = document.getElementById('suggerimento');
    inputCitta.addEventListener('input', function() {
        if(this.value.length < 2) { lista.style.display = 'none'; return; }
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.value)}&addressdetails=1&limit=5`)
        .then(r => r.json())
        .then(dati => {
            lista.innerHTML = '';
            dati.forEach(localita => {
                const btn = document.createElement('button');
                btn.type = 'button'; btn.className = 'list-group-item list-group-item-action small';
                btn.textContent = localita.display_name.split(',')[0];
                btn.onclick = () => { inputCitta.value = btn.textContent; lista.style.display = 'none'; };
                lista.appendChild(btn);
            });
            lista.style.display = 'block';
        });
    });
</script>
<script>
    document.getElementById('btn-gps').addEventListener('click', function() {
    if (!navigator.geolocation) {
        alert("Geolocalizzazione non supportata.");
        return;
    }

    // Effetto caricamento
    this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Ricerca...';

    navigator.geolocation.getCurrentPosition(function(position) {
        // Scriviamo le coordinate nei campi nascosti
        document.getElementById('latitudine').value = position.coords.latitude;
        document.getElementById('longitudine').value = position.coords.longitude;
        
        // Impostiamo la distanza di default a 60km (se hai un input range, aggiornalo)
        // Se non hai un input range, il PHP userà 60 di default
        
        // Inviamo il form
        document.querySelector('form[action="cerca.php"]').submit();
    }, function(error) {
        alert("Impossibile rilevare la posizione.");
        document.getElementById('btn-vicine').innerHTML = '<i class="bi bi-geo-alt"></i> Persone vicine a me';
    });
});
</script>
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