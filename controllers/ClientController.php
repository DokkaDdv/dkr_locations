<?php
// controller client

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Vehicle.php';
require_once __DIR__ . '/../models/Location.php';

$user = new User($pdo);
$vehicleModel = new Vehicle($pdo);
$locationModel = new Location($pdo);

if (!$user->isLoggedIn() || !$user->isClient()) {
    header('Location: index.php?page=login');
    exit;
}

$section = $_GET['section'] ?? 'louer';
$message = '';
$error   = '';
$vehicle_detail = null;
$date_debut = $_GET['date_debut'] ?? date('Y-m-d');

// modifier mot de passe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm      = $_POST['confirm_password'] ?? '';

    if (!$old_password || !$new_password || !$confirm) {
        $error = 'Tous les champs sont obligatoires';
    } elseif ($new_password !== $confirm) {
        $error = 'Les nouveaux mots de passe ne correspondent pas';
    } elseif (strlen($new_password) < 6) {
        $error = 'Le mot de passe doit faire au moins 6 caractères';
    } elseif ($user->updatePassword($_SESSION['user_id'], $old_password, $new_password)) {
        header('Location: index.php?page=client&section=compte&success=password_changed');
        exit;
    } else {
        $error = 'Mot de passe actuel incorrect';
        $section = 'compte';
    }
}

// creer une location
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['louer'])) {
    $vehicle_id     = (int)($_POST['vehicle_id'] ?? 0);
    $date_debut_post = $_POST['date_debut'] ?? '';
    $date_fin       = $_POST['date_fin'] ?? '';

    if ($vehicle_id && $date_debut_post && $date_fin && $date_fin > $date_debut_post) {
        if ($locationModel->create($_SESSION['user_id'], $vehicle_id, $date_debut_post, $date_fin)) {
            $stmt = $pdo->prepare("UPDATE vehicles SET statut = 'en_location' WHERE id = ?");
            $stmt->execute([$vehicle_id]);
            header('Location: index.php?page=client&section=mes_locations&success=location_created');
            exit;
        }
        $error = 'Erreur lors de la création de la location';
    } else {
        $error = 'Les dates sont invalides';
    }
}

// success messages
if (isset($_GET['success'])) {
    $msgs = [
        'location_created' => 'Votre location a été créée avec succès !',
        'password_changed' => 'Mot de passe modifié avec succès !',
    ];
    $message = $msgs[$_GET['success']] ?? '';
}

// detail vehicule pour le formulaire de location
if ($section === 'louer' && isset($_GET['vehicle_id'])) {
    $vehicle_detail = $vehicleModel->getById((int)$_GET['vehicle_id']);
    if (!$vehicle_detail) {
        $error = 'Véhicule non trouvé';
    }
}

// donnees par section
$vehicles  = [];
$locations = [];

if ($section === 'louer') {
    $vehicles = $vehicleModel->getAvailableByDate($date_debut);
}

if ($section === 'mes_locations') {
    $locations = $locationModel->getByUserId($_SESSION['user_id']);
}
?>
