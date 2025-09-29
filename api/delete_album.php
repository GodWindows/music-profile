<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../util/functions.php';
require_once __DIR__ . '/../env_data.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Check if user is authenticated
if (!isset($_COOKIE['session_token']) || empty($_COOKIE['session_token'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get user from session
$user = getUserFromSessionToken($_COOKIE['session_token']);
if (!$user) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid session']);
    exit;
}

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);
$albumId = isset($input['albumId']) ? (int)$input['albumId'] : 0;

// Validate album ID
if ($albumId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid album ID']);
    exit;
}

// Remove album from user
$success = remove_album_from_user($user['id'], $albumId);

if ($success) {
    echo json_encode([
        'success' => true,
        'message' => 'Album removed successfully'
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to remove album']);
}
?>

