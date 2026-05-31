<?php
require 'connessioneDB.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['id_utente'])) {
    die("ERRORE: utente non loggato");
}

// Recupero e pulisco i parametri
$id_mit = trim($_SESSION['id_utente']);
$id_dest = isset($_GET['id']) ? trim($_GET['id']) : null;
$azione = isset($_GET['azione']) ? trim($_GET['azione']) : null;

if (!$id_dest || !$azione) {
    die("ERRORE: parametri mancanti");
}

if($azione === 'like'){

    //Controllo se l'altro utente ha già messo like a me
    $sql = "SELECT * FROM likes 
            WHERE id_mit = :altro AND id_dest = :me AND stato = 'like'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':altro' => $id_dest,
        ':me' => $id_mit
    ]);

    $isLike = $stmt->fetch(PDO::FETCH_ASSOC);

    if($isLike){

        //Creo match per me
        $sql = "INSERT INTO likes(id_mit, id_dest, stato)
                VALUES (:me, :altro, 'match')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':me' => $id_mit,
            ':altro' => $id_dest
        ]);

        //Aggiorno il like dell'altro a match
        $sql = "UPDATE likes SET stato='match'
                WHERE id_mit = :altro AND id_dest = :me";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':altro' => $id_dest,
            ':me' => $id_mit
        ]);

        //Notifica match per me
        $sql = "INSERT INTO notifica(id_dest, tipo, id_mit, letto, testo)
                VALUES (:me, 'match', :altro, 0, 'hai fatto match!!')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':me' => $id_mit,
            ':altro' => $id_dest
        ]);

        //Notifica match per lui
        $sql = "INSERT INTO notifica(id_dest, tipo, id_mit, letto, testo)
                VALUES (:altro, 'match', :me, 0, 'hai fatto match!!')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':altro' => $id_dest,
            ':me' => $id_mit
        ]);

    } else {

        //Inserisco un semplice like
        $sql = "INSERT INTO likes(id_mit, id_dest, stato)
                VALUES (:me, :altro, 'like')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':me' => $id_mit,
            ':altro' => $id_dest
        ]);

        //Notifica like
        $sql = "INSERT INTO notifica(id_dest, tipo, id_mit, letto, testo)
                VALUES (:altro, 'like', :me, 0, 'Hai ricevuto un like!!!')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':altro' => $id_dest,
            ':me' => $id_mit
        ]);
    }

    header("Location: match.php");
    exit;

} else if($azione === 'skip'){

    $sql = "INSERT INTO likes(id_mit, id_dest, stato)
            VALUES (:me, :altro, 'skipped')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':me' => $id_mit,
        ':altro' => $id_dest
    ]);

    header("Location: match.php");
    exit;
}
?>