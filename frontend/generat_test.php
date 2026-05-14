<?php
// 1. Connessione al database
$host = "127.0.0.1";
$user = "root";
$password = "";
$db = "cupido";

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// 2. Dati per la generazione casuale
$nomi = ["Marco", "Sofia", "Luca", "Giulia", "Alessandro", "Elena", "Francesco", "Chiara"];
$cognomi = ["Rossi", "Bianchi", "Verdi", "Rizzo", "Ferrari", "Esposito", "Gallo"];
$citta = ["Roma", "Milano", "Napoli", "Torino", "Bologna", "Firenze"];
$sessi = ["M", "F"];

// 3. Quanti utenti vuoi creare?
$quanti = 10; 

echo "Inizio generazione di $quanti utenti...<br>";

for ($i = 0; $i < $quanti; $i++) {
    // Genera dati casuali
    $id = "user_" . uniqid(); // Crea un ID unico tipo user_645f...
    $nome = $nomi[array_rand($nomi)];
    $cognome = $cognomi[array_rand($cognomi)];
    $email = strtolower($nome . "." . $cognome . rand(1, 999) . "@test.it");
    $sesso = $sessi[array_rand($sessi)];
    $eta = rand(18, 50);
    $citta_scelta = $citta[array_rand($citta)];
    
    // --- INSERT 1: Dati Registrazione ---
    $sql_utente = "INSERT INTO datiregistrazione (id_utente, email, password, nome, cognome, sesso, eta, citta, maxEta, distanza) 
                   VALUES ('$id', '$email', 'password123', '$nome', '$cognome', '$sesso', $eta, '$citta_scelta', 99, 1)";
    
    if ($conn->query($sql_utente) === TRUE) {
        
        // --- INSERT 2: Interessi (casuali 0 o 1) ---
        $sql_interessi = "INSERT INTO interessi (id_utente, sport, cucinare, viaggiare, leggere, film, suonare, camping, casa, meditazione, cena) 
                          VALUES ('$id', ".rand(0,1).", ".rand(0,1).", ".rand(0,1).", ".rand(0,1).", ".rand(0,1).", ".rand(0,1).", ".rand(0,1).", ".rand(0,1).", ".rand(0,1).", ".rand(0,1).")";
        $conn->query($sql_interessi);

        // --- INSERT 3: Aggettivi (casuali 0 o 1) ---
        $sql_aggettivi = "INSERT INTO aggettivi (id_utente, solare, intraprendente, riflessivo, spontaneo, determinato, curioso, sognatore, empatico, ironico, colto, leale, tranquillo, socievole, premuroso, timido, avventuroso) 
                          VALUES ('$id', ".rand(0,1).", ".rand(0,1).", ".rand(0,1).", ".rand(0,1).", ".rand(0,1).", ".rand(0,1).", ".rand(0,1).", ".rand(0,1).", ".rand(0,1).", ".rand(0,1).", ".rand(0,1).", ".rand(0,1).", ".rand(0,1).", ".rand(0,1).", ".rand(0,1).", ".rand(0,1).")";
        $conn->query($sql_aggettivi);

        echo "Creato utente: $nome $cognome ($id)<br>";
    } else {
        echo "Errore per $nome: " . $conn->error . "<br>";
    }
}

$conn->close();
echo "<strong>Generazione completata!</strong>";
?>