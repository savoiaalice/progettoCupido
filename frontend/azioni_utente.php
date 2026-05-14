<?php
    require 'connessineDB.php';
    session_start();

    $azione = $_POST['azione'] ?? '';

    switch ($azione){
        case 'carica_foto_profilo':
            if(isset($_FILES['foto'])) {
                $nome = $_FILES['foto']['name']; // di questa foto voglio sapere il nome
                $tmp = $_FILES['foto']['tmp_name']; // e questa è la posizione temporanea in cui è stata salvata
                
                if(move_uploaded_file($tmp, "foto/" . $nome)) { // se riesco a spostare la foto dalla posizione temporanea a quella definitiva

                    // all'utente con id corrispondente a quello in sessione 
                    $squl = "UPDATE users SET foto_profilo = :foto WHERE id = :id"; // aggiorno la colonna foto_profilo della tabella users con il nome della foto appena caricata
                    $stmt = $pdo->prepare($squl);
                    $stmt->execute([':foto' => $nome, 
                                    ':id' => $_SESSION['user_id']]);

                    header("Location: reg4.php");
                }
            }  

            break;

        case 'carica_foto_card':
            if(isset($_FILES['foto'])) {
                foreach($_FILES['foto']['name'] as $chiave => $nome) { // per ogni foto caricata
                    $nomeOg = $_FILES['foto']['name'][$chiave]; // prendo il nome di ogni foto
                    $tmp = $_FILES['foto']['tmp_name'][$chiave]; // prendo la posizione temporanea di ogni foto

                    $nuovoNome = time() . "_" . $nomeOg; // creo un nuovo nome per la foto aggiungendo un timestamp per evitare conflitti di nomi
                    if(move_uploaded_file($tmp, "foto/" . $nuovoNome)) { // se riesco a spostare la foto dalla posizione temporanea a quella definitiva

                        // all'utente con id corrispondente a quello in sessione 
                        $squl = "INSERT INTO foto_card (user_id, nome_foto) VALUES (:user_id, :nome_foto)"; // inserisco nella tabella foto_card una nuova riga con l'id dell'utente e il nome della foto appena caricata
                        $stmt = $pdo->prepare($squl);
                        $stmt->execute([':user_id' => $_SESSION['user_id'], 
                                        ':nome_foto' => $nuovoNome]);
                    }
                }
                header("Location: reg4.php");
            }

            break;
        
        default:
            header("Location: home.php");
            exit();
    }

    // funzioni per caricare foto 
    
?>