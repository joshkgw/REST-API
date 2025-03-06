<?php
/** 
 * Endpoint class
 * 
 * This is the base class for all endpoints. It contains basic functionality
 * for handling requests. It will return a 405 error for any method that is
 * not implemented.
 * 
 * @version 1.0.0
 * @author Joshua White
 */

class Endpoint
{
    private $data;

    /**
     * Constructor to handle the request method and call the appropriate method.
     * 
     * @throws ClientError If the request method is not allowed.
     */
    public function __construct()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $method_map = [
            "GET" => "get",
            "POST" => "post",
            "PATCH" => "patch",
            "PUT" => "put",
            "DELETE" => "delete",
            "OPTIONS" => "options",
        ];

        // Checks if HTTP method exists in associative array
        if (array_key_exists($method, $method_map)) {
            // Calls the associated method.
            $this->{$method_map[$method]}();
        } else {
            throw new ClientError("Method not allowed", 405);
        }
    }

    /**
     * Setter method for data.
     * 
     * Typically called by endpoints to set the data to be returned.
     * 
     * @param mixed $data The data to be set.
     * @return void
     */
    protected function setData($data): void
    {
        $this->data = $data;
    }

    /**
     * Getter method for data.
     * 
     * Typically called by the API script to encode the data as JSON.
     * 
     * @return mixed The data to be returned.
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Default method for GET requests.
     * 
     * @return void
     * @throws ClientError Always throws a 405 error.
     */
    protected function get(): void
    {
        throw new ClientError("GET method not allowed", 405);
    }

    /**
     * Default method for POST requests.
     * 
     * @return void
     * @throws ClientError Always throws a 405 error.
     */
    protected function post(): void
    {
        throw new ClientError("POST method not allowed", 405);
    }

    /**
     * Default method for PATCH requests.
     * 
     * @return void
     * @throws ClientError Always throws a 405 error.
     */
    protected function patch(): void
    {
        throw new ClientError("PATCH method not allowed", 405);
    }

    /**
     * Default method for PUT requests.
     * 
     * @return void
     * @throws ClientError Always throws a 405 error.
     */
    protected function put(): void
    {
        throw new ClientError("PUT method not allowed", 405);
    }

    /**
     * Default method for DELETE requests.
     * 
     * @return void
     * @throws ClientError Always throws a 405 error.
     */
    protected function delete(): void
    {
        throw new ClientError("DELETE method not allowed", 405);
    }

    /**
     * Default method for OPTIONS requests.
     * 
     * @return void
     */
    protected function options(): void
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        http_response_code(200);
        exit();
    }
}