function showAddUserModal() {
    document.getElementById('userModalTitle').textContent = 'Add User';
    document.getElementById('userForm').action = '/admin/user/create';
    document.getElementById('userId').value = '';
    document.getElementById('userName').value = '';
    document.getElementById('userEmail').value = '';
    document.getElementById('userRole').value = 'user';
    document.getElementById('userModal').style.display = 'block';
}

function editUser(id, username, email, role) {
    document.getElementById('userModalTitle').textContent = 'Edit User';
    document.getElementById('userForm').action = '/admin/user/update';
    document.getElementById('userId').value = id;
    document.getElementById('userName').value = username;
    document.getElementById('userEmail').value = email;
    document.getElementById('userRole').value = role;
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
