<?php
// controller commercial

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Vehicle.php';
require_once __DIR__ . '/../models/Location.php';

$user = new User($pdo);
$vehicle = new Vehicle($pdo);
$locationModel = new Location($pdo);

if (!$user->isLoggedIn() || !$user->isCommercial()) {
    header('Location: index.php?page=login');
    exit;
}

$message = '';
$error = '';

// terminer une location en cours
if (isset($_GET['terminate'])) {
    $id_loc = (int)$_GET['terminate'];
    if ($locationModel->terminate($id_loc)) {
        header('Location: index.php?page=commercial_locations&message=terminate_success');
        exit;
    }
    $error = 'Erreur lors de la clôture de la location';
}

// modifier une location
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_location'])) {
    $id_loc    = (int)($_POST['id_location'] ?? 0);
    $date_debut = $_POST['date_debut'] ?? '';
    $date_fin   = $_POST['date_fin'] ?? '';

    if ($id_loc && $date_debut && $date_fin && $date_fin > $date_debut) {
        if ($locationModel->update($id_loc, $date_debut, $date_fin)) {
            header('Location: index.php?page=commercial_locations&message=update_location_success');
            exit;
        }
        $error = 'Erreur lors de la modification';
    } else {
        $error = 'Dates invalides';
    }
}

// annuler une location future
if (isset($_GET['cancel_location'])) {
    $id_loc = (int)$_GET['cancel_location'];
    if ($locationModel->cancel($id_loc)) {
        header('Location: index.php?page=commercial_locations&message=cancel_success');
        exit;
    }
    $error = 'Erreur lors de l\'annulation de la location';
}

// creer une location manuellement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_location'])) {
    $id_user_loc = (int)($_POST['id_user'] ?? 0);
    $id_vehicle_loc = (int)($_POST['id_vehicle'] ?? 0);
    $date_debut = $_POST['date_debut'] ?? '';
    $date_fin = $_POST['date_fin'] ?? '';

    if ($id_user_loc && $id_vehicle_loc && $date_debut && $date_fin && $date_fin > $date_debut) {
        if ($locationModel->create($id_user_loc, $id_vehicle_loc, $date_debut, $date_fin)) {
            $stmt = $pdo->prepare("UPDATE vehicles SET statut = 'en_location' WHERE id = ?");
            $stmt->execute([$id_vehicle_loc]);
            header('Location: index.php?page=commercial_locations&message=create_success');
            exit;
        }
        $error = 'Erreur lors de la création de la location';
    } else {
        $error = 'Données invalides. Vérifiez les dates et les champs obligatoires.';
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
            header('Location: index.php?page=commercial_clients&message=client_created');
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
            header('Location: index.php?page=commercial_clients&message=client_updated');
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
        header('Location: index.php?page=commercial_clients&message=client_deleted');
        exit;
    } else {
        $error = 'Erreur lors de la suppression du client';
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
        header('Location: index.php?page=commercial_vehicles_list&message=update_success');
        exit;
    }
    $error = 'Erreur lors de la modification du véhicule';
}

// donnees vehicules (pages vehicules)
$vehicles = $vehicle->getAll();
$totalVehicles = $vehicle->countAll();
$disponibles = $vehicle->countByStatus('disponible');
$reserves = $vehicle->countByStatus('reserve');
$enLocation = $vehicle->countByStatus('en_location');
$maintenance = $vehicle->countByStatus('maintenance');

// donnees locations
$activeLocations = [];
$historyLocations = [];
$availableVehicles = [];
$clients = [];

if ($page === 'commercial_locations') {
    $activeLocations = $locationModel->getActive();
    $historyLocations = $locationModel->getHistory();
    $availableVehicles = $vehicle->getAvailable();
    $clients = $user->getClients();
}

$editClient = null;
if ($page === 'commercial_clients') {
    $clients = $user->getClients();
    if (isset($_GET['edit_client'])) {
        $editClient = $user->getClientById((int)$_GET['edit_client']);
    }
}

// messages
if (isset($_GET['message'])) {
    $msgs = [
        'update_success'   => 'Véhicule modifié avec succès',
        'create_success'   => 'Location créée avec succès',
        'terminate_success' => 'Location clôturée, véhicule remis en disponible',
        'cancel_success'         => 'Location annulée, véhicule remis en disponible',
        'update_location_success' => 'Location modifiée avec succès',
        'client_created'   => 'Client créé avec succès',
        'client_updated'   => 'Client modifié avec succès',
        'client_deleted'   => 'Client supprimé avec succès',
    ];
    $message = $msgs[$_GET['message']] ?? '';
}
?>
