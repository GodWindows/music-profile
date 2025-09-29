<?php

    require_once __DIR__.  '/../env_data.php';
    require_once __DIR__.  '/../util/functions.php';

    if (isset($_COOKIE['session_token'])) {

        $client = new Google\Client; 
        $client->setClientId($clientID);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirect_uri);
        logout($_COOKIE['session_token'], $client);
    }
    header('Location: /');
?>
