<style>
/* ── Pin button ── */
.sidebar-pin-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    width: calc(100% - 24px);
    margin: 8px 12px;
    padding: 10px 12px;
    background: none;
    border: 1px solid var(--border, rgba(255,255,255,0.08));
    border-radius: 8px;
    color: var(--text3, #888);
    font-size: 13px;
    font-family: var(--font-body);
    cursor: pointer;
    overflow: hidden;
    transition: all 0.2s ease;
}

.sidebar-pin-btn:hover {
    color: var(--gold, #D4AF37);
    background: rgba(212, 175, 55, 0.06);
    border-color: var(--gold, #D4AF37);
}
.sidebar.pinned .sidebar-pin-btn {
    color: var(--gold, #D4AF37);
    border-color: var(--gold, #D4AF37);
}
.sidebar-pin-btn .icon-pin::before {
    content: '📌';
    font-style: normal;
    font-size: 14px;
}
.sidebar.pinned .sidebar-pin-btn .icon-pin::before {
    content: '📌';
    opacity: 1;
    filter: sepia(1) saturate(3) hue-rotate(5deg);
}

/* ── When pinned, sidebar stays wide regardless of hover ── */
.sidebar.pinned {
    width: 260px !important;
    min-width: 260px !important;
}

/* ══ SIDEBAR ══ */
.sidebar {
    width: 64px;
    min-width: 64px;
    flex-shrink: 0;
    overflow: visible;
    transition: width 0.25s ease, min-width 0.25s ease;
    white-space: nowrap;
    position: relative;
    z-index: 200;
}

.sidebar:hover,
.sidebar.expanded {
    width: 260px;
    min-width: 260px;
}

/* ── Icon strip: always visible ── */
.sidebar .nav-icon {
    min-width: 20px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* ── Text elements: hidden when collapsed ── */
.sidebar .nav-text,
.sidebar .nav-badge,
.sidebar .nav-section-label,
.sidebar .logo-text,
.sidebar .logo-sub,
.sidebar .user-name,
.sidebar .user-chevron,
.sidebar .dropdown-arrow {
    opacity: 0;
    width: 0;
    overflow: hidden;
    transition: opacity 0.2s ease, width 0.2s ease;
    pointer-events: none;
}

.sidebar:hover .nav-text,
.sidebar:hover .nav-badge,
.sidebar:hover .nav-section-label,
.sidebar:hover .logo-text,
.sidebar:hover .logo-sub,
.sidebar:hover .user-name,
.sidebar:hover .user-chevron,
.sidebar:hover .dropdown-arrow,
.sidebar.expanded .nav-text,
.sidebar.expanded .nav-badge,
.sidebar.expanded .nav-section-label,
.sidebar.expanded .logo-text,
.sidebar.expanded .logo-sub,
.sidebar.expanded .user-name,
.sidebar.expanded .user-chevron,
.sidebar.expanded .dropdown-arrow {
    opacity: 1;
    width: auto;
    pointer-events: auto;
}

/* ── Nav items ── */
.sidebar .nav-item,
.sidebar .nav-dropdown-trigger {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    overflow: hidden;
}

/* ── Logo ── */
.sidebar .sidebar-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    overflow: hidden;
}

/* ── User card ── */
.sidebar .user-card {
    display: flex;
    align-items: center;
    gap: 10px;
    overflow: hidden;
}

/* ── Arrow indicator ── */
.sidebar::after {
    content: '›';
    position: absolute;
    right: 4px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 16px;
    color: var(--gold, #D4AF37);
    opacity: 0.5;
    transition: opacity 0.2s ease;
    pointer-events: none;
}

.sidebar:hover::after,
.sidebar.expanded::after {
    opacity: 0;
}

/* ── Dropdown menu ── */
.sidebar:not(:hover):not(.expanded) .nav-dropdown-menu {
    display: none !important;
}

.nav-dropdown-menu {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.nav-dropdown.open .nav-dropdown-menu {
    max-height: 600px;
}

.dropdown-arrow {
    margin-left: auto;
    font-size: 10px;
    transition: transform 0.25s ease;
    display: inline-block;
}

.nav-dropdown.open .dropdown-arrow {
    transform: rotate(180deg);
}

/* ── Main content ── */
.main {
    transition: width 0.25s ease;
}
</style>

<!-- ══ SIDEBAR ══ -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-mark"></div>
        <div>
            <div class="logo-text">GED Admin</div>
            <div class="logo-sub">Affaires Culturelles</div>
        </div>
    </div>

    <button class="sidebar-pin-btn" id="sidebarPinBtn" title="Épingler le menu">
        <i class="icon icon-pin nav-icon" id="sidebarPinIcon"></i>
        <span class="nav-text">Épingler</span>
    </button>

    <nav class="sidebar-nav" id="sidebarNav">
        <div class="nav-section">
            <div class="nav-section-label">Principal</div>

          

            @php
                $mapActive = request()->routeIs('admin.map-categories.*') || request()->routeIs('admin.map-locations.*');
            @endphp
            <div class="nav-dropdown {{ $mapActive ? 'open' : '' }}" id="map-dropdown">
                <a class="nav-dropdown-trigger {{ $mapActive ? 'active' : '' }}"
                   href="javascript:void(0)"
                   onclick="toggleDropdown(this)">
                    <img src="{{ Vite::asset('resources/assets/images/map.png') }}"
                         class="nav-icon" style="width:20px;height:20px;object-fit:contain;">
                    <span class="nav-text">Carte du patrimoine</span>
                    <span class="dropdown-arrow">▼</span>
                </a>

                <div class="nav-dropdown-menu">
                    <a class="nav-item {{ request()->routeIs('admin.map-locations.*') ? 'active' : '' }}"
                       href="{{ route('map-locations.index') }}">
                        <i class="icon icon-departments nav-icon"></i>
                        <span class="nav-text">Lieux</span>
                    </a>
                    <a class="nav-item {{ request()->routeIs('admin.map-categories.*') ? 'active' : '' }}"
                       href="{{ route('map-categories.index') }}">
                        <i class="icon icon-forms nav-icon"></i>
                        <span class="nav-text">Catégories</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="avatar"></div>
            <div>
                <div class="user-name"></div>
            </div>
            <span class="user-chevron">⋮</span>
        </div>
    </div>
</aside>

<script>
(function () {
    const sidebar = document.getElementById('sidebar');
    const pinBtn  = document.getElementById('sidebarPinBtn');
    const PINNED_KEY = 'sb_pinned';

    // ── Restore pin state from localStorage ──
    if (localStorage.getItem(PINNED_KEY) === '1') {
        sidebar.classList.add('pinned', 'expanded');
    }

    // ── Toggle pin on button click ──
    pinBtn.addEventListener('click', function () {
        const isPinned = sidebar.classList.toggle('pinned');
        sidebar.classList.toggle('expanded', isPinned);
        localStorage.setItem(PINNED_KEY, isPinned ? '1' : '0');
        pinBtn.title = isPinned ? 'Désépingler le menu' : 'Épingler le menu';
        pinBtn.querySelector('.nav-text').textContent = isPinned ? 'Désépingler' : 'Épingler';
    });

    // ── Collapse on nav-item click (only when NOT pinned) ──
    document.querySelectorAll('#sidebarNav .nav-item, #sidebarNav .nav-dropdown-trigger').forEach(item => {
        item.addEventListener('click', function () {
            if (sidebar.classList.contains('pinned')) return;
            const href = this.getAttribute('href');
            if (href && href !== '#' && !href.startsWith('javascript')) {
                sidebar.classList.remove('expanded');
            }
        });
    });

    // ── Collapse on mouseleave (only when NOT pinned) ──
    sidebar.addEventListener('mouseleave', function () {
        if (!sidebar.classList.contains('pinned')) {
            sidebar.classList.remove('expanded');
        }
    });

    // ── toggleDropdown ──
    window.toggleDropdown = function (triggerEl) {
        const dropdown = triggerEl.closest('.nav-dropdown');
        const isOpen = dropdown.classList.contains('open');
        document.querySelectorAll('.nav-dropdown.open').forEach(d => {
            if (d !== dropdown) d.classList.remove('open');
        });
        dropdown.classList.toggle('open', !isOpen);
    };

    // ── Auto detect active item ──
    window.sbAutoDetect = function () {
        const items = document.querySelectorAll('#sidebarNav .nav-item');
        const currentPath = window.location.pathname;
        let matched = false;

        items.forEach(item => {
            const href = item.getAttribute('href');
            if (!href || href === '#' || href.startsWith('javascript')) return;
            const cleanHref = href.replace(/\?.*$/, '').replace(/\/$/, '');
            if (cleanHref && (currentPath === cleanHref || currentPath.startsWith(cleanHref + '/') || currentPath.includes(cleanHref))) {
                item.classList.add('active');
                matched = true;
            }
        });

        if (!matched) {
            const savedHref = sessionStorage.getItem('sb_active_href');
            if (savedHref) {
                items.forEach(item => {
                    const href = item.getAttribute('href');
                    if (href && href.replace(/\?.*$/, '').replace(/\/$/, '') === savedHref.replace(/\?.*$/, '').replace(/\/$/, '')) {
                        item.classList.add('active');
                        matched = true;
                    }
                });
            }
        }

        if (!matched) {
            const first = document.querySelector('#sidebarNav .nav-item:first-child');
            if (first) first.classList.add('active');
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', sbAutoDetect);
    } else {
        sbAutoDetect();
    }
})();
</script>
