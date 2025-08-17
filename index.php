 
 <?php
    require __DIR__.  "/vendor/autoload.php"; 
    require __DIR__.  "/env_data.php"; // create this file after fetching the github code and store your client-id, client-secret and redirect uri in it

    $client = new Google\Client; 
    $client->setClientId($clientID);
    $client->setClientSecret($clientSecret);
    $client->setRedirectUri($redirect_uri);
    $client->addScope("email");
    $client->addScope("profile ");

    $url = $client->createAuthUrl();
 ?>
 
 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Profile App !</title>
 </head>
 <body>
    <a href="<?=$url?>"> Sign in with Google</a>
 </body>
 </html>