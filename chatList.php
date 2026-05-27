<?php
require 'connessioneDB.php';
//require __DIR__ . "/controllo_sessione.php";

session_start();

// Se l'utente non è loggato, reindirizza
if (!isset($_SESSION['id_utente']) || empty($_SESSION['id_utente'])) {
    header("Location: index.php");
    exit();
}

$id_utente = $_SESSION['id_utente'];

// Array di appoggio per salvare gli ID delle interazioni valide
$id_interazioni = [];

// 1) Estraiamo i MATCH reali in cui l'utente corrente è il destinatario
$sql = "SELECT id_mit 
        FROM notifica 
        WHERE id_dest = :id_utente AND tipo = 'match'";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id_utente' => $id_utente
]);
while ($r=$stmt->fetch(PDO::FETCH_ASSOC)) {
    if (!empty($r['id_mit'])) {
        $id_interazioni[trim($r['id_mit'])] = 'match';
    }
}

//Estraiamo le CHAT già attive
$sql = "SELECT DISTINCT IF(id_mit = ?, id_dest, id_mit) AS altro 
        FROM messaggi 
        WHERE id_mit = ? OR id_dest = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $id_utente, $id_utente, $id_utente
]);
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if (!empty($r['altro'])) {
        $id_interazioni[trim($r['altro'])] = 'match'; 
    }
}

// Inizializziamo sempre l'array degli utenti per prevenire Warning grafici in assenza di match
$utenti = []; 

// Carichiamo le anagrafiche in modo sicuro
if (!empty($id_interazioni)) {
    // array_values estrae gli username (stringhe) azzerando gli indici dell'array (0, 1, 2...)
    $id_puliti = array_values(array_keys($id_interazioni));
    
    // Rimuoviamo eventuali elementi vuoti accidentali, mantenendo le stringhe/username validi
    $id_puliti = array_filter($id_puliti, function($val) {
        return $val !== null && trim($val) !== '';
    });

    // Ricalcoliamo il numero esatto di username trovati
    $quanti = count($id_puliti);

    // Eseguiamo la query SOLO se abbiamo almeno 1 username valido
    if ($quanti > 0) {
        // Rigenera i segnaposto precisi es: ?, ?, ?
        $placeholders = implode(',', array_fill(0, $quanti, '?'));
        
        $sql = "SELECT id_utente, nome, cognome FROM datiregistrazione WHERE id_utente IN ($placeholders)";
        $stmt = $pdo->prepare($sql);
        
        // PDO assocerà perfettamente l'array di stringhe ai punti interrogativi senza crashare
        $stmt->execute($id_puliti);
        $righe_utenti = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($righe_utenti as $u) {
            $id_alt = $u['id_utente']; // Stringa username
            $utenti[] = [
                'id_altro' => $id_alt,
                'nome'     => $u['nome'],
                'cognome'  => $u['cognome'],
                'tipo'     => 'match'
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le tue chat</title>

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
            font-family: "Montserrat", sans-serif;
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
        .notifiche-icon {
            position: absolute;
            right: 15px;
            top: 15px;
            font-size: 1.8rem;
            color: white;
            cursor: pointer;
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
        .chat-card {
            background: white;
            border-radius: 15px;
            padding: 1rem 1.2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        .chat-card a.nome-utente {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
        }
        .chat-card .btn {
            padding: 6px 12px;
            font-size: 0.9rem;
            border-radius: 10px;
        }
        @media(max-width: 576px){
            h2 { font-size: 1.4rem; margin-top: 1rem; }
            .chat-card { width: 95%; margin: 0 auto; padding: 0.9rem 1rem; }
            .chat-card a.nome-utente { font-size: 1.1rem; }
            .chat-card .btn { padding: 5px 10px; font-size: 0.85rem; }
        }
    </style>
</head>

<body>
    <div class="cupido-header position-relative">
        <h2 class="fw-bold h2" style="letter-spacing: 2px; margin:0;">CUPIDO</h2>
        <div class="notifiche-icon">
            <i class="bi bi-bell"></i>
            <span id="badgeNotifiche" class="notifiche-badge">0</span>
        </div>
    </div>

    <div id="popupNotifiche" style="display:none; position:fixed; top:70px; right:15px; width:290px; background:white; border-radius:12px; box-shadow:0 5px 20px rgba(0,0,0,0.2); z-index:9999; padding:15px;">
        <h5 class="fw-bold mb-2" style="color:#8d0c0c;">Notifiche</h5>
        <div id="contenutoNotifiche" style="max-height:300px; overflow-y:auto; font-size: 0.9rem;"></div>
        <button onclick="chiudiPopup()" style="margin-top:10px; width:100%; background:#8d0c0c; color:white; border:none; padding:8px; border-radius:8px;">
            Chiudi
        </button>
    </div>

    <h2 class="text-center mt-3 fw-bold">Le tue chat</h2>

    <div class="container mt-3">
        <?php if (empty($utenti)): ?>
            <p class="text-center text-muted mt-4">Nessun match o chat attiva al momento. Continua a cercare!</p>
        <?php else: ?>
            <?php foreach ($utenti as $u): ?>
                <div class="chat-card mb-3">
                    <a href="profiloUtente.php?id=<?= $u['id_altro'] ?>" class="text-decoration-none nome-utente">
                        <?= htmlspecialchars($u['nome'] . " " . $u['cognome']) ?>
                    </a>
                    <br>
                    <span class="text-muted" style="font-size: 0.85rem;">❤️‍🔥 Siete in contatto</span>
                    <br>
                    <a href="chat.php?id=<?= $u['id_altro'] ?>" class="btn btn-primary mt-2">
                       Apri chat 💬
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <nav class="navbar fixed-bottom bg-white border-top">
        <div class="container-fluid">
            <div class="row text-center w-100">
                <div class="col"><a href="match.php" class="text-decoration-none text-dark"><?php include "cupido.php"; ?></a></div>
                <div class="col"><a href="cerca.php" class="text-decoration-none text-dark"><i class="bi bi-search-heart" style="font-size: 1.9rem; color:#8d0c0c;"></i></a></div>
                <div class="col"><a href="chatList.php" class="text-decoration-none text-dark"><i class="bi bi-chat-heart" style="font-size: 1.9rem; color:#8d0c0c;"></i></a></div>
                <div class="col"><a href="profilo.php" class="text-decoration-none text-dark"><i class="bi bi-person-circle" style="font-size: 1.9rem; color:#8d0c0c;"></i></a></div>
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