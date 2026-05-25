<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
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
            font-family: 'Montserrat', sans-serif;
        }

        .main-wrapper {
            min-height: auto;
            display: block;
            align-items: center;
            justify-content: center;
            padding: 1rem 0 80px 0;
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
            color: white;
            padding: 12px 14px;
            font-weight: 600;
            transition: all 0.3s;
        }
/* per le icone nei button */
        .btn-primary-action i{
            display:inline-flex;
            align-items: center;
        }

        .btn-primary-action:hover {
            background-color: #a31f5f;
            opacity: 0.9;
            color: white;
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

        .profile-card {
            background: white; 
            border-radius: 20px; 
            padding: 3rem; /* Aumentato il padding interno come nell'auth-section */
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
            margin: 0 auto; 
           
        }
        .profile-img {
            width: 180px; height: 180px; border-radius: 50%;
            object-fit: cover; border: 5px solid var(--primary-color);
        }
        .tag {
            background: var(--primary-color); color: white;
            padding: 5px 12px; border-radius: 20px;
            margin: 3px; display: inline-block;
        }
        
        .galleria {
            width: 100%; 
            padding-top: 100%; 
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        .galleria-imm {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .galleria:hover .galleria-imm {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="profile-card mx-auto col-lg-8" style="position: relative;">

            <div class="text-center mb-4">
                <div class="brand-logo mb-2">
                    <?php include "cupido.php"; ?>
                </div>
                <h1 class="fw-bold h1" style="letter-spacing: 2px; color: var(--text-main);">CUPIDO</h1>
            </div>

            <div class="text-center mb-4">
                <h3 class="form-title">Finalizza il tuo profilo!</h3>
                <h5 class="text-muted fw-normal">Inserisci le foto per farti conoscere dagli altri utenti!</h5>
            </div>
            
            <hr class="my-4">

            <form action="azioni_utente.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="azione" value="carica_foto_profilo">
                <div class="mb-5">
                    <label for="fotoProfilo" class="form-label h6 fw-bold" style="color: var(--text-main);">
                        <i class="bi bi-person-bounding-box me-2" style="color: var(--primary-color);"></i>
                        Seleziona la tua foto profilo 
                    </label>
                    <input type="file" class="form-control rounded-pill shadow-sm" id="fotoProfilo" name="foto" accept="image/*" required>
                    <div class="form-text ps-2">Formati supportati: JPG, PNG</div>
                    
                    <div class="d-flex justify-content-center mt-3">
                        <button type="submit" class="btn btn-primary-action text-white btn-sm rounded-pill px-4 py-2 shadow-sm d-flex align-items-center gap-2">
                            <i class="bi bi-cloud-arrow-up-fill"></i>
                            CARICA FOTO PROFILO
                        </button>
                    </div>
                </div>
            </form>                    
            
            <hr class="my-4">

            <form action="azioni_utente.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="azione" value="carica_foto_card">
                <div class="mb-5">
                    <label for="fotoCard" class="form-label h6 fw-bold" style="color: var(--text-main);">
                        <i class="bi bi-images me-2" style="color: var(--primary-color);"></i>
                        Aggiungi foto alla tua galleria personale
                    </label>
                    <input type="file" class="form-control rounded-pill shadow-sm" id="fotoCard" name="foto[]" accept="image/*" multiple required>
                    <div class="form-text ps-2">Formati supportati: JPG, PNG</div>
                    
                    <div class="d-flex justify-content-center mt-3">
                        <button type="submit" class="btn btn-primary-action text-white btn-sm rounded-pill px-4 py-2 shadow-sm d-flex align-items-center gap-2">
                            <i class="bi bi-cloud-arrow-up-fill"></i>
                            AGGIUNGI ALLA GALLERIA
                        </button>
                    </div>                    
                </div>
            </form>
            
            <hr class="my-4">

            <div class="mt-4 text-center">
                <form action="match.php" method="POST">
                    <div class="d-flex justify-content-center mt-3">
                        <button type="submit" class="btn btn-primary-action text-white rounded-pill px-4 py-2 shadow-sm d-flex align-items-center gap-2">
                            SALVA
                        </button>
                    </div>
                </form>
                <p class="small mb-0">
                    <a href="reg2.php" class="text-decoration-none d-inline-flex align-items-center gap-1"
                       style="color: var(--primary-color); font-weight: 600;">
                        <i class="bi bi-arrow-left"></i> Torna indietro
                    </a>
                </p>
            </div>

        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>