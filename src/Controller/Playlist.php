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

        $name = strtolower($_GET['category_name']);

        $query = Queries::$getPlaylistsByCategory;
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($name));

            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $this->increaseCategoryViewCount($name);

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
        $query = Queries::$getPlaylistById;

        try {
            $id = (int) $_GET['playlist_id'];
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($id));

            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $this->increasePlaylistViewCount($id);

            $this->successResponse($result);
        } catch (\PDOException$e) {
            $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Increase the View Count playlist by 1.
     * Playlist identified by [playlist_id]
     */
    private function increasePlaylistViewCount($playlist_id)
    {
        $query = Queries::$increasePlaylistViewCount;

        try {
            $id = (int) $playlist_id;
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($id));
            return;
        } catch (\PDOException$e) {
            return;
        }
    }

    /**
     * Increase the View Count category by 1.
     * Category identified by [category_name]
     */
    private function increaseCategoryViewCount($category_name)
    {
        $query = Queries::$increaseCategoryViewCount;

        try {

            $stmt = $this->db->prepare($query);
            $stmt->execute(array(strtolower($category_name)));
            return;
        } catch (\PDOException$e) {
            return;
        }
    }

    /**
     * Create a new playlist in a given category.
     */
    private function createPlaylist()
    {
        $data = (array) json_decode(file_get_contents('php://input'), true);
        $categoryName = $data['category_name'];

        if (strlen($categoryName) <= 0) {
            $this->errorResponse("INVALID PARAMTERS");
            exit();
        }

        $categoryId = $this->getCategoryId(strtolower($categoryName));
        $links = (array) $data['links'];
        $currentTime = date("Y-m-d H:i:s");

        if (!$links || count($links) == 0) {
            $this->errorResponse("INVALID PARAMETERS");
            exit();
        }

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
                    "created_at" => $currentTime,
                ));

                // Get recently created playlist Id
                $query = Queries::$getPlaylist;
                $stmt = $this->db->prepare($query);
                $stmt->execute(array(
                    "playlist_name" => $playlistName,
                    "category_id" => $categoryId,
                    "created_at" => $currentTime,
                ));

                $result = $stmt->fetchAll(\PDO::FETCH_ASSOC)[0];

                $playlistId = (int) $result['playlist_id'];

                // Add links to the database
                $query = Queries::$addLinks;
                $stmt = $this->db->prepare($query);
                foreach ($links as $link) {
                    $linkMetadata = Queries::getLinkData($link);

                    $stmt->execute(array(
                        "playlist_id" => $playlistId,
                        "link" => $link,
                        "title" => $linkMetadata['title'],
                        "author_name" => $linkMetadata['author_name'],
                        "author_url" => $linkMetadata['author_url'],
                        "thumbnail_url" => $linkMetadata['thumbnail_url'],
                    ));
                }

                $this->successResponse("Playlist created successfully!");

            } catch (\PDOException$e) {
                $this->errorResponse($e->getMessage());
                exit();
            }

        } else {
            $this->errorResponse("INVALID PARAMETERS");
            exit();
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
        $response['status'] = '400';
        $response['error'] = $error;
        header('HTTP/1.1 404 Not Found');
        echo json_encode($response);
    }
}
