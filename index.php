<?php
// fichier principal

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/models/User.php';

$user = new User($pdo);

// deconnexion en priorite absolue
if (isset($_GET['logout'])) {
    $user->logout();
    header('Location: index.php?page=home');
    exit;
}

// get la page a afficher
$page = $_GET['page'] ?? 'home';

// si deja connecte et sur home/login/register, rediriger vers le bon dashboard
if (in_array($page, ['home', 'login', 'register']) && $user->isLoggedIn()) {
    if ($user->isAdmin()) {
        header('Location: index.php?page=vehicles_list');
    } elseif ($user->isCommercial()) {
        header('Location: index.php?page=commercial');
    } else {
        header('Location: index.php?page=client');
    }
    exit;
}

// check si connecté pour les pages protégées
$pages_publiques = ['home', 'login', 'register'];
if (!in_array($page, $pages_publiques) && !$user->isLoggedIn()) {
    header('Location: index.php?page=home');
    exit;
}

// router
switch ($page) {
    case 'home':
        require_once __DIR__ . '/views/home.php';
        break;

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
    
    // Pages Client
    case 'client':
        if (!$user->isClient()) {
            header('Location: index.php?page=login');
            exit;
        }
        require_once __DIR__ . '/controllers/ClientController.php';
        require_once __DIR__ . '/views/client/vehicle_list.php';
        break;

    case 'register':
        require_once __DIR__ . '/controllers/AuthController.php';
        require_once __DIR__ . '/views/register.php';
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

    case 'commercial_locations':
        if (!$user->isCommercial()) {
            header('Location: index.php?page=login');
            exit;
        }
        require_once __DIR__ . '/controllers/CommercialController.php';
        require_once __DIR__ . '/views/commercial/locations.php';
        break;

    case 'commercial_clients':
        if (!$user->isCommercial()) {
            header('Location: index.php?page=login');
            exit;
        }
        require_once __DIR__ . '/controllers/CommercialController.php';
        require_once __DIR__ . '/views/commercial/clients.php';
        break;

    case 'admin_clients':
        if (!$user->isAdmin()) {
            header('Location: index.php?page=login');
            exit;
        }
        require_once __DIR__ . '/controllers/AdminController.php';
        require_once __DIR__ . '/views/admin/clients.php';
        break;

    case 'admin_locations':
        if (!$user->isAdmin()) {
            header('Location: index.php?page=login');
            exit;
        }
        require_once __DIR__ . '/controllers/AdminController.php';
        require_once __DIR__ . '/views/admin/locations.php';
        break;

    default:
        require_once __DIR__ . '/controllers/AuthController.php';
        require_once __DIR__ . '/views/login.php';
        break;
}
?>
