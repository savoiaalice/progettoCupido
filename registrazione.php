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
                <h3 class="form-title">Crea Account</h3>
                <p class="text-muted mb-4">La tua anima gemella ti sta aspettando!</p>
            </div>


 <!-- in action ci metto l'endpoint, è l'indirzzo di dove finiranno i dati che vengono caricati -->
            <form action="reg1.php" id="register-form"> 
                <!-- con action gli dico cosa fare, viene attivato il file reg1-->
                <div class="mb-3">
                    <!-- questo for coincide con l'id dell'input, serve per fare leggere lo screen reader,
                    elemento utile per i non vedenti,
                    aumenta l'area di click senza che l'utente debba essere preciso-->
                    <label for="fullname" class="form-label small fw-bold">Nome Completo</label>
                    <input type="text" class="form-control" id="fullname" required>
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label small fw-bold">Username</label>
                    <input type="text" class="form-control" id="username" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label small fw-bold">Indirizzo Email</label>
                    <input type="email" class="form-control" id="email" required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label small fw-bold">Password</label>
                    <input type="password" class="form-control" id="password" required>
                </div>

                
                <div class="mb-3">
                    <label for="age" class="form-label small fw-bold">Età:</label>
                    <input type="number" class="form-control rounded-pill border-2" id="age" min="18" max="100" style="width: 100px;" required>
                </div>

                <div class="mb-3">
                    <label for="location" class="form-label small fw-bold">Vengo da: </label>
                    <div class="input-group">
                        <span class="input-group-text bg-withe border-2 rounded-pill ">
                            <i class="bi bi-geo-alt-fill" text-danger></i>
                        </span>
                        <input type="text" class="form-control border-2 rounded-pill" id="location" required>
                        <button class="btn btn-otline-secondary border-2 rounded-pill">
                            <i class="bi bi-gps-fixed" style="color: var(--primary-color);"></i>
                        </button>
                    </div>
                    <div id="status-localizzazione" class="form-text" style="font-size: 0.8rem;">
                    </div>
                </div>

                <input type="hidden" id="latitudine" name="lat">
                <input type="hidden" id="longitudine" name="lng">
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
</body>
</html>