{{-- Shared form for creating/editing a map location — themed to match the backoffice design system --}}

<link rel="stylesheet" href="{{ asset('css/map-backoffice.css') }}">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<form action="{{ $location->exists ? route('map-locations.update', $location) : route('map-locations.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if ($location->exists)
        @method('PUT')
    @endif

    <div class="mp-form-grid">
        {{-- ── Left column: fields ── --}}
        <div>
            <div class="mp-field">
                <label class="mp-label">Nom du lieu</label>
                <input type="text" name="name" class="mp-input"
                       value="{{ old('name', $location->name) }}" required placeholder="Ex : Médina de Tunis">
                @error('name') <div class="mp-error">{{ $message }}</div> @enderror
            </div>

            <div class="mp-field">
                <label class="mp-label">Catégorie</label>
                <select name="map_category_id" class="mp-select" required>
                    <option value="">— Choisir —</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}"
                            @selected(old('map_category_id', $location->map_category_id) == $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('map_category_id') <div class="mp-error">{{ $message }}</div> @enderror
                <div class="mp-hint">
                    Pas de la bonne catégorie ?
                    <a href="{{ route('map-categories.create') }}" target="_blank">Créer une catégorie</a>
                </div>
            </div>

            <div class="mp-field mp-row-2">
                <div>
                    <label class="mp-label">Latitude</label>
                    <input type="text" id="lat-input" name="lat" class="mp-input"
                           value="{{ old('lat', $location->lat) }}" required>
                    @error('lat') <div class="mp-error">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="mp-label">Longitude</label>
                    <input type="text" id="lng-input" name="lng" class="mp-input"
                           value="{{ old('lng', $location->lng) }}" required>
                    @error('lng') <div class="mp-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mp-field">
                <label class="mp-label">Description</label>
                <textarea name="description" class="mp-textarea" required
                          placeholder="Décrivez ce lieu pour les visiteurs...">{{ old('description', $location->description) }}</textarea>
                @error('description') <div class="mp-error">{{ $message }}</div> @enderror
            </div>

            <div class="mp-field">
                <label class="mp-label">Image</label>
                <input type="file" name="image" accept="image/*" class="mp-input">
                @error('image') <div class="mp-error">{{ $message }}</div> @enderror

                @if ($location->image)
                    <div class="mp-image-preview">
                        <img src="{{ Storage::url($location->image) }}" alt="">
                        <div class="mp-hint" style="margin:0;">Image actuelle — laisser le champ vide pour la conserver.</div>
                    </div>
                @endif
            </div>

            <div class="mp-checkbox-row">
                <input type="checkbox" name="is_active" id="is_active"
                       value="1" @checked(old('is_active', $location->is_active ?? true))>
                <label for="is_active">Visible sur le site</label>
            </div>

            <div class="mp-field" style="max-width:200px;">
                <label class="mp-label">Ordre d'affichage</label>
                <input type="number" name="order" class="mp-input"
                       value="{{ old('order', $location->order ?? 0) }}">
            </div>

            <div class="mp-actions">
                <button type="submit" class="du-btn-primary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                    {{ $location->exists ? 'Mettre à jour' : 'Ajouter le lieu' }}
                </button>
                <a href="{{ route('map-locations.index') }}" class="mp-btn-cancel">Annuler</a>
            </div>
        </div>

        {{-- ── Right column: picker map ── --}}
        <div>
            <label class="mp-label">Cliquez sur la carte pour positionner le lieu</label>
            <div class="mp-map-card">
                <div id="picker-map" style="height:420px;"></div>
            </div>
            <div class="mp-hint">Les champs latitude/longitude se remplissent automatiquement.</div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const latInput = document.getElementById('lat-input');
        const lngInput = document.getElementById('lng-input');

        const startLat = parseFloat(latInput.value) || 33.8869;
        const startLng = parseFloat(lngInput.value) || 9.5375;

        const pickerMap = L.map('picker-map').setView([startLat, startLng], 7);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 18
        }).addTo(pickerMap);

        let marker = L.marker([startLat, startLng], { draggable: true }).addTo(pickerMap);

        function setCoords(lat, lng) {
            latInput.value = lat.toFixed(6);
            lngInput.value = lng.toFixed(6);
        }

        marker.on('dragend', (e) => {
            const pos = e.target.getLatLng();
            setCoords(pos.lat, pos.lng);
        });

        pickerMap.on('click', (e) => {
            marker.setLatLng(e.latlng);
            setCoords(e.latlng.lat, e.latlng.lng);
        });
    });
</script>
