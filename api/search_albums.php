<?php
require_once __DIR__ . '/../env_data.php';
require_once __DIR__ . '/../util/functions.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Get the access token
$access_token = getSpotifyToken($spotify_client_id, $spotify_client_secret);

if (!$access_token) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to get Spotify access token']);
    exit;
}

// Check if request is from your app URL
$allowed_origins = [
    'http://localhost:8000',
    $site_url,
    'http://127.0.0.1:8000'
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$referer = $_SERVER['HTTP_REFERER'] ?? '';

$is_allowed = false;

// Check origin
if (in_array($origin, $allowed_origins)) {
    $is_allowed = true;
}

// Check referer as fallback
if (!$is_allowed && $referer) {
    foreach ($allowed_origins as $allowed_origin) {
        if (strpos($referer, $allowed_origin) === 0) {
            $is_allowed = true;
            break;
        }
    }
}

// If not allowed, return error
if (!$is_allowed) {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied']);
    exit;
}

// Get search parameters
$query = $_GET['q'] ?? $_POST['q'] ?? '';
$type = $_GET['type'] ?? $_POST['type'] ?? 'album';
$limit = $_GET['limit'] ?? $_POST['limit'] ?? '8';
$market = $_GET['market'] ?? $_POST['market'] ?? 'FR';

// Validate parameters
if (empty($query)) {
    http_response_code(400);
    echo json_encode(['error' => 'Search term is required']);
    exit;
}

// Search Spotify API
function searchSpotify($query, $type, $limit, $market, $access_token) {
    $url = 'https://api.spotify.com/v1/search?' . http_build_query([
        'q' => $query,
        'type' => $type,
        'limit' => $limit,
        'market' => $market
    ]);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $access_token
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code === 401) {
        // Token expired, try to get a new one
        global $spotify_client_id, $spotify_client_secret;
        $new_token = getSpotifyToken($spotify_client_id, $spotify_client_secret);
        if ($new_token) {
            // Retry with new token
            return searchSpotify($query, $type, $limit, $market, $new_token);
        }
    }
    
    if ($http_code === 200) {
        return json_decode($response, true);
    }
    
    return null;
}

$spotify_data = searchSpotify($query, $type, $limit, $market, $access_token);

if (!$spotify_data) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to search Spotify']);
    exit;
}

// Convert Spotify response to iTunes-like format
$results = [];
if (isset($spotify_data['albums']['items'])) {
    foreach ($spotify_data['albums']['items'] as $album) {
        $artwork_url = '';
        if (isset($album['images']) && count($album['images']) > 0) {
            // Get the largest image
            $artwork_url = $album['images'][0]['url'];
        }
        
        $artist_name = '';
        if (isset($album['artists']) && count($album['artists']) > 0) {
            $artist_name = $album['artists'][0]['name'];
        }
        
        $results[] = [
            'collectionName' => $album['name'] ?? '',
            'artistName' => $artist_name,
            'artworkUrl100' => $artwork_url,
            'collectionId' => $album['id'] ?? '',
            'artistId' => isset($album['artists'][0]['id']) ? $album['artists'][0]['id'] : '',
        ];
    }
}

$response = [
    'resultCount' => count($results),
    'results' => $results
];

echo json_encode($response);
?>
