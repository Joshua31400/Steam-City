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
   <link rel="stylesheet" href="/style/root.css">
   <link rel="stylesheet" href="/style/profile.css">
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
    <section class="user-overview">
        <div class="user-details">
            <h2>Details</h2>
            <p>Email: <?= escape($user['email']) ?></p>
        </div>

        <div class="user-identity">
            <h2><?= escape($user['username']) ?></h2>
            <a href="/profile/edit">EDIT</a>
            <?php if ($user['role'] === 'admin'): ?>
                <span>ADMIN</span>
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
        <h2>My Games</h2>
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
</body>
</html>
