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
    $client->setAccessToken($token['access_token']);

    $oauth = new Google\Service\OAuth2($client);
    $userinfo = $oauth->userinfo->get(); 

    $_30days = (86400 * 30);
    setcookie('oauth', json_encode($oauth), time() +$_30days , "/"); 
    /* var_dump(
        $userinfo->email,
        $userinfo->givenName,
        $userinfo->picture,
    ); */
    if(! user_exists($userinfo->email)){
        create_user($userinfo->email, $userinfo->givenName, $userinfo->picture);
    }
    var_dump(get_user_infos($userinfo->email)) ;

    //redirect to dashboard

?>