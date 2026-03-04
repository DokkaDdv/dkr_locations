<?php
// controller auth

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/User.php';

$user = new User($pdo);
$error = '';

// login traitement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($user->login($email, $password)) {
        // redirection selon role
        if ($user->isAdmin()) {
            header('Location: index.php?page=vehicles_list');
        } else {
            header('Location: index.php?page=commercial');
        }
        exit;
    } else {
        $error = 'Email ou mot de passe incorrect';
    }
}

// deconnexion
if (isset($_GET['logout'])) {
    $user->logout();
    header('Location: index.php?page=login');
    exit;
}
?>
