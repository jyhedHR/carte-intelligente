/**
 * ═══════════════════════════════════════════════════════════
 * UNITÉ D'ENCADREMENT DES INVESTISSEURS - COMPLETE JAVASCRIPT
 * Version: 1.0 (Safe mode - null checks added)
 * ═══════════════════════════════════════════════════════════
 */

// ============================================
// DEMO DATA
// ============================================

// Mécénat applications with Risk Indicator (F5)
let mecenatDemandes = [
    {
        id: 1,
        numero: 'MEC-20260001',
        nomInvestisseur: 'Fondation Tunisienne pour la Culture',
        matricule: '1234567/A/M/001',
        secteur: 'Livre',
        montant: 150000,
        description: 'Soutien à l\'édition de livres tunisiens',
        dateDepot: '2026-04-10',
        statut: 'pending',
        docCompleteness: 95,
        budgetPlausible: true,
        riskLevel: 'low',
        workflowStep: '📥 Étape 1/7 - Nouvelle demande reçue',
        workflowStage: 1
    },
    {
        id: 2,
        numero: 'MEC-20260002',
        nomInvestisseur: 'Groupe Chimique Tunisien',
        matricule: '2345678/B/M/002',
        secteur: 'Arts Plastiques',
        montant: 450000,
        description: 'Projet de rénovation de galerie d\'art',
        dateDepot: '2026-04-05',
        statut: 'progress',
        docCompleteness: 70,
        budgetPlausible: true,
        riskLevel: 'medium',
        workflowStep: '🔍 Étape 2/7 - Vérification documents en cours',
        workflowStage: 2
    },
    {
        id: 3,
        numero: 'MEC-20260003',
        nomInvestisseur: 'Banque Internationale Arabe',
        matricule: '3456789/C/M/003',
        secteur: 'Musique',
        montant: 2800000,
        description: 'Festival de musique internationale',
        dateDepot: '2026-04-01',
        statut: 'pending',
        docCompleteness: 45,
        budgetPlausible: false,
        riskLevel: 'high',
        anomalyReason: 'Montant anormalement élevé',
        workflowStep: '⚠️ Étape 3/7 - Anomalie détectée - En attente de validation superieur',
        workflowStage: 3
    },
    {
        id: 4,
        numero: 'MEC-20260004',
        nomInvestisseur: 'Carthage Cement',
        matricule: '4567890/D/M/004',
        secteur: 'Patrimoine',
        montant: 750000,
        description: 'Restauration de monuments historiques',
        dateDepot: '2026-03-28',
        statut: 'validated',
        docCompleteness: 100,
        budgetPlausible: true,
        riskLevel: 'low',
        workflowStep: '✅ Étape 6/7 - Attestation générée',
        workflowStage: 6
    },
    {
        id: 5,
        numero: 'MEC-20260005',
        nomInvestisseur: 'Tunisie Telecom',
        matricule: '5678901/E/M/005',
        secteur: 'Cinéma',
        montant: 1200000,
        description: 'Soutien au cinéma tunisien',
        dateDepot: '2026-03-25',
        statut: 'progress',
        docCompleteness: 85,
        budgetPlausible: true,
        riskLevel: 'medium',
        workflowStep: '📎 Étape 4/7 - En attente commission',
        workflowStage: 4
    },
    {
        id: 6,
        numero: 'MEC-20260006',
        nomInvestisseur: 'SFBT',
        matricule: '6789012/F/M/006',
        secteur: 'Théâtre',
        montant: 350000,
        description: 'Soutien aux troupes théâtrales',
        dateDepot: '2026-03-20',
        statut: 'rejected',
        docCompleteness: 30,
        budgetPlausible: false,
        riskLevel: 'high',
        anomalyReason: 'Documents incomplets et montant non justifié',
        motifRejet: 'Dossier incomplet - Documents manquants',
        workflowStep: '❌ Dossier rejeté',
        workflowStage: 0
    }
];

// Investor profiles with Engagement Score (F6)
let investorProfiles = [
    { id: 1, nom: 'Fondation Tunisienne pour la Culture', lastSubmission: '2026-04-10', totalSubmissions: 8, totalAmount: 850000, tier: 'gold', churnRisk: false },
    { id: 2, nom: 'Groupe Chimique Tunisien', lastSubmission: '2026-04-05', totalSubmissions: 5, totalAmount: 1250000, tier: 'silver', churnRisk: false },
    { id: 3, nom: 'Banque Internationale Arabe', lastSubmission: '2026-04-01', totalSubmissions: 12, totalAmount: 3500000, tier: 'gold', churnRisk: false },
    { id: 4, nom: 'Carthage Cement', lastSubmission: '2026-03-28', totalSubmissions: 4, totalAmount: 980000, tier: 'silver', churnRisk: false },
    { id: 5, nom: 'Tunisie Telecom', lastSubmission: '2026-03-25', totalSubmissions: 10, totalAmount: 2100000, tier: 'gold', churnRisk: false },
    { id: 6, nom: 'SFBT', lastSubmission: '2026-03-20', totalSubmissions: 3, totalAmount: 520000, tier: 'bronze', churnRisk: false },
    { id: 7, nom: 'Groupe Poulina', lastSubmission: '2025-01-15', totalSubmissions: 2, totalAmount: 180000, tier: 'bronze', churnRisk: true, monthsInactive: 14 },
    { id: 8, nom: 'Amen Bank', lastSubmission: '2025-02-10', totalSubmissions: 3, totalAmount: 450000, tier: 'bronze', churnRisk: true, monthsInactive: 13 }
];

// Sector investment data for Heatmap (F7)
let sectorData = [
    { name: 'Livre', amount: 850000, percentage: 8, target: 20, gap: -12 },
    { name: 'Cinéma', amount: 1200000, percentage: 12, target: 20, gap: -8 },
    { name: 'Théâtre', amount: 650000, percentage: 6, target: 15, gap: -9 },
    { name: 'Musique', amount: 3100000, percentage: 30, target: 20, gap: 10 },
    { name: 'Arts Plastiques', amount: 950000, percentage: 9, target: 15, gap: -6 },
    { name: 'Patrimoine', amount: 1750000, percentage: 17, target: 10, gap: 7 }
];

let nextId = 7;
let selectedItems = [];

// Helper to check if we're on the investisseurs page
function isInvestisseursPage() {
    const container = document.getElementById('tableBody');
    return !!container;
}

// ============================================
// HELPER FUNCTIONS (safe versions)
// ============================================

function formatDate(dateString) {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('fr-FR');
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('fr-TN', { style: 'currency', currency: 'TND', maximumFractionDigits: 0 }).format(amount);
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

function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.add('active');
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.remove('active');
}

function getStatusClass(statut) {
    const classes = {
        pending: 'investisseur-status-pending',
        progress: 'investisseur-status-progress',
        validated: 'investisseur-status-validated',
        rejected: 'investisseur-status-rejected'
    };
    return classes[statut] || 'investisseur-status-pending';
}

function getStatusLabel(statut) {
    const labels = {
        pending: '⏳ En attente',
        progress: '🔄 En cours',
        validated: '✅ Validé',
        rejected: '❌ Rejeté'
    };
    return labels[statut] || statut;
}

// ============================================
// F5: RISK INDICATOR
// ============================================

function getRiskBadge(riskLevel) {
    const labels = { low: '🟢 Faible risque', medium: '🟡 Risque modéré', high: '🔴 Risque élevé' };
    return `<span class="risk-badge ${riskLevel}">${labels[riskLevel] || 'Non évalué'}</span>`;
}

// ============================================
// F6: INVESTOR ENGAGEMENT SCORE (RFM) - SAFE
// ============================================

function getEngagementTier(tier) {
    const icons = { gold: '🏆 Or', silver: '🥈 Argent', bronze: '🥉 Bronze' };
    return `<span class="engagement-tier ${tier}">${icons[tier] || tier}</span>`;
}

function renderEngagementStats() {
    const container = document.getElementById('engagementStatsGrid');
    if (!container) return;

    const goldCount = investorProfiles.filter(i => i.tier === 'gold').length;
    const silverCount = investorProfiles.filter(i => i.tier === 'silver').length;
    const bronzeCount = investorProfiles.filter(i => i.tier === 'bronze').length;
    const churnRiskCount = investorProfiles.filter(i => i.churnRisk === true).length;

    container.innerHTML = `
        <div class="investisseur-stats" style="margin-bottom: 0;">
            <div class="investisseur-stat" onclick="filterByTier('gold')">
                <div class="investisseur-stat-value" style="color: var(--gold);">${goldCount}</div>
                <div class="investisseur-stat-label">🏆 Investisseurs Or</div>
            </div>
            <div class="investisseur-stat" onclick="filterByTier('silver')">
                <div class="investisseur-stat-value" style="color: #c0c0c0;">${silverCount}</div>
                <div class="investisseur-stat-label">🥈 Investisseurs Argent</div>
            </div>
            <div class="investisseur-stat" onclick="filterByTier('bronze')">
                <div class="investisseur-stat-value" style="color: #cd7f32;">${bronzeCount}</div>
                <div class="investisseur-stat-label">🥉 Investisseurs Bronze</div>
            </div>
            <div class="investisseur-stat" onclick="showChurnAlerts()">
                <div class="investisseur-stat-value" style="color: var(--red);">${churnRiskCount}</div>
                <div class="investisseur-stat-label">⚠️ Risque de départ</div>
            </div>
        </div>
    `;
}

function filterByTier(tier) {
    const filtered = investorProfiles.filter(i => i.tier === tier);
    showToast(`${filtered.length} investisseur(s) de niveau ${tier}`, 'info');
}

function showChurnAlerts() {
    const churnInvestors = investorProfiles.filter(i => i.churnRisk === true);
    if (churnInvestors.length > 0) {
        const names = churnInvestors.map(i => `${i.nom} (inactif depuis ${i.monthsInactive} mois)`).join('\n');
        alert(`⚠️ INVESTISSEURS À RISQUE DE DÉPART:\n\n${names}\n\n📞 Action recommandée: Contactez-les pour réengagement`);
    } else {
        showToast('Aucun investisseur à risque de départ', 'success');
    }
}

// ============================================
// F7: SECTOR HEATMAP - SAFE
// ============================================

function renderSectorHeatmap() {
    const container = document.getElementById('sectorHeatmapGrid');
    if (!container) return;

    const totalAmount = sectorData.reduce((sum, s) => sum + s.amount, 0);
    const mostUnderfunded = sectorData.reduce((prev, curr) => (curr.gap < prev.gap) ? curr : prev, sectorData[0]);

    container.innerHTML = `
        <div class="sector-grid">
            ${sectorData.map(sector => {
                const isUnderfunded = sector.gap < -5;
                const barClass = isUnderfunded ? 'underfunded' : 'normal';
                return `
                    <div class="sector-bar-item">
                        <div class="sector-bar-header">
                            <span class="sector-name">${sector.name}</span>
                            <span class="sector-percent">${sector.percentage}% (objectif ${sector.target}%)</span>
                        </div>
                        <div class="sector-bar-container">
                            <div class="sector-bar ${barClass}" style="width: ${(sector.percentage / Math.max(...sectorData.map(s => s.percentage))) * 100}%"></div>
                        </div>
                        <div class="sector-target">💰 Investi: ${formatCurrency(sector.amount)} | ${sector.gap >= 0 ? '✓ Objectif atteint' : '⚠️ Sous-financé de ' + Math.abs(sector.gap) + '%'}</div>
                    </div>
                `;
            }).join('')}
            <div class="sector-recommendation">
                💡 RECOMMANDATION IA: <strong>${mostUnderfunded.name}</strong> est le secteur le plus sous-financé (${Math.abs(mostUnderfunded.gap)}% sous objectif).
                Campagne de communication recommandée pour ce secteur.
            </div>
        </div>
    `;
}

// ============================================
// F8: ANOMALY FLAG
// ============================================

function getAnomalyFlag(demande) {
    if (demande.anomalyReason) {
        return `<span class="anomaly-flag" title="${demande.anomalyReason}">⚠️ Anomalie détectée</span>`;
    }
    return '';
}

// ============================================
// F9: AI INSIGHT PANEL - SAFE
// ============================================

function renderAIInsights() {
    const container = document.getElementById('aiInsightGrid');
    if (!container) return;

    const pendingCount = mecenatDemandes.filter(d => d.statut === 'pending').length;
    const highRiskCount = mecenatDemandes.filter(d => d.riskLevel === 'high').length;
    const anomalyCount = mecenatDemandes.filter(d => d.anomalyReason).length;
    const churnRiskCount = investorProfiles.filter(i => i.churnRisk === true).length;
    const underfundedSectors = sectorData.filter(s => s.gap < -5).length;

    container.innerHTML = `
        <div class="insight-card" onclick="filterByStatus('pending')">
            <div class="insight-icon risk-icon"></div>
            <div class="insight-content">
                <div class="insight-value">${pendingCount}</div>
                <div class="insight-label">Demandes en attente</div>
                <div class="insight-action">À traiter par l'agent →</div>
            </div>
        </div>
        <div class="insight-card warning" onclick="showHighRiskDossiers()">
            <div class="insight-icon engagement-icon"></div>
            <div class="insight-content">
                <div class="insight-value">${highRiskCount}</div>
                <div class="insight-label">Dossiers haut risque</div>
                <div class="insight-action">⚠️ Vérification prioritaire →</div>
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
        <div class="insight-card" onclick="showChurnAlerts()">
            <div class="insight-icon sector-icon"></div>
            <div class="insight-content">
                <div class="insight-value">${churnRiskCount}</div>
                <div class="insight-label">Investisseurs inactifs</div>
                <div class="insight-action">📞 À contacter →</div>
            </div>
        </div>
    `;

    const timeElement = document.getElementById('aiLastUpdate');
    if (timeElement) timeElement.innerText = new Date().toLocaleTimeString();
}

function showHighRiskDossiers() {
    const highRisk = mecenatDemandes.filter(d => d.riskLevel === 'high');
    if (highRisk.length > 0) {
        const ids = highRisk.map(d => d.id);
        selectedItems = ids;
        updateBulkBar();
        showToast(`⚠️ ${highRisk.length} dossier(s) haut risque sélectionné(s) - Vérification prioritaire`, 'warning');
    } else {
        showToast('Aucun dossier haut risque', 'success');
    }
}

function showAnomalies() {
    const anomalies = mecenatDemandes.filter(d => d.anomalyReason);
    if (anomalies.length > 0) {
        const details = anomalies.map(d => `${d.numero}: ${d.anomalyReason}`).join('\n');
        alert(`⚠️ ANOMALIES DÉTECTÉES:\n\n${details}\n\nCes dossiers nécessitent une attention particulière.`);
    } else {
        showToast('Aucune anomalie détectée', 'success');
    }
}

// ============================================
// WORKFLOW HELP
// ============================================

function showWorkflowHelp() {
    const helpMessage = `📋 GUIDE AGENT - UNITÉ INVESTISSEURS (7 ÉTAPES):

1️⃣ RÉCEPTION DEMANDE → Création du dossier, notification agent
2️⃣ VÉRIFICATION DOCUMENTS → Vérification checklist documents requis
3️⃣ TRANSMISSION COMMISSION → Envoi à la commission pour avis
4️⃣ COMMISSION → La commission émet un avis (accepté/rejeté/complément)
5️⃣ RESPONSABLE → Validation finale par le responsable
6️⃣ GÉNÉRATION ATTESTATION → Document généré automatiquement
7️⃣ NOTIFICATION → Investisseur notifié, dossier archivé

💡 FONCTIONNALITÉS IA:
- F5: Indicateur de risque (basé complétude + budget)
- F6: Score d'engagement (RFM: Or/Argent/Bronze)
- F7: Heatmap secteurs sous-financés
- F8: Détection automatique d'anomalies
- F9: Panneau d'insights centralisé`;

    alert(helpMessage);
}

// ============================================
// TABLE RENDER - SAFE VERSION
// ============================================

function renderTable() {
    const tbody = document.getElementById('tableBody');
    // CRITICAL: Exit immediately if not on investisseurs page
    if (!tbody) return;

    let search = document.getElementById('searchInput')?.value.toLowerCase() || '';
    let status = document.getElementById('statusFilter')?.value || 'all';
    let riskFilter = document.getElementById('riskFilter')?.value || 'all';

    let filtered = mecenatDemandes.filter(d =>
        (d.nomInvestisseur.toLowerCase().includes(search) || d.numero.toLowerCase().includes(search)) &&
        (status === 'all' || d.statut === status) &&
        (riskFilter === 'all' || d.riskLevel === riskFilter)
    );

    tbody.innerHTML = filtered.map(d => `
        <tr onclick="showDetail(${d.id})" style="cursor:pointer;">
            <td class="checkbox-col" onclick="event.stopPropagation()"><input type="checkbox" class="row-select bulk-checkbox" data-id="${d.id}" onchange="toggleSelect(${d.id})"></td>
            <td><strong>${d.numero}</strong><br><small style="font-size:9px; color:var(--text3);">${d.workflowStep.substring(0, 40)}...</small></td>
            <td>${d.nomInvestisseur}${d.churnRisk ? ' ⚠️' : ''}</td>
            <td>${d.secteur}</td>
            <td>${formatCurrency(d.montant)}</td>
            <td>${getRiskBadge(d.riskLevel)}</td>
            <td>${getAnomalyFlag(d)}</td>
            <td>${formatDate(d.dateDepot)}</td>
            <td><span class="investisseur-status ${getStatusClass(d.statut)}">${getStatusLabel(d.statut)}</span></td>
            <td onclick="event.stopPropagation()">
                <button class="btn btn-outline btn-sm" onclick="editDemande(${d.id})">✏️</button>
                <button class="btn btn-outline btn-sm" onclick="deleteDemande(${d.id})">🗑️</button>
                ${d.statut === 'pending' ? `<button class="btn btn-gold btn-sm" onclick="validateDemande(${d.id})">✅ Valider</button>` : ''}
            </td>
        </tr>
    `).join('');

    // Safe element updates with null checks
    const totalCountEl = document.getElementById('totalCount');
    const pendingCountEl = document.getElementById('pendingCount');
    const validatedCountEl = document.getElementById('validatedCount');
    const rejectedCountEl = document.getElementById('rejectedCount');

    if (totalCountEl) totalCountEl.innerText = mecenatDemandes.length;
    if (pendingCountEl) pendingCountEl.innerText = mecenatDemandes.filter(d => d.statut === 'pending').length;
    if (validatedCountEl) validatedCountEl.innerText = mecenatDemandes.filter(d => d.statut === 'validated').length;
    if (rejectedCountEl) rejectedCountEl.innerText = mecenatDemandes.filter(d => d.statut === 'rejected').length;

    // Only render other components if their containers exist
    if (document.getElementById('aiInsightGrid')) renderAIInsights();
    if (document.getElementById('engagementStatsGrid')) renderEngagementStats();
    if (document.getElementById('sectorHeatmapGrid')) renderSectorHeatmap();
    if (document.getElementById('activityTimeline')) renderActivityTimeline();
}

// ============================================
// ACTIVITY TIMELINE - SAFE
// ============================================

function renderActivityTimeline() {
    const container = document.getElementById('activityTimeline');
    if (!container) return;

    const activities = [];
    mecenatDemandes.forEach(d => {
        activities.push({
            type: 'created',
            action: '📥 Nouvelle demande mécénat',
            detail: `${d.nomInvestisseur} - ${d.secteur} (${formatCurrency(d.montant)})`,
            time: d.dateDepot
        });
        if (d.anomalyReason) {
            activities.push({
                type: 'anomaly',
                action: '⚠️ Anomalie détectée',
                detail: `${d.numero}: ${d.anomalyReason}`,
                time: new Date(new Date(d.dateDepot).getTime() + 1*24*60*60*1000).toISOString().split('T')[0]
            });
        }
        if (d.statut === 'validated') {
            activities.push({
                type: 'approved',
                action: '✅ Attestation délivrée',
                detail: `${d.nomInvestisseur} - Montant: ${formatCurrency(d.montant)}`,
                time: new Date(new Date(d.dateDepot).getTime() + 5*24*60*60*1000).toISOString().split('T')[0]
            });
        }
    });
    activities.sort((a,b) => new Date(b.time) - new Date(a.time));

    container.innerHTML = `
        <div class="activity-timeline">
            <div class="timeline-header">
                <div class="timeline-title">📋 Activité récente - Suivi des actions</div>
                <div class="timeline-filter">
                    <button class="timeline-filter-btn active" onclick="filterTimeline('all')">Tous</button>
                    <button class="timeline-filter-btn" onclick="filterTimeline('created')">📥 Nouveaux</button>
                    <button class="timeline-filter-btn" onclick="filterTimeline('anomaly')">⚠️ Anomalies</button>
                    <button class="timeline-filter-btn" onclick="filterTimeline('approved')">✅ Validés</button>
                </div>
            </div>
            <div class="timeline-items" id="timelineItems">
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

function formatRelativeTime(dateStr) {
    const date = new Date(dateStr);
    const now = new Date();
    const diffDays = Math.floor((now - date) / (1000 * 60 * 60 * 24));
    if (diffDays === 0) return "Aujourd'hui";
    if (diffDays === 1) return "Hier";
    if (diffDays < 7) return `Il y a ${diffDays} jours`;
    return date.toLocaleDateString('fr-FR');
}

function filterTimeline(type) {
    const items = document.querySelectorAll('.timeline-item');
    if (!items.length) return;
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
// BULK ACTIONS - SAFE
// ============================================

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    if (!selectAll) return;
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
    if (selectedItems.includes(id)) selectedItems = selectedItems.filter(i => i !== id);
    else selectedItems.push(id);
    updateBulkBar();
}

function updateBulkBar() {
    const bulkBar = document.getElementById('bulkBar');
    const selectedCount = document.getElementById('selectedCount');
    if (!bulkBar || !selectedCount) return;
    if (selectedItems.length > 0) {
        bulkBar.classList.add('active');
        selectedCount.innerText = selectedItems.length;
    } else {
        bulkBar.classList.remove('active');
    }
}

function clearSelection() {
    selectedItems = [];
    const checkboxes = document.querySelectorAll('.row-select');
    checkboxes.forEach(cb => cb.checked = false);
    updateBulkBar();
}

function bulkApprove() {
    if (selectedItems.length === 0) return;
    if (confirm(`✅ Approuver ${selectedItems.length} demande(s) ?`)) {
        selectedItems.forEach(id => {
            const d = mecenatDemandes.find(x => x.id === id);
            if (d && d.statut === 'pending') {
                d.statut = 'validated';
                d.workflowStep = '✅ Étape 6/7 - Attestation générée';
                d.workflowStage = 6;
            }
        });
        clearSelection();
        renderTable();
        showToast(`✅ ${selectedItems.length} demande(s) approuvée(s)`, 'success');
    }
}

// ============================================
// CRUD FUNCTIONS - SAFE
// ============================================

function openCreateModal() {
    const modalTitle = document.getElementById('modalTitle');
    const editId = document.getElementById('editId');
    if (!modalTitle || !editId) return;
    modalTitle.innerText = '📝 Nouvelle demande';
    editId.value = '';
    ['nomInvestisseur', 'matricule', 'secteur', 'montant', 'description'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    openModal('formModal');
}

function editDemande(id) {
    const d = mecenatDemandes.find(x => x.id === id);
    if (!d) return;
    const modalTitle = document.getElementById('modalTitle');
    const editId = document.getElementById('editId');
    if (!modalTitle || !editId) return;
    modalTitle.innerText = '✏️ Modifier la demande';
    editId.value = d.id;
    const nomInvestisseur = document.getElementById('nomInvestisseur');
    const matricule = document.getElementById('matricule');
    const secteur = document.getElementById('secteur');
    const montant = document.getElementById('montant');
    const description = document.getElementById('description');
    if (nomInvestisseur) nomInvestisseur.value = d.nomInvestisseur;
    if (matricule) matricule.value = d.matricule;
    if (secteur) secteur.value = d.secteur;
    if (montant) montant.value = d.montant;
    if (description) description.value = d.description || '';
    openModal('formModal');
}

function saveDemande() {
    const id = document.getElementById('editId')?.value;
    const today = new Date().toISOString().split('T')[0];

    const demandeData = {
        nomInvestisseur: document.getElementById('nomInvestisseur')?.value || '',
        matricule: document.getElementById('matricule')?.value || '',
        secteur: document.getElementById('secteur')?.value || '',
        montant: parseInt(document.getElementById('montant')?.value) || 0,
        description: document.getElementById('description')?.value || ''
    };

    // Calculate risk level based on completeness and budget
    let docCompleteness = 80;
    let budgetPlausible = demandeData.montant <= 1000000;
    let riskLevel = 'low';
    let anomalyReason = null;

    if (demandeData.montant > 2000000) {
        riskLevel = 'high';
        anomalyReason = 'Montant anormalement élevé';
        budgetPlausible = false;
        docCompleteness = 50;
    } else if (demandeData.montant > 1000000) {
        riskLevel = 'medium';
    }

    if (id) {
        const idx = mecenatDemandes.findIndex(d => d.id == id);
        if (idx !== -1) {
            mecenatDemandes[idx] = { ...mecenatDemandes[idx], ...demandeData };
            showToast('Demande modifiée avec succès', 'success');
        }
    } else {
        const newId = nextId++;
        const newNumero = `MEC-2026${String(newId).padStart(4, '0')}`;

        mecenatDemandes.push({
            id: newId,
            numero: newNumero,
            dateDepot: today,
            statut: 'pending',
            docCompleteness: docCompleteness,
            budgetPlausible: budgetPlausible,
            riskLevel: riskLevel,
            anomalyReason: anomalyReason,
            workflowStep: '📥 Étape 1/7 - Nouvelle demande reçue',
            workflowStage: 1,
            ...demandeData
        });
        showToast(`✅ Demande ${newNumero} créée avec succès`, 'success');
    }

    closeModal('formModal');
    renderTable();
}

function deleteDemande(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette demande ?')) {
        mecenatDemandes = mecenatDemandes.filter(d => d.id !== id);
        renderTable();
        showToast('Demande supprimée', 'success');
    }
}

function validateDemande(id) {
    const d = mecenatDemandes.find(x => x.id === id);
    if (d && d.statut === 'pending') {
        d.statut = 'validated';
        d.workflowStep = '✅ Étape 6/7 - Attestation générée';
        d.workflowStage = 6;
        renderTable();
        showToast(`✅ Demande ${d.numero} validée`, 'success');
    }
}

function showDetail(id) {
    const detailContent = document.getElementById('detailContent');
    if (!detailContent) return;
    const d = mecenatDemandes.find(x => x.id === id);
    if (!d) return;

    const detailHtml = `
        <div class="detail-section">
            <div class="detail-header">
                <div class="detail-title">📋 Informations générales</div>
                <div class="detail-badge ${getStatusClass(d.statut)}">${getStatusLabel(d.statut)}</div>
            </div>
            <div class="detail-grid">
                <div class="detail-item"><label>N° Dossier:</label><span><strong>${d.numero}</strong></span></div>
                <div class="detail-item"><label>Date dépôt:</label><span>${formatDate(d.dateDepot)}</span></div>
                <div class="detail-item"><label>Investisseur:</label><span>${d.nomInvestisseur}</span></div>
                <div class="detail-item"><label>Secteur:</label><span>${d.secteur}</span></div>
                <div class="detail-item"><label>Montant:</label><span><strong>${formatCurrency(d.montant)}</strong></span></div>
                <div class="detail-item"><label>Niveau risque:</label><span>${getRiskBadge(d.riskLevel)}</span></div>
                <div class="detail-item"><label>Complétude documents:</label><span>${d.docCompleteness}%</span></div>
                <div class="detail-item"><label>Étape workflow:</label><span><strong>${d.workflowStep}</strong></span></div>
            </div>
        </div>
        ${d.anomalyReason ? `<div class="detail-section"><div class="detail-title">⚠️ Anomalie détectée</div><div class="detail-text rejection-text">${d.anomalyReason}</div></div>` : ''}
        ${d.description ? `<div class="detail-section"><div class="detail-title">📝 Description</div><div class="detail-text">${d.description}</div></div>` : ''}
        <div class="detail-section">
            <div class="detail-title">⚙️ Actions rapides</div>
            <div class="detail-actions">
                ${d.statut === 'pending' ? `<button class="btn btn-gold btn-sm" onclick="validateDemande(${d.id}); closeModal('detailModal');">✅ Valider la demande</button>` : ''}
                <button class="btn btn-outline btn-sm" onclick="editDemande(${d.id}); closeModal('detailModal');">✏️ Modifier</button>
            </div>
        </div>
    `;

    detailContent.innerHTML = detailHtml;
    openModal('detailModal');
}

function resetFilters() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const riskFilter = document.getElementById('riskFilter');
    if (searchInput) searchInput.value = '';
    if (statusFilter) statusFilter.value = 'all';
    if (riskFilter) riskFilter.value = 'all';
    renderTable();
    showToast('Filtres réinitialisés', 'info');
}

function filterByStatus(status) {
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) statusFilter.value = status;
    renderTable();
}

// ============================================
// QUICK ACTIONS - SAFE
// ============================================

function quickActionBulkApprove() {
    const pendingItems = mecenatDemandes.filter(d => d.statut === 'pending' && d.riskLevel !== 'high');
    if (pendingItems.length === 0) {
        showToast('Aucune demande à faible risque à approuver', 'info');
        return;
    }
    selectedItems = pendingItems.map(d => d.id);
    updateBulkBar();
    bulkApprove();
}

function quickActionExport() {
    const exportData = mecenatDemandes.map(d => ({
        'N° Dossier': d.numero,
        'Investisseur': d.nomInvestisseur,
        'Secteur': d.secteur,
        'Montant': d.montant,
        'Risque': d.riskLevel,
        'Statut': getStatusLabel(d.statut),
        'Date dépôt': d.dateDepot
    }));
    console.table(exportData);
    showToast('📎 Export CSV - Vérifiez la console', 'info');
}

// ============================================
// INITIALIZATION - SAFE VERSION
// ============================================

// Make functions global
window.mecenatDemandes = mecenatDemandes;
window.renderTable = renderTable;
window.bulkApprove = bulkApprove;
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
window.updateBulkBar = updateBulkBar;
window.filterTimeline = filterTimeline;
window.quickActionBulkApprove = quickActionBulkApprove;
window.quickActionExport = quickActionExport;
window.showWorkflowHelp = showWorkflowHelp;
window.showHighRiskDossiers = showHighRiskDossiers;
window.showAnomalies = showAnomalies;
window.showChurnAlerts = showChurnAlerts;
window.filterByTier = filterByTier;

window.initInvestisseurs = function() {
    // Only initialize if we're on the investisseurs page (tableBody exists)
    if (!document.getElementById('tableBody')) {
        console.log('💼 Investisseurs module: Not on investisseurs page, skipping initialization');
        return;
    }
    console.log('💼 Initializing Investisseurs module...');
    renderTable();
    renderAIInsights();
    renderEngagementStats();
    renderSectorHeatmap();
    renderActivityTimeline();
};

// Safe DOMContentLoaded listener
document.addEventListener('DOMContentLoaded', () => {
    // Only initialize if tableBody exists (we're on investisseurs page)
    if (document.getElementById('tableBody')) {
        setTimeout(() => {
            if (typeof window.initInvestisseurs === 'function') {
                window.initInvestisseurs();
            }
        }, 30);
    }
});
