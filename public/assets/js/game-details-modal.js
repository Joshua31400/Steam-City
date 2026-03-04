async function showGameDetails(userGameId) {
    const modal = document.getElementById('gameDetailsModal');
    modal.classList.add('show');

    try {
        const response = await fetch(`/profile/game/details?id=${userGameId}`);
        const data = await response.json();

        if (data.error) {
            alert(data.error);
            closeGameDetailsModal();
            return;
        }

        const game = data.game;
        const stats = data.stats;
        const achievements = data.achievements;

        document.getElementById('gameDetailImage').src = game.image_url || '';
        document.getElementById('gameDetailName').textContent = game.game_name;
        document.getElementById('gameDetailType').textContent = game.type;
        document.getElementById('gameDetailDescription').textContent = game.description;

        document.getElementById('gameDetailPlayTime').textContent = game.play_time + 'h';
        document.getElementById('gameDetailStartDate').textContent = game.start_date;
        document.getElementById('gameDetailDaysPlayed').textContent = stats.days_played;
        document.getElementById('gameDetailLastDeath').textContent = stats.last_death;
        document.getElementById('gameDetailAchievementProgress').textContent =
            stats.unlocked_achievements + '/' + stats.total_achievements;

        const list = document.getElementById('gameDetailAchievements');
        list.innerHTML = '';
        achievements.forEach(ach => {
            const div = document.createElement('div');
            div.className = 'achievement-item ' + (ach.unlocked ? 'unlocked' : 'locked');
            div.innerHTML = '<strong>' + ach.name + '</strong><p>' + (ach.description || '') + '</p>';
            list.appendChild(div);
        });
    } catch (e) {
        alert('Erreur');
        closeGameDetailsModal();
    }
}

function closeGameDetailsModal() {
    document.getElementById('gameDetailsModal').classList.remove('show');
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('gameDetailsModal');
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeGameDetailsModal();
        }
    });
});
