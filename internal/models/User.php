<?php
// User model class to handle all database interactions related to users table
class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $email;
    public $username;
    public $password;
    public $role;

    // Constructor to initialize the database connection with stored $conn variable for use in all methods
    // Usage: $user = new User($db);
    public function __construct($db) {
        $this->conn = $db;
    }

    // This method is used in login process
    // Create a new user in the database with hashed password for security
    // Usage: $user->create();
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (email, username, password, role) 
                  VALUES (:email, :username, :password, :role)";

        $stmt = $this->conn->prepare($query);

        // Method password_hash() is used to create a secure hash of the password
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $this->role);

        return $stmt->execute();
    }

    // This method is used to find a user by email during login process
    // Prepare attribs for query guarantee security and prevent SQL injection
    // Usage: $userData = $user->findByEmail();
    public function findByEmail() {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        return $stmt->fetch();
    }

    // Verify password during login compare input password with hashed password created by create() method
    // and check with password_verify() function PHP native
    // Usage: if ($user->verifyPassword($inputPassword, $userData['password'])) { ... }
    public function verifyPassword($inputPassword, $hashedPassword) {
        return password_verify($inputPassword, $hashedPassword);
    }

    // Get all users for dashboard admin (ADMIN ONLY !)
    // Usage: $users = $user->getAll();
    public function getAll() {
        $query = "SELECT id, email, username, role, created_at FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Get user by id (for user profile)
    // Usage: $user->getById($id);
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch();
    }

    // Update user data (for admin or user profile)
    // Usage: $user->update();
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET email = :email, username = :username, role = :role 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // Delete one data by id (for admin and user profile)
    // Usage: $user->delete($id);
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}