<?php
// Redirect to a specific path and exit the script
// Usage: redirect('example.php');
function redirect($path) {
    header("Location: $path");
    exit();
}

// Sécurité XSS
// Usage: echo escape($userInput);
function escape($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Vérifier si l'utilisateur est connecté
// Usage: if (isLoggedIn()) { ... }
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Vérifier si l'utilisateur est admin
// Usage: if (isAdmin()) { ... }
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Générer une date random
// Usage: echo generateRandomDate('2020-01-01', '2025-12-31');
function generateRandomDate($startDate = '2020-01-01', $endDate = '2025-12-31') {
    $timestamp = mt_rand(strtotime($startDate), strtotime($endDate));
    return date('Y-m-d', $timestamp);
}

// Générer un temps de jeu random (en heures)
// Usage: echo generateRandomPlayTime(1, 500);
function generateRandomPlayTime($min = 1, $max = 500) {
    return mt_rand($min, $max);
}