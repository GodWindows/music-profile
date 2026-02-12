<?php
require_once __DIR__ . '/env_data.php';

echo "<h2>Database Connection Test</h2>";

$conn = connect_database();
if (!$conn) {
    echo "<p style='color: red;'>❌ Database connection failed!</p>";
    exit;
}

echo "<p style='color: green;'>✅ Database connected successfully!</p>";

// Check if sessions table exists
try {
    $stmt = $conn->query("SHOW TABLES LIKE 'sessions'");
    $result = $stmt->fetch();
    
    if ($result) {
        echo "<p style='color: green;'>✅ 'sessions' table exists!</p>";
        
        // Show table structure
        echo "<h3>Sessions Table Structure:</h3>";
        $stmt = $conn->query("DESCRIBE sessions");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        foreach ($columns as $col) {
            echo "<tr>";
            echo "<td>{$col['Field']}</td>";
            echo "<td>{$col['Type']}</td>";
            echo "<td>{$col['Null']}</td>";
            echo "<td>{$col['Key']}</td>";
            echo "<td>{$col['Default']}</td>";
            echo "<td>{$col['Extra']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Show existing sessions
        echo "<h3>Existing Sessions:</h3>";
        $stmt = $conn->query("SELECT * FROM sessions");
        $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($sessions) > 0) {
            echo "<p>Found " . count($sessions) . " session(s):</p>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Session Token</th><th>Email</th><th>Created At</th></tr>";
            foreach ($sessions as $session) {
                echo "<tr>";
                echo "<td>" . ($session['id'] ?? 'N/A') . "</td>";
                echo "<td>" . substr($session['session_token'] ?? 'N/A', 0, 20) . "...</td>";
                echo "<td>" . ($session['email'] ?? 'N/A') . "</td>";
                echo "<td>" . ($session['created_at'] ?? 'N/A') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: orange;'>⚠️ No sessions found in the table.</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ 'sessions' table does NOT exist!</p>";
        echo "<p>You need to create the sessions table. Here's the SQL:</p>";
        echo "<pre>
CREATE TABLE sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_token VARCHAR(255) UNIQUE NOT NULL,
    google_access_token TEXT,
    email VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
        </pre>";
    }
} catch (PDOException $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
