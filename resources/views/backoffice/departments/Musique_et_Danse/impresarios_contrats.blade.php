@extends('shared.layouts.backoffice')

@section('content')

<!-- Page Header -->
<div class="section-head">
    <div>
        <h1 class="section-title">🎭 Gestion des Contrats</h1>
        <p class="section-sub">Gestion des imprésarios artistiques, artistes représentés et contrats | Détection de conflits d'intérêt</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <button class="btn btn-outline" onclick="toggleQuickView()">📊 Vue Rapide</button>
        <button class="btn btn-gold" onclick="showModal('addImpModal')">+ Nouvel Imprésario</button>
    </div>
</div>

<!-- KPI Cards - Compact Layout -->
<div class="kpi-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; margin-bottom: 24px;">
    <div class="kpi-card" style="background: linear-gradient(135deg, var(--gold-dim), transparent); border: 1px solid var(--gold); border-radius: 8px; padding: 16px; cursor: pointer;" onclick="switchTab('tab-impresarios')">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <div style="font-size: 11px; color: var(--text2); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Imprésarios</div>
                <div style="font-size: 28px; font-weight: 700; color: var(--gold); margin: 8px 0;">234</div>
                <div style="font-size: 11px; color: var(--text2);">↑ 12% cette année</div>
            </div>
            <div style="font-size: 28px;">🎭</div>
        </div>
    </div>

    <div class="kpi-card" style="background: linear-gradient(135deg, var(--teal-dim), transparent); border: 1px solid var(--teal); border-radius: 8px; padding: 16px; cursor: pointer;" onclick="switchTab('tab-contracts')">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <div style="font-size: 11px; color: var(--text2); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Contrats Actifs</div>
                <div style="font-size: 28px; font-weight: 700; color: var(--teal); margin: 8px 0;">1,247</div>
                <div style="font-size: 11px; color: var(--text2);">↑ 5 nouveaux</div>
            </div>
            <div style="font-size: 28px;">📋</div>
        </div>
    </div>

    <div class="kpi-card" style="background: linear-gradient(135deg, var(--blue-dim), transparent); border: 1px solid var(--blue); border-radius: 8px; padding: 16px; cursor: pointer;" onclick="switchTab('tab-artists')">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <div style="font-size: 11px; color: var(--text2); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Artistes Représentés</div>
                <div style="font-size: 28px; font-weight: 700; color: var(--blue); margin: 8px 0;">892</div>
                <div style="font-size: 11px; color: var(--text2);">↑ 42 ce mois</div>
            </div>
            <div style="font-size: 28px;">👥</div>
        </div>
    </div>

    <div class="kpi-card" style="background: linear-gradient(135deg, var(--red-dim), transparent); border: 1px solid var(--red); border-radius: 8px; padding: 16px; cursor: pointer;" onclick="switchTab('tab-conflicts')">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <div style="font-size: 11px; color: var(--text2); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Conflits à Résoudre</div>
                <div style="font-size: 28px; font-weight: 700; color: var(--red); margin: 8px 0;">18</div>
                <div style="font-size: 11px; color: var(--text2);">Actions requises</div>
            </div>
            <div style="font-size: 28px;">⚠️</div>
        </div>
    </div>
</div>

<!-- Quick Filters & Search -->
<div class="panel" style="margin-bottom: 20px;">
    <div class="panel-body" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <div class="topbar-search" style="flex: 1; min-width: 250px;">
            <span class="topbar-search-icon">🔍</span>
            <input type="text" id="searchInput" placeholder="Rechercher imprésario, artiste, entreprise..." style="width: 100%;">
        </div>
        <select id="statusFilter" style="padding: 8px 12px; background: var(--bg3); border: 1px solid var(--border); border-radius: 6px; color: var(--text); cursor: pointer; font-size: 13px;">
            <option value="">Tous les statuts</option>
            <option value="verified">✓ Vérifié</option>
            <option value="pending">⏳ En Vérification</option>
            <option value="suspended">⛔ Suspendu</option>
        </select>
        <select id="departmentFilter" style="padding: 8px 12px; background: var(--bg3); border: 1px solid var(--border); border-radius: 6px; color: var(--text); cursor: pointer; font-size: 13px;">
            <option value="">Tous les départements</option>
            <option value="music">🎵 Musique</option>
            <option value="dance">💃 Danse</option>
        </select>
    </div>
</div>

<!-- Tab Navigation -->
<div style="display: flex; gap: 0; margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 0; overflow-x: auto;">
    <button class="tab-btn active" onclick="switchTab('tab-impresarios')" style="padding: 12px 16px; border: none; background: none; color: var(--text); font-weight: 600; font-size: 13px; cursor: pointer; border-bottom: 2px solid var(--gold); white-space: nowrap;">🎭 Imprésarios</button>
    <button class="tab-btn" onclick="switchTab('tab-contracts')" style="padding: 12px 16px; border: none; background: none; color: var(--text2); font-weight: 600; font-size: 13px; cursor: pointer; white-space: nowrap;">📋 Contrats</button>
    <button class="tab-btn" onclick="switchTab('tab-artists')" style="padding: 12px 16px; border: none; background: none; color: var(--text2); font-weight: 600; font-size: 13px; cursor: pointer; white-space: nowrap;">👥 Artistes</button>
    <button class="tab-btn" onclick="switchTab('tab-conflicts')" style="padding: 12px 16px; border: none; background: none; color: var(--text2); font-weight: 600; font-size: 13px; cursor: pointer; white-space: nowrap;">⚠️ Conflits</button>
</div>

<!-- Tab 1: Imprésarios -->
<div id="tab-impresarios" class="tab-content">
    <div class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">🎭 Liste des Imprésarios</div>
                <div class="panel-sub">234 imprésarios enregistrés • 88% vérifiés</div>
            </div>
        </div>
        <div class="panel-body no-pad">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 25%;">Imprésario</th>
                            <th style="width: 20%;">Entreprise</th>
                            <th style="width: 15%;">Artistes</th>
                            <th style="width: 15%;">Vérification</th>
                            <th style="width: 10%;">Statut</th>
                            <th style="width: 15%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td><strong>Samir Khalil</strong><br><span style="font-size: 11px; color: var(--text2);">📧 samir@artmgt.tn</span></td>
                            <td>Art Management Tunis</td>
                            <td><span style="background: var(--blue-dim); padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; color: var(--blue);">28 artistes</span></td>
                            <td><span class="badge green">✓ Vérifié</span></td>
                            <td><span class="badge green">Actif</span></td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-sm btn-ghost" title="Détails" onclick="showModal('viewImpModal')">👁️</button>
                                    <button class="btn btn-sm btn-ghost" title="Artistes">👥</button>
                                    <button class="btn btn-sm btn-ghost" title="Contrats">📄</button>
                                </div>
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td><strong>Leila Mehchi</strong><br><span style="font-size: 11px; color: var(--text2);">📧 leila@elite-prod.tn</span></td>
                            <td>Productions Artistiques Elite</td>
                            <td><span style="background: var(--blue-dim); padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; color: var(--blue);">45 artistes</span></td>
                            <td><span class="badge green">✓ Vérifié</span></td>
                            <td><span class="badge green">Actif</span></td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-sm btn-ghost" title="Détails">👁️</button>
                                    <button class="btn btn-sm btn-ghost" title="Artistes">👥</button>
                                    <button class="btn btn-sm btn-ghost" title="Contrats">📄</button>
                                </div>
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--border); background: var(--red-dim);">
                            <td><strong>Ali Zahra</strong><br><span style="font-size: 11px; color: var(--text2);">📧 ali@sfax-ent.tn</span></td>
                            <td>Sfax Entertainment Group</td>
                            <td><span style="background: var(--blue-dim); padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; color: var(--blue);">18 artistes</span></td>
                            <td><span class="badge amber">⏳ En Vérification</span></td>
                            <td><span class="badge red">⛔ Suspendu</span></td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-sm btn-ghost" title="Détails">👁️</button>
                                    <button class="btn btn-sm btn-gold" title="Valider">✓ Valider</button>
                                </div>
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td><strong>Nadia Turki</strong><br><span style="font-size: 11px; color: var(--text2);">📧 nadia@eventmuse.tn</span></td>
                            <td>Event Muse Tunisie</td>
                            <td><span style="background: var(--blue-dim); padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; color: var(--blue);">32 artistes</span></td>
                            <td><span class="badge green">✓ Vérifié</span></td>
                            <td><span class="badge green">Actif</span></td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-sm btn-ghost" title="Détails">👁️</button>
                                    <button class="btn btn-sm btn-ghost" title="Artistes">👥</button>
                                    <button class="btn btn-sm btn-ghost" title="Contrats">📄</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tab 2: Contrats -->
<div id="tab-contracts" class="tab-content" style="display: none;">
    <div class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">📋 Contrats Artistes ↔ Imprésario</div>
                <div class="panel-sub">1,247 contrats actifs • 45 expirent bientôt</div>
            </div>
        </div>
        <div class="panel-body no-pad">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 20%;">Artiste</th>
                            <th style="width: 20%;">Imprésario</th>
                            <th style="width: 15%;">Type Contrat</th>
                            <th style="width: 15%;">Durée</th>
                            <th style="width: 15%;">Statut</th>
                            <th style="width: 15%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td><strong>Ahmed Ben Ali</strong></td>
                            <td>Art Management Tunis</td>
                            <td><span style="background: var(--gold-dim); padding: 4px 8px; border-radius: 4px; font-size: 11px; color: var(--gold); font-weight: 600;">Exclusive</span></td>
                            <td>15/01/2022 → 15/01/2025</td>
                            <td><span class="badge green">✓ Actif</span></td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-sm btn-ghost" title="Voir PDF">📄</button>
                                    <button class="btn btn-sm btn-ghost" title="Renouveler">🔄</button>
                                </div>
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td><strong>Fatima Kaddour</strong></td>
                            <td>Productions Artistiques Elite</td>
                            <td><span style="background: var(--teal-dim); padding: 4px 8px; border-radius: 4px; font-size: 11px; color: var(--teal); font-weight: 600;">Non-Exclusive</span></td>
                            <td>12/03/2023 → 12/03/2026</td>
                            <td><span class="badge green">✓ Actif</span></td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-sm btn-ghost" title="Voir PDF">📄</button>
                                    <button class="btn btn-sm btn-ghost" title="Renouveler">🔄</button>
                                </div>
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--border); background: var(--red-dim);">
                            <td><strong>Mohamed Saïd</strong></td>
                            <td>Sfax Entertainment Group</td>
                            <td><span style="background: var(--gold-dim); padding: 4px 8px; border-radius: 4px; font-size: 11px; color: var(--gold); font-weight: 600;">Exclusive</span></td>
                            <td>08/06/2021 → 08/06/2024</td>
                            <td><span class="badge red">❌ Expiré</span></td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-sm btn-ghost" title="Voir PDF">📄</button>
                                    <button class="btn btn-sm btn-gold">↻ Renouveler</button>
                                </div>
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--border); background: var(--amber-dim);">
                            <td><strong>Leila Saidi</strong></td>
                            <td>Event Muse Tunisie</td>
                            <td><span style="background: var(--gold-dim); padding: 4px 8px; border-radius: 4px; font-size: 11px; color: var(--gold); font-weight: 600;">Exclusive</span></td>
                            <td>20/11/2023 → 20/11/2025</td>
                            <td><span class="badge amber">⏳ À Réviser</span></td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-sm btn-ghost" title="Voir PDF">📄</button>
                                    <button class="btn btn-sm btn-outline" title="Valider">✓</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tab 3: Artistes Représentés -->
<div id="tab-artists" class="tab-content" style="display: none;">
    <div class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">👥 Artistes Représentés</div>
                <div class="panel-sub">892 artistes associés à des imprésarios</div>
            </div>
        </div>
        <div class="panel-body no-pad">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 25%;">Artiste</th>
                            <th style="width: 20%;">Département</th>
                            <th style="width: 20%;">Imprésario(s)</th>
                            <th style="width: 15%;">Contrats</th>
                            <th style="width: 20%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td><strong>Amira Benali</strong><br><span style="font-size: 11px; color: var(--text2);">Chanteuse Classique</span></td>
                            <td><span class="badge" style="background: var(--purple-dim); color: var(--purple);">🎵 Musique</span></td>
                            <td>Art Management Tunis</td>
                            <td><span style="background: var(--green-dim); padding: 4px 8px; border-radius: 4px; font-size: 11px; color: var(--green); font-weight: 600;">1 actif</span></td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-sm btn-ghost" title="Profil">👁️</button>
                                    <button class="btn btn-sm btn-ghost" title="Contrats">📋</button>
                                </div>
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td><strong>Karim Omrani</strong><br><span style="font-size: 11px; color: var(--text2);">Danseur Contemporain</span></td>
                            <td><span class="badge" style="background: var(--teal-dim); color: var(--teal);">💃 Danse</span></td>
                            <td>Event Muse Tunisie</td>
                            <td><span style="background: var(--green-dim); padding: 4px 8px; border-radius: 4px; font-size: 11px; color: var(--green); font-weight: 600;">1 actif</span></td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-sm btn-ghost" title="Profil">👁️</button>
                                    <button class="btn btn-sm btn-ghost" title="Contrats">📋</button>
                                </div>
                            </td>
                        </tr>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td><strong>Sami Hadj</strong><br><span style="font-size: 11px; color: var(--text2);">Musicien Jazz</span></td>
                            <td><span class="badge" style="background: var(--purple-dim); color: var(--purple);">🎵 Musique</span></td>
                            <td>Elite Productions</td>
                            <td><span style="background: var(--amber-dim); padding: 4px 8px; border-radius: 4px; font-size: 11px; color: var(--amber); font-weight: 600;">2 actifs</span></td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-sm btn-ghost" title="Profil">👁️</button>
                                    <button class="btn btn-sm btn-ghost" title="Contrats">📋</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tab 4: Conflits d'Intérêt -->
<div id="tab-conflicts" class="tab-content" style="display: none;">
    <div class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">⚠️ Détection Automatique de Conflits</div>
                <div class="panel-sub">18 situations anormales détectées | Résolution requise</div>
            </div>
        </div>
        <div class="panel-body">
            <div style="display: grid; gap: 12px;">
                <!-- Conflict 1 -->
                <div style="background: var(--red-dim); border-left: 4px solid var(--red); padding: 14px; border-radius: 6px;">
                    <div style="display: flex; justify-content: space-between; align-items: start; gap: 12px;">
                        <div style="flex: 1;">
                            <div style="font-weight: 700; color: var(--red); font-size: 13px; display: flex; align-items: center; gap: 6px;">
                                🔴 Représentation Multiple Non Déclarée
                            </div>
                            <div style="font-size: 12px; color: var(--text); margin-top: 6px; line-height: 1.5;">
                                <strong>Ahmed Ben Ali</strong> est représenté par <strong>2 imprésarios différents</strong> sans signature de contrat exclusif mutuellement reconnu.
                            </div>
                            <div style="font-size: 11px; color: var(--text2); margin-top: 8px;">
                                Art Management Tunis (depuis 2022) | Elite Productions (depuis 2023)
                            </div>
                        </div>
                        <button class="btn btn-sm btn-red" style="white-space: nowrap;">Examiner</button>
                    </div>
                </div>

                <!-- Conflict 2 -->
                <div style="background: var(--amber-dim); border-left: 4px solid var(--amber); padding: 14px; border-radius: 6px;">
                    <div style="display: flex; justify-content: space-between; align-items: start; gap: 12px;">
                        <div style="flex: 1;">
                            <div style="font-weight: 700; color: var(--amber); font-size: 13px; display: flex; align-items: center; gap: 6px;">
                                🔗 Contrats Chevauchants
                            </div>
                            <div style="font-size: 12px; color: var(--text); margin-top: 6px; line-height: 1.5;">
                                <strong>3 artistes</strong> ont des périodes de représentation qui se chevauchent sans accord explicite. Possible conflit de planning ou de services.
                            </div>
                            <div style="font-size: 11px; color: var(--text2); margin-top: 8px;">
                                Fatima Kaddour | Mohamed Saïd | Leila Saidi
                            </div>
                        </div>
                        <button class="btn btn-sm btn-outline" style="white-space: nowrap;">Résoudre</button>
                    </div>
                </div>

                <!-- Conflict 3 -->
                <div style="background: var(--red-dim); border-left: 4px solid var(--red); padding: 14px; border-radius: 6px;">
                    <div style="display: flex; justify-content: space-between; align-items: start; gap: 12px;">
                        <div style="flex: 1;">
                            <div style="font-weight: 700; color: var(--red); font-size: 13px; display: flex; align-items: center; gap: 6px;">
                                🚫 Imprésario Suspendu avec Contrats Actifs
                            </div>
                            <div style="font-size: 12px; color: var(--text); margin-top: 6px; line-height: 1.5;">
                                <strong>Sfax Entertainment Group</strong> est suspendu depuis 3 mois mais a toujours <strong>12 contrats actifs</strong> non résiliés.
                            </div>
                            <div style="font-size: 11px; color: var(--text2); margin-top: 8px;">
                                Action immédiate requise • 18 artistes impactés
                            </div>
                        </div>
                        <button class="btn btn-sm btn-red" style="white-space: nowrap;">Action Requise</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Summary Panels -->
<div class="grid-2" style="margin-top: 24px;">
    <!-- Verification Status -->
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">✓ Statut de Vérification</div>
        </div>
        <div class="panel-body">
            <div class="process-row">
                <div class="process-name" style="font-size: 13px; font-weight: 600;">Vérifiés</div>
                <div style="flex: 1; margin: 0 12px;">
                    <div class="progress-bar">
                        <div class="progress-fill" style="background: var(--green); width: 88%;"></div>
                    </div>
                </div>
                <div class="process-count" style="font-weight: 700; color: var(--green);">206</div>
            </div>
            <div class="process-row">
                <div class="process-name" style="font-size: 13px; font-weight: 600;">En Vérification</div>
                <div style="flex: 1; margin: 0 12px;">
                    <div class="progress-bar">
                        <div class="progress-fill" style="background: var(--amber); width: 9%;"></div>
                    </div>
                </div>
                <div class="process-count" style="font-weight: 700; color: var(--amber);">21</div>
            </div>
            <div class="process-row">
                <div class="process-name" style="font-size: 13px; font-weight: 600;">Suspendus</div>
                <div style="flex: 1; margin: 0 12px;">
                    <div class="progress-bar">
                        <div class="progress-fill" style="background: var(--red); width: 3%;"></div>
                    </div>
                </div>
                <div class="process-count" style="font-weight: 700; color: var(--red);">7</div>
            </div>
        </div>
    </div>

    <!-- Contracts Renewal Alert -->
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">⏰ Contrats à Renouveler (45 jours)</div>
        </div>
        <div class="panel-body">
            <div class="feed-item">
                <div class="feed-dot" style="background: var(--red);"></div>
                <div style="flex: 1;">
                    <div class="feed-text"><strong>Mohamed Saïd</strong> - Sfax Entertainment</div>
                </div>
                <div class="feed-time" style="color: var(--red); font-weight: 600;">Expiré</div>
            </div>
            <div class="feed-item">
                <div class="feed-dot" style="background: var(--amber);"></div>
                <div style="flex: 1;">
                    <div class="feed-text"><strong>Karim Omrani</strong> - Art Management</div>
                </div>
                <div class="feed-time" style="color: var(--amber); font-weight: 600;">28 jours</div>
            </div>
            <div class="feed-item">
                <div class="feed-dot" style="background: var(--gold);"></div>
                <div style="flex: 1;">
                    <div class="feed-text"><strong>Sami Hadj</strong> - Elite Productions</div>
                </div>
                <div class="feed-time" style="color: var(--gold); font-weight: 600;">45 jours</div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Ajouter Imprésario -->
<div id="addImpModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: var(--bg2); border: 1px solid var(--border); border-radius: 8px; width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto;">
        <div style="padding: 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h2 style="font-size: 16px; font-weight: 700; color: var(--text);">🎭 Nouvel Imprésario</h2>
            <button onclick="closeModal('addImpModal')" style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--text2);">✕</button>
        </div>
        <div style="padding: 20px; display: grid; gap: 16px;">
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 6px;">Nom Complet *</label>
                <input type="text" placeholder="Ex: Samir Khalil" style="width: 100%; padding: 8px 12px; background: var(--bg3); border: 1px solid var(--border); border-radius: 6px; color: var(--text); font-size: 13px;">
            </div>
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 6px;">Nom Entreprise *</label>
                <input type="text" placeholder="Ex: Art Management Tunis" style="width: 100%; padding: 8px 12px; background: var(--bg3); border: 1px solid var(--border); border-radius: 6px; color: var(--text); font-size: 13px;">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 6px;">Email *</label>
                    <input type="email" placeholder="email@domain.tn" style="width: 100%; padding: 8px 12px; background: var(--bg3); border: 1px solid var(--border); border-radius: 6px; color: var(--text); font-size: 13px;">
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 6px;">Téléphone</label>
                    <input type="tel" placeholder="+216 XX XXX XXX" style="width: 100%; padding: 8px 12px; background: var(--bg3); border: 1px solid var(--border); border-radius: 6px; color: var(--text); font-size: 13px;">
                </div>
            </div>
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 6px;">Département(s) *</label>
                <div style="display: flex; gap: 12px;">
                    <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                        <input type="checkbox" checked>
                        <span style="font-size: 13px; color: var(--text);">🎵 Musique</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                        <input type="checkbox" checked>
                        <span style="font-size: 13px; color: var(--text);">💃 Danse</span>
                    </label>
                </div>
            </div>
            <div style="display: flex; gap: 10px; padding-top: 12px; border-top: 1px solid var(--border);">
                <button onclick="closeModal('addImpModal')" class="btn btn-outline" style="flex: 1;">Annuler</button>
                <button class="btn btn-gold" style="flex: 1;">Créer Imprésario</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: View Impresario -->
<div id="viewImpModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: var(--bg2); border: 1px solid var(--border); border-radius: 8px; width: 90%; max-width: 700px; max-height: 90vh; overflow-y: auto;">
        <div style="padding: 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h2 style="font-size: 16px; font-weight: 700; color: var(--text);">📋 Détails Imprésario</h2>
            <button onclick="closeModal('viewImpModal')" style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--text2);">✕</button>
        </div>
        <div style="padding: 20px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                <div style="background: var(--bg3); padding: 14px; border-radius: 6px; border: 1px solid var(--border);">
                    <div style="font-size: 11px; color: var(--text2); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Nom</div>
                    <div style="font-size: 14px; font-weight: 700; color: var(--text); margin-top: 4px;">Samir Khalil</div>
                </div>
                <div style="background: var(--bg3); padding: 14px; border-radius: 6px; border: 1px solid var(--border);">
                    <div style="font-size: 11px; color: var(--text2); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Entreprise</div>
                    <div style="font-size: 14px; font-weight: 700; color: var(--text); margin-top: 4px;">Art Management Tunis</div>
                </div>
                <div style="background: var(--bg3); padding: 14px; border-radius: 6px; border: 1px solid var(--border);">
                    <div style="font-size: 11px; color: var(--text2); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Email</div>
                    <div style="font-size: 14px; font-weight: 700; color: var(--blue); margin-top: 4px;">samir@artmgt.tn</div>
                </div>
                <div style="background: var(--bg3); padding: 14px; border-radius: 6px; border: 1px solid var(--border);">
                    <div style="font-size: 11px; color: var(--text2); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Statut</div>
                    <div style="margin-top: 4px;"><span class="badge green">✓ Actif & Vérifié</span></div>
                </div>
            </div>
            <div style="background: var(--bg3); padding: 14px; border-radius: 6px; border: 1px solid var(--border); margin-bottom: 16px;">
                <div style="font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 8px;">📊 Statistiques</div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <div><span style="color: var(--text2); font-size: 11px;">Artistes représentés:</span> <strong style="color: var(--gold);">28</strong></div>
                    <div><span style="color: var(--text2); font-size: 11px;">Contrats actifs:</span> <strong style="color: var(--teal);">28</strong></div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
function showModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function switchTab(tabName, event) {
    const tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(tab => tab.style.display = 'none');

    const buttons = document.querySelectorAll('.tab-btn');
    buttons.forEach(btn => {
        btn.style.color = 'var(--text2)';
        btn.style.borderBottom = 'none';
    });

    document.getElementById(tabName).style.display = 'block';

    event.target.style.color = 'var(--text)';
    event.target.style.borderBottom = '2px solid var(--gold)';
}

function toggleQuickView() {
    alert('Vue rapide - Statistiques en temps réel');
}

document.addEventListener('DOMContentLoaded', function() {
    if (typeof initializeSearchAndFilters === "function") {
        initializeSearchAndFilters();
    }
});
</script>

@endsection
