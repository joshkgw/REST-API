<?php
/**
 * Manager class
 * 
 * Handles requests to manage awards for content items.
 * 
 * @version 1.0.0
 * @author Joshua White
 */

class Manager extends Endpoint
{
    /**
     * Handles POST requests to give an award to a content item.
     * 
     * @return void
     * @throws ClientError if no data is provided
     * @throws ClientError if the content_id or award_id is missing
     * @throws ClientError if the content already has an award
     * @throws ClientError if the content_id or award_id does not exist
     */
    protected function post(): void {
        $db = new Database($this->env['db']); // Use the database name from env.php
        
        // Accept JSON data
        $request_body = file_get_contents("php://input");
        $request_body = json_decode($request_body, true);

        // Does the request contain data?
        if ($request_body === null) {
            throw new ClientError("No data provided", 400);
        }
        
        // Are the parameters valid?
        if (array_key_exists("content_id", $request_body) && array_key_exists("award_id", $request_body)) {
            $content_id = $request_body["content_id"];
            $award_id = $request_body["award_id"];
        } else {
            throw new ClientError("Content_id and award_id are required", 400);
        }

        $this->checkContentExists($db, $content_id);
        $this->checkAwardExists($db, $award_id);
        // Check content does not already have an award
        $this->checkPreviousAward($db, $content_id, false);

        // Give award to content
        $sql = "INSERT INTO content_has_award (content, award) 
                VALUES (:content_id, :award_id)";
        $params = [":content_id" => $content_id, ":award_id" => $award_id];

        $db->executeSQL($sql, $params);
        http_response_code(201); // Created
    }

    
    /**
     * Handles DELETE requests to take away an award from a content item.
     * 
     * @return void
     * @throws ClientError if no data is provided 
     * @throws ClientError if the content_id is missing
     * @throws ClientError if the content does not have an award
     */
    protected function delete(): void {
        $db = new Database($this->env['db']); // Use the database name from env.php
        
        // Accept JSON data
        $request_body = file_get_contents("php://input");
        $request_body = json_decode($request_body, true);

        // Does the request contain data?
        if ($request_body === null) {
            throw new ClientError("No data provided", 400);
        }
        
        // Are the parameters valid?
        if (array_key_exists("content_id", $request_body)) {
            $content_id = $request_body["content_id"];
        } else {
            throw new ClientError("Content_id is required", 400);
        }

        $this->checkContentExists($db, $content_id);
        // Check content has an award to delete
        $this->checkPreviousAward($db, $content_id, true); 

        // Delete the reward from the content
        $sql = "DELETE FROM content_has_award 
                WHERE content = :content_id";
        $params = [":content_id" => $content_id];

        $db->executeSQL($sql, $params);
        http_response_code(200); // OK
    }


    /**
     * Checks if the content already has an award.
     * 
     * @param Database $db The database connection.
     * @param int $content_id The content ID to check.
     * @param bool $shouldHaveAward Whether the content should have an award.
     * @return void
     * @throws ClientError if the content already has an award or does not have an award when it should
     */
    private function checkPreviousAward(Database $db, int $content_id, bool $shouldHaveAward): void {
        $sql = "SELECT COUNT(*) AS count 
                FROM content_has_award 
                WHERE content = :content_id";
        $params = [":content_id" => $content_id];
        $check_data = $db->executeSQL($sql, $params);
        // If it has an award but it should not, throw an error
        if ($check_data[0]["count"] > 0 && !$shouldHaveAward) {
            throw new ClientError("Content already has an award", 409);
        }
        // If it does not have an award but it should, throw an error
        if ($check_data[0]["count"] == 0 && $shouldHaveAward) {
            throw new ClientError("Content does not have an award", 404);
        }
    }


    /**
     * Checks the content_id exists.
     * 
     * @param Database $db The database connection.
     * @param int $content_id The content ID to validate.
     * @return void
     * @throws ClientError if the content_id does not exist
     */
    private function checkContentExists(Database $db, int $content_id): void {
        $sql = "SELECT COUNT(*) AS count FROM content WHERE id = :content_id";
        $params = [":content_id" => $content_id];
        $check_data = $db->executeSQL($sql, $params);
        // If no such content exists, throw an error
        if ($check_data[0]["count"] == 0) {
            throw new ClientError("Invalid content_id", 404);
        }
    }


    /**
     * Checks the award_id exists.
     * 
     * @param Database $db The database connection.
     * @param int $award_id The award ID to validate.
     * @return void
     * @throws ClientError if the award_id does not exist
     */
    private function checkAwardExists(Database $db, int $award_id): void {
        $sql = "SELECT COUNT(*) AS count FROM award WHERE id = :award_id";
        $params = [":award_id" => $award_id];
        $check_data = $db->executeSQL($sql, $params);
        // If no award exists, throw an error
        if ($check_data[0]["count"] == 0) {
            throw new ClientError("Invalid award_id", 404);
        }
    }
}