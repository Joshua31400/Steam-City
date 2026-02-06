<?php
// Validation functions for user input
// Usage: if (validateEmail($email)) { ... }
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Validate password strength (at least 8 characters)
// Usage: if (validatePassword($password)) { ... }
function validatePassword($password) {
    return strlen($password) >= PASSWORD_MIN_LENGTH;
}

// Validate that a value is not empty
// Usage: if (validateRequired($username)) { ... }
function validateRequired($value) {
    return !empty(trim($value));
}

// Sanitize user input to prevent XSS attacks
// Usage: $safeInput = sanitizeInput($userInput);
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}