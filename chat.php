<?php
require __DIR__ . "/connessioneDB.php";
session_start();

if (!isset($_SESSION['id_utente'])) {
    die("ERRORE: utente non loggato.");
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
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>
        <?php if ($chatVuota): ?>
            Chat
        <?php else: ?>
            Chat con <?= htmlspecialchars($altro['nome']) ?>
        <?php endif; ?>
    </title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            margin: 0;
            background: #f7f7f7;
            font-family: Arial, sans-serif;
        }
        .chat-header {
            background: #a31f5f;
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
            background: #a31f5f;
            color: white;
            padding: 10px 14px;
            border-radius: 12px;
            margin: 8px 0;
            max-width: 70%;
            margin-left: auto;
        }
        .suo {
            background: #e4e4e4;
            color: #333;
            padding: 10px 14px;
            border-radius: 12px;
            margin: 8px 0;
            max-width: 70%;
            margin-right: auto;
        }
        .chat-input {
            display: flex;
            padding: 10px;
            background: white;
            border-top: 1px solid #ccc;
        }
        .chat-input input {
            flex: 1;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        .chat-input button {
            background: #a31f5f;
            color: white;
            border: none;
            padding: 10px 16px;
            margin-left: 10px;
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

            <!-- BOX NOTIFICHE -->
            <div id="notificheBox" style="margin-top:20px; font-size:20px; color:#a31f5f;"></div>
        </div>
    <?php else: ?>
        <div id="chat-box"></div>
    <?php endif; ?>

    <!-- FORM MESSAGGIO (serve anche se disabilitato) -->
    <div class="chat-input">
        <form id="formMessaggio" style="display:flex; width:100%;">
            <input type="text" id="testo" placeholder="Scrivi un messaggio..." <?php if ($chatVuota) echo 'disabled'; ?>>
            <button type="submit" <?php if ($chatVuota) echo 'disabled'; ?>>Invia</button>
        </form>
    </div>

    <!-- SCRIPT CHAT -->
    <script>
        const CHAT_ID_ALTRO = <?= $chatVuota ? 'null' : json_encode($id_altro) ?>;
        const chatBox = document.getElementById("chat-box");

        function caricaChat() {
            if (!CHAT_ID_ALTRO) return;

            fetch("caricaChat.php?id=" + CHAT_ID_ALTRO)
                .then(r => r.text())
                .then(html => {
                    chatBox.innerHTML = html;
                    chatBox.scrollTop = chatBox.scrollHeight;
                });
        }

        document.getElementById("formMessaggio").addEventListener("submit", function(e){
            e.preventDefault();
            if (!CHAT_ID_ALTRO) return;

            const testo = document.getElementById("testo").value.trim();
            if (!testo) return;

            const data = new URLSearchParams();
            data.append("testo", testo);
            data.append("id_dest", CHAT_ID_ALTRO);

            fetch("inviaMessaggio.php", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: data.toString()
            }).then(() => {
                document.getElementById("testo").value = "";
                caricaChat();
            });
        });

        if (CHAT_ID_ALTRO) {
            setInterval(caricaChat, 1500);
            caricaChat();
        }
    </script>

    <!-- SCRIPT NOTIFICHE 
    <script>
        function aggiornaNotificheChatVuota() {
            if (CHAT_ID_ALTRO) return;

            fetch("notifiche.php")
                .then(r => r.json())
                .then(data => {
                    const box = document.getElementById("notificheBox");
                    let html = "";

                    if (data.like > 0) html += `❤️ ${data.like} like ricevuti<br>`;
                    if (data.match > 0) html += `🎯 ${data.match} nuovi match<br>`;
                    if (data.messaggi > 0) html += `💬 ${data.messaggi} messaggi non letti<br>`;

                    box.innerHTML = html;
                });
        }

        setInterval(aggiornaNotificheChatVuota, 2000);
        aggiornaNotificheChatVuota();
    </script>
    -->
    

</body>
</html>
