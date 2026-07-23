@extends('shared.layouts.backoffice')

@section('content')

<!-- Page Header -->
<div class="section-head">
    <div>
        <h1 class="section-title">🎓 Diplômes Musicaux</h1>
        <p class="section-sub">Gestion et validation des diplômes en musique arabe et instrumentistes</p>
    </div>
    <button class="btn btn-gold" onclick="showModal('addDiplomaModal')">+ Nouveau Diplôme</button>
</div>

<!-- KPI Cards -->
<div class="kpi-grid">
    <div class="kpi-card blue">
        <div class="kpi-icon">🎓</div>
        <div class="kpi-value">2,156</div>
        <div class="kpi-label">Diplômes Validés</div>
        <div class="kpi-delta up">↑ 18% ce mois</div>
    </div>
    <div class="kpi-card green">
        <div class="kpi-icon">✓</div>
        <div class="kpi-value">1,892</div>
        <div class="kpi-label">Instituts Reconnus</div>
        <div class="kpi-delta up">↑ 5 nouveaux</div>
    </div>
    <div class="kpi-card amber">
        <div class="kpi-icon">⏳</div>
        <div class="kpi-value">287</div>
        <div class="kpi-label">En Validation</div>
        <div class="kpi-delta flat">→ À Examiner</div>
    </div>
    <div class="kpi-card purple">
        <div class="kpi-icon">⚠</div>
        <div class="kpi-value">43</div>
        <div class="kpi-label">Doublons Détectés</div>
        <div class="kpi-delta down">↓ -2</div>
    </div>
</div>

<!-- Navigation Tabs -->
<div style="display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 0;">
    <button class="tab-btn active" onclick="switchTab('tab-validated')" style="padding: 12px 16px; border: none; background: none; color: var(--text); font-weight: 600; font-size: 13px; cursor: pointer; border-bottom: 2px solid var(--gold);">Validés ✓</button>
    <button class="tab-btn" onclick="switchTab('tab-pending')" style="padding: 12px 16px; border: none; background: none; color: var(--text2); font-weight: 600; font-size: 13px; cursor: pointer;">En Attente</button>
    <button class="tab-btn" onclick="switchTab('tab-rejected')" style="padding: 12px 16px; border: none; background: none; color: var(--text2); font-weight: 600; font-size: 13px; cursor: pointer;">Rejetés ✗</button>
    <button class="tab-btn" onclick="switchTab('tab-duplicates')" style="padding: 12px 16px; border: none; background: none; color: var(--text2); font-weight: 600; font-size: 13px; cursor: pointer;">Doublons 🔄</button>
</div>

<!-- Filters -->
<div class="panel" style="margin-bottom: 20px;">
    <div class="panel-body" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <div class="topbar-search" style="width: 280px;">
            <span class="topbar-search-icon">🔍</span>
            <input type="text" placeholder="Rechercher artiste ou institut...">
        </div>
        <select style="padding: 6px 10px; background: var(--bg3); border: 1px solid var(--border); border-radius: 6px; color: var(--text); cursor: pointer;">
            <option>Tous les niveaux</option>
            <option>Débutant</option>
            <option>Intermédiaire</option>
            <option>Avancé</option>
            <option>Expert</option>
        </select>
        <select style="padding: 6px 10px; background: var(--bg3); border: 1px solid var(--border); border-radius: 6px; color: var(--text); cursor: pointer;">
            <option>Tous les domaines</option>
            <option>Musique Arabe Classique</option>
            <option>Musique Traditionnelle</option>
            <option>Instruments</option>
            <option>Composition</option>
        </select>
    </div>
</div>

<!-- Validated Diplomas Tab -->
<div id="tab-validated" class="tab-content">
    <div class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">✓ Diplômes Validés</div>
                <div class="panel-sub">2,156 diplômes approuvés</div>
            </div>
        </div>
        <div class="panel-body no-pad">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Artiste</th>
                            <th>Diplôme</th>
                            <th>Institut</th>
                            <th>Spécialité</th>
                            <th>Année</th>
                            <th>Validé Par</th>
                            <th>Date Validation</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Ahmed Ben Ali</strong></td>
                            <td>Licence en Musique Arabe</td>
                            <td>Conservatoire de Tunis</td>
                            <td>Oud</td>
                            <td>2020</td>
                            <td>Dr. Samir Khalil</td>
                            <td>15/01/2024</td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-sm btn-ghost" title="Voir PDF">📄</button>
                                    <button class="btn btn-sm btn-ghost" title="Détails">👁️</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Fatima Kaddour</strong></td>
                            <td>Diplôme en Danse Traditionnelle</td>
                            <td>Institut National de Danse</td>
                            <td>Danse Folklorique</td>
                            <td>2019</td>
                            <td>Mme Leila Mehchi</td>
                            <td>12/02/2024</td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-sm btn-ghost" title="Voir PDF">📄</button>
                                    <button class="btn btn-sm btn-ghost" title="Détails">👁️</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Mohamed Saïd</strong></td>
                            <td>Master en Musique Arabe</td>
                            <td>Université de Sfax</td>
                            <td>Composition</td>
                            <td>2021</td>
                            <td>Prof. Ali Zahra</td>
                            <td>08/03/2024</td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-sm btn-ghost" title="Voir PDF">📄</button>
                                    <button class="btn btn-sm btn-ghost" title="Détails">👁️</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Pending Validation Tab -->
<div id="tab-pending" class="tab-content" style="display: none;">
    <div class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">⏳ Diplômes en Attente</div>
                <div class="panel-sub">287 diplômes à examiner - OCR/IA en cours</div>
            </div>
        </div>
        <div class="panel-body no-pad">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Artiste</th>
                            <th>Diplôme (OCR)</th>
                            <th>Institut (Détecté)</th>
                            <th>Spécialité (Détectée)</th>
                            <th>Confiance OCR</th>
                            <th>Priorité</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Leila Saidi</strong></td>
                            <td>Licence en Musique...</td>
                            <td>Conservatoire de Sfax</td>
                            <td>Chant Malouf</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <div style="flex: 1; height: 4px; background: var(--bg4); border-radius: 2px; overflow: hidden;">
                                        <div style="height: 100%; width: 78%; background: var(--amber); border-radius: 2px;"></div>
                                    </div>
                                    <span style="font-size: 10px; color: var(--text3);">78%</span>
                                </div>
                            </td>
                            <td><span class="badge amber">Moyen</span></td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-sm btn-ghost" title="Valider">✓</button>
                                    <button class="btn btn-sm btn-ghost" title="Réviser">📝</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Karim Omrani</strong></td>
                            <td>Diplôme Professionnel...</td>
                            <td>Institut Musical Tunisien</td>
                            <td>Piano</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <div style="flex: 1; height: 4px; background: var(--bg4); border-radius: 2px; overflow: hidden;">
                                        <div style="height: 100%; width: 92%; background: var(--green); border-radius: 2px;"></div>
                                    </div>
                                    <span style="font-size: 10px; color: var(--text3);">92%</span>
                                </div>
                            </td>
                            <td><span class="badge green">Élevé</span></td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-sm btn-ghost" title="Valider">✓</button>
                                    <button class="btn btn-sm btn-ghost" title="Réviser">📝</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Rejected Tab -->
<div id="tab-rejected" class="tab-content" style="display: none;">
    <div class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">✗ Diplômes Rejetés</div>
                <div class="panel-sub">85 diplômes avec problèmes détectés</div>
            </div>
        </div>
        <div class="panel-body no-pad">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Artiste</th>
                            <th>Raison du Rejet</th>
                            <th>Date Rejet</th>
                            <th>Rejeté Par</th>
                            <th>Statut Appel</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Nadia Turki</strong></td>
                            <td><span style="color: var(--red);">Institut non reconnu</span></td>
                            <td>20/03/2024</td>
                            <td>Dr. Samir Khalil</td>
                            <td>-</td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-sm btn-ghost" title="Détails">👁️</button>
                                    <button class="btn btn-sm btn-outline" title="Appel">📢</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Sami Hadj</strong></td>
                            <td><span style="color: var(--red);">Diplôme expiré ou invalide</span></td>
                            <td>18/03/2024</td>
                            <td>Mme Leila Mehchi</td>
                            <td>-</td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-sm btn-ghost" title="Détails">👁️</button>
                                    <button class="btn btn-sm btn-outline" title="Appel">📢</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Duplicates Tab -->
<div id="tab-duplicates" class="tab-content" style="display: none;">
    <div class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">🔄 Doublons Détectés</div>
                <div class="panel-sub">43 diplômes identiques ou similaires</div>
            </div>
        </div>
        <div class="panel-body">
            <div style="background: var(--blue-dim); border-left: 3px solid var(--blue); padding: 12px; border-radius: 4px; margin-bottom: 16px;">
                <div style="font-weight: 600; color: var(--blue); font-size: 12px;">📊 Système de Détection</div>
                <div style="font-size: 11px; color: var(--text2); margin-top: 4px;">Analyse OCR + empreinte numérique pour détecter les copies et doublons</div>
            </div>
            <div style="display: grid; gap: 12px;">
                <div style="background: var(--bg3); border: 1px solid var(--border); padding: 12px; border-radius: 6px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <div style="font-weight: 600; color: var(--text); font-size: 12px;">Ahmed Ben Ali x 2</div>
                        <span class="badge red">Doublon Exact</span>
                    </div>
                    <div style="font-size: 11px; color: var(--text2); margin-bottom: 8px;">Même diplôme soumis 2 fois - Conservatoire de Tunis 2020</div>
                    <button class="btn btn-sm btn-outline" style="width: 100%;">Fusionner & Supprimer</button>
                </div>
                <div style="background: var(--bg3); border: 1px solid var(--border); padding: 12px; border-radius: 6px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <div style="font-weight: 600; color: var(--text); font-size: 12px;">Mohamed Saïd x 3</div>
                        <span class="badge amber">Similaire (87%)</span>
                    </div>
                    <div style="font-size: 11px; color: var(--text2); margin-bottom: 8px;">Diplômes avec dates proches - Vérification manuelle requise</div>
                    <button class="btn btn-sm btn-outline" style="width: 100%;">Examiner Différences</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recognized Institutes Panel -->
<div class="panel" style="margin-top: 24px;">
    <div class="panel-head">
        <div>
            <div class="panel-title">🏫 Instituts Reconnus</div>
            <div class="panel-sub">Base de données des établissements valides</div>
        </div>
        <button class="btn btn-sm btn-outline">+ Ajouter Institut</button>
    </div>
    <div class="panel-body">
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
            <div style="background: var(--bg3); padding: 12px; border-radius: 6px; border-left: 3px solid var(--gold);">
                <div style="font-weight: 600; font-size: 12px; color: var(--text);">Conservatoire de Tunis</div>
                <div style="font-size: 10px; color: var(--text3); margin-top: 4px;">⭐ Accrédité MENFP</div>
            </div>
            <div style="background: var(--bg3); padding: 12px; border-radius: 6px; border-left: 3px solid var(--green);">
                <div style="font-weight: 600; font-size: 12px; color: var(--text);">Institut National de Danse</div>
                <div style="font-size: 10px; color: var(--text3); margin-top: 4px;">⭐ Reconnu UNESCO</div>
            </div>
            <div style="background: var(--bg3); padding: 12px; border-radius: 6px; border-left: 3px solid var(--teal);">
                <div style="font-weight: 600; font-size: 12px; color: var(--text);">Université de Sfax</div>
                <div style="font-size: 10px; color: var(--text3); margin-top: 4px;">✓ Ministre confirmé</div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Ajouter Diplôme -->
<div id="addDiplomaModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: var(--bg2); border: 1px solid var(--border); border-radius: var(--radius); width: 90%; max-width: 650px; max-height: 90vh; overflow-y: auto;">
        <div style="padding: 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h2 style="font-size: 16px; font-weight: 700; color: var(--text);">Ajouter Nouveau Diplôme</h2>
            <button onclick="closeModal('addDiplomaModal')" style="background: none; border: none; color: var(--text3); cursor: pointer; font-size: 20px;">✕</button>
        </div>

        <form style="padding: 20px;">
            <!-- Artiste -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text3); text-transform: uppercase; margin-bottom: 8px;">👤 Artiste</label>
                <input type="text" placeholder="Nom de l'artiste..." style="width: 100%; padding: 9px 12px; background: var(--bg3); border: 1px solid var(--border); border-radius: 6px; color: var(--text);">
            </div>

            <!-- Diplôme -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text3); text-transform: uppercase; margin-bottom: 8px;">📜 Diplôme</label>
                <input type="text" placeholder="Ex: Licence en Musique Arabe..." style="width: 100%; padding: 9px 12px; background: var(--bg3); border: 1px solid var(--border); border-radius: 6px; color: var(--text);">
            </div>

            <!-- Institut -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text3); text-transform: uppercase; margin-bottom: 8px;">🏫 Institut</label>
                <input type="text" placeholder="Nom de l'institut..." style="width: 100%; padding: 9px 12px; background: var(--bg3); border: 1px solid var(--border); border-radius: 6px; color: var(--text);">
            </div>

            <!-- Spécialité -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text3); text-transform: uppercase; margin-bottom: 8px;">🎵 Spécialité</label>
                    <select style="width: 100%; padding: 9px 12px; background: var(--bg3); border: 1px solid var(--border); border-radius: 6px; color: var(--text);">
                        <option>Musique Arabe Classique</option>
                        <option>Danse Folklorique</option>
                        <option>Instruments</option>
                        <option>Composition</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text3); text-transform: uppercase; margin-bottom: 8px;">📅 Année</label>
                    <input type="year" placeholder="2024" style="width: 100%; padding: 9px 12px; background: var(--bg3); border: 1px solid var(--border); border-radius: 6px; color: var(--text);">
                </div>
            </div>

            <!-- Upload Document -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text3); text-transform: uppercase; margin-bottom: 8px;">📄 Diplôme (PDF/Image)</label>
                <div style="border: 2px dashed var(--border); border-radius: 6px; padding: 28px; text-align: center; cursor: pointer;">
                    <div style="font-size: 24px; margin-bottom: 8px;">📥</div>
                    <div style="font-size: 12px; color: var(--text2);">Glisser-déposer ou cliquer pour sélectionner</div>
                    <input type="file" style="display: none;">
                </div>
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-gold" style="flex: 1;">Ajouter Diplôme</button>
                <button type="button" onclick="closeModal('addDiplomaModal')" class="btn btn-outline" style="flex: 1;">Annuler</button>
            </div>
        </form>
    </div>
</div>

<script>
function showModal(id) { document.getElementById(id).style.display = 'flex'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }
function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
    document.getElementById(tabName).style.display = 'block';
    document.querySelectorAll('.tab-btn').forEach(btn => btn.style.borderBottom = 'none');
    event.target.style.borderBottom = '2px solid var(--gold)';
}
</script>

@endsection
