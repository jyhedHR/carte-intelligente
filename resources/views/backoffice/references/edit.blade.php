@extends('shared.layouts.backoffice')

@section('title', 'Modifier le format de référence')

@section('content')
<div style="max-width:760px;margin:0 auto;">

    {{-- Breadcrumb --}}
    <div style="margin-bottom:20px;font-size:13px;color:var(--text3,#888);">
        <a href="{{ route('admin.references.index') }}" style="color:var(--gold,#D4AF37);text-decoration:none;">Gestion des références</a>
        <span style="margin:0 8px;">›</span>
        <span>Modifier</span>
    </div>

    {{-- Stats card --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px;">
        <div style="padding:16px;background:var(--bg2,rgba(255,255,255,0.03));border:1px solid var(--border,rgba(255,255,255,0.08));border-radius:10px;text-align:center;">
            <div style="font-size:24px;font-weight:700;color:var(--gold,#D4AF37);">{{ $reference->last_sequence }}</div>
            <div style="font-size:12px;color:var(--text3,#888);margin-top:4px;">Références générées</div>
        </div>
        <div style="padding:16px;background:var(--bg2,rgba(255,255,255,0.03));border:1px solid var(--border,rgba(255,255,255,0.08));border-radius:10px;text-align:center;">
            <div style="font-size:24px;font-weight:700;color:var(--text,#fff);">{{ $reference->last_sequence + 1 }}</div>
            <div style="font-size:12px;color:var(--text3,#888);margin-top:4px;">Prochaine séquence</div>
        </div>
        <div style="padding:16px;background:var(--bg2,rgba(255,255,255,0.03));border:1px solid var(--border,rgba(255,255,255,0.08));border-radius:10px;text-align:center;">
            <code style="font-family:monospace;font-size:16px;font-weight:700;color:var(--gold,#D4AF37);">
                {{ $reference->preview_example ?? $reference->buildPreview() }}
            </code>
            <div style="font-size:12px;color:var(--text3,#888);margin-top:4px;">Format actuel</div>
        </div>
    </div>

    <div class="card" style="padding:32px;">
        <h2 style="margin:0 0 4px;font-size:20px;color:var(--text,#fff);">Modifier le format</h2>
        <p style="margin:0 0 28px;color:var(--text3,#888);font-size:13px;">
            Département : <strong style="color:var(--text2,#ccc);">{{ $reference->department->name_fr ?? $reference->department->name }}</strong>
            @if($reference->formulaire)
                · Formulaire : <strong style="color:var(--text2,#ccc);">{{ $reference->formulaire->titre }}</strong>
            @endif
        </p>

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:20px;">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.references.update', $reference) }}">
            @csrf
            @method('PUT')
            @include('backoffice.references._form')

            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:32px;padding-top:20px;border-top:1px solid var(--border,rgba(255,255,255,0.08));">
                <a href="{{ route('admin.references.index') }}"
                   style="padding:10px 20px;border-radius:8px;background:rgba(255,255,255,0.05);color:var(--text2,#ccc);text-decoration:none;border:1px solid var(--border,rgba(255,255,255,0.1));font-size:14px;">
                    Annuler
                </a>
                <button type="submit" class="btn-gold" style="padding:10px 24px;font-size:14px;">
                    ✓ Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
