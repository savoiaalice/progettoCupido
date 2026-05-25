<?php
require 'connessioneDB.php';
session_start();
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

    $rigaA=getInformazioni($utenteA, $pdo);
    $rigaB=getInformazioni($utenteB, $pdo);
    $etaA=$rigaA['eta'];
    $etaB=$rigaB['eta'];
    $maxEtaA=$rigaA['maxEta'];
    $maxEtaB=$rigaB['maxEta'];

    if(interessiMatch($interessiA, $interessiB) && interessiMatch($aggettiviA, $aggettiviB) && ($rigaA['sessoP']==$rigaB['sesso'] && $rigaA['sesso']==$rigaB['sessoP']) && $rigaA['relazione']==$rigaB['relazione'] && (($rigaA['distanza']!==null && $rigaB['distanza']!==null) || ($rigaA['distanza']===null && $rigaB['distanza']===null && $rigaA['citta']==$rigaB['citta'])) && abs($etaA-$etaB)<$maxEtaA && abs($etaA-$etaB)<$maxEtaB && getLikes($utenteA, $utenteB, $pdo)){
        return true;
    }else{
        return false;
    }

}

?>