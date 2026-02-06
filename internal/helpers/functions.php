<?php
// Redirect to a specific path and exit the script
// Usage: redirect('example.php');
function redirect($path) {
    header("Location: $path");
    exit();
}

// Sécurity XSS to escape user input before displaying it in HTML
// Usage: echo escape($userInput);
function escape($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Verify if the user is logged in without checking the database (based on session)
// Useful for quick checks in templates or controllers where you just want to know if the user is authenticated
// Usage: if (isLoggedIn()) { ... }
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if the user has an admin role based on session data
// Useful for quick role checks in templates or controllers without querying the database
// Usage: if (isAdmin()) { ... }
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Generate a random date between two given dates for testing purposes
// Usage: echo generateRandomDate('2020-01-01', '2025-12-31');
function generateRandomDate($startDate = '2020-01-01', $endDate = '2025-12-31') {
    $timestamp = mt_rand(strtotime($startDate), strtotime($endDate));
    return date('Y-m-d', $timestamp);
}

// Generate a random play time in minutes for testing purposes
// Usage: echo generateRandomPlayTime(1, 500);
function generateRandomPlayTime($min = 1, $max = 500) {
    return mt_rand($min, $max);
}