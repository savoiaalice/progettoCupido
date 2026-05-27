<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    require __DIR__ . '/connessioneDB.php';
    session_start();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cupido - Registrazione</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
   
    <style>
        :root {
            --primary-color: #c62874;
            --accent-color: #fce4ec;
            --text-main: #333;
        }

        body {
            background-color: var(--accent-color);
            font-family: 'Montserrat', sans-serif;
        }

        .main-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .auth-section {
            background-color: #ffffff;
            padding: 3rem;
        }

        .form-title {
            color: var(--primary-color);
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .btn-primary-action {
            background-color: var(--primary-color);
            border: none;
            padding: 12px;
            font-weight: 600;
            transition: opacity 0.3s;
        }

        .btn-primary-action:hover {
            background-color: #a31f5f;
            opacity: 0.9;
        }

        .content-box {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(198, 40, 116, 0.25);
        }
        
        /* Sistema l'allineamento della tendina dei suggerimenti */
        #suggerimento {
            z-index: 1050;
            display: none;
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
                    <label for="data" class="form-label small fw-bold">Data di Nascita:</label>
                    <input type="date" id="data" class="form-control rounded-pill border-2" name="data" min="18" max="100" style="width: 100px;" required>
                </div>

                <input type="hidden" id="latitudine" name="latitudine">
                <input type="hidden" id="longitudine" name="longitudine">

                <div class="mb-3 position-relative">
                    <label for="citta" class="form-label small fw-bold">Vengo da:</label>
                    <div class="input-group">
                        <button type="button" id="btn-gps" class="btn rounded-pill-start border border-2 border-end-0 bg-white" title="Rileva posizione">
                            <i class="bi bi-geo-alt-fill bi-crosshairs" style="color: var(--primary-color);"></i>
                        </button>
                        
                        <input type="text" id="citta" name="citta" class="form-control  border border-2 border-start-0 rounded-pill-end" placeholder="Inserisci la città...(es. Roma)" autocomplete="off" required>
                        
                        
                    </div>
                    
                    <div id="suggerimento" class="list-group position-absolute w-100 shadow"></div>
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