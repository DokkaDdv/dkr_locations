<?php
// config db

$host = 'localhost';
$dbname = 'dkr_locations';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // forcer utf8mb4
    $pdo->exec("SET NAMES utf8mb4");
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
