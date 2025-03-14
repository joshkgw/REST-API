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
    /**
     * Handles GET requests to retrieve developer information.
     * 
     * @return void
     */
    protected function get(): void
    {
        $this->setData([
            "name" => $this->env['name'],
            "student_id" => $this->env['student_id'],
        ]);
    }
}