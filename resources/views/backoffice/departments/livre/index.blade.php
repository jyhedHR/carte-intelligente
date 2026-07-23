@extends('shared.layouts.backoffice')

@section('page-title', 'Direction du Livre - Tableau de bord')
@section('breadcrumb', 'Direction du Livre')


@section('content')
<div class="page active">


    <!-- Header -->
    <div class="livre-header">
        <div>
            <div class="livre-title">Direction Générale du Livre</div>
            <div class="livre-subtitle">Gestion des services et demandes éditoriales • Décret n° 95-1283</div>
        </div>
        <div class="livre-header-actions" style="display: flex; gap: 12px;">
            <button class="btn btn-outline btn-sm" onclick="exportDashboardData()">
                <span class="livre-action-icon icon-spark" style="width:14px;height:14px;"></span>
                Genérer rapport
            </button>
            <button class="btn btn-gold btn-sm" onclick="location.reload()">
                <span class="livre-action-icon icon-refresh" style="width:14px;height:14px;"></span>
                Actualiser
            </button>
        </div>
    </div>

    <!-- Quick Stats Row - L2: Quick Stats Cards -->
    <div class="quick-stats">
        <div class="quick-stat-card" onclick="filterByService('all')">
            <div class="quick-stat-icon">
                <span class="icon icon-bar-chart"></span>
            </div>
            <div class="quick-stat-info">
                <div class="quick-stat-value" id="totalDemandes">0</div>
                <div class="quick-stat-label">Total demandes</div>
                <div class="quick-stat-trend trend-up">↑ +12% ce mois</div>
            </div>
        </div>
        <div class="quick-stat-card" onclick="filterByService('pending')">
            <div class="quick-stat-icon">
                <span class="icon icon-pending"></span>
            </div>
            <div class="quick-stat-info">
                <div class="quick-stat-value" id="pendingDemandes">0</div>
                <div class="quick-stat-label">En attente</div>
                <div class="quick-stat-trend trend-up">↑ +3 cette semaine</div>
            </div>
        </div>
        <div class="quick-stat-card" onclick="filterByService('validated')">
            <div class="quick-stat-icon">
                <span class="icon icon-approved"></span>
            </div>
            <div class="quick-stat-info">
                <div class="quick-stat-value" id="validatedDemandes">0</div>
                <div class="quick-stat-label">Validées</div>
                <div class="quick-stat-trend trend-up">↑ +8% ce mois</div>
            </div>
        </div>
        <div class="quick-stat-card" onclick="filterByService('rejected')">
            <div class="quick-stat-icon">
                <span class="icon icon-rejected"></span>
            </div>
            <div class="quick-stat-info">
                <div class="quick-stat-value" id="rejectedDemandes">0</div>
                <div class="quick-stat-label">Rejetées</div>
                <div class="quick-stat-trend trend-down">↓ -2% ce mois</div>
            </div>
        </div>
    </div>

    <!-- Service Cards Grid - L1: Enhanced Service Cards -->
    <div class="services-grid">
        <!-- Card 1: Facilitation transfert droits -->
        <div class="service-card" onclick="window.location.href='{{ route('admin.livre.droits.index') }}'">
            <div class="service-card-inner">
                <div class="service-icon-wrapper">
                    <div class="service-icon">
                        <span class="icon icon-droits"></span>
                    </div>
                </div>
                <div class="service-content">
                    <h3 class="service-title">Facilitation transfert droits</h3>
                    <p class="service-desc">Aide aux éditeurs pour le transfert des droits d'édition</p>
                    <div class="service-stats">
                        <div class="service-stat">
                            <span class="service-stat-value" id="droitsCount">0</span>
                            <span class="service-stat-label">demandes</span>
                        </div>
                        <div class="service-stat">
                            <span class="service-stat-value" id="droitsPending">0</span>
                            <span class="service-stat-label">en attente</span>
                        </div>
                    </div>
                    <div class="service-footer">
                        <span class="service-badge gold">Décret n° 95-1283</span>
                        <div class="service-arrow">
                            <span class="icon-arrow"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Participation foire internationale -->
        <div class="service-card" onclick="window.location.href='{{ route('admin.livre.foire.index') }}'">
            <div class="service-card-inner">
                <div class="service-icon-wrapper">
                    <div class="service-icon">
                        <span class="icon icon-foire"></span>
                    </div>
                </div>
                <div class="service-content">
                    <h3 class="service-title">Participation foire internationale</h3>
                    <p class="service-desc">Soutien pour les foires du livre à l'étranger</p>
                    <div class="service-stats">
                        <div class="service-stat">
                            <span class="service-stat-value" id="foireCount">0</span>
                            <span class="service-stat-label">demandes</span>
                        </div>
                        <div class="service-stat">
                            <span class="service-stat-value" id="foirePending">0</span>
                            <span class="service-stat-label">en attente</span>
                        </div>
                    </div>
                    <div class="service-footer">
                        <span class="service-badge blue">Appui à l'export</span>
                        <div class="service-arrow">
                            <span class="icon-arrow"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Couverture frais transport -->
        <div class="service-card" onclick="window.location.href='{{ route('admin.livre.transport.index') }}'">
            <div class="service-card-inner">
                <div class="service-icon-wrapper">
                    <div class="service-icon">
                        <span class="icon icon-transport"></span>
                    </div>
                </div>
                <div class="service-content">
                    <h3 class="service-title">Couverture frais transport</h3>
                    <p class="service-desc">Prise en charge des frais d'expédition</p>
                    <div class="service-stats">
                        <div class="service-stat">
                            <span class="service-stat-value" id="transportCount">0</span>
                            <span class="service-stat-label">demandes</span>
                        </div>
                        <div class="service-stat">
                            <span class="service-stat-value" id="transportPending">0</span>
                            <span class="service-stat-label">en attente</span>
                        </div>
                    </div>
                    <div class="service-footer">
                        <span class="service-badge green">Logistique</span>
                        <div class="service-arrow">
                            <span class="icon-arrow"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Matériaux exonérés TVA -->
        <div class="service-card" onclick="window.location.href='{{ route('admin.livre.tva.index') }}'">
            <div class="service-card-inner">
                <div class="service-icon-wrapper">
                    <div class="service-icon">
                        <span class="icon icon-tva"></span>
                    </div>
                </div>
                <div class="service-content">
                    <h3 class="service-title">Matériaux exonérés TVA</h3>
                    <p class="service-desc">Liste des matériaux d'impression exonérés</p>
                    <div class="service-stats">
                        <div class="service-stat">
                            <span class="service-stat-value" id="tvaCount">0</span>
                            <span class="service-stat-label">demandes</span>
                        </div>
                        <div class="service-stat">
                            <span class="service-stat-value" id="tvaPending">0</span>
                            <span class="service-stat-label">en attente</span>
                        </div>
                    </div>
                    <div class="service-footer">
                        <span class="service-badge amber">Fiscalité</span>
                        <div class="service-arrow">
                            <span class="icon-arrow"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Section - L3: Recent Activity Timeline -->
    <div class="activity-section">
        <div class="activity-header">
            <div class="activity-title">
                <span class="icon"></span>
                Activité récente
            </div>
            <div class="activity-filter">
                <button class="activity-filter-btn active" data-filter="all" onclick="filterActivity('all')">Tous</button>
                <button class="activity-filter-btn" data-filter="droits" onclick="filterActivity('droits')">Droits</button>
                <button class="activity-filter-btn" data-filter="foire" onclick="filterActivity('foire')">Foire</button>
                <button class="activity-filter-btn" data-filter="transport" onclick="filterActivity('transport')">Transport</button>
                <button class="activity-filter-btn" data-filter="tva" onclick="filterActivity('tva')">TVA</button>
            </div>
        </div>
        <div class="activity-list" id="activityList"></div>
    </div>
</div>

<script>
// Fake data for demonstration
let allActivities = [
    { id: 1, type: 'droits', action: 'Nouvelle demande déposée', entity: 'Éditions Cérès', detail: 'Dossier #LIV-DRO-20260012', time: '2026-04-13T10:30:00', status: 'pending' },
    { id: 2, type: 'foire', action: 'Demande validée', entity: 'Sud Éditions', detail: 'Participation Foire de Paris 2026', time: '2026-04-13T09:15:00', status: 'validated' },
    { id: 3, type: 'transport', action: 'En cours de traitement', entity: 'Nirvana Press', detail: 'Transport vers Belgique - 15 cartons', time: '2026-04-12T14:45:00', status: 'progress' },
    { id: 4, type: 'tva', action: 'Attestation délivrée', entity: 'Imprimerie Tunisienne', detail: 'Exonération papier offset', time: '2026-04-12T11:20:00', status: 'validated' },
    { id: 5, type: 'droits', action: 'Complément demandé', entity: 'Alif Publishing', detail: 'Documents manquants - CIN à fournir', time: '2026-04-12T09:00:00', status: 'pending' },
    { id: 6, type: 'foire', action: 'Demande rejetée', entity: 'MIAM', detail: 'Dossier incomplet', time: '2026-04-11T16:30:00', status: 'rejected' },
    { id: 7, type: 'transport', action: 'Demande approuvée', entity: 'Renaissance Books', detail: 'Transport vers USA - 25 cartons', time: '2026-04-11T14:00:00', status: 'validated' },
    { id: 8, type: 'tva', action: 'Nouvelle demande', entity: 'Étoile du Sahel', detail: 'Demande exonération encre', time: '2026-04-11T10:15:00', status: 'pending' },
    { id: 9, type: 'droits', action: 'Document validé', entity: 'Planeta Ediciones', detail: 'Contrat signé', time: '2026-04-10T15:45:00', status: 'validated' },
    { id: 10, type: 'foire', action: 'Inscription confirmée', entity: 'Dar Al-Kitab', detail: 'Foire de Francfort 2026', time: '2026-04-10T11:00:00', status: 'validated' }
];

// Stats per service
let serviceStats = {
    droits: { total: 24, pending: 5 },
    foire: { total: 18, pending: 3 },
    transport: { total: 12, pending: 2 },
    tva: { total: 15, pending: 4 }
};

// Format relative time
function formatRelativeTime(dateStr) {
    const date = new Date(dateStr);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);

    if (diffMins < 1) return "À l'instant";
    if (diffMins < 60) return `Il y a ${diffMins} min`;
    if (diffHours < 24) return `Il y a ${diffHours} h`;
    return `Il y a ${diffDays} j`;
}

// Get status label
function getStatusLabel(status) {
    const labels = {
        pending: 'En attente',
        progress: 'En cours',
        validated: 'Validé',
        rejected: 'Rejeté'
    };
    return labels[status] || status;
}

// Render activity list
function renderActivity(filter = 'all') {
    const container = document.getElementById('activityList');
    if (!container) return;

    let filtered = allActivities;
    if (filter !== 'all') {
        filtered = allActivities.filter(a => a.type === filter);
    }

    if (filtered.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon"></div>
                <div class="empty-state-title">Aucune activité</div>
                <div class="empty-state-subtitle">Aucune activité récente pour ce service</div>
            </div>
        `;
        return;
    }

    container.innerHTML = filtered.map(activity => `
        <div class="activity-item" data-type="${activity.type}">
            <div class="activity-icon ${activity.type}"></div>
            <div class="activity-content">
                <div class="activity-action">${activity.action}</div>
                <div class="activity-entity">${activity.entity}</div>
                <div class="activity-detail" style="font-size: 11px; color: var(--text3); margin-top: 2px;">${activity.detail}</div>
            </div>
            <div class="activity-time">${formatRelativeTime(activity.time)}</div>
        </div>
    `).join('');
}

// Filter activity
function filterActivity(type) {
    // Update active button
    document.querySelectorAll('.activity-filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelector(`.activity-filter-btn[data-filter="${type}"]`).classList.add('active');

    // Render filtered activities
    renderActivity(type);
}

// Update stats
function updateStats() {
    // Total demandes
    const total = Object.values(serviceStats).reduce((sum, s) => sum + s.total, 0);
    document.getElementById('totalDemandes').innerText = total;

    // Pending demandes
    const pending = Object.values(serviceStats).reduce((sum, s) => sum + s.pending, 0);
    document.getElementById('pendingDemandes').innerText = pending;

    // Validated demandes (mock data)
    const validated = Object.values(serviceStats).reduce((sum, s) => sum + (s.total - s.pending - 2), 0);
    document.getElementById('validatedDemandes').innerText = validated;

    // Rejected demandes (mock data)
    const rejected = Object.values(serviceStats).reduce((sum, s) => sum + 2, 0);
    document.getElementById('rejectedDemandes').innerText = rejected;

    // Service specific stats
    document.getElementById('droitsCount').innerText = serviceStats.droits.total;
    document.getElementById('droitsPending').innerText = serviceStats.droits.pending;
    document.getElementById('foireCount').innerText = serviceStats.foire.total;
    document.getElementById('foirePending').innerText = serviceStats.foire.pending;
    document.getElementById('transportCount').innerText = serviceStats.transport.total;
    document.getElementById('transportPending').innerText = serviceStats.transport.pending;
    document.getElementById('tvaCount').innerText = serviceStats.tva.total;
    document.getElementById('tvaPending').innerText = serviceStats.tva.pending;
}

// Filter by service from stats cards
function filterByService(type) {
    if (type === 'all') {
        renderActivity('all');
        document.querySelector('.activity-filter-btn[data-filter="all"]').classList.add('active');
        document.querySelectorAll('.activity-filter-btn').forEach(btn => {
            if (btn.dataset.filter !== 'all') btn.classList.remove('active');
        });
    } else if (type === 'pending') {
        // Show only pending activities
        const pendingActivities = allActivities.filter(a => a.status === 'pending');
        const container = document.getElementById('activityList');
        if (pendingActivities.length === 0) {
            container.innerHTML = '<div class="empty-state"><div class="empty-state-icon"></div><div class="empty-state-title">Aucune demande en attente</div></div>';
        } else {
            container.innerHTML = pendingActivities.map(activity => `
                <div class="activity-item">
                    <div class="activity-icon ${activity.type}"></div>
                    <div class="activity-content">
                        <div class="activity-action">${activity.action}</div>
                        <div class="activity-entity">${activity.entity}</div>
                    </div>
                    <div class="activity-time">${formatRelativeTime(activity.time)}</div>
                </div>
            `).join('');
        }
    } else if (type === 'validated') {
        const validatedActivities = allActivities.filter(a => a.status === 'validated');
        const container = document.getElementById('activityList');
        if (validatedActivities.length === 0) {
            container.innerHTML = '<div class="empty-state"><div class="empty-state-icon"></div><div class="empty-state-title">Aucune demande validée</div></div>';
        } else {
            container.innerHTML = validatedActivities.map(activity => `
                <div class="activity-item">
                    <div class="activity-icon ${activity.type}"></div>
                    <div class="activity-content">
                        <div class="activity-action">${activity.action}</div>
                        <div class="activity-entity">${activity.entity}</div>
                    </div>
                    <div class="activity-time">${formatRelativeTime(activity.time)}</div>
                </div>
            `).join('');
        }
    } else if (type === 'rejected') {
        const rejectedActivities = allActivities.filter(a => a.status === 'rejected');
        const container = document.getElementById('activityList');
        if (rejectedActivities.length === 0) {
            container.innerHTML = '<div class="empty-state"><div class="empty-state-icon"></div><div class="empty-state-title">Aucune demande rejetée</div></div>';
        } else {
            container.innerHTML = rejectedActivities.map(activity => `
                <div class="activity-item">
                    <div class="activity-icon ${activity.type}"></div>
                    <div class="activity-content">
                        <div class="activity-action">${activity.action}</div>
                        <div class="activity-entity">${activity.entity}</div>
                    </div>
                    <div class="activity-time">${formatRelativeTime(activity.time)}</div>
                </div>
            `).join('');
        }
    }
}

// Export dashboard data
function exportDashboardData() {
    const data = {
        stats: {
            total: document.getElementById('totalDemandes').innerText,
            pending: document.getElementById('pendingDemandes').innerText,
            validated: document.getElementById('validatedDemandes').innerText,
            rejected: document.getElementById('rejectedDemandes').innerText,
            byService: serviceStats
        },
        recentActivities: allActivities,
        exportDate: new Date().toISOString()
    };
    console.log('Export data:', data);
    alert('Export démarré - Vérifiez la console pour les données');
}

// Make functions global
window.filterActivity = filterActivity;
window.filterByService = filterByService;
window.exportDashboardData = exportDashboardData;

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateStats();
    renderActivity('all');
});
</script>


@endsection
