{{-- Shared form for creating/editing a map category — themed --}}

<link rel="stylesheet" href="{{ asset('css/map-backoffice.css') }}">

<form action="{{ $category->exists ? route('map-categories.update', $category) : route('map-categories.store') }}"
      method="POST" style="max-width:480px;">
    @csrf
    @if ($category->exists)
        @method('PUT')
    @endif

    <div class="mp-field">
        <label class="mp-label">Nom de la catégorie</label>
        <input type="text" name="name" class="mp-input"
               value="{{ old('name', $category->name) }}" required placeholder="Ex : Culturel">
        @error('name') <div class="mp-error">{{ $message }}</div> @enderror
    </div>

    <div class="mp-field">
        <label class="mp-label">Couleur</label>
        <div class="mp-color-row">
            <input type="color" id="color-picker"
                   value="{{ old('color', $category->color ?? '#cdaa80') }}" title="Choisir une couleur">
            <input type="text" name="color" id="color-text" class="mp-input"
                   value="{{ old('color', $category->color ?? '#cdaa80') }}" required
                   pattern="^#([A-Fa-f0-9]{6})$" placeholder="#cdaa80">
        </div>
        @error('color') <div class="mp-error">{{ $message }}</div> @enderror
        <div class="mp-swatch-preview">
            <span class="mp-swatch-dot" id="swatch-preview" style="background:{{ old('color', $category->color ?? '#cdaa80') }};"></span>
            Aperçu de la couleur utilisée pour les marqueurs et la légende
        </div>
    </div>

    <div class="mp-field" style="max-width:200px;">
        <label class="mp-label">Ordre d'affichage dans la légende</label>
        <input type="number" name="order" class="mp-input"
               value="{{ old('order', $category->order ?? 0) }}">
    </div>

    <div class="mp-actions">
        <button type="submit" class="du-btn-primary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
            {{ $category->exists ? 'Mettre à jour' : 'Ajouter la catégorie' }}
        </button>
        <a href="{{ route('map-categories.index') }}" class="mp-btn-cancel">Annuler</a>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const picker = document.getElementById('color-picker');
        const text = document.getElementById('color-text');
        const preview = document.getElementById('swatch-preview');

        picker.addEventListener('input', () => {
            text.value = picker.value;
            preview.style.background = picker.value;
        });
        text.addEventListener('input', () => {
            if (/^#([A-Fa-f0-9]{6})$/.test(text.value)) {
                picker.value = text.value;
                preview.style.background = text.value;
            }
        });
    });
</script>
