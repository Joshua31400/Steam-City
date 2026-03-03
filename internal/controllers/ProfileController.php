<?php
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/internal/models/User.php';
require_once BASE_PATH . '/internal/models/Game.php';
require_once BASE_PATH . '/internal/models/UserGame.php';
require_once BASE_PATH . '/internal/models/Achievement.php';
require_once BASE_PATH . '/internal/middleware/AuthMiddleware.php';
require_once BASE_PATH . '/internal/helpers/functions.php';
require_once BASE_PATH . '/internal/helpers/validation.php';

// ProfileController to handle user profile related actions like displaying profile, editing playtime, etc.
class ProfileController {
    private $db;
    private $userModel;
    private $userGameModel;
    private $achievementModel;

    // Initialize database connection and models
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new User($this->db);
        $this->userGameModel = new UserGame($this->db);
        $this->achievementModel = new Achievement($this->db);
    }

    // Display the user's profile page with their games and achievements
    public function showProfile() {
        AuthMiddleware::requireAuth();

        $userId = $_SESSION['user_id'];

        // Prépare data for the profile view
        $user = $this->userModel->getById($userId);
        $userGames = $this->userGameModel->getByUserId($userId);
        $userAchievements = $this->achievementModel->getUserAchievements($userId);

        require PUBLIC_PATH . '/pages/profile.php';
    }

    // Edit the playtime of a game in the user's library from the profile page
    public function editUserGame() {
        AuthMiddleware::requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/profile');
        }

        $userGameId = $_POST['user_game_id'] ?? null;
        $playTime = $_POST['play_time'] ?? null;

        if (!$userGameId || !$playTime) {
            $_SESSION['error'] = 'Invalid data';
            redirect('/profile');
        }

        if ($this->userGameModel->updatePlayTime($userGameId, $_SESSION['user_id'], $playTime)) {
            $_SESSION['success'] = 'Game updated successfully';
        } else {
            $_SESSION['error'] = 'Error updating game';
        }

        redirect('/profile');
    }

    // Update the user's profile information (username, email) from the profile page
    public function updateProfile() {
        AuthMiddleware::requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/profile');
        }

        $username = sanitizeInput($_POST['username'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');

        if (!validateRequired($username) || !validateEmail($email)) {
            $_SESSION['error'] = 'Invalid data';
            redirect('/profile');
        }

        $this->userModel->email = $email;
        $existingUser = $this->userModel->findByEmail();

        if ($existingUser && $existingUser['id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'This email is already used by another account';
            redirect('/profile');
        }

        $this->userModel->id = $_SESSION['user_id'];
        $this->userModel->username = $username;
        $this->userModel->email = $email;
        $this->userModel->role = $_SESSION['role'];

        if ($this->userModel->update()) {
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;

            $_SESSION['success'] = 'Profile updated successfully';
        } else {
            $_SESSION['error'] = 'Error updating profile';
        }

        redirect('/profile');
    }
}
