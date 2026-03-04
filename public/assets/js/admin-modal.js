function showAddUserModal() {
    document.getElementById('userModalTitle').textContent = 'Add User';
    document.getElementById('userForm').action = '/admin/user/create';
    document.getElementById('userId').value = '';
    document.getElementById('userName').value = '';
    document.getElementById('userEmail').value = '';
    setCustomSelectValue('user');
    document.getElementById('userModal').style.display = 'block';
}

function editUser(id, username, email, role) {
    document.getElementById('userModalTitle').textContent = 'Edit User';
    document.getElementById('userForm').action = '/admin/user/update';
    document.getElementById('userId').value = id;
    document.getElementById('userName').value = username;
    document.getElementById('userEmail').value = email;
    setCustomSelectValue(role);
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

// ========== CUSTOM SELECT MANAGEMENT ==========

// Initialize custom select on page load
document.addEventListener('DOMContentLoaded', function() {
    const customSelect = document.getElementById('customRoleSelect');
    const trigger = customSelect.querySelector('.custom-select-trigger');
    const options = customSelect.querySelectorAll('.custom-option');
    
    // Toggle dropdown
    trigger.addEventListener('click', function() {
        customSelect.classList.toggle('open');
    });
    
    // Handle option selection
    options.forEach(option => {
        option.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            const text = this.textContent;
            
            // Update hidden input
            document.getElementById('userRole').value = value;
            
            // Update displayed text
            document.getElementById('selectedRoleText').textContent = text;
            
            // Update selected class
            options.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            
            // Close dropdown
            customSelect.classList.remove('open');
        });
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!customSelect.contains(e.target)) {
            customSelect.classList.remove('open');
        }
    });
});

// Helper function to set custom select value programmatically
function setCustomSelectValue(value) {
    const customSelect = document.getElementById('customRoleSelect');
    const options = customSelect.querySelectorAll('.custom-option');
    
    // Update hidden input
    document.getElementById('userRole').value = value;
    
    // Find and select the option
    options.forEach(option => {
        if (option.getAttribute('data-value') === value) {
            document.getElementById('selectedRoleText').textContent = option.textContent;
            option.classList.add('selected');
        } else {
            option.classList.remove('selected');
        }
    });
    
    // Make sure dropdown is closed
    customSelect.classList.remove('open');
}
