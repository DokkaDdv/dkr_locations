<?php
// controller admin

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Vehicle.php';
require_once __DIR__ . '/../models/Location.php';

$user = new User($pdo);
$vehicle = new Vehicle($pdo);
$locationModel = new Location($pdo);

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

// creer un client
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_client'])) {
    $email     = $_POST['email'] ?? '';
    $password  = $_POST['password'] ?? '';
    $nom       = $_POST['nom'] ?? '';
    $prenom    = $_POST['prenom'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    if ($email && $password && $nom && $prenom) {
        if ($user->register($email, $password, 'client', $nom, $prenom, $telephone)) {
            header('Location: index.php?page=admin_clients&message=client_created');
            exit;
        }
        $error = 'Cet email est déjà utilisé';
    } else {
        $error = 'Nom, prénom, email et mot de passe sont obligatoires';
    }
}

// modifier un client
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_client'])) {
    $id        = (int)($_POST['client_id'] ?? 0);
    $nom       = $_POST['nom'] ?? '';
    $prenom    = $_POST['prenom'] ?? '';
    $email     = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    if ($id && $nom && $prenom && $email) {
        if ($user->updateClient($id, $nom, $prenom, $email, $telephone)) {
            header('Location: index.php?page=admin_clients&message=client_updated');
            exit;
        }
        $error = 'Erreur lors de la modification du client';
    } else {
        $error = 'Nom, prénom et email sont obligatoires';
    }
}

// supprimer un client
if (isset($_GET['delete_client'])) {
    $id = (int)$_GET['delete_client'];
    if ($locationModel->countByUserId($id) > 0) {
        $error = 'Impossible de supprimer ce client : il a des locations en base.';
    } elseif ($user->deleteClient($id)) {
        header('Location: index.php?page=admin_clients&message=client_deleted');
        exit;
    } else {
        $error = 'Erreur lors de la suppression du client';
    }
}

// get les vehicles
$vehicles = $vehicle->getAll();

$clients = [];
$editClient = null;
if ($page === 'admin_clients') {
    $clients = $user->getClients();
    if (isset($_GET['edit_client'])) {
        $editClient = $user->getClientById((int)$_GET['edit_client']);
    }
}

// locations (page admin_locations)
$activeLocations = [];
$historyLocations = [];
if ($page === 'admin_locations') {
    $activeLocations = $locationModel->getActive();
    $historyLocations = $locationModel->getHistory();
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
        case 'client_created': $message = 'Client créé avec succès'; break;
        case 'client_updated': $message = 'Client modifié avec succès'; break;
        case 'client_deleted': $message = 'Client supprimé avec succès'; break;
    }
}
?>
