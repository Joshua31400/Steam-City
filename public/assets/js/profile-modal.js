function showEditProfileModal() {
    const currentUsername = document.querySelector('.user-identity h2').textContent.trim();
    const currentEmail = document.querySelector('.user-details p').textContent.replace('Email:', '').trim();

    document.getElementById('profileUsername').value = currentUsername;
    document.getElementById('profileEmail').value = currentEmail;
    document.getElementById('profileModal').style.display = 'block';
}

function closeProfileModal() { document.getElementById('profileModal').style.display = 'none'; }