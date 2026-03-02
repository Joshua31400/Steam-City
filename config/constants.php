<?php
// Path for call files from the page HTML without full path (called in the header of each page)
define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', BASE_PATH . '/public');

// Charge Oauth config (used in OAuthController and providers)
require_once BASE_PATH . '/config/oauth.php';

// Security
define('PASSWORD_MIN_LENGTH', 8);

// Session to store user data across pages (called in the header of each page)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}