<?php
// This file defines OAuthProvider, an abstract class that serves as a base for specific OAuth providers (Google, GitHub).
// It includes methods for handling user authentication and making HTTP requests to the provider's API.
abstract class OAuthProvider
{
    protected $config;
    protected $db;

    // Initialize with provider config and database connection
    public function __construct($config, $db)
    {
        $this->config = $config;
        $this->db = $db;
    }
    // Get the URL to redirect users for authorization
    abstract public function getAuthorizationUrl();
    // Exchange authorization code for access token
    abstract public function getAccessToken($code);
    // Retrieve user information using the access token
    abstract public function getUserInfo($accessToken);

    // Create or update user for API login
    public function handleUser($userInfo, $provider)
    {
        require_once BASE_PATH . '/internal/models/User.php';

        $userModel = new User($this->db);
        $userModel->email = $userInfo['email'];

        $existingUser = $userModel->findByEmail();

        if ($existingUser) {
            return $existingUser;
        } else {
            // Create new user
            $userModel->username = $userInfo['name'] ?? explode('@', $userInfo['email'])[0];
            $userModel->password = bin2hex(random_bytes(16));
            $userModel->role = 'user';

            if ($userModel->create()) {
                return $userModel->findByEmail();
            }

            return false;
        }
    }

    // Do HTTP request to provider API (used for token exchange and user info)
    // This method uses cURL to send HTTP requests to the provider's API endpoints.
    protected function makeRequest($url, $data = null, $headers = [])
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($data) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("cURL Error: $error");
        }

        return $response;
    }
}