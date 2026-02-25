<?php
// Google OAuth provider implementation that extends the base OAuthProvider class.
// It handles the specific details of Google's OAuth flow, including generating authorization URLs, exchanging authorization codes for access tokens, and retrieving user information from Google's API.
require_once BASE_PATH . '/internal/auth/OAuthProvider.php';

class GoogleProvider extends OAuthProvider {
    // Get the URL to redirect users for Google authorization
    public function getAuthorizationUrl() {
        $params = [
            'client_id' => $this->config['client_id'],
            'redirect_uri' => $this->config['redirect_uri'],
            'response_type' => 'code',
            'scope' => implode(' ', $this->config['scopes']),
            'access_type' => 'offline',
            'prompt' => 'consent'
        ];

        return $this->config['auth_url'] . '?' . http_build_query($params);
    }

    // Exchange authorization code for access token
    public function getAccessToken($code) {
        $data = [
            'code' => $code,
            'client_id' => $this->config['client_id'],
            'client_secret' => $this->config['client_secret'],
            'redirect_uri' => $this->config['redirect_uri'],
            'grant_type' => 'authorization_code'
        ];

        $response = $this->makeRequest($this->config['token_url'], $data);
        $result = json_decode($response, true);

        if (!isset($result['access_token'])) {
            throw new Exception('Failed to get access token');
        }

        return $result['access_token'];
    }

    // Retrieve user information using the access token
    public function getUserInfo($accessToken) {
        $headers = ["Authorization: Bearer $accessToken"];
        $response = $this->makeRequest($this->config['user_info_url'], null, $headers);
        $userInfo = json_decode($response, true);

        return [
            'email' => $userInfo['email'],
            'name' => $userInfo['name'],
            'avatar' => $userInfo['picture'] ?? null
        ];
    }
}