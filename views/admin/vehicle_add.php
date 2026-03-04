<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Ajouter un véhicule - DKR Locations</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css?v=2">
</head>
<body>
    <div class="header">
        <h1>DKR Locations - Ajouter un véhicule</h1>
        <div class="user-info">
            <span>Connecté en tant que : <?php echo htmlspecialchars($_SESSION['user_email']); ?> (Admin)</span>
            <a href="index.php?logout=1" class="btn btn-secondary">Déconnexion</a>
        </div>
    </div>
    
    <div class="container">
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <!-- Formulaire d'ajout de véhicule -->
        <div class="card">
            <h2>Ajouter un nouveau véhicule</h2>
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="marque">Marque :</label>
                        <input type="text" id="marque" name="marque" required>
                    </div>
                    <div class="form-group">
                        <label for="modele">Modèle :</label>
                        <input type="text" id="modele" name="modele" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="immatriculation">Immatriculation :</label>
                        <input type="text" id="immatriculation" name="immatriculation" required>
                    </div>
                    <div class="form-group">
                        <label for="tarif">Tarif (€/jour) :</label>
                        <input type="number" id="tarif" name="tarif" step="0.01" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="kilometrage">Kilométrage :</label>
                        <input type="number" id="kilometrage" name="kilometrage" required>
                    </div>
                    <div class="form-group">
                        <label for="statut">Statut :</label>
                        <select id="statut" name="statut" required>
                            <option value="disponible">Disponible</option>
                            <option value="reserve">Réservé</option>
                            <option value="en_location">En location</option>
                            <option value="maintenance">En maintenance</option>
                        </select>
                    </div>
                </div>
                
                <div style="margin-top: 20px;">
                    <button type="submit" name="add_vehicle" class="btn btn-primary">Ajouter le véhicule</button>
                    <a href="index.php?page=vehicles_list" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
