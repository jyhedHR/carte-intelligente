<header class="topbar">
    <div class="topbar-left">
        <div style="display: flex; align-items: center; gap: 8px;">
            <strong> </strong>


        </div>
    </div>

    <div class="topbar-actions">
        <!-- Search -->
        <div class="topbar-search">
            <span class="topbar-search-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <input type="text" id="globalSearch" placeholder="Rechercher un dossier, utilisateur...">
        </div>

        <!-- Language Switcher -->
        <div class="lang-switcher">
            <button class="lang-btn-fr" data-lang="fr" onclick="switchLanguage('fr')">FR</button>
            <button class="lang-btn-ar" data-lang="ar" onclick="switchLanguage('ar')">AR</button>
        </div>

        <!-- Mode Toggle - CSS Icons -->
        <button class="icon-btn mode-toggle" onclick="toggleMode()" title="Changer le thème">
            <span class="mode-icon mode-dark"></span>
            <span class="mode-icon mode-light" style="display: none;"></span>
        </button>

        <!-- Notifications -->
        <button class="icon-btn" onclick="toggleNotifications()" title="Notifications">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg>
            <span class="notif-dot"></span>
        </button>

        <!-- Admin Dropdown -->
        <div class="admin-dropdown">
            <button class="avatar-btn" onclick="toggleAdminDropdown()">
                <div class="avatar"></div>
            </button>
            <div class="admin-dropdown-menu" id="adminDropdown">
                <div class="dropdown-header">
                    <div class="dropdown-avatar"></div>
                    <div class="dropdown-info">
                        <div class="dropdown-name"></div>
                        <div class="dropdown-role">

                        </div>
                    </div>
                </div>
                <div class="dropdown-divider"></div>

                <div class="dropdown-divider"></div>
                <form method="POST" action="" id="logout-form-header" style="width: 100%;">
                    @csrf
                    <button type="submit" class="dropdown-item logout" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<style>
    /* Language Switcher Styles */
    .lang-switcher {
        display: flex;
        gap: 4px;
        background: var(--bg3);
        padding: 4px;
        border-radius: 8px;
    }

    .lang-btn-fr,
    .lang-btn-ar {
        padding: 6px 14px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        background: transparent;
        color: var(--text2);
    }

    .lang-btn-fr.active,
    .lang-btn-ar.active {
        background: var(--gold);
        color: #111;
    }

    .lang-btn-fr:hover,
    .lang-btn-ar:hover {
        background: var(--bg4);
        color: var(--text);
    }

    /* Mode Toggle Icons */
    .mode-icon {
        width: 18px;
        height: 18px;
        object-fit: contain;
    }

    .mode-dark {
        display: block;
    }

    .mode-light {
        display: none;
    }

    /* Light mode - show light icon, hide dark icon */
    body.light .mode-dark {
        display: none;
    }

    body.light .mode-light {
        display: block;
    }

    /* RTL Support for breadcrumb */
    [dir="rtl"] .topbar-breadcrumb .sep {
        display: inline-block;
        transform: scaleX(-1);
    }

    [dir="rtl"] .topbar-search input {
        text-align: right;
    }

    [dir="rtl"] .admin-dropdown-menu {
        right: auto;
        left: 0;
    }

    /* Logout button styling */
    .dropdown-item.logout {
        color: var(--red);
    }

    .dropdown-item.logout:hover {
        background: rgba(248, 113, 113, 0.1);
    }
</style>

<script>
    // ============================================
    // LANGUAGE MANAGEMENT
    // ============================================

    function switchLanguage(lang) {
        window.switchLang(lang);
    }

    // ============================================
    // THEME MANAGEMENT WITH PNG ICONS
    // ============================================
    function setTheme(theme) {
        const body = document.body;
        const darkIcon = document.getElementById('darkModeIcon');
        const lightIcon = document.getElementById('lightModeIcon');

        if (theme === 'light') {
            body.classList.remove('dark');
            body.classList.add('light');
            localStorage.setItem('theme', 'light');
            if (darkIcon) darkIcon.style.display = 'none';
            if (lightIcon) lightIcon.style.display = 'block';
        } else {
            body.classList.remove('light');
            body.classList.add('dark');
            localStorage.setItem('theme', 'dark');
            if (darkIcon) darkIcon.style.display = 'block';
            if (lightIcon) lightIcon.style.display = 'none';
        }

        // Update logo if exists
        updateLogo();
    }

    function toggleMode() {
        const isDark = document.body.classList.contains('dark');
        setTheme(isDark ? 'light' : 'dark');
    }

    function loadTheme() {
        const savedTheme = localStorage.getItem('theme');
        const darkIcon = document.getElementById('darkModeIcon');
        const lightIcon = document.getElementById('lightModeIcon');

        if (savedTheme === 'light') {
            document.body.classList.remove('dark');
            document.body.classList.add('light');
            if (darkIcon) darkIcon.style.display = 'none';
            if (lightIcon) lightIcon.style.display = 'block';
        } else {
            document.body.classList.add('dark');
            if (darkIcon) darkIcon.style.display = 'block';
            if (lightIcon) lightIcon.style.display = 'none';
        }
    }

    // Update logo based on theme
    function updateLogo() {
        const isDark = document.body.classList.contains('dark');
        const darkLogo = document.querySelector('.logo-dark');
        const lightLogo = document.querySelector('.logo-light');

        if (darkLogo && lightLogo) {
            if (isDark) {
                darkLogo.style.display = 'block';
                lightLogo.style.display = 'none';
            } else {
                darkLogo.style.display = 'none';
                lightLogo.style.display = 'block';
            }
        }
    }

    // ============================================
    // BREADCRUMB UPDATE
    // ============================================
    function updateBreadcrumb(title, crumb) {
        const titleEl = document.getElementById('page-title');
        const crumbEl = document.getElementById('page-crumb');
        if (titleEl) titleEl.textContent = title;
        if (crumbEl) crumbEl.textContent = crumb;
    }

    // Breadcrumb translation map
    const breadcrumbTranslations = {
        'GED': { fr: 'GED', ar: 'إدارة المستندات' }
    };

    // Update breadcrumb first-level translation
    function updateBreadcrumbTranslation(lang) {
        const breadcrumbEl = document.getElementById('page-breadcrumb');
        if (breadcrumbEl) {
            breadcrumbEl.textContent = breadcrumbTranslations['GED'][lang] || breadcrumbTranslations['GED']['fr'];
        }
    }

    // Auto-update breadcrumb based on URL
    function autoUpdateBreadcrumb() {
        const path = window.location.pathname;
        const lang = document.documentElement.lang || 'fr';

        const titleMap = {
            '/admin': { fr: 'Tableau de bord', ar: 'لوحة القيادة' },
            '/admin/backhome': { fr: 'Tableau de bord', ar: 'لوحة القيادة' },
            '/admin/livre': { fr: 'Direction générale du Livre', ar: 'الإدارة العامة للكتاب' },
            '/admin/livre/droits': { fr: 'Facilitation transfert droits', ar: 'تسهيل نقل حقوق النشر' },
            '/admin/livre/foire': { fr: 'Participation foire internationale', ar: 'المشاركة في المعارض الدولية' },
            '/admin/livre/transport': { fr: 'Couverture frais transport', ar: 'تغطية تكاليف النقل' },
            '/admin/livre/tva': { fr: 'Matériaux exonérés TVA', ar: 'المواد المعفاة من الضريبة' },
            '/admin/investisseurs': { fr: 'Investisseurs culturels', ar: 'المستثمرون الثقافيون' },
            '/admin/dossiers': { fr: 'Gestion des dossiers', ar: 'إدارة الملفات' },
            '/admin/users': { fr: 'Utilisateurs', ar: 'المستخدمون' },
        };

        let crumbMap = {
            '/admin': { fr: 'Dashboard', ar: 'الرئيسية' },
            '/admin/backhome': { fr: 'Dashboard', ar: 'الرئيسية' },
            '/admin/livre': { fr: 'Livre', ar: 'الكتاب' },
            '/admin/livre/droits': { fr: 'Droits', ar: 'حقوق النشر' },
            '/admin/livre/foire': { fr: 'Foire', ar: 'المعارض' },
            '/admin/livre/transport': { fr: 'Transport', ar: 'النقل' },
            '/admin/livre/tva': { fr: 'TVA', ar: 'ضريبة القيمة المضافة' },
        };

        for (const [route, titles] of Object.entries(titleMap)) {
            if (path.includes(route)) {
                updateBreadcrumb(titles[lang] || titles.fr, (crumbMap[route]?.[lang] || crumbMap[route]?.fr || route));
                updateBreadcrumbTranslation(lang);
                break;
            }
        }
    }

    // ============================================
    // ADMIN DROPDOWN
    // ============================================
    function toggleAdminDropdown() {
        const dropdown = document.getElementById('adminDropdown');
        if (dropdown) {
            dropdown.classList.toggle('open');
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function (e) {
        const dropdown = document.getElementById('adminDropdown');
        const avatarBtn = document.querySelector('.avatar-btn');
        if (dropdown && avatarBtn && !avatarBtn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('open');
        }
    });

    // ============================================
    // NOTIFICATIONS
    // ============================================
    function toggleNotifications() {
        console.log('Notifications - Coming soon');
    }

    // ============================================
    // INITIALIZATION
    // ============================================
    document.addEventListener('DOMContentLoaded', function () {
        loadTheme();
        autoUpdateBreadcrumb();

        const searchInput = document.getElementById('globalSearch');
        if (searchInput) {
            searchInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    const query = this.value.trim();
                    if (query) alert(`Recherche: ${query}`);
                }
            });
        }
    });
</script>
