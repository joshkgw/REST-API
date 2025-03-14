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

set_exception_handler(function ($exception) {
    if ($exception instanceof ClientError) {
        $errorData = ClientError::handleClientError($exception);
        $data = ["error" => $errorData["message"]];
        http_response_code($errorData["code"]);
    } else {
        http_response_code(500);
        $data = ["error" => "Internal Server Error: " . $exception->getMessage()];
    }
    echo json_encode($data);
    exit();
});