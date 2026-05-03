<?php
// config file

session_start();
header('Content-Type: text/html; charset=utf-8');

// chemin de base
define('BASE_PATH', dirname(__DIR__));

// url de base
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$script = dirname($_SERVER['SCRIPT_NAME']);
define('BASE_URL', $protocol . '://' . $host . ($script === '/' ? '' : $script));

// load la db
require_once BASE_PATH . '/config/database.php';
?>
