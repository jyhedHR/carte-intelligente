@extends('shared.layouts.backoffice')

@section('page-title', 'Lieux de la carte')

@section('content')
<link rel="stylesheet" href="{{ asset('css/map-backoffice.css') }}">
<div class="du-wrap">

    {{-- ── Page header ── --}}
    <div class="du-page-header">
        <div>
            <div class="du-page-title">
                <span class="du-page-title-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 1 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                </span>
                Lieux de la carte du patrimoine
            </div>
            <div class="du-page-sub">Gérez les lieux affichés sur la carte interactive du site</div>
        </div>

        <a href="{{ route('map-locations.create') }}" class="du-btn-primary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Ajouter un lieu
        </a>
    </div>

    {{-- ── Flash messages ── --}}
    @if (session('success'))
        <div class="du-flash success">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── KPI cards ── --}}
    <div class="du-kpi-grid">
        <div class="du-kpi" style="--kpi-color:#c9a84c;">
            <div class="du-kpi-val">{{ $locations->count() }}</div>
            <div class="du-kpi-lbl">Total lieux</div>
            <div class="du-kpi-icon">📍</div>
        </div>
        <div class="du-kpi" style="--kpi-color:#4ade80;">
            <div class="du-kpi-val">{{ $locations->where('is_active', true)->count() }}</div>
            <div class="du-kpi-lbl">Visibles</div>
            <div class="du-kpi-icon">👁</div>
        </div>
        <div class="du-kpi" style="--kpi-color:#f87171;">
            <div class="du-kpi-val">{{ $locations->where('is_active', false)->count() }}</div>
            <div class="du-kpi-lbl">Masqués</div>
            <div class="du-kpi-icon">🚫</div>
        </div>
        <div class="du-kpi" style="--kpi-color:#60a5fa;">
            <div class="du-kpi-val">{{ $locations->pluck('map_category_id')->unique()->count() }}</div>
            <div class="du-kpi-lbl">Catégories utilisées</div>
            <div class="du-kpi-icon">🏷</div>
        </div>
    </div>

    {{-- ── Main panel ── --}}
    <div class="du-panel">
        <div class="du-panel-head">
            <div class="du-panel-title">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h18v18H3z"/></svg>
                Tous les lieux
            </div>
            <a href="{{ route('map-categories.index') }}" class="du-search-btn">
                🏷 Gérer les catégories
            </a>
        </div>

        <div class="du-table-wrap">
            <table class="du-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Coordonnées</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($locations as $location)
                        <tr class="{{ !$location->is_active ? 'archived' : '' }}">
                            <td>
                                @if ($location->image)
                                    <img src="{{ Storage::url($location->image) }}" alt=""
                                         style="width:42px;height:42px;object-fit:cover;border-radius:8px;border:1px solid var(--border, rgba(255,255,255,.08));">
                                @else
                                    <div class="du-avatar" style="background:linear-gradient(135deg, var(--gold,#c9a84c), var(--gold3,#a07830));">
                                        {{ mb_substr($location->name, 0, 1) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="du-user-name">{{ $location->name }}</div>
                                <div class="du-user-sub">Ordre : {{ $location->order }}</div>
                            </td>
                            <td>
                                <span class="du-role" style="background:{{ $location->category->color }}22; border-color:{{ $location->category->color }}55; color:{{ $location->category->color }};">
                                    {{ $location->category->name }}
                                </span>
                            </td>
                            <td>
                                <span class="du-code">{{ number_format($location->lat, 4) }}, {{ number_format($location->lng, 4) }}</span>
                            </td>
                            <td>
                                @if ($location->is_active)
                                    <span class="du-badge active"><span class="du-badge-dot"></span>Visible</span>
                                @else
                                    <span class="du-badge archived"><span class="du-badge-dot"></span>Masqué</span>
                                @endif
                            </td>
                            <td>
                                <div class="du-actions">
                                    <a href="{{ route('map-locations.edit', $location) }}"
                                       class="du-action-btn edit du-tip" data-tip="Modifier">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('map-locations.destroy', $location) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Supprimer ce lieu ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="du-action-btn archive du-tip" data-tip="Supprimer">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14z"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="du-empty">
                                    <div class="du-empty-icon">📍</div>
                                    <div class="du-empty-title">Aucun lieu pour le moment</div>
                                    <div class="du-empty-sub">Ajoutez votre premier lieu pour le voir apparaître ici.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
