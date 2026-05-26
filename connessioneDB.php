    <?php
// Indirizzo di riferimento: http://localhost/phpmyadmin/index.php?route=/database/structure&db=cupido

$host = "localhost";
$port = "3306";
$db   = "cupido";
$user = "root";   /* user di xampp è root e non ha una password */
$pass = "";
$dsn  = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4"; /* charset per permettere uso di accenti ed emoji */

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false, // Disabilita l'emulazione per una maggiore sicurezza delle query
    ]);
} catch (PDOException $e) {
    // In produzione è meglio non mostrare l'errore dettagliato, ma in locale è fondamentale per il debugging
    die("Errore di connessione al database: " . $e->getMessage());
}
?>