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
    
    // Pages Admin
    case 'vehicles_list':
        if (!$user->isAdmin()) {
            header('Location: index.php?page=login');
            exit;
        }
        require_once __DIR__ . '/controllers/AdminController.php';
        require_once __DIR__ . '/views/admin/vehicles_list.php';
        break;
        
    case 'vehicle_add':
        if (!$user->isAdmin()) {
            header('Location: index.php?page=login');
            exit;
        }
        require_once __DIR__ . '/controllers/AdminController.php';
        require_once __DIR__ . '/views/admin/vehicle_add.php';
        break;
        
    case 'vehicle_edit':
        if (!$user->isAdmin()) {
            header('Location: index.php?page=login');
            exit;
        }
        require_once __DIR__ . '/controllers/AdminController.php';
        require_once __DIR__ . '/views/admin/vehicle_edit.php';
        break;
    
    // Pages Commercial
    case 'commercial':
        if (!$user->isCommercial()) {
            header('Location: index.php?page=login');
            exit;
        }
        require_once __DIR__ . '/controllers/CommercialController.php';
        require_once __DIR__ . '/views/commercial/vehicles_list.php';
        break;
        
    case 'commercial_vehicles_list':
        if (!$user->isCommercial()) {
            header('Location: index.php?page=login');
            exit;
        }
        require_once __DIR__ . '/controllers/CommercialController.php';
        require_once __DIR__ . '/views/commercial/vehicles_list.php';
        break;
        
    case 'commercial_vehicle_edit':
        if (!$user->isCommercial()) {
            header('Location: index.php?page=login');
            exit;
        }
        require_once __DIR__ . '/controllers/CommercialController.php';
        require_once __DIR__ . '/views/commercial/vehicle_edit.php';
        break;
        
    default:
        require_once __DIR__ . '/controllers/AuthController.php';
        require_once __DIR__ . '/views/login.php';
        break;
}
?>
