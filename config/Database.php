<?php
/*
 * Database Configuration Class
 * Establishes connection to 'dbantuan'.
 */

class Database {
    // Database connection parameters
    private $host = "localhost";      // The hostname of the database server
    private $db_name = "dbantuan";    // The specific database name required for this project
    private $username = "root";       // The database username (default: root)
    private $password = "";           // The database password (default: empty)
    public $conn;                     // Property to hold the connection instance

    /*
     * Get Database Connection
     * * Tries to establish a new PDO connection using the defined credentials.
     * Sets the error mode to Exception to handle potential query errors effectively.
     * * @return PDO|null Returns the connection object if successful, or null on failure.
     */
    public function getConnection() {
        $this->conn = null;
        try {
            // Create a new PDO instance with the Data Source Name (DSN)
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            // Set the PDO error mode to exception for better error reporting
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            // Catch and display any connection errors
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
