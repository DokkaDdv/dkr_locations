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

$message = '';
$error = '';
$vehicle_detail = null;

$date_debut = $_GET['date_debut'] ?? date('Y-m-d');

// creer une location
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['louer'])) {
    $vehicle_id = (int)($_POST['vehicle_id'] ?? 0);
    $date_debut_post = $_POST['date_debut'] ?? '';
    $date_fin = $_POST['date_fin'] ?? '';

    if ($vehicle_id && $date_debut_post && $date_fin && $date_fin > $date_debut_post) {
        if ($locationModel->create($_SESSION['user_id'], $vehicle_id, $date_debut_post, $date_fin)) {
            $stmt = $pdo->prepare("UPDATE vehicles SET statut = 'en_location' WHERE id = ?");
            $stmt->execute([$vehicle_id]);
            header('Location: index.php?page=client&success=1');
            exit;
        } else {
            $error = 'Erreur lors de la création de la location';
        }
    } else {
        $error = 'Les dates sont invalides. La date de fin doit être après la date de début.';
    }
}

// detail vehicule pour le formulaire de location
if (isset($_GET['vehicle_id'])) {
    $vehicle_detail = $vehicleModel->getById((int)$_GET['vehicle_id']);
    if (!$vehicle_detail) {
        $error = 'Véhicule non trouvé';
    }
}

if (isset($_GET['success'])) {
    $message = 'Votre location a été créée avec succès !';
}

// vehicules disponibles pour la date demandee
$vehicles = $vehicleModel->getAvailableByDate($date_debut);

// historique locations du client
$locations = $locationModel->getByUserId($_SESSION['user_id']);
?>
