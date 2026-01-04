
const themeToggleBtn = document.getElementById('theme-toggle');

if (themeToggleBtn) {
    const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

    // Function to set the theme
    function setTheme(isDark) {
        if (isDark) {
            document.documentElement.classList.add('dark');
            themeToggleLightIcon.classList.remove('hidden');
            themeToggleDarkIcon.classList.add('hidden');
            localStorage.setItem('color-theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            themeToggleDarkIcon.classList.remove('hidden');
            themeToggleLightIcon.classList.add('hidden');
            localStorage.setItem('color-theme', 'light');
        }
    }

    // Initial theme check
    const isDarkMode = localStorage.getItem('color-theme') === 'dark' ||
        (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);

    setTheme(isDarkMode);

    themeToggleBtn.addEventListener('click', function() {
        setTheme(!document.documentElement.classList.contains('dark'));
    });
}
