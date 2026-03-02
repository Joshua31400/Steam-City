<?php
$games = $games ?? [];
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Steam City | Home</title>
    <link rel="stylesheet" href="/style/root.css">
    <link rel="stylesheet" href="/style/home.css">
</head>
<body>
<header class="main-header">
    <div class="brand"><h1>HOME</h1></div>
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

<main class="home-content">
    <section class="search-section">
        <form role="search">
            <input type="search" name="q" placeholder="Search for a game...">
        </form>
    </section>

    <section class="games-grid">
        <?php if (!empty($games)): ?>
            <?php foreach ($games as $game): ?>
                <article class="game-card">
                    <figure class="card-image">
                        <img src="<?= escape($game['image_url'] ?? '#') ?>" alt="<?= escape($game['name']) ?>">
                    </figure>
                    <div class="card-content">
                        <span class="game-tag"><?= escape($game['type']) ?></span>
                        <h2><?= escape($game['name']) ?></h2>
                        <p><?= escape($game['description']) ?></p>
                    </div>
                    <div class="card-footer">
                        <form action="/game/add" method="POST">
                            <input type="hidden" name="game_id" value="<?= $game['id'] ?>">
                            <button type="submit" class="btn-add">+ Add to Library</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No games available</p>
        <?php endif; ?>
    </section>
</main>
</body>
</html>