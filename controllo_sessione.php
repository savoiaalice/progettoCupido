<?php
// Se l'utente non è attivo dopo un determinato numero di secondi allora faccio automaticamente il logout e chiudo la sessione
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Forza il login iniziale, ma se la sessione scade DOPO lasciamo gestire il blocco al JavaScript
if (!isset($_SESSION['id_utente']) && !isset($_GET['azione'])) {
    header("Location: index.php");
    exit();
}

// La pagina viene chiamata via AJAX/fetch per la disconnessione sul server
if (isset($_GET['azione']) && $_GET['azione'] === 'logout_timeout') {
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $parametri = session_get_cookie_params();
        setcookie(
            session_name(), 
            '', 
            time() - 4200,
            $parametri['path'],
            $parametri['domain'],
            $parametri['secure'],
            $parametri['httponly']
        );
    }
    session_destroy();
    header('Content-Type: application/json');
    echo json_encode(['status' => 'scaduto']);
    exit();
}
?>

<div class="modal fade" id="timeoutModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="noMatchModalLabel" aria-hidden="true" style="z-index: 99999;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.3); border: none; background-color: #ffffff;">
            <div class="modal-header border-0 justify-content-center pt-4">
                <h3 class="modal-title fw-bold text-center" id="noMatchModalLabel" style="color: #8d0c0c;">
                    SESSIONE SCADUTA!!
                </h3>
            </div>
            <div class="modal-body text-center text-muted px-4">
                <p style="font-size: 1.1rem; color: #212529;">La tua sessione è scaduta per inattività.</p>
                <p style="color: #6c757d;">Effettua di nuovo il <b>login</b> per motivi di sicurezza.</p>
            </div>
            <div class="modal-footer border-0 d-flex flex-column gap-2 pb-4 px-4">
                <button onclick="reindirizzaLogin()" class="btn btn-lg w-100 text-white fw-bold" style="background-color: #8d0c0c; border-radius: 10px; border: none;">
                    Torna al login
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Questa funzione si attiva SOLO quando l'utente clicca sul bottone del popup
    function reindirizzaLogin() {
        window.location.href = "index.php?error=timeout";
    }

    (function() {
        const TEMPO_INATTIVITA = 1800; // 5 secondi per il tuo test (poi rimetti 1800000 per 30 minuti)
        let timer_inattivita;

        function eseguiLogout(){
            // 1. Mostriamo subito il popup graficamente a schermo
            const modalElement = document.getElementById('timeoutModal');
            if (typeof bootstrap !== 'undefined') {
                const bootstrapModal = new bootstrap.Modal(modalElement);
                bootstrapModal.show();
            } else {
                // Alternativa di sicurezza se Bootstrap non risponde subito
                alert("La tua sessione è scaduta per inattività!");
                reindirizzaLogin();
            }

            // 2. Eseguiamo la disconnessione reale sul server in sottofondo (senza cambiare pagina)
            fetch('<?php echo basename($_SERVER['PHP_SELF']); ?>?azione=logout_timeout')
            .then(response => response.json())
            .then(data => {
                console.log("Sessione distrutta sul server con successo.");
            })
            .catch(err => {
                console.log("Notifica inviata al server.");
            });
        }

        function resettaTimer(){
            clearTimeout(timer_inattivita);
            timer_inattivita = setTimeout(eseguiLogout, TEMPO_INATTIVITA);
        }

        // Rimane in ascolto dei movimenti dell'utente per azzerare il timer
        const eventi = ['mousemove', 'mousedown', 'keypress', 'touchstart', 'scroll'];
        eventi.forEach(evento => {
            document.addEventListener(evento, resettaTimer, true);
        });

        resettaTimer();
    })();
</script>