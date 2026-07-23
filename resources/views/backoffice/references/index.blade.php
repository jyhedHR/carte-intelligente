@extends('shared.layouts.backoffice')

@section('title', 'Gestion des références')

@section('content')
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
    <div>
        <h1 style="margin:0;font-size:22px;color:var(--text,#fff);">
            <span style="color:var(--gold,#D4AF37);">⚙</span> Gestion des références
        </h1>
        <p style="margin:4px 0 0;color:var(--text3,#888);font-size:13px;">
            Définissez le format des numéros de référence par département et par formulaire.
        </p>
    </div>
    <a href="{{ route('admin.references.create') }}" class="btn-gold">
        + Nouveau format
    </a>
</div>

{{-- Flash messages --}}
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Filter bar --}}
<form method="GET" action="{{ route('admin.references.index') }}" style="margin-bottom:20px;display:flex;gap:12px;align-items:center;">
    <select name="department_id" class="form-select" style="min-width:220px;" onchange="this.form.submit()">
        <option value="">Tous les départements</option>
        @foreach($departments as $dept)
            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                {{ $dept->name_fr ?? $dept->name }}
            </option>
        @endforeach
    </select>
    @if(request('department_id'))
        <a href="{{ route('admin.references.index') }}" class="btn-ghost" style="font-size:13px;">✕ Réinitialiser</a>
    @endif
</form>

{{-- Table --}}
<div class="card" style="overflow:hidden;">
    <table class="data-table" style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:var(--bg3,rgba(255,255,255,0.04));border-bottom:1px solid var(--border,rgba(255,255,255,0.08));">
                <th style="padding:12px 16px;text-align:left;font-size:12px;color:var(--text3,#888);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Département</th>
                <th style="padding:12px 16px;text-align:left;font-size:12px;color:var(--text3,#888);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Formulaire</th>
                <th style="padding:12px 16px;text-align:left;font-size:12px;color:var(--text3,#888);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Aperçu</th>
                <th style="padding:12px 16px;text-align:left;font-size:12px;color:var(--text3,#888);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Séquence</th>
                <th style="padding:12px 16px;text-align:center;font-size:12px;color:var(--text3,#888);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Statut</th>
                <th style="padding:12px 16px;text-align:right;font-size:12px;color:var(--text3,#888);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($formats as $format)
            <tr style="border-bottom:1px solid var(--border,rgba(255,255,255,0.06));transition:background 0.15s;" onmouseover="this.style.background='rgba(255,255,255,0.03)'" onmouseout="this.style.background=''">
                {{-- Department --}}
                <td style="padding:14px 16px;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="width:8px;height:8px;border-radius:50%;background:var(--gold,#D4AF37);flex-shrink:0;"></span>
                        <span style="font-size:14px;color:var(--text,#fff);font-weight:500;">
                            {{ $format->department->name_fr ?? $format->department->name ?? '—' }}
                        </span>
                    </div>
                </td>

                {{-- Formulaire --}}
                <td style="padding:14px 16px;">
                    @if($format->formulaire)
                        <span style="font-size:13px;color:var(--text2,#ccc);">{{ $format->formulaire->titre }}</span>
                    @else
                        <span style="font-size:12px;color:var(--text3,#888);font-style:italic;background:rgba(255,255,255,0.05);padding:2px 8px;border-radius:4px;">Tous les formulaires</span>
                    @endif
                </td>

                {{-- Preview --}}
                <td style="padding:14px 16px;">
                    <code style="font-family:monospace;font-size:13px;background:rgba(212,175,55,0.12);color:var(--gold,#D4AF37);padding:4px 10px;border-radius:6px;border:1px solid rgba(212,175,55,0.25);">
                        {{ $format->preview_example ?? $format->buildPreview() }}
                    </code>
                </td>

                {{-- Sequence --}}
                <td style="padding:14px 16px;">
                    <div style="font-size:13px;color:var(--text2,#ccc);">
                        Prochain : <strong style="color:var(--text,#fff);">{{ $format->last_sequence + 1 }}</strong>
                        <span style="color:var(--text3,#888);margin-left:6px;">({{ $format->last_sequence }} générés)</span>
                    </div>
                    <form method="POST" action="{{ route('admin.references.reset', $format) }}"
                          style="display:inline;"
                          onsubmit="return confirm('Réinitialiser la séquence à 0 ?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" style="margin-top:4px;font-size:11px;background:none;border:none;color:var(--text3,#888);cursor:pointer;padding:0;text-decoration:underline;">
                            ↺ Réinitialiser
                        </button>
                    </form>
                </td>

                {{-- Status --}}
                <td style="padding:14px 16px;text-align:center;">
                    @if($format->active)
                        <span style="display:inline-flex;align-items:center;gap:4px;font-size:12px;padding:3px 10px;border-radius:20px;background:rgba(16,185,129,0.12);color:#10b981;border:1px solid rgba(16,185,129,0.25);">
                            <span style="width:6px;height:6px;border-radius:50%;background:#10b981;"></span> Actif
                        </span>
                    @else
                        <span style="display:inline-flex;align-items:center;gap:4px;font-size:12px;padding:3px 10px;border-radius:20px;background:rgba(156,163,175,0.12);color:#9ca3af;border:1px solid rgba(156,163,175,0.25);">
                            <span style="width:6px;height:6px;border-radius:50%;background:#9ca3af;"></span> Inactif
                        </span>
                    @endif
                </td>

                {{-- Actions --}}
                <td style="padding:14px 16px;text-align:right;white-space:nowrap;">
                    <a href="{{ route('admin.references.edit', $format) }}"
                       style="display:inline-flex;align-items:center;gap:5px;font-size:12px;padding:5px 12px;border-radius:6px;background:rgba(255,255,255,0.06);color:var(--text2,#ccc);text-decoration:none;border:1px solid var(--border,rgba(255,255,255,0.1));margin-right:6px;transition:all 0.2s;"
                       onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='rgba(255,255,255,0.06)'">
                        ✏️ Modifier
                    </a>
                    <form method="POST" action="{{ route('admin.references.destroy', $format) }}" style="display:inline;"
                          onsubmit="return confirm('Supprimer ce format de référence ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                style="display:inline-flex;align-items:center;gap:5px;font-size:12px;padding:5px 12px;border-radius:6px;background:rgba(220,38,38,0.08);color:#f87171;border:1px solid rgba(220,38,38,0.2);cursor:pointer;transition:all 0.2s;"
                                onmouseover="this.style.background='rgba(220,38,38,0.18)'" onmouseout="this.style.background='rgba(220,38,38,0.08)'">
                            🗑 Supprimer
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding:48px 16px;text-align:center;color:var(--text3,#888);">
                    <div style="font-size:36px;margin-bottom:12px;">📋</div>
                    <div style="font-size:15px;margin-bottom:8px;">Aucun format de référence configuré</div>
                    <a href="{{ route('admin.references.create') }}" style="color:var(--gold,#D4AF37);font-size:13px;">
                        + Créer le premier format
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($formats->hasPages())
    <div style="margin-top:16px;">{{ $formats->links() }}</div>
@endif

{{-- Info box --}}
<div style="margin-top:24px;padding:16px 20px;background:rgba(212,175,55,0.06);border:1px solid rgba(212,175,55,0.2);border-radius:10px;">
    <p style="margin:0;font-size:13px;color:var(--text2,#ccc);line-height:1.7;">
        <strong style="color:var(--gold,#D4AF37);">ℹ Comment ça fonctionne :</strong>
        Chaque format définit comment les numéros de référence des demandes sont générés.
        Un format lié à un <em>formulaire spécifique</em> est prioritaire sur le format générique du département.
        Si aucun format n'est configuré pour un département, le système utilise le préfixe par défaut (3 premières lettres du nom).
    </p>
</div>
@endsection
