<?php
namespace Src\Controller;

use Src\Utils\Queries;

class Playlist
{
    private $db;
    private $requestMethod;
    private $func;

    public function __construct($db, $requestMethod, $func)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->func = $func;
    }

    // Handle Requests
    public function processRequest()
    {

        switch ($this->requestMethod) {
            case 'GET':
                switch ($this->func) {
                    case 'getPlaylistById':
                        $this->getPlaylistById();
                        break;
                    case 'getPlaylistsByCategory':
                        $this->getPlaylistsByCategory();
                        break;
                    default:
                        $this->errorResponse("INVALID URL");
                        break;
                }
                break;
            case 'POST':
                switch ($this->func) {
                    case 'createPlaylist':
                        $this->createPlaylist();
                        break;
                    default:
                        $this->errorResponse("INVALID URL");
                        break;
                }
                break;
            case 'PUT':
                switch ($this->func) {
                    case 'increasePlaylistViewCount':
                        $this->increasePlaylistViewCount();
                        break;
                    default:
                        $this->errorResponse("INVALID URL");
                        break;
                }
                break;
            default:
                $this->errorResponse("INVALID URL");
        }
    }

    /**
     * Fetch all playlists by [category_name].
     */
    private function getPlaylistsByCategory()
    {
        $data = (array) json_decode(file_get_contents('php://input'), true);
        $name = strtolower($data['category_name']);

        $query = Queries::$getPlaylistsByCategory;
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($name));

            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $this->successResponse($result);
        } catch (\PDOException$e) {
            $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Fetch playlist with id [playlist_id]
     */
    private function getPlaylistById()
    {
        $data = (array) json_decode(file_get_contents('php://input'), true);
        $query = Queries::$getPlaylistById;

        try {
            $id = (int) $data['playlist_id'];
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($id));

            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $this->successResponse($result);
        } catch (\PDOException$e) {
            $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Increase the View Count playlist by 1.
     * Playlist identified by [playlist_id]
     */
    private function increasePlaylistViewCount()
    {
        $data = (array) json_decode(file_get_contents('php://input'), true);
        $query = Queries::$increasePlaylistViewCount;

        try {
            $id = (int) $data['playlist_id'];
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($id));

            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $this->successResponse($result);
        } catch (\PDOException$e) {
            $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Create a new playlist in a given category.
     */
    private function createPlaylist()
    {
        $data = (array) json_decode(file_get_contents('php://input'), true);
        $categoryId = $this->getCategoryId($data['category_name']);

        if ($data['playlist_name']) {
            $playlistName = $data['playlist_name'];
            $playlistDesc = $data['playlist_description'] ?? "";

            $query = Queries::$createPlaylist;

            try {
                $stmt = $this->db->prepare($query);
                $stmt->execute(array(
                    "category_id" => $categoryId,
                    "playlist_name" => $playlistName,
                    "playlist_description" => $playlistDesc,
                ));
                $this->successResponse("Playlist created successfully!");
            } catch (\PDOException$e) {
                $this->errorResponse($e->getMessage());
            }

        } else {
            $this->errorResponse("INVALID PARAMETERS");
        }

    }

    /**
     * Return Category Id of categor [categoryName].
     * Create Category if the category does not exist.
     */
    private function getCategoryId($categoryName)
    {
        $this->createCategory($categoryName);
        $query = Queries::$getCategoryId;

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($categoryName));

            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC)[0];
            return (int) $result['category_id'];
        } catch (\PDOException$e) {
            $this->errorResponse($e->getMessage());
            exit();
        }
    }

    /**
     * Create a new Category by [category_name]
     */
    private function createCategory($categoryName)
    {
        $query = Queries::$createCategory;

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($categoryName));
        } catch (\PDOException$e) {
            return;
        }
    }

    // 200 OK response
    private function successResponse($body)
    {
        $response['status'] = '200';
        $response['data'] = $body;
        header('HTTP/1.1 200 OK');
        echo json_encode($response);
    }

    // 404 Not Found Response
    private function errorResponse($error)
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['status'] = '400';
        $response['error'] = $error;
        header('HTTP/1.1 404 Not Found');
        echo json_encode($response);
    }
}
