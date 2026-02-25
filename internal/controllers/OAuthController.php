<?php
// This controller handles OAuth authentication for Google and GitHub providers.
// It manages the redirection to the provider's authorization page and processes the callback to authenticate users and create sessions.
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/internal/auth/GoogleProvider.php';
require_once BASE_PATH . '/internal/auth/GithubProvider.php';
require_once BASE_PATH . '/internal/helpers/functions.php';

class OAuthController
{
    private $db;
    private $oauthConfig;

    // Init database connection and load OAuth config
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->oauthConfig = require BASE_PATH . '/config/oauth.php';
    }

    // Redirect to Google
    public function redirectToGoogle()
    {
        $provider = new GoogleProvider($this->oauthConfig['google'], $this->db);
        $authUrl = $provider->getAuthorizationUrl();
        header("Location: $authUrl");
        exit();
    }

    // Callback Google
    public function handleGoogleCallback()
    {
        if (!isset($_GET['code'])) {
            $_SESSION['error'] = 'Authorization failed';
            redirect('/login');
        }

        try {
            $provider = new GoogleProvider($this->oauthConfig['google'], $this->db);
            // Change code for access token
            $accessToken = $provider->getAccessToken($_GET['code']);

            $userInfo = $provider->getUserInfo($accessToken);
            // Create or get user
            $user = $provider->handleUser($userInfo, 'google');

            if ($user) {
                // Create session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                redirect('/home');
            } else {
                $_SESSION['error'] = 'Failed to create user account';
                redirect('/login');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Authentication failed: ' . $e->getMessage();
            redirect('/login');
        }
    }

    public function redirectToGithub()
    {
        $provider = new GithubProvider($this->oauthConfig['github'], $this->db);
        $authUrl = $provider->getAuthorizationUrl();
        header("Location: $authUrl");
        exit();
    }

    // Callback GitHub
    public function handleGithubCallback()
    {
        if (!isset($_GET['code'])) {
            $_SESSION['error'] = 'Authorization failed';
            redirect('/login');
        }

        // Verify the state parameter to prevent CSRF attacks
        if (!isset($_GET['state']) || $_GET['state'] !== $_SESSION['oauth_state']) {
            $_SESSION['error'] = 'Invalid state parameter';
            redirect('/login');
        }

        try {
            $provider = new GithubProvider($this->oauthConfig['github'], $this->db);
            $accessToken = $provider->getAccessToken($_GET['code']);
            $userInfo = $provider->getUserInfo($accessToken);
            $user = $provider->handleUser($userInfo, 'github');

            if ($user) {
                // Create session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Clear the state from session after successful authentication
                unset($_SESSION['oauth_state']);

                redirect('/home');
            } else {
                $_SESSION['error'] = 'Failed to create user account';
                redirect('/login');
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Authentication failed: ' . $e->getMessage();
            redirect('/login');
        }
    }
}

