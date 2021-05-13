<?php
require "../start.php";
use Src\Controller\Category;
use Src\Controller\Playlist;

// Set Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

// Routes are like URL/api/...
if ($uri[1] !== 'api' || $uri[2] === null) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

$requestMethod = $_SERVER['REQUEST_METHOD']; // GET, POST, PUT, DELETE

$controller = null;
switch ($uri[2]) {
    case 'getAllCategories':
    case 'getPopularCategories':
    case 'createCategory':
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
        exit();
}

$controller->processRequest();
