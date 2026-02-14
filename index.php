<?php
    require __DIR__ . '/vendor/autoload.php';
    require_once __DIR__ . '/env_data.php';
    require_once __DIR__ . '/util/functions.php';

    // Route public profile: /@username or ?u=username
    $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (preg_match('#/@([A-Za-z0-9_.-]+)$#', $requestPath) || (isset($_GET['u']) && $_GET['u'] !== '')) {
        require __DIR__ . '/pages/public_profile.php';
        exit();
    }

    // Show landing page for root URL
    require __DIR__ . '/pages/landing.php';
    exit();
?>