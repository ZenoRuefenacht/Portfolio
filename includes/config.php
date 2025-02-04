<?php
$host = '127.0.0.1:3306';
$dbname = 'u970985206_Portfolio';
$username = 'u970985206_portfolio';

$password = 'Fallout2007&';

try {
    $pdo = new PDO( "mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password );
    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
} catch ( PDOException $e ) {
    die( 'Fehler bei der Verbindung zur Datenbank: ' . $e->getMessage() );
}
?>
