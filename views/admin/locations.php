<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Locations - DKR Locations</title>
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
            <a href="index.php?page=vehicles_list" class="btn btn-secondary">Véhicules</a>
            <a href="index.php?page=admin_locations" class="btn btn-primary">Locations</a>
            <a href="index.php?page=admin_clients" class="btn btn-secondary">Clients</a>
        </div>

        <div class="stats">
            <div class="stat-card">
                <h3>En cours</h3>
                <p class="stat-number"><?php echo count($activeLocations); ?></p>
            </div>
            <div class="stat-card">
                <h3>Historique</h3>
                <p class="stat-number"><?php echo count($historyLocations); ?></p>
            </div>
        </div>

        <!-- Locations en cours -->
        <div class="card">
            <h2>Locations en cours</h2>
            <?php if (empty($activeLocations)): ?>
                <p style="text-align: center; color: #b0b0b0; padding: 30px 0;">Aucune location en cours.</p>
            <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Véhicule</th>
                        <th>Immatriculation</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Montant estimé</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activeLocations as $loc): ?>
                    <?php
                        $jours = (strtotime($loc['date_fin']) - strtotime($loc['date_debut'])) / 86400 + 1;
                        $montant = $loc['tarif'] * max(1, $jours);
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars(trim(($loc['prenom'] ?? '') . ' ' . ($loc['nom'] ?? '')) ?: $loc['email']); ?></td>
                        <td><?php echo htmlspecialchars($loc['marque'] . ' ' . $loc['modele']); ?></td>
                        <td><?php echo htmlspecialchars($loc['immatriculation']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($loc['date_debut'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($loc['date_fin'])); ?></td>
                        <td><strong style="color: #00d4ff;"><?php echo number_format($montant, 2); ?> €</strong></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

        <!-- Historique -->
        <div class="card">
            <h2>Historique des locations</h2>
            <?php if (empty($historyLocations)): ?>
                <p style="text-align: center; color: #b0b0b0; padding: 30px 0;">Aucune location terminée.</p>
            <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Véhicule</th>
                        <th>Immatriculation</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Montant estimé</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historyLocations as $loc): ?>
                    <?php
                        $jours = (strtotime($loc['date_fin']) - strtotime($loc['date_debut'])) / 86400 + 1;
                        $montant = $loc['tarif'] * max(1, $jours);
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars(trim(($loc['prenom'] ?? '') . ' ' . ($loc['nom'] ?? '')) ?: $loc['email']); ?></td>
                        <td><?php echo htmlspecialchars($loc['marque'] . ' ' . $loc['modele']); ?></td>
                        <td><?php echo htmlspecialchars($loc['immatriculation']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($loc['date_debut'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($loc['date_fin'])); ?></td>
                        <td><strong style="color: #00d4ff;"><?php echo number_format($montant, 2); ?> €</strong></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
