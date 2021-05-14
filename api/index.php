<?php
require "../start.php";
use Src\Controller\Category;
use Src\Controller\Playlist;

// Set header with CORS
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
    // you want to allow, and if so:
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 1000');
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
    }

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");
    }
    exit(0);
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

// Routes are like URL/api/...
if ($uri[1] !== 'api' || $uri[2] === null) {
    header("HTTP/1.1 404 Not Found");
    $response["status"] = "404";
    $response["error"] = "INVALID URL";
    echo json_encode($response);
    exit();
}

$requestMethod = $_SERVER['REQUEST_METHOD']; // GET, POST, PUT, DELETE

$controller = null;
switch ($uri[2]) {
    case 'getAllCategories':
    case 'getPopularCategories':
    case 'increaseCategoryViewCount':
        $controller = new Category($dbConnection, $requestMethod, $uri[2]);
        break;
    case 'getPlaylistsByCategory':
    case 'getPlaylistById':
    case 'createPlaylist':
    case 'increasePlaylistViewCount':
        $controller = new Playlist($dbConnection, $requestMethod, $uri[2]);
        break;
    default:
        $controller = null;
        header("HTTP/1.1 404 Not Found");
        $response["status"] = "404";
        $response["error"] = "INVALID URL";
        echo json_encode($response);
        exit();
}

$controller->processRequest();
