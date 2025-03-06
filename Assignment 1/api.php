<?php
/**
 * Front door script for the API
 * 
 * An API that returns data about the author and an academic conference.
 * It will also support modifying data, specifically about awards given 
 * at the conference, including giving and taking away awards.
 * 
 * @version 1.0.0
 * @author Joshua White
*/

require "exceptionhandler.php";
require "autoloader.php";

/**
 * Sets the HTTP headers for the API response.
 * 
 * @return void
*/
function setHeaders(): void {
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: https://w23004603.nuwebspace.co.uk");
}

/**
 * Routes the URL to the appropriate endpoint based on the request URI.
 * 
 * @return void
 * @throws ClientError If the endpoint is not found.
*/
function routeURL(): void {
    $url = strtolower(parse_url($_SERVER["REQUEST_URI"])["path"]);

    try {
        switch ($url) {
            case "/assignment-1/author":
                $endpoint = new Author();
                break;
            case "/assignment-1/award":
                $endpoint = new Award();
                break;
            case "/assignment-1/content":
                $endpoint = new Content();
                break;
            case "/assignment-1/developer":
                $endpoint = new Developer();
                break;
            case "/assignment-1/manager":
                $endpoint = new Manager();
                break;
            default:
                throw new ClientError("Endpoint not found", 404);
        }
        $data = $endpoint->getData();

    } catch (ClientError $exception) {
        $data = ["error" => ClientError::handleClientError($exception)];
    } 

    echo json_encode($data);
}

setHeaders();
routeURL();