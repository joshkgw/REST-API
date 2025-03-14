<?php
/**
 * Author class
 * 
 * This class is used to handle requests for author data.
 * 
 * @version 1.0.0
 * @author Joshua White
 */

class Author extends Endpoint
{
    /**
     * Handles GET requests to retrieve a list of authors.
     * 
     * @return void
     * @throws ClientError If an invalid parameter is provided.
     */
    protected function get(): void {
        $db = new Database($this->env['db']); // Use the database name from env.php
        $sql = "SELECT DISTINCT author.id AS author_id, 
                author.name
            FROM author
            LEFT JOIN content_has_author ON author.id = content_has_author.author";
        $params = [];
 
        // Validate parameters 
        foreach ($_GET as $key => $value) {
            $key = strtolower($key);
            if (!in_array($key, ["author_id", "content_id", "search", "page"])) {
                throw new ClientError("Invalid parameter: $key", 400);
            }
        }
 
        // Handle author_id parameter
        if (isset($_GET["author_id"])) {
            // Validate author_id
            if (!is_numeric($_GET["author_id"])) {
                throw new ClientError("Invalid author_id", 400);
            }
            $sql = $sql. " WHERE id = :author_id";
            $params[":author_id"] = $_GET["author_id"];
        }
 
        // Handle content_id parameter
        if (isset($_GET["content_id"])) {
            // Validate content_id
            if (!is_numeric($_GET["content_id"])) {
                throw new ClientError("Invalid content_id", 400);
            }
            if (isset($_GET["author_id"])) {
                $sql .= " AND";
            } else {
                $sql .= " WHERE";
            }
            $sql .= " content_has_author.content = :content_id";
            $params[":content_id"] = $_GET["content_id"];
        }
        
        // Handle search parameter
        if (isset($_GET["search"])) {
            // Validate search
            if (is_numeric($_GET["search"])) {
                throw new ClientError("Invalid search", 406);
            }
            if (isset($_GET["author_id"]) || isset($_GET["content_id"])) {
                $sql .= " AND";
            } else {
                $sql .= " WHERE";
            }
            $sql .= " (LOWER(name) LIKE LOWER(:search))";
            $params[":search"] = "%" . $_GET["search"] . "%";
        }
        
        // Handle page parameter
        if (isset($_GET["page"])) {
            // Validate page
            if (!is_numeric($_GET["page"]) || $_GET["page"] <= 0) {
                throw new ClientError("Invalid page", 406);
            }
            // Ensure page is not a float
            $page = (int)$_GET["page"];
            $limit = 10; // Number of records per page
            $offset = ($page - 1) * $limit;
            $sql .= " LIMIT $limit OFFSET $offset";
        }

        $data = $db->executeSQL($sql, $params);
        $this->setData($data);
    }
}