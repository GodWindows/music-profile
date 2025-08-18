<?php
    require_once __DIR__.  '/vendor/autoload.php'; 
    require_once __DIR__ .  '/db_config.php';
    require_once __DIR__ .  '/env_data.php';

    function user_exists($email)
    {
        $conn = connect_database();
        if ($conn) {
            try {
                $stmt = $conn->prepare("SELECT * FROM users WHERE email  = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return (count($user) >= 1);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    function create_user($email, $givenName, $picture)
    {
        $conn = connect_database();
        if ($conn) {
            try {
                $stmt = $conn->prepare("INSERT INTO users (email, firstName, picture) VALUES (?, ?,?)");
                $stmt->execute([$email, $givenName, $picture]);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    function get_user_data($email)
    {
        $conn = connect_database();
        if ($conn) {
            try {
                $stmt = $conn->prepare("SELECT * FROM users WHERE email  = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $user[0];
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    function saveSessionToDb($sessionToken, $googleAccessToken, $email) {
        $conn = connect_database();
        if ($conn) {
            try {
                $stmt = $conn->prepare("INSERT INTO sessions (session_token, google_access_token, email) VALUES (:token, :access, :email)");
                $stmt->execute([
                    ':token' => $sessionToken,
                    ':email' => $email,
                    ':access' => $googleAccessToken,
                ]);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        
    }

    function deleteSessionFromDb($sessionToken) {
        $conn = connect_database();
        if ($conn) {
            try {
                $stmt = $conn->prepare("DELETE FROM sessions WHERE session_token = :token");
                $stmt->execute([':token' => $sessionToken]);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    function logout($sessionToken, $client) {
        setcookie("session_token", "", time() - 3600, "/", "", true, true);

        $conn = connect_database();
        if ($conn) {
            try {
                $stmt = $conn->prepare("SELECT google_access_token FROM sessions WHERE session_token = :token LIMIT 1");
                $stmt->execute([':token' => $sessionToken]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row && !empty($row['google_access_token'])) {

                    $accessToken = $row['google_access_token'];

                    
                    $client->setAccessToken($accessToken);

                    try {
                        $client->revokeToken();
                    } catch (Exception $e) {
                        error_log("Erreur lors de la révocation Google: " . $e->getMessage());
                    }
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        deleteSessionFromDb($sessionToken);


        

    }

    function getUserFromSessionToken($sessionToken) {
        $conn = connect_database();
        if ($conn) {
            try {
                $stmt = $conn->prepare("SELECT email FROM sessions WHERE session_token = :token LIMIT 1");
                $stmt->execute([':token' => $sessionToken]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
                if (!$row) {
                    return null; // Token invalide ou expiré
                }
            
                $email = $row['email'];
            
                return get_user_data($email);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
?>