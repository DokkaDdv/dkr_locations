<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>DKR Locations</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css?v=2">
    <style>
        .home-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            padding: 40px 20px;
        }
        .home-title {
            font-size: 52px;
            font-weight: bold;
            color: #00d4ff;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }
        .home-subtitle {
            font-size: 18px;
            color: #b0b0b0;
            margin-bottom: 50px;
        }
        .home-actions {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-home {
            padding: 14px 40px;
            font-size: 16px;
            border-radius: 6px;
        }
        .home-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 60px;
            max-width: 800px;
            width: 100%;
        }
        .feature-card {
            background: #2a2a2a;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 25px;
        }
        .feature-card h3 {
            color: #00d4ff;
            font-size: 16px;
            margin-bottom: 8px;
        }
        .feature-card p {
            color: #b0b0b0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="home-wrapper">
        <div class="home-title">DKR Locations</div>
        <p class="home-subtitle">La location de véhicules simple et rapide</p>

        <div class="home-actions">
            <a href="index.php?page=login" class="btn btn-primary btn-home">Se connecter</a>
            <a href="index.php?page=register" class="btn btn-secondary btn-home">Créer un compte</a>
        </div>

        <div class="home-features">
            <div class="feature-card">
                <h3>Large choix</h3>
                <p>Des véhicules disponibles pour tous vos besoins, de la citadine au SUV.</p>
            </div>
            <div class="feature-card">
                <h3>Réservation simple</h3>
                <p>Choisissez votre véhicule, vos dates, et c'est tout.</p>
            </div>
            <div class="feature-card">
                <h3>Suivi en ligne</h3>
                <p>Consultez et gérez vos locations depuis votre espace client.</p>
            </div>
        </div>
    </div>
</body>
</html>
