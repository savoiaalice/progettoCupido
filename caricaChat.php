<?php
session_start();
require 'connessioneDB.php';

$id_utente=$_SESSION['id_utente'];
$id_altro=$_GET['id'];

//seleziono tutti i messaggi che vengono inviati e ricevuti dall'utente
$sql="SELECT * FROM messaggi WHERE (id_mit=:id_mit AND id_dest=:id_dest) OR (id_mit=:id_dest AND id_dest=:id_mit) ORDER BY data";
$stmt=$pdo->prepare($sql);
$stmt->execute([
    ':id_mit'=>$id_utente,
    ':id_dest'=>$id_altro
]);
$messaggi=$stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($messaggi as $messaggio){
    $classe = ($messaggio['id_mit'] == $id_utente) ? "mio" : "suo";
    echo "<div class='$classe'>" . htmlspecialchars($messaggio['testo']) . "</div>";
}
?>
