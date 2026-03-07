<?php
return [
    '/profile'              => [ProfileController::class, 'showProfile'],
    '/profile/update'       => [ProfileController::class, 'updateProfile'],
    '/profile/game/details' => [ProfileController::class, 'GetGameDetails'],
    '/profile/game/remove'  => [GameController::class, 'removeFromLibrary'],
    '/profile/game/edit'    => [ProfileController::class, 'editUserGame'],
];