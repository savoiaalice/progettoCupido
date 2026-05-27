<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cupido - Registrazione</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        /* Configurazione colori identica alla Home */
        :root {
            --primary-color: #8d0c0c; /* Rosso Cupido */
            --accent-color: #fce4ec;
            --text-main: #000000;
        }

        body {
            background-color: var(--accent-color);
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
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
            background-image: url('./fotoOrizzontale.png'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.45;
        }

        /* Wrapper principale centrato a schermo intero */
        .main-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        /* Il blocco contenitore unito (Stile scheda arrotondata ed elegante) */
        .content-box {
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            background: transparent;
        }

        /* Sezione SINISTRA: Immagine Hero riadattata */
        .hero-section {
            background: linear-gradient(rgba(141, 12, 12, 0.45), rgba(0, 0, 0, 0.65)),
                url('https://images.unsplash.com/photo-1518199266791-c379a92b9414?q=80&w=1470&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 4rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Sezione DESTRA: Il form diventa un box bianco semi-trasparente effetto vetro */
        .auth-section {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 3.5rem 3rem;
            backdrop-filter: blur(8px);
        }

        /* Larghezza personalizzata per desktop */
        .col-lg-5 {
            max-width: 480px;
        }

        .form-title {
            color: var(--primary-color);
            font-weight: 700;
        }

        /* Elementi di input rifiniti */
        .form-control {
            border-radius: 10px;
            padding: 11px 14px;
            border: 1px solid #ced4da;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(141, 12, 12, 0.25);
        }

        /* Pulsante Accedi Ovale e Rosso come i bottoni principali della Home */
        .btn-primary-action {
            background-color: var(--primary-color);
            border: none;
            padding: 14px;
            font-weight: 600;
            border-radius: 50px; /* Trasformato in ovale coordinato */
            box-shadow: 0 4px 15px rgba(141, 12, 12, 0.2);
            transition: all 0.3s ease;
        }

        .btn-primary-action:hover {
            background-color: #d45e5e;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(141, 12, 12, 0.3);
            opacity: 1;
        }

        /*RESPONSIVENESS OTTIMIZZATA (Senza alterare le classi strutturali) */
        @media (max-width: 991px) {
            .content-box {
                max-width: 540px;
            }
        }

        @media (max-width: 576px) {
            body::before {
                background-image: url('fotoVerticale.png'); /* Sfondo mobile della Home */
            }
            .main-wrapper {
                padding: 1rem;
            }
            .auth-section {
                padding: 2rem 1.5rem; /* Spazi più compatti e fluidi */
            }
            .hero-section {
                padding: 2.5rem 1.5rem;
            }
            h1 {
                font-size: 2rem;
            }
            .btn-lg {
                padding: 12px 14px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

<div class="container main-wrapper">
    <div class="row content-box w-100 justify-content-center">
        <div class="col-lg-5 auth-section">
            <div class="text-center mb-4">
                <div class="brand-logo mb-2">
                    <i class="bi bi-arrow-through-heart" style="font-size: 2.5rem; color: var(--primary-color);"></i>
                </div>
                <h1 class="fw-bold h1" style="letter-spacing: 2px;">CUPIDO</h1>
            </div>

            <div class="text-center">
                <h3 class="form-title">Crea Account</h3>
                <p class="text-muted mb-4">La tua anima gemella ti sta aspettando!</p>
            </div>

            <form id="register-form" method="POST" action="azioni_utente.php">
                <input type="hidden" name="azione" value="registrazione1">
                
                <div class="mb-3">
                    <label for="nome" class="form-label small fw-bold">Nome</label>
                    <input type="text" id="nome" class="form-control" name="nome" required>
                </div>
                
                <div class="mb-3">
                    <label for="cognome" class="form-label small fw-bold">Cognome</label>
                    <input type="text" id="cognome" class="form-control" name="cognome" required>
                </div>

                <div class="mb-3">
                    <label for="id_utente" class="form-label small fw-bold">Username</label>
                    <input type="text" id="id_utente" class="form-control" name="id_utente" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label small fw-bold">Indirizzo Email</label>
                    <input type="email" id="email" class="form-control" name="email" required>
                </div>
               
                <div class="mb-3">
                    <label for="password" class="form-label small fw-bold">Password</label>
                    <input type="password" id="password" class="form-control" name="password" required>
                </div>
               
                <div class="mb-3">
                    <label for="data" class="form-label small fw-bold">Data di nascita</label>
                    <input type="date" name="data" id="data" class="form-control" required>
                </div>

                <input type="hidden" id="latitudine" name="latitudine">
                <input type="hidden" id="longitudine" name="longitudine">

                <div class="mb-3 position-relative">
                    <label for="citta" class="form-label small fw-bold">Vengo da:</label>
                    <div class="input-group">
                        <button type="button" id="btn-gps" class="btn rounded-start-pill border border-2 border-end-0 bg-white" title="Rileva posizione">
                            <i class="bi bi-geo-alt-fill bi-crosshairs" style="color: var(--primary-color);"></i>
                        </button>
                        
                        <input type="text" id="citta" name="citta" class="form-control border border-2 border-start-0 rounded-end-pill" placeholder="Inserisci la città...(es. Roma)" autocomplete="one-time-code" required>
                    </div>
                    
                    <div id="suggerimento" class="list-group position-absolute w-100 shadow" style="z-index: 1050;"></div>
                </div>
                <br>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary-action text-white btn-lg">
                        PROSEGUI
                    </button>
                </div>
            </form>

            <div class="mt-4 text-center">
                <p class="small text-muted">
                    Hai già un account? <br>
                    <a href="index.php" class="text-decoration-none" style="color: var(--primary-color); font-weight: 600;">
                        Torna all'accesso
                    </a><br>
                    <a href="home.php" class="text-decoration-none" style="color: var(--primary-color); font-weight: 600;">
                        Torna alla home
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

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
                        const nomeComune = data.address.city || data.address.town || data.address.village;
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
                
                // CORRETTO: Sistemata sintassi if(iconaGps)
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
                    }, // CORRETTO: Chiusura dell'oggetto opzioni con parentesi tonda corretta
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
</body>
</html>