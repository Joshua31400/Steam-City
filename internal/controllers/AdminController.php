<?php
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/internal/models/User.php';
require_once BASE_PATH . '/internal/models/Game.php';
require_once BASE_PATH . '/internal/middleware/AuthMiddleware.php';
require_once BASE_PATH . '/internal/helpers/functions.php';
require_once BASE_PATH . '/internal/helpers/validation.php';

class AdminController {
    private $db;
    private $userModel;
    private $gameModel;
    private $achievementModel;

    // Constructor to initialize database connection and models
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new User($this->db);
        $this->gameModel = new Game($this->db);
        $this->achievementModel = new Achievement($this->db);
    }

    // Display the admin dashboard with user and game management tables
    public function showDashboard() {
        AuthMiddleware::requireAdmin();

        $users = $this->userModel->getAll();
        $games = $this->gameModel->getAll();
        $achievements = $this->achievementModel->getAll();
        $totalUsers = count($users);
        $totalGames = count($games);

        require PUBLIC_PATH . '/pages/admin.php';
    }

    // User CRUD operations for admin management
    public function createUser() {
        AuthMiddleware::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin');
        }

        $username = sanitizeInput($_POST['username'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = sanitizeInput($_POST['role'] ?? 'user');

        if (!validateEmail($email) || !validatePassword($password) || !validateRequired($username)) {
            $_SESSION['error'] = 'Invalid data';
            redirect('/admin');
        }

        $this->userModel->username = $username;
        $this->userModel->email = $email;
        $this->userModel->password = $password;
        $this->userModel->role = $role;

        if ($this->userModel->create()) {
            $_SESSION['success'] = 'User created successfully';
        } else {
            $_SESSION['error'] = 'Error creating user';
        }

        redirect('/admin');
    }

    // Update user details (except password) from the admin dashboard
    public function updateUser() {
        AuthMiddleware::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin');
        }

        $userId = $_POST['user_id'] ?? null;
        $username = sanitizeInput($_POST['username'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $role = sanitizeInput($_POST['role'] ?? 'user');

        if (!$userId || !validateEmail($email) || !validateRequired($username)) {
            $_SESSION['error'] = 'Invalid data';
            redirect('/admin');
        }

        $this->userModel->id = $userId;
        $this->userModel->username = $username;
        $this->userModel->email = $email;
        $this->userModel->role = $role;

        if ($this->userModel->update()) {
            $_SESSION['success'] = 'User updated successfully';
        } else {
            $_SESSION['error'] = 'Error updating user';
        }

        redirect('/admin');
    }

    // Delete a user from the admin dashboard, with a check to prevent deleting oneself
    public function deleteUser() {
        AuthMiddleware::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin');
        }

        $userId = $_POST['user_id'] ?? null;

        if (!$userId || $userId == $_SESSION['user_id']) {
            $_SESSION['error'] = 'Cannot delete yourself';
            redirect('/admin');
        }

        if ($this->userModel->delete($userId)) {
            $_SESSION['success'] = 'User deleted successfully';
        } else {
            $_SESSION['error'] = 'Error deleting user';
        }

        redirect('/admin');
    }

    // Game CRUD operations for admin management
    public function createGame() {
        AuthMiddleware::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin');
        }

        $name = sanitizeInput($_POST['name'] ?? '');
        $description = sanitizeInput($_POST['description'] ?? '');
        $type = sanitizeInput($_POST['type'] ?? '');
        $imageUrl = sanitizeInput($_POST['image_url'] ?? '');

        if (!validateRequired($name) || !validateRequired($description) || !validateRequired($type)) {
            $_SESSION['error'] = 'Invalid data';
            redirect('/admin');
        }

        $this->gameModel->name = $name;
        $this->gameModel->description = $description;
        $this->gameModel->type = $type;
        $this->gameModel->image_url = $imageUrl;

        if ($this->gameModel->create()) {
            $_SESSION['success'] = 'Game created successfully';
        } else {
            $_SESSION['error'] = 'Error creating game';
        }

        redirect('/admin');
    }

    // Update game details from the admin dashboard
    public function updateGame() {
        AuthMiddleware::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin');
        }

        $gameId = $_POST['game_id'] ?? null;
        $name = sanitizeInput($_POST['name'] ?? '');
        $description = sanitizeInput($_POST['description'] ?? '');
        $type = sanitizeInput($_POST['type'] ?? '');
        $imageUrl = sanitizeInput($_POST['image_url'] ?? '');

        if (!$gameId || !validateRequired($name) || !validateRequired($description)) {
            $_SESSION['error'] = 'Invalid data';
            redirect('/admin');
        }

        $this->gameModel->id = $gameId;
        $this->gameModel->name = $name;
        $this->gameModel->description = $description;
        $this->gameModel->type = $type;
        $this->gameModel->image_url = $imageUrl;

        if ($this->gameModel->update()) {
            $_SESSION['success'] = 'Game updated successfully';
        } else {
            $_SESSION['error'] = 'Error updating game';
        }

        redirect('/admin');
    }

    // Delete a game from the admin dashboard
    public function deleteGame() {
        AuthMiddleware::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin');
        }

        $gameId = $_POST['game_id'] ?? null;

        if (!$gameId) {
            $_SESSION['error'] = 'Invalid game';
            redirect('/admin');
        }

        if ($this->gameModel->delete($gameId)) {
            $_SESSION['success'] = 'Game deleted successfully';
        } else {
            $_SESSION['error'] = 'Error deleting game';
        }

        redirect('/admin');
    }

    public function createAchievement() {
        AuthMiddleware::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin');
        }

        $gameId = $_POST['game_id'] ?? null;
        $name = sanitizeInput($_POST['name'] ?? '');
        $description = sanitizeInput($_POST['description'] ?? '');
        $iconUrl = sanitizeInput($_POST['icon_url'] ?? '');

        if (!$gameId || !validateRequired($name)) {
            $_SESSION['error'] = 'Game and achievement name are required';
            redirect('/admin');
        }

        // Vérifier que le jeu existe
        $game = $this->gameModel->getById($gameId);
        if (!$game) {
            $_SESSION['error'] = 'Invalid game selected';
            redirect('/admin');
        }

        $query = "INSERT INTO achievements (game_id, name, description, icon_url) 
              VALUES (:game_id, :name, :description, :icon_url)";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':game_id', $gameId);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':icon_url', $iconUrl);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Achievement created successfully';
        } else {
            $_SESSION['error'] = 'Error creating achievement';
        }

        redirect('/admin');
    }

    public function updateAchievement() {
        AuthMiddleware::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin');
        }

        $achievementId = $_POST['achievement_id'] ?? null;
        $gameId = $_POST['game_id'] ?? null;
        $name = sanitizeInput($_POST['name'] ?? '');
        $description = sanitizeInput($_POST['description'] ?? '');
        $iconUrl = sanitizeInput($_POST['icon_url'] ?? '');

        if (!$achievementId || !$gameId || !validateRequired($name)) {
            $_SESSION['error'] = 'Invalid data';
            redirect('/admin');
        }

        $query = "UPDATE achievements 
              SET game_id = :game_id, name = :name, description = :description, icon_url = :icon_url 
              WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':game_id', $gameId);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':icon_url', $iconUrl);
        $stmt->bindParam(':id', $achievementId);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Achievement updated successfully';
        } else {
            $_SESSION['error'] = 'Error updating achievement';
        }

        redirect('/admin');
    }

    public function deleteAchievement() {
        AuthMiddleware::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin');
        }

        $achievementId = $_POST['achievement_id'] ?? null;

        if (!$achievementId) {
            $_SESSION['error'] = 'Invalid achievement';
            redirect('/admin');
        }

        $query = "DELETE FROM achievements WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $achievementId);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Achievement deleted successfully';
        } else {
            $_SESSION['error'] = 'Error deleting achievement';
        }

        redirect('/admin');
    }
}
