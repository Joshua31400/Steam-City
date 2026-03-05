<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Steam City | Admin</title>
        <link rel="stylesheet" href="/style/admin.css">
    <script src="/assets/js/admin-modal.js"></script>
    <script src="/assets/js/gradient-mouse.js"></script>
</head>
<body>
<header class="main-header">
    <div class="brand"><h1>ADMIN</h1></div>
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

<main class="admin-content">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= escape($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error"><?= escape($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <section class="admin-stats">
        <p><strong>Total Users:</strong> <span><?= $totalUsers ?></span></p>
        <p><strong>Total Games:</strong> <span><?= $totalGames ?></span></p>
    </section>

    <div class="admin-panels">
        <!-- USERS TABLE -->
        <section class="admin-section">
            <h2>Users Management</h2>
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= escape($u['username']) ?></td>
                        <td><?= escape($u['email']) ?></td>
                        <td><?= escape($u['role']) ?></td>
                        <td>
                            <button onclick="editUser(<?= $u['id'] ?>, '<?= escape($u['username']) ?>', '<?= escape($u['email']) ?>', '<?= escape($u['role']) ?>')">Edit</button>
                            <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                <form action="/admin/user/delete" method="POST" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                    <button type="submit" onclick="return confirm('Delete this user?')">Delete</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <button onclick="showAddUserModal()">+ Add User</button>
        </section>

        <!-- GAMES TABLE -->
        <section class="admin-section">
            <h2>Games Management</h2>
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($games as $g): ?>
                    <tr>
                        <td><?= escape($g['name']) ?></td>
                        <td><?= escape($g['description']) ?></td>
                        <td><?= escape($g['type']) ?></td>
                        <td>
                            <button onclick="editGame(<?= $g['id'] ?>, '<?= escape($g['name']) ?>', '<?= escape($g['description']) ?>', '<?= escape($g['type']) ?>')">Edit</button>
                            <form action="/admin/game/delete" method="POST" style="display:inline;">
                                <input type="hidden" name="game_id" value="<?= $g['id'] ?>">
                                <button type="submit" onclick="return confirm('Delete this game?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <button onclick="showAddGameModal()">+ Add Game</button>
        </section>

        <!-- ACHIEVEMENTS TABLE -->
        <section class="admin-section">
            <h2>Achievements Management</h2>
            <table>
                <thead>
                <tr>
                    <th>Game</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($achievements as $a): ?>
                    <tr>
                        <td><?= escape($a['game_name'] ?? 'Unknown') ?></td>
                        <td><?= escape($a['name']) ?></td>
                        <td><?= escape($a['description']) ?></td>
                        <td>
                            <button onclick="editAchievement(<?= $a['id'] ?>, <?= $a['game_id'] ?>, '<?= escape($a['name']) ?>', '<?= escape($a['description']) ?>', '<?= escape($a['icon_url'] ?? '') ?>')">Edit</button>
                            <form action="/admin/achievement/delete" method="POST" style="display:inline;">
                                <input type="hidden" name="achievement_id" value="<?= $a['id'] ?>">
                                <button type="submit" onclick="return confirm('Delete this achievement?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <button onclick="showAddAchievementModal()">+ Add Achievement</button>
        </section>

    </div>
</main>

<!-- USER MODAL TEST -->
<div id="userModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); background:#fff; padding:20px; border:1px solid #ccc; z-index:1000;">
    <h3 id="userModalTitle">Add User</h3>
    <form id="userForm" action="/admin/user/create" method="POST">
        <input type="hidden" name="user_id" id="userId">
        <div><label>Username</label><input type="text" name="username" id="userName" required></div>
        <div><label>Email</label><input type="email" name="email" id="userEmail" required></div>
        <div id="passwordField"><label>Password</label><input type="password" name="password" id="userPassword"></div>
        <div>
            <label>Role</label>
            <div class="custom-select-wrapper">
                <input type="hidden" name="role" id="userRole" value="user">
                <div class="custom-select" id="customRoleSelect">
                    <div class="custom-select-trigger">
                        <span id="selectedRoleText">User</span>
                        <div class="arrow"></div>
                    </div>
                    <div class="custom-options">
                        <div class="custom-option selected" data-value="user">User</div>
                        <div class="custom-option" data-value="admin">Admin</div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit">Save</button>
        <button type="button" onclick="closeUserModal()">Cancel</button>
    </form>
</div>

<!-- GAME MODAL TEST -->
<div id="gameModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); background:#fff; padding:20px; border:1px solid #ccc; z-index:1000;">
    <h3 id="gameModalTitle">Add Game</h3>
    <form id="gameForm" action="/admin/game/create" method="POST">
        <input type="hidden" name="game_id" id="gameId">
        <div><label>Name</label><input type="text" name="name" id="gameName" required></div>
        <div><label>Description</label><textarea name="description" id="gameDescription" required></textarea></div>
        <div><label>Type</label><input type="text" name="type" id="gameType" placeholder="TPS/ FPS ..." required></div>
        <div><label>Image URL</label><input type="text" name="image_url" id="gameImage" placeholder="/assets/images/games/fortnite.webp"></div>
        <button type="submit">Save</button>
        <button type="button" onclick="closeGameModal()">Cancel</button>
    </form>
</div>

<!-- ACHIEVEMENT MODAL -->
<div id="achievementModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); background:#fff; padding:20px; border:1px solid #ccc; z-index:1000;">
    <h3 id="achievementModalTitle">Add Achievement</h3>
    <form id="achievementForm" action="/admin/achievement/create" method="POST">
        <input type="hidden" name="achievement_id" id="achievementId">
        <div>
            <label>Game</label>
            <div class="custom-select-wrapper">
                <input type="hidden" name="game_id" id="achievementGameId" value="">
                <div class="custom-select" id="customGameSelect">
                    <div class="custom-select-trigger">
                        <span id="selectedGameText">Select a game</span>
                        <div class="arrow"></div>
                    </div>
                    <div class="custom-options">
                        <?php foreach ($games as $g): ?>
                            <div class="custom-option" data-value="<?= $g['id'] ?>"><?= escape($g['name']) ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <div><label>Name</label><input type="text" name="name" id="achievementName" required></div>
        <div><label>Description</label><textarea name="description" id="achievementDescription"></textarea></div>
        <div><label>Icon URL</label><input type="text" name="icon_url" id="achievementIconUrl" placeholder="/assets/images/achievements/..."></div>
        <button type="submit">Save</button>
        <button type="button" onclick="closeAchievementModal()">Cancel</button>
    </form>
</div>

</body>
</html>
