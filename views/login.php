<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Connexion - DKR Locations</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css?v=2">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h1>DKR Locations</h1>
            <h2>Connexion</h2>
            
            <?php if (isset($error) && $error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" name="login" class="btn btn-primary">Se connecter</button>
            </form>
            
            <p class="info">Compte admin par défaut : admin@dkr.com / admin123</p>
            <p class="info">Compte commercial par défaut : commercial@dkr.com / commercial123</p>

            <p style="text-align: center; margin-top: 20px;">
                <a href="index.php?page=register" style="color: #00d4ff;">Pas encore client ? Créer un compte</a>
            </p>
        </div>
    </div>
</body>
</html>
