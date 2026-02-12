<?php
class Database {
    // Database connection parameters
    private $host = 'localhost';
    private $db_name = 'steam_city';
    private $username = 'root';
    private $password = 'root';
    private $conn;

    public function getConnection() {
        // Initialize the connection variable
        $this->conn = null;

        // Attempt to establish a connection to the database
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }

        return $this->conn;
    }
}