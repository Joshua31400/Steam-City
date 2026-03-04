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

    // Get game details for a specific game in the user's library, including achievements and stats, to display in a modal on the profile page
    // This method is called via AJAX when the user clicks on a game in their library to view details with json response
    public function getGameDetails() {
        AuthMiddleware::requireAuth();

        header('Content-Type: application/json');

        $userGameId = $_GET['id'] ?? null;

        if (!$userGameId) {
            echo json_encode(['error' => 'Invalid game ID']);
            exit;
        }

        try {
            // Get game details from user_games and games tables
            $query = "SELECT 
                    ug.id as user_game_id,
                    ug.start_date,
                    ug.play_time,
                    ug.death_date,
                    ug.added_at,
                    g.id as game_id,
                    g.name as game_name,
                    g.description,
                    g.type,
                    g.image_url
                  FROM user_games ug
                  JOIN games g ON ug.game_id = g.id
                  WHERE ug.id = :user_game_id AND ug.user_id = :user_id
                  LIMIT 1";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_game_id', $userGameId);
            $stmt->bindParam(':user_id', $_SESSION['user_id']);
            $stmt->execute();

            $gameDetails = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$gameDetails) {
                echo json_encode(['error' => 'Game not found']);
                exit;
            }

            // Get user achievements for this game
            $achievementQuery = "SELECT 
                                a.id,
                                a.name,
                                a.description,
                                a.icon_url,
                                ua.unlocked_at,
                                CASE WHEN ua.id IS NOT NULL THEN 1 ELSE 0 END as unlocked
                             FROM achievements a
                             LEFT JOIN user_achievements ua ON a.id = ua.achievement_id AND ua.user_id = :user_id
                             WHERE a.game_id = :game_id
                             ORDER BY unlocked DESC, a.name ASC";

            $achStmt = $this->db->prepare($achievementQuery);
            $achStmt->bindParam(':user_id', $_SESSION['user_id']);
            $achStmt->bindParam(':game_id', $gameDetails['game_id']);
            $achStmt->execute();

            $achievements = $achStmt->fetchAll(PDO::FETCH_ASSOC);

            // Calculate achievement stats for the game
            $totalAchievements = count($achievements);
            $unlockedAchievements = count(array_filter($achievements, function($a) {
                return $a['unlocked'] == 1;
            }));
            $achievementPercentage = $totalAchievements > 0 ? round(($unlockedAchievements / $totalAchievements) * 100) : 0;

            // Construct response with game details, achievements, and stats
            $response = [
                'success' => true,
                'game' => $gameDetails,
                'achievements' => $achievements,
                'stats' => [
                    'total_achievements' => $totalAchievements,
                    'unlocked_achievements' => $unlockedAchievements,
                    'achievement_percentage' => $achievementPercentage,
                    'days_played' => $this->calculateDaysPlayed($gameDetails['start_date']),
                    'last_death' => $gameDetails['death_date'] ? date('d/m/Y', strtotime($gameDetails['death_date'])) : 'Still alive'
                ]
            ];

            echo json_encode($response);
            exit;

        } catch (Exception $e) {
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }

// Calculate the number of days the user has been playing a game based on the start date
    private function calculateDaysPlayed($startDate) {
        $start = new DateTime($startDate);
        $now = new DateTime();
        $diff = $start->diff($now);
        return $diff->days;
    }
}
