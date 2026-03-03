<?php
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/internal/controllers/AuthController.php';
require_once BASE_PATH . '/internal/controllers/GameController.php';
require_once BASE_PATH . '/internal/controllers/ProfileController.php';
require_once BASE_PATH . '/internal/controllers/AdminController.php';
require_once BASE_PATH . '/internal/controllers/OAuthController.php';

// Get the current request URI and remove query parameters for routing
$request = $_SERVER['REQUEST_URI'] ?? '/';
$request = parse_url($request, PHP_URL_PATH) ?? '/'; // Remove query string for cleaner routing

// Routes mapping
switch ($request) {
    // AUTH ROUTES
    case '/':
    case '/login':
        $controller = new AuthController();
        $controller->showLogin();
        break;

    case '/login/process':
        $controller = new AuthController();
        $controller->login();
        break;

    case '/signup':
        $controller = new AuthController();
        $controller->showSignIn();
        break;

    case '/register':
        $controller = new AuthController();
        $controller->register();
        break;

    case '/logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    // OAUTH ROUTES
    case '/auth/google':
        $controller = new OAuthController();
        $controller->redirectToGoogle();
        break;

    case '/auth/google/callback':
        $controller = new OAuthController();
        $controller->handleGoogleCallback();
        break;

    case '/auth/github':
        $controller = new OAuthController();
        $controller->redirectToGithub();
        break;

    case '/auth/github/callback':
        $controller = new OAuthController();
        $controller->handleGithubCallback();
        break;

    // HOME & LIBRARY ROUTES
    case '/home':
        $controller = new GameController();
        $controller->showHome();
        break;

    case '/game/add':
        $controller = new GameController();
        $controller->addToLibrary();
        break;

    // PROFILE ROUTES
    case '/profile':
        $controller = new ProfileController();
        $controller->showProfile();
        break;

    case '/profile/update':
        $controller = new ProfileController();
        $controller->updateProfile();
        break;

    case '/profile/game/remove':
        $controller = new GameController();
        $controller->removeFromLibrary();
        break;

    // ADMIN ROUTES
    case '/admin':
        $controller = new AdminController();
        $controller->showDashboard();
        break;

    case '/admin/users':
        $controller = new AdminController();
        $controller->manageUsers();
        break;

    case '/admin/games':
        $controller = new AdminController();
        $controller->manageGames();
        break;

    // ADMIN USER CRUD
    case '/admin/user/create':
        $controller = new AdminController();
        $controller->createUser();
        break;

    case '/admin/user/update':
        $controller = new AdminController();
        $controller->updateUser();
        break;

    case '/admin/user/delete':
        $controller = new AdminController();
        $controller->deleteUser();
        break;

    // ADMIN GAME CRUD
    case '/admin/game/create':
        $controller = new AdminController();
        $controller->createGame();
        break;

    case '/admin/game/update':
        $controller = new AdminController();
        $controller->updateGame();
        break;

    case '/admin/game/delete':
        $controller = new AdminController();
        $controller->deleteGame();
        break;

    // PROFILE GAME EDIT
    case '/profile/game/edit':
        $controller = new ProfileController();
        $controller->editUserGame();
        break;

    // ERRORS
    default:
        http_response_code(404);
        echo '404 - Page not found';
        break;
}