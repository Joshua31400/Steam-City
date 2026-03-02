<?php
// This file is the entry point for the application. It sets up the environment and routes requests to the appropriate handlers.

// If the built-in PHP server is being used, we need to check if the requested file exists in the public directory and serve it directly if it does.
// This allows us to serve static assets like CSS, JS, and images without routing them through our application logic.
if (php_sapi_name() === 'cli-server') {
    // Verify if the requested file exists in the public directory
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
}

require_once dirname(__DIR__) . '/config/constants.php';
require_once BASE_PATH . '/internal/routes/routes.php';