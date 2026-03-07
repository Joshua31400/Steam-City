<?php
return [
    '/'              => [AuthController::class, 'showLogin'],
    '/login'         => [AuthController::class, 'showLogin'],
    '/login/process' => [AuthController::class, 'login'],
    '/sign-in'       => [AuthController::class, 'showSignIn'],
    '/register'      => [AuthController::class, 'register'],
    '/logout'        => [AuthController::class, 'logout'],
];