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

$id = $_SESSION['id_utente'];

// Inizializziamo l'array degli utenti trovati
$utenti_trovati = [];

// Gestiamo la ricerca quando l'utente compila il form
$citta_cercata = isset($_GET['citta']) ? trim($_GET['citta']) : '';

if (!empty($citta_cercata)) {
    // Cerchiamo gli utenti della città specificata, escludendo se stessi
    // Nota: adegua i nomi delle colonne se nel tuo DB sono diversi
    $stmt = $pdo->prepare("SELECT * FROM datiregistrazione WHERE citta LIKE :citta AND id_utente != :id");
    $stmt->execute([
        ':citta' => '%' . $citta_cercata . '%',
        ':id' => $id
    ]);
    $utenti_trovati = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Se non ha ancora cercato nulla, possiamo mostrare ad esempio gli ultimi iscritti (opzionale)
    $stmt = $pdo->prepare("SELECT * FROM datiregistrazione WHERE id_utente != :id ORDER BY id_utente");
    $stmt->execute([':id' => $id]);
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
            --primary-color: #a31f5f;
            --accent-color: #fce4ec;
        }
        body {
            background-color: var(--accent-color);
            font-family: 'Montserrat', sans-serif;
            padding-bottom: 80px;
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
            min-height: 95px;
            display: flex;
            align-items:center;
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
        .avatar{
            width: 55px; height:55px; border:2px solid var(--primary-color);
            object-fit:cover;
            border-radius: 50%;
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
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="search-card p-4 mb-4">
                <input type="hidden" id="latitudine" name="latitudine">
                <input type="hidden" id="longitudine" name="longitudine">
                <h3 class="fw-bold mb-3" style="color: var(--primary-color);"><i class="bi bi-search-heart"></i> Trova la tua persona nelle vicinanze </h3>
                <form action="cerca.php" method="GET" class="row g-2">
                    <div class="mb-3 position-relative">
                   
                    <div class="input-group">
                        <button type="button" id="btn-gps" class="btn rounded-pill-start border border-2 border-end-0 bg-white" title="Rileva posizione">
                            <i class="bi bi-geo-alt-fill bi-crosshairs" style="color: var(--primary-color);"></i>
                        </button>
                        
                        <input type="text" id="citta" name="citta" class="form-control  border border-2 border-start-0 rounded-pill-end" placeholder="Inserisci la città...(es. Roma)" autocomplete="off" required>
                        
                        
                    </div>
                    
                    <div id="suggerimento" class="list-group position-absolute w-100 shadow"></div>
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

                <?php foreach($utenti_trovati as $utente): ?>
                    <?php $sqlFotoAvatar = "SELECT percorso FROM foto_utenti
                    WHERE id_utente = :id_cercato AND tipo = 'profilo' LIMIT 1";

                    $stmtFotoAvatar = $pdo->prepare($sqlFotoAvatar);
                    $stmtFotoAvatar->execute([':id_cercato'=>$utente['id_utente']]);
                    $fotoAvatar = $stmtFotoAvatar->fetch();
                    ?>
               
                        <div class="col-12 col-sm-6">
                            <a href="profiloUtente.php?id=<?= urlencode($utente['id_utente']) ?>" class="text-decoration-none text-dark" >
                            <div class="card user-result-card p-3 shadow-sm h-100 w-100 d-flex flex-row align-items-center" style="cursor-pointer;">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const citta = document.getElementById('citta');
        const listaSuggerimento = document.getElementById('suggerimento');
        const campoLat = document.getElementById('latitudine');
        const campoLong = document.getElementById('longitudine');
        const btnGps = document.getElementById('btn-gps');
        let timerDigitare = null; 

        function catturaPosizione(lat, lon){
            citta.value = "Cerco la città...";
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&addressdetails=1&accept-language=it`)
                .then(response => response.json())
                .then(data => {
                    if(data && data.address){
                        
                        const nomeComune = data.address.city || data.address.town || data.address.village || data.address.municipality;
                        if(nomeComune){
                            citta.value = nomeComune;
                        } else {
                            citta.value = data.display_name.split(',')[0];
                        }

                        campoLat.value = lat;
                        campoLong.value = lon;
                    }else{
                        citta.value = "";
                        alert("Impossibile determinare la città. Ti prego inseriscila.");
                    }
                })
                .catch(errore => {
                    console.error("Errore reverse geocoding di nominatim: ", errore);
                    citta.value = "";
                    alert("Errore nel recupero della città!");
                });
        }

        if(btnGps){
            btnGps.addEventListener('click', function(e){
                e.preventDefault();
                if(!navigator.geolocation){
                    alert("Geolocalizzazione non supportata dal tuo browser.");
                    return;
                }
                const iconaGps = btnGps.querySelector('i');
                const iconaOg = iconaGps ? iconaGps.className : '';
                
                if(iconaGps){
                    iconaGps.className = "bi bi-arrow-repeat spinner-border spinner-border-sm me-1";
                }
                
                navigator.geolocation.getCurrentPosition(
                    function(position){
                        // CORRETTO: Sistemata sintassi if(iconaGps) e parentesi
                        if(iconaGps){
                            iconaGps.className = iconaOg;
                        }
                        catturaPosizione(position.coords.latitude, position.coords.longitude);
                    }, 
                    function(errore){
                        if(iconaGps){
                            iconaGps.className = iconaOg;
                        }
                        switch(errore.code){
                            case errore.PERMISSION_DENIED:
                                alert("Permesso negato. Attiva la localizzazione dalle impostazioni del dispositivo.");
                                break;
                            case errore.POSITION_UNAVAILABLE:
                                alert("Posizione non disponibile. Riprova tra poco.");
                                break;
                            case errore.TIMEOUT:
                                alert("Ci sto mettendo troppo tempo a trovare la tua posizione.");
                                break;
                            default:
                                alert("Errore nel recupero della posizione.");
                        }
                    }, 
                    {
                       enableHighAccuracy: true, 
                       timeout: 8000,
                       maximumAge: 0 
                    }
                );
            });
        }

        citta.addEventListener('input', function(){
            clearTimeout(timerDigitare);
            const testoCercato = citta.value.trim();

            if(testoCercato.length < 1){
                listaSuggerimento.style.display = 'none';
                return;
            }
            
            timerDigitare = setTimeout(()=>{
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(testoCercato)}&addressdetails=1&limit=5&accept-language=it&featuretype=settlement`)
                .then(response => response.json())
                .then(dati => {
                    listaSuggerimento.innerHTML = '';
                    if(dati.length > 0){
                        listaSuggerimento.style.display = 'block';

                        const nomiMostrati = new Set();
                        dati.forEach(localita => {
                            const dettagli = localita.display_name.split(',').slice(0, 3).join(',');
                            const dettagliP = dettagli.trim();

                            if(nomiMostrati.has(dettagliP)){
                                return;
                            }
                            nomiMostrati.add(dettagliP);
                            
                            const suggMenu = document.createElement('button');
                            suggMenu.type = 'button';
                            suggMenu.className = 'list-group-item list-group-item-action text-start small py-2';
                            suggMenu.textContent = dettagli;

                            suggMenu.addEventListener('click', function(){
                                const nomePulito = localita.address.city || localita.address.town || localita.address.village || dettagli.split(',')[0];
                                citta.value = nomePulito;

                                campoLat.value = localita.lat;
                                campoLong.value = localita.lon;

                                listaSuggerimento.style.display = 'none';
                            });
                            listaSuggerimento.appendChild(suggMenu);
                        });
                    } else {
                        listaSuggerimento.style.display = 'none';
                    }
                })
                .catch(errore => console.error("Errore nel trovare la citta: ", errore));
            }, 300);
        });

        document.addEventListener('click', function(evento){
            if(evento.target !== citta){
                listaSuggerimento.style.display = 'none';
            }
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