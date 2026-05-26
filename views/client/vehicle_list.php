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
        <h1>DKR Locations</h1>
        <div class="user-info">
            <?php
                $nomAffiche = trim(($userInfo['prenom'] ?? '') . ' ' . ($userInfo['nom'] ?? ''));
                $nomAffiche = $nomAffiche ?: $_SESSION['user_email'];
            ?>
            <span>Bonjour, <?php echo htmlspecialchars($nomAffiche); ?></span>
            <a href="index.php?logout=1" class="btn btn-secondary">Déconnexion</a>
        </div>
    </div>

    <div class="container">

        <!-- Navigation -->
        <div class="nav-menu">
            <a href="index.php?page=client&section=louer"
               class="btn <?php echo $section === 'louer' ? 'btn-primary' : 'btn-secondary'; ?>">
                Louer un véhicule
            </a>
            <a href="index.php?page=client&section=mes_locations"
               class="btn <?php echo $section === 'mes_locations' ? 'btn-primary' : 'btn-secondary'; ?>">
                Mes locations
            </a>
            <a href="index.php?page=client&section=compte"
               class="btn <?php echo $section === 'compte' ? 'btn-primary' : 'btn-secondary'; ?>">
                Mon compte
            </a>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($section === 'louer'): ?>
        <!-- ===================== LOUER UN VEHICULE ===================== -->

        <div class="card">
            <h2>Rechercher un véhicule disponible</h2>
            <form method="GET" action="index.php">
                <input type="hidden" name="page" value="client">
                <input type="hidden" name="section" value="louer">
                <div class="form-row">
                    <div class="form-group">
                        <label>Date de début souhaitée :</label>
                        <input type="date" name="date_debut"
                               value="<?php echo htmlspecialchars($date_debut); ?>"
                               min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="form-group" style="display: flex; align-items: flex-end;">
                        <button type="submit" class="btn btn-primary">Rechercher</button>
                    </div>
                </div>
            </form>
        </div>

        <?php if ($vehicle_detail): ?>
        <div class="card" style="border-color: #00d4ff;">
            <h2>Louer : <?php echo htmlspecialchars($vehicle_detail['marque'] . ' ' . $vehicle_detail['modele']); ?></h2>
            <p style="color: #b0b0b0; margin-bottom: 20px;">
                Immatriculation : <strong><?php echo htmlspecialchars($vehicle_detail['immatriculation']); ?></strong>
                &nbsp;|&nbsp;
                Tarif : <strong style="color: #00d4ff;"><?php echo number_format($vehicle_detail['tarif'], 2); ?> €/jour</strong>
            </p>
            <form method="POST" action="index.php?page=client&section=louer">
                <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_detail['id']; ?>">
                <input type="hidden" name="date_debut" value="<?php echo htmlspecialchars($date_debut); ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label>Date de début :</label>
                        <input type="date" value="<?php echo htmlspecialchars($date_debut); ?>" disabled style="opacity: 0.6;">
                    </div>
                    <div class="form-group">
                        <label>Date de fin :</label>
                        <input type="date" name="date_fin"
                               min="<?php echo date('Y-m-d', strtotime($date_debut . ' +1 day')); ?>" required>
                    </div>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" name="louer" class="btn btn-primary">Confirmer la location</button>
                    <a href="index.php?page=client&section=louer&date_debut=<?php echo urlencode($date_debut); ?>" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
        <?php endif; ?>

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
                            <a href="index.php?page=client&section=louer&vehicle_id=<?php echo $v['id']; ?>&date_debut=<?php echo urlencode($date_debut); ?>"
                               class="btn btn-small btn-primary">Louer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

        <?php elseif ($section === 'mes_locations'): ?>
        <!-- ===================== MES LOCATIONS ===================== -->

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
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($locations as $loc): ?>
                    <?php
                        $jours = (strtotime($loc['date_fin']) - strtotime($loc['date_debut'])) / 86400 + 1;
                        $enCours = strtotime($loc['date_fin']) >= strtotime('today');
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($loc['marque'] . ' ' . $loc['modele']); ?></td>
                        <td><?php echo htmlspecialchars($loc['immatriculation']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($loc['date_debut'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($loc['date_fin'])); ?></td>
                        <td><?php echo number_format($loc['tarif'] * max(1, $jours), 2); ?> €</td>
                        <td>
                            <?php if ($enCours): ?>
                                <span class="badge badge-en_location">En cours</span>
                            <?php else: ?>
                                <span class="badge badge-disponible">Terminée</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>

        <?php elseif ($section === 'compte'): ?>
        <!-- ===================== MON COMPTE ===================== -->

        <div class="card" style="max-width: 500px;">
            <h2>Modifier mon mot de passe</h2>
            <form method="POST" action="index.php?page=client&section=compte">
                <div class="form-group">
                    <label>Mot de passe actuel :</label>
                    <input type="password" name="old_password" required>
                </div>
                <div class="form-group">
                    <label>Nouveau mot de passe :</label>
                    <input type="password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label>Confirmer le nouveau mot de passe :</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <button type="submit" name="change_password" class="btn btn-primary">Modifier le mot de passe</button>
            </form>
        </div>

        <?php endif; ?>

    </div>
</body>
</html>
