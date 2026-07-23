/**
 * ═══════════════════════════════════════════════════════════
 * GED BACKOFFICE - MAIN APPLICATION
 * Version: 2.1 - PERFORMANCE OPTIMIZED
 * ═══════════════════════════════════════════════════════════
 */

// ============================================
// DOM Elements Cache
// ============================================
const DOM = {
    get: (selector) => document.querySelector(selector),
    getAll: (selector) => document.querySelectorAll(selector),
    getId: (id) => document.getElementById(id)
};

// ============================================
// PAGE NAVIGATION - FIXED RECURSION
// ============================================
const pageTitles = {
    dossiers:  ['Gestion des dossiers', 'Dossiers'],
    forms:     ['Générateur de formulaires', 'Form Builder'],
    workflows: ['Workflows BPMN', 'Workflows'],
    users:     ['Utilisateurs', 'Utilisateurs'],
    ia:        ['Module IA — V1 Core', 'Intelligence Artificielle'],
    notifs:    ['Notifications', 'Notifications'],
    audit:     ['Audit Trail', 'Audit'],
    stats:     ['Statistiques', 'Statistiques'],
    rules:     ['Moteur de règles', 'Règles métier'],
    'arts-sceniques': ['Direction des Arts Scéniques', 'Arts Scéniques'],
    dashboard: ['Tableau de bord', 'Accueil']
};

// Flag to prevent recursive calls
let isNavigating = false;

window.showPage = function(name) {
    // Prevent recursive calls
    if (isNavigating) return;
    isNavigating = true;

    try {
        // Hide all pages and deactivate nav items
        DOM.getAll('.page').forEach(p => p.classList.remove('active'));
        DOM.getAll('.nav-item').forEach(n => n.classList.remove('active'));

        const page = DOM.getId('page-' + name);
        if (page) page.classList.add('active');

        // Update title and breadcrumb
        const t = pageTitles[name] || [name, name];
        const titleEl = DOM.getId('page-title');
        const crumbEl = DOM.getId('page-crumb');
        if (titleEl) titleEl.textContent = t[0];
        if (crumbEl) crumbEl.textContent = t[1];

        // Highlight active nav item
        DOM.getAll('.nav-item').forEach(item => {
            const onclick = item.getAttribute('onclick');
            if (onclick && onclick.includes(`'${name}'`)) {
                item.classList.add('active');
            }
        });

        // PAGE-SPECIFIC INIT - WITHOUT RECURSION
        if (name === 'investisseurs') {
            if (typeof window.initInvestisseurs === 'function') {
                setTimeout(window.initInvestisseurs, 30);
            }
        }
        else if (name === 'arts-sceniques') {
            if (typeof window.initArtsSceniques === 'function') {
                setTimeout(window.initArtsSceniques, 30);
            }
        }
        // Removed the recursive call to initializeDashboard
    } finally {
        // Reset flag after a short delay to allow other operations
        setTimeout(() => {
            isNavigating = false;
        }, 100);
    }
};

// ============================================
// UI COMPONENTS
// ============================================
window.setFilter = function(el) {
    const bar = el.closest('.filter-bar');
    if (bar) {
        bar.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
    }
};

window.openSheet = function() {
    DOM.getId('detail-sheet')?.classList.add('open');
    DOM.getId('overlay')?.classList.add('show');
};

window.closeSheet = function() {
    DOM.getId('detail-sheet')?.classList.remove('open');
    DOM.getId('overlay')?.classList.remove('show');
};

window.selectField = function(el) {
    DOM.getAll('.form-field-item').forEach(f => f.classList.remove('active'));
    el.classList.add('active');
};

// ============================================
// ANIMATIONS & CHARTS - OPTIMIZED
// ============================================
window.animateKPI = function(id, target, suffix = '') {
    const el = DOM.getId(id);
    if (!el) return;

    // Use requestAnimationFrame for smoother animations
    let val = 0;
    const duration = 800; // ms
    const startTime = performance.now();

    function animate(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        val = Math.floor(target * progress);
        el.textContent = val + suffix;

        if (progress < 1) {
            requestAnimationFrame(animate);
        }
    }

    requestAnimationFrame(animate);
};

window.buildMiniChart = function() {
    const chart = DOM.getId('mini-chart');
    if (!chart) return;

    const data = [8, 12, 7, 15, 11, 18, 9, 14, 20, 13, 16, 10, 22, 19, 11, 17, 24, 14, 21, 16, 13, 20, 18, 25, 15, 19, 22, 28, 17, 31];
    const max = Math.max(...data);

    // Use DocumentFragment for better performance
    const fragment = document.createDocumentFragment();
    data.forEach((v, i) => {
        const pct = Math.round((v / max) * 100);
        const isToday = i === data.length - 1;
        const bar = document.createElement('div');
        bar.className = `chart-bar ${isToday ? 'chart-bar-today' : ''}`;
        bar.style.height = `${pct}%`;
        bar.title = `${v} dossiers`;
        fragment.appendChild(bar);
    });

    chart.innerHTML = '';
    chart.appendChild(fragment);
};

// ============================================
// THEME MANAGEMENT
// ============================================
function setTheme(theme) {
    const body = document.body;

    if (theme === 'light') {
        body.classList.remove('dark');
        body.classList.add('light');
        localStorage.setItem('theme', 'light');
    } else {
        body.classList.remove('light');
        body.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    }

    updateLogo();
}

window.toggleMode = function() {
    const isDark = document.body.classList.contains('dark');
    setTheme(isDark ? 'light' : 'dark');
};

function loadTheme() {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'light') {
        document.body.classList.remove('dark');
        document.body.classList.add('light');
    } else {
        document.body.classList.add('dark');
        document.body.classList.remove('light');
    }
}

// ============================================
// LOGO MANAGEMENT
// ============================================
function updateLogo() {
    const isDark = document.body.classList.contains('dark');
    const darkLogo = DOM.get('.logo-dark');
    const lightLogo = DOM.get('.logo-light');

    if (darkLogo && lightLogo) {
        darkLogo.style.display = isDark ? 'block' : 'none';
        lightLogo.style.display = isDark ? 'none' : 'block';
    }
}

// ============================================
// SIDEBAR DROPDOWNS
// ============================================
window.toggleDropdown = function(trigger) {
    if (event) event.stopPropagation();
    const dropdown = trigger?.closest('.nav-dropdown');
    if (!dropdown) return;

    DOM.getAll('.nav-dropdown.open').forEach(d => {
        if (d !== dropdown) d.classList.remove('open');
    });
    dropdown.classList.toggle('open');
};

window.toggleDropdownFromArrow = function(arrowElement) {
    if (event) event.stopPropagation();
    const dropdown = arrowElement?.closest('.nav-dropdown');
    if (dropdown) dropdown.classList.toggle('open');
};

// ============================================
// HEADER FUNCTIONS
// ============================================
window.updateBreadcrumb = function(title, crumb) {
    const titleEl = DOM.getId('page-title');
    const crumbEl = DOM.getId('page-crumb');
    if (titleEl) titleEl.textContent = title;
    if (crumbEl) crumbEl.textContent = crumb;
};

window.switchLanguage = function(lang) {
    const isArabic = lang === 'ar';
    document.documentElement.lang = isArabic ? 'ar' : 'fr';
    document.documentElement.dir = isArabic ? 'rtl' : 'ltr';

    DOM.get('.lang-btn-fr')?.classList.toggle('active', !isArabic);
    DOM.get('.lang-btn-ar')?.classList.toggle('active', isArabic);

    localStorage.setItem('language', lang);
    location.reload();
};

window.toggleAdminDropdown = function() {
    DOM.getId('adminDropdown')?.classList.toggle('open');
};

window.toggleNotifications = function() {
    console.log('Notifications - Coming soon');
};

window.showProfile = function() {
    console.log('Profile - Coming soon');
};

window.showSettings = function() {
    console.log('Settings - Coming soon');
};

window.logout = function() {
    if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
        window.location.href = '/logout';
    }
};

// ============================================
// BREADCRUMB AUTO-UPDATE
// ============================================
const breadcrumbMap = {
    '/admin/livre': { title: 'Direction du Livre', crumb: 'Livre' },
    '/admin/livre/droits': { title: 'Facilitation transfert droits', crumb: 'Livre / Droits' },
    '/admin/livre/foire': { title: 'Participation foire internationale', crumb: 'Livre / Foire' },
    '/admin/livre/transport': { title: 'Couverture frais transport', crumb: 'Livre / Transport' },
    '/admin/livre/tva': { title: 'Matériaux exonérés TVA', crumb: 'Livre / TVA' },
    '/admin/investisseurs': { title: 'Investisseurs Culturels', crumb: 'Investisseurs' },
    '/admin/investisseurs/mecenat': { title: 'Attestation Mécénat', crumb: 'Investisseurs / Mécénat' },
    '/admin/investisseurs/agrement': { title: 'Demande Agrément', crumb: 'Investisseurs / Agrément' },
    '/admin/investisseurs/certification': { title: 'Certification', crumb: 'Investisseurs / Certification' },
    '/admin/dossiers': { title: 'Gestion des dossiers', crumb: 'Dossiers' },
    '/admin/users': { title: 'Gestion des utilisateurs', crumb: 'Utilisateurs' },
    '/admin/generateformulair': { title: 'Générateur de formulaires', crumb: 'Form Builder' },
    '/admin/workflows': { title: 'Workflows BPMN', crumb: 'Workflows' },
    '/admin/rules': { title: 'Moteur de règles', crumb: 'Rules Engine' },
    '/admin': { title: 'Tableau de bord', crumb: 'Accueil' },
    '/admin/': { title: 'Tableau de bord', crumb: 'Accueil' },
    '/admin/backhome': { title: 'Tableau de bord', crumb: 'Accueil' },
    '/admin/dashboard': { title: 'Tableau de bord', crumb: 'Accueil' }
};

function autoUpdateBreadcrumb() {
    const path = window.location.pathname;

    // Check exact matches first, then partial
    for (const [route, data] of Object.entries(breadcrumbMap)) {
        if (path === route || path.startsWith(route + '/')) {
            updateBreadcrumb(data.title, data.crumb);
            return;
        }
    }
}

// ============================================
// BROWSER DETECTION & FIXES - OPTIMIZED
// ============================================
const Browser = {
    isOpera: () => navigator.userAgent.includes('OPR') || navigator.userAgent.includes('Opera'),
    isChrome: () => navigator.userAgent.includes('Chrome'),
    isEdge: () => navigator.userAgent.includes('Edg'),
    isWebKit: function() { return this.isOpera() || this.isChrome() || this.isEdge(); }
};

function applyBrowserFixes() {
    if (Browser.isOpera()) {
        document.body.classList.add('browser-opera');

        const sidebar = DOM.get('.sidebar');
        if (sidebar) {
            sidebar.style.height = '100vh';
            sidebar.style.position = 'fixed';
        }

        const main = DOM.get('.main');
        if (main) main.style.marginLeft = 'var(--sidebar-w)';
    }

    if (Browser.isWebKit()) {
        const setVh = () => {
            document.documentElement.style.setProperty('--vh', `${window.innerHeight * 0.01}px`);
        };
        setVh();
        window.addEventListener('resize', setVh);
    }
}

// ============================================
// EVENT HANDLERS
// ============================================
function closeDropdownsOnClickOutside(e) {
    if (!e.target.closest('.nav-dropdown')) {
        DOM.getAll('.nav-dropdown.open').forEach(d => d.classList.remove('open'));
    }
}

function closeAdminDropdownOnClickOutside(e) {
    const dropdown = DOM.getId('adminDropdown');
    const avatarBtn = DOM.get('.avatar-btn');
    if (dropdown && avatarBtn && !avatarBtn.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove('open');
    }
}

function highlightActiveNavItem() {
    const currentPath = window.location.pathname;

    DOM.getAll('.nav-item').forEach(link => {
        const href = link.getAttribute('href');
        if (href && href !== '#' && href !== '' && currentPath === href) {
            link.classList.add('active');
            const dropdown = link.closest('.nav-dropdown');
            if (dropdown) dropdown.classList.add('open');
        }
    });
}

// ============================================
// DASHBOARD INITIALIZATION - NO RECURSION
// ============================================
let dashboardInitialized = false;

function initializeDashboard() {
    // Prevent multiple initializations
    if (dashboardInitialized) return;
    dashboardInitialized = true;

    const isDashboard = ['/admin', '/admin/', '/admin/backhome', '/admin/dashboard'].includes(window.location.pathname);

    if (isDashboard) {
        // Small delay to ensure DOM is ready
        setTimeout(() => {
animateKPI('kpi1', 143);
            if (DOM.getId('mini-chart')) buildMiniChart();

            // Update the page title without recursion
            const titleEl = DOM.getId('page-title');
            const crumbEl = DOM.getId('page-crumb');
            if (titleEl) titleEl.textContent = 'Tableau de bord';
            if (crumbEl) crumbEl.textContent = 'Accueil';
        }, 50);
    }
}

// ============================================
// LAZY LOADING FOR HEAVY COMPONENTS
// ============================================
function lazyLoadComponent(componentName, callback) {
    if (document.readyState === 'complete') {
        setTimeout(callback, 100);
    } else {
        window.addEventListener('load', () => {
            setTimeout(callback, 100);
        });
    }
}

// ============================================
// OPTIMIZED INITIALIZATION
// ============================================
let appInitialized = false;

function initApp() {
    if (appInitialized) return;
    appInitialized = true;

    // Theme
    loadTheme();
    updateLogo();

    // Language
    const savedLang = localStorage.getItem('language');
    if (savedLang === 'ar') switchLanguage('ar');

    // UI
    autoUpdateBreadcrumb();
    highlightActiveNavItem();
    applyBrowserFixes();

    // Initialize dashboard without recursion
    initializeDashboard();

    // Event listeners
    document.addEventListener('click', closeDropdownsOnClickOutside);
    document.addEventListener('click', closeAdminDropdownOnClickOutside);

    console.log('App initialized:', window.location.pathname);
}

// Start the app when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initApp);
} else {
    initApp();
}

// ============================================
// EXPORT FOR DEV TOOLS (optional)
// ============================================
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { DOM, showPage, toggleMode };
}
