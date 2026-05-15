<?php
require 'connessioneDB.php';
session_start();

$azione = $_POST['azione'] ?? '';

switch ($azione) {

    case 'accesso':
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $id_utente= $_POST['id_utente'] ?? '';

        $sql = "SELECT * FROM datiregistrazione 
                WHERE email = :email 
                AND password = :password
                AND id_utente= :id_utente";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':password' => $password,
            ':id_utente' => $id_utente
        ]);

        $utente = $stmt->fetch();

        if ($utente) {
            $_SESSION['id_utente'] = $utente['id_utente'];
            header("Location: profilo.php");
            exit();
        } else {
            echo "<script>alert('Email o password errati'); window.location.href='index.php';</script>";
            exit();
        }
        var_dump($_POST);
    exit();
        break;

    case 'registrazione1':
        $id_utente = $_POST['id_utente'] ?? null;
        $email     = $_POST['email'] ?? null;
        $password  = $_POST['password'] ?? null; 
        $nome      = $_POST['nome'] ?? null;
        $cognome   = $_POST['cognome'] ?? null;
        $eta       = $_POST['eta'] ?? null;
        $citta     = $_POST['citta'] ?? null;

        if (
            empty($nome)|| empty($cognome) ||
            empty($email) || empty($password) || empty($eta) || empty($citta) || empty($id_utente)
        ) {
            
            header("Location: home.php");
            exit();
        }
        try {
            $pdo->beginTransaction();

            $sql1 = "INSERT INTO datiregistrazione 
                    (id_utente, email, password, nome, cognome, sesso, eta, citta, maxEta, distanza, sessoP, relazione) 
                     VALUES 
                    (:id_utente, :email, :pass, :nome, :cognome, NULL, :eta, :citta, NULL, NULL, NULL, NULL)";
            $stmt1 = $pdo->prepare($sql1);
            $stmt1->execute([
                ':id_utente' => $id_utente,
                ':email' => $email,
                ':pass' => $password,
                ':nome' => $nome,
                ':cognome' => $cognome,
                ':eta' => $eta,
                ':citta' => $citta,
            ]);
            //inserisco già l'id_utente nelle altre tabelle per poi verificare che sia lo stesso ed apportare delle modifiche//
            $sql2 = "INSERT INTO interessi (id_utente) VALUES (:id_utente)";
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->execute([':id_utente' => $id_utente]);

            $sql3 = "INSERT INTO aggettivi (id_utente) VALUES (:id_utente)";
            $stmt3 = $pdo->prepare($sql3);
            $stmt3->execute([':id_utente' => $id_utente]);


            $pdo->commit();
            $_SESSION['id_utente'] = $id_utente; //sta prendendo ora l'id e lo sta associando alla tabella//
            header("Location: reg1.php");
            exit();
        }catch (Exception $e) {
            $pdo->rollBack();
            echo "Errore: " . $e->getMessage();}
    
        break;

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
        // Aggettivi
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

    header("Location: reg3.php");
    exit();
        break;

    case 'registrazione2':
        $id_utente = $_SESSION['id_utente'];
        $sesso     = $_POST['sesso'] ?? null;
        $sessoP    =$_POST['sessoP'] ?? null;
        $relazione =$_POST['relazione'] ?? null;
        $maxEta   =$_POST['maxEta'] ?? null;
        $distanza  =$_POST['distanza'] ?? null;
        if (
            empty($sesso)|| empty($sessoP) ||
            empty($relazione) || empty($maxEta) || empty($distanza)
        ) {
            
            header("Location: reg1.php");
            exit();
        }
        try {
            $pdo->beginTransaction();

            $sql1 = "UPDATE datiregistrazione SET 
                    sesso= :sesso,
                    sessoP= :sessoP,
                    relazione= :relazione,
                    maxEta= :maxEta,
                    distanza= :distanza
                    WHERE id_utente = :id_utente";
            $stmt1 = $pdo->prepare($sql1);
            $stmt1->execute([
                ':sesso' => $sesso,
                ':maxEta' => $maxEta,
                ':distanza' => $distanza,
                ':sessoP' => $sessoP,
                ':relazione' => $relazione,
                ':id_utente' => $id_utente
            ]);


            $pdo->commit();
            header("Location: reg4.php");
            exit();
        }catch (Exception $e) {
            $pdo->rollBack();
            echo "Errore: " . $e->getMessage();}
    
        break;

    case 'carica_foto_profilo':
        if (isset($_FILES['foto'])) {
            $nome = $_FILES['foto']['name'];
            $tmp = $_FILES['foto']['tmp_name'];
            $percorso = "foto/" . $nome;

            if (move_uploaded_file($tmp, $percorso)) {
                $sql = "INSERT INTO foto_utenti (id_utente, percorso, tipo) 
                        VALUES (:id_utente, :percorso, 'profilo')";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':id_utente' => $_SESSION['id_utente'],
                    ':percorso' => $percorso
                ]);

                header("Location: reg4.php");
                exit();
            }
        }
        break;

    case 'carica_foto_card':
        if (isset($_FILES['foto'])) {
            foreach ($_FILES['foto']['name'] as $chiave => $nomeOg) {
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
            header("Location: reg4.php");
            exit();
        }
        break;

    default:
        header("Location: home.php");
        exit();
}
