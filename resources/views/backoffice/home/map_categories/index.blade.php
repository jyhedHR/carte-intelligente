@extends('shared.layouts.backoffice')

@section('page-title', 'Catégories de la carte')

@section('content')
<link rel="stylesheet" href="{{ asset('css/map-backoffice.css') }}">
<div class="du-wrap">

    <div class="du-page-header">
        <div>
            <div class="du-page-title">
                <span class="du-page-title-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.59 13.41L11 3.83A2 2 0 0 0 9.59 3.24H4a1 1 0 0 0-1 1v5.59a2 2 0 0 0 .59 1.41l9.58 9.58a2 2 0 0 0 2.82 0l5.59-5.59a2 2 0 0 0 0-2.82z"/>
                        <circle cx="7.5" cy="7.5" r="1.5" fill="currentColor"/>
                    </svg>
                </span>
                Catégories de la carte
            </div>
            <div class="du-page-sub">Gérez les types de lieux et leurs couleurs sur la carte</div>
        </div>

        <a href="{{ route('map-categories.create') }}" class="du-btn-primary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Ajouter une catégorie
        </a>
    </div>

    @if (session('success'))
        <div class="du-flash success">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="du-panel">
        <div class="du-panel-head">
            <div class="du-panel-title">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h18v18H3z"/></svg>
                Toutes les catégories
            </div>
            <a href="{{ route('map-locations.index') }}" class="du-search-btn">
                📍 Voir les lieux
            </a>
        </div>

        <div class="du-table-wrap">
            <table class="du-table">
                <thead>
                    <tr>
                        <th>Couleur</th>
                        <th>Nom</th>
                        <th>Slug</th>
                        <th>Nb. lieux</th>
                        <th>Ordre</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>
                                <span style="display:inline-block;width:26px;height:26px;border-radius:50%;
                                             background:{{ $category->color }};
                                             border:2px solid rgba(255,255,255,.15);
                                             box-shadow:0 0 10px {{ $category->color }}66;"></span>
                            </td>
                            <td>
                                <div class="du-user-name">{{ $category->name }}</div>
                            </td>
                            <td><span class="du-code">{{ $category->slug }}</span></td>
                            <td>
                                <span class="du-badge active" style="background:rgba(96,165,250,.12); border-color:rgba(96,165,250,.25); color:#60a5fa;">
                                    {{ $category->locations()->count() }} lieux
                                </span>
                            </td>
                            <td>{{ $category->order }}</td>
                            <td>
                                <div class="du-actions">
                                    <a href="{{ route('map-categories.edit', $category) }}"
                                       class="du-action-btn edit du-tip" data-tip="Modifier">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('map-categories.destroy', $category) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Supprimer cette catégorie ? Tous les lieux liés seront aussi supprimés.');">
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
                                    <div class="du-empty-icon">🏷</div>
                                    <div class="du-empty-title">Aucune catégorie pour le moment</div>
                                    <div class="du-empty-sub">Créez votre première catégorie pour commencer à ajouter des lieux.</div>
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
