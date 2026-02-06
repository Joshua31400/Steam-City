<?php
// Achievement model class to handle all database interactions related to achievements table
// Achievements are linked to games and can be unlocked by users
class Achievement {
    private $conn;
    private $table = 'achievements';

    public $id;
    public $game_id;
    public $name;
    public $description;
    public $icon_url;

    // Constructor to initialize the database connection
    // Usage: $achievement = new Achievement($db);
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all achievements for a specific game
    // Usage: $achievements = $achievement->getByGameId($gameId);
    public function getByGameId($gameId) {
        $query = "SELECT * FROM " . $this->table . " WHERE game_id = :game_id ORDER BY name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':game_id', $gameId);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Get a single achievement by ID
    // This is used for admin editing and details pop-up
    // Usage: $data = $achievement->getById($id);
    public function getById($id) {
        $query = "SELECT a.*, g.name as game_name 
                  FROM " . $this->table . " a
                  JOIN games g ON a.game_id = g.id
                  WHERE a.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch();
    }

    // Get all achievements (for admin)
    // Usage: $all = $achievement->getAll();
    public function getAll() {
        $query = "SELECT a.*, g.name as game_name 
                  FROM " . $this->table . " a
                  JOIN games g ON a.game_id = g.id
                  ORDER BY g.name, a.name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Create a new achievement (admin only)
    // Usage: $achievement->create();
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (game_id, name, description, icon_url) 
                  VALUES (:game_id, :name, :description, :icon_url)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':game_id', $this->game_id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':icon_url', $this->icon_url);

        return $stmt->execute();
    }

    // Update an existing achievement (admin only)
    // Usage: $achievement->update();
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET game_id = :game_id, name = :name, 
                      description = :description, icon_url = :icon_url 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':game_id', $this->game_id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':icon_url', $this->icon_url);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // Delete an achievement (admin only)
    // Usage: $achievement->delete($id);
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}
