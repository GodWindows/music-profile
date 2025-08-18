<?php
    require __DIR__.  '/vendor/autoload.php'; 
    require_once __DIR__.  '/env_data.php';
    require_once __DIR__.  '/db_config.php';
    require_once __DIR__.  '/functions.php';

    $client = new Google\Client; 
    $client->setClientId($clientID);
    $client->setClientSecret($clientSecret);
    $client->setRedirectUri($redirect_uri);

    if (! isset($_GET['code'])) {
        echo "login failed";
    }

    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $googleAccessToken = $token['access_token'];
    $client->setAccessToken($token);

    $oauth = new Google\Service\OAuth2($client);
    $userinfo = $oauth->userinfo->get(); 

    $_30days = (86400 * 30);
    /* var_dump(
        $userinfo->email,
        $userinfo->givenName,
        $userinfo->picture,
    ); */
    if(! user_exists($userinfo->email)){
        create_user($userinfo->email, $userinfo->givenName, $userinfo->picture);
    }
    $sessionToken = base64_encode(random_bytes(32));
    saveSessionToDb($sessionToken, $googleAccessToken, $userinfo->email );
    setcookie('session_token', $sessionToken, time() +$_30days , "/"); 
    header('Location: index.php');

?>