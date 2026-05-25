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
        if ($user->isAdmin()) {
            header('Location: index.php?page=vehicles_list');
        } elseif ($user->isClient()) {
            header('Location: index.php?page=client');
        } else {
            header('Location: index.php?page=commercial');
        }
        exit;
    } else {
        $error = 'Email ou mot de passe incorrect';
    }
}

// inscription client
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $telephone = $_POST['telephone'] ?? '';

    if ($email && $password && $nom && $prenom) {
        if ($user->register($email, $password, 'client', $nom, $prenom, $telephone)) {
            $user->login($email, $password);
            header('Location: index.php?page=client');
            exit;
        } else {
            $error = 'Cet email est déjà utilisé';
        }
    } else {
        $error = 'Veuillez remplir tous les champs obligatoires';
    }
}

// deconnexion
if (isset($_GET['logout'])) {
    $user->logout();
    header('Location: index.php?page=login');
    exit;
}
?>
