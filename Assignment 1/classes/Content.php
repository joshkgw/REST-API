<?php
/**
 * Content class
 * 
 * This class is used to handle requests for content data.  
 * 
 * @version v1.0.0
 * @author Joshua White
 */

class Content extends Endpoint
{
    /**
     * Handles GET requests to retrieve a list of content.
     * 
     * @return void
     * @throws ClientError if an invalid parameter is provided
     */
    protected function get(): void {
        $db = new Database($this->env['db']); // Use the database name from env.php
        $sql = "SELECT DISTINCT content.id AS content_id, 
                content.title, 
                content.abstract, 
                content.doi_link, 
                content.preview_video,
                type.name AS type, 
                award.name AS award
            FROM content
            LEFT JOIN type ON content.type = type.id
            LEFT JOIN content_has_award ON content.id = content_has_award.content
            LEFT JOIN award ON content_has_award.award = award.id
            LEFT JOIN content_has_author ON content.id = content_has_author.content";
        $params = [];
 
        // Validate parameters 
        foreach ($_GET as $key => $value) {
            $key = strtolower($key);
            if (!in_array($key, ["content_id", "author_id", "search", "page"])) {
                throw new ClientError("Invalid parameter: $key", 400);
            }
        }
 
        // Handle content_id parameter
        if (isset($_GET["content_id"])) {
            // Validate content_id
            if (!is_numeric($_GET["content_id"])) {
                throw new ClientError("Invalid content_id", 400);
            }
            $sql .= " WHERE content.id = :content_id";
            $params[":content_id"] = $_GET["content_id"];
        }
 
        // Handle author_id parameter
        if (isset($_GET["author_id"])) {
            // Validate author_id
            if (!is_numeric($_GET["author_id"])) {
                throw new ClientError("Invalid author_id", 400);
            }
            if (isset($_GET["content_id"])) {
                $sql .= " AND";
            } else {
                $sql .= " WHERE";
            }
            $sql .= " content_has_author.author = :author_id";
            $params[":author_id"] = $_GET["author_id"];
        }

        // Handle search parameter
        if (isset($_GET["search"])) {
            // Validate search
            if (is_numeric($_GET["search"])) {
                throw new ClientError("Invalid search", 406);
            }
            if (isset($_GET["content_id"]) || isset($_GET["author_id"])) {
                $sql .= " AND";
            } else {
                $sql .= " WHERE";
            }
            $sql .= " (LOWER(content.title) LIKE LOWER(:search) OR LOWER(content.abstract) LIKE LOWER(:search))";
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