<?php
/**
 * Developer class
 * 
 * This class provides information about the developer.
 * 
 * @version 1.0.0
 * @author Joshua White
 */

class Developer extends Endpoint
{
    private $name = "Joshua White";
    private $student_id = "w23004603";

    /**
     * Handles GET requests to retrieve developer information.
     * 
     * @return void
     */
    protected function get(): void
    {
        $this->setData([
            "name" => $this->name,
            "student_id" => $this->student_id,
        ]);
    }
}