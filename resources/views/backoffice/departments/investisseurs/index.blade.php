@extends('shared.layouts.backoffice')

@section('page-title', "Unité d'Encadrement des Investisseurs - Tableau de bord")

@vite(['resources/assets/css/backend.css', 'resources/assets/css/investisseurs.css'])

@section('content')
    <div class="page active">
        <!-- Header -->
        <div class="livre-header">
            <div>
                <div class="livre-title">Unité d'Encadrement des Investisseurs</div>
                <div class="livre-subtitle">Gestion des investisseurs culturels et des attestations de mécénat</div>
            </div>
        </div>

        <!-- F9: AI INSIGHT PANEL -->
        <div class="ai-insight-panel">
            <div class="ai-insight-header">
                <div class="ai-insight-title">
                    <span class="ai-icon"></span>
                    <span>🧠 AI Insight Panel - Investisseurs</span>
                    <span class="ai-badge active">Analyse en temps réel</span>
                </div>
                <div class="ai-insight-time">Dernière mise à jour: <span id="aiLastUpdate">à l'instant</span></div>
            </div>
            <div class="ai-insight-grid" id="aiInsightGrid"></div>
        </div>

        <!-- Service Cards Grid -->
        <div class="services-grid">
            <!-- Card 1: Mécénat -->
            <div class="service-card" onclick="window.location.href='{{ route('admin.investisseurs.index') }}'">
                <div class="service-card-inner">
                    <div class="service-icon-wrapper">
                        <div class="service-icon">
                            <span class="icon icon-mecenat"></span>
                        </div>
                    </div>
                    <div class="service-content">
                        <h3 class="service-title">🎭 Attestation Mécénat</h3>
                        <p class="service-desc">Gestion des demandes d'attestation pour les investisseurs culturels</p>
                        <div class="service-stats">
                            <div class="service-stat">
                                <span class="service-stat-value" id="mecenatCount">0</span>
                                <span class="service-stat-label">demandes</span>
                            </div>
                            <div class="service-stat">
                                <span class="service-stat-value" id="mecenatPending">0</span>
                                <span class="service-stat-label">en attente</span>
                            </div>
                        </div>
                        <div class="service-footer">
                            <span class="service-badge gold">Processus 7 étapes</span>
                            <div class="service-arrow">
                                <span class="icon-arrow"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Agrément -->
            <div class="service-card" onclick="window.location.href='{{ route('admin.investisseurs.index') }}'">
                <div class="service-card-inner">
                    <div class="service-icon-wrapper">
                        <div class="service-icon">
                            <span class="icon icon-agrement"></span>
                        </div>
                    </div>
                    <div class="service-content">
                        <h3 class="service-title">📜 Demande Agrément</h3>
                        <p class="service-desc">Attribution de l'agrément aux investisseurs culturels</p>
                        <div class="service-stats">
                            <div class="service-stat">
                                <span class="service-stat-value" id="agrementCount">0</span>
                                <span class="service-stat-label">demandes</span>
                            </div>
                            <div class="service-stat">
                                <span class="service-stat-value" id="agrementPending">0</span>
                                <span class="service-stat-label">en attente</span>
                            </div>
                        </div>
                        <div class="service-footer">
                            <span class="service-badge blue">Processus 5 étapes</span>
                            <div class="service-arrow">
                                <span class="icon-arrow"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Certification -->
            <div class="service-card" onclick="window.location.href='{{ route('admin.investisseurs.index') }}'">
                <div class="service-card-inner">
                    <div class="service-icon-wrapper">
                        <div class="service-icon">
                            <span class="icon icon-certification"></span>
                        </div>
                    </div>
                    <div class="service-content">
                        <h3 class="service-title">🏅 Certification</h3>
                        <p class="service-desc">Attribution des certifications aux investisseurs qualifiés</p>
                        <div class="service-stats">
                            <div class="service-stat">
                                <span class="service-stat-value" id="certificationCount">0</span>
                                <span class="service-stat-label">demandes</span>
                            </div>
                            <div class="service-stat">
                                <span class="service-stat-value" id="certificationPending">0</span>
                                <span class="service-stat-label">en attente</span>
                            </div>
                        </div>
                        <div class="service-footer">
                            <span class="service-badge green">Processus 4 étapes</span>
                            <div class="service-arrow">
                                <span class="icon-arrow"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- F6: Investor Engagement Stats (RFM) -->
        <div class="health-score-summary">
            <div class="health-header">
                <div class="health-header-info">
                    <div class="activity-icon user-health-icon"></div>
                    <div class="health-header-text">
                        <h3>🏆 Top Investisseurs (RFM)</h3>
                        <p>Classification Or/Argent/Bronze basée sur l'engagement (Récence, Fréquence, Montant)</p>
                    </div>
                </div>
            </div>
            <div id="engagementStatsContainer"></div>
        </div>

        <!-- F7: Sector Heatmap -->
        <div class="sector-heatmap">
            <div class="sector-header">
                <div class="sector-title">
                    <span>📊</span>
                    <span>Heatmap des investissements par secteur culturel</span>
                    <span class="ai-badge active">Analyse IA</span>
                </div>
            </div>
            <div id="sectorHeatmapContainer"></div>
        </div>

        <!-- Recent Activity Section -->
        <div class="activity-section">
            <div class="activity-header">
                <div class="activity-title">
                    <span class="icon"></span>
                    📋 Activité récente
                </div>
            </div>
            <div class="activity-list" id="recentActivityList"></div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════
         MODAL — TOP INVESTISSEURS DETAILS
    ════════════════════════════════════════════ --}}
    <div class="modal" id="investorDetailModal">
        <div class="modal-content" style="max-width: 650px;">
            <div class="modal-header">
                <div class="modal-title" id="investorModalTitle">Détails des investisseurs</div>
                <button class="modal-close" onclick="closeModal('investorDetailModal')">✕</button>
            </div>
            <div class="modal-body" id="investorDetailContent"></div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('investorDetailModal')">Fermer</button>
                <button class="btn btn-gold" onclick="exportInvestorList()">📥 Exporter la liste</button>
            </div>
        </div>
    </div>

    {{-- MODAL — SECTOR DETAILS --}}
    <div class="modal" id="sectorDetailModal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <div class="modal-title" id="sectorModalTitle">Détails du secteur</div>
                <button class="modal-close" onclick="closeModal('sectorDetailModal')">✕</button>
            </div>
            <div class="modal-body" id="sectorDetailContent"></div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('sectorDetailModal')">Fermer</button>
                <button class="btn btn-gold" onclick="showSectorRecommendation()">💡 Voir recommandation IA</button>
            </div>
        </div>
    </div>

    {{-- MODAL — HIGH RISK DOSSIERS --}}
    <div class="modal" id="highRiskModal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <div class="modal-title">⚠️ Dossiers haut risque</div>
                <button class="modal-close" onclick="closeModal('highRiskModal')">✕</button>
            </div>
            <div class="modal-body" id="highRiskContent"></div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('highRiskModal')">Fermer</button>
                <button class="btn btn-red" onclick="prioritizeHighRisk()">📌 Prioriser ces dossiers</button>
            </div>
        </div>
    </div>

    {{-- MODAL — CHURN RISK INVESTORS --}}
    <div class="modal" id="churnRiskModal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <div class="modal-title">⚠️ Investisseurs à risque de départ</div>
                <button class="modal-close" onclick="closeModal('churnRiskModal')">✕</button>
            </div>
            <div class="modal-body" id="churnRiskContent"></div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('churnRiskModal')">Fermer</button>
                <button class="btn btn-gold" onclick="sendChurnEmails()">📧 Envoyer email de relance</button>
            </div>
        </div>
    </div>

    {{-- MODAL — UNDERFUNDED SECTORS --}}
    <div class="modal" id="underfundedModal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <div class="modal-title">📊 Secteurs sous-financés</div>
                <button class="modal-close" onclick="closeModal('underfundedModal')">✕</button>
            </div>
            <div class="modal-body" id="underfundedContent"></div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('underfundedModal')">Fermer</button>
                <button class="btn btn-gold" onclick="generateSectorReport()">📄 Générer rapport</button>
            </div>
        </div>
    </div>

    <style>
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 1100;
            align-items: center;
            justify-content: center;
        }
        .modal.active {
            display: flex;
        }
        .modal-content {
            background: var(--bg2);
            border-radius: var(--radius);
            max-width: 650px;
            width: 90%;
            max-height: 80vh;
            overflow: hidden;
            animation: modalFadeIn 0.3s ease;
        }
        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .modal-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-title {
            font-weight: 700;
            font-size: 16px;
        }
        .modal-close {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: var(--text3);
        }
        .modal-close:hover { color: var(--red); }
        .modal-body {
            padding: 20px;
            max-height: 60vh;
            overflow-y: auto;
        }
        .modal-footer {
            padding: 16px 20px;
            border-top: 1px solid var(--border);
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        /* Investor list item */
        .investor-list-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-bottom: 1px solid var(--border);
            transition: background 0.15s;
        }
        .investor-list-item:hover { background: var(--bg3); }
        .investor-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gold-dim);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 700;
            flex-shrink: 0;
        }
        .investor-info { flex: 1; }
        .investor-name { font-weight: 700; font-size: 13px; }
        .investor-stats {
            font-size: 11px;
            color: var(--text3);
            margin-top: 3px;
            display: flex;
            gap: 12px;
        }
        .investor-badge {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
        }
        .investor-badge.gold { background: rgba(201,168,76,0.15); color: var(--gold); }
        .investor-badge.silver { background: rgba(192,192,192,0.15); color: #c0c0c0; }
        .investor-badge.bronze { background: rgba(205,127,50,0.15); color: #cd7f32; }
        .investor-badge.churn { background: rgba(248,113,113,0.15); color: var(--red); }

        /* Sector detail */
        .sector-detail-card {
            background: var(--bg3);
            border-radius: var(--radius-sm);
            padding: 16px;
            margin-bottom: 16px;
        }
        .sector-metrics {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 12px;
        }
        .sector-metric {
            text-align: center;
            padding: 12px;
            background: var(--bg2);
            border-radius: var(--radius-sm);
        }
        .sector-metric-value {
            font-size: 22px;
            font-weight: 900;
            font-family: var(--font-mono);
        }
        .sector-metric-label {
            font-size: 10px;
            color: var(--text3);
            margin-top: 4px;
        }

        /* Engagement tiers */
        .engagement-tiers {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            padding: 16px;
        }
        .engagement-tier-card {
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 16px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .engagement-tier-card:hover {
            transform: translateY(-3px);
            border-color: var(--gold);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .tier-icon { font-size: 28px; margin-bottom: 8px; }
        .tier-value { font-size: 28px; font-weight: 900; font-family: var(--font-mono); }
        .tier-label { font-size: 12px; font-weight: 600; margin-top: 5px; }
        .tier-desc { font-size: 10px; color: var(--text3); margin-top: 3px; }

        /* Sector heatmap */
        .sector-grid {
            padding: 16px;
        }
        .sector-bar-item {
            margin-bottom: 16px;
            cursor: pointer;
            padding: 8px;
            border-radius: var(--radius-sm);
            transition: background 0.15s;
        }
        .sector-bar-item:hover { background: var(--bg3); }
        .sector-bar-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }
        .sector-name {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 13px;
        }
        .sector-percent {
            font-size: 12px;
            font-weight: 700;
        }
        .sector-percent.underfunded { color: var(--red); }
        .sector-percent.overfunded { color: var(--green); }
        .sector-bar-container {
            height: 8px;
            background: var(--bg4);
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 6px;
        }
        .sector-bar {
            height: 100%;
            border-radius: 4px;
            transition: width 0.3s ease;
        }
        .sector-bar.normal { background: var(--gold); }
        .sector-bar.underfunded { background: var(--red); }
        .sector-bar.overfunded { background: var(--green); }
        .sector-target {
            font-size: 10px;
            color: var(--text3);
        }
        .sector-recommendation {
            background: linear-gradient(135deg, var(--purple-dim), rgba(167,139,250,0.05));
            border: 1px solid rgba(167,139,250,0.2);
            border-radius: var(--radius-sm);
            padding: 12px;
            margin-top: 16px;
            font-size: 12px;
            cursor: pointer;
        }

        /* AI Insight Grid */
        .ai-insight-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            padding: 16px;
        }
        .insight-card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 16px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .insight-card:hover {
            border-color: var(--gold);
            transform: translateY(-2px);
        }
        .insight-value {
            font-size: 28px;
            font-weight: 900;
            font-family: var(--font-mono);
            color: var(--gold);
        }
        .insight-label {
            font-size: 11px;
            color: var(--text3);
            margin-top: 5px;
        }
        .insight-action {
            font-size: 10px;
            color: var(--gold);
            margin-top: 8px;
            font-weight: 600;
        }

        /* Activity section */
        .activity-section {
            margin-top: 24px;
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }
        .activity-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
        }
        .activity-title {
            font-weight: 700;
            font-size: 14px;
        }
        .activity-list {
            max-height: 300px;
            overflow-y: auto;
        }
        .activity-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            border-bottom: 1px solid var(--border);
        }
        .activity-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        .activity-icon.mecenat { background: rgba(201,168,76,0.15); color: var(--gold); }
        .activity-icon.agrement { background: rgba(59,130,246,0.15); color: var(--blue); }
        .activity-icon.certification { background: rgba(74,222,128,0.15); color: var(--green); }
        .activity-content { flex: 1; }
        .activity-action { font-weight: 600; font-size: 12px; }
        .activity-entity { font-size: 11px; color: var(--text3); }
        .activity-detail { font-size: 10px; color: var(--text3); margin-top: 2px; }
        .activity-time { font-size: 10px; color: var(--text3); font-family: var(--font-mono); }

        /* Health score summary */
        .health-score-summary {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            margin-bottom: 24px;
            overflow: hidden;
        }
        .health-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
        }
        .health-header-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .health-header-text h3 {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
        }
        .health-header-text p {
            margin: 4px 0 0;
            font-size: 11px;
            color: var(--text3);
        }

        /* Sector heatmap */
        .sector-heatmap {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            margin-bottom: 24px;
            overflow: hidden;
        }
        .sector-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
        }
        .sector-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            font-size: 14px;
        }

        /* Services grid */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .service-card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            cursor: pointer;
            transition: all 0.2s;
        }
        .service-card:hover {
            transform: translateY(-3px);
            border-color: var(--gold);
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }
        .service-card-inner { padding: 20px; }
        .service-icon-wrapper { margin-bottom: 16px; }
        .service-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: var(--gold-dim);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .service-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .service-desc {
            font-size: 12px;
            color: var(--text3);
            margin-bottom: 16px;
            line-height: 1.4;
        }
        .service-stats {
            display: flex;
            gap: 20px;
            margin-bottom: 16px;
        }
        .service-stat {
            text-align: center;
        }
        .service-stat-value {
            font-size: 22px;
            font-weight: 900;
            font-family: var(--font-mono);
            color: var(--gold);
        }
        .service-stat-label {
            font-size: 10px;
            color: var(--text3);
        }
        .service-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .service-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
        }
        .service-badge.gold { background: rgba(201,168,76,0.15); color: var(--gold); }
        .service-badge.blue { background: rgba(59,130,246,0.15); color: var(--blue); }
        .service-badge.green { background: rgba(74,222,128,0.15); color: var(--green); }
        .service-badge.amber { background: rgba(251,191,36,0.15); color: var(--amber); }
    </style>

    <script>
    // ============================================
    // INVESTISSEURS DASHBOARD - ENHANCED SCRIPT
    // ============================================

    // Demo Data
    const mecenatDemandes = [
        { id: 1, numero: 'MEC-20260001', nomInvestisseur: 'Fondation Tunisienne pour la Culture', statut: 'pending', montant: 150000, riskLevel: 'low', documents: 'Complet', dateDepot: '2026-04-10' },
        { id: 2, numero: 'MEC-20260002', nomInvestisseur: 'Groupe Chimique Tunisien', statut: 'progress', montant: 450000, riskLevel: 'medium', documents: 'Partiel', dateDepot: '2026-04-05' },
        { id: 3, numero: 'MEC-20260003', nomInvestisseur: 'Banque Internationale Arabe', statut: 'pending', montant: 2800000, riskLevel: 'high', documents: 'Complet', dateDepot: '2026-04-01' },
        { id: 4, numero: 'MEC-20260004', nomInvestisseur: 'Carthage Cement', statut: 'validated', montant: 750000, riskLevel: 'low', documents: 'Complet', dateDepot: '2026-03-28' },
        { id: 5, numero: 'MEC-20260005', nomInvestisseur: 'Tunisie Telecom', statut: 'progress', montant: 1200000, riskLevel: 'medium', documents: 'Partiel', dateDepot: '2026-03-25' },
        { id: 6, numero: 'MEC-20260006', nomInvestisseur: 'SFBT', statut: 'rejected', montant: 350000, riskLevel: 'high', documents: 'Manquant', dateDepot: '2026-03-20' }
    ];

    const agrementDemandes = [
        { id: 1, numero: 'AGR-20260001', nomInvestisseur: 'Fondation Tunisienne pour la Culture', statut: 'validated' },
        { id: 2, numero: 'AGR-20260002', nomInvestisseur: 'Groupe Chimique Tunisien', statut: 'pending' },
        { id: 3, numero: 'AGR-20260003', nomInvestisseur: 'Carthage Cement', statut: 'progress' }
    ];

    const certificationDemandes = [
        { id: 1, numero: 'CER-20260001', nomInvestisseur: 'Fondation Tunisienne pour la Culture', statut: 'validated' },
        { id: 2, numero: 'CER-20260002', nomInvestisseur: 'Tunisie Telecom', statut: 'pending' }
    ];

    const investorProfiles = [
        { nom: 'Fondation Tunisienne pour la Culture', tier: 'gold', lastSubmission: '2026-04-10', totalSubmissions: 8, totalAmount: 4250000, churnRisk: false, sector: 'Musique' },
        { nom: 'Groupe Chimique Tunisien', tier: 'silver', lastSubmission: '2026-04-05', totalSubmissions: 5, totalAmount: 2150000, churnRisk: false, sector: 'Cinéma' },
        { nom: 'Banque Internationale Arabe', tier: 'gold', lastSubmission: '2026-04-01', totalSubmissions: 12, totalAmount: 6800000, churnRisk: false, sector: 'Livre' },
        { nom: 'Carthage Cement', tier: 'silver', lastSubmission: '2026-03-28', totalSubmissions: 4, totalAmount: 1750000, churnRisk: false, sector: 'Patrimoine' },
        { nom: 'Tunisie Telecom', tier: 'gold', lastSubmission: '2026-03-25', totalSubmissions: 10, totalAmount: 5200000, churnRisk: false, sector: 'Arts Plastiques' },
        { nom: 'SFBT', tier: 'bronze', lastSubmission: '2026-03-20', totalSubmissions: 3, totalAmount: 850000, churnRisk: false, sector: 'Théâtre' },
        { nom: 'Groupe Poulina', tier: 'bronze', lastSubmission: '2025-01-15', totalSubmissions: 2, totalAmount: 450000, churnRisk: true, monthsInactive: 14, sector: 'Musique' },
        { nom: 'Amen Bank', tier: 'bronze', lastSubmission: '2025-02-10', totalSubmissions: 3, totalAmount: 620000, churnRisk: true, monthsInactive: 13, sector: 'Livre' },
        { nom: 'Orange Tunisie', tier: 'silver', lastSubmission: '2026-03-15', totalSubmissions: 6, totalAmount: 2850000, churnRisk: false, sector: 'Cinéma' },
        { nom: 'Attijari Bank', tier: 'bronze', lastSubmission: '2026-02-20', totalSubmissions: 4, totalAmount: 920000, churnRisk: false, sector: 'Patrimoine' }
    ];

    const sectorData = [
        { name: 'Livre', icon: '📚', amount: 850000, percentage: 8, target: 20, gap: -12, investors: 2, topInvestor: 'Banque Internationale Arabe' },
        { name: 'Cinéma', icon: '🎬', amount: 1200000, percentage: 12, target: 20, gap: -8, investors: 2, topInvestor: 'Groupe Chimique Tunisien' },
        { name: 'Théâtre', icon: '🎭', amount: 650000, percentage: 6, target: 15, gap: -9, investors: 1, topInvestor: 'SFBT' },
        { name: 'Musique', icon: '🎵', amount: 3100000, percentage: 30, target: 20, gap: 10, investors: 2, topInvestor: 'Fondation Tunisienne' },
        { name: 'Arts Plastiques', icon: '🎨', amount: 950000, percentage: 9, target: 15, gap: -6, investors: 1, topInvestor: 'Tunisie Telecom' },
        { name: 'Patrimoine', icon: '🏛️', amount: 1750000, percentage: 17, target: 10, gap: 7, investors: 2, topInvestor: 'Carthage Cement' }
    ];

    const recentActivities = [
        { type: 'mecenat', action: 'Nouvelle demande mécénat', entity: 'Fondation Tunisienne', detail: 'Montant: 150 000 TND', time: '2026-04-13T10:30:00' },
        { type: 'mecenat', action: 'Demande validée', entity: 'Carthage Cement', detail: 'Attestation générée', time: '2026-04-12T14:20:00' },
        { type: 'agrement', action: 'Nouvelle demande agrément', entity: 'Groupe Chimique', detail: 'En attente de vérification', time: '2026-04-12T09:15:00' },
        { type: 'certification', action: 'Certification délivrée', entity: 'Tunisie Telecom', detail: 'Certificat valide 2 ans', time: '2026-04-11T16:45:00' },
        { type: 'mecenat', action: 'Anomalie détectée', entity: 'Banque Internationale', detail: 'Montant anormalement élevé', time: '2026-04-11T11:30:00' },
        { type: 'mecenat', action: 'Dossier validé', entity: 'Orange Tunisie', detail: 'Attestation signée', time: '2026-04-10T09:00:00' }
    ];

    // Helper Functions
    function formatRelativeTime(dateStr) {
        const date = new Date(dateStr);
        const now = new Date();
        const diffHours = Math.floor((now - date) / (1000 * 60 * 60));
        if (diffHours < 1) return "À l'instant";
        if (diffHours < 24) return `Il y a ${diffHours} h`;
        return `Il y a ${Math.floor(diffHours / 24)} j`;
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('fr-TN', { style: 'currency', currency: 'TND', maximumFractionDigits: 0 }).format(amount);
    }

    function openModal(id) { document.getElementById(id).classList.add('active'); }
    function closeModal(id) { document.getElementById(id).classList.remove('active'); }

    // Render AI Insights (F9)
    function renderAIInsights() {
        const container = document.getElementById('aiInsightGrid');
        if (!container) return;

        const pendingMecenat = mecenatDemandes.filter(d => d.statut === 'pending').length;
        const highRiskCount = mecenatDemandes.filter(d => d.riskLevel === 'high').length;
        const churnRiskCount = investorProfiles.filter(i => i.churnRisk === true).length;
        const underfundedSectors = sectorData.filter(s => s.gap < -5).length;

        container.innerHTML = `
            <div class="insight-card" onclick="window.location.href='{{ route('admin.investisseurs.index') }}'">
                <div class="insight-value">${pendingMecenat}</div>
                <div class="insight-label">Demandes en attente</div>
                <div class="insight-action">À traiter par l'agent →</div>
            </div>
            <div class="insight-card warning" onclick="showHighRiskDossiers()">
                <div class="insight-value" style="color: #f87171;">${highRiskCount}</div>
                <div class="insight-label">Dossiers haut risque</div>
                <div class="insight-action">⚠️ Vérification prioritaire →</div>
            </div>
            <div class="insight-card" onclick="showChurnAlerts()">
                <div class="insight-value" style="color: #fbbf24;">${churnRiskCount}</div>
                <div class="insight-label">Investisseurs inactifs</div>
                <div class="insight-action">📞 À contacter →</div>
            </div>
            <div class="insight-card" onclick="showUnderfundedSectors()">
                <div class="insight-value" style="color: #a78bfa;">${underfundedSectors}</div>
                <div class="insight-label">Secteurs sous-financés</div>
                <div class="insight-action">📊 Recommandation →</div>
            </div>
        `;

        const timeElement = document.getElementById('aiLastUpdate');
        if (timeElement) timeElement.innerText = new Date().toLocaleTimeString();
    }

    // Render Engagement Stats (F6) with clickable cards
    function renderEngagementStats() {
        const container = document.getElementById('engagementStatsContainer');
        if (!container) return;

        const goldInvestors = investorProfiles.filter(i => i.tier === 'gold');
        const silverInvestors = investorProfiles.filter(i => i.tier === 'silver');
        const bronzeInvestors = investorProfiles.filter(i => i.tier === 'bronze');
        const churnInvestors = investorProfiles.filter(i => i.churnRisk === true);

        container.innerHTML = `
            <div class="engagement-tiers">
                <div class="engagement-tier-card gold" onclick="showInvestorsByTier('gold', '🏆 Investisseurs Or', ${JSON.stringify(goldInvestors).replace(/"/g, '&quot;')})">
                    <div class="tier-icon">🏆</div>
                    <div class="tier-value" style="color: var(--gold);">${goldInvestors.length}</div>
                    <div class="tier-label">Investisseurs Or</div>
                    <div class="tier-desc">Plus de 8 dossiers · Montant > 5M TND</div>
                </div>
                <div class="engagement-tier-card silver" onclick="showInvestorsByTier('silver', '🥈 Investisseurs Argent', ${JSON.stringify(silverInvestors).replace(/"/g, '&quot;')})">
                    <div class="tier-icon">🥈</div>
                    <div class="tier-value" style="color: #c0c0c0;">${silverInvestors.length}</div>
                    <div class="tier-label">Investisseurs Argent</div>
                    <div class="tier-desc">4 à 7 dossiers · Montant 2-5M TND</div>
                </div>
                <div class="engagement-tier-card bronze" onclick="showInvestorsByTier('bronze', '🥉 Investisseurs Bronze', ${JSON.stringify(bronzeInvestors).replace(/"/g, '&quot;')})">
                    <div class="tier-icon">🥉</div>
                    <div class="tier-value" style="color: #cd7f32;">${bronzeInvestors.length}</div>
                    <div class="tier-label">Investisseurs Bronze</div>
                    <div class="tier-desc">Moins de 4 dossiers · Montant < 2M TND</div>
                </div>
                <div class="engagement-tier-card churn" onclick="showChurnAlerts()">
                    <div class="tier-icon">⚠️</div>
                    <div class="tier-value" style="color: var(--red);">${churnInvestors.length}</div>
                    <div class="tier-label">Risque de départ</div>
                    <div class="tier-desc">Inactifs +12 mois</div>
                </div>
            </div>
        `;
    }

    // Show investors by tier in modal
    function showInvestorsByTier(tier, title, investors) {
        const modalTitle = document.getElementById('investorModalTitle');
        const modalBody = document.getElementById('investorDetailContent');

        modalTitle.innerHTML = title;

        if (investors.length === 0) {
            modalBody.innerHTML = `<div style="text-align: center; padding: 40px;">Aucun investisseur dans cette catégorie</div>`;
        } else {
            modalBody.innerHTML = `
                <div style="margin-bottom: 16px;">
                    <div style="background: var(--bg3); padding: 12px; border-radius: var(--radius-sm); margin-bottom: 16px;">
                        <strong>📊 Statistiques:</strong><br>
                        Total investisseurs: ${investors.length}<br>
                        Montant total investi: ${formatCurrency(investors.reduce((sum, i) => sum + (i.totalAmount || 0), 0))}<br>
                        Total dossiers: ${investors.reduce((sum, i) => sum + (i.totalSubmissions || 0), 0)}
                    </div>
                    <div style="font-weight: 700; margin-bottom: 12px;">Liste des investisseurs:</div>
                </div>
                ${investors.map(inv => `
                    <div class="investor-list-item" onclick="showInvestorDetails('${inv.nom}')">
                        <div class="investor-avatar" style="background: ${tier === 'gold' ? 'rgba(201,168,76,0.2)' : tier === 'silver' ? 'rgba(192,192,192,0.2)' : 'rgba(205,127,50,0.2)'}">
                            ${inv.nom.charAt(0)}
                        </div>
                        <div class="investor-info">
                            <div class="investor-name">${inv.nom}</div>
                            <div class="investor-stats">
                                <span>📄 ${inv.totalSubmissions} dossiers</span>
                                <span>💰 ${formatCurrency(inv.totalAmount || 0)}</span>
                                <span>📅 ${inv.lastSubmission ? new Date(inv.lastSubmission).toLocaleDateString('fr-FR') : 'N/A'}</span>
                            </div>
                        </div>
                        <span class="investor-badge ${tier}">${tier === 'gold' ? '🏆 Or' : tier === 'silver' ? '🥈 Argent' : '🥉 Bronze'}</span>
                    </div>
                `).join('')}
            `;
        }

        openModal('investorDetailModal');
    }

    // Show individual investor details
    function showInvestorDetails(investorName) {
        const investor = investorProfiles.find(i => i.nom === investorName);
        if (!investor) return;

        const tierIcon = investor.tier === 'gold' ? '🏆' : investor.tier === 'silver' ? '🥈' : '🥉';
        const tierColor = investor.tier === 'gold' ? 'var(--gold)' : investor.tier === 'silver' ? '#c0c0c0' : '#cd7f32';

        const investorModal = document.getElementById('investorModalTitle');
        investorModal.innerHTML = `📋 Détails - ${investor.nom}`;

        document.getElementById('investorDetailContent').innerHTML = `
            <div class="sector-detail-card">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <div class="investor-avatar" style="width: 60px; height: 60px; font-size: 24px; background: ${tierColor}20; color: ${tierColor}">
                        ${investor.nom.charAt(0)}
                    </div>
                    <div>
                        <div style="font-size: 18px; font-weight: 700;">${investor.nom}</div>
                        <div><span class="investor-badge ${investor.tier}" style="font-size: 12px;">${tierIcon} ${investor.tier.toUpperCase()}</span></div>
                    </div>
                </div>
                <div class="sector-metrics">
                    <div class="sector-metric">
                        <div class="sector-metric-value">${investor.totalSubmissions}</div>
                        <div class="sector-metric-label">Dossiers soumis</div>
                    </div>
                    <div class="sector-metric">
                        <div class="sector-metric-value">${formatCurrency(investor.totalAmount || 0)}</div>
                        <div class="sector-metric-label">Montant total</div>
                    </div>
                    <div class="sector-metric">
                        <div class="sector-metric-value">${investor.lastSubmission ? new Date(investor.lastSubmission).toLocaleDateString('fr-FR') : 'N/A'}</div>
                        <div class="sector-metric-label">Dernière activité</div>
                    </div>
                </div>
                <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid var(--border);">
                    <div><strong>Secteur principal:</strong> ${investor.sector || 'Non spécifié'}</div>
                    <div><strong>Statut:</strong> ${investor.churnRisk ? '<span style="color: var(--red);">⚠️ Inactif - À contacter</span>' : '<span style="color: var(--green);">✅ Actif</span>'}</div>
                    ${investor.monthsInactive ? `<div><strong>Inactif depuis:</strong> ${investor.monthsInactive} mois</div>` : ''}
                </div>
            </div>
            <div class="sector-detail-card">
                <div style="font-weight: 700; margin-bottom: 12px;">📜 Historique des demandes</div>
                ${mecenatDemandes.filter(d => d.nomInvestisseur === investor.nom).map(d => `
                    <div class="investor-list-item" style="padding: 8px 0;">
                        <div class="investor-info">
                            <div class="investor-name">${d.numero}</div>
                            <div class="investor-stats">
                                <span>💰 ${formatCurrency(d.montant)}</span>
                                <span>📅 ${new Date(d.dateDepot).toLocaleDateString('fr-FR')}</span>
                                <span>📄 ${d.documents}</span>
                            </div>
                        </div>
                        <span class="badge ${d.statut === 'validated' ? 'green' : d.statut === 'pending' ? 'amber' : d.statut === 'rejected' ? 'red' : 'blue'}" style="font-size: 9px;">
                            ${d.statut === 'validated' ? 'Validé' : d.statut === 'pending' ? 'En attente' : d.statut === 'rejected' ? 'Rejeté' : 'En cours'}
                        </span>
                    </div>
                `).join('') || '<div style="text-align: center; padding: 20px; color: var(--text3);">Aucune demande trouvée</div>'}
            </div>
        `;
    }

    // Render Sector Heatmap (F7) with clickable sectors
    function renderSectorHeatmap() {
        const container = document.getElementById('sectorHeatmapContainer');
        if (!container) return;

        const maxPercentage = Math.max(...sectorData.map(s => s.percentage));
        const mostUnderfunded = sectorData.reduce((prev, curr) => (curr.gap < prev.gap) ? curr : prev, sectorData[0]);

        container.innerHTML = `
            <div class="sector-grid">
                ${sectorData.map(sector => {
                    const isUnderfunded = sector.gap < -5;
                    const isOverfunded = sector.gap > 5;
                    let barClass = 'normal';
                    if (isUnderfunded) barClass = 'underfunded';
                    if (isOverfunded) barClass = 'overfunded';
                    const barWidth = (sector.percentage / maxPercentage) * 100;

                    return `
                        <div class="sector-bar-item" onclick="showSectorDetails('${sector.name}')">
                            <div class="sector-bar-header">
                                <div class="sector-name">
                                    <span>${sector.icon}</span>
                                    ${sector.name}
                                </div>
                                <div class="sector-percent ${isUnderfunded ? 'underfunded' : isOverfunded ? 'overfunded' : ''}">
                                    ${sector.percentage}%
                                    <span style="font-size: 10px;">(objectif ${sector.target}%)</span>
                                </div>
                            </div>
                            <div class="sector-bar-container">
                                <div class="sector-bar ${barClass}" style="width: ${barWidth}%"></div>
                            </div>
                            <div class="sector-target">
                                💰 ${formatCurrency(sector.amount)} |
                                ${sector.gap >= 0 ? '✓ Objectif atteint' : `⚠️ Sous-financé de ${Math.abs(sector.gap)}%`}
                            </div>
                        </div>
                    `;
                }).join('')}
                <div class="sector-recommendation" onclick="showUnderfundedSectors()">
                    💡 RECOMMANDATION IA: <strong>${mostUnderfunded.name}</strong> est le secteur le plus sous-financé (${Math.abs(mostUnderfunded.gap)}% sous objectif).<br>
                    Campagne de communication recommandée pour ce secteur.
                </div>
            </div>
        `;
    }

    // Show sector details in modal
    function showSectorDetails(sectorName) {
        const sector = sectorData.find(s => s.name === sectorName);
        if (!sector) return;

        const modalTitle = document.getElementById('sectorModalTitle');
        const modalBody = document.getElementById('sectorDetailContent');

        modalTitle.innerHTML = `${sector.icon} ${sector.name} - Détails des investissements`;

        const investorsInSector = investorProfiles.filter(i => i.sector === sector.name);

        modalBody.innerHTML = `
            <div class="sector-detail-card">
                <div class="sector-metrics">
                    <div class="sector-metric">
                        <div class="sector-metric-value">${formatCurrency(sector.amount)}</div>
                        <div class="sector-metric-label">Investissement total</div>
                    </div>
                    <div class="sector-metric">
                        <div class="sector-metric-value">${sector.percentage}%</div>
                        <div class="sector-metric-label">Part du marché</div>
                    </div>
                    <div class="sector-metric">
                        <div class="sector-metric-value">${sector.gap >= 0 ? '+' : ''}${sector.gap}%</div>
                        <div class="sector-metric-label">Écart objectif</div>
                    </div>
                </div>
                <div style="margin-top: 16px;">
                    <div><strong>Objectif cible:</strong> ${sector.target}%</div>
                    <div><strong>Top investisseur:</strong> ${sector.topInvestor}</div>
                    <div><strong>Nombre d'investisseurs actifs:</strong> ${sector.investors}</div>
                </div>
            </div>
            <div class="sector-detail-card">
                <div style="font-weight: 700; margin-bottom: 12px;">🏢 Investisseurs dans ce secteur</div>
                ${investorsInSector.length > 0 ? investorsInSector.map(inv => `
                    <div class="investor-list-item" onclick="showInvestorDetails('${inv.nom}')">
                        <div class="investor-avatar" style="background: ${inv.tier === 'gold' ? 'rgba(201,168,76,0.2)' : inv.tier === 'silver' ? 'rgba(192,192,192,0.2)' : 'rgba(205,127,50,0.2)'}">
                            ${inv.nom.charAt(0)}
                        </div>
                        <div class="investor-info">
                            <div class="investor-name">${inv.nom}</div>
                            <div class="investor-stats">
                                <span>📄 ${inv.totalSubmissions} dossiers</span>
                                <span>💰 ${formatCurrency(inv.totalAmount || 0)}</span>
                            </div>
                        </div>
                        <span class="investor-badge ${inv.tier}">${inv.tier === 'gold' ? '🏆 Or' : inv.tier === 'silver' ? '🥈 Argent' : '🥉 Bronze'}</span>
                    </div>
                `).join('') : '<div style="text-align: center; padding: 20px; color: var(--text3);">Aucun investisseur actif dans ce secteur</div>'}
            </div>
            <div class="sector-detail-card">
                <div style="font-weight: 700; margin-bottom: 12px;">💡 Recommandation IA</div>
                <div style="padding: 12px; background: var(--purple-dim); border-radius: var(--radius-sm);">
                    ${sector.gap < 0 ?
                        `⚠️ Ce secteur est sous-financé de ${Math.abs(sector.gap)}%. Une campagne de communication ciblée pourrait attirer de nouveaux investisseurs.` :
                        `✓ Ce secteur a atteint son objectif. Maintenir la dynamique actuelle.`}
                </div>
            </div>
        `;

        openModal('sectorDetailModal');
    }

    // Show high risk dossiers in modal
    function showHighRiskDossiers() {
        const highRisk = mecenatDemandes.filter(d => d.riskLevel === 'high');
        const modalBody = document.getElementById('highRiskContent');

        if (highRisk.length === 0) {
            modalBody.innerHTML = '<div style="text-align: center; padding: 40px;">✅ Aucun dossier haut risque détecté</div>';
        } else {
            modalBody.innerHTML = `
                <div style="margin-bottom: 16px; background: var(--red-dim); padding: 12px; border-radius: var(--radius-sm);">
                    <strong>⚠️ ${highRisk.length} dossier(s) nécessitent une attention immédiate</strong>
                </div>
                ${highRisk.map(d => `
                    <div class="investor-list-item">
                        <div class="investor-avatar" style="background: rgba(248,113,113,0.15); color: var(--red);">!</div>
                        <div class="investor-info">
                            <div class="investor-name">${d.nomInvestisseur}</div>
                            <div class="investor-stats">
                                <span>📄 ${d.numero}</span>
                                <span>💰 ${formatCurrency(d.montant)}</span>
                                <span>📅 ${new Date(d.dateDepot).toLocaleDateString('fr-FR')}</span>
                            </div>
                        </div>
                        <span class="badge red">Risque élevé</span>
                    </div>
                `).join('')}
                <div style="margin-top: 16px; padding: 12px; background: var(--bg3); border-radius: var(--radius-sm);">
                    <strong>📋 Critères de risque:</strong><br>
                    • Montant anormalement élevé (> 2M TND)<br>
                    • Documents incomplets ou non conformes<br>
                    • Historique de rejet ou anomalie détectée
                </div>
            `;
        }

        openModal('highRiskModal');
    }

    // Show churn risk investors in modal
    function showChurnAlerts() {
        const churnInvestors = investorProfiles.filter(i => i.churnRisk === true);
        const modalBody = document.getElementById('churnRiskContent');

        if (churnInvestors.length === 0) {
            modalBody.innerHTML = '<div style="text-align: center; padding: 40px;">✅ Aucun investisseur à risque de départ</div>';
        } else {
            modalBody.innerHTML = `
                <div style="margin-bottom: 16px; background: var(--amber-dim); padding: 12px; border-radius: var(--radius-sm);">
                    <strong>⚠️ ${churnInvestors.length} investisseur(s) inactif(s) depuis plus de 12 mois</strong>
                </div>
                ${churnInvestors.map(i => `
                    <div class="investor-list-item" onclick="showInvestorDetails('${i.nom}')">
                        <div class="investor-avatar" style="background: rgba(251,191,36,0.15); color: var(--amber);">⚠️</div>
                        <div class="investor-info">
                            <div class="investor-name">${i.nom}</div>
                            <div class="investor-stats">
                                <span>📄 ${i.totalSubmissions} dossiers</span>
                                <span>💰 ${formatCurrency(i.totalAmount || 0)}</span>
                                <span>📅 Inactif depuis ${i.monthsInactive} mois</span>
                            </div>
                        </div>
                        <span class="investor-badge churn">Risque départ</span>
                    </div>
                `).join('')}
                <div style="margin-top: 16px; padding: 12px; background: var(--bg3); border-radius: var(--radius-sm);">
                    <strong>📋 Action recommandée:</strong><br>
                    • Envoyer un email de relance personnalisé<br>
                    • Proposer un entretien de réengagement<br>
                    • Mettre en avant les nouveaux avantages
                </div>
            `;
        }

        openModal('churnRiskModal');
    }

    // Show underfunded sectors in modal
    function showUnderfundedSectors() {
        const underfunded = sectorData.filter(s => s.gap < -5);
        const modalBody = document.getElementById('underfundedContent');

        if (underfunded.length === 0) {
            modalBody.innerHTML = '<div style="text-align: center; padding: 40px;">✅ Tous les secteurs ont atteint leurs objectifs</div>';
        } else {
            modalBody.innerHTML = `
                <div style="margin-bottom: 16px; background: var(--red-dim); padding: 12px; border-radius: var(--radius-sm);">
                    <strong>📊 ${underfunded.length} secteur(s) sous-financé(s)</strong>
                </div>
                ${underfunded.map(s => `
                    <div class="sector-bar-item" style="cursor: pointer;" onclick="showSectorDetails('${s.name}')">
                        <div class="sector-bar-header">
                            <div class="sector-name">${s.icon} ${s.name}</div>
                            <div class="sector-percent underfunded">${s.percentage}% (objectif ${s.target}%)</div>
                        </div>
                        <div class="sector-bar-container">
                            <div class="sector-bar underfunded" style="width: ${(s.percentage / Math.max(...sectorData.map(sd => sd.percentage))) * 100}%"></div>
                        </div>
                        <div class="sector-target">
                            💰 ${formatCurrency(s.amount)} | ⚠️ Sous-financé de ${Math.abs(s.gap)}%
                        </div>
                    </div>
                `).join('')}
                <div style="margin-top: 16px; padding: 12px; background: linear-gradient(135deg, var(--purple-dim), rgba(167,139,250,0.05)); border-radius: var(--radius-sm);">
                    <strong>💡 Recommandation IA:</strong><br>
                    ${underfunded[0] ? `Prioriser le secteur <strong>${underfunded[0].name}</strong> pour une campagne de communication ciblée.` : ''}
                </div>
            `;
        }

        openModal('underfundedModal');
    }

    // Render Recent Activity
    function renderRecentActivity() {
        const container = document.getElementById('recentActivityList');
        if (!container) return;

        container.innerHTML = recentActivities.map(activity => `
            <div class="activity-item">
                <div class="activity-icon ${activity.type}"></div>
                <div class="activity-content">
                    <div class="activity-action">${activity.action}</div>
                    <div class="activity-entity">${activity.entity}</div>
                    <div class="activity-detail">${activity.detail}</div>
                </div>
                <div class="activity-time">${formatRelativeTime(activity.time)}</div>
            </div>
        `).join('');
    }

    // Update Stats Counters
    function updateStatsCounters() {
        const mecenatCount = mecenatDemandes.length;
        const mecenatPending = mecenatDemandes.filter(d => d.statut === 'pending').length;
        const agrementCount = agrementDemandes.length;
        const agrementPending = agrementDemandes.filter(d => d.statut === 'pending').length;
        const certificationCount = certificationDemandes.length;
        const certificationPending = certificationDemandes.filter(d => d.statut === 'pending').length;

        document.getElementById('mecenatCount') && (document.getElementById('mecenatCount').innerText = mecenatCount);
        document.getElementById('mecenatPending') && (document.getElementById('mecenatPending').innerText = mecenatPending);
        document.getElementById('agrementCount') && (document.getElementById('agrementCount').innerText = agrementCount);
        document.getElementById('agrementPending') && (document.getElementById('agrementPending').innerText = agrementPending);
        document.getElementById('certificationCount') && (document.getElementById('certificationCount').innerText = certificationCount);
        document.getElementById('certificationPending') && (document.getElementById('certificationPending').innerText = certificationPending);
    }

    // Export functions
    function exportInvestorList() {
        alert('📥 Export des investisseurs démarré (CSV)');
    }

    function prioritizeHighRisk() {
        alert('📌 Dossiers haut risque priorisés. Les agents seront notifiés.');
        closeModal('highRiskModal');
    }

    function sendChurnEmails() {
        alert('📧 Emails de relance envoyés aux investisseurs inactifs.');
        closeModal('churnRiskModal');
    }

    function generateSectorReport() {
        alert('📄 Rapport des secteurs sous-financés généré.');
        closeModal('underfundedModal');
    }

    function showSectorRecommendation() {
        const sectorName = document.getElementById('sectorModalTitle').innerText.split(' - ')[0].replace(/[^a-zA-ZÀ-ÿ\s]/g, '').trim();
        const sector = sectorData.find(s => sectorName.includes(s.name));
        if (sector) {
            alert(`💡 RECOMMANDATION IA pour ${sector.name}:\n\n${sector.gap < 0 ?
                `Campagne de communication ciblée recommandée. Objectif: attirer ${Math.ceil((sector.target - sector.percentage) / 100 * sectorData.reduce((sum, s) => sum + s.amount, 0) / 1000000)}M TND supplémentaires.` :
                `Maintenir la dynamique actuelle. Explorer des synergies avec d'autres secteurs.`}`);
        }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        renderAIInsights();
        renderEngagementStats();
        renderSectorHeatmap();
        renderRecentActivity();
        updateStatsCounters();
    });

    // Make functions global
    window.showHighRiskDossiers = showHighRiskDossiers;
    window.showChurnAlerts = showChurnAlerts;
    window.showUnderfundedSectors = showUnderfundedSectors;
    window.showSectorDetails = showSectorDetails;
    window.showInvestorsByTier = showInvestorsByTier;
    window.showInvestorDetails = showInvestorDetails;
    window.exportInvestorList = exportInvestorList;
    window.prioritizeHighRisk = prioritizeHighRisk;
    window.sendChurnEmails = sendChurnEmails;
    window.generateSectorReport = generateSectorReport;
    window.showSectorRecommendation = showSectorRecommendation;
    window.closeModal = closeModal;
    window.openModal = openModal;
    </script>
@endsection
