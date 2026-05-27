<?php
require __DIR__ . "/connessioneDB.php";
//require __DIR__ . "/controllo_sessione.php";
session_start();

if (!isset($_SESSION['id_utente']) || empty($_SESSION['id_utente'])) {
    header("Location: index.php");
    exit();
}
$id_utente = $_SESSION['id_utente'];
$id_altro = isset($_GET['id']) && $_GET['id'] !== '' ? $_GET['id'] : null;
$chatVuota = ($id_altro === null);

$altro = null;
if (!$chatVuota) {
    $sql = "SELECT id_utente, nome, cognome FROM datiregistrazione WHERE id_utente = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $id_altro
    ]);
    $altro = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$altro) {
        $chatVuota = true;
    }
}
$sql_lette="UPDATE messaggi
            SET letto=1
            WHERE id_mit=:id_mit AND id_dest=:id_dest";
$stmt_lette=$pdo->prepare($sql_lette);
$stmt_lette->execute([
    ':id_mit'=>$id_altro,
    ':id_dest'=>$id_utente
]);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php if ($chatVuota): ?>
            Chat
        <?php else: ?>
            Chat con <?= htmlspecialchars($altro['nome']) ?>
        <?php endif; ?>
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            margin: 0;
            background: #fcfae4;
            font-family: "Montserrat", sans-serif;
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
        .chat-header {
            background: #8d0c0c;
            color: white;
            padding: 15px;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .chat-header button {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }
        #chat-box {
            height: calc(100vh - 140px);
            overflow-y: auto;
            padding: 15px;
        }
        .mio {
            background: #8d0c0c;
            color: white;
            padding: 10px 14px;
            border-radius: 12px;
            margin: 8px 0;
            max-width: 70%;
            margin-left: auto;
            word-wrap: break-word;
        }
        .suo {
            background: white;
            color: #333;
            padding: 10px 14px;
            border-radius: 12px;
            margin: 8px 0;
            max-width: 70%;
            margin-right: auto;
            word-wrap: break-word;
        }
        .chat-input-container {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: white;
            border-top: 1px solid #ccc;
            padding: 10px;
        }
        .chat-input-container form {
            display: flex;
            width: 100%;
            gap: 10px;
        }
        .chat-input-container input {
            flex: 1;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        .chat-input-container button {
            background: #8d0c0c;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer;
        }
        .empty-state {
            height: calc(100vh - 140px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #777;
            padding: 20px;
        }
    </style>
</head>

<body>

    <div class="chat-header">
        <button onclick="history.back()">←</button>
        <span>
            <?php if ($chatVuota): ?>
                Chat
            <?php else: ?>
                <?= htmlspecialchars($altro['nome'] . " " . $altro['cognome']) ?>
            <?php endif; ?>
        </span>
    </div>

    <?php if ($chatVuota): ?>
        <div class="empty-state">
            <div>
                <strong>Chat vuota</strong><br>
                Quando farai match o riceverai un like, potrai iniziare a chattare da qui.
            </div>

            <div id="notificheBox" style="margin-top:20px; font-size:18px; color:#8d0c0c; font-weight: 600;"></div>
        </div>
    <?php else: ?>
        <div id="chat-box"></div>
    <?php endif; ?>

    <div class="chat-input-container">
        <form id="formMessaggio">
            <input type="text" id="testo" name="testo" placeholder="Scrivi un messaggio..." <?php if ($chatVuota) echo 'disabled'; ?> autocomplete="off">
            <button type="submit" <?php if ($chatVuota) echo 'disabled'; ?>>Invia</button>
        </form>
    </div>

    <script>
        const CHAT_ID_ALTRO = <?= $chatVuota ? 'null' : json_encode($id_altro) ?>;
        const chatBox = document.getElementById("chat-box");

        function caricaChat() {
            if (!CHAT_ID_ALTRO) return;

            // encodeURIComponent serve a proteggere l'URL se l'username contiene caratteri speciali o spazi
            fetch("caricaChat.php?id=" + encodeURIComponent(CHAT_ID_ALTRO))
                .then(r => r.text())
                .then(html => {
                    chatBox.innerHTML = html;
                    chatBox.scrollTop = chatBox.scrollHeight;
                }).catch(err => console.error("Errore caricamento chat:", err));
        }

        if (!<?= $chatVuota ? 'true' : 'false' ?>) {
            document.getElementById("formMessaggio").addEventListener("submit", function(e){
                e.preventDefault();
                if (!CHAT_ID_ALTRO) return;

                const inputTesto = document.getElementById("testo");
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
        }

        if (CHAT_ID_ALTRO) {
            setInterval(caricaChat, 1500);
            caricaChat();
        }
    </script>

    <script>
        function aggiornaNotificheChatVuota() {
            if (CHAT_ID_ALTRO) return; // Se la chat è attiva, questo script si ferma

            fetch("notifiche.php")
                .then(r => r.json())
                .then(data => {
                    const box = document.getElementById("notificheBox");
                    let html = "";

                    if (data.like > 0) html += `❤️ ${data.like} like ricevuti<br>`;
                    if (data.match > 0) html += `🎯 ${data.match} nuovi match<br>`;
                    if (data.messaggi > 0) html += `💬 ${data.messaggi} messaggi non letti<br>`;

                    box.innerHTML = html;
                }).catch(err => console.get ? console.error("Errore notifiche:", err) : null);
        }

        if (!CHAT_ID_ALTRO) {
            setInterval(aggiornaNotificheChatVuota, 2000);
            aggiornaNotificheChatVuota();
        }
    </script>

</body>
</html>