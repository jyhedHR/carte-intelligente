/* ============================================
   DIRECTION DU LIVRE - COMPLETE JAVASCRIPT
   Version: 4.0 - Compact Health Scores & Enhanced Timeline
   ============================================ */

// ============================================
// GLOBAL VARIABLES
// ============================================

let currentDate = new Date();
let selectedCalendarDate = new Date().toISOString().split('T')[0];
let calendarNotes = JSON.parse(localStorage.getItem('calendarNotes') || '{}');
let activeTooltip = null;

// ============================================
// HELPER FUNCTIONS
// ============================================

function formatDate(dateString) {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('fr-FR');
}

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
    if (diffDays === 1) return "Hier";
    return `Il y a ${diffDays} j`;
}

function showToast(message, type = 'success') {
    let toast = document.getElementById('livre-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'livre-toast';
        toast.style.cssText = `
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            padding: 12px 24px;
            border-radius: 8px;
            color: white;
            font-size: 13px;
            font-weight: 500;
            z-index: 1100;
            animation: fadeInUp 0.3s ease;
            background: ${type === 'success' ? '#4ade80' : type === 'error' ? '#f87171' : '#fbbf24'};
        `;
        document.body.appendChild(toast);
        const style = document.createElement('style');
        style.textContent = `@keyframes fadeInUp { from { opacity: 0; transform: translate(-50%, 20px); } to { opacity: 1; transform: translate(-50%, 0); } }`;
        document.head.appendChild(style);
    }
    toast.textContent = message;
    toast.style.display = 'block';
    setTimeout(() => { toast.style.display = 'none'; }, 3000);
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ============================================
// MODAL FUNCTIONS
// ============================================

function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.add('show');
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.remove('show');
}

// ============================================
// L1: SMART DOCUMENT AUTO-FILL WITH AI DETECTION
// ============================================

function checkExistingDocuments(publisherName) {
    const panel = document.getElementById('smartDocPanel');
    const list = document.getElementById('smartDocList');
    if (!panel || !list) return;

    list.innerHTML = `
        <div class="ai-scanning-container">
            <div class="ai-scanning-animation">
                <div class="scan-line"></div>
                <div style="font-size: 12px; color: var(--gold); margin-top: 12px; text-align: center; font-weight: 600;">AI Scanning...</div>
                <div style="font-size: 10px; color: var(--text3); text-align: center; margin-top: 4px;">Détection intelligente des documents existants</div>
            </div>
        </div>
    `;
    panel.style.display = 'block';

    setTimeout(() => {
        const aiDetectedDocs = [
            { name: 'CIN', date: '2025-12-15', valid: true, confidence: 99, status: 'Valide' },
            { name: 'Matricule Fiscal', date: '2025-10-20', valid: true, confidence: 98, status: 'Valide' },
            { name: 'Registre de Commerce', date: '2024-11-10', valid: false, confidence: 97, status: 'Expiré le 10.11.2025' }
        ];
        list.innerHTML = `
            <div style="margin-bottom: 8px;"><span style="font-size: 11px; color: var(--gold); font-weight: 600;">🔍 AI Confidence</span></div>
            ${aiDetectedDocs.map(doc => `
                <div class="smart-doc-item ai-detected" onclick="selectDocument(this, '${doc.name}')">
                    <div class="smart-doc-check"></div>
                    <div style="flex: 1;">
                        <div style="font-size: 13px; font-weight: 500; display: flex; justify-content: space-between;">
                            <span>${doc.name}</span>
                            <span style="font-size: 10px; color: var(--gold);">🧠 ${doc.confidence}%</span>
                        </div>
                        <div style="font-size: 10px; color: var(--text3);">Scanné le ${doc.date}</div>
                        <div style="font-size: 10px; color: ${doc.valid ? '#4ade80' : '#f87171'}; margin-top: 2px; font-weight: 600;">${doc.status}</div>
                    </div>
                    ${!doc.valid ? '<span style="font-size: 10px; color: var(--red);">⚠️ Expire</span>' : '<span style="font-size: 10px; color: #4ade80;">✓</span>'}
                </div>
            `).join('')}
            <div style="margin-top: 12px; padding: 8px 12px; background: rgba(74, 222, 128, 0.1); border-radius: 6px; font-size: 10px; color: #4ade80; text-align: center; font-weight: 600;">
                ✅ AI successfully scanned 3 documents
            </div>
        `;
    }, 1200);
}

function selectDocument(element, docName) {
    document.querySelectorAll('.smart-doc-item').forEach(item => item.classList.remove('active'));
    element.classList.add('active');
    showToast(`${docName} sélectionné pour réutilisation`);
}

// ============================================
// L2: BULK APPROVE FUNCTIONS
// ============================================

let selectedItems = [];

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.row-select');
    if (!selectAll) return;
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
    const selectAll = document.getElementById('selectAll');
    if (selectAll) selectAll.checked = document.querySelectorAll('.row-select').length === selectedItems.length;
}

function updateBulkBar() {
    const bulkBar = document.getElementById('bulkBar');
    const selectedCount = document.getElementById('selectedCount');
    if (!bulkBar) return;
    if (selectedItems.length > 0) {
        bulkBar.classList.add('active');
        if (selectedCount) selectedCount.innerText = selectedItems.length;
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

// ============================================
// L3: COMPACT CALENDAR FUNCTIONS
// ============================================

function getMonthNameShort(month) {
    const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
    return months[month];
}

function getEventsForDate(dateStr, filteredDemandes) {
    if (!filteredDemandes) return [];
    const dossiersOnDate = filteredDemandes.filter(d => d.dateDepot === dateStr);
    const events = [];
    dossiersOnDate.forEach(d => {
        let urgency = 'normal';
        if (d.statut === 'pending') urgency = 'urgent';
        else if (d.statut === 'progress') urgency = 'warning';
        events.push({ title: d.nomEditeur, status: d.statut, urgency: urgency, numero: d.numero });
    });
    return events;
}

function renderCompactCalendar(demandesData) {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const filterYear = document.getElementById('calendarYearFilter')?.value;
    const filterMonth = document.getElementById('calendarMonthFilter')?.value;
    const filterStatus = document.getElementById('calendarStatusFilter')?.value;

    let filteredDemandes = [...(demandesData || window.demandes || [])];
    if (filterYear && filterYear !== 'all') filteredDemandes = filteredDemandes.filter(d => new Date(d.dateDepot).getFullYear() == filterYear);
    if (filterMonth && filterMonth !== 'all') filteredDemandes = filteredDemandes.filter(d => new Date(d.dateDepot).getMonth() == filterMonth);
    if (filterStatus && filterStatus !== 'all') filteredDemandes = filteredDemandes.filter(d => d.statut === filterStatus);

    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const today = new Date();
    const startDay = firstDay.getDay();

    const monthDisplay = document.getElementById('calendarMonthDisplay');
    if (monthDisplay) monthDisplay.innerHTML = `${getMonthNameShort(month)} ${year}`;

    const grid = document.getElementById('calendarGridCompact');
    if (!grid) return;

    let calendarHtml = '';
    const daysFromPrevMonth = startDay === 0 ? 6 : startDay - 1;
    const prevMonthDays = new Date(year, month, 0).getDate();

    for (let i = daysFromPrevMonth - 1; i >= 0; i--) {
        const day = prevMonthDays - i;
        calendarHtml += `<div class="calendar-cell-compact other-month"><div class="cell-date-compact">${day}</div></div>`;
    }

    for (let day = 1; day <= lastDay.getDate(); day++) {
        const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
        const events = getEventsForDate(dateStr, filteredDemandes);
        const hasNotes = calendarNotes[dateStr] && calendarNotes[dateStr].length > 0;
        const isToday = year === today.getFullYear() && month === today.getMonth() && day === today.getDate();
        const isSelected = dateStr === selectedCalendarDate;

        let eventsHtml = '';
        if (events.length > 0) {
            eventsHtml = `<div class="event-dots-compact">`;
            events.forEach(event => {
                eventsHtml += `<div class="event-dot-compact ${event.urgency}" title="${event.title} - ${event.status}"></div>`;
            });
            eventsHtml += `</div>`;
        }
        if (hasNotes) eventsHtml += `<div class="event-dots-compact"><div class="event-dot-compact note" title="📝 Note(s)"></div></div>`;

        let cellClass = 'calendar-cell-compact';
        if (isToday) cellClass += ' today';
        if (isSelected) cellClass += ' selected';

        calendarHtml += `
            <div class="${cellClass}" onclick="goToDateFromCalendar('${dateStr}')">
                <div class="cell-date-compact">${day}</div>
                ${eventsHtml}
            </div>
        `;
    }
    grid.innerHTML = calendarHtml;
    renderNotesForDate(selectedCalendarDate);
}

function goToDateFromCalendar(dateStr) {
    selectedCalendarDate = dateStr;
    const listView = document.getElementById('listView');
    const calendarView = document.getElementById('calendarView');
    const listBtn = document.querySelector('.calendar-view-btn[onclick*="list"]');
    const calendarBtn = document.querySelector('.calendar-view-btn[onclick*="calendar"]');
    if (listView && calendarView) {
        listView.style.display = 'block';
        calendarView.style.display = 'none';
        if (listBtn) listBtn.classList.add('active');
        if (calendarBtn) calendarBtn.classList.remove('active');
    }
    const searchInput = document.getElementById('searchInput');
    if (searchInput) searchInput.value = dateStr;
    if (typeof window.renderTable === 'function') window.renderTable();
    showToast(`Affichage des demandes du ${new Date(dateStr).toLocaleDateString('fr-FR')}`);
}

function selectCalendarDate(dateStr) {
    selectedCalendarDate = dateStr;
    if (typeof window.renderCompactCalendar === 'function') window.renderCompactCalendar(window.demandes);
    renderNotesForDate(dateStr);
}

function prevMonthCompact() { currentDate.setMonth(currentDate.getMonth() - 1); if (typeof window.renderCompactCalendar === 'function') window.renderCompactCalendar(window.demandes); }
function nextMonthCompact() { currentDate.setMonth(currentDate.getMonth() + 1); if (typeof window.renderCompactCalendar === 'function') window.renderCompactCalendar(window.demandes); }
function goToTodayCompact() { currentDate = new Date(); selectedCalendarDate = currentDate.toISOString().split('T')[0]; if (typeof window.renderCompactCalendar === 'function') window.renderCompactCalendar(window.demandes); }
function applyCalendarFilters() { if (typeof window.renderCompactCalendar === 'function') window.renderCompactCalendar(window.demandes); showToast('Filtres appliqués'); }
function resetCalendarFilters() {
    const yearFilter = document.getElementById('calendarYearFilter');
    const monthFilter = document.getElementById('calendarMonthFilter');
    const statusFilter = document.getElementById('calendarStatusFilter');
    if (yearFilter) yearFilter.value = 'all';
    if (monthFilter) monthFilter.value = 'all';
    if (statusFilter) statusFilter.value = 'all';
    if (typeof window.renderCompactCalendar === 'function') window.renderCompactCalendar(window.demandes);
    showToast('Filtres réinitialisés');
}
function goToToday() { currentDate = new Date(); selectedCalendarDate = currentDate.toISOString().split('T')[0]; if (typeof window.renderCompactCalendar === 'function') window.renderCompactCalendar(window.demandes); }

function populateYearFilter(demandesData) {
    const yearSelect = document.getElementById('calendarYearFilter');
    if (!yearSelect) return;
    yearSelect.innerHTML = '<option value="all">Toutes</option>';
    const years = [...new Set((demandesData || []).map(d => new Date(d.dateDepot).getFullYear()))];
    years.sort().forEach(year => {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
    });
}

// ============================================
// NOTES SYSTEM
// ============================================

function renderNotesForDate(dateStr) {
    const notes = calendarNotes[dateStr] || [];
    const formattedDate = new Date(dateStr).toLocaleDateString('fr-FR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    const sidebar = document.getElementById('calendarSidebar');
    if (!sidebar) return;
    const notesHtml = `
        <div class="notes-panel">
            <div class="notes-header">
                <div class="notes-title">Notes</div>
                <div class="notes-date">${formattedDate}</div>
            </div>
            <div class="notes-list" id="notesList">
                ${notes.length === 0 ? '<div class="note-empty"><p>Aucune note pour cette date</p></div>' : notes.map((note, index) => `
                    <div class="note-item">
                        <div class="note-header">
                            <div class="note-time">${note.time || new Date().toLocaleTimeString()}</div>
                            <div class="note-actions">
                                <button class="note-action-btn" onclick="editNote('${dateStr}', ${index})">✏️</button>
                                <button class="note-action-btn" onclick="deleteNote('${dateStr}', ${index})">🗑️</button>
                            </div>
                        </div>
                        <div class="note-content">${escapeHtml(note.content)}</div>
                    </div>
                `).join('')}
            </div>
            <div class="add-note-form">
                <textarea class="add-note-input" id="newNoteContent" rows="2" placeholder="Écrire une note..."></textarea>
                <button class="add-note-btn" onclick="addNote('${dateStr}')">+ Ajouter une note</button>
            </div>
        </div>
    `;
    sidebar.innerHTML = notesHtml;
}

function addNote(dateStr) {
    const content = document.getElementById('newNoteContent')?.value.trim();
    if (!content) { showToast('Veuillez écrire une note', 'warning'); return; }
    if (!calendarNotes[dateStr]) calendarNotes[dateStr] = [];
    calendarNotes[dateStr].push({ content: content, time: new Date().toLocaleTimeString(), date: new Date().toISOString() });
    localStorage.setItem('calendarNotes', JSON.stringify(calendarNotes));
    renderNotesForDate(dateStr);
    if (typeof window.renderCompactCalendar === 'function') window.renderCompactCalendar(window.demandes);
    showToast('Note ajoutée avec succès');
}

function deleteNote(dateStr, index) {
    if (confirm('Supprimer cette note ?')) {
        calendarNotes[dateStr].splice(index, 1);
        if (calendarNotes[dateStr].length === 0) delete calendarNotes[dateStr];
        localStorage.setItem('calendarNotes', JSON.stringify(calendarNotes));
        renderNotesForDate(dateStr);
        if (typeof window.renderCompactCalendar === 'function') window.renderCompactCalendar(window.demandes);
        showToast('Note supprimée');
    }
}

function editNote(dateStr, index) {
    const newContent = prompt('Modifier la note:', calendarNotes[dateStr][index].content);
    if (newContent && newContent.trim()) {
        calendarNotes[dateStr][index].content = newContent.trim();
        calendarNotes[dateStr][index].time = new Date().toLocaleTimeString();
        localStorage.setItem('calendarNotes', JSON.stringify(calendarNotes));
        renderNotesForDate(dateStr);
        showToast('Note modifiée');
    }
}

// ============================================
// L4: REJECTION TEMPLATES
// ============================================

let currentRejectId = null;
const rejectionTemplatesList = [
    { title: 'Documents incomplets', desc: 'Manque attestation CNSS, registre de commerce...' },
    { title: 'Hors délai', desc: 'Demande déposée après la date limite' },
    { title: 'Informations erronées', desc: 'Matricule fiscal ou CIN invalide' },
    { title: 'Format non conforme', desc: 'Documents au format incorrect ou illisibles' },
    { title: 'Dossier déjà traité', desc: 'Demande déjà validée précédemment' }
];

function openRejectModal(id) {
    currentRejectId = id;
    const templatesHtml = rejectionTemplatesList.map(t => `
        <div class="rejection-template-card" onclick="selectRejectionTemplate(this, '${t.desc.replace(/'/g, "\\'")}')">
            <div class="rejection-template-title">${t.title}</div>
            <div class="rejection-template-desc">${t.desc}</div>
        </div>
    `).join('');
    const templatesContainer = document.getElementById('rejectionTemplates');
    if (templatesContainer) templatesContainer.innerHTML = templatesHtml;
    const reasonField = document.getElementById('rejectionReason');
    if (reasonField) reasonField.value = '';
    openModal('rejectModal');
}

function selectRejectionTemplate(element, reason) {
    document.querySelectorAll('.rejection-template-card').forEach(card => card.classList.remove('selected'));
    element.classList.add('selected');
    document.getElementById('rejectionReason').value = reason;
}

function confirmReject() {
    const reason = document.getElementById('rejectionReason').value;
    if (!reason) { showToast('Veuillez saisir un motif de rejet', 'error'); return; }
    if (typeof window.rejectDemandeCallback === 'function') window.rejectDemandeCallback(currentRejectId, reason);
    closeModal('rejectModal');
}

// ============================================
// L5: DOCUMENT EXPIRY WARNING
// ============================================

function checkDocumentExpiry() {
    const expiryWarning = document.getElementById('expiryWarning');
    if (expiryWarning) expiryWarning.style.display = 'block';
}

// ============================================
// F1: PUBLISHER HEALTH SCORE - COMPACT SCROLLABLE
// ============================================

function renderHealthScores(demandesData) {
    const container = document.getElementById('healthScoreGrid');
    if (!container) return;

    const data = demandesData || window.demandes || [];
    const publisherMap = new Map();
    data.forEach(d => {
        if (!publisherMap.has(d.nomEditeur)) {
            publisherMap.set(d.nomEditeur, {
                score: d.healthScore || 50,
                totalDemandes: 1,
                validatedCount: d.statut === 'validated' ? 1 : 0,
                lastActivity: d.dateDepot
            });
        } else {
            const existing = publisherMap.get(d.nomEditeur);
            existing.totalDemandes++;
            if (d.statut === 'validated') existing.validatedCount++;
            existing.score = Math.round((existing.score + (d.healthScore || 50)) / 2);
            if (d.dateDepot > existing.lastActivity) existing.lastActivity = d.dateDepot;
            publisherMap.set(d.nomEditeur, existing);
        }
    });

    const publishers = Array.from(publisherMap.entries()).map(([name, data]) => ({
        name: name,
        score: data.score,
        successRate: Math.round((data.validatedCount / data.totalDemandes) * 100),
        totalDemandes: data.totalDemandes
    })).sort((a, b) => b.score - a.score);

    const topPublishers = publishers.slice(0, 8);
    const countElement = document.getElementById('publisherCount');
    if (countElement) countElement.innerText = `${publishers.length} éditeur${publishers.length > 1 ? 's' : ''}`;

    container.innerHTML = `
        <div class="health-scroll-container" style="overflow-x: auto; white-space: nowrap; padding-bottom: 8px;">
            <div style="display: inline-flex; gap: 12px;">
                ${topPublishers.map(p => {
                    let scoreClass = p.score >= 80 ? 'high' : p.score >= 60 ? 'medium' : 'low';
                    let statusIcon = p.score >= 80 ? '🟢' : p.score >= 60 ? '🟡' : '🔴';
                    return `
                        <div class="health-card-compact ${scoreClass}" onclick="filterByPublisher('${p.name.replace(/'/g, "\\'")}')" style="cursor:pointer; display: inline-block; min-width: 140px; background: var(--bg3); border-radius: var(--radius-sm); padding: 12px; margin-right: 4px; transition: all 0.2s; border: 1px solid var(--border);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <span style="font-size: 13px; font-weight: 600; color: var(--text); white-space: normal; word-break: break-word;">${p.name.substring(0, 20)}${p.name.length > 20 ? '...' : ''}</span>
                                <span style="font-size: 14px;">${statusIcon}</span>
                            </div>
                            <div style="display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 6px;">
                                <span style="font-size: 22px; font-weight: 800; color: var(--gold);">${p.score}</span>
                                <span style="font-size: 10px; color: var(--text3);">${p.totalDemandes} dossiers</span>
                            </div>
                            <div class="health-score-bar" style="height: 4px; background: var(--bg4); border-radius: 2px; overflow: hidden; margin-bottom: 6px;">
                                <div class="health-score-fill" style="width: ${p.score}%; height: 100%; background: ${p.score >= 80 ? '#4ade80' : p.score >= 60 ? '#fbbf24' : '#f87171'}; border-radius: 2px;"></div>
                            </div>
                            <div style="font-size: 10px; color: var(--text3);">✅ ${p.successRate}% succès</div>
                        </div>
                    `;
                }).join('')}
            </div>
        </div>
        ${publishers.length > 8 ? `<div style="text-align: center; margin-top: 12px; font-size: 11px; color: var(--text3);">+ ${publishers.length - 8} autres éditeurs</div>` : ''}
    `;
}

function filterByPublisher(publisherName) {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) searchInput.value = publisherName;
    if (typeof window.renderTable === 'function') window.renderTable();
    if (typeof window.switchView === 'function') window.switchView('list');
    showToast(`Filtré par: ${publisherName}`);
}

// ============================================
// F9: AI INSIGHT PANEL
// ============================================

function renderAIInsights(demandesData) {
    const container = document.getElementById('aiInsightGrid');
    if (!container) return;

    const data = demandesData || window.demandes || [];
    const pendingCount = data.filter(d => d.statut === 'pending').length;
    const urgentCount = data.filter(d => (d.slaDays || 30) <= 2 && d.statut !== 'validated').length;
    const lowHealthCount = data.filter(d => (d.healthScore || 50) < 60).length;
    const validatedCount = data.filter(d => d.statut === 'validated').length;
    const successRate = data.length > 0 ? Math.round((validatedCount / data.length) * 100) : 0;

    container.innerHTML = `
        <div class="insight-card" onclick="filterByStatus('pending')" style="cursor:pointer;">
            <div class="insight-icon urgent-icon"></div>
            <div class="insight-content">
                <div class="insight-value">${pendingCount}</div>
                <div class="insight-label">Demandes en attente</div>
                <div class="insight-action">Cliquez pour voir →</div>
            </div>
        </div>
        <div class="insight-card warning" onclick="showUrgentDossiers()" style="cursor:pointer;">
            <div class="insight-icon warning-icon"></div>
            <div class="insight-content">
                <div class="insight-value">${urgentCount}</div>
                <div class="insight-label">Dossiers urgents (SLA ≤ 2j)</div>
                <div class="insight-action">⚠️ Traitement prioritaire →</div>
            </div>
        </div>
        <div class="insight-card" onclick="showLowHealthPublishers()" style="cursor:pointer;">
            <div class="insight-icon health-icon"></div>
            <div class="insight-content">
                <div class="insight-value">${lowHealthCount}</div>
                <div class="insight-label">Éditeurs à risque (score < 60)</div>
                <div class="insight-action">📊 Analyser →</div>
            </div>
        </div>
        <div class="insight-card" onclick="quickActionExport()" style="cursor:pointer;">
            <div class="insight-icon export-icon"></div>
            <div class="insight-content">
                <div class="insight-value">${successRate}%</div>
                <div class="insight-label">Taux de validation</div>
                <div class="insight-action">📈 vs 82% mois dernier ↑</div>
            </div>
        </div>
    `;

    const timeElement = document.getElementById('aiLastUpdate');
    if (timeElement) timeElement.innerText = new Date().toLocaleTimeString();
}

function showUrgentDossiers() {
    const data = window.demandes || [];
    const urgentIds = data.filter(d => (d.slaDays || 30) <= 2 && d.statut !== 'validated').map(d => d.id);
    if (urgentIds.length > 0) {
        if (typeof window.selectedItems !== 'undefined') window.selectedItems = urgentIds;
        if (typeof window.updateBulkBar === 'function') window.updateBulkBar();
        showToast(`${urgentIds.length} dossier(s) urgent(s) sélectionné(s) - Traitement prioritaire recommandé`, 'warning');
        if (typeof window.switchView === 'function') window.switchView('list');
    } else {
        showToast('Aucun dossier urgent', 'info');
    }
}

function showLowHealthPublishers() {
    const data = window.demandes || [];
    const lowHealthPublishers = [...new Set(data.filter(d => (d.healthScore || 50) < 60).map(d => d.nomEditeur))];
    if (lowHealthPublishers.length > 0) {
        showToast(`Éditeurs à risque: ${lowHealthPublishers.join(', ')} - Score santé bas`, 'warning');
    } else {
        showToast('Aucun éditeur à risque', 'info');
    }
}

// ============================================
// F4: SLA BREACH ALERTS
// ============================================

function renderSLAAlerts(demandesData) {
    const container = document.getElementById('slaAlertsPanel');
    if (!container) return;

    const data = demandesData || window.demandes || [];
    const urgentDossiers = data.filter(d => (d.slaDays || 30) <= 2 && d.statut !== 'validated' && d.statut !== 'rejected');
    const warningDossiers = data.filter(d => (d.slaDays || 30) <= 5 && (d.slaDays || 30) > 2 && d.statut !== 'validated' && d.statut !== 'rejected');

    if (urgentDossiers.length === 0 && warningDossiers.length === 0) {
        container.innerHTML = `
            <div class="sla-header">
                <span class="sla-icon"></span>
                <span>Alertes SLA</span>
                <span class="sla-badge" style="background: rgba(74,222,128,0.15); color:#4ade80;">✓ Aucune alerte</span>
            </div>
            <div style="padding: 20px; text-align: center; color: var(--text3);">Tous les dossiers respectent les délais SLA</div>
        `;
        return;
    }

    container.innerHTML = `
        <div class="sla-header">
            <span class="sla-icon"></span>
            <span>Alertes SLA - Délais de traitement</span>
            <span class="sla-badge">${urgentDossiers.length + warningDossiers.length} alerte(s)</span>
        </div>
        <div class="sla-list">
            ${urgentDossiers.map(d => `
                <div class="sla-item urgent" onclick="goToDossier(${d.id})" style="cursor:pointer;">
                    <div class="sla-dot urgent"></div>
                    <div class="sla-content">
                        <div class="sla-title">${d.nomEditeur} - ${d.numero}</div>
                        <div class="sla-desc">⚠️ Délai restant: ${d.slaDays || 30} jour(s) - Action requise immédiatement</div>
                    </div>
                    <div class="sla-action">Traiter →</div>
                </div>
            `).join('')}
            ${warningDossiers.map(d => `
                <div class="sla-item warning" onclick="goToDossier(${d.id})" style="cursor:pointer;">
                    <div class="sla-dot warning"></div>
                    <div class="sla-content">
                        <div class="sla-title">${d.nomEditeur} - ${d.numero}</div>
                        <div class="sla-desc">⏰ Délai restant: ${d.slaDays || 30} jour(s) - À surveiller</div>
                    </div>
                    <div class="sla-action">Voir →</div>
                </div>
            `).join('')}
        </div>
    `;
}

function goToDossier(id) {
    if (typeof window.showDetail === 'function') window.showDetail(id);
}

// ============================================
// F3: FAIR RECOMMENDATION
// ============================================

function showFairRecommendation(publisherName) {
    const container = document.getElementById('fairRecommendation');
    const content = document.getElementById('fairRecommendationContent');
    if (!container || !content) return;

    const recommendations = {
        'Éditions Cérès': ['Salon du Livre de Paris (92% match)', 'Foire de Francfort (89% match)', 'Cairo International Book Fair (85% match)'],
        'Sud Éditions': ['Foire de Francfort (88% match)', 'Salon du Livre de Montréal (84% match)', 'Foire de Bologne (81% match)'],
        'Nirvana Press': ['Salon du Livre de Paris (76% match)', 'Foire de Francfort (72% match)']
    };

    const fairs = recommendations[publisherName] || ['Salon du Livre de Paris (85% match)', 'Foire de Francfort (82% match)', 'Cairo International Book Fair (78% match)'];

    content.innerHTML = `
        <div class="recommendation-badge" style="font-size: 11px; color: var(--gold); margin-bottom: 8px;">✨ Basé sur votre profil éditeur</div>
        <div class="recommendation-list">
            ${fairs.map(fair => `<div class="recommendation-item" style="display: flex; align-items: center; gap: 8px; padding: 6px 0;"><span class="rec-icon">📚</span><span style="flex:1;">${fair}</span></div>`).join('')}
        </div>
        <div class="recommendation-note" style="font-size: 10px; color: var(--text3); margin-top: 8px; padding-top: 6px; border-top: 1px solid var(--border); text-align: center;">
            Ces foires correspondent à votre catalogue et historique
        </div>
    `;
    container.style.display = 'block';
}

// ============================================
// ACTIVITY TIMELINE - ENHANCED
// ============================================

function renderActivityTimeline(demandesData) {
    const timelineContainer = document.getElementById('activityTimeline');
    if (!timelineContainer) return;

    const data = demandesData || [];
    const activities = [];

    data.forEach(d => {
        activities.push({ type: 'created', action: 'Nouvelle demande créée', detail: `${d.nomEditeur} - ${d.numero}`, time: d.dateDepot });
        if (d.statut === 'validated') {
            activities.push({ type: 'approved', action: 'Demande approuvée', detail: `${d.nomEditeur} - ${d.numero}`, time: new Date(new Date(d.dateDepot).getTime() + 2 * 24 * 60 * 60 * 1000).toISOString().split('T')[0] });
        }
        if (d.statut === 'rejected') {
            activities.push({ type: 'rejected', action: 'Demande rejetée', detail: `${d.nomEditeur} - ${d.numero}`, time: new Date(new Date(d.dateDepot).getTime() + 1 * 24 * 60 * 60 * 1000).toISOString().split('T')[0] });
        }
    });

    activities.sort((a, b) => new Date(b.time) - new Date(a.time));
    const recentActivities = activities.slice(0, 10);

    timelineContainer.innerHTML = `
        <div class="activity-timeline">
            <div class="timeline-header">
                <div class="timeline-title">Activité récente</div>
                <div class="timeline-filter">
                    <button class="timeline-filter-btn active" data-filter="all" onclick="filterTimeline('all')">Tous</button>
                    <button class="timeline-filter-btn" data-filter="created" onclick="filterTimeline('created')">Nouveaux</button>
                    <button class="timeline-filter-btn" data-filter="approved" onclick="filterTimeline('approved')">Approuvés</button>
                    <button class="timeline-filter-btn" data-filter="rejected" onclick="filterTimeline('rejected')">Rejetés</button>
                </div>
            </div>
            <div class="timeline-items" id="timelineItemsList">
                ${recentActivities.map(a => `
                    <div class="timeline-item" data-type="${a.type}">
                        <div class="timeline-icon ${a.type}"></div>
                        <div class="timeline-content">
                            <div class="timeline-action">${a.action}</div>
                            <div class="timeline-detail">${escapeHtml(a.detail)}</div>
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
    const buttons = document.querySelectorAll('.timeline-filter-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    if (event && event.target) event.target.classList.add('active');
    items.forEach(item => {
        if (type === 'all' || item.dataset.type === type) item.style.display = 'flex';
        else item.style.display = 'none';
    });
}

// ============================================
// ENGAGEMENT STATS
// ============================================

function renderEngagementStats(demandesData) {
    const statsContainer = document.getElementById('engagementStats');
    if (!statsContainer) return;

    const data = demandesData || [];
    const totalDemandes = data.length;
    const pendingCount = data.filter(d => d.statut === 'pending').length;
    const validatedCount = data.filter(d => d.statut === 'validated').length;
    const thisMonthCount = data.filter(d => {
        const date = new Date(d.dateDepot);
        const now = new Date();
        return date.getMonth() === now.getMonth() && date.getFullYear() === now.getFullYear();
    }).length;
    const successRate = totalDemandes > 0 ? Math.round((validatedCount / totalDemandes) * 100) : 0;

    statsContainer.innerHTML = `
        <div class="engagement-grid">
            <div class="engagement-card" onclick="if(window.filterByStatus) filterByStatus('all')">
                <div class="engagement-value">${totalDemandes}</div>
                <div class="engagement-label">Total demandes</div>
                <div class="engagement-trend trend-up">↑ +12% ce mois</div>
            </div>
            <div class="engagement-card" onclick="if(window.filterByStatus) filterByStatus('pending')">
                <div class="engagement-value">${pendingCount}</div>
                <div class="engagement-label">En attente</div>
                <div class="engagement-trend ${pendingCount > 5 ? 'trend-up' : 'trend-down'}">${pendingCount > 5 ? '↑ Priorité haute' : '↓ Peu de retards'}</div>
            </div>
            <div class="engagement-card" onclick="if(window.filterByStatus) filterByStatus('validated')">
                <div class="engagement-value">${validatedCount}</div>
                <div class="engagement-label">Validées</div>
                <div class="engagement-trend trend-up">↑ ${successRate}% taux succès</div>
            </div>
            <div class="engagement-card" onclick="if(window.showThisMonth) showThisMonth()">
                <div class="engagement-value">${thisMonthCount}</div>
                <div class="engagement-label">Ce mois-ci</div>
                <div class="engagement-trend trend-up">↑ +8 vs mois dernier</div>
            </div>
        </div>
    `;
}

// ============================================
// VIEW SWITCHING
// ============================================

function switchView(view) {
    const listView = document.getElementById('listView');
    const calendarView = document.getElementById('calendarView');
    const listBtn = document.querySelector('.calendar-view-btn[onclick*="list"]');
    const calendarBtn = document.querySelector('.calendar-view-btn[onclick*="calendar"]');
    if (listBtn) listBtn.classList.remove('active');
    if (calendarBtn) calendarBtn.classList.remove('active');
    if (view === 'calendar') {
        if (listView) listView.style.display = 'none';
        if (calendarView) calendarView.style.display = 'block';
        if (calendarBtn) calendarBtn.classList.add('active');
        setTimeout(() => {
            if (window.demandes) populateYearFilter(window.demandes);
            if (typeof renderCompactCalendar === 'function') renderCompactCalendar(window.demandes);
        }, 50);
    } else {
        if (listView) listView.style.display = 'block';
        if (calendarView) calendarView.style.display = 'none';
        if (listBtn) listBtn.classList.add('active');
    }
}

// ============================================
// QUICK ACTIONS
// ============================================

function quickActionBulkApprove() {
    if (typeof window.bulkApprove === 'function') window.bulkApprove();
    else showToast('Fonctionnalité à implémenter', 'info');
}

function quickActionExport() {
    if (window.demandes) {
        const csv = window.demandes.map(d => `${d.numero},${d.nomEditeur},${d.matricule},${d.nomGerant},${d.dateDepot},${d.statut}`).join('\n');
        console.log('Export data:', csv);
        showToast('Export CSV démarré - Vérifiez la console');
    } else {
        showToast('Aucune donnée à exporter', 'error');
    }
}

function quickActionHelp() {
    showToast('Guide: Cliquez sur les éléments pour plus d\'informations', 'info');
}

// ============================================
// EXPORT FUNCTIONS FOR GLOBAL ACCESS
// ============================================

window.formatDate = formatDate;
window.formatRelativeTime = formatRelativeTime;
window.showToast = showToast;
window.openModal = openModal;
window.closeModal = closeModal;
window.checkExistingDocuments = checkExistingDocuments;
window.selectDocument = selectDocument;
window.toggleSelectAll = toggleSelectAll;
window.toggleSelect = toggleSelect;
window.updateBulkBar = updateBulkBar;
window.clearSelection = clearSelection;
window.renderCompactCalendar = renderCompactCalendar;
window.goToDateFromCalendar = goToDateFromCalendar;
window.selectCalendarDate = selectCalendarDate;
window.prevMonthCompact = prevMonthCompact;
window.nextMonthCompact = nextMonthCompact;
window.goToTodayCompact = goToTodayCompact;
window.applyCalendarFilters = applyCalendarFilters;
window.resetCalendarFilters = resetCalendarFilters;
window.goToToday = goToToday;
window.populateYearFilter = populateYearFilter;
window.renderNotesForDate = renderNotesForDate;
window.addNote = addNote;
window.deleteNote = deleteNote;
window.editNote = editNote;
window.openRejectModal = openRejectModal;
window.selectRejectionTemplate = selectRejectionTemplate;
window.confirmReject = confirmReject;
window.checkDocumentExpiry = checkDocumentExpiry;
window.renderEngagementStats = renderEngagementStats;
window.renderActivityTimeline = renderActivityTimeline;
window.filterTimeline = filterTimeline;
window.switchView = switchView;
window.quickActionBulkApprove = quickActionBulkApprove;
window.quickActionExport = quickActionExport;
window.quickActionHelp = quickActionHelp;
window.renderHealthScores = renderHealthScores;
window.renderAIInsights = renderAIInsights;
window.renderSLAAlerts = renderSLAAlerts;
window.showUrgentDossiers = showUrgentDossiers;
window.showLowHealthPublishers = showLowHealthPublishers;
window.filterByPublisher = filterByPublisher;
window.goToDossier = goToDossier;
window.showFairRecommendation = showFairRecommendation;
window.escapeHtml = escapeHtml;
window.getEventsForDate = getEventsForDate;
window.getMonthNameShort = getMonthNameShort;
