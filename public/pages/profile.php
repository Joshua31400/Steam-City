<?php
// Fonction helper for formatting achievement unlock dates in a human-friendly way (e.g. "Today", "Yesterday", "3 days ago", etc.)
function formatAchievementDate($date)
{
    $now = new DateTime();
    $achievementDate = new DateTime($date);
    $diff = $now->diff($achievementDate);

    if ($diff->days == 0) return 'Today';
    if ($diff->days == 1) return 'Yesterday';
    if ($diff->days < 7) return $diff->days . ' days ago';
    if ($diff->days < 30) return 'Last week';
    if ($diff->days < 365) return 'Last month';
    return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Steam City | Profile</title>
    <link rel="stylesheet" href="/style/profile.css">
    <script src="/assets/js/profile-modal.js"></script>
</head>
<body>
<header class="main-header">
    <div class="brand"><h1>PROFILE</h1></div>
    <nav class="user-nav">
        <ul>
            <li><a href="/home">Home</a></li>
            <li><a href="/profile">Profile</a></li>
            <li>
                <form action="/logout" method="POST" style="display:inline;">
                    <button type="submit">Logout</button>
                </form>
            </li>
        </ul>
    </nav>
</header>

<main class="profile-content">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= escape($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?= escape($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <section class="user-overview">
        <div class="user-details">
            <h2>Details</h2>
            <p>Email: <?= escape($user['email']) ?></p>
        </div>

        <div class="user-identity">
            <h2><?= escape($user['username']) ?></h2>

            <button onclick="showEditProfileModal()" class="btn-edit">EDIT</button>

            <?php if ($user['role'] === 'admin'): ?>
                <a href="/admin" class="btn-admin">ACCESS ADMIN</a>
            <?php endif; ?>
        </div>

        <div class="user-achievements">
            <h2>Last Achievements</h2>
            <ul>
                <?php if (!empty($userAchievements)): ?>
                    <?php foreach (array_slice($userAchievements, 0, 4) as $ach): ?>
                        <li>
                            <span><?= escape($ach['name']) ?></span>
                            <span><?= formatAchievementDate($ach['unlocked_at']) ?></span>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No achievements yet</li>
                <?php endif; ?>
            </ul>
        </div>
    </section>

    <section class="my-games">
        <h2>MY GAMES</h2>
        <div class="games-list">
            <?php if (!empty($userGames)): ?>
                <?php foreach ($userGames as $game): ?>
                    <article class="game-card">
                        <h3><?= escape($game['name']) ?></h3>
                        <p>Start: <?= date('d/m/Y', strtotime($game['start_date'])) ?></p>
                        <p>Play time: <?= escape($game['play_time']) ?>h</p>
                        <a href="/game/details?id=<?= $game['game_id'] ?>">Details</a>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
            <a href="/home">+ Add Game</a>
        </div>
    </section>
</main>

<div id="profileModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); background:#fff; padding:20px; border:1px solid #ccc; z-index:1000;">
    <div>
        <h3>Edit Profile</h3>

        <form action="/profile/update" method="POST">
            <div>
                <label>Username</label>
                <input
                        type="text"
                        name="username"
                        id="profileUsername"
                        required>
            </div>

            <div>
                <label>Email</label>
                <input
                        type="email"
                        name="email"
                        id="profileEmail"
                        required>
            </div>

            <div>
                <button
                        type="submit">
                    Save Changes
                </button>
            </div>
            <div>
                <button
                        type="button"
                        onclick="closeProfileModal()">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>