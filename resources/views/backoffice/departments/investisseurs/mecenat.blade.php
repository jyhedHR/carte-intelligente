@extends('shared.layouts.backoffice')

@section('page-title', 'Attestation Mécénat - Unité d\'Encadrement des Investisseurs')

@vite(['resources/assets/css/backend.css', 'resources/assets/css/investisseurs.css'])

@section('content')
<div class="page active">
    <!-- Header -->
    <div class="livre-header">
        <div>
            <div class="livre-title">🎭 Attestation de caractère administratif - Mécénat culturel</div>
            <div class="livre-subtitle">Gestion des demandes d'attestation pour les investisseurs culturels</div>
        </div>
        <button class="btn btn-gold" onclick="openCreateModal()">
            <span class="livre-action-icon icon-add-btn"></span>
            Nouvelle demande
        </button>
    </div>

    <!-- WORKFLOW GUIDE -->
    <div class="workflow-guide">
        <div class="workflow-guide-header">
            <span class="guide-icon"></span>
            <span>📋 Guide de traitement - Rôle de l'agent (Mécénat)</span>
            <span class="guide-badge">7 étapes</span>
        </div>
        <div class="workflow-guide-steps">
            <div class="guide-step"><div class="step-number">1</div><div class="step-content"><div class="step-title">📥 Réception</div><div class="step-desc">Création dossier avec ID unique</div></div></div>
            <div class="guide-arrow">→</div>
            <div class="guide-step"><div class="step-number">2</div><div class="step-content"><div class="step-title">🔍 Vérification docs</div><div class="step-desc">Vérifier checklist documents</div></div></div>
            <div class="guide-arrow">→</div>
            <div class="guide-step"><div class="step-number">3</div><div class="step-content"><div class="step-title">📎 Demande complément</div><div class="step-desc">Si documents manquants</div></div></div>
            <div class="guide-arrow">→</div>
            <div class="guide-step"><div class="step-number">4</div><div class="step-content"><div class="step-title">✅ Transmission commission</div><div class="step-desc">Envoi à la commission</div></div></div>
            <div class="guide-arrow">→</div>
            <div class="guide-step"><div class="step-number">5</div><div class="step-content"><div class="step-title">✍️ Avis commission</div><div class="step-desc">Accepté/rejeté/complément</div></div></div>
            <div class="guide-arrow">→</div>
            <div class="guide-step"><div class="step-number">6</div><div class="step-content"><div class="step-title">📄 Attestation générée</div><div class="step-desc">Document officiel créé</div></div></div>
            <div class="guide-arrow">→</div>
            <div class="guide-step"><div class="step-number">7</div><div class="step-content"><div class="step-title">🔔 Notification</div><div class="step-desc">Investisseur informé</div></div></div>
        </div>
    </div>

    <!-- F9: AI INSIGHT PANEL -->
    <div class="ai-insight-panel">
        <div class="ai-insight-header">
            <div class="ai-insight-title"><span class="ai-icon"></span><span>🧠 AI Insight Panel - Mécénat</span><span class="ai-badge active">Analyse en temps réel</span></div>
            <div class="ai-insight-time">Dernière mise à jour: <span id="aiLastUpdate">à l'instant</span></div>
        </div>
        <div class="ai-insight-grid" id="aiInsightGrid"></div>
    </div>

    <!-- F6: INVESTOR ENGAGEMENT SCORE -->
    <div class="health-score-summary">
        <div class="health-header">
            <div class="health-header-info">
                <div class="activity-icon user-health-icon"></div>
                <div class="health-header-text">
                    <h3>🏆 Score d'engagement investisseurs (RFM)</h3>
                    <p>Classification Or/Argent/Bronze basée sur l'historique des participations</p>
                </div>
            </div>
        </div>
        <div id="engagementStatsContainer"></div>
    </div>


    <!-- Quick Actions -->
    <div class="quick-actions">
        <button class="quick-action-btn" onclick="quickActionBulkApprove()"><span class="quick-action-icon icon-select"></span>✅ Approuver à faible risque</button>
        <button class="quick-action-btn" onclick="quickActionExport()"><span class="quick-action-icon icon-export-import"></span>📎 Exporter les données</button>
        <button class="quick-action-btn" onclick="showWorkflowHelp()"><span class="quick-action-icon icon-question"></span>❓ Aide workflow</button>
    </div>

    <!-- Stats Cards -->
    <div class="investisseur-stats">
        <div class="investisseur-stat" onclick="filterByStatus('all')"><div class="investisseur-stat-value" id="totalCount">0</div><div class="investisseur-stat-label">📋 Total demandes</div></div>
        <div class="investisseur-stat" onclick="filterByStatus('pending')"><div class="investisseur-stat-value" id="pendingCount">0</div><div class="investisseur-stat-label">⏳ En attente</div></div>
        <div class="investisseur-stat" onclick="filterByStatus('validated')"><div class="investisseur-stat-value" id="validatedCount">0</div><div class="investisseur-stat-label">✅ Validées</div></div>
        <div class="investisseur-stat" onclick="filterByStatus('rejected')"><div class="investisseur-stat-value" id="rejectedCount">0</div><div class="investisseur-stat-label">❌ Rejetées</div></div>
    </div>

    <!-- Filters -->
    <div class="investisseur-filter">
        <input type="text" id="searchInput" placeholder="🔍 Rechercher par investisseur, n° dossier..." onkeyup="renderTable()">
        <select id="statusFilter" onchange="renderTable()">
            <option value="all">📊 Tous les statuts</option>
            <option value="pending">⏳ En attente</option>
            <option value="progress">🔄 En cours</option>
            <option value="validated">✅ Validé</option>
            <option value="rejected">❌ Rejeté</option>
        </select>
        <select id="riskFilter" onchange="renderTable()">
            <option value="all">🎯 Tous les risques</option>
            <option value="low">🟢 Risque faible</option>
            <option value="medium">🟡 Risque modéré</option>
            <option value="high">🔴 Risque élevé</option>
        </select>
        <button class="btn btn-outline" onclick="resetFilters()">🔄 Réinitialiser</button>
    </div>

    <!-- Table -->
    <div class="panel">
        <div class="panel-head"><div class="panel-title">📋 Liste des demandes d'attestation mécénat</div><div class="panel-sub">Dernière mise à jour: <span id="lastUpdate"></span></div></div>
        <div class="panel-body no-pad">
            <div style="overflow-x:auto">
                <table class="investisseur-table">
                    <thead>
                        <tr>
                            <th class="checkbox-col"><input type="checkbox" id="selectAll" class="bulk-checkbox" onclick="toggleSelectAll()"></th>
                            <th>📄 N° Dossier</th>
                            <th>🏢 Investisseur</th>
                            <th>🎨 Secteur</th>
                            <th>💰 Montant</th>
                            <th>⚠️ Risque (F5)</th>
                            <th>🚨 Anomalie (F8)</th>
                            <th>📅 Date dépôt</th>
                            <th>🎯 Statut</th>
                            <th>⚙️ Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Activity Timeline -->
    <div id="activityTimeline"></div>

    <!-- Bulk Action Bar -->
    <div id="bulkBar" class="bulk-bar"><span class="bulk-count" id="selectedCount">0</span><span>dossiers sélectionnés</span><div class="bulk-actions"><button class="btn btn-gold btn-sm" onclick="bulkApprove()">✅ Approuver</button><button class="btn btn-outline btn-sm" onclick="clearSelection()">❌ Annuler</button></div></div>

    <!-- Modal Création -->
    <div id="formModal" class="investisseur-modal-overlay">
        <div class="investisseur-modal">
            <div class="investisseur-modal-header"><h3 id="modalTitle">📝 Nouvelle demande</h3><button class="investisseur-modal-close" onclick="closeModal('formModal')">✕</button></div>
            <div class="investisseur-modal-body">
                <input type="hidden" id="editId">
                <div class="form-row"><label>🏢 Nom de l'investisseur <span class="required">*</span></label><input type="text" id="nomInvestisseur" placeholder="Ex: Fondation Tunisienne pour la Culture"></div>
                <div class="form-row"><label>📋 Matricule Fiscal <span class="required">*</span></label><input type="text" id="matricule" placeholder="0000000/A/M/000"></div>
                <div class="form-row"><label>🎨 Secteur culturel <span class="required">*</span></label>
                    <select id="secteur">
                        <option value="Livre">📚 Livre</option>
                        <option value="Cinéma">🎬 Cinéma</option>
                        <option value="Théâtre">🎭 Théâtre</option>
                        <option value="Musique">🎵 Musique</option>
                        <option value="Arts Plastiques">🎨 Arts Plastiques</option>
                        <option value="Patrimoine">🏛️ Patrimoine</option>
                    </select>
                </div>
                <div class="form-row"><label>💰 Montant demandé (TND) <span class="required">*</span></label><input type="number" id="montant" placeholder="Ex: 150000"></div>
                <div class="form-row"><label>📝 Description du projet</label><textarea id="description" rows="3" placeholder="Description détaillée..."></textarea></div>
            </div>
            <div class="investisseur-modal-footer"><button class="btn btn-outline" onclick="closeModal('formModal')">Annuler</button><button class="btn btn-gold" onclick="saveDemande()">💾 Enregistrer</button></div>
        </div>
    </div>

    <!-- Modal Détail -->
    <div id="detailModal" class="investisseur-modal-overlay">
        <div class="investisseur-modal">
            <div class="investisseur-modal-header"><h3>📋 Détails de la demande</h3><button class="investisseur-modal-close" onclick="closeModal('detailModal')">✕</button></div>
            <div class="investisseur-modal-body" id="detailContent"></div>
            <div class="investisseur-modal-footer"><button class="btn btn-outline" onclick="closeModal('detailModal')">Fermer</button></div>
        </div>
    </div>
</div>

<script>
// ============================================
// MECENAT DEMO DATA
// ============================================
let mecenatDemandes = [
    { id: 1, numero: 'MEC-20260001', nomInvestisseur: 'Fondation Tunisienne pour la Culture', matricule: '1234567/A/M/001', secteur: 'Livre', montant: 150000, description: 'Soutien à l\'édition de livres tunisiens', dateDepot: '2026-04-10', statut: 'pending', docCompleteness: 95, riskLevel: 'low', workflowStep: '📥 Étape 1/7 - Nouvelle demande reçue', anomalyReason: null },
    { id: 2, numero: 'MEC-20260002', nomInvestisseur: 'Groupe Chimique Tunisien', matricule: '2345678/B/M/002', secteur: 'Arts Plastiques', montant: 450000, description: 'Projet de rénovation de galerie d\'art', dateDepot: '2026-04-05', statut: 'progress', docCompleteness: 70, riskLevel: 'medium', workflowStep: '🔍 Étape 2/7 - Vérification documents', anomalyReason: null },
    { id: 3, numero: 'MEC-20260003', nomInvestisseur: 'Banque Internationale Arabe', matricule: '3456789/C/M/003', secteur: 'Musique', montant: 2800000, description: 'Festival de musique internationale', dateDepot: '2026-04-01', statut: 'pending', docCompleteness: 45, riskLevel: 'high', workflowStep: '⚠️ Anomalie détectée', anomalyReason: '⚠️ Montant anormalement élevé (>2M TND)' },
    { id: 4, numero: 'MEC-20260004', nomInvestisseur: 'Carthage Cement', matricule: '4567890/D/M/004', secteur: 'Patrimoine', montant: 750000, description: 'Restauration de monuments historiques', dateDepot: '2026-03-28', statut: 'validated', docCompleteness: 100, riskLevel: 'low', workflowStep: '✅ Attestation générée', anomalyReason: null },
    { id: 5, numero: 'MEC-20260005', nomInvestisseur: 'Tunisie Telecom', matricule: '5678901/E/M/005', secteur: 'Cinéma', montant: 1200000, description: 'Soutien au cinéma tunisien', dateDepot: '2026-03-25', statut: 'progress', docCompleteness: 85, riskLevel: 'medium', workflowStep: '📎 En attente commission', anomalyReason: null },
    { id: 6, numero: 'MEC-20260006', nomInvestisseur: 'SFBT', matricule: '6789012/F/M/006', secteur: 'Théâtre', montant: 350000, description: 'Soutien aux troupes théâtrales', dateDepot: '2026-03-20', statut: 'rejected', docCompleteness: 30, riskLevel: 'high', anomalyReason: '⚠️ Documents incomplets (30%)', motifRejet: 'Dossier incomplet - Documents manquants', workflowStep: '❌ Dossier rejeté' }
];

let nextId = 7;
let selectedItems = [];

// Engagement data
const investorTiersData = [
    { tier: 'gold', count: 3, label: 'Investisseurs Or', icon: '🏆', color: 'var(--gold)', description: 'Plus de 8 dossiers' },
    { tier: 'silver', count: 2, label: 'Investisseurs Argent', icon: '🥈', color: '#c0c0c0', description: '4 à 7 dossiers' },
    { tier: 'bronze', count: 3, label: 'Investisseurs Bronze', icon: '🥉', color: '#cd7f32', description: 'Moins de 4 dossiers' },
    { tier: 'churn', count: 2, label: 'Risque de départ', icon: '⚠️', color: 'var(--red)', description: 'Inactifs +12 mois' }
];

// Sector data
const sectorData = [
    { name: 'Livre', icon: '📚', amount: 850000, percentage: 8, target: 20, gap: -12 },
    { name: 'Cinéma', icon: '🎬', amount: 1200000, percentage: 12, target: 20, gap: -8 },
    { name: 'Théâtre', icon: '🎭', amount: 650000, percentage: 6, target: 15, gap: -9 },
    { name: 'Musique', icon: '🎵', amount: 3100000, percentage: 30, target: 20, gap: 10 },
    { name: 'Arts Plastiques', icon: '🎨', amount: 950000, percentage: 9, target: 15, gap: -6 },
    { name: 'Patrimoine', icon: '🏛️', amount: 1750000, percentage: 17, target: 10, gap: 7 }
];

// ============================================
// HELPER FUNCTIONS
// ============================================
function formatDate(dateString) {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('fr-FR');
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('fr-TN', { style: 'currency', currency: 'TND', maximumFractionDigits: 0 }).format(amount);
}

function formatRelativeTime(dateStr) {
    const date = new Date(dateStr);
    const now = new Date();
    const diffDays = Math.floor((now - date) / (1000 * 60 * 60 * 24));
    if (diffDays === 0) return "Aujourd'hui";
    if (diffDays === 1) return "Hier";
    if (diffDays < 7) return `Il y a ${diffDays} jours`;
    return `Il y a ${diffDays} jours`;
}

function getRiskBadge(riskLevel) {
    if (riskLevel === 'low') return '<span class="risk-badge low">🟢 Faible risque</span>';
    if (riskLevel === 'medium') return '<span class="risk-badge medium">🟡 Risque modéré</span>';
    return '<span class="risk-badge high">🔴 Risque élevé</span>';
}

function getAnomalyFlag(demande) {
    if (demande.anomalyReason) {
        return `<span class="anomaly-flag">⚠️ ${demande.anomalyReason.substring(0, 30)}</span>`;
    }
    return '<span style="color: var(--text3);">-</span>';
}

function getStatusClass(statut) {
    const classes = { pending: 'investisseur-status-pending', progress: 'investisseur-status-progress', validated: 'investisseur-status-validated', rejected: 'investisseur-status-rejected' };
    return classes[statut] || 'investisseur-status-pending';
}

function getStatusLabel(statut) {
    const labels = { pending: '⏳ En attente', progress: '🔄 En cours', validated: '✅ Validé', rejected: '❌ Rejeté' };
    return labels[statut] || statut;
}

function showToast(message, type = 'success') {
    let toast = document.getElementById('investisseur-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'investisseur-toast';
        toast.style.cssText = `position:fixed; bottom:30px; left:50%; transform:translateX(-50%); padding:12px 24px; border-radius:8px; color:white; font-size:13px; font-weight:500; z-index:1100; background:${type === 'success' ? '#4ade80' : type === 'warning' ? '#fbbf24' : '#f87171'}; animation:fadeInUp 0.3s ease;`;
        document.body.appendChild(toast);
        const style = document.createElement('style');
        style.textContent = `@keyframes fadeInUp{from{opacity:0; transform:translate(-50%,20px);}to{opacity:1; transform:translate(-50%,0);}}`;
        document.head.appendChild(style);
    }
    toast.textContent = message;
    toast.style.display = 'block';
    setTimeout(() => toast.style.display = 'none', 3000);
}

function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }

// ============================================
// RENDER FUNCTIONS
// ============================================

function renderAIInsights() {
    const container = document.getElementById('aiInsightGrid');
    if (!container) return;
    const pendingCount = mecenatDemandes.filter(d => d.statut === 'pending').length;
    const highRiskCount = mecenatDemandes.filter(d => d.riskLevel === 'high').length;
    const anomalyCount = mecenatDemandes.filter(d => d.anomalyReason).length;
    const validatedCount = mecenatDemandes.filter(d => d.statut === 'validated').length;
    const successRate = mecenatDemandes.length > 0 ? Math.round((validatedCount / mecenatDemandes.length) * 100) : 0;

    container.innerHTML = `
        <div class="insight-card" onclick="filterByStatus('pending')">
            <div class="insight-icon risk-icon"></div>
            <div class="insight-content">
                <div class="insight-value">${pendingCount}</div>
                <div class="insight-label">Demandes en attente</div>
                <div class="insight-action">À traiter →</div>
            </div>
        </div>
        <div class="insight-card warning" onclick="showHighRiskDossiers()">
            <div class="insight-icon engagement-icon"></div>
            <div class="insight-content">
                <div class="insight-value">${highRiskCount}</div>
                <div class="insight-label">Dossiers haut risque</div>
                <div class="insight-action">⚠️ Prioritaire →</div>
            </div>
        </div>
        <div class="insight-card" onclick="showAnomalies()">
            <div class="insight-icon anomaly-icon"></div>
            <div class="insight-content">
                <div class="insight-value">${anomalyCount}</div>
                <div class="insight-label">Anomalies détectées</div>
                <div class="insight-action">À examiner →</div>
            </div>
        </div>
        <div class="insight-card" onclick="quickActionExport()">
            <div class="insight-icon sector-icon"></div>
            <div class="insight-content">
                <div class="insight-value">${successRate}%</div>
                <div class="insight-label">Taux validation</div>
                <div class="insight-action">Performance →</div>
            </div>
        </div>
    `;
    const timeElement = document.getElementById('aiLastUpdate');
    if (timeElement) timeElement.innerText = new Date().toLocaleTimeString();
}

function renderEngagementStats() {
    const container = document.getElementById('engagementStatsContainer');
    if (!container) return;
    container.innerHTML = `
        <div class="engagement-tiers">
            ${investorTiersData.map(t => `
                <div class="engagement-tier-card ${t.tier}" onclick="filterByTier('${t.tier}')">
                    <div class="tier-icon">${t.icon}</div>
                    <div class="tier-value" style="color: ${t.color};">${t.count}</div>
                    <div class="tier-label">${t.label}</div>
                    <div class="tier-desc">${t.description}</div>
                </div>
            `).join('')}
        </div>
    `;
}

function renderSectorHeatmap() {
    const container = document.getElementById('sectorHeatmapContainer');
    if (!container) return;
    const maxPercentage = Math.max(...sectorData.map(s => s.percentage));
    const mostUnderfunded = sectorData.filter(s => s.gap < 0).reduce((prev, curr) => (curr.gap < prev.gap) ? curr : prev, sectorData[0]);

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
                            <div class="sector-name"><span>${sector.icon}</span> ${sector.name}</div>
                            <div class="sector-percent ${isUnderfunded ? 'underfunded' : isOverfunded ? 'overfunded' : ''}">
                                ${sector.percentage}% (objectif ${sector.target}%)
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
                💡 RECOMMANDATION IA: <strong>${mostUnderfunded.name}</strong> est le secteur le plus sous-financé (${Math.abs(mostUnderfunded.gap)}% sous objectif). Campagne recommandée.
            </div>
        </div>
    `;
}

function renderTable() {
    const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const statusFilter = document.getElementById('statusFilter')?.value || 'all';
    const riskFilter = document.getElementById('riskFilter')?.value || 'all';

    let filtered = mecenatDemandes.filter(d =>
        (d.nomInvestisseur.toLowerCase().includes(searchTerm) || d.numero.toLowerCase().includes(searchTerm)) &&
        (statusFilter === 'all' || d.statut === statusFilter) &&
        (riskFilter === 'all' || d.riskLevel === riskFilter)
    );

    const tbody = document.getElementById('tableBody');
    if (!tbody) return;

    tbody.innerHTML = filtered.map(d => `
        <tr onclick="showDetail(${d.id})" style="cursor:pointer;">
            <td class="checkbox-col" onclick="event.stopPropagation()">
                <input type="checkbox" class="row-select bulk-checkbox" data-id="${d.id}" onchange="toggleSelect(${d.id})">
            </td>
            <td><strong>${d.numero}</strong><br><small style="font-size:9px; color:var(--text3);">${d.workflowStep}</small></td>
            <td>${d.nomInvestisseur}${d.anomalyReason ? ' ⚠️' : ''}</td>
            <td>${d.secteur}</td>
            <td><strong>${formatCurrency(d.montant)}</strong></td>
            <td>${getRiskBadge(d.riskLevel)}</td>
            <td>${getAnomalyFlag(d)}</td>
            <td>${formatDate(d.dateDepot)}</td>
            <td><span class="investisseur-status ${getStatusClass(d.statut)}">${getStatusLabel(d.statut)}</span></td>
            <td onclick="event.stopPropagation()">
                <button class="btn btn-outline btn-sm" onclick="editDemande(${d.id})" style="margin-right:4px;">✏️</button>
                <button class="btn btn-outline btn-sm" onclick="deleteDemande(${d.id})" style="margin-right:4px;">🗑️</button>
                ${d.statut === 'pending' ? `<button class="btn btn-gold btn-sm" onclick="validateDemande(${d.id})">✅ Valider</button>` : ''}
            </td>
        </table>
    `).join('');

    document.getElementById('totalCount').innerText = mecenatDemandes.length;
    document.getElementById('pendingCount').innerText = mecenatDemandes.filter(d => d.statut === 'pending').length;
    document.getElementById('validatedCount').innerText = mecenatDemandes.filter(d => d.statut === 'validated').length;
    document.getElementById('rejectedCount').innerText = mecenatDemandes.filter(d => d.statut === 'rejected').length;
    document.getElementById('lastUpdate').innerText = new Date().toLocaleTimeString();

    renderActivityTimeline();
}

function renderActivityTimeline() {
    const container = document.getElementById('activityTimeline');
    if (!container) return;

    const activities = [];
    mecenatDemandes.forEach(d => {
        activities.push({ type: 'created', action: '📥 Nouvelle demande', detail: `${d.nomInvestisseur} - ${formatCurrency(d.montant)}`, time: d.dateDepot });
        if (d.anomalyReason) {
            activities.push({ type: 'anomaly', action: '⚠️ Anomalie détectée', detail: `${d.numero}: ${d.anomalyReason}`, time: d.dateDepot });
        }
        if (d.statut === 'validated') {
            activities.push({ type: 'approved', action: '✅ Attestation délivrée', detail: `${d.nomInvestisseur}`, time: d.dateDepot });
        }
        if (d.statut === 'rejected') {
            activities.push({ type: 'rejected', action: '❌ Demande rejetée', detail: `${d.nomInvestisseur}`, time: d.dateDepot });
        }
    });
    activities.sort((a, b) => new Date(b.time) - new Date(a.time));

    container.innerHTML = `
        <div class="activity-timeline">
            <div class="timeline-header">
                <div class="timeline-title">📋 Activité récente</div>
                <div class="timeline-filter">
                    <button class="timeline-filter-btn active" onclick="filterTimeline('all')">Tous</button>
                    <button class="timeline-filter-btn" onclick="filterTimeline('created')">📥 Nouveaux</button>
                    <button class="timeline-filter-btn" onclick="filterTimeline('anomaly')">⚠️ Anomalies</button>
                    <button class="timeline-filter-btn" onclick="filterTimeline('approved')">✅ Validés</button>
                </div>
            </div>
            <div class="timeline-items">
                ${activities.slice(0, 10).map(a => `
                    <div class="timeline-item" data-type="${a.type}">
                        <div class="timeline-content">
                            <div class="timeline-action">${a.action}</div>
                            <div class="timeline-detail">${a.detail}</div>
                        </div>
                        <div class="timeline-time">${formatRelativeTime(a.time)}</div>
                    </div>
                `).join('')}
            </div>
        </div>
    `;
}

function filterTimeline(type) {
    const items = document.querySelectorAll('.timeline-item');
    document.querySelectorAll('.timeline-filter-btn').forEach(btn => btn.classList.remove('active'));
    if (event && event.target) event.target.classList.add('active');
    items.forEach(item => {
        if (type === 'all' || item.dataset.type === type) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

// ============================================
// CRUD FUNCTIONS
// ============================================
function openCreateModal() {
    document.getElementById('modalTitle').innerText = '📝 Nouvelle demande';
    document.getElementById('editId').value = '';
    ['nomInvestisseur', 'matricule', 'secteur', 'montant', 'description'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    openModal('formModal');
}

function editDemande(id) {
    const d = mecenatDemandes.find(x => x.id === id);
    if (!d) return;
    document.getElementById('modalTitle').innerText = '✏️ Modifier la demande';
    document.getElementById('editId').value = d.id;
    document.getElementById('nomInvestisseur').value = d.nomInvestisseur;
    document.getElementById('matricule').value = d.matricule;
    document.getElementById('secteur').value = d.secteur;
    document.getElementById('montant').value = d.montant;
    document.getElementById('description').value = d.description || '';
    openModal('formModal');
}

function saveDemande() {
    const id = document.getElementById('editId').value;
    const today = new Date().toISOString().split('T')[0];
    const montant = parseInt(document.getElementById('montant').value) || 0;

    let riskLevel = 'low';
    let anomalyReason = null;
    if (montant > 2000000) {
        riskLevel = 'high';
        anomalyReason = '⚠️ Montant anormalement élevé (>2M TND)';
    } else if (montant > 1000000) {
        riskLevel = 'medium';
    }

    const demandeData = {
        nomInvestisseur: document.getElementById('nomInvestisseur').value,
        matricule: document.getElementById('matricule').value,
        secteur: document.getElementById('secteur').value,
        montant: montant,
        description: document.getElementById('description').value,
        dateDepot: today,
        statut: 'pending',
        docCompleteness: 80,
        riskLevel: riskLevel,
        anomalyReason: anomalyReason,
        workflowStep: '📥 Étape 1/7 - Nouvelle demande reçue'
    };

    if (!demandeData.nomInvestisseur || !demandeData.matricule || !demandeData.montant) {
        showToast('Veuillez remplir tous les champs obligatoires', 'error');
        return;
    }

    if (id) {
        const idx = mecenatDemandes.findIndex(d => d.id == id);
        if (idx !== -1) {
            mecenatDemandes[idx] = { ...mecenatDemandes[idx], ...demandeData };
            showToast('Demande modifiée', 'success');
        }
    } else {
        const newId = nextId++;
        const newNumero = `MEC-2026${String(newId).padStart(4, '0')}`;
        mecenatDemandes.push({ id: newId, numero: newNumero, ...demandeData });
        showToast(`✅ Demande ${newNumero} créée`, 'success');
    }

    closeModal('formModal');
    renderTable();
    renderAIInsights();
}

function deleteDemande(id) {
    if (confirm('Supprimer cette demande ?')) {
        mecenatDemandes = mecenatDemandes.filter(d => d.id !== id);
        renderTable();
        renderAIInsights();
        showToast('Demande supprimée', 'success');
    }
}

function validateDemande(id) {
    const d = mecenatDemandes.find(x => x.id === id);
    if (d && d.statut === 'pending') {
        d.statut = 'validated';
        d.workflowStep = '✅ Attestation générée';
        renderTable();
        renderAIInsights();
        showToast(`✅ Demande ${d.numero} validée`, 'success');
    }
}

function showDetail(id) {
    const d = mecenatDemandes.find(x => x.id === id);
    if (!d) return;

    document.getElementById('detailContent').innerHTML = `
        <div class="detail-section">
            <div class="detail-header">
                <div class="detail-title">📋 Informations</div>
                <div class="detail-badge ${getStatusClass(d.statut)}">${getStatusLabel(d.statut)}</div>
            </div>
            <div class="detail-grid">
                <div class="detail-item"><label>N° Dossier:</label><span><strong>${d.numero}</strong></span></div>
                <div class="detail-item"><label>Date dépôt:</label><span>${formatDate(d.dateDepot)}</span></div>
                <div class="detail-item"><label>Investisseur:</label><span>${d.nomInvestisseur}</span></div>
                <div class="detail-item"><label>Secteur:</label><span>${d.secteur}</span></div>
                <div class="detail-item"><label>Montant:</label><span><strong>${formatCurrency(d.montant)}</strong></span></div>
                <div class="detail-item"><label>Risque:</label><span>${getRiskBadge(d.riskLevel)}</span></div>
                <div class="detail-item"><label>Workflow:</label><span><strong>${d.workflowStep}</strong></span></div>
            </div>
        </div>
        ${d.anomalyReason ? `<div class="detail-section"><div class="detail-title">⚠️ Anomalie</div><div class="detail-text rejection-text">${d.anomalyReason}</div></div>` : ''}
        ${d.description ? `<div class="detail-section"><div class="detail-title">📝 Description</div><div class="detail-text">${d.description}</div></div>` : ''}
        ${d.motifRejet ? `<div class="detail-section"><div class="detail-title">❌ Motif rejet</div><div class="detail-text rejection-text">${d.motifRejet}</div></div>` : ''}
        <div class="detail-section">
            <div class="detail-title">⚙️ Actions</div>
            <div class="detail-actions">
                ${d.statut === 'pending' ? `<button class="btn btn-gold btn-sm" onclick="validateDemande(${d.id}); closeModal('detailModal');">✅ Valider</button>` : ''}
                <button class="btn btn-outline btn-sm" onclick="editDemande(${d.id}); closeModal('detailModal');">✏️ Modifier</button>
            </div>
        </div>
    `;
    openModal('detailModal');
}

function filterByStatus(status) {
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) statusFilter.value = status;
    renderTable();
    showToast(`Filtré par: ${status === 'all' ? 'tous les statuts' : status}`, 'info');
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = 'all';
    document.getElementById('riskFilter').value = 'all';
    renderTable();
    showToast('Filtres réinitialisés', 'info');
}

// ============================================
// BULK FUNCTIONS
// ============================================
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.row-select');
    checkboxes.forEach(cb => {
        cb.checked = selectAll.checked;
        const id = parseInt(cb.dataset.id);
        if (selectAll.checked && !selectedItems.includes(id)) selectedItems.push(id);
        else if (!selectAll.checked) selectedItems = [];
    });
    updateBulkBar();
}

function toggleSelect(id) {
    if (selectedItems.includes(id)) {
        selectedItems = selectedItems.filter(i => i !== id);
    } else {
        selectedItems.push(id);
    }
    updateBulkBar();
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.checked = document.querySelectorAll('.row-select').length === selectedItems.length;
    }
}

function updateBulkBar() {
    const bulkBar = document.getElementById('bulkBar');
    const selectedCount = document.getElementById('selectedCount');
    if (selectedItems.length > 0) {
        bulkBar.classList.add('active');
        selectedCount.innerText = selectedItems.length;
    } else {
        bulkBar.classList.remove('active');
    }
}

function clearSelection() {
    selectedItems = [];
    document.querySelectorAll('.row-select').forEach(cb => cb.checked = false);
    const selectAll = document.getElementById('selectAll');
    if (selectAll) selectAll.checked = false;
    updateBulkBar();
}

function bulkApprove() {
    if (selectedItems.length === 0) {
        showToast('Aucun dossier sélectionné', 'warning');
        return;
    }
    if (confirm(`✅ Approuver ${selectedItems.length} demande(s) ?`)) {
        let approvedCount = 0;
        selectedItems.forEach(id => {
            const d = mecenatDemandes.find(x => x.id === id);
            if (d && d.statut === 'pending') {
                d.statut = 'validated';
                d.workflowStep = '✅ Attestation générée';
                approvedCount++;
            }
        });
        clearSelection();
        renderTable();
        renderAIInsights();
        showToast(`✅ ${approvedCount} demande(s) approuvée(s)`, 'success');
    }
}

function quickActionBulkApprove() {
    const lowRiskPending = mecenatDemandes.filter(d => d.statut === 'pending' && d.riskLevel === 'low');
    if (lowRiskPending.length === 0) {
        showToast('Aucune demande à faible risque en attente', 'info');
        return;
    }
    selectedItems = lowRiskPending.map(d => d.id);
    updateBulkBar();
    bulkApprove();
}

function quickActionExport() {
    const exportData = mecenatDemandes.map(d => ({
        'N° Dossier': d.numero,
        'Investisseur': d.nomInvestisseur,
        'Secteur': d.secteur,
        'Montant (TND)': d.montant,
        'Risque': d.riskLevel,
        'Statut': getStatusLabel(d.statut),
        'Date dépôt': d.dateDepot
    }));
    console.table(exportData);
    showToast('📎 Export CSV - Vérifiez la console', 'info');
}

function showWorkflowHelp() {
    alert(`📋 GUIDE AGENT - MÉCÉNAT (7 ÉTAPES):

1️⃣ RÉCEPTION → Création du dossier avec ID unique
2️⃣ VÉRIFICATION DOCS → Vérifiez la checklist des documents
3️⃣ DEMANDE COMPLÉMENT → Si documents manquants
4️⃣ TRANSMISSION COMMISSION → Envoi pour avis
5️⃣ AVIS COMMISSION → Accepté/rejeté/complément
6️⃣ GÉNÉRATION ATTESTATION → Document officiel créé
7️⃣ NOTIFICATION → Investisseur informé

💡 FONCTIONNALITÉS IA:
- F5: Risque basé sur complétude + budget
- F8: Détection anomalies (montants >2M TND)
- F9: Insights en temps réel`);
}

function showHighRiskDossiers() {
    const highRisk = mecenatDemandes.filter(d => d.riskLevel === 'high');
    if (highRisk.length > 0) {
        const details = highRisk.map(d => `• ${d.numero}: ${d.nomInvestisseur} (${formatCurrency(d.montant)})`).join('\n');
        alert(`⚠️ DOSSIERS HAUT RISQUE (${highRisk.length}):\n\n${details}\n\nCes dossiers nécessitent une attention particulière.`);
    } else {
        alert('✅ Aucun dossier haut risque.');
    }
}

function showAnomalies() {
    const anomalies = mecenatDemandes.filter(d => d.anomalyReason);
    if (anomalies.length > 0) {
        const details = anomalies.map(d => `• ${d.numero}: ${d.anomalyReason}`).join('\n');
        alert(`⚠️ ANOMALIES DÉTECTÉES (${anomalies.length}):\n\n${details}`);
    } else {
        alert('✅ Aucune anomalie détectée.');
    }
}

function showUnderfundedSectors() {
    const underfunded = sectorData.filter(s => s.gap < -5);
    alert(`📊 SECTEURS SOUS-FINANCÉS (${underfunded.length}):\n\n${underfunded.map(s => `• ${s.name}: ${Math.abs(s.gap)}% sous objectif`).join('\n')}\n\n💡 Campagne de communication recommandée.`);
}

function showSectorDetails(sectorName) {
    const sector = sectorData.find(s => s.name === sectorName);
    if (sector) {
        alert(`📊 SECTEUR ${sector.name.toUpperCase()}:\n\n• Investissement: ${formatCurrency(sector.amount)}\n• Part: ${sector.percentage}% (objectif ${sector.target}%)\n• Écart: ${sector.gap >= 0 ? '+' : ''}${sector.gap}%`);
    }
}

function filterByTier(tier) {
    const tierData = investorTiersData.find(t => t.tier === tier);
    if (tierData) {
        alert(`🏆 ${tierData.label.toUpperCase()} (${tierData.count}):\n\n${tierData.description}`);
    }
}

// ============================================
// INITIALIZATION
// ============================================
document.addEventListener('DOMContentLoaded', () => {
    renderTable();
    renderAIInsights();
    renderEngagementStats();
    renderSectorHeatmap();
});

// Make functions global
window.filterByStatus = filterByStatus;
window.resetFilters = resetFilters;
window.openCreateModal = openCreateModal;
window.editDemande = editDemande;
window.saveDemande = saveDemande;
window.deleteDemande = deleteDemande;
window.validateDemande = validateDemande;
window.showDetail = showDetail;
window.toggleSelectAll = toggleSelectAll;
window.toggleSelect = toggleSelect;
window.clearSelection = clearSelection;
window.bulkApprove = bulkApprove;
window.quickActionBulkApprove = quickActionBulkApprove;
window.quickActionExport = quickActionExport;
window.showWorkflowHelp = showWorkflowHelp;
window.showHighRiskDossiers = showHighRiskDossiers;
window.showAnomalies = showAnomalies;
window.showUnderfundedSectors = showUnderfundedSectors;
window.showSectorDetails = showSectorDetails;
window.filterByTier = filterByTier;
window.filterTimeline = filterTimeline;
window.closeModal = closeModal;
</script>
@endsection
