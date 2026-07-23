@extends('shared.layouts.frontoffice')

@section('page-title', 'Carte du Patrimoine Tunisien')

@section('content')

<!-- FULL-SCREEN MAP CONTAINER (Google Maps Style) -->
<div class="map-page-container">

    <!-- TOP HEADER BAR -->
    <div class="map-header">
        <div class="header-left">
            <h1 class="map-title">Patrimoine Tunisien</h1>
            <p class="map-subtitle">Découvrez les trésors culturels de la Tunisie</p>
        </div>
        <a href="{{ url()->previous() }}" class="header-close-btn" title="Retour">✕</a>
    </div>

    <!-- MAP & SIDEBAR WRAPPER -->
    <div class="map-layout">

        <!-- FULL MAP - Takes most of the space -->
        <div class="map-container">
            <div id="tunisia-map"></div>

            <!-- ZOOM CONTROLS (Floating, scoped to the map area only) -->
            <div class="zoom-controls-floating">
                <button class="zoom-btn" id="zoom-in" title="Zoom avant">+</button>
                <button class="zoom-btn" id="zoom-out" title="Zoom arrière">−</button>
            </div>

            <!-- INFO PANEL (Floating, scoped to the map area only — never covers the sidebar) -->
            <div class="info-panel" id="info-panel">
                <button class="info-close-btn" id="info-close-btn" title="Fermer" aria-label="Fermer">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M1 1L13 13M13 1L1 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                </button>
                <div class="info-header">
                    <img id="info-image" src="{{ asset('images/placeholder.png') }}" alt="location image" class="info-image-large">
                    <div class="info-image-gradient"></div>
                    <span class="info-category" id="info-category">CATEGORY</span>
                </div>
                <div class="info-body">
                    <h2 class="info-title" id="info-title">Location Name</h2>
                    <p class="info-description" id="info-description">Description of the location</p>
                    <div class="info-coords" id="info-coords">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none"><path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21z" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="9.5" r="2.3" stroke="currentColor" stroke-width="1.6"/></svg>
                        <span id="info-coords-text"></span>
                    </div>
                    <button class="info-explore-btn">
                        <span>Explorer Plus</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDEBAR - LOCATIONS PANEL -->
        <div class="sidebar-panel">
            <div class="sidebar-header">
                <h2>Lieux Patrimoine</h2>
                <input type="text" id="search-input" class="search-box" placeholder="Rechercher...">
            </div>

            <div class="sidebar-legend">
                <div class="legend-title-row">
                    <div class="legend-title">Catégories</div>
                    <button type="button" class="legend-reset" id="legend-reset">Tout afficher</button>
                </div>
                <div class="legend-items" id="legend-items">
                    @foreach ($mapCategories as $cat)
                        <button type="button"
                                class="legend-chip active"
                                data-category="{{ $cat['slug'] }}"
                                style="--chip-color: {{ $cat['color'] }};">
                            <span class="legend-dot"></span>
                            <span class="legend-text">{{ $cat['name'] }}</span>
                            <span class="legend-count" id="count-{{ $cat['slug'] }}">0</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="sidebar-divider"></div>

            <div class="sidebar-content">
                <div class="locations-list" id="locations-list"></div>
            </div>
        </div>
    </div>

    <!-- ZOOM CONTROLS AND INFO PANEL MOVED INSIDE .map-container ABOVE -->

</div>

<style>
/* ═══════════════════════════════════════════════════════════════════════════
   FULL-SCREEN MAP PAGE — Google Maps Style Layout
═══════════════════════════════════════════════════════════════════════════ */

* {
  box-sizing: border-box;
}

/* MAIN CONTAINER - Full Screen */
.map-page-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: #1a1a1a;
  display: flex;
  flex-direction: column;
  z-index: 50;
  overflow: hidden;
}

/* HEADER BAR */
.map-header {
  background: rgba(0, 0, 0, 0.85);
  backdrop-filter: blur(10px);
  border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  padding: 16px 24px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: white;
  flex-shrink: 0;
}

.header-left h1 {
  margin: 0;
  font-size: 24px;
  font-weight: 700;
  color: #fff;
}

.header-left p {
  margin: 4px 0 0 0;
  font-size: 13px;
  color: rgba(255, 255, 255, 0.6);
}

.header-close-btn {
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  color: white;
  width: 32px;
  height: 32px;
  border-radius: 6px;
  font-size: 18px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

.header-close-btn:hover {
  background: rgba(255, 255, 255, 0.15);
  border-color: rgba(255, 255, 255, 0.3);
}

/* MAP LAYOUT - Main Content */
.map-layout {
  display: flex;
  flex: 1;
  gap: 0;
  overflow: hidden;
}

/* MAP CONTAINER - Takes most of the space, and is the positioning
   context for the zoom controls + info panel so they never drift
   over the sidebar */
.map-container {
  flex: 1;
  background: linear-gradient(135deg, #1a1a1a 0%, #0f0f0f 100%);
  position: relative;
  z-index: 1;
  min-width: 0; /* allow flex child to shrink below content size */
}

#tunisia-map {
  width: 100%;
  height: 100%;
}

/* SIDEBAR PANEL - Right side */
.sidebar-panel {
  width: 360px;
  background: rgba(15, 15, 15, 0.95);
  border-left: 1px solid rgba(255, 255, 255, 0.08);
  display: flex;
  flex-direction: column;
  color: white;
  overflow: hidden;
}

.sidebar-header {
  padding: 16px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  flex-shrink: 0;
}

.sidebar-header h2 {
  margin: 0 0 12px 0;
  font-size: 18px;
  font-weight: 600;
  color: #fff;
}

.search-box {
  width: 100%;
  padding: 10px 12px;
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.15);
  border-radius: 6px;
  color: white;
  font-size: 13px;
  transition: all 0.2s;
}

.search-box::placeholder {
  color: rgba(255, 255, 255, 0.4);
}

.search-box:focus {
  outline: none;
  background: rgba(255, 255, 255, 0.12);
  border-color: rgba(255, 255, 255, 0.25);
}

/* SIDEBAR LEGEND */
.sidebar-legend {
  padding: 14px 16px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  flex-shrink: 0;
}

.legend-title-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 10px;
}

.legend-title {
  font-size: 12px;
  font-weight: 700;
  color: rgba(255, 255, 255, 0.6);
  text-transform: uppercase;
  letter-spacing: 0.6px;
}

.legend-reset {
  background: none;
  border: none;
  color: #c9a84c;
  font-size: 11px;
  font-weight: 600;
  cursor: pointer;
  padding: 2px 4px;
  opacity: 0.85;
  transition: opacity 0.2s;
}

.legend-reset:hover {
  opacity: 1;
  text-decoration: underline;
}

.legend-items {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

/* Legend chips — pill-shaped, colored, toggleable filters */
.legend-chip {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 7px 10px 7px 8px;
  border-radius: 999px;
  cursor: pointer;
  transition: all 0.2s ease;
  font-size: 12px;
  font-family: inherit;
  background: color-mix(in srgb, var(--chip-color) 14%, rgba(255,255,255,0.04));
  border: 1px solid color-mix(in srgb, var(--chip-color) 45%, rgba(255,255,255,0.12));
  color: rgba(255, 255, 255, 0.85);
}

.legend-chip:hover {
  transform: translateY(-1px);
  background: color-mix(in srgb, var(--chip-color) 24%, rgba(255,255,255,0.06));
  box-shadow: 0 4px 12px color-mix(in srgb, var(--chip-color) 35%, transparent);
}

.legend-chip.active {
  background: color-mix(in srgb, var(--chip-color) 30%, rgba(255,255,255,0.06));
  border-color: var(--chip-color);
  box-shadow: 0 0 0 1px color-mix(in srgb, var(--chip-color) 50%, transparent);
}

/* Dimmed / inactive state when filtered out */
.legend-chip.inactive {
  opacity: 0.4;
  background: rgba(255, 255, 255, 0.03);
  border-color: rgba(255, 255, 255, 0.1);
  box-shadow: none;
}

.legend-chip.inactive .legend-dot {
  box-shadow: none;
}

.legend-dot {
  width: 9px;
  height: 9px;
  border-radius: 50%;
  flex-shrink: 0;
  background: var(--chip-color);
  box-shadow: 0 0 8px var(--chip-color);
}

.legend-text {
  color: #fff;
  font-weight: 600;
  white-space: nowrap;
}

.legend-count {
  font-size: 10px;
  font-weight: 700;
  color: #000;
  background: var(--chip-color);
  border-radius: 999px;
  min-width: 18px;
  height: 18px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0 5px;
}

.sidebar-divider {
  height: 1px;
  background: rgba(255, 255, 255, 0.08);
}

/* LOCATIONS LIST */
.sidebar-content {
  flex: 1;
  overflow-y: auto;
  overflow-x: hidden;
}

.locations-list {
  padding: 8px;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.location-item {
  padding: 12px;
  background: rgba(255, 255, 255, 0.04);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 13px;
}

.location-item:hover {
  background: rgba(255, 255, 255, 0.08);
  border-color: rgba(255, 255, 255, 0.15);
}

.location-item.active {
  background: rgba(201, 168, 76, 0.2);
  border-color: rgba(201, 168, 76, 0.5);
  color: #c9a84c;
}

.location-name {
  font-weight: 600;
  color: white;
  margin-bottom: 4px;
}

.location-category {
  font-size: 11px;
  color: rgba(255, 255, 255, 0.5);
  text-transform: uppercase;
}

/* ZOOM CONTROLS - Floating */
.zoom-controls-floating {
  position: absolute;
  bottom: 120px;
  right: 16px;
  display: flex;
  flex-direction: column;
  gap: 8px;
  z-index: 900;
}

.zoom-btn {
  width: 44px;
  height: 44px;
  border-radius: 6px;
  background: rgba(0, 0, 0, 0.7);
  border: 1px solid rgba(255, 255, 255, 0.2);
  color: white;
  font-size: 20px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
}

.zoom-btn:hover {
  background: rgba(0, 0, 0, 0.85);
  border-color: rgba(255, 255, 255, 0.3);
  color: #c9a84c;
}

/* ═══════════════════════════════════════════════════════════════
   INFO PANEL — floating card, scoped to the map area only
═══════════════════════════════════════════════════════════════ */
.info-panel {
    position: absolute;
    bottom: 20px;
    right: 20px;
    width: 340px;
    max-width: calc(100% - 40px);
    background: rgba(18, 18, 20, 0.98);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    overflow: hidden;
    z-index: 1000;
    box-shadow:
      0 24px 60px rgba(0, 0, 0, 0.55),
      0 0 0 1px rgba(255, 255, 255, 0.02),
      0 0 32px -8px color-mix(in srgb, var(--accent, #c9a84c) 55%, transparent);
    display: none;
    flex-direction: column;
    max-height: min(70vh, 560px);
    opacity: 0;
    transform: translateY(16px) scale(0.98);
}

.info-panel.active {
    display: flex;
    animation: infoPanelIn 0.32s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@keyframes infoPanelIn {
  from { opacity: 0; transform: translateY(16px) scale(0.98); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}

/* Thin accent bar along the top, colored per category */
.info-panel::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 3px;
  background: var(--accent, #c9a84c);
  z-index: 5;
}

.info-header {
  position: relative;
  overflow: hidden;
  height: 190px;
  flex-shrink: 0;
  background: #0c0c0c;
}

.info-image-large {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 6s ease;
}

.info-panel.active .info-image-large {
  transform: scale(1.06);
}

/* Gradient fade so the category chip and heading edge stay legible */
.info-image-gradient {
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, rgba(0,0,0,0.05) 40%, rgba(12,12,14,0.95) 100%);
  z-index: 2;
}

.info-category {
  position: absolute;
  bottom: 12px;
  left: 14px;
  z-index: 3;
  display: inline-flex;
  align-items: center;
  padding: 5px 12px;
  background: var(--accent, #c9a84c);
  color: #101010;
  font-size: 10px;
  font-weight: 800;
  border-radius: 999px;
  text-transform: uppercase;
  letter-spacing: 0.6px;
  box-shadow: 0 4px 14px rgba(0,0,0,0.35);
}

/* Floating circular close button, sits over the image top-right */
.info-close-btn {
  position: absolute;
  top: 12px;
  right: 12px;
  z-index: 6;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(6px);
  border: 1px solid rgba(255, 255, 255, 0.15);
  color: rgba(255, 255, 255, 0.85);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

.info-close-btn:hover {
  background: rgba(220, 50, 50, 0.85);
  border-color: rgba(255, 255, 255, 0.3);
  color: white;
  transform: rotate(90deg);
}

.info-body {
  padding: 16px 18px 18px;
  flex: 1;
  overflow-y: auto;
}

.info-title {
  margin: 0 0 8px 0;
  font-size: 19px;
  font-weight: 700;
  color: white;
  line-height: 1.3;
  letter-spacing: -0.2px;
}

.info-description {
  margin: 0 0 14px 0;
  font-size: 13px;
  line-height: 1.6;
  color: rgba(255, 255, 255, 0.65);
  display: -webkit-box;
  -webkit-line-clamp: 4;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.info-coords {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 11px;
  color: rgba(255, 255, 255, 0.55);
  margin-bottom: 16px;
  padding: 6px 10px;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.06);
  border-radius: 999px;
  font-family: 'SF Mono', 'Roboto Mono', monospace;
}

.info-coords svg {
  color: var(--accent, #c9a84c);
  flex-shrink: 0;
}

.info-explore-btn {
  width: 100%;
  padding: 11px;
  background: linear-gradient(135deg, var(--accent, #c9a84c), color-mix(in srgb, var(--accent, #c9a84c) 70%, black));
  border: none;
  color: #101010;
  border-radius: 10px;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.info-explore-btn:hover {
  filter: brightness(1.08);
  box-shadow: 0 8px 22px -4px color-mix(in srgb, var(--accent, #c9a84c) 60%, transparent);
  transform: translateY(-1px);
}

.info-explore-btn svg {
  transition: transform 0.2s;
}

.info-explore-btn:hover svg {
  transform: translateX(3px);
}

/* Slim custom scrollbar for the body when description is long */
.info-body::-webkit-scrollbar {
  width: 5px;
}
.info-body::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.15);
  border-radius: 10px;
}

/* ANIMATIONS */
@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* RESPONSIVE */
@media (max-width: 1024px) {
  .sidebar-panel {
    width: 320px;
  }
}

@media (max-width: 768px) {
  .map-layout {
    flex-direction: column;
  }

  .map-container {
    flex: 1;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  }

  .sidebar-panel {
    width: 100%;
    height: 300px;
    border-left: none;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
  }

  .info-panel {
    width: 100%;
    max-width: 100%;
    bottom: 0;
    right: 0;
    border-radius: 16px 16px 0 0;
    max-height: 50vh;
  }

  .zoom-controls-floating {
    bottom: 320px;
  }
}

@media (max-width: 480px) {
  .map-header {
    padding: 12px 16px;
  }

  .header-left h1 {
    font-size: 18px;
  }

  .header-left p {
    font-size: 11px;
  }

  .sidebar-panel {
    height: 250px;
  }

  .info-panel {
    width: 100%;
    max-width: 100%;
    bottom: 0;
    right: 0;
    border-radius: 16px 16px 0 0;
    max-height: 60vh;
  }
}
</style>

<!-- LEAFLET & DATA SCRIPT -->
<script>
  window.mapLocations = @json($mapLocations);
  window.mapCategories = @json($mapCategories);
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  console.log('[Map] Initializing Heritage Map...');
  console.log('[Map] Locations:', window.mapLocations);

  // Initialize Leaflet map - Fixed to Tunisia
  const map = L.map('tunisia-map').setView([33.8869, 9.5375], 7);

  // Tunisia bounds
  const tunisiaBounds = [
    [30.2, 8.0],   // Southwest
    [37.5, 11.6]   // Northeast
  ];
  map.setMaxBounds(tunisiaBounds);

  // Add tiles
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 18,
  }).addTo(map);

  // Zoom controls
  document.getElementById('zoom-in').addEventListener('click', () => map.zoomIn());
  document.getElementById('zoom-out').addEventListener('click', () => map.zoomOut());

  // Store markers
  const markers = {};
  const locationsList = document.getElementById('locations-list');
  const infoPanel = document.getElementById('info-panel');
  const searchInput = document.getElementById('search-input');

  // Track which categories are currently active in the legend filter.
  // Starts with every category active (nothing filtered out).
  const activeCategories = new Set(window.mapCategories.map(c => c.slug));

  // Tally locations per category for the legend badges
  const categoryCounts = {};
  window.mapLocations.forEach(loc => {
    categoryCounts[loc.categorySlug] = (categoryCounts[loc.categorySlug] || 0) + 1;
  });
  window.mapCategories.forEach(cat => {
    const countEl = document.getElementById(`count-${cat.slug}`);
    if (countEl) countEl.textContent = categoryCounts[cat.slug] || 0;
  });

  // Add markers
  window.mapLocations.forEach((loc, index) => {
    const markerColor = window.mapCategories.find(c => c.slug === loc.categorySlug)?.color || '#FFD700';

    // Create custom marker with color
    const markerHTML = `<span style="color: ${markerColor}; font-size: 28px;">★</span>`;
    const customIcon = L.divIcon({
      html: markerHTML,
      className: 'custom-marker',
      iconSize: [30, 30],
      iconAnchor: [15, 15],
    });

    const marker = L.marker(loc.coords, { icon: customIcon }).addTo(map);
    marker.bindPopup(`<strong>${loc.name}</strong><br>${loc.category}`);

    // Clicking a star on the map now opens the same info panel as the sidebar,
    // and syncs the sidebar list highlight + scrolls it into view.
    marker.on('click', () => {
      showInfoPanel(loc);
      highlightLocation(index);
      const activeItem = locationsList.children[index];
      if (activeItem) {
        activeItem.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      }
    });

    markers[index] = marker;

    // Create location list item
    const listItem = document.createElement('div');
    listItem.className = 'location-item';
    listItem.dataset.category = loc.categorySlug;
    listItem.innerHTML = `
      <div class="location-name">${loc.name}</div>
      <div class="location-category">${loc.category}</div>
    `;

    listItem.addEventListener('click', () => {
      map.setView(loc.coords, 12);
      marker.openPopup();
      showInfoPanel(loc);
      highlightLocation(index);
    });

    locationsList.appendChild(listItem);
  });

  // Search functionality
  searchInput.addEventListener('input', (e) => {
    const term = e.target.value.toLowerCase();
    document.querySelectorAll('.location-item').forEach((item, index) => {
      const loc = window.mapLocations[index];
      const matchesSearch = loc.name.toLowerCase().includes(term);
      const matchesCategory = activeCategories.has(loc.categorySlug);
      item.style.display = (matchesSearch && matchesCategory) ? 'block' : 'none';
    });
  });

  // ── LEGEND FILTERING ──────────────────────────────────────────
  // Clicking a legend chip toggles that category on/off across both
  // the map markers and the sidebar list. Multiple categories can be
  // active at once; clicking the last active one re-shows everything
  // so the map never ends up empty by accident.
  const legendChips = document.querySelectorAll('.legend-chip');
  const legendReset = document.getElementById('legend-reset');

  function applyFilter() {
    window.mapLocations.forEach((loc, index) => {
      const visible = activeCategories.has(loc.categorySlug);
      const markerEl = markers[index].getElement();
      if (markerEl) markerEl.style.display = visible ? '' : 'none';

      const listItem = locationsList.children[index];
      if (listItem) {
        const matchesSearch = loc.name.toLowerCase().includes(searchInput.value.toLowerCase());
        listItem.style.display = (visible && matchesSearch) ? 'block' : 'none';
      }
    });

    legendChips.forEach(chip => {
      const isActive = activeCategories.has(chip.dataset.category);
      chip.classList.toggle('active', isActive);
      chip.classList.toggle('inactive', !isActive);
    });
  }

  legendChips.forEach(chip => {
    chip.addEventListener('click', () => {
      const slug = chip.dataset.category;

      if (activeCategories.has(slug)) {
        // Don't allow filtering down to zero categories — treat a click
        // on the only active chip as "select just this one" instead.
        if (activeCategories.size === 1) {
          window.mapCategories.forEach(c => activeCategories.add(c.slug));
        } else {
          activeCategories.delete(slug);
        }
      } else {
        activeCategories.add(slug);
      }

      applyFilter();
    });
  });

  legendReset.addEventListener('click', () => {
    window.mapCategories.forEach(c => activeCategories.add(c.slug));
    applyFilter();
  });

  // Info panel functions
  function showInfoPanel(location) {
    document.getElementById('info-title').textContent = location.name;
    document.getElementById('info-description').textContent = location.description;
    document.getElementById('info-category').textContent = location.category;
    document.getElementById('info-image').src = location.img || '{{ asset('images/placeholder.png') }}';
    document.getElementById('info-coords-text').textContent = `${location.coords[0].toFixed(4)}°N, ${location.coords[1].toFixed(4)}°E`;
    // Tint the card's accent bar, badge, coords icon and button to match this location's category color
    infoPanel.style.setProperty('--accent', location.color || '#c9a84c');
    infoPanel.classList.add('active');
  }

  function highlightLocation(index) {
    document.querySelectorAll('.location-item').forEach((item, i) => {
      item.classList.toggle('active', i === index);
    });
  }

  // Close info panel
  // Close info panel
document.getElementById('info-close-btn').addEventListener('click', () => {
    infoPanel.classList.remove('active');
});

// Optional: Close when clicking outside the panel (better UX)
infoPanel.addEventListener('click', (e) => {
    if (e.target === infoPanel) {
        infoPanel.classList.remove('active');
    }
});

// Close on Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        infoPanel.classList.remove('active');
    }
});
});
</script>

@endsection
