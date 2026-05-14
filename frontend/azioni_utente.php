<?php
    require 'connessineDB.php';
    session_start();

    $azione = $_POST['azione'] ?? '';

    switch ($azione){
        case 'registrazione':
            $id_utente = $_POST['id_utente'];
            $email     = $_POST['email'];
            $password  = $_POST['password']; 
            $nome      = $_POST['nome'];
            $cognome   = $_POST['cognome'];
            $sesso     = $_sesso;
            $eta       = $_POST['eta'];
            $citta     = $_POST['citta'];

            try {
                $pdo->beginTransaction();

                // 1. Tabella principale
                $sql1 = "INSERT INTO datiregistrazione (id_utente, email, password, nome, cognome, sesso, eta, citta, maxEta, distanza) 
                         VALUES (:id, :email, :pass, :nome, :cognome, :sesso, :eta, :citta, 99, 50)";
                $stmt1 = $pdo->prepare($sql1);
                $stmt1->execute([
                    ':id' => $id_utente, ':email' => $email, ':pass' => $password, 
                    ':nome' => $nome, ':cognome' => $cognome, ':sesso' => $sesso, 
                    ':eta' => $eta, ':citta' => $citta
                ]);

                // 2. Inserimento righe vuote per interessi e aggettivi (necessario per futuri UPDATE)
                $pdo->prepare("INSERT INTO interessi (id_utente) VALUES (?)")->execute([$id_utente]);
                $pdo->prepare("INSERT INTO aggettivi (id_utente) VALUES (?)")->execute([$id_utente]);

                $pdo->commit();
                $_SESSION['user_id'] = $id_utente;
                header("Location: reg2.php");
            } catch (Exception $e) {
                $pdo->rollBack();
                echo "Errore: " . $e->getMessage();
            }
            break;

        case 'carica_foto_profilo':
            if(isset($_FILES['foto'])) {
                $nome = $_FILES['foto']['name'];
                $tmp = $_FILES['foto']['tmp_name'];
                $percorso = "foto/" . $nome;
                
                if(move_uploaded_file($tmp, $percorso)) {
                    
                    $squl = "INSERT INTO foto_utenti (id_utente, percorso, tipo) VALUES (:id, :percorso, 'profilo')";
                    $stmt = $pdo->prepare($squl);
                    $stmt->execute([
                        ':percorso' => $percorso, 
                        ':id' => $_SESSION['user_id']
                    ]);

                    header("Location: reg4.php");
                }
            }  
            break;

        case 'carica_foto_card':
            if(isset($_FILES['foto'])) {
                foreach($_FILES['foto']['name'] as $chiave => $nomeOg) {
                    $tmp = $_FILES['foto']['tmp_name'][$chiave];
                    $nuovoNome = time() . "_" . $nomeOg;
                    $percorso = "foto/" . $nuovoNome;

                    if(move_uploaded_file($tmp, $percorso)) {
                        // CORREZIONE: Usiamo la tabella 'foto_utenti' del tuo SQL con tipo 'galleria'
                        $squl = "INSERT INTO foto_utenti (id_utente, percorso, tipo) VALUES (:id, :percorso, 'galleria')";
                        $stmt = $pdo->prepare($squl);
                        $stmt->execute([
                            ':id' => $_SESSION['user_id'], 
                            ':percorso' => $percorso
                        ]);
                    }
                }
                header("Location: reg4.php");
            }
            break;
        
        default:
            header("Location: home.php");
            exit();
    }
?>