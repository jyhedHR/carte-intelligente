@extends('shared.layouts.backoffice')

@section('title', 'Nouveau format de référence')

@section('content')
<div style="max-width:760px;margin:0 auto;">

    {{-- Breadcrumb --}}
    <div style="margin-bottom:20px;font-size:13px;color:var(--text3,#888);">
        <a href="{{ route('admin.references.index') }}" style="color:var(--gold,#D4AF37);text-decoration:none;">Gestion des références</a>
        <span style="margin:0 8px;">›</span>
        <span>Nouveau format</span>
    </div>

    <div class="card" style="padding:32px;">
        <h2 style="margin:0 0 4px;font-size:20px;color:var(--text,#fff);">Nouveau format de référence</h2>
        <p style="margin:0 0 28px;color:var(--text3,#888);font-size:13px;">
            Configurez la structure des références générées pour ce département ou formulaire.
        </p>

        <form method="POST" action="{{ route('admin.references.store') }}">
            @csrf
            @php $reference = null; @endphp
            @include('backoffice.references._form')

            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:32px;padding-top:20px;border-top:1px solid var(--border,rgba(255,255,255,0.08));">
                <a href="{{ route('admin.references.index') }}"
                   style="padding:10px 20px;border-radius:8px;background:rgba(255,255,255,0.05);color:var(--text2,#ccc);text-decoration:none;border:1px solid var(--border,rgba(255,255,255,0.1));font-size:14px;">
                    Annuler
                </a>
                <button type="submit" class="btn-gold" style="padding:10px 24px;font-size:14px;">
                    ✓ Créer le format
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
