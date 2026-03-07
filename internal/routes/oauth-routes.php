<?php
return [
    '/auth/google'          => [OAuthController::class, 'redirectToGoogle'],
    '/auth/google/callback' => [OAuthController::class, 'handleGoogleCallback'],
    '/auth/github'          => [OAuthController::class, 'redirectToGithub'],
    '/auth/github/callback' => [OAuthController::class, 'handleGithubCallback'],
];