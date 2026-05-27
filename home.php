<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . "/connessioneDB.php";
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cupido - Registrazione</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
    :root {
        --primary-color: #8d0c0c;
        --text-main: #000000;
    }

    body {
        font-family: 'Montserrat', sans-serif;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        position: relative;
        overflow-x: hidden;
        background-color: #fce4ec;
    }

    /* Il tuo collage fotografico come sfondo a schermo intero con opacità */
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

    /* Navbar posizionata in alto a destra */
    .navbar-custom {
        position: absolute;
        top: 0;
        right: 0;
        padding: 1.5rem 2rem;
        z-index: 10;
    }

    .navbar-custom .btn-link-custom {
        color: #000000;
        font-weight: 600;
        text-decoration: none;
        font-size: 1.05rem;
    }

    .navbar-custom .btn-link-custom:hover {
        color: var(--primary-color);
    }

    /* Contenitore principale a tutta pagina */
    .main-container {
        min-height: 100vh;
        display: flex;
        position: relative;
        padding: 2rem;
    }

    /* Titolo a sinistra "Trova la tua metà." */
    .left-headline {
        position: absolute;
        left: 5%;
        top: 48%;
        transform: translateY(-50%);
        font-size: 3.5rem;
        font-weight: 700;
        color: #000000;
        max-width: 450px;
        line-height: 1.2;
    }

    /* SPECIFICHE GENERALI DEL RIQUADRO BIANCO (Funzionano SEMPRE, anche su PC) */
    .popup-box {
        background-color: rgba(255, 255, 255, 0.95); /* Sfondo bianco al 95% */
        padding: 3rem 2.5rem;
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        position: absolute;
        right: 12%;
        top: 52%;
        transform: translateY(-50%);
        max-width: 460px;
        width: 100%;
        z-index: 2;
        backdrop-filter: blur(8px);
    }

    .right-content-box {
        width: 100%;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .brand-title {
        font-size: 2.8rem;
        font-weight: 700;
        letter-spacing: 2px;
        color: #000000;
        margin-top: -0.5rem;
        margin-bottom: 1.5rem;
    }

    .site-description {
        font-size: 1.1rem;
        font-weight: 400;
        color: black;
        line-height: 1.5;
        margin-bottom: 2rem;
    }

    .btn-action-white {
        background-color: #8d0c0c;
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
        padding: 14px 32px;
        border-radius: 50px;
        border: none;
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-action-white:hover {
        background-color: #d45e5e;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        color: white;
    }

    /* 📱 ADATTAMENTO RESPONSIVE (Solo variazioni per Tablet/Mobile) */
    @media (max-width: 991px) {
        body::before {
            opacity: 0.5;
        }
        .navbar-custom {
            position: relative;
            width: 100%;
            justify-content: center;
            padding: 1rem;
        }
        .main-container {
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            padding-top: 1rem;
        }
        .left-headline {
            position: relative;
            left: auto;
            top: auto;
            transform: none;
            text-align: center;
            font-size: 2.2rem;
            margin-top: 2rem;
            margin-bottom: 3rem;
        }
        .popup-box {
            position: relative;
            right: auto;
            top: auto;
            transform: none;
            margin: 2rem auto;
            max-width: 90%;
            padding: 2rem 1.5rem;
        }
    }

    @media (max-width: 576px) {
        body::before {
            background-image: url('fotoVerticale.png');
        }
    }
</style>
    </style>
</head>

<body>

    <div class="navbar-custom d-flex gap-4 justify-content-end align-items-center">
        <a href="index.php" class="btn-action-white py-2 px-3" style="font-size: 0.95rem;">Accedi</a>
    </div>

    <div class="container-fluid main-container">
    
    <div class="left-headline">
        Trova la tua metà.
    </div>

    <div class="popup-box">
        <div class="right-content-box">
            <div class="brand-logo">
                <?php include "cupido.php"; ?>
            </div>
            
            <h1 class="brand-title">CUPIDO</h1>
            
            <p class="site-description">
                Cupido è il sito che, tenendo conto dei tuoi interessi e preferenze in ambito relazionale, ti aiuta a trovare la persona giusta.
            </p>
            
            <a href="registrazione.php" class="btn-action-white btn-registrati">
                Inizia la tua storia
            </a>
        </div>
    </div>

</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>