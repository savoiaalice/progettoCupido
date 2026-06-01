<?php
require 'connessioneDB.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
function formattaNome($stringa){
    if(empty($stringa)){
        return '';
    }
    $stringa = trim($stringa);
    $stringa = mb_convert_case($stringa, MB_CASE_TITLE, "utf-8");

    return $stringa;
}

$azione = $_POST['azione'] ?? '';

switch ($azione) {

    case 'registrazione_interessi':
        $id_utente=$_SESSION['id_utente'];

        $sql="UPDATE interessi SET
        sport= :sport,
        cucinare= :cucinare,
        viaggiare= :viaggiare,
        leggere= :leggere,
        film= :film,
        suonare= :suonare,
        camping= :camping,
        casa= :casa,
        meditazione= :meditazione,
        cena= :cena
        WHERE id_utente= :id_utente";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
        ':sport' => isset($_POST['sport']) ? 1 : 0,
        ':cucinare' => isset($_POST['cucinare']) ? 1 : 0,
        ':viaggiare' => isset($_POST['viaggiare']) ? 1 : 0,
        ':leggere' => isset($_POST['leggere']) ? 1 : 0,
        ':film' => isset($_POST['film']) ? 1 : 0,
        ':suonare' => isset($_POST['suonare']) ? 1 : 0,
        ':camping' => isset($_POST['camping']) ? 1 : 0,
        ':casa' => isset($_POST['casa']) ? 1 : 0,
        ':meditazione' => isset($_POST['meditazione']) ? 1 : 0,
        ':cena' => isset($_POST['cena']) ? 1 : 0,
        ':id_utente' => $id_utente
        ]);
    
    // controllo da dove sto arrivando usando provenienza, tag input hide in profio
    $provenienza = $_POST['provenienza'] ?? 'registrazione';

    // se vengo dalla pagina del profilo e lo sto modificando, quando finisco la modifica resto in profilo
    if($provenienza === 'profilo'){
        header("Location: profilo.php");
    } else {
//se sto modificando i dati perchè mi sto registrando, allora continuo con la registrazione nella prossima oagina
        header("Location: reg4.php");
    }
    exit();
    break;

    case 'registrazione_aggettivi':
        $id_utente=$_SESSION['id_utente'];

        $sql2 = "UPDATE aggettivi SET 
        solare = :solare,
        riflessivo = :riflessivo,
        spontaneo = :spontaneo,
        determinato = :determinato,
        curioso = :curioso,
        sognatore = :sognatore,
        empatico = :empatico,
        ironico = :ironico,
        colto = :colto,
        leale = :leale,
        tranquillo = :tranquillo,
        socievole = :socievole,
        premuroso = :premuroso,
        timido = :timido,
        avventuroso = :avventuroso
        WHERE id_utente = :id_utente";

        $stmt2 = $pdo->prepare($sql2);
        $stmt2->execute([
            ':solare' => isset($_POST['solare']) ? 1 : 0,
            ':riflessivo' => isset($_POST['riflessivo']) ? 1 : 0,
            ':spontaneo' => isset($_POST['spontaneo']) ? 1 : 0,
            ':determinato' => isset($_POST['determinato']) ? 1 : 0,
            ':curioso' => isset($_POST['curioso']) ? 1 : 0,
            ':sognatore' => isset($_POST['sognatore']) ? 1 : 0,
            ':empatico' => isset($_POST['empatico']) ? 1 : 0,
            ':ironico' => isset($_POST['ironico']) ? 1 : 0,
            ':colto' => isset($_POST['colto']) ? 1 : 0,
            ':leale' => isset($_POST['leale']) ? 1 : 0,
            ':tranquillo' => isset($_POST['tranquillo']) ? 1 : 0,
            ':socievole' => isset($_POST['socievole']) ? 1 : 0,
            ':premuroso' => isset($_POST['premuroso']) ? 1 : 0,
            ':timido' => isset($_POST['timido']) ? 1 : 0,
            ':avventuroso' => isset($_POST['avventuroso']) ? 1 : 0,
            ':id_utente' => $id_utente
        ]);
    // controllo da dove sto arrivando usando provenienza, tag input hide in profio
    $provenienza = $_POST['provenienza'] ?? 'registrazione';

    // se vengo dalla pagina del profilo e lo sto modificando, quando finisco la modifica resto in profilo
    if($provenienza === 'profilo'){
        header("Location: profilo.php");
    } else {
//se sto modificando i dati perchè mi sto registrando, allora continuo con la registrazione nella prossima pagina
        header("Location: reg4.php");
    }
    exit();
    break;

    case 'carica_foto_profilo':
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $nome = $_FILES['foto']['name'];
            $tmp = $_FILES['foto']['tmp_name'];
            $percorso = "foto/" . $nome;

            if (move_uploaded_file($tmp, $percorso)) {
                $id_utente = $_SESSION['id_utente'];

                //controllo se è presente foto profilo, dal form reg4 è required
                $sqlControllo = "SELECT * FROM foto_utenti WHERE id_utente = :id_utente AND tipo = 'profilo'LIMIT 1";
                $stmControllo = $pdo->prepare($sqlControllo);
                $stmControllo->execute([':id_utente' => $id_utente]);
                $fotoEsistente = $stmControllo->fetch();

                //dopo che verifico l'esistenza della foto anche nella base di dati la modifico tramite aggiornamneto
                if($fotoEsistente){

                $sql = "UPDATE foto_utenti SET percorso = :percorso WHERE id_utente = :id_utente AND tipo = 'profilo'";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([    
                    ':id_utente' => $id_utente,
                    ':percorso' => $percorso
                ]);
                }else{
                    //se non dovvesse essefci la foto profilo ne permetto l'inserimento per la proma volta
                    $sql = "INSERT INTO foto_utenti (id_utente, percorso, tipo) 
                    VALUES (:id_utente, :percorso, 'profilo')";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':id_utente' => $id_utente,
                        ':percorso' => $percorso
                    ]);
                }                
            }
        }
        header("Location: profilo.php");
        exit();
        break;

    case 'carica_nuova_foto':
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM foto_utenti 
                        WHERE id_utente = :id_utente AND tipo = 'galleria'");
        $stmt->execute([':id_utente' => $_SESSION['id_utente']]);
        $fotoCaricate = $stmt->fetchColumn();

        if($fotoCaricate + count($_FILES['foto']['name'])> 6){
           
            header("Location: profilo.php");
            exit();
        }


        if (isset($_FILES['foto']) && !empty($_FILES['foto']['name'][0])) {
            foreach ($_FILES['foto']['name'] as $chiave => $nomeOg) {
                if($_FILES['foto']['error'][$chiave] == 0){
                    $tmp = $_FILES['foto']['tmp_name'][$chiave];
                    $nuovoNome = time() . "_" . $nomeOg;
                    $percorso = "foto/" . $nuovoNome;   
               
                    
                    if (move_uploaded_file($tmp, $percorso)) {
                        $sql = "INSERT INTO foto_utenti (id_utente, percorso, tipo) 
                                VALUES (:id_utente, :percorso, 'galleria')";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([
                            ':id_utente' => $_SESSION['id_utente'],
                            ':percorso' => $percorso
                        ]);
                    }
                }
            }
        }
       
        header("Location: profilo.php");
        exit();
        break;

    case 'modifica_dati':
        $id_utente = $_SESSION['id_utente'];
        $nome      = formattaNome($_POST['nome'] ?? null);
        $cognome   = formattaNome($_POST['cognome'] ?? null); 
        $citta     = formattaNome($_POST['citta'] ?? null);
        $eta       = $_POST['eta'] ?? null;
        $email     = $_POST['email'] ?? null;
        $sesso     = $_POST['sesso'] ?? null;
        $sessoP    =$_POST['sessoP'] ?? null;
        $distanza  =isset($_POST['distanza']) ? 1 : 0;
        $maxEta   =$_POST['maxEta'] ?? null;
        $relazione =$_POST['relazione'] ?? null;
        $latitudine = $_POST['latitudine'] ?? null;
        $longitudine = $_POST['longitudine'] ?? null;
    
        salvaDati($pdo, $id_utente, $nome, $cognome, $citta, $eta, $email, 
        $sesso, $sessoP, $distanza,  $maxEta, $relazione, $latitudine, $longitudine, true);
        header("Location: profilo.php");
        exit();
        break;

    case 'elimina_foto':
        $id_utente = $_SESSION['id_utente'];
        if(isset($_POST['id_foto']) && !empty($_POST['id_foto'])){
            $id_foto = intval($_POST['id_foto']);

            // recupero percorso per poter eliminarlo
            $sqlPercorso = "SELECT percorso FROM foto_utenti
                WHERE id_foto=:id_foto AND id_utente = :id_utente AND tipo = 'galleria'";
            $stmtPercorso = $pdo->prepare($sqlPercorso);
            $stmtPercorso->execute([
                ':id_foto'=> $id_foto,
                ':id_utente'=> $id_utente
            ]);
            $foto = $stmtPercorso->fetch();

            if($foto){
                $percorsoFoto = $foto['percorso'];

                $sqlElimina = "DELETE FROM foto_utenti
                        WHERE id_foto = :id_foto AND id_utente = :id_utente";
                $stmtElimina= $pdo->prepare($sqlElimina);
                $stmtElimina->execute([
                    ':id_foto' => $id_foto,
                    ':id_utente' => $id_utente

                ]);
                if(file_exists($percorsoFoto) && is_file($percorsoFoto)){
                    unlink($percorsoFoto);
                }
            }
        }
        header("Location: profilo.php");
        exit();
        break;

        case 'cambia_password':
            $id_utente = $_SESSION['id_utente'];
            $vecchiaPassword = $_POST['password'];
            $nuovaPassword = $_POST['nuovaPassword'];
            $confermaPassword = $_POST['confermaPassword'];

            $regexPassword = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
            if(!preg_match($regexPassword, $nuovaPassword)){
                echo "<script>alert('La password deve contenere almeno:\\n- 8 caratteri \\n- una lettera maiuscola \\n- una minuscola \\n- un numero \\n- un carattere speciale!'); window.history.back();</script>";
                exit();
            }

            if($nuovaPassword !== $confermaPassword){
                echo "<script>alert('Le due password non coincidono'); window.history.back();</script>";
                exit();
            }

            $stmt = $pdo->prepare("SELECT password FROM datiregistrazione
                        WHERE id_utente = :id_utente");
            $stmt->execute([]);

            if(!password_verify($password, $vecchiaPassword)){
                echo "<script>alert('Vecchia password sbagliata!'); window.history.back();</script>";
                exit();
            }

            $passwordCifrata = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("UPDATE datiregistrazione SET password = :password
                                WHERE id_utente = :id_utente");
            $stmt->execute([$passwordCifrata, $id_utente]);
            echo "<script>alert('Password aggiornata con successo!'); window.location.href='profilo.php';</script>";
            header("Location: profilo.php");
            exit();
            break;

            
    

    default:
        header("Location: reg3.php");
        exit();
}

function salvaDati($pdo, $id_utente, $nome, $cognome, $citta, $eta, $email, 
        $sesso, $sessoP, $distanza,  $maxEta, $relazione, $latitudine, $longitudine, $modifica_dati = true) {
    
    if($modifica_dati){
        $sql1 = "UPDATE datiregistrazione SET
        nome= :nome,
        cognome= :cognome,
        citta= :citta,
        eta= :eta,
        email= :email,
        sesso= :sesso,
        sessoP= :sessoP,
        distanza= :distanza,
        maxEta= :maxEta,
        relazione= :relazione
        WHERE id_utente = :id_utente";
    
    } else{
        $sql1 = "INSERT INTO datiregistrazione (id_utente,
        nome, cognome, citta, eta, email, sesso, distanza,
        maxEta, relazione)
        VALUES (:id_utente,
        :nome, :cognome, :citta, :eta, :email, :sesso,:distanza,
        :maxEta,  :relazione)";
    }
        $stmt1 = [
            ':id_utente' => $id_utente,
            ':nome' => $nome,
            ':cognome' => $cognome,
            ':citta' => $citta,
            ':eta' => $eta,
            ':email' => $email,
            ':sesso' => $sesso,
            ':sessoP' => $sessoP,
            ':distanza' => $distanza,
            ':maxEta' => $maxEta,
            ':relazione' => $relazione
            
        ];
    
    $stmt = $pdo->prepare($sql1);
    return $stmt ->execute($stmt1);
}
?>