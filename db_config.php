<?php
    require_once __DIR__.  '/env_data.php';

    function connect_database() {
        $config = (object) [
            'server'   => "localhost:3306",
            'username' => "root",
            'password' => "rootroot",
            'name'     => "musium"
        ];
        try {
            $conn = new PDO(
                "mysql:host={$config->server};dbname={$config->name}", 
                $config->username, 
                $config->password
            );
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch(PDOException $e) {
            if (env_type() == "dev") {
                echo "Connection failed: " . $e->getMessage();
            }
        }
    }

    
?>