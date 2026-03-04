<?php
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/internal/models/Game.php';
require_once BASE_PATH . '/internal/models/UserGame.php';
require_once BASE_PATH . '/internal/middleware/AuthMiddleware.php';
require_once BASE_PATH . '/internal/helpers/functions.php';

class GameController {
    private $db;
    private $gameModel;
    private $userGameModel;
    // Initialize database connection and models
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->gameModel = new Game($this->db);
        $this->userGameModel = new UserGame($this->db);
    }

    // Display the home page with the list of games
    public function showHome() {
        AuthMiddleware::requireAuth();

        $games = $this->gameModel->getAll();

        require PUBLIC_PATH . '/pages/home.php';
    }

    // Add a game to the user's library with random data for playtime and dates
    public function addToLibrary() {
        AuthMiddleware::requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/home');
        }

        $gameId = $_POST['game_id'] ?? null;

        if (!$gameId) {
            $_SESSION['error'] = 'Jeu invalide';
            redirect('/home');
        }

        if ($this->userGameModel->exists($_SESSION['user_id'], $gameId)) {
            $_SESSION['error'] = 'Ce jeu est déjà dans votre bibliothèque';
            redirect('/profile');
        }

        $this->userGameModel->user_id = $_SESSION['user_id'];
        $this->userGameModel->game_id = $gameId;
        $this->userGameModel->start_date = generateRandomDate();
        $this->userGameModel->play_time = generateRandomPlayTime();
        $this->userGameModel->death_date = generateRandomDate();

        if ($this->userGameModel->create()) {
            $_SESSION['success'] = 'Jeu ajouté à votre bibliothèque';
        } else {
            $_SESSION['error'] = 'Erreur lors de l\'ajout';
        }

        redirect('/profile');
    }

    // Delete a game from the user's library
    public function removeFromLibrary() {
        AuthMiddleware::requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/profile');
        }

        $userGameId = $_POST['user_game_id'] ?? null;

        if ($this->userGameModel->delete($userGameId, $_SESSION['user_id'])) {
            $_SESSION['success'] = 'Jeu supprimé';
        } else {
            $_SESSION['error'] = 'Erreur lors de la suppression';
        }

        redirect('/profile');
    }
}