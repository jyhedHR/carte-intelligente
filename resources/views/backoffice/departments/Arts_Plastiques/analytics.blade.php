@extends('shared.layouts.backoffice')

@section('title', 'Analytics & Rapports')
@section('breadcrumb', 'Analytics & Rapports')

@section('content')
<div class="content">
    <!-- Header -->
    <div class="header">
        <div>
            <h1>Analytics & Rapports</h1>
            <p>Analyse des activités et génération de rapports</p>
        </div>
        <button class="btn btn-primary" onclick="openModal('modal-generate-report')">+ Générer Rapport</button>
    </div>

    <!-- KPI Cards -->
    <div class="kpi-grid">
        <div class="kpi-card red">
            <div class="kpi-icon">📊</div>
            <div class="kpi-label">Total Requêtes</div>
            <div class="kpi-value">1,248</div>
            <div class="badge red">+12% vs mois dernier</div>
        </div>
        <div class="kpi-card green">
            <div class="kpi-icon">✅</div>
            <div class="kpi-label">Taux de Validation</div>
            <div class="kpi-value">87%</div>
            <div class="badge green">+5 pts</div>
        </div>
        <div class="kpi-card gold">
            <div class="kpi-icon">⏱️</div>
            <div class="kpi-label">Délai Moyen</div>
            <div class="kpi-value">4.2j</div>
            <div class="badge gold">-0.3j</div>
        </div>
        <div class="kpi-card blue">
            <div class="kpi-icon">👥</div>
            <div class="kpi-label">Utilisateurs Actifs</div>
            <div class="kpi-value">347</div>
            <div class="badge blue">+23 nouveaux</div>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Filtres</div>
        </div>
        <div class="panel-body no-pad">
            <div style="display: flex; gap: 15px; padding: 15px; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px;">
                    <label class="form-label">Période</label>
                    <select class="form-select" onchange="updateChart()">
                        <option>Cette semaine</option>
                        <option selected>Ce mois</option>
                        <option>Ce trimestre</option>
                        <option>Cette année</option>
                        <option>Personnalisé</option>
                    </select>
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label class="form-label">Département</label>
                    <select class="form-select">
                        <option selected>Tous</option>
                        <option>Photos d'Œuvres</option>
                        <option>Accès FNAP</option>
                        <option>Artistes Étrangers</option>
                        <option>Prêts d'Œuvres</option>
                    </select>
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label class="form-label">Type de Rapport</label>
                    <select class="form-select">
                        <option selected>Tous les types</option>
                        <option>Activité</option>
                        <option>Performance</option>
                        <option>Utilisateurs</option>
                        <option>Révenu</option>
                    </select>
                </div>
                <div style="display: flex; align-items: flex-end;">
                    <button class="btn btn-outline btn-sm">Réinitialiser</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
     <!-- Charts Section -->
    <div class="charts-grid">
        <!-- Chart 1: Requêtes par type -->
        <div class="chart-card">
            <div class="chart-title">Requêtes par Type (Mensuel)</div>
            <div class="chart-placeholder">
                <svg viewBox="0 0 400 200" style="width: 100%; height: 200px;">
                    <!-- Bar chart representation -->
                    <rect x="30" y="50" width="40" height="120" fill="var(--gold)" opacity="0.8"/>
                    <rect x="80" y="80" width="40" height="90" fill="var(--gold)" opacity="0.7"/>
                    <rect x="130" y="40" width="40" height="130" fill="var(--gold)" opacity="0.6"/>
                    <rect x="180" y="70" width="40" height="100" fill="var(--gold)" opacity="0.8"/>
                    <rect x="230" y="60" width="40" height="110" fill="var(--gold)" opacity="0.7"/>
                    <rect x="280" y="100" width="40" height="70" fill="var(--gold)" opacity="0.6"/>

                    <text x="50" y="185" text-anchor="middle" font-size="11" fill="var(--text2)">Photos</text>
                    <text x="100" y="185" text-anchor="middle" font-size="11" fill="var(--text2)">FNAP</text>
                    <text x="150" y="185" text-anchor="middle" font-size="11" fill="var(--text2)">Artistes</text>
                    <text x="200" y="185" text-anchor="middle" font-size="11" fill="var(--text2)">Prêts</text>
                    <text x="250" y="185" text-anchor="middle" font-size="11" fill="var(--text2)">Conflits</text>
                    <text x="300" y="185" text-anchor="middle" font-size="11" fill="var(--text2)">Autres</text>
                </svg>
            </div>
            <div class="chart-legend">
                <div class="legend-item"><span class="legend-dot" style="background: var(--gold);"></span> Requêtes</div>
            </div>
        </div>

        <!-- Chart 2: Statut Requêtes -->
        <div class="chart-card">
            <div class="chart-title">Statut des Requêtes</div>
            <div class="chart-placeholder">
                <svg viewBox="0 0 300 200" style="width: 100%; height: 200px;">
                    <!-- Pie chart representation -->
                    <circle cx="100" cy="100" r="60" fill="var(--green)" opacity="0.8"/>
                    <circle cx="100" cy="100" r="50" fill="var(--bg2)"/>
                    <circle cx="100" cy="100" r="40" fill="var(--gold)" opacity="0.8"/>
                    <circle cx="100" cy="100" r="30" fill="var(--bg2)"/>
                    <circle cx="100" cy="100" r="20" fill="var(--red)" opacity="0.8"/>

                    <text x="100" y="100" text-anchor="middle" dominant-baseline="middle" font-weight="bold" font-size="14" fill="var(--text)">87%</text>

                    <text x="200" y="60" font-size="12" fill="var(--text2)"><tspan fill="var(--green)">●</tspan> Validées</text>
                    <text x="200" y="85" font-size="12" fill="var(--text2)"><tspan fill="var(--gold)">●</tspan> En Cours</text>
                    <text x="200" y="110" font-size="12" fill="var(--text2)"><tspan fill="var(--red)">●</tspan> Rejetées</text>
                </svg>
            </div>
        </div>

        <!-- Chart 3: Délais de Traitement -->
        <div class="chart-card">
            <div class="chart-title">Évolution Délai Moyen (Jours)</div>
            <div class="chart-placeholder">
                <svg viewBox="0 0 400 200" style="width: 100%; height: 200px;">
                    <!-- Line chart representation -->
                    <polyline points="20,140 70,110 120,90 170,70 220,85 270,75 320,60 370,65"
                              fill="none" stroke="var(--gold)" stroke-width="3"/>
                    <polyline points="20,140 70,110 120,90 170,70 220,85 270,75 320,60 370,65"
                              fill="none" stroke="var(--gold)" stroke-width="1" opacity="0.2" stroke-dasharray="5,5"/>

                    <circle cx="20" cy="140" r="3" fill="var(--gold)"/>
                    <circle cx="70" cy="110" r="3" fill="var(--gold)"/>
                    <circle cx="120" cy="90" r="3" fill="var(--gold)"/>
                    <circle cx="170" cy="70" r="3" fill="var(--gold)"/>
                    <circle cx="220" cy="85" r="3" fill="var(--gold)"/>
                    <circle cx="270" cy="75" r="3" fill="var(--gold)"/>
                    <circle cx="320" cy="60" r="3" fill="var(--gold)"/>
                    <circle cx="370" cy="65" r="3" fill="var(--gold)"/>
                </svg>
            </div>
            <div class="chart-footer">Tendance: Amélioration de 0.3 jours</div>
        </div>

        <!-- Chart 4: Top Validators -->
        <div class="chart-card">
            <div class="chart-title">Top Validateurs (Mai 2024)</div>
            <div style="padding: 15px;">
                <div style="margin-bottom: 12px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                        <span style="color: var(--text);">Marie Dubois</span>
                        <span style="color: var(--gold); font-weight: 600;">127</span>
                    </div>
                    <div style="width: 100%; height: 6px; background: var(--bg3); border-radius: 3px; overflow: hidden;">
                        <div style="width: 95%; height: 100%; background: var(--gold);"></div>
                    </div>
                </div>
                <div style="margin-bottom: 12px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                        <span style="color: var(--text);">Pierre Martin</span>
                        <span style="color: var(--gold); font-weight: 600;">112</span>
                    </div>
                    <div style="width: 100%; height: 6px; background: var(--bg3); border-radius: 3px; overflow: hidden;">
                        <div style="width: 85%; height: 100%; background: var(--gold);"></div>
                    </div>
                </div>
                <div style="margin-bottom: 12px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                        <span style="color: var(--text);">Sophie Laurent</span>
                        <span style="color: var(--gold); font-weight: 600;">98</span>
                    </div>
                    <div style="width: 100%; height: 6px; background: var(--bg3); border-radius: 3px; overflow: hidden;">
                        <div style="width: 75%; height: 100%; background: var(--gold);"></div>
                    </div>
                </div>
                <div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                        <span style="color: var(--text);">Jean Arnould</span>
                        <span style="color: var(--gold); font-weight: 600;">85</span>
                    </div>
                    <div style="width: 100%; height: 6px; background: var(--bg3); border-radius: 3px; overflow: hidden;">
                        <div style="width: 65%; height: 100%; background: var(--gold);"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports List -->
    <div class="panel" style="margin-top: 30px;">
        <div class="panel-head">
            <div class="panel-title">Rapports Générés Récemment</div>
        </div>
        <div class="panel-body no-pad">
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Type Rapport</th>
                            <th>Date Génération</th>
                            <th>Période</th>
                            <th>Format</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="badge gray">Activité Mensuelle</span></td>
                            <td>15 mai 2024</td>
                            <td>Mai 2024</td>
                            <td>PDF, Excel</td>
                            <td><span class="badge green">Disponible</span></td>
                            <td class="row-actions">
                                <button class="btn-ghost btn-sm" onclick="showToast('Téléchargement du PDF...')" title="Télécharger PDF">📥</button>
                                <button class="btn-ghost btn-sm" onclick="showToast('Export Excel...')" title="Exporter">📊</button>
                                <button class="btn-ghost btn-sm" onclick="showToast('Partage du rapport...')" title="Partager">🔗</button>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="badge gray">Performance</span></td>
                            <td>10 mai 2024</td>
                            <td>Avril 2024</td>
                            <td>PDF</td>
                            <td><span class="badge green">Disponible</span></td>
                            <td class="row-actions">
                                <button class="btn-ghost btn-sm" onclick="showToast('Téléchargement du PDF...')" title="Télécharger PDF">📥</button>
                                <button class="btn-ghost btn-sm" onclick="showToast('Partage du rapport...')" title="Partager">🔗</button>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="badge gray">Utilisateurs Actifs</span></td>
                            <td>08 mai 2024</td>
                            <td>Q1 2024</td>
                            <td>PDF, Excel</td>
                            <td><span class="badge green">Disponible</span></td>
                            <td class="row-actions">
                                <button class="btn-ghost btn-sm" onclick="showToast('Téléchargement du PDF...')" title="Télécharger PDF">📥</button>
                                <button class="btn-ghost btn-sm" onclick="showToast('Export Excel...')" title="Exporter">📊</button>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="badge gray">Analyse Conflits</span></td>
                            <td>05 mai 2024</td>
                            <td>Avril 2024</td>
                            <td>PDF</td>
                            <td><span class="badge green">Disponible</span></td>
                            <td class="row-actions">
                                <button class="btn-ghost btn-sm" onclick="showToast('Téléchargement du PDF...')" title="Télécharger PDF">📥</button>
                                <button class="btn-ghost btn-sm" onclick="openModal('modal-view-report')" title="Voir">👁️</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Generate Report Modal -->
<div id="modal-generate-report" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">Générer Nouveau Rapport</div>
            <button class="modal-close" onclick="closeModal('modal-generate-report')">×</button>
        </div>
        <form class="form-action" data-modal-id="modal-generate-report">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Type de Rapport</label>
                    <select class="form-select" required>
                        <option>Activité Mensuelle</option>
                        <option>Performance</option>
                        <option>Utilisateurs Actifs</option>
                        <option>Analyse Conflits</option>
                        <option>Rapport Financier</option>
                        <option>Audit</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Période</label>
                    <select class="form-select" required>
                        <option>Ce mois</option>
                        <option>Ce trimestre</option>
                        <option>Cette année</option>
                        <option>Personnalisée</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Format</label>
                    <div style="display: flex; gap: 10px;">
                        <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                            <input type="checkbox" checked> PDF
                        </label>
                        <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                            <input type="checkbox"> Excel
                        </label>
                        <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                            <input type="checkbox"> CSV
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Inclure</label>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                            <input type="checkbox" checked> Graphiques
                        </label>
                        <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                            <input type="checkbox" checked> Tableaux détaillés
                        </label>
                        <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                            <input type="checkbox"> Données brutes
                        </label>
                        <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                            <input type="checkbox"> Prévisions
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Envoyer à (Email)</label>
                    <input type="email" class="form-input" placeholder="votre@email.com">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modal-generate-report')">Annuler</button>
                <button type="submit" class="btn btn-primary">Générer Rapport</button>
            </div>
        </form>
    </div>
</div>

<!-- View Report Modal -->
<div id="modal-view-report" class="modal">
    <div class="modal-content" style="max-width: 900px;">
        <div class="modal-header">
            <div class="modal-title">Rapport: Analyse Conflits - Avril 2024</div>
            <button class="modal-close" onclick="closeModal('modal-view-report')">×</button>
        </div>
        <div class="modal-body" style="max-height: 600px; overflow-y: auto;">
            <div style="line-height: 1.6; color: var(--text2);">
                <h4 style="margin-top: 0;">Résumé Exécutif</h4>
                <p>Ce rapport analyse les conflits et litiges enregistrés au cours du mois d'avril 2024 au sein de la Direction des Arts Plastiques.</p>

                <h4>Statistiques Principales</h4>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Total de conflits enregistrés: 24</li>
                    <li>Conflits résolus: 10 (42%)</li>
                    <li>Conflits en cours: 12 (50%)</li>
                    <li>Conflits escaladés: 2 (8%)</li>
                    <li>Délai moyen de résolution: 28 jours</li>
                </ul>

                <h4>Répartition par Type</h4>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Droit d'auteur: 8 (33%)</li>
                    <li>Propriété intellectuelle: 6 (25%)</li>
                    <li>Paiement: 5 (21%)</li>
                    <li>Exposition: 3 (13%)</li>
                    <li>Prêt: 2 (8%)</li>
                </ul>

                <h4>Analyse par Priorité</h4>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Critiques: 5 (21%) - 100% en cours</li>
                    <li>Hautes: 8 (33%) - 75% en cours</li>
                    <li>Moyennes: 7 (29%) - 43% résolus</li>
                    <li>Basses: 4 (17%) - 100% résolus</li>
                </ul>

                <h4>Recommandations</h4>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Augmenter les ressources pour les cas critiques</li>
                    <li>Implémenter une médiation précoce</li>
                    <li>Améliorer la communication avec les parties prenantes</li>
                    <li>Former les équipes aux techniques de résolution</li>
                </ul>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeModal('modal-view-report')">Fermer</button>
            <button type="button" class="btn btn-primary" onclick="showToast('Rapport téléchargé...')">Télécharger</button>
        </div>
    </div>
</div>
<style>
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    .chart-card {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 20px;
    }
    .chart-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 15px;
    }
    .chart-placeholder {
        width: 100%;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .chart-legend {
        display: flex;
        gap: 15px;
        margin-top: 10px;
    }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--text2);
    }
    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
    .chart-footer {
        font-size: 12px;
        color: var(--text3);
        margin-top: 10px;
        text-align: center;
    }
    h3 {
        color: var(--text);
    }
    h4 {
        color: var(--text);
        font-size: 14px;
        margin: 15px 0 8px 0;
    }
    ul, ol {
        color: var(--text2);
        font-size: 13px;
    }
</style>
@endsection
