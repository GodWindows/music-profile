<?php
    require_once __DIR__ . '/../vendor/autoload.php'; 
    require_once __DIR__ . '/../env_data.php';

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
                if (env_type() == "dev") {
                    error_log("Error checking user existence: " . $e->getMessage());
                }
            }
        }
    }

    function create_user($email, $givenName, $picture)
    {
        $conn = connect_database();
        if ($conn) {
            try {
                $stmt = $conn->prepare("INSERT INTO users (email, firstName, picture, profile_visibility) VALUES (?, ?, ?, 'private')");
                $stmt->execute([$email, $givenName, $picture]);
            } catch (PDOException $e) {
                if (env_type() == "dev") {
                    error_log("Error creating user: " . $e->getMessage());
                }
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
                if (env_type() == "dev") {
                    error_log("Error fetching user data: " . $e->getMessage());
                }
            }
        }
    }

    function get_profile_visibility($email)
    {
        $conn = connect_database();
        if ($conn) {
            try {
                $stmt = $conn->prepare("SELECT profile_visibility FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result ? $result['profile_visibility'] : 'private';
            } catch (PDOException $e) {
                if (env_type() == "dev") {
                    error_log("Error fetching profile visibility: " . $e->getMessage());
                }
                return 'private';
            }
        }
        return 'private';
    }

    function update_user_bio($email, $bio)
    {
        $conn = connect_database();
        if ($conn) {
            try {
                $stmt = $conn->prepare("UPDATE users SET bio = ? WHERE email = ?");
                $result = $stmt->execute([$bio, $email]);
                return $result;
            } catch (PDOException $e) {
                if (env_type() == "dev") {
                    error_log("Error updating bio: " . $e->getMessage());
                }
                return false;
            }
        }
        return false;
    }

    function update_profile_visibility($email, $visibility)
    {
        $conn = connect_database();
        if ($conn) {
            try {
                if (!in_array($visibility, ['private', 'public'])) {
                    return false;
                }
                $stmt = $conn->prepare("UPDATE users SET profile_visibility = ? WHERE email = ?");
                $result = $stmt->execute([$visibility, $email]);
                return $result;
            } catch (PDOException $e) {
                if (env_type() == "dev") {
                    error_log("Error updating profile visibility: " . $e->getMessage());
                }
                return false;
            }
        }
        return false;
    }

    function check_pseudo_availability($pseudo)
    {
        $conn = connect_database();
        if ($conn) {
            try {
                $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE pseudo = ?");
                $stmt->execute([$pseudo]);
                $count = $stmt->fetchColumn();
                return $count === 0;
            } catch (PDOException $e) {
                if (env_type() == "dev") {
                    error_log("Error checking pseudo availability: " . $e->getMessage());
                }
                return false;
            }
        }
        return false;
    }

    function update_user_pseudo($email, $pseudo)
    {
        $conn = connect_database();
        if ($conn) {
            try {
                if (!check_pseudo_availability($pseudo)) {
                    return false;
                }
                $stmt = $conn->prepare("UPDATE users SET pseudo = ? WHERE email = ?");
                $result = $stmt->execute([$pseudo, $email]);
                return $result;
            } catch (PDOException $e) {
                if (env_type() == "dev") {
                    error_log("Error updating pseudo: " . $e->getMessage());
                }
                return false;
            }
        }
        return false;
    }

    function get_user_albums($userId)
    {
        $conn = connect_database();
        if ($conn) {
            try {
                $stmt = $conn->prepare("\n                    SELECT a.id, a.name, a.artist_name, a.image_url_60, a.image_url_100, a.created_at, ua.added_at\n                    FROM albums a\n                    INNER JOIN user_albums ua ON a.id = ua.album_id\n                    WHERE ua.user_id = ?\n                    ORDER BY ua.added_at DESC\n                ");
                $stmt->execute([$userId]);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                if (env_type() == "dev") {
                    error_log("Error fetching user albums: " . $e->getMessage());
                }
                return [];
            }
        }
        return [];
    }

    function add_album_to_user($userId, $albumName)
    {
        $conn = connect_database();
        if ($conn) {
            try {
                $conn->beginTransaction();
                $stmt = $conn->prepare("INSERT INTO albums (name) VALUES (?)");
                $stmt->execute([$albumName]);
                $albumId = $conn->lastInsertId();
                $stmt = $conn->prepare("INSERT INTO user_albums (user_id, album_id) VALUES (?, ?)");
                $stmt->execute([$userId, $albumId]);
                $conn->commit();
                return $albumId;
            } catch (PDOException $e) {
                if(env_type() == "dev") {
                    $conn->rollBack();
                    error_log("Error adding album to user: " . $e->getMessage());
                }
                return false;
            }
        }
        return false;
    }

    function remove_album_from_user($userId, $albumId)
    {
        $conn = connect_database();
        if ($conn) {
            try {
                $stmt = $conn->prepare("DELETE FROM user_albums WHERE user_id = ? AND album_id = ?");
                $result = $stmt->execute([$userId, $albumId]);
                return $result;
            } catch (PDOException $e) {
                if (env_type() == "dev") {
                    error_log("Error removing album from user: " . $e->getMessage());
                }
                return false;
            }
        }
        return false;
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
                if (env_type() == "dev") {
                    echo "Error: " . $e->getMessage();
                }
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
                if (env_type() == "dev") {
                    echo "Error: " . $e->getMessage();
                }
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
                        if (env_type() == "dev") {
                            error_log("Erreur lors de la révocation Google: " . $e->getMessage());
                        }
                    }
                }
            } catch (PDOException $e) {
                if (env_type() == "dev") {
                    echo "Error: " . $e->getMessage();
                }
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
                    return null;
                }
            
                $email = $row['email'];
            
                return get_user_data($email);
            } catch (PDOException $e) {
                if (env_type() == "dev") {
                    echo "Error: " . $e->getMessage();
                }
            }
        }
    }

    function get_user_public_min_by_pseudo($pseudo)
    {
        $conn = connect_database();
        if ($conn) {
            try {
                $stmt = $conn->prepare("SELECT id, pseudo, firstName, bio, profile_visibility FROM users WHERE pseudo = ? LIMIT 1");
                $stmt->execute([$pseudo]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return $row ?: null;
            } catch (PDOException $e) {
                if (env_type() == "dev") {
                    error_log("Error fetching user by pseudo: " . $e->getMessage());
                }
                return null;
            }
        }
        return null;
    }

    function add_or_get_album_with_metadata_and_link_user($userId, $albumData)
    {
        $conn = connect_database();
        if (!$conn) {
            return false;
        }
        try {
            $conn->beginTransaction();

            $externalAlbumId = isset($albumData["external_album_id"]) ? $albumData["external_album_id"] : null;
            $albumName = isset($albumData["album_name"]) ? $albumData["album_name"] : null;
            $externalArtistId = isset($albumData["external_artist_id"]) ? $albumData["external_artist_id"] : null;
            $artistName = isset($albumData["artist_name"]) ? $albumData["artist_name"] : null;
            $image60 = isset($albumData["image_url_60"]) ? $albumData["image_url_60"] : null;
            $image100 = isset($albumData["image_url_100"]) ? $albumData["image_url_100"] : null;

            if (!empty($externalAlbumId)) {
                $stmt = $conn->prepare("SELECT id FROM albums WHERE external_album_id = ? LIMIT 1");
                $stmt->execute([$externalAlbumId]);
                $existing = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($existing && isset($existing["id"])) {
                    $albumId = $existing["id"];
                } else {
                    $stmt = $conn->prepare("INSERT INTO albums (external_album_id, external_artist_id, name, artist_name, image_url_60, image_url_100) VALUES (?, ?, ?, ?, ?, ?) ");
                    $stmt->execute([$externalAlbumId, $externalArtistId, $albumName, $artistName, $image60, $image100]);
                    $albumId = $conn->lastInsertId();
                }
            } else {
                $stmt = $conn->prepare("INSERT INTO albums (name, artist_name, image_url_60, image_url_100) VALUES (?, ?, ?, ?)");
                $stmt->execute([$albumName, $artistName, $image60, $image100]);
                $albumId = $conn->lastInsertId();
            }

            try {
                $stmt = $conn->prepare("INSERT INTO user_albums (user_id, album_id) VALUES (?, ?)");
                $stmt->execute([$userId, $albumId]);
            } catch (PDOException $e) {
                // ignore duplicate link
            }

            $conn->commit();
            return $albumId;
        } catch (PDOException $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            error_log("Error adding album with metadata: " . $e->getMessage());
            return false;
        }
    }
?>
