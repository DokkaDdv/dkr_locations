<?php
// config db

$host = 'db.eaqsguvphrgangmbwcdr.supabase.co';
$port = '5432';
$dbname = 'postgres';
$username = 'postgres';
$password = '899ber8GiD9XAe';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
