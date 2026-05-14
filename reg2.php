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
            font-family: 'Monteserrat';
        }

        .main-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0; /* Spazio extra per scroll su schermi piccoli */
        }

        .hero-section {
            background: linear-gradient(rgba(198, 40, 116, 0.6), rgba(0, 0, 0, 0.6)), 
                        url('https://images.unsplash.com/photo-1511988617509-a57c8a288659?q=80&w=1471&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 3rem;
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

        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(198, 40, 116, 0.25);
        }
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(198, 40, 116, 0.25);
        }
        .form-check-input{
            cursor: pointer;
        }

        .form-check-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(198, 40, 116, 0.25);
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
                <h3 class="form-title">Cosa cerchi in un partner?</h3>
            </div>


            <form id="register-form">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Io sono:</label>
                    <select class="form-select" id="gender" required>
                        <option value="uomo">Uomo</option>
                        <option value="donna">Donna</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Il mio partner deve essere:</label>
                    <select class="form-select" id="partner_gender" required>
                        <option value="uomo">Uomo</option>
                        <option value="donna">Donna</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label small fw-bold">Che tipo di relazione cerchi?</label>
                    <select class="form-select" id="relazione" required>
                        <option value="seria">Relazione seria</option>
                        <option value="aperta">Relazione aperta</option>
                        <option value="amicizia">Amicizia</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="ageDiff" class="form-label small fw-bold">Massima differenza di età</label>
                    <input type="number" class="form-control rounded-pill border-2" id="ageDiff" min="0" max="50" style="width: 100px;">
                </div>

                
                <div class="from-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch" id="lungaDistanza">
                    <label for="form-check-label small fw-bold" for="lungaDistanza">Aperto a relazioni a distanza?</label>
                </div>

                <br>
                

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary-action text-white btn-lg">
                        INVIA TUTTI I DATI
                    </button>
                </div>
            </form>

            <div class="mt-4 text-center">
                    <a href="reg1.php" class="text-decoration-none" style="color: var(--primary-color); font-weight: 600;">
                        Torna indietro
                    </a>
                </p>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
<!-- servirà per il server -->
    function rilevaPosizione(){
        const status = document.getElementById('status-localizzazione');
        const locationInput = document.getElementById('location');

        if(!navigator.geolocation){
            status.innerHTML = '<span class ="text-danger">Geolocalizzazione non disponibile.</span>';
            return;
        }

        status.innerHTML = '<span class="text-muted">Rilevamento della posizione...</span>';

        navigator.geolocation.getCurrentPosition((position) =>{
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            document.getElementById('latitudine').value = lat;
            document.getElementById('longitudine').value = lng;

            status.innerHTML = '<span class="text-success">Posizione rilevata con successo!</span>';
            locationInput.value = "Coordinate rilevate";
        },
        ()=>{
            status.innerHTML = '<span class="text-danger">Impossibile rilevare la posizione.</span>';
        }
        );
    }

<!-- br aggiunto per poter inciare il file  -->
</script>
</body>
</html>