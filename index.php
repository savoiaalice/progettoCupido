<?php
require __DIR__ . "/connessioneDB.php";
session_start();

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DatingApp - Login</title>
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

        /* RESPONSIVENESS OTTIMIZZATA (Senza alterare le classi strutturali) */
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
                <!-- logo e nome -->
                <div class="text-center mb-4">
                    <div class="brand-logo mb-2">
                        <i class="bi bi-arrow-through-heart" style="font-size: 3rem; color: var(--primary-color);"></i>
                    </div>
                    <h1 class="fw-bold" style="letter-spacing: 2px;">CUPIDO</h1>
                </div>

                <!-- titoloform -->
                <div class="text-center">
                    <h3 class="form-title">Accedi</h3>
                    <p class="text-muted mb-4">Inserisci le tue credenziali.</p>
                </div>

                <!-- oggetto form -->
                <form id="login-form" method="POST" action="azioni_utente.php">
                    <input type="hidden" name="azione" value="accesso">
                    <div class="mb-3">
                        <label for="cellaLogin" class="form-label small fw-bold">Email o Username</label>
                        <input type="text" class="form-control" name="cellaLogin" placeholder = "Inserisci la tua email o username"required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label small fw-bold">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary-action text-white btn-lg">
                            Entra nel profilo
                        </button>
                    </div>
                </form>

                <!-- link che rimanda alla registrazione -->
                <div class="mt-4 text-center">
                    <p class="small">
                        Non hai ancora un account? <br>
                        <a href="registrazione.php" class="text-decoration-none"
                            style="color: var(--primary-color); font-weight: 600;">
                            Registrati gratuitamente
                        </a><br>
                        <a href="home.php" class="text-decoration-none"
                            style="color: var(--primary-color); font-weight: 600;">
                            Torna alla home
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
<!-- br aggiunto per poter inciare il file  -->
<br>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>