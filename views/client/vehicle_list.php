<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Espace Client - DKR Locations</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css?v=2">
</head>
<body>
    <div class="header">
        <h1>DKR Locations - Espace Client</h1>
        <div class="user-info">
            <span>Bonjour, <?php echo htmlspecialchars($_SESSION['user_email']); ?></span>
            <a href="index.php?logout=1" class="btn btn-secondary">Déconnexion</a>
        </div>
    </div>

    <div class="container">

        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Recherche par date de debut -->
        <div class="card">
            <h2>Rechercher un véhicule</h2>
            <form method="GET" action="index.php">
                <input type="hidden" name="page" value="client">
                <div class="form-row">
                    <div class="form-group">
                        <label>Date de début souhaitée :</label>
                        <input type="date" name="date_debut" value="<?php echo htmlspecialchars($date_debut); ?>" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="form-group" style="display: flex; align-items: flex-end;">
                        <button type="submit" class="btn btn-primary">Rechercher</button>
                    </div>
                </div>
            </form>
        </div>

        <?php if ($vehicle_detail): ?>
        <!-- Formulaire de location pour le vehicule selectionne -->
        <div class="card" style="border-color: #00d4ff;">
            <h2>Louer : <?php echo htmlspecialchars($vehicle_detail['marque'] . ' ' . $vehicle_detail['modele']); ?></h2>
            <p style="color: #b0b0b0; margin-bottom: 20px;">
                Immatriculation : <strong><?php echo htmlspecialchars($vehicle_detail['immatriculation']); ?></strong>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                Tarif : <strong style="color: #00d4ff;"><?php echo number_format($vehicle_detail['tarif'], 2); ?> €/jour</strong>
            </p>
            <form method="POST" action="index.php?page=client">
                <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_detail['id']; ?>">
                <input type="hidden" name="date_debut" value="<?php echo htmlspecialchars($date_debut); ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label>Date de début :</label>
                        <input type="date" value="<?php echo htmlspecialchars($date_debut); ?>" disabled style="opacity: 0.6;">
                    </div>
                    <div class="form-group">
                        <label>Date de fin :</label>
                        <input type="date" name="date_fin" min="<?php echo date('Y-m-d', strtotime($date_debut . ' +1 day')); ?>" required>
                    </div>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" name="louer" class="btn btn-primary">Confirmer la location</button>
                    <a href="index.php?page=client&date_debut=<?php echo urlencode($date_debut); ?>" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <!-- Liste des vehicules disponibles -->
        <div class="card">
            <h2>Véhicules disponibles à partir du <?php echo date('d/m/Y', strtotime($date_debut)); ?></h2>
            <?php if (empty($vehicles)): ?>
                <p style="text-align: center; color: #b0b0b0; padding: 30px 0;">Aucun véhicule disponible pour cette date.</p>
            <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Marque</th>
                        <th>Modèle</th>
                        <th>Immatriculation</th>
                        <th>Tarif / jour</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vehicles as $v): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($v['marque']); ?></td>
                        <td><?php echo htmlspecialchars($v['modele']); ?></td>
                        <td><?php echo htmlspecialchars($v['immatriculation']); ?></td>
                        <td><strong style="color: #00d4ff;"><?php echo number_format($v['tarif'], 2); ?> €</strong></td>
                        <td>
                            <a href="index.php?page=client&vehicle_id=<?php echo $v['id']; ?>&date_debut=<?php echo urlencode($date_debut); ?>" class="btn btn-small btn-primary">Louer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

        <!-- Mes locations -->
        <div class="card">
            <h2>Mes locations</h2>
            <?php if (empty($locations)): ?>
                <p style="text-align: center; color: #b0b0b0; padding: 30px 0;">Vous n'avez aucune location enregistrée.</p>
            <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Véhicule</th>
                        <th>Immatriculation</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Montant total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($locations as $loc): ?>
                    <tr>
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
