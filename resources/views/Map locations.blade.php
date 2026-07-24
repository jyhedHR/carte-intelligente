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

            <!-- HOLOGRAM FX — purely decorative, toggled by the sidebar switch -->
            <div class="hologram-grid" aria-hidden="true"></div>
            <div class="hologram-scanline" aria-hidden="true"></div>

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
                <div class="sidebar-header-top">
                    <h2>Lieux Patrimoine</h2>
                    <button type="button"
                            class="map-theme-toggle"
                            id="map-theme-toggle"
                            title="Basculer entre thème sombre et clair"
                            aria-label="Basculer entre thème sombre et clair"
                            aria-pressed="false">
                        <svg class="map-theme-icon map-theme-icon-moon" width="13" height="13" viewBox="0 0 24 24" fill="none"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span class="map-theme-track"><span class="map-theme-thumb"></span></span>
                        <svg class="map-theme-icon map-theme-icon-sun" width="13" height="13" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                </div>
                <input type="text" id="search-input" class="search-box" placeholder="Rechercher...">
            </div>

            <button type="button" class="hologram-toggle" id="hologram-toggle" aria-pressed="false" title="Activer l'effet hologramme 3D">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M12 2v12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><ellipse cx="12" cy="17.5" rx="7.5" ry="2.4" stroke="currentColor" stroke-width="1.8"/><ellipse cx="12" cy="17.5" rx="4.2" ry="1.3" stroke="currentColor" stroke-width="1.4" opacity="0.6"/></svg>
                <span>Mode Hologramme</span>
                <span class="hologram-toggle-pill"><span class="hologram-toggle-dot"></span></span>
            </button>

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
  background: linear-gradient(135deg, #131c2e 0%, #060910 100%);
  position: relative;
  z-index: 1;
  min-width: 0; /* allow flex child to shrink below content size */
}

#tunisia-map {
  width: 100%;
  height: 100%;
  background: #0a0e16;
}

/* ── HOLOGRAM MODE ────────────────────────────────────────────
   Purely visual: tilts the whole map in 3D space (like a tabletop
   projection), shifts its color toward cyan, adds a pulsing edge
   glow, a subtle flicker, a moving laser scanline, and a faint
   wireframe grid on top — all toggled by the sidebar switch. */
.map-container {
  perspective: 1400px;
}

#tunisia-map {
  transition: transform 0.6s ease, filter 0.6s ease, box-shadow 0.6s ease;
  transform-style: preserve-3d;
}

.map-container.hologram-mode #tunisia-map {
  transform: rotateX(7deg) scale(1.02);
  filter: saturate(1.5) contrast(1.15) hue-rotate(150deg) brightness(1.08);
  box-shadow: 0 0 55px rgba(56, 214, 255, 0.35), inset 0 0 70px rgba(56, 214, 255, 0.15);
  animation: hologram-flicker 6s infinite;
}

@keyframes hologram-flicker {
  0%, 96%, 100% { opacity: 1; }
  97% { opacity: 0.88; }
  98.5% { opacity: 0.97; }
}

.hologram-grid,
.hologram-scanline {
  position: absolute;
  inset: 0;
  pointer-events: none;
  opacity: 0;
  transition: opacity 0.4s ease;
}

.hologram-grid {
  z-index: 640;
  background-image:
    linear-gradient(rgba(94, 234, 255, 0.09) 1px, transparent 1px),
    linear-gradient(90deg, rgba(94, 234, 255, 0.09) 1px, transparent 1px);
  background-size: 34px 34px;
  mix-blend-mode: screen;
}

.hologram-scanline {
  z-index: 650;
  background: linear-gradient(
    to bottom,
    transparent 0%,
    rgba(94, 234, 255, 0.12) 46%,
    rgba(94, 234, 255, 0.32) 50%,
    rgba(94, 234, 255, 0.12) 54%,
    transparent 100%
  );
  background-size: 100% 280px;
  background-repeat: no-repeat;
  animation: hologram-scan 3.6s linear infinite;
  mix-blend-mode: screen;
}

.map-container.hologram-mode .hologram-grid,
.map-container.hologram-mode .hologram-scanline {
  opacity: 1;
}

@keyframes hologram-scan {
  0%   { background-position: 0 -280px; }
  100% { background-position: 0 calc(100% + 280px); }
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

.sidebar-header-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  margin-bottom: 12px;
}

.sidebar-header h2 {
  margin: 0;
  font-size: 18px;
  font-weight: 600;
  color: #fff;
}

/* MAP THEME TOGGLE — dark/light switch for the map only */
.map-theme-toggle {
  display: flex;
  align-items: center;
  gap: 6px;
  flex-shrink: 0;
  background: none;
  border: none;
  padding: 2px;
  cursor: pointer;
  color: rgba(255, 255, 255, 0.55);
}

.map-theme-icon-moon {
  color: #c9a84c;
}

.map-theme-icon-sun {
  color: rgba(255, 255, 255, 0.35);
}

.map-theme-toggle[aria-pressed="true"] .map-theme-icon-moon {
  color: rgba(255, 255, 255, 0.35);
}

.map-theme-toggle[aria-pressed="true"] .map-theme-icon-sun {
  color: #f5c94a;
}

.map-theme-track {
  position: relative;
  width: 34px;
  height: 18px;
  border-radius: 999px;
  background: rgba(255, 255, 255, 0.12);
  border: 1px solid rgba(255, 255, 255, 0.15);
  transition: background 0.25s ease, border-color 0.25s ease;
}

.map-theme-toggle[aria-pressed="true"] .map-theme-track {
  background: rgba(201, 168, 76, 0.35);
  border-color: rgba(201, 168, 76, 0.55);
}

.map-theme-thumb {
  position: absolute;
  top: 1px;
  left: 1px;
  width: 14px;
  height: 14px;
  border-radius: 50%;
  background: #f0e6c8;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
  transition: transform 0.25s ease;
}

.map-theme-toggle[aria-pressed="true"] .map-theme-thumb {
  transform: translateX(16px);
  background: #fff8e0;
}

/* Map area background swaps to a light parchment tone in light mode */
.map-container.map-theme-light {
  background: linear-gradient(135deg, #e9e2d3 0%, #d8cfb8 100%);
}

.map-container.map-theme-light #tunisia-map {
  background: #ded4bb;
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

/* HOLOGRAM MODE TOGGLE BUTTON — sits below the search box */
.hologram-toggle {
  display: flex;
  align-items: center;
  gap: 8px;
  width: 100%;
  margin-top: 10px;
  padding: 9px 12px;
  background: rgba(94, 234, 255, 0.06);
  border: 1px solid rgba(94, 234, 255, 0.25);
  border-radius: 8px;
  color: rgba(220, 245, 255, 0.85);
  font-size: 12.5px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}

.hologram-toggle:hover {
  background: rgba(94, 234, 255, 0.12);
  border-color: rgba(94, 234, 255, 0.45);
}

.hologram-toggle span:not(.hologram-toggle-pill):not(.hologram-toggle-dot) {
  flex: 1;
  text-align: left;
}

.hologram-toggle-pill {
  position: relative;
  width: 30px;
  height: 16px;
  border-radius: 999px;
  background: rgba(255, 255, 255, 0.12);
  border: 1px solid rgba(255, 255, 255, 0.15);
  flex-shrink: 0;
  transition: background 0.25s ease, border-color 0.25s ease;
}

.hologram-toggle-dot {
  position: absolute;
  top: 1px;
  left: 1px;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: rgba(220, 245, 255, 0.7);
  transition: transform 0.25s ease, background 0.25s ease;
}

.hologram-toggle[aria-pressed="true"] {
  background: rgba(94, 234, 255, 0.14);
  border-color: rgba(94, 234, 255, 0.6);
  color: #d7f8ff;
  box-shadow: 0 0 12px rgba(94, 234, 255, 0.25);
}

.hologram-toggle[aria-pressed="true"] .hologram-toggle-pill {
  background: rgba(94, 234, 255, 0.4);
  border-color: rgba(94, 234, 255, 0.7);
}

.hologram-toggle[aria-pressed="true"] .hologram-toggle-dot {
  transform: translateX(14px);
  background: #eafdff;
  box-shadow: 0 0 6px rgba(94, 234, 255, 0.9);
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
  background: rgba(255, 255, 255, 0.06);
  border: 1px solid rgba(255, 255, 255, 0.1);
  color: rgba(255, 255, 255, 0.85);
  font-size: 12px;
  font-weight: 600;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s;
  flex: 1;
  justify-content: center;
  white-space: nowrap;
}

.info-nav-btn:hover {
  background: color-mix(in srgb, var(--accent, #c9a84c) 22%, transparent);
  border-color: color-mix(in srgb, var(--accent, #c9a84c) 55%, transparent);
  color: var(--accent, #c9a84c);
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
  color: rgba(255, 255, 255, 0.45);
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

/* GOVERNORATE HOVER "VUE" CARD — bilingual name + heritage location
   list, opened on hover (interactive, so items inside stay clickable). */
.gov-tooltip-wrap {
  background: linear-gradient(160deg, rgba(22, 26, 36, 0.97) 0%, rgba(10, 12, 18, 0.97) 100%) !important;
  border: 1px solid rgba(201, 168, 76, 0.4) !important;
  border-radius: 12px !important;
  box-shadow: 0 14px 32px rgba(0, 0, 0, 0.55), 0 2px 0 rgba(255, 255, 255, 0.04) inset !important;
  padding: 0 !important;
  backdrop-filter: blur(6px);
  opacity: 1 !important;
}

.gov-tooltip-wrap::before {
  border-top-color: rgba(201, 168, 76, 0.4) !important;
}

.gov-popup {
  padding: 14px 16px 12px;
  min-width: 200px;
  max-width: 260px;
}

.gov-popup-header {
  border-bottom: 1px solid rgba(201, 168, 76, 0.2);
  padding-bottom: 8px;
  margin-bottom: 8px;
}

.gov-popup-titles h4 {
  margin: 0;
  font-size: 15px;
  font-weight: 700;
  color: #f0d68a;
  letter-spacing: 0.01em;
  white-space: normal;
}

.gov-popup-ar {
  display: block;
  font-size: 12px;
  color: rgba(240, 230, 200, 0.65);
  direction: rtl;
  margin-top: 1px;
}

.gov-popup-count {
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: rgba(240, 230, 200, 0.55);
  margin-bottom: 8px;
}

.gov-popup-list {
  list-style: none;
  margin: 0;
  padding: 0;
  max-height: 160px;
  overflow-y: auto;
}

.gov-popup-list li {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 6px 4px;
  border-radius: 6px;
  font-size: 13px;
  color: #f0e6c8;
  cursor: pointer;
  transition: background 0.15s;
  white-space: normal;
}

.gov-popup-list li:hover {
  background: rgba(201, 168, 76, 0.14);
}

.gov-popup-dot {
  flex-shrink: 0;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  box-shadow: 0 0 4px currentColor;
}

.gov-popup-item-name {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.gov-popup-empty {
  font-size: 12.5px;
  color: rgba(240, 230, 200, 0.45);
  font-style: italic;
  cursor: default !important;
  white-space: normal;
}

.gov-popup-empty:hover {
  background: transparent !important;
}

.gov-popup-list::-webkit-scrollbar {
  width: 5px;
}

.gov-popup-list::-webkit-scrollbar-thumb {
  background: rgba(201, 168, 76, 0.4);
  border-radius: 3px;
}

/* ALWAYS-VISIBLE GOVERNORATE NAME LABEL — centered in each region,
   no box/background, just outlined text so it reads over any theme
   (dark, light, or hologram) and any terrain color underneath. */
.gov-label-permanent {
  background: transparent !important;
  border: none !important;
  box-shadow: none !important;
  padding: 0 !important;
  margin: 0 !important;
  color: rgba(240, 214, 138, 0.92);
  font-size: 11.5px;
  font-weight: 700;
  letter-spacing: 0.05em;
  text-transform: uppercase;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.85), 0 0 6px rgba(0, 0, 0, 0.6);
  pointer-events: none !important;
  white-space: nowrap;
}

.gov-label-permanent::before {
  display: none !important;
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

  // ── RICH DARK THEME + 3D RELIEF ───────────────────────────────
  // Two stacked tile layers instead of the old flat single tile:
  //  1) baseTilePane  — the dark basemap, recolored via CSS filter
  //     from CARTO's neutral grays into a deeper indigo/teal palette.
  //  2) hillshadePane — a worldwide shaded-relief layer blended on
  //     top (mix-blend-mode) so mountains/valleys read as raised,
  //     giving the map a subtle 3D, embossed feel.
  // No more dimming mask or glowing border trace — the whole map is
  // now shown in the same rich theme, Tunisia included.
  map.createPane('baseTilePane');
  const baseTilePane = map.getPane('baseTilePane');
  baseTilePane.style.zIndex = 200;
  baseTilePane.style.filter = 'saturate(2.1) hue-rotate(192deg) brightness(0.78) contrast(1.2)';

  const baseTileLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_nolabels/{z}/{x}/{y}{r}.png', {
    attribution: '© OpenStreetMap contributors © <a href="https://carto.com/attributions">CARTO</a>',
    subdomains: 'abcd',
    maxZoom: 19,
    pane: 'baseTilePane',
  }).addTo(map);

  map.createPane('hillshadePane');
  const hillshadePane = map.getPane('hillshadePane');
  hillshadePane.style.zIndex = 210;
  hillshadePane.style.pointerEvents = 'none';
  hillshadePane.style.mixBlendMode = 'overlay';
  hillshadePane.style.opacity = '0.65';

  L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Elevation/World_Hillshade/MapServer/tile/{z}/{y}/{x}', {
    attribution: 'Hillshade © Esri',
    maxNativeZoom: 13,
    maxZoom: 19,
    pane: 'hillshadePane',
  }).addTo(map);

  // Tunisia's real national border (simplified) — kept only to clip the
  // place-name labels layer below to Tunisia (so neighboring countries'
  // city names never render), no dimming/glow effect applied to it anymore.
  const tunisiaBorderCoords = [[9.48214,30.307556],[9.055603,32.102692],[8.439103,32.506285],[8.430473,32.748337],[7.612642,33.344115],[7.524482,34.097376],[8.140981,34.655146],[8.376368,35.479876],[8.217824,36.433177],[8.420964,36.946427],[9.509994,37.349994],[10.210002,37.230002],[10.18065,36.724038],[11.028867,37.092103],[11.100026,36.899996],[10.600005,36.41],[10.593287,35.947444],[10.939519,35.698984],[10.807847,34.833507],[10.149593,34.330773],[10.339659,33.785742],[10.856836,33.76874],[11.108501,33.293343],[11.488787,33.136996],[11.432253,32.368903],[10.94479,32.081815],[10.636901,31.761421],[9.950225,31.37607],[10.056575,30.961831],[9.970017,30.539325],[9.48214,30.307556]];
  const tunisiaLatLngs = tunisiaBorderCoords.map(([lng, lat]) => [lat, lng]);

  // ── TUNISIA-ONLY LABELS ────────────────────────────────────────
  // A separate labels layer, sitting in its own pane clipped exactly
  // to Tunisia's border. This is how city names like Tunis, Sousse,
  // Kairouan stay visible while Algeria/Libya place names never
  // render anywhere, even though the rest of the map is no longer dimmed.
  map.createPane('tunisiaLabelsPane');
  const labelsPane = map.getPane('tunisiaLabelsPane');
  labelsPane.style.zIndex = 450; // above the base + hillshade panes, below markers (600)
  labelsPane.style.pointerEvents = 'none';

  const labelsTileLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_only_labels/{z}/{x}/{y}{r}.png', {
    subdomains: 'abcd',
    maxZoom: 19,
    pane: 'tunisiaLabelsPane',
  }).addTo(map);

  // ── DARK / LIGHT THEME SWITCH ──────────────────────────────────
  // Swaps the base + labels tile sources and re-tunes the color
  // filter / hillshade blend for each theme, driven by the sidebar
  // toggle further down. The recolor filter keeps the same rich
  // indigo/teal palette in dark mode and a soft, warm parchment
  // palette in light mode — both still run through the same
  // hillshade relief layer for the 3D terrain feel.
  const mapContainerEl = document.querySelector('.map-container');
  let mapTheme = 'dark';

  function setMapTheme(theme) {
    mapTheme = theme;
    const isLight = theme === 'light';

    baseTileLayer.setUrl(isLight
      ? 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager_nolabels/{z}/{x}/{y}{r}.png'
      : 'https://{s}.basemaps.cartocdn.com/dark_nolabels/{z}/{x}/{y}{r}.png');

    // Light mode is toned down on purpose — pure-white basemaps are
    // glary on screen, so brightness/contrast are pulled back below
    // their neutral (1.0) values and a faint warm cast is added,
    // similar to a "night light" filter, for easier reading. Dark
    // mode is left exactly as-is.
    baseTilePane.style.filter = isLight
      ? 'saturate(1.05) hue-rotate(-6deg) brightness(0.95) contrast(0.94) sepia(0.06)'
      : 'saturate(2.1) hue-rotate(192deg) brightness(0.78) contrast(1.2)';

    // Hillshade needs a different blend per theme — 'overlay' looks
    // rich on dark tiles, but washes out on a light basemap, so light
    // mode switches to 'multiply' at a lower opacity instead (kept
    // gentle here too, so relief doesn't muddy the softened palette).
    hillshadePane.style.mixBlendMode = isLight ? 'multiply' : 'overlay';
    hillshadePane.style.opacity = isLight ? '0.22' : '0.65';

    labelsTileLayer.setUrl(isLight
      ? 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager_only_labels/{z}/{x}/{y}{r}.png'
      : 'https://{s}.basemaps.cartocdn.com/dark_only_labels/{z}/{x}/{y}{r}.png');

    if (mapContainerEl) mapContainerEl.classList.toggle('map-theme-light', isLight);

    const themeToggleBtn = document.getElementById('map-theme-toggle');
    if (themeToggleBtn) themeToggleBtn.setAttribute('aria-pressed', String(isLight));
  }

  const themeToggleBtn = document.getElementById('map-theme-toggle');
  if (themeToggleBtn) {
    themeToggleBtn.addEventListener('click', () => {
      setMapTheme(mapTheme === 'dark' ? 'light' : 'dark');
    });
  }

  // ── HOLOGRAM MODE TOGGLE ───────────────────────────────────────
  // Purely a CSS/visual layer (3D tilt + cyan color shift + scanline
  // + grid overlay, defined in the stylesheet) — no map logic changes.
  const hologramToggle = document.getElementById('hologram-toggle');
  if (hologramToggle && mapContainerEl) {
    hologramToggle.addEventListener('click', () => {
      const isOn = mapContainerEl.classList.toggle('hologram-mode');
      hologramToggle.setAttribute('aria-pressed', String(isOn));
    });
  }

  function clipLabelsToTunisia() {
    const points = tunisiaLatLngs.map(([lat, lng]) => map.latLngToLayerPoint([lat, lng]));
    const clipPath = 'polygon(' + points.map(p => `${p.x}px ${p.y}px`).join(',') + ')';
    labelsPane.style.clipPath = clipPath;
    labelsPane.style.webkitClipPath = clipPath;
  }
  map.on('move zoom viewreset', clipLabelsToTunisia);
  map.whenReady(clipLabelsToTunisia);

  // ── GOVERNORATES OVERLAY ────────────────────────────────────────
  // A subtle "entourage" (outline) around each of Tunisia's 24
  // governorates — transparent by default so the relief/theme
  // underneath stays visible, lighting up with a soft gold tint and
  // a bilingual name tooltip on hover. Clicking a region opens a
  // "vue" popup listing every heritage location that falls inside
  // it, covering all 24 governorates — i.e. all of Tunisia.
  map.createPane('governoratesPane');
  const governoratesPane = map.getPane('governoratesPane');
  governoratesPane.style.zIndex = 500; // above labels (450), below markers (600)

  // Ray-casting point-in-polygon test. GeoJSON rings are [lng, lat],
  // while our locations are stored as [lat, lng] — order is handled here.
  function pointInRing(lat, lng, ring) {
    let inside = false;
    for (let i = 0, j = ring.length - 1; i < ring.length; j = i++) {
      const xi = ring[i][0], yi = ring[i][1];
      const xj = ring[j][0], yj = ring[j][1];
      const intersect = ((yi > lat) !== (yj > lat)) &&
        (lng < (xj - xi) * (lat - yi) / (yj - yi) + xi);
      if (intersect) inside = !inside;
    }
    return inside;
  }

  function pointInPolygonRings(lat, lng, rings) {
    if (!pointInRing(lat, lng, rings[0])) return false; // outside the outer ring
    for (let k = 1; k < rings.length; k++) {
      if (pointInRing(lat, lng, rings[k])) return false; // inside a hole
    }
    return true;
  }

  function isPointInGeometry(lat, lng, geometry) {
    if (geometry.type === 'Polygon') return pointInPolygonRings(lat, lng, geometry.coordinates);
    if (geometry.type === 'MultiPolygon') return geometry.coordinates.some(rings => pointInPolygonRings(lat, lng, rings));
    return false;
  }

  // Exposed globally so a governorate can be re-fit into view, and so
  // the hover card's location list (built as a plain HTML string) can
  // jump straight to a location's info panel.
  window.__govLayers = {};
  window.__zoomToGov = (id) => {
    const layer = window.__govLayers[id];
    if (layer) map.fitBounds(layer.getBounds(), { padding: [30, 30] });
  };
  window.__selectLocation = (idx) => selectLocation(idx, { pan: true });

  fetch("{{ Vite::asset('resources/assets/data/tunisia_governorates.geojson') }}")
    .then(res => res.json())
    .then(geojson => {
      const governoratesLayer = L.geoJSON(geojson, {
        pane: 'governoratesPane',
        style: {
          color: 'rgba(201, 168, 76, 0.55)',
          weight: 1.25,
          fill: true,
          fillColor: '#c9a84c',
          fillOpacity: 0.03,
        },
        onEachFeature: (feature, layer) => {
          const id = feature.properties.id;
          const nameEn = feature.properties.name_en || '';
          const nameAr = feature.properties.name_ar || '';

          window.__govLayers[id] = layer;

          // Every heritage location whose coordinates fall inside this
          // governorate's polygon (holes excluded) — listed in the hover card.
          const matchIndices = [];
          window.mapLocations.forEach((loc, idx) => {
            if (isPointInGeometry(loc.coords[0], loc.coords[1], feature.geometry)) {
              matchIndices.push(idx);
            }
          });

          const count = matchIndices.length;
          const countLabel = count === 0 ? 'Aucun lieu' : (count === 1 ? '1 lieu patrimonial' : `${count} lieux patrimoniaux`);

          const listHTML = matchIndices.length
            ? matchIndices.map(idx => {
                const loc = window.mapLocations[idx];
                return `<li onclick="window.__selectLocation(${idx})">
                          <span class="gov-popup-dot" style="background:${loc.color || '#c9a84c'}"></span>
                          <span class="gov-popup-item-name">${loc.name}</span>
                        </li>`;
              }).join('')
            : `<li class="gov-popup-empty">Aucun lieu enregistré pour l'instant</li>`;

          // Full "vue" card — same rich content as the old click popup,
          // but now opens purely on hover (interactive:true keeps it open
          // while the cursor moves onto the card itself to click a location).
          layer.bindTooltip(
            `<div class="gov-popup">
               <div class="gov-popup-header">
                 <div class="gov-popup-titles">
                   <h4>${nameEn}</h4>
                   <span class="gov-popup-ar">${nameAr}</span>
                 </div>
               </div>
               <div class="gov-popup-count">${countLabel}</div>
               <ul class="gov-popup-list">${listHTML}</ul>
             </div>`,
            { sticky: true, interactive: true, className: 'gov-tooltip-wrap', direction: 'top', opacity: 1 }
          );

          // Always-visible name label, centered in the region — so the
          // governorate's name reads directly off the map, no hover needed.
          L.tooltip({
            permanent: true,
            direction: 'center',
            className: 'gov-label-permanent',
            interactive: false,
            pane: 'tunisiaLabelsPane',
          })
            .setLatLng(layer.getBounds().getCenter())
            .setContent(nameEn)
            .addTo(map);

          // Give every region border a subtle raised, embossed look by
          // default, lifting further into a soft glow on hover — this is
          // what makes the whole map of Tunisia read as modern and 3D
          // rather than a flat outline.
          layer.on('add', () => {
            if (layer._path) {
              layer._path.style.transition = 'filter 0.25s ease, fill-opacity 0.25s ease';
              layer._path.style.filter = 'drop-shadow(0 2px 3px rgba(0, 0, 0, 0.45))';
            }
          });

          layer.on('mouseover', () => {
            layer.setStyle({ weight: 2.5, color: '#f0d68a', fillOpacity: 0.16 });
            layer.bringToFront();
            if (layer._path) {
              layer._path.style.filter = 'drop-shadow(0 0 8px rgba(240, 214, 138, 0.55)) drop-shadow(0 5px 10px rgba(0, 0, 0, 0.55))';
            }
          });

          layer.on('mouseout', () => {
            governoratesLayer.resetStyle(layer);
            if (layer._path) {
              layer._path.style.filter = 'drop-shadow(0 2px 3px rgba(0, 0, 0, 0.45))';
            }
          });

          // Click still zooms/fits the map to the region — quick way to
          // focus on one governorate without any popup getting in the way.
          layer.on('click', () => {
            map.fitBounds(layer.getBounds(), { padding: [30, 30] });
          });
        },
      }).addTo(map);
    })
    .catch(err => console.error('[Map] Failed to load governorates overlay:', err));

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
