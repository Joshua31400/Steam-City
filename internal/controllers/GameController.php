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

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->gameModel = new Game($this->db);
        $this->userGameModel = new UserGame($this->db);
    }

    // Afficher la page home avec tous les jeux
    public function showHome() {
        AuthMiddleware::requireAuth();

        $games = $this->gameModel->getAll();

        // Passer les données aux vues (tu devras modifier tes HTML en PHP)
        require PUBLIC_PATH . '/pages/home.html';
    }

    // Ajouter un jeu à la bibliothèque d'un utilisateur
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

        // Générer des données random
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

    // Supprimer un jeu de la bibliothèque
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