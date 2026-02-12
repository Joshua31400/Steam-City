<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Steam City | Admin</title>
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
    </div>
</main>

<!-- USER MODAL TEST (FUTURE INTERFACE) -->
<div id="userModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); background:#fff; padding:20px; border:1px solid #ccc; z-index:1000;">
    <h3 id="userModalTitle">Add User</h3>
    <form id="userForm" action="/admin/user/create" method="POST">
        <input type="hidden" name="user_id" id="userId">
        <div><label>Username</label><input type="text" name="username" id="userName" required></div>
        <div><label>Email</label><input type="email" name="email" id="userEmail" required></div>
        <div><label>Role</label>
            <select name="role" id="userRole">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <div id="passwordField"><label>Password</label><input type="password" name="password" id="userPassword" minlength="8"></div>
        <button type="submit">Save</button>
        <button type="button" onclick="closeUserModal()">Cancel</button>
    </form>
</div>

<!-- GAME MODAL TEST (FUTURE INTERFACE) -->
<div id="gameModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); background:#fff; padding:20px; border:1px solid #ccc; z-index:1000;">
    <h3 id="gameModalTitle">Add Game</h3>
    <form id="gameForm" action="/admin/game/create" method="POST">
        <input type="hidden" name="game_id" id="gameId">
        <div><label>Name</label><input type="text" name="name" id="gameName" required></div>
        <div><label>Description</label><textarea name="description" id="gameDescription" required></textarea></div>
        <div><label>Type</label><input type="text" name="type" id="gameType" required></div>
        <div><label>Image URL</label><input type="text" name="image_url" id="gameImage"></div>
        <button type="submit">Save</button>
        <button type="button" onclick="closeGameModal()">Cancel</button>
    </form>
</div>

<script>
    /* TEST (FUTURE INTERFACE) */
    function showAddUserModal() {
        document.getElementById('userModalTitle').textContent = 'Add User';
        document.getElementById('userForm').action = '/admin/user/create';
        document.getElementById('userId').value = '';
        document.getElementById('userName').value = '';
        document.getElementById('userEmail').value = '';
        document.getElementById('userRole').value = 'user';
        document.getElementById('userPassword').value = '';
        document.getElementById('passwordField').style.display = 'block';
        document.getElementById('userPassword').required = true;
        document.getElementById('userModal').style.display = 'block';
    }

    function editUser(id, username, email, role) {
        document.getElementById('userModalTitle').textContent = 'Edit User';
        document.getElementById('userForm').action = '/admin/user/update';
        document.getElementById('userId').value = id;
        document.getElementById('userName').value = username;
        document.getElementById('userEmail').value = email;
        document.getElementById('userRole').value = role;
        document.getElementById('passwordField').style.display = 'none';
        document.getElementById('userPassword').required = false;
        document.getElementById('userModal').style.display = 'block';
    }

    function closeUserModal() { document.getElementById('userModal').style.display = 'none'; }

    function showAddGameModal() {
        document.getElementById('gameModalTitle').textContent = 'Add Game';
        document.getElementById('gameForm').action = '/admin/game/create';
        document.getElementById('gameId').value = '';
        document.getElementById('gameName').value = '';
        document.getElementById('gameDescription').value = '';
        document.getElementById('gameType').value = '';
        document.getElementById('gameImage').value = '';
        document.getElementById('gameModal').style.display = 'block';
    }

    function editGame(id, name, description, type) {
        document.getElementById('gameModalTitle').textContent = 'Edit Game';
        document.getElementById('gameForm').action = '/admin/game/update';
        document.getElementById('gameId').value = id;
        document.getElementById('gameName').value = name;
        document.getElementById('gameDescription').value = description;
        document.getElementById('gameType').value = type;
        document.getElementById('gameModal').style.display = 'block';
    }

    function closeGameModal() { document.getElementById('gameModal').style.display = 'none'; }
</script>
</body>
</html>
