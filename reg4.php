<?php
    require 'connessioneDB.php';
    session_start();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cupido - Finalizza profilo</title>
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
            display: flex;
            justify-content: center;
            width: 100%;
        }

        /* Il form diventa un box bianco semi-trasparente effetto vetro al 95% */
        .auth-section {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 3.5rem 3rem;
            backdrop-filter: blur(8px);
            width: 100%;
        }

        /* Questa classe definisce la larghezza esatta che vedi nella seconda foto */
        .col-lg-5 {
            max-width: 480px;
            width: 100%;
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

        /* Pulsanti d'azione ovali rossi e centrati */
        .btn-primary-action {
            background-color: var(--primary-color);
            border: none;
            padding: 10px 24px;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(141, 12, 12, 0.2);
            transition: all 0.3s ease;
        }

        .btn-primary-action:hover {
            background-color: #d45e5e;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(141, 12, 12, 0.3);
            opacity: 1;
        }

        @media (max-width: 991px) {
            .col-lg-5 {
                max-width: 540px;
            }
        }

        @media (max-width: 576px) {
            body::before {
                background-image: url('fotoVerticale.png');
            }
            .main-wrapper {
                padding: 1rem;
            }
            .auth-section {
                padding: 2rem 1.5rem;
            }
            h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

<div class="container main-wrapper">
    <div class="row w-100 justify-content-center">

        <div class="col-lg-5 content-box">
            <div class="auth-section">
                
                <div class="text-center mb-4">
                    <div class="brand-logo mb-2">
                        <?php include "cupido.php"; ?>
                    </div>
                    <h1 class="fw-bold h1" style="letter-spacing: 2px; color: var(--text-main);">CUPIDO</h1>
                </div>

                <div class="text-center mb-4">
                    <h3 class="form-title">Finalizza il tuo profilo!</h3>
                    <h5 class="text-muted fw-normal small">Inserisci le foto per farti conoscere dagli altri utenti!</h5>
                </div>
                
                <hr class="my-4">

                <form action="azioni_utente.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="azione" value="carica_foto_profilo">
                    <div class="mb-4">
                        <label for="fotoProfilo" class="form-label small fw-bold" style="color: var(--text-main);">
                            <i class="bi bi-person-bounding-box me-2" style="color: var(--primary-color);"></i>
                            Seleziona la tua foto profilo 
                        </label>
                        <input type="file" class="form-control shadow-sm" id="fotoProfilo" name="foto" accept="image/*" required>
                        <div class="form-text ps-2 small">Formati supportati: JPG, PNG</div>
                        
                        <div class="d-flex justify-content-center mt-3">
                            <button type="submit" class="btn btn-primary-action text-white btn-sm d-flex align-items-center gap-2">
                                <i class="bi bi-cloud-arrow-up-fill"></i>
                                CARICA FOTO PROFILO
                            </button>
                        </div>
                    </div>
                </form>                    
                
                <hr class="my-4">

                <form action="azioni_utente.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="azione" value="carica_foto_card">
                    <div class="mb-4">
                        <label for="fotoCard" class="form-label small fw-bold" style="color: var(--text-main);">
                            <i class="bi bi-images me-2" style="color: var(--primary-color);"></i>
                            Aggiungi foto alla tua galleria personale
                        </label>
                        <input type="file" class="form-control shadow-sm" id="fotoCard" name="foto[]" accept="image/*" multiple required>
                        <div class="form-text ps-2 small">Formati supportati: JPG, PNG</div>
                        
                        <div class="d-flex justify-content-center mt-3">
                            <button type="submit" class="btn btn-primary-action text-white btn-sm d-flex align-items-center gap-2">
                                <i class="bi bi-cloud-arrow-up-fill"></i>
                                AGGIUNGI ALLA GALLERIA
                            </button>
                        </div>                    
                    </div>
                </form>
                
                <hr class="my-4">

                <div class="mt-4 text-center">
                    <form action="match.php" method="POST">
                        <div class="d-flex justify-content-center mt-3 mb-3">
                            <button type="submit" class="btn btn-primary-action text-white d-flex align-items-center gap-2">
                                SALVA
                            </button>
                        </div>
                    </form>
                    <p class="small mb-0">
                        <a href="reg2.php" class="text-decoration-none d-inline-flex align-items-center gap-1" style="color: var(--primary-color); font-weight: 600;">
                            <i class="bi bi-arrow-left"></i> Torna indietro
                        </a>
                    </p>
                </div>

            </div>
        </div>

    </div>
</div>
    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>