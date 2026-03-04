<?php
require_once BASE_PATH . '/config/oauth.php';
require_once BASE_PATH . '/config/database.php';

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    // Constructor to initialize database connection parameters
    public function __construct() {
        $this->host = env('DB_HOST', 'localhost');
        $this->db_name = env('DB_NAME', 'steam_city');
        $this->username = env('DB_USER', 'root');
        $this->password = env('DB_PASS', '');
    }

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
