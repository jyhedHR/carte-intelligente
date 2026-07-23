// ── TUNISIA MAP (LEAFLET) — now driven by backend data ──
document.addEventListener('DOMContentLoaded', () => {
    const mapEl = document.getElementById('tunisia-map');
    if (!mapEl || typeof L === 'undefined') return;

    const tunisiaCenter = [33.886917, 9.537499];
    const tunisiaBounds = [[31.6366, 7.5244], [38.5409, 11.5657]];

    const map = L.map('tunisia-map', {
        center: tunisiaCenter,
        zoom: 7,
        zoomControl: true,
        scrollWheelZoom: true,
        doubleClickZoom: true,
        maxBounds: tunisiaBounds,
        maxBoundsViscosity: 1.0,
        minZoom: 7,
        maxZoom: 12,
        dragging: true
    });

    map.on('drag', () => {
        map.panInsideBounds(tunisiaBounds, { animate: false });
    });

    // Dark tile layer
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '© OpenStreetMap contributors © CARTO',
        maxZoom: 19
    }).addTo(map);

    // Border mask — darkens everything outside Tunisia
    fetch('https://raw.githubusercontent.com/johan/world.geo.json/master/countries/TUN.geo.json')
        .then(r => r.json())
        .then(tunisiaGeoJSON => {
            const worldCoords = [[[-180, -90], [-180, 90], [180, 90], [180, -90], [-180, -90]]];
            const tunisiaRings = [];
            tunisiaGeoJSON.features[0].geometry.coordinates.forEach(polygon => {
                polygon.forEach(ring => tunisiaRings.push(ring));
            });
            L.geoJSON({
                type: "Feature",
                geometry: {
                    type: "Polygon",
                    coordinates: [worldCoords[0], ...tunisiaRings]
                }
            }, {
                style: { fillColor: '#0a0806', fillOpacity: 1, color: '#c9a84c', weight: 2, opacity: 0.6 },
                interactive: false
            }).addTo(map);
        });

    map.fitBounds(tunisiaBounds, { padding: [20, 20] });

    // ── Locations data ──
    // Previously a hardcoded array. Now comes from the server, rendered into
    // window.mapLocations by the Blade view (see home_blade_map_section.blade.php).
    // Each item looks like:
    // { name, coords: [lat, lng], category, categorySlug, color, description, img }
    const locations = Array.isArray(window.mapLocations) ? window.mapLocations : [];

    // categoriesMeta lets us fall back to a color if a location's own
    // "color" field is missing for any reason (defensive, shouldn't happen).
    const categoriesMeta = Array.isArray(window.mapCategoriesMeta) ? window.mapCategoriesMeta : [];

    window.getCategoryColor = function (location) {
        if (location && location.color) return location.color;
        const meta = categoriesMeta.find(c => c.slug === (location && location.categorySlug));
        return meta ? meta.color : '#cdaa80';
    };

    window.createCustomMarker = function (location) {
        const color = getCategoryColor(location);
        const id = location.name.replace(/\s/g, '_');
        const markerHTML = `
      <div style="position:relative;width:20px;height:20px;animation:pulse 2s ease-in-out infinite;">
        <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" style="width:100%;height:100%;filter:drop-shadow(0 0 12px ${color}99) drop-shadow(0 0 6px ${color}66);">
          <defs>
            <filter id="glow-${id}">
              <feGaussianBlur stdDeviation="1.5" result="coloredBlur"/>
              <feMerge><feMergeNode in="coloredBlur"/><feMergeNode in="SourceGraphic"/></feMerge>
            </filter>
          </defs>
          <rect x="8" y="2" width="16" height="16" fill="${color}" stroke="#c9a84c" stroke-width="2.5" rx="2" transform="rotate(45 16 16)" opacity="0.95"/>
          <circle cx="16" cy="16" r="4" fill="#c9a84c" filter="url(#glow-${id})"/>
        </svg>
      </div>`;
        return L.marker(location.coords, {
            icon: L.divIcon({ html: markerHTML, iconSize: [32, 32], iconAnchor: [16, 16], popupAnchor: [0, -20], className: '' }),
            title: location.name
        });
    };

    window.updateInfoPanel = function (location) {
        document.getElementById('info-title').textContent = location.name;
        document.getElementById('info-category').textContent = location.category;
        document.getElementById('info-description').textContent = location.description;
        document.getElementById('info-coords').textContent = `📍 ${location.coords[0].toFixed(4)}°N, ${location.coords[1].toFixed(4)}°E`;

        const imgEl = document.getElementById('info-image');
        if (location.img) {
            imgEl.src = location.img;
            imgEl.style.display = '';
        } else {
            // no image uploaded for this location — hide gracefully instead of a broken icon
            imgEl.removeAttribute('src');
            imgEl.style.display = 'none';
        }

        document.getElementById('info-panel').classList.add('active');
    };

    // Add markers
    locations.forEach(location => {
        const marker = createCustomMarker(location);
        marker.addTo(map);
        marker.on('click', () => {
            updateInfoPanel(location);
            map.flyTo(location.coords, 10, { duration: 1 });
            document.querySelectorAll('.location-item').forEach((item, idx) => {
                item.classList.toggle('active', locations[idx].name === location.name);
            });
        });
    });

    // Build sidebar list
    const locationsList = document.getElementById('locations-list');
    if (locationsList) {
        locations.forEach(location => {
            const item = document.createElement('div');
            item.className = 'location-item';
            item.innerHTML = `<div class="location-name">${location.name}</div><div class="location-category">${location.category}</div>`;
            item.addEventListener('click', () => {
                map.flyTo(location.coords, 10, { duration: 1 });
                updateInfoPanel(location);
                document.querySelectorAll('.location-item').forEach(el => el.classList.remove('active'));
                item.classList.add('active');
            });
            locationsList.appendChild(item);
        });
    }

    // Close info panel
    const closeBtn = document.getElementById('info-close-btn');
    if (closeBtn) closeBtn.onclick = () => document.getElementById('info-panel').classList.remove('active');

    // Zoom buttons
    const zoomIn = document.getElementById('zoom-in');
    const zoomOut = document.getElementById('zoom-out');
    if (zoomIn) zoomIn.addEventListener('click', () => map.zoomIn());
    if (zoomOut) zoomOut.addEventListener('click', () => map.zoomOut());
});
