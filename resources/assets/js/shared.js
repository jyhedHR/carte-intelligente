// ===============================================
// GED Shared JavaScript - v2.0
// Dark/Light Mode + Language Support
// ===============================================

// ==================== DARK / LIGHT MODE ====================
window.toggleMode = function() {
    const html = document.documentElement;
    const icon = document.getElementById('mode-icon');

    const sunIcon = window.assets.sun;
    const moonIcon = window.assets.moon;

    if (html.classList.contains('dark')) {
        html.classList.remove('dark');
        localStorage.setItem('ged_theme', 'light');

        if (icon) icon.src = sunIcon;

    } else {
        html.classList.add('dark');
        localStorage.setItem('ged_theme', 'dark');

        if (icon) icon.src = moonIcon;
    }
}

window.addEventListener('DOMContentLoaded', () => {
    const html = document.documentElement;
    const icon = document.getElementById('mode-icon');

    const savedTheme = localStorage.getItem('ged_theme');
    const sunIcon = window.assets?.sun;
    const moonIcon = window.assets?.moon;

    if (savedTheme === 'dark') {
        html.classList.add('dark');
        if (icon && moonIcon) icon.src = moonIcon;
    } else {
        html.classList.remove('dark');
        if (icon && sunIcon) icon.src = sunIcon;
    }
});
// ==================== INIT EVERYTHING ====================
document.addEventListener('DOMContentLoaded', () => {
    initTheme();

    // Make sure i18n.js function is globally available
    if (typeof applyLang === 'function') {
        window.applyLangFromI18n = applyLang;
    }
});
