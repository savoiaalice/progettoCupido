<?php
$fotos    = $fotos    ?? [];
$nome     = $nome     ?? '';
$cognome  = $cognome  ?? '';
$eta      = $eta      ?? '';
$citta    = $citta    ?? '';
$id_altro = $id_altro ?? null;
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cupido - Match</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #8d0c0c;
            --accent-color: #fcfae4;
            --text-main: #333;
        }

        body {
            background-color: var(--accent-color);
            font-family: 'Montserrat', sans-serif;
            padding-bottom: 80px;
        }
        /* Sfondo globale con collage fotografico (ereditato dallo stile Home) */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background-image: url('./cupidini.jpg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.45;
        }
        
        .cupido-header {
            background: var(--primary-color);
            color: white;
            padding: 15px;
            font-size: 20px;
            position: relative;
        }

        @media(max-width: 576px){
            .btn-lg {
                padding: 10px 14px;
                font-size: 1rem;
                border-radius: 10px;
            }
            .navbar {
                padding: 0.8rem 1rem;
            }
        }

        .card-foto {
            width: 100%;
            height: 420px;
            overflow: hidden;
            border-radius: 15px;
            position: relative;
        }

        .card-foto img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .carousel-item {
            height: 420px;
        }

        .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
            border-radius: 15px;
            background-color: #fcfae4;
        }

        .btn-like, .btn-skip {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            font-size: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-like {
            background-color: #8d0c0c;
            color: white;
        }

        .btn-skip {
            background-color: #ddd;
            color: #fd0505;
        }

        /* ICONA NOTIFICHE */
        .notifiche-icon {
            position: absolute;
            right: 15px;
            top: 15px;
            font-size: 1.8rem;
            color: white;
            cursor: pointer;
            display: inline-block;
            line-height: 1;
        }

        .notifiche-badge {
            position: absolute;
            top: -2px;
            right: -5px;
            background: red;
            color: white;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 50%;
            display: none;
            font-weight: bold;
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
    <h5 class="fw-bold mb-2" style="color:#8d0c0c;">Notifiche</h5>
    <div id="contenutoNotifiche" style="max-height:300px; overflow-y:auto; font-size: 0.9rem;"></div>
    <button onclick="chiudiPopup()" style="margin-top:10px; width:100%; background:#8d0c0c; color:white; border:none; padding:8px; border-radius:8px;">
        Chiudi
    </button>
</div>

<div class="container-fluid col-12 col-md-8 mx-auto mt-4" style="padding-bottom: 80px;">
    <div class="card p-3 shadow-sm">

        <div class="card-foto">
            <div id="carouselUtente" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    $active = "active";
                    foreach ($fotos as $foto) {
                        echo '
                        <div class="carousel-item '.$active.'">
                            <img src="'.$foto['percorso'].'" alt="foto utente">
                        </div>';
                        $active = "";
                    }
                    ?>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carouselUtente" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselUtente" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>

        <div class="mt-3">
            <h3 class="fw-bold"><?php echo htmlspecialchars($nome . " " . $cognome); ?>, <?php echo htmlspecialchars($eta); ?></h3>
            <p class="text-muted"><?php echo htmlspecialchars($citta); ?></p>

            <a href="profiloUtente.php?id=<?php echo $id_altro; ?>" class="btn btn-outline-danger w-100 mt-2" style="color: var(--primary-color); border-color: var(--primary-color);">
                Vedi profilo completo
            </a>
        </div>

        <div class="d-flex justify-content-around py-3">
            <a href="azione.php?azione=skip&id=<?php echo $id_altro; ?>" class="btn-skip text-decoration-none">
                <i class="bi bi-x-lg"></i>
            </a>
            <a href="azione.php?azione=like&id=<?php echo $id_altro; ?>" class="btn-like text-decoration-none">
                <i class="bi bi-heart-fill"></i>
            </a>
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
                    <i class="bi bi-search-heart" style="font-size: 1.9rem; color:#8d0c0c;"></i>
                </a>
            </div>
            <div class="col">
                <a href="chat_completa.php" class="text-decoration-none text-dark">
                    <i class="bi bi-chat-heart" style="font-size: 1.9rem; color:#8d0c0c;"></i>
                </a>
            </div>
            <div class="col">
                <a href="profilo.php" class="text-decoration-none text-dark">
                    <i class="bi bi-person-circle" style="font-size: 1.9rem; color:#8d0c0c;"></i>
                </a>
            </div>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
            let linkProfilo = `<a href="profiloUtente.php?id=${n.id_mit}" class="text-decoration-none fw-bold" style="color: #8d0c0c;">${mit}</a>`;

            if (n.tipo === "like") {
                html += `
                <div class="mb-2 pb-2 border-bottom d-flex justify-content-between align-items-center" ${stileLetta}>
                    <span>❤️ <b>${linkProfilo}</b> ti ha messo like</span>
                    <div class="azione-container">
                        ${parseInt(n.letto) === 0 ? `
                        <button onclick="ricambiaLike(this, '${n.id_mit}')" class="btn btn-sm text-white" style="background-color:#8d0c0c; font-size:0.75rem;">
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