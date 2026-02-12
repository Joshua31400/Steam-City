<?php
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/internal/models/User.php';
require_once BASE_PATH . '/internal/helpers/validation.php';

class AuthController {
    private $db;
    private $userModel;

    // Initialize database connection and user model
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new User($this->db);
    }

    public function showLogin() {
        require PUBLIC_PATH . '/pages/login.html';
    }

    public function showSignIn() {
        require PUBLIC_PATH . '/pages/sign-in.html';
    }

    // Longin process for users
    public function login() {
        // Only allow POST requests for login
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/login');
        }

        // Sanitize and validate input data
        $email = sanitizeInput($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validate email and password
        if (!validateEmail($email) || !validateRequired($password)) {
            $_SESSION['error'] = 'Email ou mot de passe invalide';
            redirect('/login');
        }

        // Verify credentials against the database
        $this->userModel->email = $email;
        $user = $this->userModel->findByEmail();

        if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
            // Create session for the user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on user role
            if ($user['role'] === 'admin') {
                redirect('/admin');
            } else {
                redirect('/home');
            }
        } else {
            $_SESSION['error'] = 'Identifiants incorrects';
            redirect('/login');
        }
    }

    // Registration process for new users
    public function register() {
        // Only allow POST requests for registration
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/signup');
        }

        // Sanitize and validate input data
        $email = sanitizeInput($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validate email and password
        if (!validateEmail($email)) {
            $_SESSION['error'] = 'Email invalide';
            redirect('/signup');
        }

        // Validate password strength
        if (!validatePassword($password)) {
            $_SESSION['error'] = 'Le mot de passe doit contenir au moins 8 caractères';
            redirect('/signup');
        }

        // Check if passwords match
        if ($password !== $confirmPassword) {
            $_SESSION['error'] = 'Les mots de passe ne correspondent pas';
            redirect('/signup');
        }

        // Verify if the email is already registered
        $this->userModel->email = $email;
        if ($this->userModel->findByEmail()) {
            $_SESSION['error'] = 'Cet email est déjà utilisé';
            redirect('/signup');
        }

        // Create new user in the database
        // Username is the part before the @ in the email
        $username = explode('@', $email)[0];
        $this->userModel->username = $username;
        $this->userModel->password = $password;
        $this->userModel->role = 'user';

        // Attempt to create the user and handle success or failure
        if ($this->userModel->create()) {
            $_SESSION['success'] = 'Compte créé avec succès ! Connectez-vous';
            redirect('/login');
        } else {
            $_SESSION['error'] = 'Erreur lors de la création du compte';
            redirect('/signup');
        }
    }

    // Deconnect the user and destroy the session
    public function logout() {
        session_destroy();
        redirect('/login');
    }
}