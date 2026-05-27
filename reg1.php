<?php
    require 'connessioneDB.php';
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

        /* 📱 RESPONSIVENESS OTTIMIZZATA (Senza alterare le classi strutturali) */
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
                        <?php include "cupido.php"; ?>
                    </div>
                    <h1 class="fw-bold h1" style="letter-spacing: 2px;">CUPIDO</h1>
                </div>

                <div class="text-center">
                    <h3 class="form-title">Parlaci dei tuoi interessi</h3>
                </div>

                <form id="register-form" method="POST" action="azioni_utente.php">
                    <input type="hidden" name="azione" value="registrazione_interessi">
                    <label class="form-label small fw-bold">Cosa ti piace fare nel tempo libero?</label><br>
                    <div class="d-flex flex-wrap gap-2">

                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="sport" value="sport" class="form-check-input">
                            <label for="sport">Fare sport</label>  
                        </div>
                        
                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="cucinare" value="cucina" class="form-check-input">
                            <label for="cucina">Cucinare</label>  
                        </div>
                        
                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="viaggiare" value="viaggi" class="form-check-input   ">
                            <label for="viaggi">Viaggiare</label> 
                        </div>

                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="leggere" value="lettura" class="form-check-input">
                            <label for="lettura">Leggere</label> 
                        </div>

                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="film" value="cinema" class="form-check-input   ">
                            <label for="cinema">Guardare film o serie tv</label>
                        </div>

                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="suonare" value="suonare" class="form-check-input">
                            <label for="suonare">Suonare uno strumento</label>
                        </div>

                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="camping" value="camping" class="form-check-input">
                            <label for="camping">Camping</label>
                        </div>

                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="casa" value="casa" class="form-check-input">
                            <label for="casa">Casa e relax</label>
                        </div>

                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="meditazione" value="meditazione" class="form-check-input">
                            <label for="meditazione">Meditazione</label>
                        </div>

                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="cena" value="cena" class="form-check-input">
                            <label for="cena">Andare a cena fuori</label>
                        </div>
 
                    </div><br>


                    <label class="form-label small fw-bold">Quali sono gli aggettivi che meglio ti
                        descrivono?</label><br>
                    <div class="d-flex flex-wrap gap-2">

                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="solare" value="solare" class="form-check-input">
                            <label for="solare">Solare</label>
                        </div>
                        
                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="riflessivo" value="riflessivo" class="form-check-input">
                            <label for="riflessivo">Riflessivo</label>
                        </div>


                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="spontaneo" value="spontaneo" class="form-check-input">
                            <label for="spontaneo">Spontaneo</label>
                        </div>

                        
                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="determinato" value="determinato" class="form-check-input">
                            <label for="determinato">Determinato</label>
                        </div>
                        
                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="curioso" value="curioso" class="form-check-input">
                            <label for="curioso">Curioso</label>
                        </div>
                        
                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="sognatore" value="sognatore" class="form-check-input">
                            <label for="sognatore">Sognatore</label>
                        </div>
                        
                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="empatico" value="empatico" class="form-check-input">
                            <label for="empatico">Empatico</label>
                        </div>
                        
                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="ironico" value="ironico" class="form-check-input">
                            <label for="ironico">Ironico</label>
                        </div>
                        
                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="colto" value="colto" class="form-check-input">
                            <label for="colto">Colto</label>
                        </div>
                        
                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="leale" value="leale" class="form-check-input">
                            <label for="leale">Leale</label>
                        </div>
                        
                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="tranquillo" value="tranquillo" class="form-check-input">
                            <label for="tranquillo">Tranquillo</label>
                        </div>
                        
                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="socievole" value="socievole" class="form-check-input">
                            <label for="socievole">Socievole</label>
                        </div>
                        
                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="premuroso" value="premuroso" class="form-check-input">
                            <label for="premuroso">Premuroso</label>
                        </div>
                        
                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="timido" value="timido" class="form-check-input">
                            <label for="timido">Timido</label>
                        </div>
                        
                        <div class= "item-ancorato"> 
                            <input type="checkbox" name="avventuroso" value="avventuroso" class="form-check-input">
                            <label for="avventuroso">Avventuroso</label>
                        </div>
                        
                        
                    </div><br>

                    <div class="d-grid">
                        <button type="submit" href="reg2.php" class="btn btn-primary-action text-white btn-lg">
                            PROSEGUI
                        </button>
                    </div>
                </form>

                <div class="mt-4 text-center">
                    <a href="registrazione.php" class="text-decoration-none"
                        style="color: var(--primary-color); font-weight: 600;">
                        Torna indietro
                    </a>
                    </p>
                </div>
            </div>

        </div>
    </div>
    <!-- br aggiunto per poter inciare il file  -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>