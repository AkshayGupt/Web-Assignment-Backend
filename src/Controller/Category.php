<?php
namespace Src\Controller;

use Src\Utils\Queries;

class Category
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
                    case 'getAllCategories':
                        $this->getAllCategories();
                        break;
                    case 'getPopularCategories':
                        $this->getPopularCategories();
                        break;
                    default:
                        $this->errorResponse("INVALID URL");
                        break;
                }
                break;
            case 'PUT':
                switch ($this->func) {
                    case 'increaseCategoryViewCount':
                        $this->increaseCategoryViewCount();
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
     * Fetch all Categories in the database.
     */
    private function getAllCategories()
    {
        $query = Queries::$getAllCategories;

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $this->successResponse($result);
        } catch (\PDOException$e) {
            $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Fetch popular Categories in the database.
     * LIMIT the result top 5 popular categories.
     */
    private function getPopularCategories()
    {
        $query = Queries::$getPopularCategories;

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $this->successResponse($result);
        } catch (\PDOException$e) {
            $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Increase the View Count category by 1.
     * Category identified by [category_id]
     */
    private function increaseCategoryViewCount()
    {
        $data = (array) json_decode(file_get_contents('php://input'), true);
        $query = Queries::$increaseCategoryViewCount;

        try {
            $id = (int) $data['category_id'];
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($id));

            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $this->successResponse($result);
        } catch (\PDOException$e) {
            $this->errorResponse($e->getMessage());
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
        $response['status'] = '200';
        $response['error'] = $error;
        header('HTTP/1.1 404 Not Found');
        echo json_encode($response);
    }
}
