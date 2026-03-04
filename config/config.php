<?php
// config file

session_start();
header('Content-Type: text/html; charset=utf-8');

// chemin de base
define('BASE_PATH', dirname(__DIR__));

// url de base
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$script = dirname($_SERVER['SCRIPT_NAME']);
define('BASE_URL', $protocol . '://' . $host . $script);

// load la db
require_once BASE_PATH . '/config/database.php';
?>
