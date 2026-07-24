@extends('shared.layouts.frontoffice')

@section('page-title', 'Carte du Patrimoine Tunisien')

@section('content')

<div class="map-page-container">

    <!-- TOP MAP HEADER BAR -->
    <div class="map-header">
        <div class="header-left">
            <div class="header-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="currentColor" opacity="0.9"/>
                    <circle cx="12" cy="9" r="2.5" fill="#1a1a1a"/>
                </svg>
            </div>
            <div>
                <h1 class="map-title">Patrimoine Tunisien</h1>
                <p class="map-subtitle">Découvrez les trésors culturels de la Tunisie</p>
            </div>
        </div>
        <div class="header-right">
            <div class="header-stat">
                <span class="stat-num" id="anim-total" data-target="{{ count($mapLocations) }}">0</span>
                <span class="stat-label">lieux</span>
            </div>
            <div class="header-divider"></div>
            <div class="header-stat">
                <span class="stat-num" id="anim-govs" data-target="24">0</span>
                <span class="stat-label">gouvernorats</span>
            </div>
            <div class="header-divider"></div>
            <div class="header-stat">
                <span class="stat-num" id="anim-dels" data-target="264">0</span>
                <span class="stat-label">délégations</span>
            </div>
            <a href="{{ url()->previous() }}" class="header-close-btn" title="Retour">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                    <path d="M1 1L13 13M13 1L1 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </a>
        </div>
    </div>

    <!-- MAP & SIDEBAR WRAPPER -->
    <div class="map-layout">

        <!-- MAP -->
        <div class="map-container">
            <div id="tunisia-map"></div>

            <!-- HERO GLOW — centered on Tunisia -->
            <div class="tunisia-hero-glow"></div>

            <!-- VIGNETTE OVERLAY -->
            <div class="map-vignette"></div>

            <!-- ZOOM CONTROLS -->
            <div class="zoom-controls-floating">
                <button class="zoom-btn" id="zoom-in" title="Zoom avant">+</button>
                <button class="zoom-btn" id="zoom-out" title="Zoom arrière">−</button>
                <div class="zoom-sep"></div>
                <button class="zoom-btn zoom-reset-btn" id="zoom-reset" title="Recentrer">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm0-11a3 3 0 100 6 3 3 0 000-6z" fill="currentColor"/>
                    </svg>
                </button>
            </div>

            <!-- RICH GOVERNORATE TOOLTIP -->
            <div class="gov-tooltip" id="gov-tooltip">
                <div class="gov-tooltip-header">
                    <div class="gov-tooltip-pin">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="gov-tooltip-name" id="gov-tooltip-name"></div>
                        <div class="gov-tooltip-ar" id="gov-tooltip-ar"></div>
                    </div>
                </div>
                <div class="gov-tooltip-meta" id="gov-tooltip-meta">
                    <div class="gov-tooltip-meta-item">
                        <span class="gov-tooltip-meta-val" id="tt-sites">—</span>
                        <span class="gov-tooltip-meta-label">lieux</span>
                    </div>
                    <div class="gov-tooltip-divider"></div>
                    <div class="gov-tooltip-meta-item">
                        <span class="gov-tooltip-meta-val">›</span>
                        <span class="gov-tooltip-meta-label">Explorer</span>
                    </div>
                </div>
            </div>

            <!-- SELECTED GOVERNORATE BANNER -->
            <div class="gov-banner" id="gov-banner">
                <div class="gov-banner-inner">
                    <div class="gov-banner-swatch" id="gov-banner-swatch"></div>
                    <div class="gov-banner-text">
                        <span class="gov-banner-name" id="gov-banner-name"></span>
                        <span class="gov-banner-ar" id="gov-banner-ar"></span>
                    </div>
                    <span class="gov-banner-count" id="gov-banner-count"></span>
                    <button class="gov-banner-close" id="gov-banner-close">
                        <svg width="10" height="10" viewBox="0 0 14 14" fill="none">
                            <path d="M1 1L13 13M13 1L1 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- INFO PANEL -->
            <div class="info-panel" id="info-panel">
                <button class="info-close-btn" id="info-close-btn">
                    <svg width="13" height="13" viewBox="0 0 14 14" fill="none">
                        <path d="M1 1L13 13M13 1L1 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
                <div class="info-header">
                    <img id="info-image" src="{{ asset('images/placeholder.png') }}" alt="" class="info-image-large">
                    <div class="info-image-gradient"></div>
                    <span class="info-category" id="info-category">CATEGORY</span>
                </div>
                <div class="info-body">
                    <h2 class="info-title" id="info-title">Location Name</h2>
                    <p class="info-description" id="info-description">Description</p>
                    <div class="info-coords">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none">
                            <path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21z" stroke="currentColor" stroke-width="1.6"/>
                            <circle cx="12" cy="9.5" r="2.3" stroke="currentColor" stroke-width="1.6"/>
                        </svg>
                        <span id="info-coords-text"></span>
                    </div>
                    <a href="#" class="info-explore-btn" id="info-explore-btn">
                        <span>Explorer Plus</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDEBAR -->
        <div class="sidebar-panel">

            <!-- SIDEBAR HEADER -->
            <div class="sidebar-header">
                <h2>Lieux Patrimoine</h2>
                <div class="search-wrapper">
                    <svg class="search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none">
                        <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/>
                        <path d="m16.5 16.5 4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <input type="text" id="search-input" class="search-box" placeholder="Site, gouvernorat, délégation...">
                    <button class="search-clear" id="search-clear">×</button>
                </div>
            </div>

            <!-- SUMMARY CARDS -->
            <div class="sidebar-summary">
                <div class="summary-card">
                    <div class="summary-icon">🏛</div>
                    <div class="summary-info">
                        <div class="summary-val" id="sum-sites">{{ count($mapLocations) }}</div>
                        <div class="summary-label">Sites</div>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-icon">🗺</div>
                    <div class="summary-info">
                        <div class="summary-val">24</div>
                        <div class="summary-label">Gouvernorats</div>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-icon">🏘</div>
                    <div class="summary-info">
                        <div class="summary-val">264</div>
                        <div class="summary-label">Délégations</div>
                    </div>
                </div>
            </div>

            <!-- LAYER TOGGLES -->
            <div class="sidebar-layer-toggles">
                <div class="layer-toggle-row">
                    <span class="layer-toggle-label">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" style="flex-shrink:0">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Gouvernorats
                    </span>
                    <button class="pill-toggle" id="gov-toggle-btn" role="switch" aria-checked="true">
                        <span class="pill-knob"></span>
                    </button>
                </div>
                <div class="layer-toggle-row">
                    <span class="layer-toggle-label">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" style="flex-shrink:0">
                            <rect x="3" y="3" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="2"/>
                            <rect x="14" y="3" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="2"/>
                            <rect x="3" y="14" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="2"/>
                            <rect x="14" y="14" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        Délégations
                    </span>
                    <button class="pill-toggle" id="detail-toggle-btn" role="switch" aria-checked="false">
                        <span class="pill-knob"></span>
                    </button>
                </div>
            </div>

            <!-- CATEGORIES LEGEND -->
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

            <!-- LOCATIONS LIST -->
            <div class="sidebar-content">
                <div class="locations-list" id="locations-list"></div>
            </div>
        </div>
    </div>
</div>

<style>
/* ═══════════════════════════════════════════════════════════════
   PATRIMOINE TUNISIEN — Premium Dark Map v3
   Full upgrade: gradient govs, hero glow, animated counters,
   layer toggles, rich tooltip, sidebar summary cards
═══════════════════════════════════════════════════════════════ */

*, *::before, *::after { box-sizing: border-box; }

:root {
  --gold:        #C9A84C;
  --gold-bright: #E8C76A;
  --gold-glow:   rgba(201, 168, 76, 0.22);
  --gold-dim:    rgba(201, 168, 76, 0.12);
  --dark-bg:     #080810;
  --panel-bg:    rgba(10, 10, 16, 0.98);
  --border:      rgba(255, 255, 255, 0.06);
  --border-gold: rgba(201, 168, 76, 0.28);
  --text:        #f0ede8;
  --text-muted:  rgba(240, 237, 232, 0.42);
  --radius:      10px;
}

/* ── MAIN CONTAINER ───────────────────────────────────────────── */
.map-page-container {
  position: fixed;
  inset: 0;
  background: var(--dark-bg);
  display: flex;
  flex-direction: column;
  z-index: 50;
  overflow: hidden;
  font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
  /* Push below site header — adjust if your header height differs */
  padding-top: 64px;
}

/* ── MAP HEADER ───────────────────────────────────────────────── */
.map-header {
  background: rgba(8, 8, 16, 0.97);
  backdrop-filter: blur(16px);
  border-bottom: 1px solid var(--border);
  padding: 11px 22px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-shrink: 0;
  position: relative;
  z-index: 10;
  animation: revealDown 0.45s cubic-bezier(0.16,1,0.3,1) both;
}

.map-header::after {
  content: '';
  position: absolute;
  bottom: 0; left: 0; right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent 0%, var(--gold) 30%, var(--gold) 70%, transparent 100%);
  opacity: 0.28;
}

.header-left  { display: flex; align-items: center; gap: 12px; }
.header-right { display: flex; align-items: center; gap: 18px; }

.header-icon {
  width: 36px; height: 36px;
  background: linear-gradient(135deg, var(--gold), #7a5b10);
  border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
  color: #1a1a1a; flex-shrink: 0;
  box-shadow: 0 0 20px rgba(201,168,76,0.35);
}

.map-title {
  margin: 0;
  font-size: 18px; font-weight: 800;
  color: var(--text);
  letter-spacing: -0.4px;
}

.map-subtitle {
  margin: 3px 0 0;
  font-size: 11.5px;
  color: rgba(240, 237, 232, 0.6); /* slightly more visible than before */
  letter-spacing: 0.2px;
}

.header-stat {
  display: flex; flex-direction: column; align-items: center; gap: 1px;
}
.stat-num {
  font-size: 15px; font-weight: 800;
  color: var(--gold); line-height: 1;
  font-variant-numeric: tabular-nums;
}
.stat-label {
  font-size: 9px; font-weight: 600;
  color: var(--text-muted);
  text-transform: uppercase; letter-spacing: 0.8px;
}
.header-divider { width: 1px; height: 26px; background: var(--border); }

.header-close-btn {
  width: 32px; height: 32px; border-radius: 8px;
  background: rgba(255,255,255,0.05);
  border: 1px solid var(--border);
  color: var(--text-muted);
  display: flex; align-items: center; justify-content: center;
  transition: all 0.2s; text-decoration: none;
}
.header-close-btn:hover {
  background: rgba(220,50,50,0.18);
  border-color: rgba(220,50,50,0.4);
  color: #ff6b6b;
}

/* ── LAYOUT ───────────────────────────────────────────────────── */
.map-layout {
  display: flex;
  flex: 1;
  overflow: hidden;
}

.map-container {
  flex: 1;
  position: relative;
  min-width: 0;
  background: #050510;
  animation: revealFade 0.8s ease 0.15s both;
}

#tunisia-map {
  width: 100%; height: 100%;
}

/* Hide Leaflet default controls */
.leaflet-control-zoom,
.leaflet-control-attribution { display: none !important; }

/* Dark tiles */
.leaflet-tile-pane {
  filter: brightness(0.72) saturate(0.65) contrast(1.08);
}

/* ── HERO GLOW — soft gold halo behind Tunisia ────────────────── */
.tunisia-hero-glow {
  position: absolute;
  /* Tunisia sits roughly 38% from top, 46% from left in the map viewport */
  top: 15%; left: 20%;
  width: 60%; height: 70%;
  pointer-events: none;
  z-index: 350;
  background: radial-gradient(
    ellipse 55% 65% at 50% 50%,
    rgba(201, 168, 76, 0.055) 0%,
    rgba(201, 168, 76, 0.022) 45%,
    transparent 75%
  );
  animation: heroBreath 5s ease-in-out infinite;
}

@keyframes heroBreath {
  0%, 100% { opacity: 0.8; transform: scale(1); }
  50%       { opacity: 1;   transform: scale(1.04); }
}

/* ── VIGNETTE — darker outside Tunisia ───────────────────────── */
.map-vignette {
  position: absolute; inset: 0;
  pointer-events: none;
  z-index: 400;
  background: radial-gradient(
    ellipse 68% 72% at 50% 46%,
    transparent 30%,
    rgba(5, 5, 16, 0.45) 55%,
    rgba(5, 5, 16, 0.82) 76%,
    rgba(5, 5, 16, 0.97) 90%,
    #050510 100%
  );
}

/* ── GOVERNORATE LAYER ────────────────────────────────────────── */
.leaflet-interactive {
  cursor: pointer;
  transition: fill 0.22s ease, fill-opacity 0.22s ease, stroke-width 0.2s ease, stroke 0.2s ease;
}

/* breathing border glow on the whole SVG overlay */
.gov-layer-active .leaflet-overlay-pane svg {
  animation: govGlow 3.8s ease-in-out infinite;
}

@keyframes govGlow {
  0%,100% { filter: drop-shadow(0 0 2px rgba(201,168,76,0.25)); }
  50%      { filter: drop-shadow(0 0 6px rgba(201,168,76,0.5)); }
}

/* ── RICH GOVERNORATE TOOLTIP ─────────────────────────────────── */
.gov-tooltip {
  position: absolute;
  z-index: 800;
  background: rgba(5, 5, 14, 0.98);
  backdrop-filter: blur(14px);
  border: 1px solid var(--border-gold);
  border-radius: 14px;
  padding: 12px 15px;
  pointer-events: none;
  display: none;
  min-width: 170px;
  box-shadow:
    0 16px 48px rgba(0,0,0,0.7),
    0 0 0 1px rgba(201,168,76,0.05),
    0 0 32px -10px rgba(201,168,76,0.28);
}

.gov-tooltip.visible {
  display: block;
  animation: tooltipIn 0.16s cubic-bezier(0.16,1,0.3,1) both;
}

@keyframes tooltipIn {
  from { opacity: 0; transform: translateY(7px) scale(0.97); }
  to   { opacity: 1; transform: translateY(0)  scale(1); }
}

.gov-tooltip-header {
  display: flex; align-items: center; gap: 9px;
  margin-bottom: 9px;
}

.gov-tooltip-pin {
  width: 24px; height: 24px;
  border-radius: 50%;
  background: var(--gov-color, var(--gold-dim));
  border: 1px solid var(--gov-color, var(--border-gold));
  display: flex; align-items: center; justify-content: center;
  color: var(--gov-color, var(--gold));
  flex-shrink: 0;
  box-shadow: 0 0 12px var(--gov-color, var(--gold-glow));
}

.gov-tooltip-name {
  font-size: 14px; font-weight: 700;
  color: var(--text);
  letter-spacing: 0.2px;
}
.gov-tooltip-ar {
  font-size: 11px; color: var(--gold);
  direction: rtl; margin-top: 2px;
}

.gov-tooltip-meta {
  display: flex; align-items: center; gap: 8px;
  padding-top: 9px;
  border-top: 1px solid rgba(255,255,255,0.06);
}

.gov-tooltip-meta-item {
  display: flex; flex-direction: column; align-items: center;
  gap: 2px; flex: 1;
}

.gov-tooltip-meta-val {
  font-size: 15px; font-weight: 800;
  color: var(--gold); line-height: 1;
}

.gov-tooltip-meta-label {
  font-size: 9px; color: var(--text-muted);
  text-transform: uppercase; letter-spacing: 0.6px;
}

.gov-tooltip-divider {
  width: 1px; height: 28px;
  background: rgba(255,255,255,0.07);
  flex-shrink: 0;
}

/* ── SELECTED GOVERNORATE BANNER ──────────────────────────────── */
.gov-banner {
  position: absolute;
  top: 14px; left: 50%; transform: translateX(-50%);
  z-index: 700;
  pointer-events: none;
  opacity: 0;
  transition: opacity 0.28s ease, transform 0.28s cubic-bezier(0.16,1,0.3,1);
  transform: translateX(-50%) translateY(-10px);
}
.gov-banner.visible {
  opacity: 1; pointer-events: auto;
  transform: translateX(-50%) translateY(0);
}

.gov-banner-inner {
  display: flex; align-items: center; gap: 10px;
  background: rgba(5, 5, 14, 0.96);
  backdrop-filter: blur(16px);
  border: 1px solid var(--border-gold);
  border-radius: 999px;
  padding: 8px 12px 8px 10px;
  box-shadow:
    0 12px 40px rgba(0,0,0,0.6),
    0 0 24px -6px rgba(201,168,76,0.35);
}

.gov-banner-swatch {
  width: 10px; height: 10px; border-radius: 50%;
  flex-shrink: 0;
  box-shadow: 0 0 8px currentColor;
}

.gov-banner-text { display: flex; align-items: center; gap: 8px; }
.gov-banner-name { font-size: 14px; font-weight: 700; color: var(--text); }
.gov-banner-ar   { font-size: 11px; color: var(--gold); direction: rtl; }

.gov-banner-count {
  font-size: 11px; font-weight: 700;
  color: #080810; background: var(--gold);
  border-radius: 999px; padding: 2px 8px;
  min-width: 20px; text-align: center;
}

.gov-banner-close {
  width: 22px; height: 22px; border-radius: 50%;
  background: rgba(255,255,255,0.07);
  border: 1px solid rgba(255,255,255,0.1);
  color: var(--text-muted);
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all 0.18s; padding: 0;
}
.gov-banner-close:hover {
  background: rgba(220,50,50,0.3);
  border-color: rgba(220,50,50,0.5); color: #ff6b6b;
}

/* ── CUSTOM MARKERS ───────────────────────────────────────────── */
.custom-marker-wrap {
  position: relative;
  display: flex; align-items: center; justify-content: center;
}

.marker-pin {
  width: 28px; height: 28px;
  border-radius: 50% 50% 50% 0;
  transform: rotate(-45deg);
  border: 2px solid rgba(255,255,255,0.18);
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 4px 16px rgba(0,0,0,0.55);
  transition: all 0.25s cubic-bezier(0.34,1.56,0.64,1);
  cursor: pointer;
  animation: pinBreath 3.2s ease-in-out infinite;
}

@keyframes pinBreath {
  0%,100% { box-shadow: 0 4px 16px rgba(0,0,0,0.55), 0 0 6px 1px currentColor; }
  50%      { box-shadow: 0 4px 16px rgba(0,0,0,0.55), 0 0 14px 3px currentColor; }
}

.marker-inner {
  width: 8px; height: 8px; border-radius: 50%;
  background: rgba(255,255,255,0.85);
  transform: rotate(45deg); flex-shrink: 0;
}

.marker-pulse {
  position: absolute; inset: -6px; border-radius: 50%;
  border: 2px solid currentColor;
  animation: markerPulse 2.6s ease-out infinite;
  opacity: 0; pointer-events: none;
}

@keyframes markerPulse {
  0%   { transform: scale(0.5); opacity: 0.75; }
  100% { transform: scale(1.9); opacity: 0; }
}

.custom-marker-wrap:hover .marker-pin {
  transform: rotate(-45deg) scale(1.22);
  box-shadow: 0 6px 24px rgba(0,0,0,0.6), 0 0 22px currentColor;
}

/* ── ZOOM CONTROLS ────────────────────────────────────────────── */
.zoom-controls-floating {
  position: absolute;
  bottom: 24px; left: 18px;
  display: flex; flex-direction: column; gap: 4px;
  z-index: 600;
  animation: revealFade 0.6s ease 0.7s both;
}

.zoom-btn {
  width: 38px; height: 38px; border-radius: 9px;
  background: rgba(8, 8, 16, 0.92);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.08);
  color: var(--text); font-size: 18px; font-weight: 500;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all 0.18s; padding: 0; line-height: 1;
}
.zoom-btn:hover {
  background: rgba(201,168,76,0.12);
  border-color: var(--gold); color: var(--gold);
  box-shadow: 0 0 18px rgba(201,168,76,0.2);
}
.zoom-sep { height: 1px; background: var(--border); margin: 2px 6px; }
.zoom-reset-btn { font-size: 14px; }

/* ── HOVER PARTICLE ───────────────────────────────────────────── */
.hover-particle {
  position: absolute;
  width: 4px; height: 4px; border-radius: 50%;
  background: var(--gold);
  pointer-events: none;
  z-index: 900;
  animation: particleDrift 0.9s ease-out forwards;
}

@keyframes particleDrift {
  0%   { transform: translate(0,0) scale(1);  opacity: 0.9; }
  100% { transform: translate(var(--dx),var(--dy)) scale(0); opacity: 0; }
}

/* ── SIDEBAR ──────────────────────────────────────────────────── */
.sidebar-panel {
  width: 340px;
  background: var(--panel-bg);
  border-left: 1px solid var(--border);
  display: flex; flex-direction: column;
  color: var(--text);
  overflow: hidden; flex-shrink: 0;
  animation: revealRight 0.55s cubic-bezier(0.16,1,0.3,1) 0.25s both;
}

@keyframes revealRight {
  from { opacity: 0; transform: translateX(24px); }
  to   { opacity: 1; transform: translateX(0); }
}

/* ── SIDEBAR HEADER ───────────────────────────────────────────── */
.sidebar-header {
  padding: 16px 16px 14px;
  border-bottom: 1px solid var(--border);
  flex-shrink: 0;
}

.sidebar-header h2 {
  margin: 0 0 11px;
  font-size: 15px; font-weight: 700;
  color: var(--text); letter-spacing: -0.2px;
}

.search-wrapper { position: relative; }

.search-icon {
  position: absolute; left: 10px; top: 50%; transform: translateY(-50%);
  color: var(--text-muted); pointer-events: none;
}

.search-box {
  width: 100%;
  padding: 9px 32px;
  background: rgba(255,255,255,0.04);
  border: 1px solid var(--border);
  border-radius: 9px;
  color: var(--text); font-size: 12.5px;
  transition: border-color 0.22s, box-shadow 0.22s, background 0.22s;
  font-family: inherit;
}
.search-box::placeholder { color: var(--text-muted); font-size: 12px; }
.search-box:focus {
  outline: none;
  background: rgba(201,168,76,0.04);
  border-color: rgba(201,168,76,0.4);
  box-shadow: 0 0 0 3px rgba(201,168,76,0.08), 0 0 16px -4px rgba(201,168,76,0.2);
}

.search-clear {
  position: absolute; right: 8px; top: 50%; transform: translateY(-50%);
  width: 20px; height: 20px;
  background: rgba(255,255,255,0.08); border: none; border-radius: 50%;
  color: var(--text-muted); font-size: 14px; line-height: 1;
  cursor: pointer; display: none;
  align-items: center; justify-content: center;
  transition: all 0.15s; padding: 0;
}
.search-clear:hover { background: rgba(255,255,255,0.16); color: var(--text); }
.search-clear.visible { display: flex; }

/* ── SUMMARY CARDS ────────────────────────────────────────────── */
.sidebar-summary {
  display: flex; gap: 0;
  border-bottom: 1px solid var(--border);
  flex-shrink: 0;
}

.summary-card {
  flex: 1;
  display: flex; align-items: center; gap: 8px;
  padding: 11px 10px;
  border-right: 1px solid var(--border);
  transition: background 0.18s;
  cursor: default;
}
.summary-card:last-child { border-right: none; }
.summary-card:hover { background: rgba(201,168,76,0.04); }

.summary-icon { font-size: 16px; line-height: 1; flex-shrink: 0; }

.summary-val {
  font-size: 15px; font-weight: 800;
  color: var(--gold); line-height: 1;
}

.summary-label {
  font-size: 9px; font-weight: 600;
  color: var(--text-muted);
  text-transform: uppercase; letter-spacing: 0.6px;
  margin-top: 2px;
}

/* ── LAYER TOGGLES ────────────────────────────────────────────── */
.sidebar-layer-toggles {
  padding: 6px 0;
  border-bottom: 1px solid var(--border);
  flex-shrink: 0;
}

.layer-toggle-row {
  display: flex; align-items: center;
  justify-content: space-between;
  padding: 8px 16px;
  transition: background 0.16s;
}
.layer-toggle-row:hover { background: rgba(255,255,255,0.02); }

.layer-toggle-label {
  display: flex; align-items: center; gap: 8px;
  font-size: 12px; font-weight: 600;
  color: var(--text-muted);
  text-transform: uppercase; letter-spacing: 0.7px;
}

/* pill toggle — shared style for both gov and delegation toggles */
.pill-toggle {
  position: relative;
  width: 40px; height: 22px; border-radius: 999px;
  background: rgba(255,255,255,0.08);
  border: 1px solid rgba(255,255,255,0.14);
  cursor: pointer;
  transition: background 0.22s, border-color 0.22s, box-shadow 0.22s;
  padding: 0; flex-shrink: 0;
}

.pill-toggle[aria-checked="true"] {
  background: rgba(201,168,76,0.28);
  border-color: var(--gold);
  box-shadow: 0 0 14px rgba(201,168,76,0.24);
}

.pill-knob {
  position: absolute;
  top: 3px; left: 3px;
  width: 14px; height: 14px;
  border-radius: 50%;
  background: rgba(255,255,255,0.4);
  transition: transform 0.22s cubic-bezier(0.34,1.56,0.64,1), background 0.22s;
  pointer-events: none;
}

.pill-toggle[aria-checked="true"] .pill-knob {
  transform: translateX(18px);
  background: var(--gold);
}

/* ── LEGEND ───────────────────────────────────────────────────── */
.sidebar-legend {
  padding: 12px 16px;
  border-bottom: 1px solid var(--border);
  flex-shrink: 0;
}

.legend-title-row {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 9px;
}

.legend-title {
  font-size: 10px; font-weight: 700;
  color: var(--text-muted);
  text-transform: uppercase; letter-spacing: 1px;
}

.legend-reset {
  background: none; border: none;
  color: var(--gold); font-size: 11px; font-weight: 600;
  cursor: pointer; padding: 2px 4px;
  opacity: 0.75; transition: opacity 0.2s; font-family: inherit;
}
.legend-reset:hover { opacity: 1; }

.legend-items { display: flex; flex-wrap: wrap; gap: 5px; }

.legend-chip {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 5px 9px 5px 7px;
  border-radius: 999px;
  cursor: pointer;
  transition: all 0.2s ease;
  font-size: 11px; font-family: inherit;
  background: color-mix(in srgb, var(--chip-color) 10%, rgba(255,255,255,0.03));
  border: 1px solid color-mix(in srgb, var(--chip-color) 30%, rgba(255,255,255,0.07));
  color: rgba(255,255,255,0.8);
}
.legend-chip:hover {
  transform: translateY(-1px);
  background: color-mix(in srgb, var(--chip-color) 18%, rgba(255,255,255,0.05));
  box-shadow: 0 4px 12px color-mix(in srgb, var(--chip-color) 28%, transparent);
}
.legend-chip.active {
  background: color-mix(in srgb, var(--chip-color) 20%, rgba(255,255,255,0.04));
  border-color: var(--chip-color);
}
.legend-chip.inactive {
  opacity: 0.28;
  background: rgba(255,255,255,0.02);
  border-color: rgba(255,255,255,0.06);
}

.legend-dot {
  width: 7px; height: 7px; border-radius: 50%;
  background: var(--chip-color);
  box-shadow: 0 0 5px var(--chip-color);
  flex-shrink: 0;
}
.legend-chip.inactive .legend-dot { box-shadow: none; }
.legend-text { color: #fff; font-weight: 600; white-space: nowrap; }
.legend-count {
  font-size: 9px; font-weight: 800; color: #000;
  background: var(--chip-color);
  border-radius: 999px; min-width: 16px; height: 16px;
  display: inline-flex; align-items: center; justify-content: center;
  padding: 0 4px;
}

.sidebar-divider { height: 1px; background: var(--border); flex-shrink: 0; }

/* ── LOCATIONS LIST ───────────────────────────────────────────── */
.sidebar-content { flex: 1; overflow-y: auto; overflow-x: hidden; }
.sidebar-content::-webkit-scrollbar { width: 4px; }
.sidebar-content::-webkit-scrollbar-thumb {
  background: rgba(255,255,255,0.08); border-radius: 10px;
}
.sidebar-content::-webkit-scrollbar-thumb:hover { background: rgba(201,168,76,0.3); }

.locations-list { padding: 8px; display: flex; flex-direction: column; gap: 2px; }

.locations-empty {
  padding: 40px 16px; text-align: center;
  color: var(--text-muted); font-size: 13px;
}

.location-item {
  padding: 10px 12px;
  background: rgba(255,255,255,0.025);
  border: 1px solid transparent;
  border-radius: 9px; cursor: pointer;
  transition: all 0.16s;
  display: flex; align-items: center; gap: 10px;
  animation: itemSlideIn 0.3s ease both;
}
.location-item:hover {
  background: rgba(255,255,255,0.05);
  border-color: var(--border);
  transform: translateX(2px);
}
.location-item.active {
  background: rgba(201,168,76,0.09);
  border-color: rgba(201,168,76,0.35);
}

.location-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }

.location-info { flex: 1; min-width: 0; }
.location-name {
  font-weight: 600; font-size: 12.5px; color: var(--text);
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.location-item.active .location-name { color: var(--gold); }
.location-category {
  font-size: 10px; color: var(--text-muted);
  text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;
}

.location-arrow {
  color: var(--text-muted); font-size: 16px;
  opacity: 0; transition: all 0.2s;
}
.location-item:hover .location-arrow,
.location-item.active .location-arrow { opacity: 1; color: var(--gold); }

/* ── INFO PANEL ───────────────────────────────────────────────── */
.info-panel {
  position: absolute;
  bottom: 20px; left: 64px;
  width: 310px; max-width: calc(100% - 82px);
  background: rgba(6, 6, 18, 0.98);
  backdrop-filter: blur(24px);
  border: 1px solid rgba(255,255,255,0.07);
  border-radius: 16px; overflow: hidden;
  z-index: 1000;
  box-shadow:
    0 40px 80px rgba(0,0,0,0.75),
    0 0 0 1px rgba(255,255,255,0.02),
    0 0 60px -16px color-mix(in srgb, var(--accent, var(--gold)) 55%, transparent);
  display: none; flex-direction: column;
  max-height: min(72vh, 500px);
}

.info-panel::before {
  content: ''; position: absolute;
  top: 0; left: 0; right: 0; height: 2px;
  background: var(--accent, var(--gold)); z-index: 5;
}

.info-panel.active {
  display: flex;
  animation: infoPanelIn 0.3s cubic-bezier(0.16,1,0.3,1) forwards;
}

@keyframes infoPanelIn {
  from { opacity: 0; transform: translateY(16px) scale(0.96); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}

.info-header {
  position: relative; overflow: hidden;
  height: 160px; flex-shrink: 0;
  background: #0a0a12;
}
.info-image-large {
  width: 100%; height: 100%; object-fit: cover;
  transition: transform 6s ease;
}
.info-panel.active .info-image-large { transform: scale(1.06); }

.info-image-gradient {
  position: absolute; inset: 0;
  background: linear-gradient(180deg, rgba(0,0,0,0.04) 25%, rgba(6,6,18,0.97) 100%);
  z-index: 2;
}

.info-category {
  position: absolute; bottom: 12px; left: 14px; z-index: 3;
  padding: 4px 11px;
  background: var(--accent, var(--gold));
  color: #08080f; font-size: 9px; font-weight: 800;
  border-radius: 999px;
  text-transform: uppercase; letter-spacing: 0.8px;
}

.info-close-btn {
  position: absolute; top: 10px; right: 10px; z-index: 6;
  width: 28px; height: 28px; border-radius: 50%;
  background: rgba(0,0,0,0.5);
  backdrop-filter: blur(6px);
  border: 1px solid rgba(255,255,255,0.12);
  color: rgba(255,255,255,0.8);
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all 0.2s; padding: 0;
}
.info-close-btn:hover {
  background: rgba(220,50,50,0.8);
  border-color: transparent; color: white;
  transform: rotate(90deg);
}

.info-body { padding: 14px 16px 16px; flex: 1; overflow-y: auto; }
.info-body::-webkit-scrollbar { width: 4px; }
.info-body::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }

.info-title {
  margin: 0 0 6px;
  font-size: 16px; font-weight: 700; color: var(--text);
  line-height: 1.3; letter-spacing: -0.3px;
}
.info-description {
  margin: 0 0 12px;
  font-size: 12.5px; line-height: 1.65; color: var(--text-muted);
  display: -webkit-box;
  -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
}
.info-coords {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 10px; color: var(--text-muted);
  margin-bottom: 14px; padding: 5px 10px;
  background: rgba(255,255,255,0.04);
  border: 1px solid rgba(255,255,255,0.06);
  border-radius: 999px;
  font-family: 'SF Mono','Roboto Mono', monospace;
}
.info-coords svg { color: var(--accent, var(--gold)); flex-shrink: 0; }

.info-explore-btn {
  display: flex; align-items: center; justify-content: center; gap: 7px;
  width: 100%; padding: 10px;
  background: linear-gradient(135deg,
    var(--accent, var(--gold)),
    color-mix(in srgb, var(--accent, var(--gold)) 60%, #080810));
  border: none; color: #08080f;
  border-radius: 9px; font-size: 12.5px; font-weight: 700;
  cursor: pointer; text-decoration: none;
  transition: all 0.2s; font-family: inherit;
}
.info-explore-btn:hover {
  filter: brightness(1.1);
  box-shadow: 0 6px 22px -4px color-mix(in srgb, var(--accent, var(--gold)) 55%, transparent);
  transform: translateY(-1px);
}
.info-explore-btn svg { transition: transform 0.2s; }
.info-explore-btn:hover svg { transform: translateX(3px); }

/* ── SHARED ANIMATIONS ────────────────────────────────────────── */
@keyframes revealFade {
  from { opacity: 0; } to { opacity: 1; }
}
@keyframes revealDown {
  from { opacity: 0; transform: translateY(-12px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes itemSlideIn {
  from { opacity: 0; transform: translateX(8px); }
  to   { opacity: 1; transform: translateX(0); }
}

/* ── RESPONSIVE ───────────────────────────────────────────────── */
@media (max-width: 1024px) { .sidebar-panel { width: 300px; } }
@media (max-width: 768px) {
  .map-layout { flex-direction: column; }
  .sidebar-panel { width: 100%; height: 280px; border-left: none; border-top: 1px solid var(--border); }
  .info-panel { left: 10px; right: 10px; width: auto; max-width: none; bottom: 290px; }
  .zoom-controls-floating { bottom: 300px; }
  .header-stat { display: none; }
  .header-divider { display: none; }
}
@media (max-width: 480px) {
  .map-header { padding: 10px 14px; }
  .map-title { font-size: 16px; }
  .header-icon { display: none; }
}

@media (prefers-reduced-motion: reduce) {
  .marker-pulse, .tunisia-hero-glow,
  .gov-layer-active .leaflet-overlay-pane svg { animation: none !important; }
  .info-panel.active, .location-item { animation: none !important; }
}
</style>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<!-- DATA BRIDGE -->
<script>
  window.mapLocations  = @json($mapLocations);
  window.mapCategories = @json($mapCategories);
</script>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

  // ═══════════════════════════════════════════════════════════
  //  ANIMATED COUNTERS
  // ═══════════════════════════════════════════════════════════
  function animateCounter(el, target, duration) {
    const start = performance.now();
    const update = (now) => {
      const progress = Math.min((now - start) / duration, 1);
      const eased = 1 - Math.pow(1 - progress, 3);
      el.textContent = Math.round(eased * target);
      if (progress < 1) requestAnimationFrame(update);
      else el.textContent = target;
    };
    requestAnimationFrame(update);
  }

  // Stagger counter animations on page load
  setTimeout(() => {
    document.querySelectorAll('[data-target]').forEach((el, i) => {
      setTimeout(() => {
        animateCounter(el, parseInt(el.dataset.target), 1200);
      }, i * 180);
    });
  }, 500);

  // ═══════════════════════════════════════════════════════════
  //  MAP INIT
  // ═══════════════════════════════════════════════════════════
  const TUNISIA_BOUNDS = L.latLngBounds([30.2, 7.5], [37.55, 11.6]);

  const map = L.map('tunisia-map', {
    zoomControl:        false,
    attributionControl: false,
    minZoom:            6,
    maxZoom:            16,
    maxBounds:          TUNISIA_BOUNDS,
    maxBoundsViscosity: 1.0,
  }).fitBounds(TUNISIA_BOUNDS);

  // CartoDB Dark Matter — no land labels
  L.tileLayer(
    'https://{s}.basemaps.cartocdn.com/dark_nolabels/{z}/{x}/{y}{r}.png',
    { subdomains: 'abcd', maxZoom: 19, opacity: 0.68 }
  ).addTo(map);

  // City labels only, very subtle
  L.tileLayer(
    'https://{s}.basemaps.cartocdn.com/dark_only_labels/{z}/{x}/{y}{r}.png',
    { subdomains: 'abcd', maxZoom: 19, opacity: 0.28 }
  ).addTo(map);

  // ── ZOOM CONTROLS ─────────────────────────────────────────
  document.getElementById('zoom-in').addEventListener('click',  () => map.zoomIn());
  document.getElementById('zoom-out').addEventListener('click', () => map.zoomOut());
  document.getElementById('zoom-reset').addEventListener('click', () => {
    map.flyToBounds(TUNISIA_BOUNDS, { padding: [20, 20], duration: 0.9 });
  });

  // ═══════════════════════════════════════════════════════════
  //  GOVERNORATE GRADIENT COLOURS — one per governorate
  //  24 governorates, each gets a distinct gradient accent
  // ═══════════════════════════════════════════════════════════
  const GOV_GRADIENT_COLORS = [
    { border: '#C9A84C', fill: '#C9A84C' }, // Gold
    { border: '#7B5EA7', fill: '#9B7EC8' }, // Purple
    { border: '#2E86AB', fill: '#4DA6C8' }, // Blue
    { border: '#28A745', fill: '#48C768' }, // Green
    { border: '#E07B39', fill: '#F09A58' }, // Orange
    { border: '#C0392B', fill: '#E05A4A' }, // Red
    { border: '#16A085', fill: '#30C0A5' }, // Teal
    { border: '#D4AC0D', fill: '#F0CC2D' }, // Yellow
    { border: '#8E44AD', fill: '#AE64CD' }, // Violet
    { border: '#1ABC9C', fill: '#3ADCBC' }, // Emerald
    { border: '#E74C3C', fill: '#F06C5C' }, // Crimson
    { border: '#3498DB', fill: '#54B8FB' }, // Sky Blue
    { border: '#E67E22', fill: '#F09E42' }, // Amber
    { border: '#2ECC71', fill: '#4EEC91' }, // Mint
    { border: '#9B59B6', fill: '#BB79D6' }, // Lilac
    { border: '#F39C12', fill: '#FFBC32' }, // Saffron
    { border: '#1E8BC3', fill: '#3EABE3' }, // Cerulean
    { border: '#27AE60', fill: '#47CE80' }, // Forest
    { border: '#D35400', fill: '#F37420' }, // Burnt Orange
    { border: '#8D6E63', fill: '#AD8E83' }, // Warm Brown
    { border: '#00BCD4', fill: '#20DCF4' }, // Cyan
    { border: '#FF5722', fill: '#FF7742' }, // Deep Orange
    { border: '#607D8B', fill: '#809DAB' }, // Blue Grey
    { border: '#4CAF50', fill: '#6CCF70' }, // Leaf Green
  ];

  let govColorIndex = 0;

  function getDefaultStyle(colorSet) {
    return {
      color:        colorSet.border,
      weight:       1.4,
      opacity:      0.7,
      fillColor:    colorSet.fill,
      fillOpacity:  0.07,
      smoothFactor: 1,
    };
  }

  function getHoverStyle(colorSet) {
    return {
      color:       colorSet.border,
      weight:      2.6,
      opacity:     1,
      fillColor:   colorSet.fill,
      fillOpacity: 0.22,
    };
  }

  function getSelectedStyle(colorSet) {
    return {
      color:       colorSet.border,
      weight:      3.4,
      opacity:     1,
      fillColor:   colorSet.fill,
      fillOpacity: 0.38,
    };
  }

  // Dimmed style for non-selected govs when one is selected
  const S_DIM = {
    color:       'rgba(255,255,255,0.12)',
    weight:      0.8,
    opacity:     0.4,
    fillColor:   '#1a1a2a',
    fillOpacity: 0.04,
  };

  // ── TOOLTIP + BANNER REFS ──────────────────────────────────
  const tooltip     = document.getElementById('gov-tooltip');
  const tooltipName = document.getElementById('gov-tooltip-name');
  const tooltipAr   = document.getElementById('gov-tooltip-ar');
  const ttSites     = document.getElementById('tt-sites');
  const govBanner   = document.getElementById('gov-banner');
  const bannerName  = document.getElementById('gov-banner-name');
  const bannerAr    = document.getElementById('gov-banner-ar');
  const bannerCount = document.getElementById('gov-banner-count');
  const bannerSwatch= document.getElementById('gov-banner-swatch');

  let selectedLayer = null;
  let geojsonLayer  = null;
  let allGovLayers  = []; // for dim/un-dim

  function getGovCount(govName) {
    return window.mapLocations.filter(l =>
      l.governorate && l.governorate.toLowerCase() === govName.toLowerCase()
    ).length;
  }

  // ── PARTICLE EFFECT ───────────────────────────────────────
  function spawnParticles(clientX, clientY, color) {
    const mapEl = document.getElementById('tunisia-map');
    const rect  = mapEl.getBoundingClientRect();
    for (let i = 0; i < 5; i++) {
      const p = document.createElement('div');
      p.className = 'hover-particle';
      const angle = (i / 5) * 2 * Math.PI + Math.random() * 0.6;
      const dist  = 16 + Math.random() * 18;
      p.style.cssText = `
        left:${clientX - rect.left}px;top:${clientY - rect.top}px;
        background:${color};
        --dx:${Math.cos(angle)*dist}px;--dy:${Math.sin(angle)*dist}px;
        animation-delay:${i*0.06}s;
      `;
      mapEl.appendChild(p);
      setTimeout(() => p.remove(), 1100);
    }
  }

  // ═══════════════════════════════════════════════════════════
  //  LOAD GOVERNORATE GEOJSON
  // ═══════════════════════════════════════════════════════════
  const GOV_URL = "{{ Vite::asset('resources/assets/maps/tunisia_governorates.geojson') }}";

  let govLayerVisible = true;
  const govToggleBtn  = document.getElementById('gov-toggle-btn');

  fetch(GOV_URL)
    .then(r => { if (!r.ok) throw new Error('GeoJSON not found'); return r.json(); })
    .then(data => {
      geojsonLayer = L.geoJSON(data, {
        style: () => {
          // assigned per-feature in onEachFeature
          return { fillOpacity: 0, opacity: 0 };
        },
        onEachFeature(feature, layer) {
          const colorSet = GOV_GRADIENT_COLORS[govColorIndex++ % GOV_GRADIENT_COLORS.length];
          const defStyle = getDefaultStyle(colorSet);
          layer._colorSet   = colorSet;
          layer._defStyle   = defStyle;
          layer._nameEn     = feature.properties.NAME_EN || feature.properties.shapeName || '';
          layer._nameAr     = feature.properties.NAME_AR || '';

          layer.setStyle(defStyle);
          allGovLayers.push(layer);

          const mapEl = document.getElementById('tunisia-map');

          layer.on('mouseover', (e) => {
            if (layer !== selectedLayer) layer.setStyle(getHoverStyle(colorSet));
            layer.bringToFront();

            // Set tooltip gov color CSS var
            tooltip.style.setProperty('--gov-color', colorSet.border);
            tooltipName.textContent = layer._nameEn;
            tooltipAr.textContent   = layer._nameAr;
            ttSites.textContent     = getGovCount(layer._nameEn) || '—';
            tooltip.classList.add('visible');

            spawnParticles(e.originalEvent.clientX, e.originalEvent.clientY, colorSet.border);
          });

          layer.on('mousemove', e => {
            const rect = mapEl.getBoundingClientRect();
            tooltip.style.left = (e.originalEvent.clientX - rect.left + 18) + 'px';
            tooltip.style.top  = (e.originalEvent.clientY - rect.top  - 60) + 'px';
          });

          layer.on('mouseout', () => {
            if (layer !== selectedLayer) layer.setStyle(defStyle);
            tooltip.classList.remove('visible');
          });

          layer.on('click', e => {
            L.DomEvent.stopPropagation(e);

            if (selectedLayer && selectedLayer !== layer) {
              // Restore previous selection to default
              selectedLayer.setStyle(selectedLayer._defStyle);
            }

            if (selectedLayer === layer) {
              // Clicking same gov again deselects
              layer.setStyle(defStyle);
              selectedLayer = null;
              govBanner.classList.remove('visible');
              // Un-dim all
              allGovLayers.forEach(l => l.setStyle(l._defStyle));
              return;
            }

            selectedLayer = layer;
            layer.setStyle(getSelectedStyle(colorSet));
            layer.bringToFront();

            // Dim all other governorates
            allGovLayers.forEach(l => {
              if (l !== layer) l.setStyle(S_DIM);
            });

            // Fly to
            map.flyToBounds(layer.getBounds(), { padding: [60, 60], duration: 0.7, maxZoom: 12 });

            // Banner
            bannerName.textContent  = layer._nameEn;
            bannerAr.textContent    = layer._nameAr;
            bannerSwatch.style.background = colorSet.border;
            bannerSwatch.style.color      = colorSet.border;
            bannerSwatch.style.boxShadow  = `0 0 8px ${colorSet.border}`;
            const cnt = getGovCount(layer._nameEn);
            bannerCount.textContent = cnt + ' lieu' + (cnt !== 1 ? 'x' : '');
            govBanner.classList.add('visible');

            if (markersGroup) markersGroup.bringToFront();
          });
        }
      }).addTo(map);

      // Staggered entrance animation for governorate paths
      document.querySelector('.leaflet-overlay-pane')?.classList.add('gov-layer-active');
      const paths = document.querySelectorAll('.leaflet-overlay-pane path');
      paths.forEach((path, i) => {
        path.style.opacity = '0';
        setTimeout(() => {
          path.style.transition = 'opacity 0.5s ease';
          path.style.opacity    = '1';
        }, 60 + i * 28);
      });

      if (markersGroup) markersGroup.bringToFront();
    })
    .catch(err => {
      console.warn('[Map] Governorate GeoJSON:', err.message);
    });

  // Deselect on map background click
  map.on('click', () => {
    if (selectedLayer) {
      selectedLayer.setStyle(selectedLayer._defStyle);
      selectedLayer = null;
      govBanner.classList.remove('visible');
      allGovLayers.forEach(l => l.setStyle(l._defStyle));
    }
  });

  document.getElementById('gov-banner-close').addEventListener('click', () => {
    if (selectedLayer) {
      selectedLayer.setStyle(selectedLayer._defStyle);
      selectedLayer = null;
      allGovLayers.forEach(l => l.setStyle(l._defStyle));
    }
    govBanner.classList.remove('visible');
  });

  // ── GOVERNORATE LAYER TOGGLE ───────────────────────────────
  govToggleBtn.addEventListener('click', () => {
    govLayerVisible = !govLayerVisible;
    govToggleBtn.setAttribute('aria-checked', govLayerVisible ? 'true' : 'false');
    if (geojsonLayer) {
      if (govLayerVisible) {
        geojsonLayer.addTo(map);
        if (markersGroup) markersGroup.bringToFront();
      } else {
        map.removeLayer(geojsonLayer);
        // Also clear selection if hidden
        selectedLayer = null;
        govBanner.classList.remove('visible');
      }
    }
  });

  // ═══════════════════════════════════════════════════════════
  //  MUNICIPALITY LAYER
  // ═══════════════════════════════════════════════════════════
  const MUNI_URL = "{{ Vite::asset('resources/assets/maps/Tunisian_municipality.geojson') }}";

  const S_MUNI_DEFAULT = {
    color: '#7B9FC9', weight: 0.7, opacity: 0.5,
    fillColor: '#5A82B4', fillOpacity: 0.04, smoothFactor: 1,
  };
  const S_MUNI_HOVER = {
    color: '#A8C8F0', weight: 1.6, opacity: 0.9,
    fillColor: '#7BAAD8', fillOpacity: 0.15,
  };

  let municipalityLayer  = null;
  let municipalityLoaded = false;
  let muniDetailActive   = false;

  const detailBtn = document.getElementById('detail-toggle-btn');

  function loadMunicipalityLayer() {
    detailBtn.style.opacity      = '0.55';
    detailBtn.style.pointerEvents = 'none';

    fetch(MUNI_URL)
      .then(r => { if (!r.ok) throw new Error('Muni GeoJSON not found'); return r.json(); })
      .then(data => {
        const mapEl = document.getElementById('tunisia-map');

        municipalityLayer = L.geoJSON(data, {
          style: S_MUNI_DEFAULT,
          onEachFeature(feature, layer) {
            const nameEn = feature.properties.NAME_EN    || '';
            const nameAr = feature.properties.NAME_EN_AR || '';
            const govEn  = feature.properties.gouv_name  || '';

            layer.on('mouseover', () => {
              layer.setStyle(S_MUNI_HOVER);
              layer.bringToFront();
              tooltip.style.removeProperty('--gov-color');
              tooltipName.textContent = nameEn + (govEn ? ` — ${govEn}` : '');
              tooltipAr.textContent   = nameAr;
              ttSites.textContent     = '—';
              tooltip.classList.add('visible');
            });

            layer.on('mousemove', e => {
              const rect = mapEl.getBoundingClientRect();
              tooltip.style.left = (e.originalEvent.clientX - rect.left + 18) + 'px';
              tooltip.style.top  = (e.originalEvent.clientY - rect.top  - 60) + 'px';
            });

            layer.on('mouseout', () => {
              layer.setStyle(S_MUNI_DEFAULT);
              tooltip.classList.remove('visible');
            });
          }
        });

        municipalityLoaded = true;
        municipalityLayer.addTo(map);
        if (markersGroup) markersGroup.bringToFront();

        detailBtn.style.opacity       = '';
        detailBtn.style.pointerEvents = '';
        detailBtn.setAttribute('aria-checked', 'true');
      })
      .catch(err => {
        console.warn('[Map] Municipality GeoJSON:', err.message);
        detailBtn.style.opacity       = '';
        detailBtn.style.pointerEvents = '';
        detailBtn.setAttribute('aria-checked', 'false');
        muniDetailActive = false;
      });
  }

  detailBtn.addEventListener('click', () => {
    muniDetailActive = !muniDetailActive;
    detailBtn.setAttribute('aria-checked', muniDetailActive ? 'true' : 'false');

    if (muniDetailActive) {
      if (!municipalityLoaded) {
        loadMunicipalityLayer();
      } else {
        municipalityLayer.addTo(map);
        if (markersGroup) markersGroup.bringToFront();
      }
    } else {
      if (municipalityLayer) map.removeLayer(municipalityLayer);
    }
  });

  // ═══════════════════════════════════════════════════════════
  //  MARKERS
  // ═══════════════════════════════════════════════════════════
  const markers      = {};
  const markersGroup = L.layerGroup().addTo(map);
  const locationsList = document.getElementById('locations-list');
  const infoPanel     = document.getElementById('info-panel');
  const searchInput   = document.getElementById('search-input');
  const searchClear   = document.getElementById('search-clear');

  const activeCategories = new Set(window.mapCategories.map(c => c.slug));

  // Category counts
  const counts = {};
  window.mapLocations.forEach(loc => {
    counts[loc.categorySlug] = (counts[loc.categorySlug] || 0) + 1;
  });
  window.mapCategories.forEach(cat => {
    const el = document.getElementById(`count-${cat.slug}`);
    if (el) el.textContent = counts[cat.slug] || 0;
  });

  // Category icons mapping (add more as needed)
  const CAT_ICONS = {
    'patrimoine':   '🏛',
    'archeologie':  '⛏',
    'mosquee':      '🕌',
    'musee':        '🎭',
    'nature':       '🌿',
    'culture':      '📚',
    'monument':     '🗿',
    'default':      '📍',
  };

  function getCatIcon(slug) {
    return CAT_ICONS[slug] || CAT_ICONS['default'];
  }

  window.mapLocations.forEach((loc, i) => {
    const color = loc.color || '#C9A84C';
    const icon  = getCatIcon(loc.categorySlug);

    const markerHtml = `
      <div class="custom-marker-wrap" style="color:${color}">
        <div class="marker-pulse" style="border-color:${color};"></div>
        <div class="marker-pin" style="background:${color};">
          <div class="marker-inner"></div>
        </div>
      </div>`;

    const leafletIcon = L.divIcon({
      html: markerHtml, className: '',
      iconSize:   [28, 28],
      iconAnchor: [14, 28],
    });

    const marker = L.marker(loc.coords, { icon: leafletIcon }).addTo(markersGroup);

    marker.on('click', () => {
      showInfoPanel(loc);
      highlightItem(i);
      locationsList.children[i]?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });

    markers[i] = marker;

    // Sidebar item
    const item = document.createElement('div');
    item.className = 'location-item';
    item.dataset.category = loc.categorySlug;
    item.style.animationDelay = (i * 0.035) + 's';
    item.innerHTML = `
      <div class="location-dot" style="background:${color}; box-shadow:0 0 6px ${color};"></div>
      <div class="location-info">
        <div class="location-name">${loc.name}</div>
        <div class="location-category">${icon} ${loc.category}</div>
      </div>
      <div class="location-arrow">›</div>`;

    item.addEventListener('click', () => {
      map.flyTo(loc.coords, 13, { duration: 0.75 });
      showInfoPanel(loc);
      highlightItem(i);
    });

    locationsList.appendChild(item);
  });

  // ── SEARCH ────────────────────────────────────────────────
  searchInput.addEventListener('input', () => {
    searchClear.classList.toggle('visible', searchInput.value.length > 0);
    applyFilter();
  });
  searchClear.addEventListener('click', () => {
    searchInput.value = '';
    searchClear.classList.remove('visible');
    applyFilter();
  });

  // ── LEGEND FILTER ─────────────────────────────────────────
  document.querySelectorAll('.legend-chip').forEach(chip => {
    chip.addEventListener('click', () => {
      const slug = chip.dataset.category;
      if (activeCategories.has(slug)) {
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

  document.getElementById('legend-reset').addEventListener('click', () => {
    window.mapCategories.forEach(c => activeCategories.add(c.slug));
    applyFilter();
  });

  function applyFilter() {
    const term = searchInput.value.toLowerCase().trim();
    let visibleCount = 0;

    window.mapLocations.forEach((loc, i) => {
      const show = activeCategories.has(loc.categorySlug) &&
                   (!term || loc.name.toLowerCase().includes(term));

      const item = locationsList.children[i];
      if (item) item.style.display = show ? '' : 'none';

      const el = markers[i]?.getElement();
      if (el) {
        el.style.opacity       = show ? '1' : '0';
        el.style.pointerEvents = show ? '' : 'none';
        el.style.transition    = 'opacity 0.2s';
      }
      if (show) visibleCount++;
    });

    document.querySelectorAll('.legend-chip').forEach(chip => {
      const on = activeCategories.has(chip.dataset.category);
      chip.classList.toggle('active', on);
      chip.classList.toggle('inactive', !on);
    });

    let empty = locationsList.querySelector('.locations-empty');
    if (visibleCount === 0) {
      if (!empty) {
        empty = document.createElement('div');
        empty.className   = 'locations-empty';
        empty.textContent = 'Aucun lieu trouvé.';
        locationsList.appendChild(empty);
      }
    } else if (empty) {
      empty.remove();
    }
  }

  // ── INFO PANEL ────────────────────────────────────────────
  function showInfoPanel(loc) {
    const color = loc.color || '#C9A84C';
    infoPanel.style.setProperty('--accent', color);

    document.getElementById('info-title').textContent       = loc.name;
    document.getElementById('info-description').textContent = loc.description || '';
    document.getElementById('info-category').textContent    = loc.category;
    document.getElementById('info-image').src               =
      loc.img || '{{ asset("images/placeholder.png") }}';
    document.getElementById('info-coords-text').textContent =
      `${parseFloat(loc.coords[0]).toFixed(4)}°N, ${parseFloat(loc.coords[1]).toFixed(4)}°E`;

    const exploreBtn = document.getElementById('info-explore-btn');
    if (loc.url) exploreBtn.href = loc.url;

    infoPanel.classList.remove('active');
    void infoPanel.offsetWidth; // force reflow → re-trigger animation
    infoPanel.classList.add('active');
  }

  function highlightItem(index) {
    Array.from(locationsList.children).forEach((item, i) => {
      item.classList.toggle('active', i === index);
    });
  }

  document.getElementById('info-close-btn').addEventListener('click', () => {
    infoPanel.classList.remove('active');
    Array.from(locationsList.children).forEach(item => item.classList.remove('active'));
  });

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      infoPanel.classList.remove('active');
      if (selectedLayer) {
        selectedLayer.setStyle(selectedLayer._defStyle);
        allGovLayers.forEach(l => l.setStyle(l._defStyle));
        selectedLayer = null;
        govBanner.classList.remove('visible');
      }
    }
  });

});
</script>

@endsection
