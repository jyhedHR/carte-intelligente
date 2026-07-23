@extends('shared.layouts.backoffice')

@section('title', 'Réclamations — Dérogations de soumission')
@section('breadcrumb', 'Réclamations')

@section('content')
<div class="reclam-wrap" style="padding:24px;">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
        <h1 style="font-size:22px;font-weight:700;margin:0;">Réclamations — Demandes de dérogation</h1>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <a href="{{ route('admin.reclamations.index') }}"
               style="padding:6px 14px;border-radius:20px;font-size:13px;text-decoration:none;
                      background:{{ !request('statut') ? '#c9a84c' : '#f1f1f1' }};
                      color:{{ !request('statut') ? '#fff' : '#333' }};">
                Toutes ({{ ($counts['en_attente'] ?? 0) + ($counts['approuvee'] ?? 0) + ($counts['rejetee'] ?? 0) }})
            </a>
            <a href="{{ route('admin.reclamations.index', ['statut' => 'en_attente']) }}"
               style="padding:6px 14px;border-radius:20px;font-size:13px;text-decoration:none;
                      background:{{ request('statut') === 'en_attente' ? '#c9a84c' : '#f1f1f1' }};
                      color:{{ request('statut') === 'en_attente' ? '#fff' : '#333' }};">
                En attente ({{ $counts['en_attente'] ?? 0 }})
            </a>
            <a href="{{ route('admin.reclamations.index', ['statut' => 'approuvee']) }}"
               style="padding:6px 14px;border-radius:20px;font-size:13px;text-decoration:none;
                      background:{{ request('statut') === 'approuvee' ? '#16a34a' : '#f1f1f1' }};
                      color:{{ request('statut') === 'approuvee' ? '#fff' : '#333' }};">
                Approuvées ({{ $counts['approuvee'] ?? 0 }})
            </a>
            <a href="{{ route('admin.reclamations.index', ['statut' => 'rejetee']) }}"
               style="padding:6px 14px;border-radius:20px;font-size:13px;text-decoration:none;
                      background:{{ request('statut') === 'rejetee' ? '#dc2626' : '#f1f1f1' }};
                      color:{{ request('statut') === 'rejetee' ? '#fff' : '#333' }};">
                Rejetées ({{ $counts['rejetee'] ?? 0 }})
            </a>
            <a href="{{ route('admin.reclamations.export', request()->only('statut')) }}"
               style="padding:6px 14px;border-radius:20px;font-size:13px;text-decoration:none;
                      background:#1f3864;color:#fff;display:inline-flex;align-items:center;gap:6px;">
                ⬇️ Exporter CSV
            </a>
        </div>
    </div>

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

    {{-- Bulk actions bar --}}
    <div id="bulkBar" style="display:none;align-items:center;justify-content:space-between;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:10px 16px;margin-bottom:14px;">
        <span id="bulkCount" style="font-size:13px;color:#991b1b;font-weight:600;"></span>
        <button type="button" onclick="openDeleteModal('bulk')"
            style="padding:7px 16px;background:#dc2626;color:#fff;border:none;border-radius:6px;cursor:pointer;font-weight:600;font-size:13px;">
            🗑 Supprimer la sélection
        </button>
    </div>

    <div style="overflow-x:auto;border:1px solid #eee;border-radius:12px;">
        <table style="width:100%;border-collapse:collapse;font-size:14px;">
            <thead>
                <tr style="background:#faf9f7;text-align:left;">
                    <th style="padding:12px;width:28px;">
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
                    </th>
                    <th style="padding:12px;">Utilisateur</th>
                    <th style="padding:12px;">Formulaire</th>
                    <th style="padding:12px;">Motif</th>
                    <th style="padding:12px;">Soumissions</th>
                    <th style="padding:12px;">Validité jusqu'au</th>
                    <th style="padding:12px;">Statut</th>
                    <th style="padding:12px;">Demandée le</th>
                    <th style="padding:12px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reclamations as $r)
                    <tr style="border-top:1px solid #f0f0f0;vertical-align:top;">
                        <td style="padding:12px;">
                            <input type="checkbox" class="reclam-checkbox" value="{{ $r->id }}" onchange="updateBulkBar()">
                        </td>
                        <td style="padding:12px;">
                            <div style="font-weight:600;">{{ $r->user_prenom }} {{ $r->user_nom }}</div>
                            <div style="color:#999;font-size:12px;">{{ $r->user_email }}</div>
                        </td>
                        <td style="padding:12px;">{{ $r->form_titre }}</td>
                        <td style="padding:12px;max-width:260px;white-space:pre-wrap;">{{ $r->motif }}</td>
                        <td style="padding:12px;">
                            {{ $r->submission_count ?? 0 }}
                            / {{ $r->max_submissions ?? '∞' }}
                            @if(($r->bonus_submissions ?? 0) > 0)
                                <div style="color:#16a34a;font-size:12px;">+{{ $r->bonus_submissions }} bonus</div>
                            @endif
                        </td>
                        <td style="padding:12px;">
                            {{ $r->valid_until ? \Carbon\Carbon::parse($r->valid_until)->format('d/m/Y') : '—' }}
                        </td>
                        <td style="padding:12px;">
                            @if($r->statut === 'en_attente')
                                <span style="background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:20px;font-size:12px;">En attente</span>
                            @elseif($r->statut === 'approuvee')
                                <span style="background:#dcfce7;color:#166534;padding:3px 10px;border-radius:20px;font-size:12px;">Approuvée</span>
                                @if($r->action)
                                    <div style="color:#999;font-size:11px;margin-top:4px;">
                                        {{ ['extra_submission' => '+ soumission(s)', 'reset_compteur' => 'Compteur réinitialisé', 'prolonger_validite' => 'Validité prolongée'][$r->action] ?? $r->action }}
                                        @if($r->valeur) ({{ $r->valeur }}) @endif
                                    </div>
                                @endif
                            @else
                                <span style="background:#fee2e2;color:#991b1b;padding:3px 10px;border-radius:20px;font-size:12px;">Rejetée</span>
                            @endif
                            @if($r->admin_comment)
                                <div style="color:#999;font-size:11px;margin-top:4px;font-style:italic;">"{{ $r->admin_comment }}"</div>
                            @endif
                        </td>
                        <td style="padding:12px;color:#999;font-size:12px;">
                            {{ \Carbon\Carbon::parse($r->created_at)->format('d/m/Y H:i') }}
                        </td>
                        <td style="padding:12px;">
                            <div style="display:flex;flex-direction:column;gap:8px;min-width:150px;">
                                <a href="{{ route('admin.reclamations.show', $r->id) }}"
                                   style="text-align:center;padding:6px 10px;background:#f1f1f1;color:#333;border-radius:6px;text-decoration:none;font-size:12px;font-weight:600;">
                                    👁 Voir le détail
                                </a>

                                @if($r->statut === 'en_attente')
                                    <details>
                                        <summary style="cursor:pointer;color:#c9a84c;font-weight:600;">Traiter</summary>
                                        <div style="margin-top:10px;min-width:240px;">
                                            {{-- Approve form --}}
                                            <form method="POST" action="{{ route('admin.reclamations.approve', $r->id) }}" style="margin-bottom:10px;padding:10px;background:#f9fafb;border-radius:8px;">
                                                @csrf
                                                <select name="action" required style="width:100%;margin-bottom:6px;padding:6px;border-radius:6px;border:1px solid #ddd;">
                                                    <option value="extra_submission">Accorder des soumissions supplémentaires</option>
                                                    <option value="reset_compteur">Réinitialiser le compteur à 0</option>
                                                    <option value="prolonger_validite">Prolonger la validité (mois)</option>
                                                </select>
                                                <input type="number" name="valeur" min="1" max="120" placeholder="Valeur (ex: 1)"
                                                       style="width:100%;margin-bottom:6px;padding:6px;border-radius:6px;border:1px solid #ddd;">
                                                <input type="text" name="admin_comment" maxlength="1000" placeholder="Commentaire (optionnel)"
                                                       style="width:100%;margin-bottom:6px;padding:6px;border-radius:6px;border:1px solid #ddd;">
                                                <button type="submit"
                                                    style="width:100%;padding:8px;background:#16a34a;color:#fff;border:none;border-radius:6px;cursor:pointer;font-weight:600;">
                                                    ✓ Approuver
                                                </button>
                                            </form>
                                            {{-- Reject form --}}
                                            <form method="POST" action="{{ route('admin.reclamations.reject', $r->id) }}" style="padding:10px;background:#f9fafb;border-radius:8px;">
                                                @csrf
                                                <input type="text" name="admin_comment" maxlength="1000" placeholder="Motif du rejet (optionnel)"
                                                       style="width:100%;margin-bottom:6px;padding:6px;border-radius:6px;border:1px solid #ddd;">
                                                <button type="submit"
                                                    style="width:100%;padding:8px;background:#dc2626;color:#fff;border:none;border-radius:6px;cursor:pointer;font-weight:600;">
                                                    ✕ Rejeter
                                                </button>
                                            </form>
                                        </div>
                                    </details>
                                @else
                                    <span style="color:#999;font-size:12px;">
                                        Traitée le {{ $r->traite_le ? \Carbon\Carbon::parse($r->traite_le)->format('d/m/Y') : '—' }}
                                        @if($r->traite_par_nom)
                                            <br>par {{ $r->traite_par_prenom }} {{ $r->traite_par_nom }}
                                        @endif
                                    </span>
                                @endif

                                <button type="button" onclick="openDeleteModal('single', {{ $r->id }})"
                                    style="padding:6px 10px;background:#fff;border:1px solid #dc2626;color:#dc2626;border-radius:6px;cursor:pointer;font-size:12px;font-weight:600;">
                                    🗑 Supprimer
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="padding:32px;text-align:center;color:#999;">
                            Aucune réclamation trouvée.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px;">
        {{ $reclamations->links() }}
    </div>
</div>

{{-- ── Delete confirmation modal (shared by single + bulk) ─────────────── --}}
<div id="deleteModalOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:12px;padding:24px;max-width:420px;width:90%;">
        <h3 style="margin:0 0 10px;font-size:17px;color:#991b1b;">⚠️ Confirmer la suppression</h3>
        <p id="deleteModalText" style="font-size:13px;color:#666;line-height:1.5;margin-bottom:14px;">
            Cette action est irréversible. Pour confirmer, tapez <strong>SUPPRIMER</strong> ci-dessous.
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

{{-- Hidden forms actually submitted by the modal --}}
<form id="singleDeleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
    <input type="hidden" name="confirmation" id="singleDeleteConfirmation">
</form>

<form id="bulkDeleteForm" method="POST" action="{{ route('admin.reclamations.bulk-destroy') }}" style="display:none;">
    @csrf
    @method('DELETE')
    <input type="hidden" name="confirmation" id="bulkDeleteConfirmation">
    <div id="bulkDeleteIdsContainer"></div>
</form>

<script>
    let _deleteMode = null; // 'single' | 'bulk'
    let _deleteTargetId = null;

    function toggleSelectAll(checkbox) {
        document.querySelectorAll('.reclam-checkbox').forEach(cb => cb.checked = checkbox.checked);
        updateBulkBar();
    }

    function updateBulkBar() {
        const checked = document.querySelectorAll('.reclam-checkbox:checked');
        const bar = document.getElementById('bulkBar');
        const countLabel = document.getElementById('bulkCount');

        if (checked.length > 0) {
            bar.style.display = 'flex';
            countLabel.textContent = checked.length + ' réclamation(s) sélectionnée(s)';
        } else {
            bar.style.display = 'none';
        }
    }

    function openDeleteModal(mode, id = null) {
        _deleteMode = mode;
        _deleteTargetId = id;

        const text = document.getElementById('deleteModalText');
        if (mode === 'bulk') {
            const count = document.querySelectorAll('.reclam-checkbox:checked').length;
            text.innerHTML = `Vous êtes sur le point de supprimer <strong>${count}</strong> réclamation(s). Cette action est irréversible. Pour confirmer, tapez <strong>SUPPRIMER</strong> ci-dessous.`;
        } else {
            text.innerHTML = 'Cette réclamation sera définitivement supprimée. Pour confirmer, tapez <strong>SUPPRIMER</strong> ci-dessous.';
        }

        document.getElementById('deleteConfirmInput').value = '';
        _refreshConfirmBtnState();
        document.getElementById('deleteModalOverlay').style.display = 'flex';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModalOverlay').style.display = 'none';
        _deleteMode = null;
        _deleteTargetId = null;
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
        const value = document.getElementById('deleteConfirmInput').value;
        if (value !== 'SUPPRIMER') return;

        if (_deleteMode === 'single') {
            const form = document.getElementById('singleDeleteForm');
            form.action = `{{ url('admin/reclamations') }}/${_deleteTargetId}`;
            document.getElementById('singleDeleteConfirmation').value = value;
            form.submit();
        } else if (_deleteMode === 'bulk') {
            const ids = Array.from(document.querySelectorAll('.reclam-checkbox:checked')).map(cb => cb.value);
            const container = document.getElementById('bulkDeleteIdsContainer');
            container.innerHTML = '';
            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                container.appendChild(input);
            });
            document.getElementById('bulkDeleteConfirmation').value = value;
            document.getElementById('bulkDeleteForm').submit();
        }
    }
</script>
@endsection
