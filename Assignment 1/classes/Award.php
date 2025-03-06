<?php
/**
 * Award class
 * 
 * Handles requests to the award endpoint.
 * 
 * @version v1.0.0
 * @author Joshua White
 */

class Award extends Endpoint
{
    /**
     * Handles GET requests to retrieve a list of awards.
     * 
     * @return void
     */
    protected function get(): void {
        $db = new Database("db/chi2023.sqlite");
        $sql = "SELECT DISTINCT award.id AS award_id,
                award.name
            FROM award";
        $params = [];

        $data = $db->executeSQL($sql, $params);
        $this->setData($data);
    }

    /**
     * Handles POST requests to create a new award.
     * 
     * @return void
     * @throws ClientError if no data is provided or if the name is not unique
     */
    protected function post(): void {
        $db = new Database("db/chi2023.sqlite");
        
        // Accept JSON data
        $request_body = file_get_contents("php://input");
        $request_body = json_decode($request_body, true);

        // Does the request contain data?
        if ($request_body === null) {
            throw new ClientError("No data provided", 400);
        }
        
        // Validate the name
        if (array_key_exists("name", $request_body)) {
            $name = $request_body["name"];
        } else {
            throw new ClientError("Name is required", 400);
        }

        // Check if the award name is unique
        $sql = "SELECT COUNT(*) AS count FROM award WHERE name = :name";
        $params = [":name" => $name];
        $check_data = $db->executeSQL($sql, $params);
        // If the count is greater than 0, the name is not unique
        if ($check_data[0]["count"] > 0) {
            throw new ClientError("Award name already exists", 409);
            return;
        }

        // Insert new award
        $sql = "INSERT INTO award (name) 
                VALUES (:name)";
        $params = [":name" => $name];

        $db->executeSQL($sql, $params);
        http_response_code(201); // Created
    }

    /**
     * Handles PATCH requests to update the name of an existing award.
     * 
     * @return void
     * @throws ClientError if no data is provided, if the award_id or name is missing, or if the name is not unique
     */
    protected function patch(): void {
        $db = new Database("db/chi2023.sqlite");
        
        // Accept JSON data
        $request_body = file_get_contents("php://input");
        $request_body = json_decode($request_body, true);

        // Does the request contain data?
        if ($request_body === null) {
            throw new ClientError("No data provided", 400);
        }
        
        // Validate the award_id and name
        if (array_key_exists("award_id", $request_body) && array_key_exists("name", $request_body)) {
            $award_id = $request_body["award_id"];
            $name = $request_body["name"];
        } else {
            throw new ClientError("Award_id and name are required", 400);
        }

        // Check if the award name is unique
        $sql = "SELECT COUNT(*) AS count FROM award WHERE name = :name AND id != :award_id";
        $params = [":name" => $name, ":award_id" => $award_id];
        $check_data = $db->executeSQL($sql, $params);
        // If the count is greater than 0, the name is not unique
        if ($check_data[0]["count"] > 0) {
            throw new ClientError("Award name already exists", 409);
            return;
        }

        // Update award name
        $sql = "UPDATE award 
                SET name = :name 
                WHERE id = :award_id";
        $params = [":name" => $name, ":award_id" => $award_id];

        $db->executeSQL($sql, $params);
        http_response_code(200); // OK
    }

    /**
     * Handles DELETE requests to delete an award.
     * 
     * @return void
     * @throws ClientError if no data is provided or if the award_id is missing
     */
    protected function delete(): void {
        $db = new Database("db/chi2023.sqlite");
        
        // Accept JSON data
        $request_body = file_get_contents("php://input");
        $request_body = json_decode($request_body, true);

        // Does the request contain data?
        if ($request_body === null) {
            throw new ClientError("No data provided", 400);
        }
        
        // Validate the award_id
        if (array_key_exists("award_id", $request_body)) {
            $award_id = $request_body["award_id"];
        } else {
            throw new ClientError("Award_id is required", 400);
        }

        // Delete award
        $sql = "DELETE FROM award 
                WHERE id = :award_id";
        $params = [":award_id" => $award_id];

        $db->executeSQL($sql, $params);
        http_response_code(200); // OK
    }
}