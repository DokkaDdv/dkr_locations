<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Modifier un véhicule - DKR Locations</title>
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
        <?php 
        // Récupérer le véhicule à modifier
        $id = $_GET['id'] ?? 0;
        $vehicleToEdit = $vehicle->getById($id);
        
        if (!$vehicleToEdit):
        ?>
            <div class="alert alert-error">Véhicule non trouvé</div>
            <a href="index.php?page=commercial_vehicles_list" class="btn btn-secondary">Retour à la liste</a>
        <?php else: ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <!-- Formulaire de modification -->
            <div class="card">
                <h2>Modifier le véhicule</h2>
                <form method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $vehicleToEdit['id']; ?>">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="marque">Marque :</label>
                            <input type="text" id="marque" name="marque" value="<?php echo htmlspecialchars($vehicleToEdit['marque']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="modele">Modèle :</label>
                            <input type="text" id="modele" name="modele" value="<?php echo htmlspecialchars($vehicleToEdit['modele']); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="immatriculation">Immatriculation :</label>
                            <input type="text" id="immatriculation" name="immatriculation" value="<?php echo htmlspecialchars($vehicleToEdit['immatriculation']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="tarif">Tarif (€/jour) :</label>
                            <input type="number" id="tarif" name="tarif" step="0.01" value="<?php echo $vehicleToEdit['tarif']; ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="kilometrage">Kilométrage :</label>
                            <input type="number" id="kilometrage" name="kilometrage" value="<?php echo $vehicleToEdit['kilometrage']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="statut">Statut :</label>
                            <select id="statut" name="statut" required>
                                <option value="disponible" <?php echo $vehicleToEdit['statut'] === 'disponible' ? 'selected' : ''; ?>>Disponible</option>
                                <option value="reserve" <?php echo $vehicleToEdit['statut'] === 'reserve' ? 'selected' : ''; ?>>Réservé</option>
                                <option value="en_location" <?php echo $vehicleToEdit['statut'] === 'en_location' ? 'selected' : ''; ?>>En location</option>
                                <option value="maintenance" <?php echo $vehicleToEdit['statut'] === 'maintenance' ? 'selected' : ''; ?>>En maintenance</option>
                            </select>
                        </div>
                    </div>
                    
                    <div style="margin-top: 20px;">
                        <button type="submit" name="update_vehicle" class="btn btn-primary">Modifier le véhicule</button>
                        <a href="index.php?page=commercial_vehicles_list" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
            
        <?php endif; ?>
    </div>
</body>
</html>
