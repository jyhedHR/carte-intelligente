<header class="main-header" id="main-header">
    <div class="header-inner">

        <!-- Mobile hamburger toggle (hidden on desktop via header.css) -->
        <button class="hamburger-btn" id="hamburger-btn" aria-label="Ouvrir le menu" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav class="header-nav" id="header-nav">

            <!-- Accueil -->
            <div class="nav-item">
               <a href="{{ route('home') }}" class="header-link">Accueil</a>
            </div>

            <!-- Le Ministère (Mega Menu) -->
            <div class="nav-item">
                <a href="#" class="header-link">Ministère</a>
                <div class="dropdown-menu mega-menu">

                    <div class="dropdown-column">
                        <h4>Le Ministre</h4>
                        <a href="#">Le Ministre</a>
                        <a href="#">Publications régionales</a>
                    </div>

                </div>

            </div>

            <div class="nav-item">
                <a href="#" class="header-link">Organisation</a>
                <div class="dropdown-menu mega-menu">
                    <div class="dropdown-column">
                        <h4>Organisation</h4>
                        <a href="#">Organisation structurelle</a>
                        <a href="#">Institutions culturelles</a>
                        <a href="#">Maisons de culture et complexes</a>
                        <a href="#">Bibliothèques</a>
                    </div>
                </div>

            </div>
            <div class="nav-item">
                <a href="#" class="header-link">Institutions</a>
                <div class="dropdown-menu mega-menu">

                    <div class="dropdown-column">
                        <h4>Centres & Instituts</h4>
                        <a href="#">Instituts musique, théâtre, arts</a>
                        <a href="#">Centres d'arts dramatiques et scéniques</a>
                    </div>
                </div>

            </div>
            <div class="nav-item">
                <a href="#" class="header-link">Juridiques</a>
                <div class="dropdown-menu mega-menu">
                    <div class="dropdown-column">
                        <h4>Juridiques</h4>
                        <a href="#">Attributions du Ministère</a>
                        <a href="#">Textes juridiques</a>
                        <a href="#">Accords internationaux</a>
                        <a href="#">Nouveautés juridiques</a>
                    </div>
                </div>

            </div>
            <div class="nav-item">
                <a href="#" class="header-link">Investissement</a>
                <div class="dropdown-menu mega-menu">
                    <div class="dropdown-column">
                        <h4>Finances & Investissement</h4>
                        <a href="#">Accords</a>
                        <a href="#">Finances publiques</a>
                        <a href="#">Loi de finances</a>
                        <a href="#">Financement public des associations</a>
                    </div>
                </div>

            </div>
        </nav>
<a href="{{ route('home') }}" class="header-logo" id="header-logo">
    <img src="{{ Vite::asset('resources/assets/images/logo1.png') }}" alt="Ministère des Affaires Culturelles">
</a>
        <!-- Custom Language Button -->


        <div class="header-right">
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Rechercher..." id="header-search">
                <span class="search-icon">
                    <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M4.47487 4.4749C2.32698 6.62279 2.32698 10.1052 4.47487 12.2531C6.62275 14.401 10.1052 14.401 12.253 12.2531C14.4009 10.1052 14.4009 6.62279 12.253 4.4749C10.1052 2.32702 6.62275 2.32702 4.47487 4.4749ZM10.8388 10.8389C9.47199 12.2057 7.25591 12.2057 5.88908 10.8389C4.52224 9.47203 4.52224 7.25595 5.88908 5.88912C7.25591 4.52228 9.47199 4.52228 10.8388 5.88912C12.2057 7.25595 12.2057 9.47203 10.8388 10.8389Z"
                                fill="#c9a84C"></path>
                            <path
                                d="M11.1924 13.3137C10.6066 12.7279 10.6066 11.7782 11.1924 11.1924C11.7782 10.6066 12.7279 10.6066 13.3137 11.1924L16.8492 14.7279C17.435 15.3137 17.435 16.2635 16.8492 16.8492C16.2635 17.435 15.3137 17.435 14.7279 16.8492L11.1924 13.3137Z"
                                fill="#c9a84C"></path>
                        </g>
                    </svg>
                </span>
            </div>

            {{-- NOTIFICATION BELL --}}
            @auth
            <div class="notification-menu">
                <button id="notificationBtn" class="notification-btn" title="Notifications">
                    <img src="{{ Vite::asset('resources/assets/images/bell.png') }}" alt="Notifications" style="width:24px; height:24px;">
                    <span id="notificationBadge" class="notification-badge" style="display: none;">0</span>
                </button>

                <div id="notificationDropdown" class="notification-dropdown">
                    <div class="notification-header">
                        <h4>Notifications</h4>
                        <button id="markAllReadBtn" class="mark-all-read-btn">Tout marquer comme lu</button>
                    </div>
                    <div id="notificationList" class="notification-list">
                        <div class="notification-loading">Chargement...</div>
                    </div>
                    <div class="notification-footer">
                        {{-- <a href="{{ route('notifications.index') }}" class="view-all-link">Voir toutes les notifications</a> --}}
                    </div>
                </div>
            </div>
            @endauth


            <div class="user-menu">
                {{-- User button with auth indicator --}}
                <button id="userBtn" class="user-btn" title="Menu utilisateur">
                    @auth

                        <span style="position:relative; display:inline-block;">
                            <img src="{{ Vite::asset('resources\images\icons\LOGOuSER.png') }}" alt="langue"
                                style="width:50px; height:50px; object-fit:contain; pointer-events:none;">
                            <span
                                style="position:absolute; top:-2px; right:-4px;
                         width:8px; height:8px; border-radius:50%;
                         background:var(--gold); display:block;"></span>
                        </span>
                    @else
                       <img src="{{ Vite::asset('resources\images\icons\LOGOuSER.png') }}" alt="langue"
                                style="width:50px; height:50px; object-fit:contain; pointer-events:none;">
                    @endauth
                </button>

                {{-- AFTER --}}
                <div id="userDropdown" class="user-dropdown">
                    @auth
                        <div class="user-dropdown-header">
                            <div class="user-dropdown-avatar">
                                <span class="online-dot"></span>
                            </div>
                            <div>
                                <div class="user-dropdown-name">{{ Auth::user()->full_name }}</div>
                                <div class="user-dropdown-role">
                                    @if(Auth::user()->isAdmin())
                                        Administrateur
                                    @else
                                        Membre
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="user-dropdown-list">
                            {{-- <a href="{{ route('profile.index') }}" class="user-dropdown-item">🗂️ Mon espace</a> --}}
                            <div class="user-dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                                @csrf
                                <button type="submit" class="user-dropdown-item logout-btn">🚪 Se déconnecter</button>
                            </form>
                        </div>
                    @else
                        <div class="user-dropdown-header">
                            <div class="user-dropdown-avatar" style="background: var(--bg3); color: var(--text3);">👤</div>
                            <div>
                                <div class="user-dropdown-name" style="color: var(--text);">Non connecté</div>
                                <div class="user-dropdown-role">Rejoignez-nous</div>
                            </div>
                        </div>

                        <div class="user-dropdown-list">
                            <a href="{{ route('login') }}" class="user-dropdown-item">🔑 Connexion</a>
                            <a href="{{ route('register') }}" class="user-dropdown-item">✍️ Créer un compte</a>
                        </div>
                    @endauth

    <div class="user-dropdown-footer">
        <a href="#">Paramètres du compte</a>
    </div>
</div>
            </div>
            <!-- NEW - with real images -->
<div class="icon-btn mode-toggle" onclick="toggleMode()" title="Changer le thème">
  <img id="mode-icon"
       src="{{ Vite::asset('resources/assets/images/sun.png') }}"
       alt="Light Mode"
       style="width:30px; height:30px;">
</div>
            <div class="custom-lang-btn" id="custom-lang-btn" title="Changer la langue">
                <img src="{{ Vite::asset('resources/assets/images/earth_icon.png') }}" alt="langue"
                    style="width:28px; height:28px; object-fit:contain; pointer-events:none;">
                <div class="lang-dropdown" id="lang-dropdown">
                    <button onclick="switchLang('fr')">Français</button>
                    <button onclick="switchLang('ar')">العربية</button>
                </div>
            </div>
            <!-- GTranslate wrapper — hidden, required for the API to work -->
            <div class="gtranslate_wrapper" style="display:none " id="gt-wrapper-27219054"></div>
        </div>

    </div>

</header>

<!-- Overlay behind the slide-out mobile menu -->
<div class="mobile-nav-overlay" id="mobile-nav-overlay"></div>

<style>
/* Add to your existing <style> block */

.user-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    width: 280px;
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    z-index: 1000;
    display: none;
    margin-top: 8px;
    overflow: hidden;
}

.user-dropdown.show {
    display: block;
    animation: dropdownFadeIn 0.2s ease;
}

.user-dropdown-header {
    padding: 12px 16px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 10px;
}

.user-dropdown-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(201,168,76,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 700;
    color: var(--gold);
    flex-shrink: 0;
    position: relative;
}

.user-dropdown-avatar .online-dot {
    position: absolute;
    bottom: 1px;
    right: 1px;
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: var(--gold);
    border: 2px solid var(--bg2);
}

.user-dropdown-name {
    font-size: 14px;
    font-weight: 700;
    color: var(--gold);
}

.user-dropdown-role {
    font-size: 11px;
    color: var(--text3);
    margin-top: 1px;
}

.user-dropdown-list {
    padding: 6px 0;
}

.user-dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 16px;
    font-size: 13px;
    color: var(--text);
    text-decoration: none;
    cursor: pointer;
    transition: background 0.15s;
    border: none;
    background: transparent;
    width: 100%;
    text-align: left;
}

.user-dropdown-item:hover {
    background: var(--bg3);
}

.user-dropdown-divider {
    height: 1px;
    background: var(--border);
    margin: 4px 0;
}

.logout-btn {
    color: #e74c3c !important;
}

.logout-btn:hover {
    background: rgba(231, 76, 60, 0.08) !important;
}

.user-dropdown-footer {
    padding: 8px 16px;
    border-top: 1px solid var(--border);
    text-align: center;
}

.user-dropdown-footer a {
    font-size: 11px;
    color: var(--gold);
    text-decoration: none;
}

.user-dropdown-footer a:hover {
    text-decoration: underline;
}
.notification-menu {
    position: relative;
    margin-right: 0px;
}

.notification-btn {
    background: transparent;
    border: none;
    cursor: pointer;
    position: relative;
    padding: 2px;
    border-radius: 50%;
    transition: background 0.2s;
}

.notification-btn:hover {
    background: rgba(201, 168, 76, 0.1);
    filter: drop-shadow(0 0 6px rgba(201, 168, 76, 0.8))
            drop-shadow(0 0 14px rgba(201, 168, 76, 0.5));
}

.notification-badge {
    position: absolute;
    top: 0;
    right: 0;
    background: var(--gold);
    color: #111;
    font-size: 10px;
    font-weight: 700;
    min-width: 18px;
    height: 18px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 4px;
    font-family: var(--font-mono);
}

.notification-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    width: 380px;
    max-width: 90vw;
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    z-index: 1000;
    display: none;
    margin-top: 8px;
}

.notification-dropdown.show {
    display: block;
    animation: dropdownFadeIn 0.2s ease;
}

@keyframes dropdownFadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.notification-header {
    padding: 12px 16px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notification-header h4 {
    font-size: 14px;
    font-weight: 700;
    color: var(--text);
    margin: 0;
}

.mark-all-read-btn {
    background: transparent;
    border: none;
    font-size: 11px;
    color: var(--gold);
    cursor: pointer;
    font-weight: 500;
}

.mark-all-read-btn:hover {
    text-decoration: underline;
}

.notification-list {
    max-height: 400px;
    overflow-y: auto;
}

.notification-item {
    padding: 12px 16px;
    border-bottom: 1px solid var(--border);
    cursor: pointer;
    transition: background 0.15s;
    display: flex;
    gap: 12px;
}

.notification-item:hover {
    background: var(--bg3);
}

.notification-item.unread {
    background: rgba(201, 168, 76, 0.05);
    position: relative;
}

.notification-item.unread::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: var(--gold);
}

.notification-icon {
    font-size: 20px;
    flex-shrink: 0;
}

.notification-content {
    flex: 1;
    min-width: 0;
}

.notification-message {
    font-size: 13px;
    color: var(--text);
    margin-bottom: 4px;
    line-height: 1.4;
}

.notification-time {
    font-size: 10px;
    color: var(--text3);
}

.notification-footer {
    padding: 10px 16px;
    border-top: 1px solid var(--border);
    text-align: center;
}

.view-all-link {
    font-size: 12px;
    color: var(--gold);
    text-decoration: none;
}

.view-all-link:hover {
    text-decoration: underline;
}

.notification-loading,
.notification-empty {
    padding: 30px;
    text-align: center;
    color: var(--text3);
    font-size: 12px;
}
</style>

<script>
    window.assets = {
        sun: "{{ Vite::asset('resources/assets/images/sun.png') }}",
        moon: "{{ Vite::asset('resources/assets/images/night-mode.png') }}"
    };
</script>

<script>
// ══ MOBILE NAV TOGGLE ══
document.addEventListener('DOMContentLoaded', function () {
    const hamburgerBtn = document.getElementById('hamburger-btn');
    const headerNav     = document.getElementById('header-nav');
    const overlay       = document.getElementById('mobile-nav-overlay');

    if (!hamburgerBtn || !headerNav || !overlay) return;



    hamburgerBtn.addEventListener('click', function () {
        headerNav.classList.contains('open') ? closeMenu() : openMenu();
    });

    overlay.addEventListener('click', closeMenu);

    // Close automatically if the viewport is resized back to desktop width
    window.addEventListener('resize', function () {
        if (window.innerWidth > 900 && headerNav.classList.contains('open')) {
            closeMenu();
        }
    });

    // On mobile, turn each nav item with a mega-menu into an accordion
    // instead of navigating away or relying on hover (hover doesn't exist on touch).
    headerNav.querySelectorAll('.nav-item').forEach(function (item) {
        const megaMenu = item.querySelector('.dropdown-menu.mega-menu');
        const link     = item.querySelector('.header-link');
        if (!megaMenu || !link) return; // plain links (e.g. Accueil) navigate normally

        link.addEventListener('click', function (e) {
            if (window.innerWidth > 900) return; // desktop: let hover handle it
            e.preventDefault();
            const isOpen = item.classList.contains('mobile-open');
            headerNav.querySelectorAll('.nav-item.mobile-open').forEach(function (other) {
                if (other !== item) other.classList.remove('mobile-open');
            });
            item.classList.toggle('mobile-open', !isOpen);
        });
    });
});
</script>

@auth
<script>
// Notification system
document.addEventListener('DOMContentLoaded', function() {
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const notificationList = document.getElementById('notificationList');
    const notificationBadge = document.getElementById('notificationBadge');
    const markAllReadBtn = document.getElementById('markAllReadBtn');

    if (!notificationBtn) return;

    let dropdownOpen = false;

    // Toggle dropdown
    notificationBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdownOpen = !dropdownOpen;
        if (dropdownOpen) {
            notificationDropdown.classList.add('show');
            loadNotifications();
            updateUnreadCount();
        } else {
            notificationDropdown.classList.remove('show');
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (dropdownOpen && !notificationDropdown.contains(e.target) && !notificationBtn.contains(e.target)) {
            notificationDropdown.classList.remove('show');
            dropdownOpen = false;
        }
    });

    // Load notifications for dropdown
    function loadNotifications() {
        fetch('/api/notifications?per_page=10')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    renderNotifications(data.data);
                } else {
                    notificationList.innerHTML = '<div class="notification-empty">📭 Aucune notification</div>';
                }
                updateUnreadCount();
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                notificationList.innerHTML = '<div class="notification-empty">❌ Erreur de chargement</div>';
            });
    }

    function renderNotifications(notifications) {
        if (notifications.length === 0) {
            notificationList.innerHTML = '<div class="notification-empty">📭 Aucune notification</div>';
            return;
        }

        notificationList.innerHTML = notifications.map(notif => {
            const icon = notif.type === 'APPROVE' ? '✅' : (notif.type === 'REJECT' ? '❌' : '🔔');
            const unreadClass = notif.lu ? '' : 'unread';
            const timeAgo = getTimeAgo(notif.heure || notif.created_at);

            return `
                <div class="notification-item ${unreadClass}" data-id="${notif.id}" onclick="markAndRedirect(${notif.id}, '${notif.type}')">
                    <div class="notification-icon">${icon}</div>
                    <div class="notification-content">
                        <div class="notification-message">${escapeHtml(notif.message)}</div>
                        <div class="notification-time">${timeAgo}</div>
                    </div>
                </div>
            `;
        }).join('');
    }

    // Update unread count in badge
    function updateUnreadCount() {
        fetch('/api/notifications/unread-count')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.unread_count > 0) {
                    notificationBadge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                    notificationBadge.style.display = 'flex';
                } else {
                    notificationBadge.style.display = 'none';
                }
            })
            .catch(error => console.error('Error updating unread count:', error));
    }

    // Mark all as read
    markAllReadBtn.addEventListener('click', function() {
        fetch('/api/notifications/mark-all-read', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications();
                updateUnreadCount();
            }
        })
        .catch(error => console.error('Error marking all as read:', error));
    });

    // Auto-refresh unread count every 30 seconds
    setInterval(updateUnreadCount, 30000);

    // Initial unread count load
    updateUnreadCount();

    // Helper functions
    function getTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMs / 3600000);
        const diffDays = Math.floor(diffMs / 86400000);

        if (diffMins < 1) return 'À l\'instant';
        if (diffMins < 60) return `Il y a ${diffMins} min`;
        if (diffHours < 24) return `Il y a ${diffHours} h`;
        if (diffDays < 7) return `Il y a ${diffDays} j`;

        return date.toLocaleDateString('fr-FR');
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});

window.markAndRedirect = function(notificationId, type) {
    fetch(`/api/notifications/${notificationId}/mark-read`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
        }
    })
    .then(() => {
        window.location.href = `/notifications/${notificationId}`;
    })
    .catch(() => {
        window.location.href = `/notifications/${notificationId}`;
    });
};
</script>
@endauth
