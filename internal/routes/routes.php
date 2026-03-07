<?php
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/internal/controllers/AuthController.php';
require_once BASE_PATH . '/internal/controllers/GameController.php';
require_once BASE_PATH . '/internal/controllers/ProfileController.php';
require_once BASE_PATH . '/internal/controllers/AdminController.php';
require_once BASE_PATH . '/internal/controllers/OAuthController.php';

$request = $_SERVER['REQUEST_URI'] ?? '/';
$request = parse_url($request, PHP_URL_PATH) ?? '/';

$routes = array_merge(
    require __DIR__ . '/auth-routes.php',
    require __DIR__ . '/oauth-routes.php',
    require __DIR__ . '/game-routes.php',
    require __DIR__ . '/profile-routes.php',
    require __DIR__ . '/admin-routes.php'
);

$errorHandlers = require __DIR__ . '/errors/errors-routes.php';

if (isset($routes[$request])) {
    [$controllerClass, $method] = $routes[$request];
    $controller = new $controllerClass();
    $controller->$method();
} else {
    $errorHandlers[404]();
}