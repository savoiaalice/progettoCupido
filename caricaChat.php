<?php
session_start();
require 'connessioneDB.php';

if (!isset($_SESSION['id_utente']) || !isset($_GET['id'])) {
    exit; // Chiude silenziosamente se mancano i dati
}

$id_utente = $_SESSION['id_utente'];
$id_altro = $_GET['id'];
//recupero la foto profilo del mittente
$sql_foto="SELECT percorso
            FROM foto_utenti
            WHERE id_utente=:id_utente AND tipo='profilo'";
$stmt_foto=$pdo->prepare($sql_foto);
$stmt_foto->execute([
    ':id_utente'=>$id_altro
]);
$dati=$stmt_foto->fetch(PDO::FETCH_ASSOC);
//se l'utente non ha foto profilo seleziono una standard
$foto_profilo=$dati['percorso'];
//segno come letti i messaggi che l'altro ha inviato a me
$sql="UPDATE messaggi
        SET letto=1
        WHERE id_mit=:id_mit AND id_dest=:id_dest and letto=0";
$stmt=$pdo->prepare($sql);
$stmt->execute([
    ':id_mit'=>$id_altro,
    ':id_dest'=>$id_utente
]);
//Ovviamente se leggo il messaggio anche la notifica del messaggio deve apparire letta
$sql_not="UPDATE notifica
            SET letto=1
            WHERE id_mit=:id_mit AND id_dest=:id_dest AND tipo='messaggio' AND letto=0" ;
$stmt_not=$pdo->prepare($sql_not);
$stmt_not->execute([
    ':id_mit'=>$id_altro,
    ':id_dest'=>$id_utente
]);

//stampo la foto profilo
echo "<input type='hidden' id='url-foto-rilevata' value='".htmlspecialchars($foto_profilo)."'>";

// Seleziono tutti i messaggi scambiati tra i due utenti
$sql = "SELECT * FROM messaggi 
        WHERE (id_mit = ? AND id_dest = ?) 
           OR (id_mit = ? AND id_dest = ?) 
        ORDER BY data ASC";

$stmt = $pdo->prepare($sql);

// Passiamo le variabili nell'ordine esatto in cui compaiono i punti interrogativi
$stmt->execute([
    $id_utente, // Primo ?: id_mit = io
    $id_altro,  // Secondo ?: id_dest = altro
    $id_altro,  // Terzo ?: id_mit = altro
    $id_utente  // Quarto ?: id_dest = io
]);

$messaggi = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($messaggi as $messaggio) {
    //vedo se il messaggio è mio o dell'altro utente
    if($messaggio['id_mit']==$id_utente){
        $classe="mio";
        //metto la spunta sui messaggi letti
        if($messaggio['letto']==1){
            $spunta="<i class='bi bi-check2-all text-info' style='font-size: 0.95rem; margin-left: 5px;'></i>";
        }else{
            $spunta="<i class='bi bi-check2' style='color: rgba(255,255,255,0.7); font-size: 0.95rem; margin-left: 5px;'></i>";
        }
    }else{
        $classe="suo";
        $spunta="";
    }
    //stampiamo il messaggio con la spunta integrata
    echo "<div class='$classe'>";
    echo "<span>" . htmlspecialchars($messaggio['testo']) . "</span>";
    if (!empty($spunta)) {
        echo " " . $spunta;
    }
    echo "</div>";
}
?>