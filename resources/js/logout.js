document.addEventListener('DOMContentLoaded', function() {
    const logoutButton = document.getElementById('logout-button');
    if (logoutButton) {
        logoutButton.addEventListener('click', function() {
            // Try Alpine.js event first
            if (window.Alpine) {
                window.dispatchEvent(new CustomEvent('open-logout-modal'));
            } else {
                // Fallback: show modal directly
                const modal = document.getElementById('logout-modal');
                if (modal) {
                    modal.style.display = 'flex';
                }
            }
        });
    }
});