<?php
// UserGame model class to handle all database interactions related to user_games table
// This table links users to their games with play statistics
class UserGame {
    private $conn;
    private $table = 'user_games';

    public $id;
    public $user_id;
    public $game_id;
    public $start_date;
    public $play_time;

    // Constructor to initialize the database connection
    // Usage: $userGame = new UserGame($db);
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all games for a specific user (for profile page)
    // Usage: $games = $userGame->getByUserId($userId);
    public function getByUserId($userId) {
        $query = "SELECT ug.*, g.name, g.description, g.type, g.image_url 
                  FROM " . $this->table . " ug
                  JOIN games g ON ug.game_id = g.id
                  WHERE ug.user_id = :user_id
                  ORDER BY ug.added_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Get a specific game for a user relation by ID
    // Usage: $relation = $userGame->getById($id);
    public function getById($id) {
        $query = "SELECT ug.*, g.name, g.description, g.type, g.image_url 
                  FROM " . $this->table . " ug
                  JOIN games g ON ug.game_id = g.id
                  WHERE ug.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch();
    }

    // Check if a user already has a specific game
    // Usage: if ($userGame->exists($userId, $gameId)) { ... }
    public function exists($userId, $gameId) {
        $query = "SELECT id FROM " . $this->table . " 
                  WHERE user_id = :user_id AND game_id = :game_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':game_id', $gameId);
        $stmt->execute();

        return $stmt->fetch() !== false;
    }

    // Add a game to user's library with random start_date, play_time
    // For testing purposes, Admin can add games with random stats to users
    // Usage: $userGame->create();
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (user_id, game_id, start_date, play_time) 
                  VALUES (:user_id, :game_id, :start_date, :play_time)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':game_id', $this->game_id);
        $stmt->bindParam(':start_date', $this->start_date);
        $stmt->bindParam(':play_time', $this->play_time);

        return $stmt->execute();
    }

    // Update play time or dates for a user's game
    // For admin dashboard, we can update play time and dates for testing purposes
    // Usage: $userGame->update();
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET start_date = :start_date, play_time = :play_time 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':start_date', $this->start_date);
        $stmt->bindParam(':play_time', $this->play_time);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    // Remove a game from user's library
    // For user profile or admin dashboard
    // Usage: $userGame->delete($id);
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Count total games for a user (for stats)
    // Usage: $count = $userGame->countByUser($userId);
    public function countByUser($userId) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        $result = $stmt->fetch();
        return $result['total'];
    }
}
