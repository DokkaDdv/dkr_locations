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
        <h1>DKR Locations - Espace Commercial</h1>
        <div class="user-info">
            <span>Connecté en tant que : <?php echo htmlspecialchars($_SESSION['user_email']); ?> (Commercial)</span>
            <a href="index.php?logout=1" class="btn btn-secondary">Déconnexion</a>
        </div>
    </div>

    <div class="container">
        <div class="nav-menu">
            <a href="index.php?page=commercial_vehicles_list" class="btn btn-secondary">Véhicules</a>
            <a href="index.php?page=commercial_locations" class="btn btn-primary">Locations</a>
            <a href="index.php?page=commercial_clients" class="btn btn-secondary">Clients</a>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Stats -->
        <div class="stats">
            <div class="stat-card">
                <h3>En cours</h3>
                <p class="stat-number"><?php echo count($activeLocations); ?></p>
            </div>
            <div class="stat-card">
                <h3>Historique</h3>
                <p class="stat-number"><?php echo count($historyLocations); ?></p>
            </div>
            <div class="stat-card">
                <h3>Véhicules dispo</h3>
                <p class="stat-number"><?php echo count($availableVehicles); ?></p>
            </div>
            <div class="stat-card">
                <h3>Clients</h3>
                <p class="stat-number"><?php echo count($clients); ?></p>
            </div>
        </div>

        <!-- Creer une location -->
        <div class="card">
            <h2>Créer une location</h2>
            <form method="POST" action="index.php?page=commercial_locations">
                <div class="form-row">
                    <div class="form-group">
                        <label>Client :</label>
                        <select name="id_user" required>
                            <option value="">-- Choisir un client --</option>
                            <?php foreach ($clients as $c): ?>
                                <option value="<?php echo $c['id']; ?>">
                                    <?php
                                        $nom = trim(($c['prenom'] ?? '') . ' ' . ($c['nom'] ?? ''));
                                        echo htmlspecialchars($nom ? $nom . ' — ' . $c['email'] : $c['email']);
                                    ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Véhicule disponible :</label>
                        <select name="id_vehicle" required>
                            <option value="">-- Choisir un véhicule --</option>
                            <?php foreach ($availableVehicles as $v): ?>
                                <option value="<?php echo $v['id']; ?>">
                                    <?php echo htmlspecialchars($v['marque'] . ' ' . $v['modele'] . ' — ' . $v['immatriculation'] . ' (' . number_format($v['tarif'], 2) . ' €/j)'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Date de début :</label>
                        <input type="date" name="date_debut" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Date de fin :</label>
                        <input type="date" name="date_fin" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                    </div>
                </div>
                <button type="submit" name="create_location" class="btn btn-primary">Créer la location</button>
            </form>
        </div>

        <!-- Locations en cours -->
        <div class="card">
            <h2>Locations en cours (<?php echo count($activeLocations); ?>)</h2>
            <?php if (empty($activeLocations)): ?>
                <p style="text-align: center; color: #b0b0b0; padding: 20px 0;">Aucune location en cours.</p>
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activeLocations as $loc): ?>
                    <tr>
                        <td>
                            <?php
                                $nom = trim(($loc['prenom'] ?? '') . ' ' . ($loc['nom'] ?? ''));
                                echo htmlspecialchars($nom ?: $loc['email']);
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($loc['marque'] . ' ' . $loc['modele']); ?></td>
                        <td><?php echo htmlspecialchars($loc['immatriculation']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($loc['date_debut'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($loc['date_fin'])); ?></td>
                        <td>
                            <?php
                                $jours = (strtotime($loc['date_fin']) - strtotime($loc['date_debut'])) / 86400 + 1;
                                echo number_format($loc['tarif'] * max(1, $jours), 2) . ' €';
                            ?>
                        </td>
                        <td>
                            <a href="index.php?page=commercial_locations&terminate=<?php echo $loc['id_location']; ?>"
                               class="btn btn-small btn-danger"
                               onclick="return confirm('Clôturer cette location et remettre le véhicule en disponible ?')">
                                Terminer
                            </a>
                        </td>
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
                <p style="text-align: center; color: #b0b0b0; padding: 20px 0;">Aucune location dans l'historique.</p>
            <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Véhicule</th>
                        <th>Immatriculation</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Montant total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historyLocations as $loc): ?>
                    <tr>
                        <td>
                            <?php
                                $nom = trim(($loc['prenom'] ?? '') . ' ' . ($loc['nom'] ?? ''));
                                echo htmlspecialchars($nom ?: $loc['email']);
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($loc['marque'] . ' ' . $loc['modele']); ?></td>
                        <td><?php echo htmlspecialchars($loc['immatriculation']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($loc['date_debut'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($loc['date_fin'])); ?></td>
                        <td>
                            <?php
                                $jours = (strtotime($loc['date_fin']) - strtotime($loc['date_debut'])) / 86400 + 1;
                                echo number_format($loc['tarif'] * max(1, $jours), 2) . ' €';
                            ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

    </div>
</body>
</html>
