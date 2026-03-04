<?php
// fichier principal

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/models/User.php';

$user = new User($pdo);

// get la page a afficher
$page = $_GET['page'] ?? 'login';

// check si connecté
if ($page !== 'login' && !$user->isLoggedIn()) {
    $page = 'login';
}

// router
switch ($page) {
    case 'login':
        require_once __DIR__ . '/controllers/AuthController.php';
        require_once __DIR__ . '/views/login.php';
        break;
        
    case 'vehicles_list':
        require_once __DIR__ . '/controllers/AdminController.php';
        require_once __DIR__ . '/views/admin/vehicles_list.php';
        break;
        
    case 'vehicle_add':
        require_once __DIR__ . '/controllers/AdminController.php';
        require_once __DIR__ . '/views/admin/vehicle_add.php';
        break;
        
    case 'vehicle_edit':
        require_once __DIR__ . '/controllers/AdminController.php';
        require_once __DIR__ . '/views/admin/vehicle_edit.php';
        break;
        
    default:
        require_once __DIR__ . '/controllers/AuthController.php';
        require_once __DIR__ . '/views/login.php';
        break;
}
?>
