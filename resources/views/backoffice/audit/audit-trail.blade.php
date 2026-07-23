@extends('shared.layouts.backoffice')

@section('page-title', 'Audit Trail')

@section('content')
<style>
    /* Pagination Styles */
.audit-pagination {
    display: flex;
    justify-content: center;
    margin-top: 1.5rem;
}

.audit-pagination nav {
    display: inline-flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    justify-content: center;
}

.audit-pagination .pagination {
    display: flex;
    gap: 0.5rem;
    list-style: none;
    margin: 0;
    padding: 0;
    flex-wrap: wrap;
}

.audit-pagination .page-item {
    display: inline-block;
}

.audit-pagination .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 0.75rem;
    background: var(--bg3);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--text2);
    font-size: 0.8rem;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.audit-pagination .page-link:hover {
    background: var(--bg4);
    border-color: var(--gold);
    color: var(--gold);
}

.audit-pagination .page-item.active .page-link {
    background: var(--gold);
    border-color: var(--gold);
    color: #111;
}

.audit-pagination .page-item.disabled .page-link {
    opacity: 0.4;
    cursor: not-allowed;
}

.audit-pagination .page-item.disabled .page-link:hover {
    background: var(--bg3);
    border-color: var(--border);
    color: var(--text2);
}

/* Previous/Next specific styling */
.audit-pagination .page-item:first-child .page-link {
    border-radius: var(--radius-sm);
}

.audit-pagination .page-item:last-child .page-link {
    border-radius: var(--radius-sm);
}
    .audit-page {
        padding: 1.5rem 2rem;
    }

    .audit-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .audit-title h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 0.25rem;
    }

    .audit-title p {
        color: var(--text2);
        font-size: 0.85rem;
    }

    /* Stats Cards */
    .audit-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .audit-stat-card {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .audit-stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .audit-stat-icon.blue { background: rgba(59,130,246,0.15); }
    .audit-stat-icon.green { background: rgba(74,222,128,0.15); }
    .audit-stat-icon.red { background: rgba(248,113,113,0.15); }
    .audit-stat-icon.purple { background: rgba(167,139,250,0.15); }

    .audit-stat-info h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text);
        line-height: 1;
    }

    .audit-stat-info p {
        font-size: 0.7rem;
        color: var(--text2);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 0.25rem;
    }

    /* Filter Bar */
    .audit-filter-bar {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-end;
    }

    .audit-filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
        min-width: 140px;
    }

    .audit-filter-group label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text3);
    }

    .audit-filter-select,
    .audit-filter-input {
        padding: 0.5rem 0.75rem;
        background: var(--bg3);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        color: var(--text);
        font-size: 0.8rem;
        cursor: pointer;
    }

    .audit-filter-select:focus,
    .audit-filter-input:focus {
        outline: none;
        border-color: var(--gold);
    }

    .audit-search-group {
        flex: 1;
        min-width: 200px;
    }

    .audit-search-input {
        width: 100%;
        padding: 0.5rem 0.75rem;
        background: var(--bg3);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        color: var(--text);
        font-size: 0.8rem;
    }

    .audit-search-input:focus {
        outline: none;
        border-color: var(--gold);
    }

    .audit-filter-actions {
        display: flex;
        gap: 0.5rem;
    }

    /* Table */
    .audit-table-wrapper {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow-x: auto;
        margin-bottom: 1.5rem;
    }

    .audit-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8rem;
    }

    .audit-table th {
        padding: 0.9rem 1rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text3);
        border-bottom: 1px solid var(--border);
        background: var(--bg3);
    }

    .audit-table td {
        padding: 0.8rem 1rem;
        color: var(--text2);
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }

    .audit-table tr:hover td {
        background: var(--bg3);
    }

    /* Status Badges */
    .audit-status {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .audit-status.approved {
        background: rgba(74,222,128,0.15);
        color: #4ade80;
    }

    .audit-status.rejected {
        background: rgba(248,113,113,0.15);
        color: #f87171;
    }

    /* Pagination */
    .audit-pagination {
        display: flex;
        justify-content: center;
        margin-top: 1.5rem;
    }

    /* Top Validators Section */
    .audit-validators-section {
        margin-top: 2rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .audit-validators-card,
    .audit-recent-card {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
    }

    .audit-card-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border);
        background: var(--bg3);
    }

    .audit-card-header h3 {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .audit-card-body {
        padding: 1rem;
    }

    .validator-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.7rem 0;
        border-bottom: 1px solid var(--border);
    }

    .validator-item:last-child {
        border-bottom: none;
    }

    .validator-info {
        display: flex;
        flex-direction: column;
    }

    .validator-name {
        font-weight: 600;
        color: var(--text);
        font-size: 0.85rem;
    }

    .validator-email {
        font-size: 0.7rem;
        color: var(--text3);
    }

    .validator-stats {
        text-align: right;
    }

    .validator-total {
        font-weight: 700;
        color: var(--text);
    }

    .validator-breakdown {
        font-size: 0.7rem;
        color: var(--text3);
    }

    .validator-breakdown .approved { color: #4ade80; }
    .validator-breakdown .rejected { color: #f87171; }

    .recent-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.7rem 0;
        border-bottom: 1px solid var(--border);
    }

    .recent-info {
        flex: 1;
    }

    .recent-ref {
        font-weight: 600;
        color: var(--text);
        font-size: 0.8rem;
    }

    .recent-status {
        font-size: 0.7rem;
        margin-top: 0.2rem;
    }

    .recent-date {
        font-size: 0.7rem;
        color: var(--text3);
        text-align: right;
    }

    .btn-export {
        background: var(--gold);
        color: #111;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-export:hover {
        background: var(--gold2);
        transform: translateY(-1px);
    }

    .btn-reset {
        background: var(--bg3);
        border: 1px solid var(--border);
        color: var(--text2);
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-reset:hover {
        background: var(--bg4);
        color: var(--text);
    }

    .btn-refresh {
        background: var(--bg3);
        border: 1px solid var(--border);
        color: var(--text2);
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--text3);
    }

    @media (max-width: 768px) {
        .audit-page { padding: 1rem; }
        .audit-validators-section { grid-template-columns: 1fr; }
        .audit-filter-bar { flex-direction: column; align-items: stretch; }
        .audit-filter-group { width: 100%; }
    }
</style>

<div class="audit-page">
    <div class="audit-header">
        <div class="audit-title">
            <h1>📋 Audit Trail</h1>
            <p>Historique complet des validations et rejets de demandes</p>
        </div>
        <div>
            <button class="btn-export" onclick="auditTrailExportCSV()">
                📥 Exporter en CSV
            </button>
        </div>
    </div>

 <!-- Stats Cards -->
<div class="audit-stats-grid">
    <div class="audit-stat-card">
        <div class="audit-stat-icon blue">📊</div>
        <div class="audit-stat-info">
            <h3 id="statTotal">{{ number_format($auditTrailStats['total_validated'] ?? 0) }}</h3>
            <p>Total validations</p>
        </div>
    </div>
    <div class="audit-stat-card">
        <div class="audit-stat-icon green">✅</div>
        <div class="audit-stat-info">
            <h3 id="statApproved">{{ number_format($auditTrailStats['total_approved'] ?? 0) }}</h3>
            <p>Approuvées</p>
        </div>
    </div>
    <div class="audit-stat-card">
        <div class="audit-stat-icon red">❌</div>
        <div class="audit-stat-info">
            <h3 id="statRejected">{{ number_format($auditTrailStats['total_rejected'] ?? 0) }}</h3>
            <p>Rejetées</p>
        </div>
    </div>
    <div class="audit-stat-card">
        <div class="audit-stat-icon purple">👥</div>
        <div class="audit-stat-info">
            <h3 id="statValidators">{{ number_format($auditTrailStats['unique_validators'] ?? 0) }}</h3>
            <p>Validateurs uniques</p>
        </div>
    </div>
</div>


    <!-- Filter Bar -->
    <div class="audit-filter-bar">
        <div class="audit-filter-group">
            <label>Statut</label>
            <select class="audit-filter-select" id="auditFilterStatus">
                <option value="">Tous</option>
                <option value="validee">✅ Validée</option>
                <option value="rejetee">❌ Rejetée</option>
            </select>
        </div>
        <div class="audit-filter-group">
            <label>Département</label>
            <select class="audit-filter-select" id="auditFilterDepartment">
                <option value="">Tous</option>
                @foreach($auditTrailDepartments ?? [] as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name_fr }}</option>
                @endforeach
            </select>
        </div>
        <div class="audit-filter-group">
            <label>Date début</label>
            <input type="date" class="audit-filter-input" id="auditFilterDateFrom">
        </div>
        <div class="audit-filter-group">
            <label>Date fin</label>
            <input type="date" class="audit-filter-input" id="auditFilterDateTo">
        </div>
        <div class="audit-search-group">
            <label>Rechercher</label>
            <input type="text" class="audit-search-input" id="auditFilterSearch" placeholder="Réf., demandeur, validateur...">
        </div>
        <div class="audit-filter-actions">
            <button class="btn-refresh" onclick="auditTrailResetFilters()">Reset</button>
        </div>
    </div>

    <!-- Main Table -->
    <div class="audit-table-wrapper">
        <table class="audit-table">
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Demandeur</th>
                    <th>Type de demande</th>
                    <th>Validateur</th>
                    <th>Statut</th>
                    <th>Date validation</th>
                    <th>Département</th>
                </tr>
            </thead>
           <tbody id="auditTrailTableBody">
    @if(isset($auditTrailRecords) && $auditTrailRecords->count() > 0)
        @foreach($auditTrailRecords as $record)
        <tr>
            <td><strong>{{ $record->reference }}</strong></td>
            <td>{{ $record->demandeur_prenom }} {{ $record->demandeur_nom }}</td>
            <td>{{ $record->demande_type ?? '-' }}</td>
            <td>{{ $record->validateur_prenom }} {{ $record->validateur_nom }}</td>
            <td>
                <span class="audit-status {{ $record->statut === 'validee' ? 'approved' : 'rejected' }}">
                    {{ $record->statut === 'validee' ? '✅ Validée' : ($record->statut === 'rejetee' ? '❌ Rejetée' : $record->statut) }}
                </span>
            </td>
            <td>{{ $record->validated_at ? \Carbon\Carbon::parse($record->validated_at)->format('d/m/Y H:i') : '-' }}</td>
            <td>{{ $record->department_name ?? '-' }}</td>
        </tr>
        @endforeach
    @else
        <tr>
            <td colspan="7" class="empty-state">Aucune validation trouvée</td>
        </tr>
    @endif
</tbody>
        </table>
    </div>

<!-- Pagination -->
<div class="audit-pagination" id="auditTrailPagination">
    @if(isset($auditTrailRecords) && $auditTrailRecords)
        {{ $auditTrailRecords->links() }}
    @endif
</div>

    <!-- Top Validators & Recent Activity -->
    <div class="audit-validators-section">
        <div class="audit-validators-card">
            <div class="audit-card-header">
                <h3>🏆 Top 10 validateurs</h3>
            </div>
            <div class="audit-card-body" id="auditTopValidators">
                @foreach($auditTrailTopValidators ?? [] as $validator)
                <div class="validator-item">
                    <div class="validator-info">
                        <span class="validator-name">{{ $validator->prenom }} {{ $validator->nom }}</span>
                        <span class="validator-email">{{ $validator->email }}</span>
                    </div>
                    <div class="validator-stats">
                        <div class="validator-total">{{ $validator->total_validations }} validations</div>
                        <div class="validator-breakdown">
                            <span class="approved">✅ {{ $validator->approved_count }}</span>
                            <span class="rejected">❌ {{ $validator->rejected_count }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="audit-recent-card">
            <div class="audit-card-header">
                <h3>🕐 Dernières activités (7 jours)</h3>
            </div>
            <div class="audit-card-body" id="auditRecentActivity">
                @foreach($auditTrailRecentActivity ?? [] as $activity)
                <div class="recent-item">
                    <div class="recent-info">
                        <div class="recent-ref">{{ $activity->reference }}</div>
                        <div class="recent-status">
                            <span class="audit-status {{ $activity->statut === 'validee' ? 'approved' : 'rejected' }}" style="font-size: 0.65rem;">
                                {{ $activity->statut === 'validee' ? 'Validée' : 'Rejetée' }}
                            </span>
                            <span style="font-size: 0.7rem; margin-left: 0.5rem;">par {{ $activity->validateur_prenom }} {{ $activity->validateur_nom }}</span>
                        </div>
                    </div>
                    <div class="recent-date">
                        {{ \Carbon\Carbon::parse($activity->validated_at)->diffForHumans() }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
let auditTrailCurrentFilters = {};

function auditTrailLoadData() {
    const filters = {
        statut: document.getElementById('auditFilterStatus').value,
        department_id: document.getElementById('auditFilterDepartment').value,
        date_from: document.getElementById('auditFilterDateFrom').value,
        date_to: document.getElementById('auditFilterDateTo').value,
        search: document.getElementById('auditFilterSearch').value,
    };

    const params = new URLSearchParams(filters);
    fetch(`{{ route('admin.audit-trail.data') }}?${params.toString()}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update table
            const tbody = document.getElementById('auditTrailTableBody');
            if (data.data.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="empty-state">Aucune validation trouvée</td></tr>';
            } else {
                tbody.innerHTML = data.data.data.map(record => `
                    <tr>
                        <td><strong>${escapeHtml(record.reference)}</strong></td>
                        <td>${escapeHtml(record.demandeur_prenom)} ${escapeHtml(record.demandeur_nom)}</td>
                        <td>${escapeHtml(record.demande_type || '-')}</td>
                        <td>${escapeHtml(record.validateur_prenom)} ${escapeHtml(record.validateur_nom)}</td>
                        <td>
                            <span class="audit-status ${record.statut === 'validee' ? 'approved' : 'rejected'}">
                                ${record.statut === 'validee' ? '✅ Validée' : (record.statut === 'rejetee' ? '❌ Rejetée' : record.statut)}
                            </span>
                        </td>
                        <td>${record.validated_at ? new Date(record.validated_at).toLocaleString('fr-FR') : '-'}</td>
                        <td>${escapeHtml(record.department_name || '-')}</td>
                    </tr>
                `).join('');
            }

            // Update pagination with styled buttons
            const pagination = document.getElementById('auditTrailPagination');
            if (data.data.links) {
                const links = data.data.links;
                let paginationHtml = '<nav><ul class="pagination">';

                links.forEach(link => {
                    const isActive = link.active === true;
                    const isDisabled = link.url === null;
                    let displayLabel = link.label;

                    // Translate pagination labels
                    if (link.label === '&laquo; Previous') displayLabel = '‹ Précédent';
                    if (link.label === 'Next &raquo;') displayLabel = 'Suivant ›';

                    paginationHtml += `
                        <li class="page-item ${isActive ? 'active' : ''} ${isDisabled ? 'disabled' : ''}">
                            ${!isDisabled
                                ? `<button class="page-link" onclick="auditTrailGoToPage('${link.url}')">${displayLabel}</button>`
                                : `<span class="page-link">${displayLabel}</span>`
                            }
                        </li>
                    `;
                });

                paginationHtml += '</ul></nav>';
                pagination.innerHTML = paginationHtml;
            }

            // Update stats
            if (data.stats) {
                document.getElementById('statTotal').textContent = data.stats.total_validated.toLocaleString();
                document.getElementById('statApproved').textContent = data.stats.total_approved.toLocaleString();
                document.getElementById('statRejected').textContent = data.stats.total_rejected.toLocaleString();
                document.getElementById('statValidators').textContent = data.stats.unique_validators.toLocaleString();
            }

            // Update top validators
            if (data.top_validators) {
                const validatorContainer = document.getElementById('auditTopValidators');
                validatorContainer.innerHTML = data.top_validators.map(v => `
                    <div class="validator-item">
                        <div class="validator-info">
                            <span class="validator-name">${escapeHtml(v.prenom)} ${escapeHtml(v.nom)}</span>
                            <span class="validator-email">${escapeHtml(v.email)}</span>
                        </div>
                        <div class="validator-stats">
                            <div class="validator-total">${v.total_validations} validations</div>
                            <div class="validator-breakdown">
                                <span class="approved">✅ ${v.approved_count}</span>
                                <span class="rejected">❌ ${v.rejected_count}</span>
                            </div>
                        </div>
                    </div>
                `).join('');
            }

            // Update recent activity
            if (data.recent_activity) {
                const recentContainer = document.getElementById('auditRecentActivity');
                recentContainer.innerHTML = data.recent_activity.map(a => `
                    <div class="recent-item">
                        <div class="recent-info">
                            <div class="recent-ref">${escapeHtml(a.reference)}</div>
                            <div class="recent-status">
                                <span class="audit-status ${a.statut === 'validee' ? 'approved' : 'rejected'}" style="font-size: 0.65rem;">
                                    ${a.statut === 'validee' ? 'Validée' : 'Rejetée'}
                                </span>
                                <span style="font-size: 0.7rem; margin-left: 0.5rem;">par ${escapeHtml(a.validateur_prenom)} ${escapeHtml(a.validateur_nom)}</span>
                            </div>
                        </div>
                        <div class="recent-date">
                            ${new Date(a.validated_at).toLocaleDateString('fr-FR')}
                        </div>
                    </div>
                `).join('');
            }
        }
    })
    .catch(error => console.error('Error loading audit trail:', error));
}

function auditTrailGoToPage(url) {
    const params = new URLSearchParams(url.split('?')[1]);
    document.getElementById('auditFilterStatus').value = params.get('statut') || '';
    document.getElementById('auditFilterDepartment').value = params.get('department_id') || '';
    document.getElementById('auditFilterDateFrom').value = params.get('date_from') || '';
    document.getElementById('auditFilterDateTo').value = params.get('date_to') || '';
    document.getElementById('auditFilterSearch').value = params.get('search') || '';
    auditTrailLoadData();
}

function auditTrailResetFilters() {
    document.getElementById('auditFilterStatus').value = '';
    document.getElementById('auditFilterDepartment').value = '';
    document.getElementById('auditFilterDateFrom').value = '';
    document.getElementById('auditFilterDateTo').value = '';
    document.getElementById('auditFilterSearch').value = '';
    auditTrailLoadData();
}

function auditTrailExportCSV() {
    const params = new URLSearchParams({
        statut: document.getElementById('auditFilterStatus').value,
        department_id: document.getElementById('auditFilterDepartment').value,
        date_from: document.getElementById('auditFilterDateFrom').value,
        date_to: document.getElementById('auditFilterDateTo').value,
        search: document.getElementById('auditFilterSearch').value,
    });
    window.location.href = `{{ route('admin.audit-trail.export') }}?${params.toString()}`;
}

function escapeHtml(str) {
    if (!str) return '';
    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

// Add event listeners
document.addEventListener('DOMContentLoaded', function() {
    const filters = ['auditFilterStatus', 'auditFilterDepartment', 'auditFilterDateFrom', 'auditFilterDateTo', 'auditFilterSearch'];
    filters.forEach(id => {
        document.getElementById(id)?.addEventListener('change', auditTrailLoadData);
        document.getElementById(id)?.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') auditTrailLoadData();
        });
    });
});
</script>
@endsection
