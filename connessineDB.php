<!-- http://localhost/phpmyadmin/index.php?route=/database/structure&db=cupido-->
    <?php
        $host = "localhost";
        $port = "3306";
        $db = "cupido";
        $user = "root";   /*user di xampp è root e non ha una password*/
        $pass = "";
        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";/*aggiungo il charset per permettere uso di accenti ed emoji*/

        try {
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            /* questo PDO manda messaggio se non avviene connessione alla base di dati
            quindi blocca tutto, se la pagina viene caricata, la connessione è andata a buon fine */
            die("Connessione fallita: " . $e->getMessage());
        }

    ?>    