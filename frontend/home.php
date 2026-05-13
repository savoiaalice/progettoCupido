<?php
    require 'connessineDB.php';
    $messaggio = "Cupido versione dinamica";

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
            padding: 2rem 0; /* Spazio extra per scroll su schermi piccoli */
        }

        .hero-section {
            background: linear-gradient(rgba(198, 40, 116, 0.6), rgba(0, 0, 0, 0.6)), 
                        url('https://images.unsplash.com/reserve/Af0sF2OS5S5gatqrKzVP_Silhoutte.jpg?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
                        /*foto della pagina di apertura*/
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
        .btn-outline-custom{
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            font-weight: 600;
        }
        .btn-outline-custom:hover{
            background-color: #a31f5f;
            color:white;
            border-color: #a31f5f;
        }

        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(198, 40, 116, 0.25);
        }

        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(198, 40, 116, 0.25);
        }
    </style>
</head>

<body>
    <!-- verifico se gli utenti sono inseriti e raggiungibili -->
<!--     
    <p>
        <?php
            try{
                $stmt = $pdo-> query("SELECT * FROM users");
                $numero = $stmt->rowCount();
                echo "Numero di utenti registrati: " . $numero;
            } catch (PDOException $e) {
                echo "Errore nel recupero dei dati: " . $e->getMessage();
            }
        ?>
    </p> -->
    
    <nav class="navbar navbar-expand-lg fixed-top px-4 py-3"> <!--barra di navigazione-->
        <div class="container-fluid justify-content-end">
            <div class="d-flex gap-2"> <!--dice ai div di rimanere fluidi nell'allinearsi, invece di andare a capo-->
                <a href="index.php" class="btn btn-outline-custom rounded-pill px-4 text-center">
                    Accedi
                </a>
                <a href="registrazione.php" class="btn btn-primary-action text-white rounded-pill px-4">
                    Registrati
                </a>
            </div>
        </div>
    </nav>

    <!--struttura della fascia grafica centrale-->
    <div class="container main-wrapper">
        <div class="row content-box w-100">
            
            <div class="col-lg-7 d-none d-lg-flex hero-section flex-column justify-content-center text-center">
                <!-- eventuale contenuto scritto nel centro 
                VUOTA COME è ADESSO SI PUò TOGLIERE-->
            </div>

            <div class="col-lg-5 auth-section">
                <div class="text-center mb-4">
                    <div class="brand-logo mb-2">
                        <!-- icona -->
                        <i class="bi bi-arrow-through-heart" style="font-size: 2.5rem; color: var(--primary-color);"></i>
                    </div>
                    <!--  -->
                    <h1 class="fw-bold h1" style="letter-spacing: 2px;">CUPIDO</h1>
                </div>
                <div class="text-center">
                    <h3>
                        Cupido è il sito che, tenendo conto dei tuoi interessi e preferenze in ambito relazionale, ti aiuta a trovare la persona giusta.
                    </h3>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>