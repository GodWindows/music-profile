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
$albumName = isset($input['albumName']) ? trim($input['albumName']) : '';

// Optional metadata from iTunes API
$externalAlbumId = isset($input['externalAlbumId']) ? trim($input['externalAlbumId']) : null; // iTunes collectionId
$externalArtistId = isset($input['externalArtistId']) ? trim($input['externalArtistId']) : null; // iTunes artistId
$artistName = isset($input['artistName']) ? trim($input['artistName']) : null;
$imageUrl60 = isset($input['imageUrl60']) ? trim($input['imageUrl60']) : null; // artworkUrl60
$imageUrl100 = isset($input['imageUrl100']) ? trim($input['imageUrl100']) : null; // artworkUrl100

// Validate album name
if (empty($albumName)) {
    http_response_code(400);
    echo json_encode(['error' => 'Album name cannot be empty']);
    exit;
}

if (strlen($albumName) > 255) {
    http_response_code(400);
    echo json_encode(['error' => 'Album name too long (max 255 characters)']);
    exit;
}

// Add album with metadata when available
if ($externalAlbumId || $artistName || $imageUrl60 || $imageUrl100) {
    $albumId = add_or_get_album_with_metadata_and_link_user($user['id'], [
        'external_album_id' => $externalAlbumId,
        'external_artist_id' => $externalArtistId,
        'album_name' => $albumName,
        'artist_name' => $artistName,
        'image_url_60' => $imageUrl60,
        'image_url_100' => $imageUrl100,
    ]);
} else {
    $albumId = add_album_to_user($user['id'], $albumName);
}

if ($albumId) {
    echo json_encode([
        'success' => true,
        'message' => 'Album added successfully',
        'albumId' => $albumId,
        'albumName' => $albumName
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to add album']);
}
?>

