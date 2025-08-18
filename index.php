<?php
    require __DIR__.  '/vendor/autoload.php';
    require_once __DIR__.  '/env_data.php';
    require_once __DIR__.  '/functions.php';

    $client = new Google\Client; 
    $client->setClientId($clientID);
    $client->setClientSecret($clientSecret);
    $client->setRedirectUri($redirect_uri);

    if (!isset($_COOKIE['session_token']) || $_COOKIE['session_token']== "" ) {
        header('Location: login.php');

    } 
    $user = getUserFromSessionToken($_COOKIE['session_token']);
    if ($user== null) {
        header('Location: login.php');
    }
    
    $user = (getUserFromSessionToken($_COOKIE['session_token']));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Accueil — Mon Musium</title>
<link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="container">
    <header>
        <h1>Bienvenue, <?= htmlspecialchars($user['firstName']) ?> 👋</h1>
        <button id="logoutBtn">Déconnexion</button>
    </header>

    <section class="profile-card">
        <img src="<?= htmlspecialchars($user['picture']) ?>" alt="Photo de profil" class="avatar">
        <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Bio :</strong> <?= htmlspecialchars($user['bio']) ?></p>
    </section>
</div>

<script src="js/app.js"></script>
</body>
</html>