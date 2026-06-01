
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
                    <i class="bi bi-search-heart fs-3" style="color:#8d0c0c;"></i>
                </a>
            </div>
            <div class="col">
                <a href="chat_completa.php" class="text-decoration-none text-dark">
                    <i class="bi bi-chat-heart fs-3" style="color:#8d0c0c;"></i>
                </a>
            </div>
            <div class="col">
                <a href="profilo.php" class="text-decoration-none text-dark">
                    <i class="bi bi-person-circle fs-3" style="color:#8d0c0c;"></i>
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
                        <a href="chat_completa.php?id=${n.id_mit}" class="btn btn-sm btn-primary" style="font-size:0.72rem; padding: 3px 8px;">Chatta 💬</a>
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