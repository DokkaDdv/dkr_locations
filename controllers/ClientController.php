<?php 
// controller client

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Vehicle.php';

$user = new User($pdo);
$vehicle = new Vehicle($pdo);

// check si client
if (!$user->isLoggedIn()) {
    header('Location: index.php?page=login');
    exit;
}

// Vérifier que l'utilisateur n'est pas admin
if ($user->isAdmin()) {
    header('Location: index.php?page=vehicles_list');
    exit;
}

$message = '';
$error = '';
$vehicles = [];
$reservations = [];

//afficher les vehicules disponibles 
if ($user->isClient()) {
    $vehicles = $vehicle->getAvailable();
}

// details vehicule
if (isset($_GET['vehicle_id'])) {
    $vehicle_id = $_GET['vehicle_id'];
    $vehicle_details = $vehicle->getById($vehicle_id);
    if (!$vehicle_details) {
        $error = 'Véhicule non trouvé';
    }   else {
        $vehicle = $vehicle_details;
    }   
}
// creer une reservation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_reservation'])) {
    require_once __DIR__ . '/../models/Reservation.php';
    $reservation = new Reservation($pdo);
    $vehicle_id = $_POST['vehicule_id'] ?? 0;
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
}

// liste des reservations d'un client
if ($user->isClient()) {
    require_once __DIR__ . '/../models/Reservation.php';
    $reservation = new Reservation($pdo);
    $reservations = $reservation->getByUserID($_SESSION['user_id']);
}

// details reservation
if (isset($_GET['reservation_id'])) {
    require_once __DIR__ . '/../models/Reservation.php';
    $reservation = new Reservation($pdo);
    $reservation_id = $_GET['reservation_id'];
    $reservation_details = $reservation->getByID($reservation_id);
    if (!$reservation_details) {
        $error = 'Réservation non trouvée';
    } else {
        $reservation = $reservation_details;
    }
}

// annuler reservation
if (isset($_GET['cancel_reservation'])) {
    require_once __DIR__ . '/../models/Reservation.php';
    $reservation = new Reservation($pdo);
    $reservation_id = $_GET['cancel_reservation'];
    if ($reservation->cancel($reservation_id)) {
        header('Location: index.php?page=client_dashboard&message=cancel_success');
        exit;
    } else {
        $error = 'Erreur lors de l\'annulation de la réservation';
    }
}

