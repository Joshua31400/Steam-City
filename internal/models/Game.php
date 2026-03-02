<?php
// Game model class to handle all database interactions related to games table
class Game {
    private $conn;
    private $table = 'games';

    public $id;
    public $name;
    public $description;
    public $type;
    public $image_url;

    // Constructor to initialize the database connection with stored $conn variable for use in all methods
    // Usage: $game = new Game($db);
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all games for listing on homepage and dashboard
    // Usage: $games = $game->getAll();
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Get a single game by ID for future details pop-up
    // Usage: $gameData = $game->getById($id);
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch();
    }

    // Create a new game in the database for admin dashboard only
    // Usage: $game->create();
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (name, description, type, image_url) 
                  VALUES (:name, :description, :type, :image_url)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':type', $this->type);
        // For simplicity, we allow admins to provide an image URL directly. In a real app, you would handle file uploads and storage.
        // URL: /assets/images/games/filename.png .jpg .jpeg .webp
        $stmt->bindParam(':image_url', $this->image_url);

        return $stmt->execute();
    }

    // Update an existing game in the database for admin dashboard only
    // Usage: $game->update();
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET name = :name, description = :description, 
                      type = :type, image_url = :image_url 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':image_url', $this->image_url);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // Delete a game by id from the database for admin dashboard only
    // Usage: $game->delete($id);
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}