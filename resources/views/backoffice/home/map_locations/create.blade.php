@extends('shared.layouts.backoffice')

@section('page-title', 'Ajouter un lieu')

@section('content')
<link rel="stylesheet" href="{{ asset('css/map-backoffice.css') }}">
<div class="du-wrap">
    <div class="du-page-header">
        <div>
            <div class="du-page-title">
                <span class="du-page-title-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                </span>
                Ajouter un lieu
            </div>
            <div class="du-page-sub">Renseignez les informations du nouveau lieu sur la carte</div>
        </div>
        <a href="{{ route('map-locations.index') }}" class="mp-btn-cancel" style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:9px;background:var(--bg4,#1e2228);border:1px solid var(--border2,rgba(255,255,255,.12));color:var(--text2,#8a8f9a);font-size:13.5px;font-weight:600;text-decoration:none;">
            ← Retour à la liste
        </a>
    </div>

    <div class="du-panel" style="padding:24px;">
        @php $location = new \App\Models\MapLocation(); @endphp
        @include('backoffice.home.map_locations._form', ['location' => $location, 'categories' => $categories])
    </div>
</div>
@endsection
