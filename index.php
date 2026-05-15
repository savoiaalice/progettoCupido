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
        /* Definizione dei colori a tema senza rinominare le classi */
        :root {
            --primary-color: #c62874;
            --accent-color: #fce4ec;
            --text-main: #333;
        }

        body {
            background-color: var(--accent-color);
            font-family: 'Montserrat';
        }

        /* Container principale del layout */
        .main-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Sezione di sinistra: Immagine e Logo */
        .hero-section {
            background: linear-gradient(rgba(224, 82, 96, 0.6), rgba(0, 0, 0, 0.6)),
                url('https://images.unsplash.com/photo-1518199266791-c379a92b9414?q=80&w=1470&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 3rem;
        }

        /* Sezione di destra: Form di Login */
        .auth-section {
            background-color: #ffffff;
            padding: 3rem;
        }

        .form-title {
            color: var(--primary-color);
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        /* Pulsante d'azione principale */
        .btn-primary-action {
            background-color: var(--primary-color);
            border: none;
            padding: 12px;
            font-weight: 600;
            transition: opacity 0.3s;
        }

        .btn-primary-action:hover {
            background-color: #c62874;
            opacity: 0.9;
        }

        /* Utility per i bordi arrotondati del contenitore */
        .content-box {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
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
                <!-- logo e nome -->
                <div class="text-center mb-4">
                    <div class="brand-logo mb-2">
                        <?php include "cupido.php"; ?>
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
                        <label for="id_utente" class="form-label small fw-bold">Username</label>
                        <input type="text" class="form-control" name="id_utente" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label small fw-bold">Indirizzo Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>

                    <div class="mb-4">
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