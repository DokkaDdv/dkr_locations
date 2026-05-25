<?php
// controller admin

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Vehicle.php';

$user = new User($pdo);
$vehicle = new Vehicle($pdo);

// check si admin
if (!$user->isLoggedIn() || !$user->isAdmin()) {
    header('Location: index.php?page=login');
    exit;
}

$message = '';
$error = '';

// ajouter vehicle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_vehicle'])) {
    $marque = $_POST['marque'] ?? '';
    $modele = $_POST['modele'] ?? '';
    $immatriculation = $_POST['immatriculation'] ?? '';
    $tarif = $_POST['tarif'] ?? 0;
    $statut = $_POST['statut'] ?? 'disponible';

    if ($vehicle->add($marque, $modele, $immatriculation, $tarif, $statut)) {
        header('Location: index.php?page=vehicles_list&message=add_success');
        exit;
    } else {
        $error = 'Erreur lors de l\'ajout du véhicule';
    }
}

// modifier vehicle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_vehicle'])) {
    $id = $_POST['id'] ?? 0;
    $marque = $_POST['marque'] ?? '';
    $modele = $_POST['modele'] ?? '';
    $immatriculation = $_POST['immatriculation'] ?? '';
    $tarif = $_POST['tarif'] ?? 0;
    $statut = $_POST['statut'] ?? 'disponible';

    if ($vehicle->update($id, $marque, $modele, $immatriculation, $tarif, $statut)) {
        header('Location: index.php?page=vehicles_list&message=update_success');
        exit;
    } else {
        $error = 'Erreur lors de la modification du véhicule';
    }
}

// supprimer vehicle
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if ($vehicle->delete($id)) {
        header('Location: index.php?page=vehicles_list&message=delete_success');
        exit;
    } else {
        $error = 'Erreur lors de la suppression du véhicule';
    }
}

// get les vehicles
$vehicles = $vehicle->getAll();

// liste clients
$clients = [];
if ($page === 'admin_clients') {
    $clients = $user->getClients();
}

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
?>
