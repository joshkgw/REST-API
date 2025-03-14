<?php
/**
 * Front door script for the API
 * 
 * It sets the HTTP headers, routes the URL to the appropriate endpoint, 
 * and returns the JSON-encoded response.
 * 
 * @version 1.0.0
 * @author Joshua White
*/

require "exceptionhandler.php";
require "autoloader.php";
$env = require "env.php";


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
function routeURL(array $env): void {
    $basepath = strtolower($env["basepath"]);
    $url = strtolower(parse_url($_SERVER["REQUEST_URI"])["path"]);

    // Remove the base path from the URL
    if (strpos($url, $basepath) === 0) {
        $url = substr($url, strlen($basepath));
    }

    switch ($url) {
        case "/author":
            $endpoint = new Author($env);
            break;
        case "/award":
            $endpoint = new Award($env);
            break;
        case "/content":
            $endpoint = new Content($env);
            break;
        case "/developer":
            $endpoint = new Developer($env);
            break;
        case "/manager":
            $endpoint = new Manager($env);
            break;
        default:
            throw new ClientError("Endpoint not found", 404);
    }
    $data = $endpoint->getData();

    echo json_encode($data);
}


setHeaders();
$apiKey = new ApiKey($env);
routeURL($env);