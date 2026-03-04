// UTILITAIRES
const getEl = id => document.getElementById(id);

function openModal(modalId) {
    getEl(modalId).style.display = 'block';
}

function closeModal(modalId) {
    getEl(modalId).style.display = 'none';
}

// CUSTOM SELECT
function initCustomSelect(selectId, hiddenInputId, textDisplayId) {
    const select = getEl(selectId);
    if (!select) return;

    const trigger = select.querySelector('.custom-select-trigger');
    const options = select.querySelectorAll('.custom-option');

    trigger.addEventListener('click', e => {
        e.stopPropagation();
        select.classList.toggle('open');
    });

    options.forEach(option => {
        option.addEventListener('click', e => {
            e.stopPropagation();
            getEl(hiddenInputId).value = option.dataset.value;
            getEl(textDisplayId).textContent = option.textContent;
            options.forEach(opt => opt.classList.remove('selected'));
            option.classList.add('selected');
            select.classList.remove('open');
        });
    });

    document.addEventListener('click', e => {
        if (!select.contains(e.target)) select.classList.remove('open');
    });
}

function setCustomSelectValue(selectId, hiddenInputId, textDisplayId, value) {
    const select = getEl(selectId);
    if (!select) return;

    getEl(hiddenInputId).value = value;
    select.querySelectorAll('.custom-option').forEach(option => {
        const isSelected = option.dataset.value === String(value);
        option.classList.toggle('selected', isSelected);
        if (isSelected) getEl(textDisplayId).textContent = option.textContent;
    });
    select.classList.remove('open');
}

// USER MODAL
function showAddUserModal() {
    getEl('userModalTitle').textContent = 'Add User';
    getEl('userForm').action = '/admin/user/create';
    getEl('userId').value = '';
    getEl('userName').value = '';
    getEl('userEmail').value = '';
    getEl('userPassword').value = '';
    getEl('passwordField').style.display = 'block';
    getEl('userPassword').required = true;
    setCustomSelectValue('customRoleSelect', 'userRole', 'selectedRoleText', 'user');
    openModal('userModal');
}

function editUser(id, username, email, role) {
    getEl('userModalTitle').textContent = 'Edit User';
    getEl('userForm').action = '/admin/user/update';
    getEl('userId').value = id;
    getEl('userName').value = username;
    getEl('userEmail').value = email;
    getEl('passwordField').style.display = 'none';
    getEl('userPassword').required = false;
    setCustomSelectValue('customRoleSelect', 'userRole', 'selectedRoleText', role);
    openModal('userModal');
}

function closeUserModal() { closeModal('userModal'); }

// GAME MODAL
function showAddGameModal() {
    getEl('gameModalTitle').textContent = 'Add Game';
    getEl('gameForm').action = '/admin/game/create';
    ['gameId', 'gameName', 'gameDescription', 'gameType', 'gameImage'].forEach(id => getEl(id).value = '');
    openModal('gameModal');
}

function editGame(id, name, description, type) {
    getEl('gameModalTitle').textContent = 'Edit Game';
    getEl('gameForm').action = '/admin/game/update';
    getEl('gameId').value = id;
    getEl('gameName').value = name;
    getEl('gameDescription').value = description;
    getEl('gameType').value = type;
    openModal('gameModal');
}

function closeGameModal() { closeModal('gameModal'); }

// ACHIEVEMENT MODAL
function showAddAchievementModal() {
    getEl('achievementModalTitle').textContent = 'Add Achievement';
    getEl('achievementForm').action = '/admin/achievement/create';
    ['achievementId', 'achievementName', 'achievementDescription', 'achievementIconUrl'].forEach(id => getEl(id).value = '');
    getEl('achievementGameId').value = '';
    getEl('selectedGameText').textContent = 'Select a game';
    document.querySelectorAll('#customGameSelect .custom-option').forEach(opt => opt.classList.remove('selected'));
    openModal('achievementModal');
}

function editAchievement(id, gameId, name, description, iconUrl) {
    getEl('achievementModalTitle').textContent = 'Edit Achievement';
    getEl('achievementForm').action = '/admin/achievement/update';
    getEl('achievementId').value = id;
    getEl('achievementName').value = name;
    getEl('achievementDescription').value = description;
    getEl('achievementIconUrl').value = iconUrl;
    setCustomSelectValue('customGameSelect', 'achievementGameId', 'selectedGameText', gameId);
    openModal('achievementModal');
}

function closeAchievementModal() { closeModal('achievementModal'); }

// Init
document.addEventListener('DOMContentLoaded', function() {
    // Init custom selects
    initCustomSelect('customRoleSelect', 'userRole', 'selectedRoleText');
    initCustomSelect('customGameSelect', 'achievementGameId', 'selectedGameText');

    // Fermeture modals
    const modals = ['userModal', 'gameModal', 'achievementModal'];
    const closeFns = { userModal: closeUserModal, gameModal: closeGameModal, achievementModal: closeAchievementModal };

    // Escape
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            modals.forEach(id => {
                if (getEl(id)?.style.display === 'block') closeFns[id]();
            });
        }
    });

    // Clic sur le fond
    modals.forEach(id => {
        getEl(id)?.addEventListener('mousedown', e => {
            if (e.target === getEl(id)) closeFns[id]();
        });
    });
});