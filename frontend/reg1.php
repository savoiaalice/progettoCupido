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
            padding: 2rem 0;
            /* Spazio extra per scroll su schermi piccoli */
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
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .item-ancorato{
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .form-check-input:checked{
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

         .form-check-input:focus{
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
                        <i class="bi bi-arrow-through-heart"
                            style="font-size: 2.5rem; color: var(--primary-color);"></i>
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
                        <a type="submit" href="reg2.php" class="btn btn-primary-action text-white btn-lg">
                            PROSEGUI
                        </a>
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