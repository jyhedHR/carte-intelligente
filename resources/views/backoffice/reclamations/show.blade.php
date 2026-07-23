@extends('shared.layouts.backoffice')

@section('title', 'Détail de la réclamation')
@section('breadcrumb', 'Réclamations')

@section('content')
<div style="padding:24px;max-width:820px;">

    <a href="{{ route('admin.reclamations.index') }}"
       style="display:inline-block;margin-bottom:16px;color:#666;text-decoration:none;font-size:13px;">
        ← Retour à la liste
    </a>

    @if(session('success'))
        <div style="background:#dcfce7;border:1px solid #16a34a;color:#166534;border-radius:8px;padding:12px 16px;margin-bottom:16px;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#fee2e2;border:1px solid #dc2626;color:#991b1b;border-radius:8px;padding:12px 16px;margin-bottom:16px;">
            {{ session('error') }}
        </div>
    @endif

    <div style="border:1px solid #eee;border-radius:12px;overflow:hidden;">

        {{-- Header --}}
        <div style="padding:20px 24px;background:#faf9f7;border-bottom:1px solid #eee;display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap;">
            <div>
                <h1 style="font-size:20px;font-weight:700;margin:0 0 6px;">Réclamation #{{ $reclamation->id }}</h1>
                <div style="color:#999;font-size:13px;">
                    Demandée le {{ \Carbon\Carbon::parse($reclamation->created_at)->format('d/m/Y à H:i') }}
                </div>
            </div>
            <div>
                @if($reclamation->statut === 'en_attente')
                    <span style="background:#fef3c7;color:#92400e;padding:5px 14px;border-radius:20px;font-size:13px;font-weight:600;">En attente</span>
                @elseif($reclamation->statut === 'approuvee')
                    <span style="background:#dcfce7;color:#166534;padding:5px 14px;border-radius:20px;font-size:13px;font-weight:600;">Approuvée</span>
                @else
                    <span style="background:#fee2e2;color:#991b1b;padding:5px 14px;border-radius:20px;font-size:13px;font-weight:600;">Rejetée</span>
                @endif
            </div>
        </div>

        <div style="padding:24px;">

            {{-- User + form --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">
                <div>
                    <div style="font-size:12px;color:#999;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Utilisateur</div>
                    <div style="font-weight:600;">{{ $reclamation->user_prenom }} {{ $reclamation->user_nom }}</div>
                    <div style="color:#666;font-size:13px;">{{ $reclamation->user_email }}</div>
                </div>
                <div>
                    <div style="font-size:12px;color:#999;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Formulaire</div>
                    <div style="font-weight:600;">{{ $reclamation->form_titre }}</div>
                </div>
            </div>

            {{-- Motif --}}
            <div style="margin-bottom:24px;">
                <div style="font-size:12px;color:#999;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Motif de la demande</div>
                <div style="background:#f9fafb;border-radius:8px;padding:14px 16px;white-space:pre-wrap;font-size:14px;line-height:1.6;">
                    {{ $reclamation->motif }}
                </div>
            </div>

            {{-- Tracking snapshot --}}
            <div style="margin-bottom:24px;">
                <div style="font-size:12px;color:#999;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">État des soumissions</div>
                <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:12px;">
                    <div style="background:#f9fafb;border-radius:8px;padding:12px;text-align:center;">
                        <div style="font-size:20px;font-weight:700;">{{ $reclamation->submission_count ?? 0 }} / {{ $reclamation->max_submissions ?? '∞' }}</div>
                        <div style="font-size:11px;color:#999;margin-top:2px;">Soumissions</div>
                    </div>
                    <div style="background:#f9fafb;border-radius:8px;padding:12px;text-align:center;">
                        <div style="font-size:20px;font-weight:700;color:{{ ($reclamation->bonus_submissions ?? 0) > 0 ? '#16a34a' : '#333' }};">
                            +{{ $reclamation->bonus_submissions ?? 0 }}
                        </div>
                        <div style="font-size:11px;color:#999;margin-top:2px;">Bonus accordé</div>
                    </div>
                    <div style="background:#f9fafb;border-radius:8px;padding:12px;text-align:center;">
                        <div style="font-size:16px;font-weight:700;">
                            {{ $reclamation->valid_until ? \Carbon\Carbon::parse($reclamation->valid_until)->format('d/m/Y') : '—' }}
                        </div>
                        <div style="font-size:11px;color:#999;margin-top:2px;">Valide jusqu'au</div>
                    </div>
                </div>
            </div>

            {{-- Decision --}}
            @if($reclamation->statut !== 'en_attente')
                <div style="margin-bottom:24px;">
                    <div style="font-size:12px;color:#999;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Décision</div>
                    <div style="background:{{ $reclamation->statut === 'approuvee' ? '#f0fdf4' : '#fef2f2' }};border:1px solid {{ $reclamation->statut === 'approuvee' ? '#bbf7d0' : '#fecaca' }};border-radius:8px;padding:14px 16px;font-size:13px;">
                        @if($reclamation->statut === 'approuvee' && $reclamation->action)
                            <div style="margin-bottom:6px;">
                                <strong>Action :</strong>
                                {{ ['extra_submission' => 'Soumissions supplémentaires accordées', 'reset_compteur' => 'Compteur réinitialisé', 'prolonger_validite' => 'Validité prolongée'][$reclamation->action] ?? $reclamation->action }}
                                @if($reclamation->valeur) ({{ $reclamation->valeur }}) @endif
                            </div>
                        @endif
                        @if($reclamation->admin_comment)
                            <div style="margin-bottom:6px;"><strong>Commentaire :</strong> "{{ $reclamation->admin_comment }}"</div>
                        @endif
                        <div style="color:#666;">
                            Traitée le {{ $reclamation->traite_le ? \Carbon\Carbon::parse($reclamation->traite_le)->format('d/m/Y à H:i') : '—' }}
                            @if($reclamation->traite_par_nom)
                                par {{ $reclamation->traite_par_prenom }} {{ $reclamation->traite_par_nom }}
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Approve / reject actions --}}
            @if($reclamation->statut === 'en_attente')
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:8px;">
                    <form method="POST" action="{{ route('admin.reclamations.approve', $reclamation->id) }}" style="padding:16px;background:#f9fafb;border-radius:10px;">
                        @csrf
                        <div style="font-weight:600;margin-bottom:10px;color:#166534;">✓ Approuver</div>
                        <select name="action" required style="width:100%;margin-bottom:8px;padding:8px;border-radius:6px;border:1px solid #ddd;">
                            <option value="extra_submission">Accorder des soumissions supplémentaires</option>
                            <option value="reset_compteur">Réinitialiser le compteur à 0</option>
                            <option value="prolonger_validite">Prolonger la validité (mois)</option>
                        </select>
                        <input type="number" name="valeur" min="1" max="120" placeholder="Valeur (ex: 1)"
                               style="width:100%;margin-bottom:8px;padding:8px;border-radius:6px;border:1px solid #ddd;box-sizing:border-box;">
                        <input type="text" name="admin_comment" maxlength="1000" placeholder="Commentaire (optionnel)"
                               style="width:100%;margin-bottom:8px;padding:8px;border-radius:6px;border:1px solid #ddd;box-sizing:border-box;">
                        <button type="submit"
                            style="width:100%;padding:10px;background:#16a34a;color:#fff;border:none;border-radius:6px;cursor:pointer;font-weight:600;">
                            ✓ Approuver
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.reclamations.reject', $reclamation->id) }}" style="padding:16px;background:#f9fafb;border-radius:10px;">
                        @csrf
                        <div style="font-weight:600;margin-bottom:10px;color:#991b1b;">✕ Rejeter</div>
                        <input type="text" name="admin_comment" maxlength="1000" placeholder="Motif du rejet (optionnel)"
                               style="width:100%;margin-bottom:8px;padding:8px;border-radius:6px;border:1px solid #ddd;box-sizing:border-box;">
                        <button type="submit"
                            style="width:100%;padding:10px;background:#dc2626;color:#fff;border:none;border-radius:6px;cursor:pointer;font-weight:600;margin-top:auto;">
                            ✕ Rejeter
                        </button>
                    </form>
                </div>
            @endif

            {{-- Delete --}}
            <div style="border-top:1px solid #eee;margin-top:16px;padding-top:16px;text-align:right;">
                <button type="button" onclick="openDeleteModal()"
                    style="padding:8px 16px;background:#fff;border:1px solid #dc2626;color:#dc2626;border-radius:6px;cursor:pointer;font-size:13px;font-weight:600;">
                    🗑 Supprimer cette réclamation
                </button>
            </div>

        </div>
    </div>
</div>

{{-- Delete confirmation modal --}}
<div id="deleteModalOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;padding:24px;max-width:420px;width:90%;">
        <h3 style="margin:0 0 10px;font-size:17px;color:#991b1b;">⚠️ Confirmer la suppression</h3>
        <p style="font-size:13px;color:#666;line-height:1.5;margin-bottom:14px;">
            Cette réclamation sera définitivement supprimée. Pour confirmer, tapez <strong>SUPPRIMER</strong> ci-dessous.
        </p>
        <input type="text" id="deleteConfirmInput" placeholder="Tapez SUPPRIMER"
               autocomplete="off"
               style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;margin-bottom:14px;font-size:14px;box-sizing:border-box;">
        <div style="display:flex;gap:10px;justify-content:flex-end;">
            <button type="button" onclick="closeDeleteModal()"
                style="padding:8px 16px;background:#f1f1f1;border:none;border-radius:6px;cursor:pointer;font-weight:600;">
                Annuler
            </button>
            <button type="button" id="deleteConfirmBtn" onclick="submitDelete()" disabled
                style="padding:8px 16px;background:#dc2626;color:#fff;border:none;border-radius:6px;cursor:not-allowed;font-weight:600;opacity:.5;">
                Supprimer
            </button>
        </div>
    </div>
</div>

<form id="deleteForm" method="POST" action="{{ route('admin.reclamations.destroy', $reclamation->id) }}" style="display:none;">
    @csrf
    @method('DELETE')
    <input type="hidden" name="confirmation" id="deleteConfirmation">
</form>

<script>
    function openDeleteModal() {
        document.getElementById('deleteConfirmInput').value = '';
        _refreshConfirmBtnState();
        document.getElementById('deleteModalOverlay').style.display = 'flex';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModalOverlay').style.display = 'none';
    }

    document.getElementById('deleteConfirmInput').addEventListener('input', _refreshConfirmBtnState);

    function _refreshConfirmBtnState() {
        const ok = document.getElementById('deleteConfirmInput').value === 'SUPPRIMER';
        const btn = document.getElementById('deleteConfirmBtn');
        btn.disabled = !ok;
        btn.style.opacity = ok ? '1' : '.5';
        btn.style.cursor = ok ? 'pointer' : 'not-allowed';
    }

    function submitDelete() {
        if (document.getElementById('deleteConfirmInput').value !== 'SUPPRIMER') return;
        document.getElementById('deleteConfirmation').value = 'SUPPRIMER';
        document.getElementById('deleteForm').submit();
    }
</script>
@endsection
