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

                    <!-- PREV / NEXT NAVIGATION — move between locations without closing the panel -->
                    <div class="info-nav-row">
                        <button type="button" class="info-nav-btn" id="info-nav-prev" title="Lieu précédent" aria-label="Lieu précédent">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span>Précédent</span>
                        </button>
                        <span class="info-nav-counter" id="info-nav-counter">1 / 1</span>
                        <button type="button" class="info-nav-btn" id="info-nav-next" title="Lieu suivant" aria-label="Lieu suivant">
                            <span>Suivant</span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
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
  background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
  display: flex;
  flex-direction: column;
  z-index: 50;
  overflow: hidden;
}

/* HEADER BAR */
.map-header {
  background: linear-gradient(90deg, #0f172a 0%, #1a1f35 100%);
  backdrop-filter: blur(10px);
  border-bottom: 2px solid #b8860b;
  padding: 16px 24px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: #e0e7ff;
  flex-shrink: 0;
  box-shadow: 0 4px 20px rgba(0, 255, 136, 0.2);
}

.header-left h1 {
  margin: 0;
  font-size: 24px;
  font-weight: 700;
  color: #b8860b;
  text-shadow: 0 0 10px rgba(0, 255, 136, 0.5);
}

.header-left p {
  margin: 4px 0 0 0;
  font-size: 13px;
  color: #94a3b8;
}

.header-close-btn {
  background: rgba(0, 255, 136, 0.1);
  border: 2px solid #b8860b;
  color: #b8860b;
  width: 32px;
  height: 32px;
  border-radius: 8px;
  font-size: 18px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  box-shadow: 0 0 8px rgba(0, 255, 136, 0.3);
}

.header-close-btn:hover {
  background: #b8860b;
  border-color: #b8860b;
  color: #0f172a;
  transform: rotate(90deg);
  box-shadow: 0 0 15px rgba(0, 255, 136, 0.6);
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
  background: linear-gradient(135deg, #0f172a 0%, #1a1f35 100%);
  position: relative;
  z-index: 1;
  min-width: 0; /* allow flex child to shrink below content size */
}

#tunisia-map {
  width: 100%;
  height: 100%;
  background: #1a2332;
}

/* ── TUNISIA SHADOW & GLOW EFFECT ──────────────────────────────────────────
   Deep shadow and glow for Tunisia visibility */
.country-mask {
  display: block;
}

/* Neon glow effect for Tunisia border */
.tunisia-border-glow {
  display: block;
}

/* SIDEBAR PANEL - Right side */
.sidebar-panel {
  width: 360px;
  background: linear-gradient(180deg, #0f172a 0%, #1a1f35 100%);
  border-left: 3px solid #b8860b;
  display: flex;
  flex-direction: column;
  color: #e0e7ff;
  overflow: hidden;
  box-shadow: -4px 0 25px rgba(0, 255, 136, 0.15);
}

.sidebar-header {
  padding: 16px;
  border-bottom: 2px solid #b8860b;
  flex-shrink: 0;
  background: linear-gradient(90deg, #0f172a 0%, #1a1f35 100%);
}

.sidebar-header h2 {
  margin: 0 0 12px 0;
  font-size: 18px;
  font-weight: 600;
  color: #b8860b;
  text-shadow: 0 0 10px rgba(0, 255, 136, 0.4);
}

.search-box {
  width: 100%;
  padding: 10px 12px;
  background: rgba(0, 255, 136, 0.05);
  border: 2px solid #b8860b;
  border-radius: 8px;
  color: #e0e7ff;
  font-size: 13px;
  transition: all 0.3s ease;
  box-shadow: 0 0 8px rgba(0, 255, 136, 0.2);
}

.search-box::placeholder {
  color: #64748b;
}

.search-box:focus {
  outline: none;
  background: rgba(0, 255, 136, 0.08);
  border-color: #b8860b;
  box-shadow: 0 0 15px rgba(0, 255, 136, 0.4);
}

/* SIDEBAR LEGEND */
.sidebar-legend {
  padding: 14px 16px;
  border-bottom: 2px solid #b8860b;
  flex-shrink: 0;
  background: linear-gradient(90deg, rgba(0, 255, 136, 0.05) 0%, rgba(0, 255, 136, 0.02) 100%);
  box-shadow: inset 0 2px 8px rgba(0, 255, 136, 0.1);
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
  color: #b8860b;
  text-transform: uppercase;
  letter-spacing: 0.6px;
  text-shadow: 0 0 8px rgba(0, 255, 136, 0.3);
}

.legend-reset {
  background: none;
  border: none;
  color: #3b82f6;
  font-size: 11px;
  font-weight: 600;
  cursor: pointer;
  padding: 2px 4px;
  opacity: 0.85;
  transition: all 0.2s ease;
}

.legend-reset:hover {
  opacity: 1;
  text-decoration: underline;
  color: #2563eb;
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
  transition: all 0.3s ease;
  font-size: 12px;
  font-family: inherit;
  background: rgba(0, 255, 136, 0.05);
  border: 2px solid var(--chip-color);
  color: #e0e7ff;
  box-shadow: 0 0 8px color-mix(in srgb, var(--chip-color) 40%, transparent);
}

.legend-chip:hover {
  transform: translateY(-2px);
  background: rgba(0, 255, 136, 0.12);
  border-color: var(--chip-color);
  box-shadow: 0 0 16px var(--chip-color);
}

.legend-chip.active {
  background: rgba(0, 255, 136, 0.15);
  border-color: var(--chip-color);
  box-shadow: 0 0 20px color-mix(in srgb, var(--chip-color) 60%, transparent);
}

/* Dimmed / inactive state when filtered out */
.legend-chip.inactive {
  opacity: 0.4;
  background: rgba(0, 255, 136, 0.02);
  border-color: #1a1f35;
  box-shadow: none;
}

.legend-chip.inactive .legend-dot {
  box-shadow: 0 0 4px rgba(59, 130, 246, 0.3);
}

.legend-dot {
  width: 9px;
  height: 9px;
  border-radius: 50%;
  flex-shrink: 0;
  background: var(--chip-color);
  box-shadow: 0 0 8px color-mix(in srgb, var(--chip-color) 60%, transparent);
}

.legend-text {
  color: #1a1a2e;
  font-weight: 600;
  white-space: nowrap;
}

.legend-count {
  font-size: 10px;
  font-weight: 700;
  color: #ffffff;
  background: var(--chip-color);
  border-radius: 999px;
  min-width: 18px;
  height: 18px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0 5px;
  box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
}

.sidebar-divider {
  height: 2px;
  background: linear-gradient(90deg, transparent, #b8860b, transparent);
  box-shadow: 0 0 12px rgba(0, 255, 136, 0.4);
}

/* LOCATIONS LIST */
.sidebar-content {
  flex: 1;
  overflow-y: auto;
  overflow-x: hidden;
  background: #0f172a;
}

.locations-list {
  padding: 8px;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.location-item {
  padding: 12px;
  background: linear-gradient(90deg, rgba(0, 255, 136, 0.03) 0%, rgba(0, 255, 136, 0.01) 100%);
  border: 2px solid #1a1f35;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 13px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

.location-item:hover {
  background: rgba(0, 255, 136, 0.08);
  border-color: #b8860b;
  box-shadow: 0 4px 15px rgba(0, 255, 136, 0.25);
  transform: translateX(4px);
}

.location-item.active {
  background: rgba(0, 255, 136, 0.12);
  border-color: #b8860b;
  color: #b8860b;
  box-shadow: 0 0 16px rgba(0, 255, 136, 0.3);
  text-shadow: 0 0 6px rgba(0, 255, 136, 0.3);
}

.location-name {
  font-weight: 600;
  color: #e0e7ff;
  margin-bottom: 4px;
}

.location-category {
  font-size: 11px;
  color: #94a3b8;
  text-transform: uppercase;
  font-weight: 500;
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
  border-radius: 10px;
  background: rgba(0, 255, 136, 0.05);
  border: 2px solid #b8860b;
  color: #b8860b;
  font-size: 20px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 0 12px rgba(0, 255, 136, 0.2);
}

.zoom-btn:hover {
  background: #b8860b;
  border-color: #b8860b;
  color: #0f172a;
  transform: scale(1.08);
  box-shadow: 0 0 20px rgba(0, 255, 136, 0.5);
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
    background: linear-gradient(135deg, #0f172a 0%, #1a1f35 100%);
    backdrop-filter: blur(16px);
    border: 2px solid #b8860b;
    border-radius: 16px;
    overflow: hidden;
    z-index: 1000;
    box-shadow:
      0 24px 60px rgba(0, 255, 136, 0.25),
      0 0 0 1px rgba(0, 255, 136, 0.2),
      0 0 40px -8px rgba(0, 255, 136, 0.4);
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
  height: 4px;
  background: linear-gradient(90deg, var(--accent, #b8860b) 0%, color-mix(in srgb, var(--accent, #b8860b) 70%, #0f172a) 100%);
  z-index: 5;
  box-shadow: 0 0 15px var(--accent, #b8860b);
}

.info-header {
  position: relative;
  overflow: hidden;
  height: 190px;
  flex-shrink: 0;
  background: linear-gradient(135deg, #1a2332 0%, #0f172a 100%);
}

.info-image-large {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 6s ease;
  filter: brightness(0.8) saturate(1.1);
}

.info-panel.active .info-image-large {
  transform: scale(1.06);
}

/* Gradient fade so the category chip and heading edge stay legible */
.info-image-gradient {
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, rgba(0,255,136,0.1) 40%, rgba(26,35,50,0.95) 100%);
  z-index: 2;
}

.info-category {
  position: absolute;
  bottom: 12px;
  left: 14px;
  z-index: 3;
  display: inline-flex;
  align-items: center;
  padding: 6px 12px;
  background: var(--accent, #b8860b);
  color: #0f172a;
  font-size: 10px;
  font-weight: 800;
  border-radius: 999px;
  text-transform: uppercase;
  letter-spacing: 0.6px;
  box-shadow: 0 4px 14px rgba(0, 255, 136, 0.5), 0 0 20px rgba(0, 255, 136, 0.3);
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
  background: rgba(0, 255, 136, 0.1);
  backdrop-filter: blur(6px);
  border: 2px solid #b8860b;
  color: #b8860b;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  box-shadow: 0 0 12px rgba(0, 255, 136, 0.3);
}

.info-close-btn:hover {
  background: #ff0055;
  border-color: #ff0055;
  color: white;
  transform: rotate(90deg);
  box-shadow: 0 0 20px rgba(255, 0, 85, 0.6);
}

.info-body {
  padding: 16px 18px 18px;
  flex: 1;
  overflow-y: auto;
  background: linear-gradient(180deg, #0f172a 0%, #1a1f35 100%);
}

.info-title {
  margin: 0 0 8px 0;
  font-size: 19px;
  font-weight: 700;
  color: #b8860b;
  line-height: 1.3;
  letter-spacing: -0.2px;
  text-shadow: 0 0 8px rgba(0, 255, 136, 0.3);
}

.info-description {
  margin: 0 0 14px 0;
  font-size: 13px;
  line-height: 1.6;
  color: #cbd5e1;
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
  color: #94a3b8;
  margin-bottom: 16px;
  padding: 6px 10px;
  background: rgba(0, 255, 136, 0.05);
  border: 1px solid #b8860b;
  border-radius: 999px;
  font-family: 'SF Mono', 'Roboto Mono', monospace;
}

.info-coords svg {
  color: var(--accent, #3b82f6);
  flex-shrink: 0;
}

.info-explore-btn {
  width: 100%;
  padding: 11px;
  background: linear-gradient(135deg, var(--accent, #b8860b), color-mix(in srgb, var(--accent, #b8860b) 80%, #0f172a));
  border: none;
  color: #0f172a;
  border-radius: 10px;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  box-shadow: 0 0 20px rgba(0, 255, 136, 0.4);
}

.info-explore-btn:hover {
  filter: brightness(1.15);
  box-shadow: 0 0 30px rgba(0, 255, 136, 0.6);
  transform: translateY(-2px);
}

.info-explore-btn svg {
  transition: transform 0.3s ease;
}

.info-explore-btn:hover svg {
  transform: translateX(3px);
}

/* PREV / NEXT NAVIGATION ROW — sits below the explore button */
.info-nav-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 8px;
  margin-top: 10px;
}

.info-nav-btn {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 8px 12px;
  background: rgba(0, 255, 136, 0.05);
  border: 2px solid #b8860b;
  color: #b8860b;
  font-size: 12px;
  font-weight: 600;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  flex: 1;
  justify-content: center;
  white-space: nowrap;
  box-shadow: 0 0 8px rgba(0, 255, 136, 0.2);
}

.info-nav-btn:hover {
  background: rgba(0, 255, 136, 0.15);
  border-color: #b8860b;
  color: #b8860b;
  box-shadow: 0 0 16px rgba(0, 255, 136, 0.4);
}

.info-nav-btn:active {
  transform: scale(0.96);
}

.info-nav-btn:disabled {
  opacity: 0.35;
  cursor: not-allowed;
  pointer-events: none;
}

.info-nav-btn svg {
  flex-shrink: 0;
  transition: transform 0.15s;
}

.info-nav-btn:hover svg {
  transform: scale(1.1);
}

.info-nav-counter {
  font-size: 11px;
  font-weight: 600;
  color: #64748b;
  font-family: 'SF Mono', 'Roboto Mono', monospace;
  flex-shrink: 0;
  min-width: 44px;
  text-align: center;
}

@media (max-width: 480px) {
  .info-nav-btn span {
    display: none;
  }
  .info-nav-btn {
    flex: 0 0 auto;
    width: 40px;
    padding: 8px;
  }
}

/* Slim custom scrollbar for the body when description is long */
.info-body::-webkit-scrollbar {
  width: 5px;
}
.info-body::-webkit-scrollbar-thumb {
  background: #b8860b;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 255, 136, 0.5);
}

.sidebar-content::-webkit-scrollbar {
  width: 5px;
}
.sidebar-content::-webkit-scrollbar-thumb {
  background: #b8860b;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 255, 136, 0.5);
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
  const map = L.map('tunisia-map', {
    zoomControl: false,       // we use our own custom zoom buttons
    minZoom: 6,
    maxZoom: 18,
    maxBoundsViscosity: 1.0,  // fully resist dragging past the bounds
  }).setView([33.8869, 9.5375], 6.6);

  // Give a little breathing room around Tunisia's real border so the
  // blurred/dimmed neighboring area is visible — this is what makes
  // Tunisia visually "pop" against its surroundings.
  const tunisiaBounds = [
    [28.7, 5.8],   // Southwest
    [38.6, 13.2]   // Northeast
  ];
  map.setMaxBounds(tunisiaBounds);

  // Modern dark basemap with high contrast — label-free version. We add place names back
  // in separately below, clipped strictly to Tunisia, so neighboring
  // countries never show any city/country names at all.
  L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_nolabels/{z}/{x}/{y}{r}.png', {
    attribution: '© OpenStreetMap contributors © <a href="https://carto.com/attributions">CARTO</a>',
    subdomains: 'abcd',
    maxZoom: 19,
  }).addTo(map);

  // ── TUNISIA SPOTLIGHT MASK ────────────────────────────────────
  // Tunisia's real national border (simplified), used to cut a "hole"
  // in the dimming mask, trace the glowing outline, and clip the
  // labels layer below.
  const tunisiaBorderCoords = [[9.48214,30.307556],[9.055603,32.102692],[8.439103,32.506285],[8.430473,32.748337],[7.612642,33.344115],[7.524482,34.097376],[8.140981,34.655146],[8.376368,35.479876],[8.217824,36.433177],[8.420964,36.946427],[9.509994,37.349994],[10.210002,37.230002],[10.18065,36.724038],[11.028867,37.092103],[11.100026,36.899996],[10.600005,36.41],[10.593287,35.947444],[10.939519,35.698984],[10.807847,34.833507],[10.149593,34.330773],[10.339659,33.785742],[10.856836,33.76874],[11.108501,33.293343],[11.488787,33.136996],[11.432253,32.368903],[10.94479,32.081815],[10.636901,31.761421],[9.950225,31.37607],[10.056575,30.961831],[9.970017,30.539325],[9.48214,30.307556]];
  const tunisiaLatLngs = tunisiaBorderCoords.map(([lng, lat]) => [lat, lng]);

  // A large rectangle covering the whole pannable world, with
  // Tunisia's outline punched out as a hole — Leaflet renders holes
  // with an evenodd fill-rule, so Tunisia itself is left untouched.
  // fillColor is set explicitly here (Leaflet defaults to bright blue
  // otherwise, and its inline style wins over a plain CSS "fill" rule).
  const worldRing = [[-85, -180], [-85, 180], [85, 180], [85, -180]];
  L.polygon([worldRing, tunisiaLatLngs], {
    className: 'country-mask',
    stroke: false,
    fillColor: '#0a0e1a',
    fillOpacity: 0.8,
    interactive: false,
  }).addTo(map);

  // Neon glow border traced exactly along Tunisia's coastline/frontier
  L.polygon(tunisiaLatLngs, {
    className: 'tunisia-border-glow',
    color: '#b8860b',
    weight: 3,
    opacity: 1,
    fill: false,
    interactive: false,
  }).addTo(map);

  // ── TUNISIA-ONLY LABELS ────────────────────────────────────────
  // A separate labels layer, sitting in its own pane clipped exactly
  // to Tunisia's border. This is how city names like Tunis, Sousse,
  // Kairouan stay visible while Algeria/Libya place names never
  // render anywhere, even in the dimmed/blurred surrounding area.
  map.createPane('tunisiaLabelsPane');
  const labelsPane = map.getPane('tunisiaLabelsPane');
  labelsPane.style.zIndex = 450; // above the mask (overlayPane, 400), below markers (600)
  labelsPane.style.pointerEvents = 'none';

  L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_only_labels/{z}/{x}/{y}{r}.png', {
    subdomains: 'abcd',
    maxZoom: 19,
    pane: 'tunisiaLabelsPane',
  }).addTo(map);

  function clipLabelsToTunisia() {
    const points = tunisiaLatLngs.map(([lat, lng]) => map.latLngToLayerPoint([lat, lng]));
    const clipPath = 'polygon(' + points.map(p => `${p.x}px ${p.y}px`).join(',') + ')';
    labelsPane.style.clipPath = clipPath;
    labelsPane.style.webkitClipPath = clipPath;
  }
  map.on('move zoom viewreset', clipLabelsToTunisia);
  map.whenReady(clipLabelsToTunisia);

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
      selectLocation(index, { pan: false });
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
      selectLocation(index, { pan: true });
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
    if (infoPanel.classList.contains('active')) updateNavControls();
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

    if (infoPanel.classList.contains('active')) updateNavControls();
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
  // Tracks which location (index into window.mapLocations) is currently
  // shown in the info panel, so the Précédent/Suivant buttons know where
  // to move from.
  let currentLocationIndex = null;
  const navPrevBtn = document.getElementById('info-nav-prev');
  const navNextBtn = document.getElementById('info-nav-next');
  const navCounter = document.getElementById('info-nav-counter');

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

  // Indices of locations currently passing the category filter + search box,
  // in the same order they appear in the sidebar list. Précédent/Suivant
  // step through this list, so a user browsing a filtered/searched subset
  // never jumps to a hidden location.
  function getVisibleIndices() {
    const term = searchInput.value.toLowerCase();
    const indices = [];
    window.mapLocations.forEach((loc, i) => {
      const matchesSearch = loc.name.toLowerCase().includes(term);
      const matchesCategory = activeCategories.has(loc.categorySlug);
      if (matchesSearch && matchesCategory) indices.push(i);
    });
    return indices;
  }

  function updateNavControls() {
    const visible = getVisibleIndices();
    const pos = visible.indexOf(currentLocationIndex);
    const total = visible.length;

    if (navCounter) {
      navCounter.textContent = total > 0 ? `${pos + 1} / ${total}` : '0 / 0';
    }
    // Only disable when there's nothing (or only one thing) to move to —
    // otherwise buttons stay enabled and wrap around from either end.
    const disable = total <= 1;
    if (navPrevBtn) navPrevBtn.disabled = disable;
    if (navNextBtn) navNextBtn.disabled = disable;
  }

  // Opens the info panel for a given location index, syncs the sidebar
  // highlight + scroll, and optionally pans/zooms the map to it. Used by
  // marker clicks, sidebar clicks, and the Précédent/Suivant buttons alike
  // so all three stay perfectly in sync.
  function selectLocation(index, { pan = false } = {}) {
    const loc = window.mapLocations[index];
    if (!loc) return;

    currentLocationIndex = index;

    if (pan) {
      map.setView(loc.coords, 12);
    }
    const marker = markers[index];
    if (marker) marker.openPopup();

    showInfoPanel(loc);
    highlightLocation(index);
    updateNavControls();

    const activeItem = locationsList.children[index];
    if (activeItem) {
      activeItem.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
  }

  // Steps to the previous/next location within the currently visible
  // (filtered/searched) set, wrapping around at either end.
  function navigateInfoPanel(direction) {
    const visible = getVisibleIndices();
    if (visible.length === 0) return;

    let pos = visible.indexOf(currentLocationIndex);
    if (pos === -1) {
      // Current location got filtered out from under us — start from the
      // beginning rather than doing nothing.
      pos = direction > 0 ? -1 : 0;
    }

    const nextPos = (pos + direction + visible.length) % visible.length;
    selectLocation(visible[nextPos], { pan: true });
  }

  if (navPrevBtn) navPrevBtn.addEventListener('click', () => navigateInfoPanel(-1));
  if (navNextBtn) navNextBtn.addEventListener('click', () => navigateInfoPanel(1));

  // Left/Right arrow keys also step through locations while the panel is open,
  // as long as the user isn't typing in the search box.
  document.addEventListener('keydown', (e) => {
    if (!infoPanel.classList.contains('active')) return;
    if (document.activeElement === searchInput) return;
    if (e.key === 'ArrowRight') navigateInfoPanel(1);
    if (e.key === 'ArrowLeft') navigateInfoPanel(-1);
  });

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
