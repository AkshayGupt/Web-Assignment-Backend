<?php
namespace Src\Controller;

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
        $res = null;
        switch ($this->requestMethod) {
            case 'GET':
                switch ($this->func) {
                    case 'getPlaylistById':
                        break;
                    case 'getPlaylistsByCategory':
                        break;
                    default:
                        $res = $this->notFoundResponse("INVALID URL");
                        break;
                }
                break;
            case 'POST':
                switch ($this->func) {
                    case 'createPlaylist':
                        break;
                    default:
                        $res = $this->notFoundResponse("INVALID URL");
                        break;
                }
                break;
            case 'PUT':
                switch ($this->func) {
                    case 'increasePlaylistViewCount':
                        break;
                    default:
                        $res = $this->notFoundResponse("INVALID URL");
                        break;
                }
                break;
            default:
                $res = $this->notFoundResponse("INVALID URL");
        }
        header($res['status_code_header']);
        echo json_encode($res);

    }

    // 200 OK response
    private function successResponse($body)
    {
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['status'] = '200';
        $response['message'] = $body;
        return $response;
    }

    // 404 Not Found Response
    private function notFoundResponse($error)
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['status'] = '200';
        $response['error'] = $error;
        return $response;
    }
}
