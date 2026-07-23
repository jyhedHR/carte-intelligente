@extends('shared.layouts.backoffice')

@section('title', 'Artistes Étrangers')
@section('breadcrumb', 'Artistes Étrangers')

@section('content')
<!-- KPI Cards -->
<div class="kpi-grid">
    <div class="kpi-card gold">
        <div class="kpi-icon">✈️</div>
        <div class="kpi-value">87</div>
        <div class="kpi-label">Demandes Totales</div>
        <div class="kpi-delta up">↑ 6 cette semaine</div>
    </div>
    <div class="kpi-card teal">
        <div class="kpi-icon">✅</div>
        <div class="kpi-value">64</div>
        <div class="kpi-label">Autorisées</div>
        <div class="kpi-delta up">↑ 4 cette semaine</div>
    </div>
    <div class="kpi-card blue">
        <div class="kpi-icon">⏳</div>
        <div class="kpi-value">15</div>
        <div class="kpi-label">En Attente</div>
        <div class="kpi-delta flat">→ Stable</div>
    </div>
    <div class="kpi-card red">
        <div class="kpi-icon">❌</div>
        <div class="kpi-value">8</div>
        <div class="kpi-label">Refusées</div>
        <div class="kpi-delta down">↓ 1 cette semaine</div>
    </div>
</div>

<!-- Filters & Actions -->
<div class="panel" style="margin-bottom: 24px;">
    <div class="panel-head">
        <div>
            <div class="panel-title">Filtres & Actions</div>
            <div class="panel-sub">Gérez l'autorisation des artistes étrangers</div>
        </div>
        <button class="btn btn-gold" onclick="openModal('modal-add-artiste')">+ Nouvelle Demande</button>
    </div>
    <div class="panel-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
            <input type="text" class="form-input" placeholder="Rechercher par nom...">
            <select class="form-select">
                <option>Tous les statuts</option>
                <option>Autorisée</option>
                <option>En attente</option>
                <option>Refusée</option>
            </select>
            <select class="form-select">
                <option>Tous les pays</option>
                <option>Italie</option>
                <option>Espagne</option>
                <option>Allemagne</option>
                <option>Belgique</option>
            </select>
            <button class="btn btn-outline">🔍 Rechercher</button>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="panel">
    <div class="panel-head">
        <div>
            <div class="panel-title">Demandes d'Autorisation - Artistes Étrangers</div>
            <div class="panel-sub">Autorisation pour les artistes étrangers à travailler en France</div>
        </div>
        <div style="display: flex; gap: 8px;">
            <button class="btn btn-outline btn-sm" onclick="showToast('Export CSV réalisé!', 'info')">📥 Exporter</button>
            <button class="btn btn-outline btn-sm" onclick="showToast('Document imprimé!', 'info')">🖨️ Imprimer</button>
        </div>
    </div>
    <div class="panel-body no-pad">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nom Artiste</th>
                        <th>Nationalité</th>
                        <th>Domaine</th>
                        <th>Date Demande</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Marco Rossi</strong></td>
                        <td>🇮🇹 Italie</td>
                        <td>Sculpture</td>
                        <td>20/04/2024</td>
                        <td><span class="badge green">Autorisée</span></td>
                        <td>
                            <div class="row-actions">
                                <button class="btn btn-ghost btn-sm" onclick="openModal('modal-view-artiste-1')">👁️ Voir</button>
                                <button class="btn btn-ghost btn-sm" onclick="openModal('modal-edit-artiste-1')">✏️ Éditer</button>
                                <button class="btn btn-ghost btn-sm btn-red" onclick="showToast('Demande supprimée')">🗑️</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Isabella Garcia</strong></td>
                        <td>🇪🇸 Espagne</td>
                        <td>Peinture</td>
                        <td>19/04/2024</td>
                        <td><span class="badge blue">En attente</span></td>
                        <td>
                            <div class="row-actions">
                                <button class="btn btn-ghost btn-sm" onclick="openModal('modal-view-artiste-2')">👁️ Voir</button>
                                <button class="btn btn-ghost btn-sm" onclick="openModal('modal-edit-artiste-2')">✏️ Éditer</button>
                                <button class="btn btn-ghost btn-sm btn-red" onclick="showToast('Demande supprimée')">🗑️</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Klaus Müller</strong></td>
                        <td>🇩🇪 Allemagne</td>
                        <td>Installation</td>
                        <td>18/04/2024</td>
                        <td><span class="badge gold">Autorisée</span></td>
                        <td>
                            <div class="row-actions">
                                <button class="btn btn-ghost btn-sm" onclick="openModal('modal-view-artiste-3')">👁️ Voir</button>
                                <button class="btn btn-ghost btn-sm" onclick="openModal('modal-edit-artiste-3')">✏️ Éditer</button>
                                <button class="btn btn-ghost btn-sm btn-red" onclick="showToast('Demande supprimée')">🗑️</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Anna Bianchi</strong></td>
                        <td>🇮🇹 Italie</td>
                        <td>Vidéo Art</td>
                        <td>17/04/2024</td>
                        <td><span class="badge red">Refusée</span></td>
                        <td>
                            <div class="row-actions">
                                <button class="btn btn-ghost btn-sm" onclick="openModal('modal-view-artiste-4')">👁️ Voir</button>
                                <button class="btn btn-ghost btn-sm" onclick="openModal('modal-edit-artiste-4')">✏️ Éditer</button>
                                <button class="btn btn-ghost btn-sm btn-red" onclick="showToast('Demande supprimée')">🗑️</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Luc Vandermeule</strong></td>
                        <td>🇧🇪 Belgique</td>
                        <td>Photographie</td>
                        <td>16/04/2024</td>
                        <td><span class="badge green">Autorisée</span></td>
                        <td>
                            <div class="row-actions">
                                <button class="btn btn-ghost btn-sm" onclick="openModal('modal-view-artiste-5')">👁️ Voir</button>
                                <button class="btn btn-ghost btn-sm" onclick="openModal('modal-edit-artiste-5')">✏️ Éditer</button>
                                <button class="btn btn-ghost btn-sm btn-red" onclick="showToast('Demande supprimée')">🗑️</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Artiste Modal -->
<div id="modal-add-artiste" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">Nouvelle Demande d'Autorisation</div>
            <button class="modal-close" onclick="closeModal('modal-add-artiste')">×</button>
        </div>
        <form class="form-action" data-modal-id="modal-add-artiste">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nom Complet *</label>
                    <input type="text" class="form-input" placeholder="Ex: Marco Rossi" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nationalité *</label>
                    <select class="form-select" required>
                        <option value="">-- Sélectionnez un pays --</option>
                        <option>Italie</option>
                        <option>Espagne</option>
                        <option>Allemagne</option>
                        <option>Belgique</option>
                        <option>Autres</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Domaine Artistique *</label>
                    <input type="text" class="form-input" placeholder="Ex: Sculpture" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-input" placeholder="exemple@email.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Durée d'Autorisation (mois)</label>
                    <input type="number" class="form-input" placeholder="Ex: 12" min="1">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modal-add-artiste')">Annuler</button>
                <button type="submit" class="btn btn-gold">Créer Demande</button>
            </div>
        </form>
    </div>
</div>

<!-- View Artiste Modal -->
<div id="modal-view-artiste-1" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">Détails - Marco Rossi</div>
            <button class="modal-close" onclick="closeModal('modal-view-artiste-1')">×</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Nom</label>
                <input type="text" class="form-input" value="Marco Rossi" disabled>
            </div>
            <div class="form-group">
                <label class="form-label">Nationalité</label>
                <input type="text" class="form-input" value="Italie" disabled>
            </div>
            <div class="form-group">
                <label class="form-label">Domaine</label>
                <input type="text" class="form-input" value="Sculpture" disabled>
            </div>
            <div class="form-group">
                <label class="form-label">Statut</label>
                <input type="text" class="form-input" value="Autorisée" disabled>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-view-artiste-1')">Fermer</button>
        </div>
    </div>
</div>

<!-- Edit Artiste Modals -->
@for ($i = 1; $i <= 5; $i++)
<div id="modal-edit-artiste-{{ $i }}" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">Éditer Demande d'Autorisation</div>
            <button class="modal-close" onclick="closeModal('modal-edit-artiste-{{ $i }}')">×</button>
        </div>
        <form class="form-action" data-modal-id="modal-edit-artiste-{{ $i }}">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Statut</label>
                    <select class="form-select">
                        <option>Autorisée</option>
                        <option>En attente</option>
                        <option>Refusée</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Raison du Refus (si applicable)</label>
                    <textarea class="form-textarea" placeholder="Expliquez votre décision..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Date d'Expiration</label>
                    <input type="date" class="form-input">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modal-edit-artiste-{{ $i }}')">Annuler</button>
                <button type="submit" class="btn btn-gold">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-view-artiste-{{ $i }}" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">Détails de la Demande</div>
            <button class="modal-close" onclick="closeModal('modal-view-artiste-{{ $i }}')">×</button>
        </div>
        <div class="modal-body">
            <p style="color: var(--text2); font-size: 13px;">Informations détaillées sur la demande d'autorisation.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-view-artiste-{{ $i }}')">Fermer</button>
        </div>
    </div>
</div>
@endfor
@endsection
