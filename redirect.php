<?php
    require __DIR__.  '/vendor/autoload.php'; 
    require __DIR__.  '/env_data.php'; // create this file after fetching the github code and store your client-id, client-secret and redirect uri in it

    $client = new Google\Client; 
    $client->setClientId($clientID);
    $client->setClientSecret($clientSecret);
    $client->setRedirectUri($redirect_uri);

    if (! isset($_GET['code'])) {
        echo "login failed";
    }

    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    $oauth = new Google\Service\OAuth2($client);
    $userinfo = $oauth->userinfo->get(); 
    print_r($userinfo);

?>