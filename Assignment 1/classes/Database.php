<?php
/**
 * Database
 * 
 * Uses PDO to connect to a SQLite database and execute SQL statements.
 * 
 * @version v1.0.0
 * @author Joshua White
 */

class Database
{
    private $dbConnection;

    /**
     * Constructor to set up the database connection.
     * 
     * @param string $dbName The name of the SQLite database file.
     */
    public function __construct($dbName) 
    {
        $this->setDbConnection($dbName);  
    }

    /**
     * Set up the database connection.
     * 
     * @param string $dbName The name of the SQLite database file.
     * @throws Exception if the database connection fails.
     */
    private function setDbConnection($dbName) 
    {
        try {
            $this->dbConnection = new PDO('sqlite:' . $dbName);
            $this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Execute an SQL statement.
     * 
     * @param string $sql The SQL statement to execute.
     * @param array $params The parameters to bind to the SQL statement.
     * @return array The result set as an associative array.
     * @throws Exception if the SQL execution fails.
     */
    public function executeSQL($sql, $params = [])
    { 
        try {
            $stmt = $this->dbConnection->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("SQL execution failed: " . $e->getMessage());
        }
    }
}