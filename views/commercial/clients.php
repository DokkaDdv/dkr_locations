<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Clients - DKR Locations</title>
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
            <a href="index.php?page=commercial_locations" class="btn btn-secondary">Locations</a>
            <a href="index.php?page=commercial_clients" class="btn btn-primary">Clients</a>
        </div>

        <!-- Creer un client -->
        <div class="card">
            <h2>Créer un client</h2>
            <form method="POST" action="index.php?page=commercial_clients">
                <div class="form-row">
                    <div class="form-group">
                        <label>Prénom :</label>
                        <input type="text" name="prenom" required>
                    </div>
                    <div class="form-group">
                        <label>Nom :</label>
                        <input type="text" name="nom" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Email :</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>Téléphone :</label>
                        <input type="tel" name="telephone">
                    </div>
                </div>
                <div class="form-group" style="max-width: 400px;">
                    <label>Mot de passe :</label>
                    <input type="text" name="password" required placeholder="Le client pourra se connecter avec ce mot de passe">
                </div>
                <button type="submit" name="create_client" class="btn btn-primary">Créer le client</button>
            </form>
        </div>

        <!-- Liste clients -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Liste des clients (<?php echo count($clients); ?>)</h2>
            </div>

            <?php if (empty($clients)): ?>
                <p style="text-align: center; color: #b0b0b0; padding: 30px 0;">Aucun client enregistré.</p>
            <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Nb locations</th>
                        <th>Inscrit le</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $c): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($c['nom'] ?? '—'); ?></td>
                        <td><?php echo htmlspecialchars($c['prenom'] ?? '—'); ?></td>
                        <td><?php echo htmlspecialchars($c['email']); ?></td>
                        <td><?php echo htmlspecialchars($c['telephone'] ?? '—'); ?></td>
                        <td><strong style="color: #00d4ff;"><?php echo $c['nb_locations']; ?></strong></td>
                        <td><?php echo date('d/m/Y', strtotime($c['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
