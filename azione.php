<?php
require 'connessioneDB.php';
session_start();

if(!isset($_SESSION['id_utente'])){
    die("ERRORE: utente npn loggato");
}

$id_mit=$_SESSION['id_utente'];
$id_dest=$_GET['id'];
$azione=$_GET['azione'];

if(!($id_dest || $azione) ){
    die("ERRORE: paramentri mancanti");
}

if($azione==='like'){
    //controllo se l'altro utente ha già messo like a me
    $sql="SELECT* FROM likes WHERE id_mit=:id_dest AND id_dest=:id_mit AND stato='like'";
    $stmt=$pdo->prepare($sql);
    $stmt->execute([
        ':id_mit'=>$id_mit,
        ':id_dest'=>$id_dest
    ]);
    $match=$stmt->fetch(PDO::FETCH_ASSOC);
    if($match){
        $sql1="INSERT INTO likes(id_mit, id_dest, stato)
        VALUES
        :id_mit, :id_dest, 'match'";
        $stmt=$pdo->prepare($sql1);
        $stmt->execute([
            ':id_mit'=>$id_mit,
            ':id_dest'=>$id_dest
        ]);
        //notifico il match ad entrambi
        $sql="INSERT INTO notifica(id_dest, tipo, id_mit, testo)
        VALUES
        (:id_dest, 'match', :id_mit, 'hai fatto match!!')";
        $stmt=$pdo->prepare($sql);
        $stmt->execute([
            ':id_mit'=>$id_mit,
            ':id_dest'=>$id_dest
        ]);
        $stmt->execute([
            ':id_mit'=>$id_dest,
            ':id_dest'=>$id_mit
        ]);
        //aggiorno anche l'altro
        $sql="UPDATE likes
        SET stato='match'
        WHERE id_mit=:id_mit AND id_dest=:id_dest";
        $stmt=$pdo->prepare($sql);
        $stmt->execute([
            ':id_mit'=>$id_mit,
            ':id_dest'=>$id_dest
        ]);
    }else{
        //metto un semplice like
        $sql="INSERT INTO likes(id_mit, id_dest, stato)
        VALUES
        (:id_mit, :id_dest, 'like')";
        $stmt=$pdo->prepare($sql);
        $stmt->execute([
            ':id_mit'=>$id_mit,
            ':id_dest'=>$id_dest
        ]);
        //notifico il match
        $sql="INSERT INTO notifica(id_dest, tipo, id_mit, testo)
        VALUES
        (:id_dest, 'like', :id_mit, 'Hai ricevuto un like!!!')";
        $stmt=$pdo->prepare($sql);
        $stmt->execute([
            ':id_mit'=>$id_mit,
            ':id_dest'=>$id_dest
        ]);
    }
    header("Location: match.php");
    exit;
}else if($azione=='skip'){
    //metto che l'ho skippato in modo tale da non farmelo comparire più
        $sql="INSERT INTO likes(id_mit, id_dest, stato)
        VALUES
        (:id_mit, :id_dest, 'skipped')";
        $stmt=$pdo->prepare($sql);
        $stmt->execute([
            ':id_mit'=>$id_mit,
            ':id_dest'=>$id_dest
        ]);
    header("Location: match.php");
    exit;
}
?>