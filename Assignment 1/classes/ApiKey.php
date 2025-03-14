<?php
/**
 * ApiKey class
 * 
 * This class is used to validate and extract the API key from the Authorization header.
 * 
 * @version 1.0.0
 * @author Joshua White
 */
class ApiKey {
    /**
     * Extracts the API key from the Authorization header.
     * 
     * @return string The API key
     * @throws ClientError If the Authorization header is missing
     * @throws ClientError If the Authorization header is invalid
     */

    private $env;


    /**
     * Constructor to initialize the environment variables and validate the API key.
     * 
     * @param array $env The environment variables.
     */
    public function __construct(array $env) {
        $this->env = $env;
        $this->validateApiKey();
    }


    public function getApiKey(): string {
        // Get all headers from the HTTP request
        $allHeaders = getallheaders();
        
        // Check if the Authorization header is present
        if (array_key_exists('Authorization', $allHeaders)) {
            $authorizationHeader = $allHeaders['Authorization'];
        } elseif (array_key_exists('authorization', $allHeaders)) {
            $authorizationHeader = $allHeaders['authorization'];
        } else {
            // Throw an exception for missing authorization header
            throw new ClientError("Missing authorization header", 401);
        }
        
        // Check if the bearer token is present.
        if (substr($authorizationHeader, 0, 7) != 'Bearer ') {
            // Throw an exception for invalid authorization header
            throw new ClientError("Invalid authorization header", 400);
        }
        
        return trim(substr($authorizationHeader, 7));  
    }

    
    /**
     * Validates the API key.
     * 
     * @return void
     * @throws ClientError If the API key is invalid
     */
    public function validateApiKey(): void {
        if ($this->env['api_key'] != base64_decode($this->getApiKey())) {
            throw new ClientError("Invalid API key", 401);
        }
    }
}