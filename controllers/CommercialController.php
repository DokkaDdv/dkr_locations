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

// terminer une location
if (isset($_GET['terminate'])) {
    $id_loc = (int)$_GET['terminate'];
    if ($locationModel->terminate($id_loc)) {
        header('Location: index.php?page=commercial_locations&message=terminate_success');
        exit;
    }
    $error = 'Erreur lors de la clôture de la location';
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

if ($page === 'commercial_clients') {
    $clients = $user->getClients();
}

// messages
if (isset($_GET['message'])) {
    $msgs = [
        'update_success'   => 'Véhicule modifié avec succès',
        'create_success'   => 'Location créée avec succès',
        'terminate_success'=> 'Location clôturée, véhicule remis en disponible',
        'client_created'   => 'Client créé avec succès',
    ];
    $message = $msgs[$_GET['message']] ?? '';
}
?>
