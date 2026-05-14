<?php
    require 'connessioneDB.php';
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizza profilo</title>
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

        /* .hero-section {
            background: linear-gradient(rgba(198, 40, 116, 0.6), rgba(0, 0, 0, 0.6)), 
                        url('https://images.unsplash.com/photo-1511988617509-a57c8a288659?q=80&w=1471&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 3rem;
        } */

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
                    <h3 class="form-title">Finalizza il tuo profilo!</h3>
                </div>
<!-- FOTO PROFILO -->
                <div class="text-center">
                    <h3 class=" fw-bold">Inserisci foto per farti conoscere dagli altri utenti!</h3>
                </div>
                <br>

                <form action="carica_foto.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="fotoProfilo" class="form-title">Seleziona dal dispositivo una foto profilo</label>
                        <input type="file" class="form-control" id="fotoProfilo" name="foto" accept="image/*" required>
                        <div class="form-text">Formati supportati: JPG, PNG, GIF</div>
                        <div class="d-flex justify-content-center mt-2">
                            <button type="submit" class="btn btn-primary-action text-white btn-sm px-4">
                                CARICA FOTO 
    <!-- ALICE vedi se ti piace con o senza la nuvoletta carica vicino, a me non fa impazzire -->
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i>
                            </button>
                    </div>
                    </div>                     

                    <div>
                        <label for="fotoCard" class="form-title">Seleziona dal dispositivo foto prda aggiungere</label>
                        <input type="file" class="form-control" id="fotoCard" name="foto[]" accept="image/*" required>
                        <div class="form-text">Formati supportati: JPG, PNG, GIF</div>
                        <div class="d-flex justify-content-center mt-2">
                            <button type="submit" class="btn btn-primary-action text-white btn-sm px-4">
                                CARICA FOTO 
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i>
                            </button>
                        </div>
                    </div>
                    <br>

                    
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary-action text-white btn-lg">
                            SALVA  
                        </button>
                    </div>
                </form>

                <div class="mt-4 text-center">
                    <p class="small"> 
                        <a href="reg2.php" class="text-decoration-none"
                            style="color: var(--primary-color); font-weight: 600;">
                            Torna indietro
                        </a>
                    </p>
                </div>


            </div>
        </div>    
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"> </script>
    
</body>
</html>