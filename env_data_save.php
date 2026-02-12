 <?php/* 
    function env_type(){
        return "dev"; // dev | prod
    }
    $clientID = "1086296910946-9aanvitsci0m13du9l3uhlf5mpggv7a9.apps.googleusercontent.com";
    $clientSecret = "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";
    $redirect_uri = "https://universon.fr/util/redirect.php";
    
    

    
    // Application settings
    $site_url = "https://universon.fr"; // e.g., https://monsite.com
    $site_title = "Universon (Mon univers musical)";
    
    function connect_database() {
        $config = (object) [
            'server'   => "localhost",
            'username' => "u747137429_universon_web",
            'password' => "2Tq_@wY6f$+fUCz",
            'name'     => "u747137429_universon"
        ];
        try {
            $conn = new PDO(
                "mysql:host={$config->server};dbname={$config->name}", 
                $config->username, 
                $config->password
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch(PDOException $e) {
            if (env_type() == "dev") {
                echo "Connection failed: " . $e->getMessage();
            }
        }
    } */
?> 