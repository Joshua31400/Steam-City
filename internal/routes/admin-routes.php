<?php
return [
    '/admin'                    => [AdminController::class, 'showDashboard'],
    '/admin/users'              => [AdminController::class, 'manageUsers'],
    '/admin/games'              => [AdminController::class, 'manageGames'],
    '/admin/user/create'        => [AdminController::class, 'createUser'],
    '/admin/user/update'        => [AdminController::class, 'updateUser'],
    '/admin/user/delete'        => [AdminController::class, 'deleteUser'],
    '/admin/game/create'        => [AdminController::class, 'createGame'],
    '/admin/game/update'        => [AdminController::class, 'updateGame'],
    '/admin/game/delete'        => [AdminController::class, 'deleteGame'],
    '/admin/achievement/create' => [AdminController::class, 'createAchievement'],
    '/admin/achievement/update' => [AdminController::class, 'updateAchievement'],
    '/admin/achievement/delete' => [AdminController::class, 'deleteAchievement'],
];