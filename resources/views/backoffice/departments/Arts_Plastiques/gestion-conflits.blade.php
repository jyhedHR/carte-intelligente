@extends('shared.layouts.backoffice')

@section('title', 'Gestion des Documents')
@section('breadcrumb', 'Gestion des Documents')

@section('content')
<!-- KPI Cards -->
<div class="kpi-grid">
    <div class="kpi-card gold">
        <div class="kpi-icon">📁</div>
        <div class="kpi-value">1,547</div>
        <div class="kpi-label">Documents Total</div>
        <div class="kpi-delta up">↑ +125 ce mois</div>
    </div>
    <div class="kpi-card green">
        <div class="kpi-icon">💾</div>
        <div class="kpi-value">24.5 GB</div>
        <div class="kpi-label">Stockage Utilisé</div>
        <div class="kpi-delta flat">→ 42% de 50 GB</div>
    </div>
    <div class="kpi-card red">
        <div class="kpi-icon">⚠️</div>
        <div class="kpi-value">8</div>
        <div class="kpi-label">Documents Quarantainés</div>
        <div class="kpi-delta down">↓ À vérifier</div>
    </div>
    <div class="kpi-card blue">
        <div class="kpi-icon">📥</div>
        <div class="kpi-value">342</div>
        <div class="kpi-label">Téléchargements (30j)</div>
        <div class="kpi-delta up">↑ +15%</div>
    </div>
</div>

<!-- Filters & Actions -->
<div class="panel" style="margin-bottom: 24px;">
    <div class="panel-head">
        <div>
            <div class="panel-title">Filtres & Actions</div>
            <div class="panel-sub">Organisez et gérez tous les documents relatifs aux demandes</div>
        </div>
        <button class="btn btn-gold" onclick="openModal('modal-upload-doc')">+ Uploader Document</button>
    </div>
    <div class="panel-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
            <input type="text" class="form-input" placeholder="Rechercher par nom...">
            <select class="form-select">
                <option>Tous les statuts</option>
                <option>Approuvé</option>
                <option>En Attente</option>
                <option>Quarantainé</option>
                <option>Rejeté</option>
            </select>
            <select class="form-select">
                <option>Tous les types</option>
                <option>Certificats</option>
                <option>Photos/Images</option>
                <option>Contrats</option>
                <option>Autorisations</option>
            </select>
            <select class="form-select">
                <option>Tous les dossiers</option>
                <option>Photos d'Œuvres</option>
                <option>FNAP</option>
                <option>Artistes Étrangers</option>
                <option>Prêts d'Œuvres</option>
            </select>
        </div>
    </div>
</div>

<!-- Documents Table -->
<div class="panel">
    <div class="panel-head">
        <div>
            <div class="panel-title">Documents Récents</div>
            <div class="panel-sub">Suivi des documents et validations</div>
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
                        <th>Nom du Document</th>
                        <th>Type</th>
                        <th>Taille</th>
                        <th>Dossier</th>
                        <th>Date Upload</th>
                        <th>Statut</th>
                        <th>Uploadé par</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="code">📄 Certificat_Auth_001.pdf</span></td>
                        <td><span class="badge gray">Certificat</span></td>
                        <td>2.3 MB</td>
                        <td>Photos d'Œuvres</td>
                        <td>15/05/2024</td>
                        <td><span class="badge green">Approuvé</span></td>
                        <td>
                            <div class="row-user">
                                <div class="avatar-sm">MR</div>
                                <div>
                                    <strong>Marie Rousseau</strong><br>
                                    <span class="text-muted">marie@art.fr</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="row-actions">
                                <button class="btn btn-ghost btn-sm" onclick="showToast('Téléchargement...')" title="Télécharger">📥</button>
                                <button class="btn btn-ghost btn-sm" onclick="openModal('modal-view-doc')" title="Aperçu">👁️</button>
                                <button class="btn btn-ghost btn-sm btn-gold" onclick="openModal('modal-edit-doc')">✏️</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="code">🖼️ Artwork_Photo_156.jpg</span></td>
                        <td><span class="badge gray">Photo</span></td>
                        <td>5.8 MB</td>
                        <td>Photos d'Œuvres</td>
                        <td>15/05/2024</td>
                        <td><span class="badge green">Approuvé</span></td>
                        <td>
                            <div class="row-user">
                                <div class="avatar-sm">JD</div>
                                <div>
                                    <strong>Jean Dupont</strong><br>
                                    <span class="text-muted">jean@art.fr</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="row-actions">
                                <button class="btn btn-ghost btn-sm" onclick="showToast('Téléchargement...')" title="Télécharger">📥</button>
                                <button class="btn btn-ghost btn-sm" onclick="openModal('modal-view-doc')" title="Aperçu">👁️</button>
                                <button class="btn btn-ghost btn-sm btn-gold" onclick="openModal('modal-edit-doc')">✏️</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="code">📋 Demande_FNAP_087.docx</span></td>
                        <td><span class="badge gray">Document</span></td>
                        <td>1.2 MB</td>
                        <td>FNAP</td>
                        <td>14/05/2024</td>
                        <td><span class="badge blue">En Attente</span></td>
                        <td>
                            <div class="row-user">
                                <div class="avatar-sm">SB</div>
                                <div>
                                    <strong>Sophie Bernard</strong><br>
                                    <span class="text-muted">sophie@art.fr</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="row-actions">
                                <button class="btn btn-ghost btn-sm" onclick="showToast('Téléchargement...')" title="Télécharger">📥</button>
                                <button class="btn btn-ghost btn-sm" onclick="openModal('modal-view-doc')" title="Aperçu">👁️</button>
                                <button class="btn btn-ghost btn-sm btn-gold" onclick="openModal('modal-edit-doc')">✏️</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="code">🛂 Passeport_Chen_Wei.pdf</span></td>
                        <td><span class="badge gray">Identité</span></td>
                        <td>3.1 MB</td>
                        <td>Artistes Étrangers</td>
                        <td>14/05/2024</td>
                        <td><span class="badge green">Approuvé</span></td>
                        <td>
                            <div class="row-user">
                                <div class="avatar-sm">PA</div>
                                <div>
                                    <strong>Pierre Arnould</strong><br>
                                    <span class="text-muted">pierre@art.fr</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="row-actions">
                                <button class="btn btn-ghost btn-sm" onclick="showToast('Téléchargement...')" title="Télécharger">📥</button>
                                <button class="btn btn-ghost btn-sm" onclick="openModal('modal-view-doc')" title="Aperçu">👁️</button>
                                <button class="btn btn-ghost btn-sm btn-gold" onclick="openModal('modal-edit-doc')">✏️</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="code">📋 Contrat_Pret_042.pdf</span></td>
                        <td><span class="badge gray">Contrat</span></td>
                        <td>4.7 MB</td>
                        <td>Prêts d'Œuvres</td>
                        <td>13/05/2024</td>
                        <td><span class="badge red">Rejeté</span></td>
                        <td>
                            <div class="row-user">
                                <div class="avatar-sm">LM</div>
                                <div>
                                    <strong>Luc Martin</strong><br>
                                    <span class="text-muted">luc@art.fr</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="row-actions">
                                <button class="btn btn-ghost btn-sm" onclick="showToast('Téléchargement...')" title="Télécharger">📥</button>
                                <button class="btn btn-ghost btn-sm" onclick="openModal('modal-view-doc')" title="Aperçu">👁️</button>
                                <button class="btn btn-ghost btn-sm btn-red" onclick="openModal('modal-delete-doc')">🗑️</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Quarantined Documents -->
<div class="panel" style="margin-top: 24px;">
    <div class="panel-head">
        <div>
            <div class="panel-title">Documents en Quarantaine</div>
            <div class="panel-sub">Documents à vérifier ou supprimer</div>
        </div>
    </div>
    <div class="panel-body no-pad">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Document</th>
                        <th>Raison</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="background: var(--red-dim); opacity: 0.8;">
                        <td><span class="code">⚠️ Document_Suspect_001.exe</span></td>
                        <td><span class="badge red">Format dangereux détecté</span></td>
                        <td>10/05/2024</td>
                        <td>
                            <div class="row-actions">
                                <button class="btn btn-ghost btn-sm" onclick="showToast('Document analysé...')" title="Analyser">🔍</button>
                                <button class="btn btn-ghost btn-sm btn-red" onclick="openModal('modal-delete-doc')" title="Supprimer">🗑️</button>
                            </div>
                        </td>
                    </tr>
                    <tr style="background: var(--red-dim); opacity: 0.8;">
                        <td><span class="code">📄 Fichier_Crypt.pdf</span></td>
                        <td><span class="badge red">Fichier corrompu</span></td>
                        <td>08/05/2024</td>
                        <td>
                            <div class="row-actions">
                                <button class="btn btn-ghost btn-sm" onclick="showToast('Tentative de réparation...')" title="Réparer">🔧</button>
                                <button class="btn btn-ghost btn-sm btn-red" onclick="openModal('modal-delete-doc')" title="Supprimer">🗑️</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- View Document Modal -->
<div class="modal" id="modal-view-doc">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">Aperçu du Document</div>
            <button class="modal-close" onclick="closeModal('modal-view-doc')">✕</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Nom du Document</label>
                <input type="text" class="form-input" value="Certificat_Auth_001.pdf" readonly>
            </div>
            <div class="form-group">
                <label class="form-label">Type</label>
                <input type="text" class="form-input" value="Certificat" readonly>
            </div>
            <div class="form-group">
                <label class="form-label">Dossier</label>
                <input type="text" class="form-input" value="Photos d'Œuvres" readonly>
            </div>
            <div class="form-group">
                <label class="form-label">Uploadé par</label>
                <input type="text" class="form-input" value="Marie Rousseau (marie@art.fr)" readonly>
            </div>
            <div class="form-group">
                <label class="form-label">Date Upload</label>
                <input type="text" class="form-input" value="15/05/2024 14:32" readonly>
            </div>
            <div class="form-group">
                <label class="form-label">Statut</label>
                <input type="text" class="form-input" value="Approuvé" readonly>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-view-doc')">Fermer</button>
            <button class="btn btn-gold" onclick="closeModal('modal-view-doc'); showToast('Téléchargement en cours...', 'success')">📥 Télécharger</button>
        </div>
    </div>
</div>

<!-- Edit Document Modal -->
<div class="modal" id="modal-edit-doc">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">Éditer le Document</div>
            <button class="modal-close" onclick="closeModal('modal-edit-doc')">✕</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Statut</label>
                <select class="form-select">
                    <option>Approuvé</option>
                    <option>En Attente</option>
                    <option>Rejeté</option>
                    <option>Quarantainé</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Dossier</label>
                <select class="form-select">
                    <option>Photos d'Œuvres</option>
                    <option>FNAP</option>
                    <option>Artistes Étrangers</option>
                    <option>Prêts d'Œuvres</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Commentaires</label>
                <textarea class="form-input" placeholder="Ajoutez vos observations..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-edit-doc')">Annuler</button>
            <button class="btn btn-gold" onclick="closeModal('modal-edit-doc'); showToast('Document mis à jour!', 'success')">Enregistrer</button>
        </div>
    </div>
</div>

<!-- Delete Document Modal -->
<div class="modal" id="modal-delete-doc">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">Supprimer le Document</div>
            <button class="modal-close" onclick="closeModal('modal-delete-doc')">✕</button>
        </div>
        <div class="modal-body">
            <div style="padding: 20px 0; text-align: center;">
                <div style="font-size: 48px; margin-bottom: 15px;">⚠️</div>
                <div style="font-size: 15px; font-weight: 600; color: var(--text); margin-bottom: 8px;">Êtes-vous certain de vouloir supprimer ce document?</div>
                <div style="font-size: 13px; color: var(--text2); margin-bottom: 20px;">Cette action est irréversible. Le document sera supprimé définitivement du système.</div>
                <div style="background: var(--bg3); border: 1px solid var(--border); border-radius: 6px; padding: 12px; font-size: 12px; color: var(--text2); text-align: left;">
                    <strong>Document:</strong> Contrat_Pret_042.pdf<br>
                    <strong>Dossier:</strong> Prêts d'Œuvres<br>
                    <strong>Uploadé par:</strong> Luc Martin
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-delete-doc')">Annuler</button>
            <button class="btn btn-red" onclick="closeModal('modal-delete-doc'); showToast('Document supprimé!', 'success')">🗑️ Supprimer définitivement</button>
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
<div class="modal" id="modal-upload-doc">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">Uploader un Nouveau Document</div>
            <button class="modal-close" onclick="closeModal('modal-upload-doc')">✕</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Type de Document</label>
                <select class="form-select">
                    <option>Certificat</option>
                    <option>Photo/Image</option>
                    <option>Contrat</option>
                    <option>Autorisation</option>
                    <option>Rapport</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Dossier de Destination</label>
                <select class="form-select">
                    <option>Photos d'Œuvres</option>
                    <option>FNAP</option>
                    <option>Artistes Étrangers</option>
                    <option>Prêts d'Œuvres</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Sélectionner Fichier(s)</label>
                <input type="file" class="form-input" multiple>
            </div>
            <div class="form-group">
                <label class="form-label">Description (Optionnel)</label>
                <textarea class="form-input" placeholder="Ajoutez une description..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-upload-doc')">Annuler</button>
            <button class="btn btn-gold" onclick="closeModal('modal-upload-doc'); showToast('Document uploadé avec succès!', 'success')">Uploader</button>
        </div>
    </div>
</div>

@endsection
