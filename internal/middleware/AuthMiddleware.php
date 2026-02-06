<?php
require_once BASE_PATH . '/internal/helpers/functions.php';

class AuthMiddleware {
    // Verify if the user is logged in and redirect to login if not
    // Each page that requires authentication should call this method at the beginning
    // Usage: AuthMiddleware::requireAuth();
    public static function requireAuth() {
        if (!isLoggedIn()) {
            redirect('/login');
        }
    }

    // Verify if the user is an admin and redirect to home if not
    // If the user is admin the button to access on profile page will be visible
    // Usage: AuthMiddleware::requireAdmin();
    public static function requireAdmin() {
        if (!isLoggedIn() || !isAdmin()) {
            redirect('/home');
        }
    }

    // Redirect to home if the user is already authenticated (used on login and register pages)
    // Usage: AuthMiddleware::redirectIfAuthenticated();
    public static function redirectIfAuthenticated() {
        if (isLoggedIn()) {
            redirect('/home');
        }
    }
}