<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Liste des véhicules - DKR Locations</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css?v=2">
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
        <div class="nav-menu">
            <a href="index.php?page=vehicles_list" class="btn btn-primary">Véhicules</a>
            <a href="index.php?page=admin_locations" class="btn btn-secondary">Locations</a>
            <a href="index.php?page=admin_clients" class="btn btn-secondary">Clients</a>
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
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <!-- Liste des véhicules -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Liste des véhicules</h2>
                <a href="index.php?page=vehicle_add" class="btn btn-primary">+ Ajouter un véhicule</a>
            </div>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>Marque</th>
                        <th>Modèle</th>
                        <th>Immatriculation</th>
                        <th>Tarif</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($vehicles)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center;">Aucun véhicule enregistré</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($vehicles as $v): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($v['marque']); ?></td>
                                <td><?php echo htmlspecialchars($v['modele']); ?></td>
                                <td><?php echo htmlspecialchars($v['immatriculation']); ?></td>
                                <td><?php echo number_format($v['tarif'], 2); ?> €</td>
                                <td>
                                    <span class="badge badge-<?php echo $v['statut']; ?>">
                                        <?php echo ucfirst($v['statut']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="index.php?page=vehicle_edit&id=<?php echo $v['id']; ?>" class="btn btn-small btn-warning">Modifier</a>
                                    <a href="index.php?page=vehicles_list&delete=<?php echo $v['id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce véhicule ?')">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
