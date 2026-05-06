<?php 
// controller client

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Vehicle.php';

$user = new User($pdo);
$vehicle = new Vehicle($pdo);

// check si client
if (!user->isLoggedIn() || $user->isAdmin()) {
    header('Location: index.php?page=Login');
    exit;
}

$message = '';
$error = '';

