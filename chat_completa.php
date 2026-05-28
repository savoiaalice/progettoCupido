<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . "/connessioneDB.php";
require __DIR__ . "/funzioniMatch.php";

// Se l'utente non è loggato, reindirizza alla pagina di login/index
if (!isset($_SESSION['id_utente']) || empty($_SESSION['id_utente'])) {
    header("Location: index.php");
    exit();
}

$id_utente = $_SESSION['id_utente'];
if(isset($_GET['id']) && $_GET['id'] !== ''){
    $id_altro = $_GET['id'];
} else {
    $id_altro = null;
}

$chatVuota = false;
if($id_altro === null){
    $chatVuota = true;
}

$altro = null;
if(!$chatVuota){
    // Nome tabella uniformato in datiregistrazione (minuscolo)
    $sql = "SELECT id_utente, nome, cognome
            FROM datiregistrazione
            WHERE id_utente = :id_utente";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_utente' => $id_altro
    ]);
    $altro = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(!$altro){
        $chatVuota = true;
    }
}

// Array di appoggio per salvare gli ID delle interazioni valide
$id_interazioni = [];

//Estraiamo i MATCH reali in cui l'utente corrente è il destinatario
$sql = "SELECT id_mit 
        FROM notifica 
        WHERE id_dest = :id_utente AND tipo = 'match'";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id_utente' => $id_utente
]);
while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
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

$utenti = []; 

// Carichiamo le anagrafiche in modo sicuro
if (!empty($id_interazioni)) {
    $id_puliti = array_values(array_keys($id_interazioni));
    
    $id_puliti = array_filter($id_puliti, function($val) {
        return $val !== null && trim($val) !== '';
    });

    $quanti = count($id_puliti);

    if ($quanti > 0) {
        $placeholders = implode(',', array_fill(0, $quanti, '?'));
        //seleziona i dati del destinatario e conta quanti messaggi non letti ci sono
        $sql = "SELECT d.id_utente, d.nome, d.cognome,
                (SELECT COUNT(*) FROM messaggi m 
                    WHERE m.id_mit = d.id_utente AND m.id_dest = ? AND m.letto = 0) as non_letti
                FROM datiregistrazione d
                WHERE d.id_utente IN ($placeholders)";
        $stmt = $pdo->prepare($sql);
        //uniamo i paramentri da eseguire
        $par=array_merge([$id_utente], $id_puliti);
        $stmt->execute($par);
        $righe_utenti = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($righe_utenti as $u) {
            $id_alt = $u['id_utente'];
            $utenti[] = [
                'id_altro' => $id_alt,
                'nome'     => $u['nome'],
                'cognome'  => $u['cognome'],
                'tipo'     => 'match',
                'non_letti'=> $u['non_letti']//salvo il numero di messaggi non letti
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
        .app-container {
            display: flex;
            height: 100vh;
            width: 100vw;
            position: relative;
        }
        .app-container::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -2;
            background-image: url('./cupidini.jpg'); 
            background-size: cover;
            background-position: center;
            opacity: 0.25;
        }
        .sidebar {
            width: 350px;
            min-width: 300px;
            background: white;
            border-right: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
            z-index: 10;
        }
        body {
            background-color: var(--accent-color);
            font-family: "Montserrat", sans-serif;
            /* Rimosso il padding-bottom che serviva per la vecchia navbar fissa */
            padding-bottom: 0; 
        }
        .cupido-header {
            background: var(--primary-color);
            color: white;
            padding: 15px;
            font-size: 20px;
            position: relative;
        }
        .sidebar-scrollable {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
            background-color:#fcfae4;
        }
        .contact-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }
        .contact-card:hover {
            background: #f1f1f1;
        }
        .contact-card.active {
            background: #fff0f0;
            border-color: var(--primary-color);
        }
        .chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: rgba(252, 250, 228, 0.6);
        }
        .chat-header {
            background: var(--primary-color);
            color: white;
            padding: 15px;
            font-size: 20px;
            position: relative;
        }
        #chat-box {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }
        .mio {
            background: var(--primary-color);
            color: white;
            padding: 10px 14px;
            border-radius: 15px 15px 2px 15px;
            margin: 6px 0;
            max-width: 65%;
            margin-left: auto;
            word-wrap: break-word;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .suo {
            background: white;
            color: #333;
            padding: 10px 14px;
            border-radius: 15px 15px 15px 2px;
            margin: 6px 0;
            max-width: 65%;
            margin-right: auto;
            word-wrap: break-word;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .chat-input-container {
            background: white;
            border-top: 1px solid #e0e0e0;
            padding: 15px;
        }
        .chat-input-container form {
            display: flex;
            gap: 10px;
        }
        .chat-input-container input {
            flex: 1;
            padding: 12px;
            border-radius: 25px;
            border: 1px solid #ccc;
            outline: none;
            padding-left: 20px;
        }
        .chat-input-container button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0 22px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
        }
        .empty-state {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #777;
            text-align: center;
        }
        @media(max-width: 768px) {
            .sidebar { width: 100%; min-width: 100%; display: <?php echo $chatVuota ? 'flex' : 'none'; ?>; }
            .chat-main { display: <?php echo $chatVuota ? 'none' : 'flex'; ?>; }
            .back-btn { display: block !important; }
        }
    </style>
</head>

<body>
    <div class="app-container">

    <div class="sidebar" id="sidebar-panel">
        <div class="cupido-header d-flex align-items-center gap-2">
            <a href="match.php" class="text-white text-decoration-none fs-4 line-height-1" style="padding-right: 5px;">←</a>
            <h3 class="fw-bold m-0" style="letter-spacing: 2px; font-size: 1.5rem;">CUPIDO</h3>
        </div>

        <div class="sidebar-scrollable">
            <?php if (empty($utenti)): ?>
                <p class="text-center text-muted small mt-4">Nessuna chat attiva. Continua a cercare!</p>
            <?php else: ?>
                <?php foreach ($utenti as $u): ?>
                    <div class="contact-card <?php if($id_altro === $u['id_altro']) echo 'active'; ?>" 
                        onclick="selezionaUtente('<?= htmlspecialchars($u['id_altro']) ?>', '<?= htmlspecialchars($u['nome'] . ' ' . $u['cognome']) ?>')">
        
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="fw-bold" style="color: var(--primary-color);">
                                <?= htmlspecialchars($u['nome'] . " " . $u['cognome']) ?>
                            </div>
                                <?php if (isset($u['non_letti']) && $u['non_letti'] > 0): ?>
                                <span class="badge bg-danger rounded-pill"><?= $u['non_letti'] ?></span>
                                <?php endif; ?>
                            </div>
        
                                 <span class="text-muted small"><i class="bi bi-chat-heart"></i> Clicca per chattare</span>
                         </div>
                    <?php endforeach; ?>
            <?php endif; ?>
            </div>
        </div>

    <div class="chat-main" id="chat-panel">
        <div class="chat-header d-flex align-items-center gap-2">
            <button class="btn text-white back-btn p-0 border-0 fs-4 d-none" onclick="tornaAllaLista()">←</button>
            <span id="nome-header-chat" class="fw-bold m-0" style="letter-spacing: 2px; font-size: 1.5rem;">
                <?= $chatVuota ? 'Seleziona una chat' : htmlspecialchars($altro['nome'] . " " . $altro['cognome']) ?>
            </span>
        </div>

        <div id="chat-box">
            <?php if ($chatVuota): ?>
                <div class="empty-state">
                    <i class="bi bi-chat-quote fs-1 mb-2" style="color: var(--primary-color);"></i>
                    <strong>Nessuna conversazione selezionata</strong>
                    <p class="small text-muted px-4">Scegli una persona dalla lista di sinistra per visualizzare i messaggi o iniziare a parlare.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="chat-input-container">
            <form id="formMessaggio">
                <input type="text" id="testo" name="testo" placeholder="Scrivi un messaggio..." <?php if ($chatVuota) echo 'disabled'; ?> autocomplete="off">
                <button type="submit" id="btnInvia" <?php if ($chatVuota) echo 'disabled'; ?>>Invia</button>
            </form>
        </div>
    </div>

</div>

<script>
    let CHAT_ID_ALTRO = <?= $chatVuota ? 'null' : json_encode($id_altro) ?>;
    const chatBox = document.getElementById("chat-box");
    const inputTesto = document.getElementById("testo");
    const btnInvia = document.getElementById("btnInvia");
    const nomeHeaderChat = document.getElementById("nome-header-chat");
    let timerChat = null;

    function selezionaUtente(id, nomeCompleto) {
        // Rimuove il badge delle notifiche dall'utente cliccato immediatamente
        if(event && event.currentTarget) {
            const badge = event.currentTarget.querySelector('.badge');
            if(badge) badge.remove();
        }

        CHAT_ID_ALTRO = id;
        nomeHeaderChat.textContent = nomeCompleto;
        inputTesto.disabled = false;
        btnInvia.disabled = false;

        document.querySelectorAll('.contact-card').forEach(card => card.classList.remove('active'));
        if(event && event.currentTarget) {
            event.currentTarget.classList.add('active');
        }

        if(window.innerWidth <= 768) {
            document.getElementById("sidebar-panel").style.display = "none";
            document.getElementById("chat-panel").style.display = "flex";
        }

        caricaChat();
        clearInterval(timerChat);
        timerChat = setInterval(caricaChat, 1500);
    }

    function tornaAllaLista() {
        document.getElementById("sidebar-panel").style.display = "flex";
        document.getElementById("chat-panel").style.display = "none";
        clearInterval(timerChat);
    }

    function caricaChat() {
        if (!CHAT_ID_ALTRO) return;

        fetch("caricaChat.php?id=" + encodeURIComponent(CHAT_ID_ALTRO))
            .then(r => r.text())
            .then(html => {
                chatBox.innerHTML = html;
                chatBox.scrollTop = chatBox.scrollHeight;
            })
            .catch(err => console.error("Errore caricamento chat:", err));
    }

    document.getElementById("formMessaggio").addEventListener("submit", function(e){
        e.preventDefault();
        if (!CHAT_ID_ALTRO) return;

        const testo = inputTesto.value.trim();
        if (!testo) return;

        const data = new URLSearchParams();
        data.append("testo", testo);
        data.append("id_dest", CHAT_ID_ALTRO);

        fetch("inviaMessaggio.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: data.toString()
        }).then(() => {
            inputTesto.value = "";
            caricaChat();
        }).catch(err => console.error("Errore invio messaggio:", err));
    });

    if (CHAT_ID_ALTRO) {
        if (window.innerWidth <= 768) {
            document.getElementById("sidebar-panel").style.display = "none";
            document.getElementById("chat-panel").style.display = "flex";
        }
        timerChat = setInterval(caricaChat, 1500);
        caricaChat();
    }
</script>
</body>
</html>