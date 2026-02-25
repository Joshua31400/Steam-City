<?php
// GitHub OAuth provider implementation that extends the base OAuthProvider class.
// It handles the specific details of GitHub's OAuth flow, including generating authorization URLs, exchanging
require_once BASE_PATH . '/internal/auth/OAuthProvider.php';

class GithubProvider extends OAuthProvider {

    // Get the URL to redirect users for GitHub authorization with state parameter for CSRF protection
    public function getAuthorizationUrl() {
        $params = [
            'client_id' => $this->config['client_id'],
            'redirect_uri' => $this->config['redirect_uri'],
            'scope' => implode(' ', $this->config['scopes']),
            'state' => bin2hex(random_bytes(16))
        ];

        $_SESSION['oauth_state'] = $params['state'];

        return $this->config['auth_url'] . '?' . http_build_query($params);
    }

    // Exchange authorization code for access token using GitHub's token endpoint
    public function getAccessToken($code) {
        $data = [
            'client_id' => $this->config['client_id'],
            'client_secret' => $this->config['client_secret'],
            'code' => $code,
            'redirect_uri' => $this->config['redirect_uri']
        ];

        $headers = ['Accept: application/json'];
        $response = $this->makeRequest($this->config['token_url'], $data, $headers);
        $result = json_decode($response, true);

        if (!isset($result['access_token'])) {
            throw new Exception('Failed to get access token');
        }

        return $result['access_token'];
    }

    public function getUserInfo($accessToken) {
        $headers = [
            "Authorization: Bearer $accessToken",
            "User-Agent: Steam-City-App"
        ];

        // Get basic user info
        $response = $this->makeRequest($this->config['user_info_url'], null, $headers);
        $userInfo = json_decode($response, true);

        // Get user emails to find the primary email
        $emailResponse = $this->makeRequest('https://api.github.com/user/emails', null, $headers);
        $emails = json_decode($emailResponse, true);

        // Find the primary email
        $primaryEmail = null;
        foreach ($emails as $email) {
            if ($email['primary'] && $email['verified']) {
                $primaryEmail = $email['email'];
                break;
            }
        }

        // If no primary email is found, use the first email in the list
        return [
            'email' => $primaryEmail ?? $userInfo['email'],
            'name' => $userInfo['name'] ?? $userInfo['login'],
            'avatar' => $userInfo['avatar_url'] ?? null
        ];
    }
}