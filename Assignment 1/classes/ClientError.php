<?php
/**
 * Client Error Exceptions
 * 
 * Any 400s error will be handled by this class.
 * 
 * @version v1.0.1
 * @author Joshua White
 */

class ClientError extends Exception 
{
    private static $errorMessages = [
        400 => "Bad request",
        401 => "Unauthorized",
        403 => "Forbidden",
        404 => "Not found",
        405 => "Method not allowed",
        406 => "Not acceptable",
        408 => "Request timeout",
        409 => "Conflict",
    ];

    
    /**
     * Handles client errors by setting the appropriate HTTP response code and returning the error message.
     * 
     * @param ClientError $exception The client error exception to handle.
     * @return array The error message and code corresponding to the HTTP status code.
     * @throws Exception if the error code is unknown.
     */
    public static function handleClientError(ClientError $exception)
    {
        $code = $exception->getCode();
        if (array_key_exists($code, self::$errorMessages)) {
            http_response_code($code);
            return [
                "message" => self::$errorMessages[$code] . ": " . $exception->getMessage(),
                "code" => $code
            ];
        } else {
            http_response_code(500);
            return [
                "message" => "Unknown error",
                "code" => 500
            ];
        }
    }
}