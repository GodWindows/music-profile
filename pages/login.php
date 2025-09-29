<?php
    require __DIR__.  '/../vendor/autoload.php'; 
    require __DIR__.  '/../env_data.php'; // create this file after fetching the github code and store your client-id, client-secret and redirect uri in it
    require_once __DIR__.  '/../util/functions.php'; 

    $client = new Google\Client; 
    $client->setClientId($clientID);
    $client->setClientSecret($clientSecret);
    $client->setRedirectUri($redirect_uri);
    $client->addScope("email");
    $client->addScope("profile ");

    $url = $client->createAuthUrl();
?>
 
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $site_title ?> — Connexion</title>
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="icon" href="/img/logo.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body>
    <!-- Music Background Elements -->
    <div class="music-elements">
        <i data-lucide="music" class="music-note"></i>
        <i data-lucide="music-2" class="music-note"></i>
        <i data-lucide="music-3" class="music-note"></i>
        <i data-lucide="music-4" class="music-note"></i>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="brand-icon" style="margin: 0 auto var(--space-lg); font-size: 3rem; background: none; border: none; box-shadow: none;">
                    <img src="/img/logo.ico" alt="Logo" style="width: 3rem; height: 3rem;">
                </div>
                <h1 class="login-title"><?= $site_title ?></h1>
                <p class="login-subtitle">Créez votre collection musicale personnalisée</p>
            </div>
            
            <a href="<?=$url?>" class="btn btn-google">
                <i data-lucide="external-link"></i>
                <span>Continuer avec Google</span>
            </a>
            
            <p class="text-center mt-3 text-gray-400">
                En vous connectant, vous acceptez nos conditions d'utilisation
            </p>
        </div>
    </div>
</body>
</html>
