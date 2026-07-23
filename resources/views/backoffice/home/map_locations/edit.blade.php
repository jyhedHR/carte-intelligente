@extends('shared.layouts.backoffice')

@section('page-title', 'Modifier le lieu')

@section('content')
<link rel="stylesheet" href="{{ asset('css/map-backoffice.css') }}">
<div class="du-wrap">
    <div class="du-page-header">
        <div>
            <div class="du-page-title">
                <span class="du-page-title-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                </span>
                Modifier : {{ $location->name }}
            </div>
            <div class="du-page-sub">Mettez à jour les informations de ce lieu</div>
        </div>
        <a href="{{ route('map-locations.index') }}" class="mp-btn-cancel" style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:9px;background:var(--bg4,#1e2228);border:1px solid var(--border2,rgba(255,255,255,.12));color:var(--text2,#8a8f9a);font-size:13.5px;font-weight:600;text-decoration:none;">
            ← Retour à la liste
        </a>
    </div>

    <div class="du-panel" style="padding:24px;">
        @include('backoffice.home.map_locations._form', ['location' => $location, 'categories' => $categories])
    </div>
</div>
@endsection
