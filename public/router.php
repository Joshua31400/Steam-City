<?php
/**
 * Router for PHP Built-in Server
 * Handles static files and routes dynamic requests to index.php
 */

// Get the requested file path
$requested_file = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// If the requested file exists and is not a directory, serve it
if (file_exists($requested_file) && !is_dir($requested_file)) {
    return false; // Let PHP built-in server handle the static file
}

// Otherwise, route everything to index.php
require_once __DIR__ . '/index.php';
