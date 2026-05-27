<?php
//se l'utente non è attivo dopo un determinato numero di secondi allora faccio automaticamente il logout e chiudo la sessione
    if(session_status()=== PHP_SESSION_NONE){
        session_start();
    }
    if(!isset($_SESSION['id_utente'])){
        header("Location: index.php");
        exit();
    }

    //la pagina viene chiamata via AJAX/fecth per la disconnessione
    if(isset($_GET['azione']) && $_GET['azione']==='logout_timeout'){
        $_SESSION=array();
        if(ini_get("session.use_cookies")){
            $parametri=session_get_cookie_params();
            set_cookie(session_name(), '', time()-4200,
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
<script>
    (function(){
        const TEMPO_INATTIVITA=50000000000000;
        let timer_inattivita;
        function eseguiLogout(){
            fetch('<?php echo basename($_SERVER['PHP_SELF']); ?>?azione=logout_timeout')
            .then(response=>response.json())
            .then(data=>{
                if(data.status === 'scaduto'){
                    alert('Sessione scaduta per inattività. Effettua di nuovo il login');
                    window.location.href="index.php?error=timeout";
                }
            })
            .catch(()=>{
                window.location.href = 'index.php?error=timeout';
            })
        }
        function resettaTimer(){
            clearTimeout(timer_inattivita);
            timer_inattivita = setTimeout(eseguiLogout, TEMPO_INATTIVITA);
        }
        //eventi da monitorare
        const eventi = ['mousemove', 'mousedown', 'keypress', 'touchstart', 'scroll'];
    
        eventi.forEach(evento => {
            document.addEventListener(evento, resettaTimer, true);
        });
        resettaTimer();
    })();
</script>