<?php
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

function get_user_infos($email)
{
    $conn = connect_database();
    if ($conn) {
        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email  = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $user;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
