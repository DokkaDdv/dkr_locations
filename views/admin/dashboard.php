<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - DKR Locations</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <div class="header">
        <h1>DKR Locations - Administration</h1>
        <div class="user-info">
            <span>Connecté en tant que : <?php echo htmlspecialchars($_SESSION['user_email']); ?> (Admin)</span>
            <a href="index.php?logout=1" class="btn btn-secondary">Déconnexion</a>
        </div>
    </div>
    
    <div class="container">
        <!-- Navigation -->
        <div class="nav-menu">
            <a href="index.php?page=admin" class="btn btn-primary">Tableau de bord</a>
            <a href="index.php?page=vehicles_list" class="btn btn-primary">Liste des véhicules</a>
            <a href="index.php?page=vehicle_add" class="btn btn-primary">Ajouter un véhicule</a>
        </div>
        
        <!-- Statistiques -->
        <div class="stats">
            <div class="stat-card">
                <h3>Total véhicules</h3>
                <p class="stat-number"><?php echo $totalVehicles; ?></p>
            </div>
            <div class="stat-card">
                <h3>Disponible</h3>
                <p class="stat-number"><?php echo $disponibles; ?></p>
            </div>
            <div class="stat-card">
                <h3>Réservé</h3>
                <p class="stat-number"><?php echo $reserves; ?></p>
            </div>
            <div class="stat-card">
                <h3>En location</h3>
                <p class="stat-number"><?php echo $enLocation; ?></p>
            </div>
            <div class="stat-card">
                <h3>En maintenance</h3>
                <p class="stat-number"><?php echo $maintenance; ?></p>
            </div>
        </div>
        
        <div class="card">
            <h2>Bienvenue dans le tableau de bord</h2>
            <p>Utilisez le menu ci-dessus pour naviguer dans l'application.</p>
            <ul>
                <li><strong>Liste des véhicules</strong> : Consulter tous les véhicules de la flotte</li>
                <li><strong>Ajouter un véhicule</strong> : Ajouter un nouveau véhicule à la flotte</li>
            </ul>
        </div>
    </div>
</body>
</html>
