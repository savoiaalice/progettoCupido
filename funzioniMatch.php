<?php
require 'connessioneDB.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
function getInteressi($utente, $pdo){
    $interessi=[];
    $sql="SELECT * FROM interessi WHERE id_utente= :utente";
    $stmt=$pdo->prepare($sql);
    $stmt->execute([
        ':utente'=>$utente
    ]);
    $riga=$stmt->fetch(PDO::FETCH_ASSOC);
    if(!$riga){
        return [];
    }
    foreach($riga as $chiave => $valore){
        if($chiave !== 'id_utente' && $valore==1){
            $interessi[]=$chiave;
        }
    }
    return $interessi;
}

function getAggettivi($utente, $pdo){
    $aggettivi=[];
    $sql="SELECT * FROM aggettivi WHERE id_utente= :utente";
    $stmt=$pdo->prepare($sql);
    $stmt->execute([
        ':utente'=>$utente
    ]);
    $riga=$stmt->fetch(PDO::FETCH_ASSOC);
    if(!$riga){
        return [];
    }
    foreach($riga as $chiave => $valore){
        if($chiave !== 'id_utente' && $valore==1){
            $aggettivi[]=$chiave;
        }
    }
    return $aggettivi;
}

function getFoto($utente, $pdo){
    $sql="SELECT * FROM foto_utenti WHERE id_utente= :utente ORDER BY tipo";
    $stmt=$pdo->prepare($sql);
    $stmt->execute([
        ':utente'=>$utente
    ]);
    $riga=$stmt->fetchAll(PDO::FETCH_ASSOC);
    if(!$riga){
        return [];
    }else{
        return $riga;
    }
}

function getInformazioni($utente, $pdo){
    $sql="SELECT * FROM datiregistrazione WHERE id_utente= :utente";
    $stmt=$pdo->prepare($sql);
    $stmt->execute([
        ':utente'=>$utente
    ]);
    $riga=$stmt->fetch(PDO::FETCH_ASSOC);
    if(!$riga){
        return [];
    }else{
        return $riga;
    }
}

function getLikes($id_mit, $id_dest, $pdo){
    $sql="SELECT* FROM likes WHERE id_mit=:id_mit AND id_dest=:id_dest";
    $stmt=$pdo->prepare($sql);
    $stmt->execute([
        ':id_mit'=>$id_mit,
        ':id_dest'=>$id_dest
    ]);
    $riga=$stmt->fetch(PDO::FETCH_ASSOC);
    if($riga){
        return false;
    }else{
        return true;
    }
}

function interessiMatch($interessi1, $interessi2){
    $count=0;
    $len1=count($interessi1);
    $len2=count($interessi2);
    $minlen=0;
    if($len1<=$len2){
        $minlen=$len1;
    }else{
        $minlen=$len2;
    }
    foreach($interessi1 as $elem1){
        foreach ($interessi2 as $elem2){
            if($elem1==$elem2){
                $count++;
            }
        }
    }
    if($count>=$minlen/4){
        return true;
    }else{
        return false;
    }
}

function calcolaMatch($utenteA, $utenteB, $pdo) {
    $interessiA = getInteressi($utenteA, $pdo);
    $interessiB = getInteressi($utenteB, $pdo);

    $aggettiviA = getAggettivi($utenteA, $pdo);
    $aggettiviB = getAggettivi($utenteB, $pdo);

    $rigaA = getInformazioni($utenteA, $pdo);
    $rigaB = getInformazioni($utenteB, $pdo);

    // Se uno dei due utenti non esiste nel DB, esci subito
    if (!$rigaA || !$rigaB) {
        return false;
    }

    $etaA = $rigaA['eta'];
    $etaB = $rigaB['eta'];
    $maxEtaA = $rigaA['maxEta'];
    $maxEtaB = $rigaB['maxEta'];

    $interessi = interessiMatch($interessiA, $interessiB)
        && interessiMatch($aggettiviA, $aggettiviB)
        && ($rigaA['sessoP'] == $rigaB['sesso'] && $rigaA['sesso'] == $rigaB['sessoP'])
        && $rigaA['relazione'] == $rigaB['relazione'];

    $condizioneEtaLike = abs($etaA - $etaB) < $maxEtaA
        && abs($etaA - $etaB) < $maxEtaB
        
        && getLikes($utenteA, $utenteB, $pdo);

    // prendo coordinate dal database
    $latA = $rigaA['latitudine'] ?? null;
    $latB = $rigaB['latitudine'] ?? null;
    $longA = $rigaA['longitudine'] ?? null;
    $longB = $rigaB['longitudine'] ?? null;

    if ($latA == null || $latB == null || $longA == null || $longB == null) {
        return false;
    }

    // Formula di Haversine per le distanze reali in km
    $raggioTerra = 6371;

    $diffLat = deg2rad($latB - $latA);
    $diffLong = deg2rad($longB - $longA);

    $passaggioA = sin($diffLat / 2) * sin($diffLat / 2) +
                  cos(deg2rad($latA)) * cos(deg2rad($latB)) *
                  sin($diffLong / 2) * sin($diffLong / 2);
    $passaggioB = 2 * atan2(sqrt($passaggioA), sqrt(1 - $passaggioA));
    
    // Distanza reale calcolata
    $distanzaReale = $raggioTerra * $passaggioB;

    $tipoRelazione = ($distanzaReale <= 85) ? 0 : 1;

    // compatibilità sulle distanze
    if($tipoRelazione == 1){
        $distanzaCompatibile = ($rigaA['distanza'] == 1 && $rigaB['distanza'] == 1);
    }else{
        $distanzaCompatibile = true;
    }
    

   
    // condizioni match
    if ($interessi && $condizioneEtaLike && $distanzaCompatibile) {
        // Salviamo la distanza numerica reale per poterla mostrare graficamente nella card
        $GLOBALS['distanza_effettiva'] = $distanzaReale;
        return true;
    } else {
        return false;
    }
}
function getDistanza($latA, $longA, $latB, $longB) {
    // Se le coordinate sono vuote, zero o nulle, non calcolare
    if (empty($latA) || empty($longA) || empty($latB) || empty($longB)) {
        return false;
    }

    // Forza la conversione in float per evitare problemi di stringhe in PHP
    $latA = (float)$latA;
    $longA = (float)$longA;
    $latB = (float)$latB;
    $longB = (float)$longB;

    $raggioTerra = 6371; // Raggio della Terra in Km

    $diffLat = deg2rad($latB - $latA);
    $diffLong = deg2rad($longB - $longA);

    $passaggioA = sin($diffLat / 2) * sin($diffLat / 2) +
                  cos(deg2rad($latA)) * cos(deg2rad($latB)) *
                  sin($diffLong / 2) * sin($diffLong / 2);
    $passaggioB = 2 * atan2(sqrt($passaggioA), sqrt(1 - $passaggioA));
    
    return $raggioTerra * $passaggioB;
}
?>