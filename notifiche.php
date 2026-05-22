<?php
    require __DIR__ . "/connessioneDB.php";
    session_start();

    $id_utente=$_SESSION['id_utente'];
    //like ricevuti:

    $sql="SELECT tipo, COUNT(*) as totale FROM notifica WHERE id_dest=:id_dest AND letto=0 GROUP BY tipo";
    $stmt=$pdo->prepare($sql);
    $stmt->execute([
        ':id_dest'=>$id_utente
    ]);

    $righe = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $likes=0;
    $messaggi=0;
    $match=0;

    foreach($righe as $r){
        if ($r['tipo'] === 'like') $likes = $r['totale'];
        if ($r['tipo'] === 'match') $match = $r['totale'];
        if ($r['tipo'] === 'messaggio') $messaggi = $r['totale'];
    }
    echo json_encode([
        "like" => $likes,
        "match" => $match,
        "messaggi" => $messaggi
    ]);
    ?>