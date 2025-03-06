<?php
/**
 * General exception handler
 * 
 * This script sets a global exception handler that catches all uncaught exceptions,
 * sets the HTTP response code to 500, and returns a JSON-encoded response with the
 * exception details.
 * 
 * @version 1.0.0
 * @author Joshua White
 */

set_exception_handler(function($exception){
    http_response_code(500);
    $data = [
        "message" => $exception->getMessage(),
        "code" => $exception->getCode(),
        "file" => $exception->getFile(),
        "line" => $exception->getLine(),
    ];
    echo json_encode($data);
    exit();
});