<?php
// controller commercial

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Vehicle.php';

$user = new User($pdo);
$vehicle = new Vehicle($pdo);

// check si commercial
if (!$user->isLoggedIn() || !$user->isCommercial()) {
    header('Location: index.php?page=login');
    exit;
}

$message = '';
$error = '';

// modifier vehicle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_vehicle'])) {
    $id = $_POST['id'] ?? 0;
    $marque = $_POST['marque'] ?? '';
    $modele = $_POST['modele'] ?? '';
    $immatriculation = $_POST['immatriculation'] ?? '';
    $tarif = $_POST['tarif'] ?? 0;
    $kilometrage = $_POST['kilometrage'] ?? 0;
    $statut = $_POST['statut'] ?? 'disponible';
    
    if ($vehicle->update($id, $marque, $modele, $immatriculation, $tarif, $kilometrage, $statut)) {
        header('Location: index.php?page=commercial_vehicles_list&message=update_success');
        exit;
    } else {
        $error = 'Erreur lors de la modification du véhicule';
    }
}

// get les vehicles
$vehicles = $vehicle->getAll();

// les stats
$totalVehicles = $vehicle->countAll();
$disponibles = $vehicle->countByStatus('disponible');
$reserves = $vehicle->countByStatus('reserve');
$enLocation = $vehicle->countByStatus('en_location');
$maintenance = $vehicle->countByStatus('maintenance');

// les messages
if (isset($_GET['message'])) {
    switch ($_GET['message']) {
        case 'add_success':
            $message = 'Véhicule ajouté avec succès';
            break;
        case 'update_success':
            $message = 'Véhicule modifié avec succès';
            break;
        case 'delete_success':
            $message = 'Véhicule supprimé avec succès';
            break;
    }
}


// les messages
if (isset($_GET['message'])) {
    switch ($_GET['message']) {
        case 'update_success':
            $message = 'Véhicule modifié avec succès';
            break;
    }
}
?>