// Mock Data
const mockEvents = [
    // January 2026
    { date: '2026-01-05', type: 'event', title: 'Art Exhibition Opening', department: 'art_plastique', description: 'Annual art exhibition opening ceremony', status: 'important', time: '14:00' },
    { date: '2026-01-08', type: 'log', title: 'Budget Review', department: 'administration', description: 'Q1 budget review and approval', status: 'normal', time: '10:30' },
    { date: '2026-01-12', type: 'meeting', title: 'Department Heads Meeting', department: 'administration', description: 'Monthly department heads coordination', status: 'normal', time: '09:00' },
    { date: '2026-01-15', type: 'event', title: 'Music Concert', department: 'musique', description: 'Winter concert - classical music series', status: 'normal', time: '19:00' },
    { date: '2026-01-20', type: 'log', title: 'Student Registration', department: 'administration', description: 'Spring semester registration deadline', status: 'pending', time: '17:00' },

    // February 2026
    { date: '2026-02-01', type: 'event', title: 'Dance Performance', department: 'dance', description: 'Contemporary dance showcase', status: 'normal', time: '18:00' },
    { date: '2026-02-05', type: 'log', title: 'Staff Training', department: 'administration', description: 'Annual staff training program', status: 'normal', time: '09:00' },
    { date: '2026-02-10', type: 'meeting', title: 'Curriculum Review', department: 'art_plastique', description: 'Review and update curriculum standards', status: 'normal', time: '14:00' },
    { date: '2026-02-14', type: 'event', title: 'Valentine Concert', department: 'musique', description: 'Special Valentine&apos;s concert', status: 'important', time: '19:30' },
    { date: '2026-02-20', type: 'log', title: 'Inventory Check', department: 'art_plastique', description: 'Art supplies inventory audit', status: 'pending', time: '11:00' },

    // March 2026
    { date: '2026-03-03', type: 'event', title: 'Spring Dance Festival', department: 'dance', description: 'Multi-department spring dance festival', status: 'important', time: '19:00' },
    { date: '2026-03-08', type: 'log', title: 'System Maintenance', department: 'administration', description: 'Database and server maintenance', status: 'normal', time: '22:00' },
    { date: '2026-03-12', type: 'meeting', title: 'Budget Planning', department: 'administration', description: 'Next fiscal year budget planning', status: 'normal', time: '10:00' },
    { date: '2026-03-17', type: 'event', title: 'Art Master Class', department: 'art_plastique', description: 'International artist master class', status: 'important', time: '15:00' },
    { date: '2026-03-25', type: 'log', title: 'Performance Reviews', department: 'administration', description: 'Staff performance evaluation season', status: 'pending', time: '13:00' },

    // April 2026
    { date: '2026-04-02', type: 'event', title: 'Spring Recital', department: 'musique', description: 'Student spring music recital', status: 'normal', time: '19:00' },
    { date: '2026-04-07', type: 'log', title: 'Procurement Orders', department: 'administration', description: 'Submit equipment procurement requests', status: 'pending', time: '12:00' },
    { date: '2026-04-15', type: 'meeting', title: 'Safety Inspection', department: 'administration', description: 'Facility safety inspection', status: 'normal', time: '11:00' },
    { date: '2026-04-20', type: 'event', title: 'Dance Workshop', department: 'dance', description: 'Advanced choreography workshop', status: 'normal', time: '10:00' },
    { date: '2026-04-28', type: 'log', title: 'Enrollment Data', department: 'administration', description: 'Update enrollment statistics', status: 'normal', time: '14:00' },

    // May 2026
    { date: '2026-05-05', type: 'event', title: 'Art Symposium', department: 'art_plastique', description: 'Contemporary art theory symposium', status: 'important', time: '10:00' },
    { date: '2026-05-12', type: 'log', title: 'Grant Applications', department: 'administration', description: 'Submit federal grant applications', status: 'pending', time: '17:00' },
    { date: '2026-05-18', type: 'meeting', title: 'Spring Board Meeting', department: 'administration', description: 'Board of directors spring meeting', status: 'normal', time: '14:00' },
    { date: '2026-05-25', type: 'event', title: 'Graduation Ceremony', department: 'administration', description: 'Annual graduation celebration', status: 'important', time: '10:00' },

    // June 2026 - Current Month
    { date: '2026-06-01', type: 'log', title: 'Summer Planning', department: 'administration', description: 'Summer session planning meeting', status: 'normal', time: '09:00' },
    { date: '2026-06-05', type: 'event', title: 'Summer Concert Series', department: 'musique', description: 'Outdoor summer concert series begins', status: 'normal', time: '18:00' },
    { date: '2026-06-10', type: 'meeting', title: 'Facility Upgrade Review', department: 'administration', description: 'Review facility upgrade proposals', status: 'normal', time: '13:00' },
    { date: '2026-06-15', type: 'event', title: 'Summer Art Camp', department: 'art_plastique', description: 'Youth summer art intensive program', status: 'normal', time: '09:00' },
    { date: '2026-06-20', type: 'log', title: 'Mid-Year Review', department: 'administration', description: 'Mid-year financial and operational review', status: 'pending', time: '14:00' },
    { date: '2026-06-25', type: 'event', title: 'Dance Summer Intensive', department: 'dance', description: 'Professional dancer summer intensive', status: 'important', time: '10:00' },

    // More events scattered through remaining months
    { date: '2026-07-04', type: 'event', title: 'Independence Day Special', department: 'musique', description: 'Patriotic music celebration', status: 'important', time: '19:00' },
    { date: '2026-08-15', type: 'event', title: 'Fall Semester Kickoff', department: 'administration', description: 'Welcome and orientation for new students', status: 'normal', time: '10:00' },
    { date: '2026-09-01', type: 'log', title: 'New Academic Year', department: 'administration', description: 'Official start of academic year', status: 'important', time: '08:00' },
    { date: '2026-10-31', type: 'event', title: 'Halloween Performance', department: 'dance', description: 'Spooky themed dance performance', status: 'normal', time: '20:00' },
    { date: '2026-11-15', type: 'meeting', title: 'Holiday Planning', department: 'administration', description: 'Plan holiday events and celebrations', status: 'normal', time: '11:00' },
    { date: '2026-12-20', type: 'event', title: 'Winter Holiday Concert', department: 'musique', description: 'Annual winter holiday concert', status: 'important', time: '19:00' },
];

// Calendar State
let currentDate = new Date(2026, 3, 9); // Start at April 2026
let selectedDate = null;
let filteredEvents = [...mockEvents];

// DOM Elements
const monthSelect = document.getElementById('monthSelect');
const yearSelect = document.getElementById('yearSelect');
const departmentFilter = document.getElementById('departmentFilter');
const eventTypeFilter = document.getElementById('eventTypeFilter');
const searchInput = document.getElementById('searchInput');
const calendarTitle = document.getElementById('calendarTitle');
const calendarDays = document.getElementById('calendarDays');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const selectedDayEvents = document.getElementById('selectedDayEvents');
const selectedDateDisplay = document.getElementById('selectedDateDisplay');
const activityTimeline = document.getElementById('activityTimeline');
const totalEvents = document.getElementById('totalEvents');
const totalLogs = document.getElementById('totalLogs');
const totalMeetings = document.getElementById('totalMeetings');
const departmentStats = document.getElementById('departmentStats');
const eventModal = document.getElementById('eventModal');
const exportBtn = document.getElementById('exportBtn');
const darkModeToggle = document.getElementById('darkModeToggle');
const exportModal = document.getElementById('exportModal');

// Initialize Calendar
function init() {
    updateCalendar();
    updateFilters();
    setupEventListeners();
    displayActivityTimeline();
    updateStats();
    initDarkMode();
}

// Dark Mode Management
function initDarkMode() {
    const darkModePreference = localStorage.getItem('darkMode');
    if (darkModePreference === 'enabled') {
        document.documentElement.classList.add('dark');
        document.body.classList.add('dark-mode');
    } else {
        document.documentElement.classList.remove('dark');
        document.body.classList.remove('dark-mode');
    }
}

function toggleDarkMode() {
    document.documentElement.classList.toggle('dark');
    document.body.classList.toggle('dark-mode');
    const isDarkMode = document.body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDarkMode ? 'enabled' : 'disabled');
}

// Open Export Modal
function openExportModal() {
    console.log("[v0] Opening export modal");
    const currentMonth = currentDate.getMonth();
    document.getElementById('exportStartMonth').value = currentMonth;
    document.getElementById('exportEndMonth').value = currentMonth;
    document.getElementById('exportYear').value = currentDate.getFullYear();
    exportModal.classList.remove('hidden');
    console.log("[v0] Export modal opened");
}

// Close Export Modal
window.closeExportModal = function() {
    console.log("[v0] Closing export modal");
    exportModal.classList.add('hidden');
};

// Download Export Report with Date Range
window.downloadExportReport = function() {
    console.log("[v0] Download export report clicked");
    const startMonth = parseInt(document.getElementById('exportStartMonth').value);
    const endMonth = parseInt(document.getElementById('exportEndMonth').value);
    const year = parseInt(document.getElementById('exportYear').value);
    console.log("[v0] Export params - startMonth:", startMonth, "endMonth:", endMonth, "year:", year);

    const monthNames = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

    let fileName = `Rapport_Calendrier_`;
    if (startMonth === endMonth) {
        fileName += `${monthNames[startMonth]}_${year}.txt`;
    } else {
        fileName += `${monthNames[startMonth]}_a_${monthNames[endMonth]}_${year}.txt`;
    }

    let reportContent = `=====================================\nRAPPORT CALENDRIER\n=====================================\n\n`;

    if (startMonth === endMonth) {
        reportContent += `Période: ${monthNames[startMonth]} ${year}\n`;
    } else {
        reportContent += `Période: ${monthNames[startMonth]} à ${monthNames[endMonth]} ${year}\n`;
    }

    reportContent += `Généré: ${new Date().toLocaleString('fr-FR')}\n\n`;

    if (departmentFilter.value) {
        reportContent += `Filtre Département: ${formatDepartment(departmentFilter.value)}\n`;
    }
    if (eventTypeFilter.value) {
        const typeMap = { 'event': 'Événements', 'log': 'Journaux d\'Activité', 'meeting': 'Réunions' };
        reportContent += `Filtre Type d'Événement: ${typeMap[eventTypeFilter.value]}\n`;
    }

    reportContent += `\n=====================================\nSTATISTIQUES\n=====================================\n`;
    reportContent += `Événements Total: ${totalEvents.textContent}\n`;
    reportContent += `Journaux d'Activité: ${totalLogs.textContent}\n`;
    reportContent += `Réunions: ${totalMeetings.textContent}\n`;

    reportContent += `\n=====================================\nDÉTAILS DES ÉVÉNEMENTS\n=====================================\n\n`;

    // Collect events within date range
    const eventsInRange = filteredEvents.filter(event => {
        const eventYear = parseInt(event.date.split('-')[0]);
        const eventMonth = parseInt(event.date.split('-')[1]) - 1;

        if (eventYear !== year) return false;

        if (startMonth <= endMonth) {
            return eventMonth >= startMonth && eventMonth <= endMonth;
        } else {
            return eventMonth >= startMonth || eventMonth <= endMonth;
        }
    });

    if (eventsInRange.length === 0) {
        reportContent += `Aucun événement trouvé pour la période sélectionnée.\n`;
    } else {
        eventsInRange.forEach(event => {
            const dateObj = new Date(event.date);
            reportContent += `Date: ${dateObj.toLocaleDateString('fr-FR')}\n`;
            reportContent += `Heure: ${event.time}\n`;
            reportContent += `Titre: ${event.title}\n`;
            reportContent += `Type: ${event.type}\n`;
            reportContent += `Département: ${formatDepartment(event.department)}\n`;
            reportContent += `Description: ${event.description}\n`;
            reportContent += `Statut: ${event.status}\n\n`;
        });
    }

    // Create and download file
    const element = document.createElement('a');
    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(reportContent));
    element.setAttribute('download', fileName);
    element.style.display = 'none';
    document.body.appendChild(element);
    console.log("[v0] Created download element, clicking...");
    element.click();
    document.body.removeChild(element);
    console.log("[v0] Download triggered");

    closeExportModal();
    console.log("[v0] Export complete, showing alert");
    alert(`Rapport exporté en tant que ${fileName}`);
};

// Setup Event Listeners
function setupEventListeners() {
    prevBtn.addEventListener('click', goToPreviousMonth);
    nextBtn.addEventListener('click', goToNextMonth);
    monthSelect.addEventListener('change', handleDateChange);
    yearSelect.addEventListener('change', handleDateChange);
    departmentFilter.addEventListener('change', applyFilters);
    eventTypeFilter.addEventListener('change', applyFilters);
    searchInput.addEventListener('input', applyFilters);
    exportBtn.addEventListener('click', openExportModal);
    darkModeToggle.addEventListener('click', toggleDarkMode);
}

// Navigation
function goToPreviousMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    updateCalendar();
}

function goToNextMonth() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    updateCalendar();
}

// Handle Date Change
function handleDateChange() {
    const month = parseInt(monthSelect.value);
    const year = parseInt(yearSelect.value);
    currentDate.setMonth(month);
    currentDate.setFullYear(year);
    updateCalendar();
}

// Update Calendar Display
function updateCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    // Update title
    const monthName = new Date(year, month).toLocaleString('fr-FR', { month: 'long' });
    calendarTitle.textContent = `${monthName.charAt(0).toUpperCase() + monthName.slice(1)} ${year}`;

    // Update selectors
    monthSelect.value = month;
    yearSelect.value = year;

    // Generate calendar days
    renderCalendarDays(year, month);
}

// Render Calendar Days
function renderCalendarDays(year, month) {
    calendarDays.innerHTML = '';

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const daysInPrevMonth = new Date(year, month, 0).getDate();

    // Previous month's days
    for (let i = firstDay - 1; i >= 0; i--) {
        const day = daysInPrevMonth - i;
        addDayElement(day, true, year, month - 1);
    }

    // Current month's days
    const today = new Date();
    for (let day = 1; day <= daysInMonth; day++) {
        const isToday = day === today.getDate() &&
            month === today.getMonth() &&
            year === today.getFullYear();
        addDayElement(day, false, year, month, isToday);
    }

    // Next month's days
    const totalCells = calendarDays.children.length;
    const remainingCells = 42 - totalCells; // 6 rows × 7 days
    for (let day = 1; day <= remainingCells; day++) {
        addDayElement(day, true, year, month + 1);
    }
}

// Add Day Element
function addDayElement(day, isOtherMonth, year, month, isToday = false) {
    const dayEl = document.createElement('div');
    dayEl.className = 'calendar-day';

    if (isOtherMonth) {
        dayEl.classList.add('other-month');
    }

    if (isToday) {
        dayEl.classList.add('today');
    }

    const dateStr = getDateString(day, month, year);
    const dayEvents = mockEvents.filter(e => e.date === dateStr);

    if (dayEvents.length > 0) {
        dayEl.classList.add('has-events');
    }

    const dayNumber = document.createElement('div');
    dayNumber.className = 'day-number';
    dayNumber.textContent = day;
    dayEl.appendChild(dayNumber);

    const dayEventsContainer = document.createElement('div');
    dayEventsContainer.className = 'day-events';

    dayEvents.forEach(event => {
        const indicator = document.createElement('div');
        indicator.className = `event-indicator ${event.type}`;
        dayEventsContainer.appendChild(indicator);
    });

    dayEl.appendChild(dayEventsContainer);

    if (!isOtherMonth) {
        dayEl.addEventListener('click', () => selectDay(dateStr, day));
    }

    calendarDays.appendChild(dayEl);
}

// Get Date String
function getDateString(day, month, year) {
    const d = new Date(year, month, day);
    return d.toISOString().split('T')[0];
}

// Select Day
function selectDay(dateStr, day) {
    selectedDate = dateStr;

    // Update selected state
    document.querySelectorAll('.calendar-day').forEach(el => {
        el.classList.remove('selected');
    });
    event.currentTarget.classList.add('selected');

    // Update events display
    displayDayEvents(dateStr, day);
}

// Display Day Events
function displayDayEvents(dateStr, day) {
    const date = new Date(dateStr);
    const dayName = date.toLocaleString('fr-FR', { weekday: 'long' });
    const monthName = date.toLocaleString('fr-FR', { month: 'long' });

    selectedDateDisplay.textContent = `${dayName.charAt(0).toUpperCase() + dayName.slice(1)}, ${monthName.charAt(0).toUpperCase() + monthName.slice(1)} ${date.getDate()}`;

    const dayEvents = filteredEvents.filter(e => e.date === dateStr);

    if (dayEvents.length === 0) {
        selectedDayEvents.innerHTML = '<p class="text-slate-500 dark:text-slate-400 text-sm">Aucun événement prévu</p>';
    } else {
        const typeMap = { 'event': 'Événement', 'log': 'Journal', 'meeting': 'Réunion' };
        selectedDayEvents.innerHTML = dayEvents.map(event => `
            <div class="event-card ${event.type}" onclick="showEventModal(event)">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="event-badge ${event.type}">${typeMap[event.type] || event.type}</span>
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">${event.time}</span>
                    </div>
                    <div class="event-title">${event.title}</div>
                    <div class="event-meta">
                        <span>📍 ${formatDepartment(event.department)}</span>
                        <span>•</span>
                        <span>${event.description}</span>
                    </div>
                </div>
            </div>
        `).join('');
    }
}

// Format Department Name
function formatDepartment(dept) {
    const deptMap = {
        'art_plastique': 'Art Plastique',
        'musique': 'Musique',
        'dance': 'Danse',
        'theater': 'Théâtre',
        'administration': 'Administration'
    };
    return deptMap[dept] || dept;
}

// Apply Filters
function applyFilters() {
    const department = departmentFilter.value;
    const eventType = eventTypeFilter.value;
    const searchTerm = searchInput.value.toLowerCase();

    filteredEvents = mockEvents.filter(event => {
        const matchDept = !department || event.department === department;
        const matchType = !eventType || event.type === eventType;
        const matchSearch = !searchTerm ||
            event.title.toLowerCase().includes(searchTerm) ||
            event.description.toLowerCase().includes(searchTerm);

        return matchDept && matchType && matchSearch;
    });

    // Refresh display
    updateCalendar();
    displayActivityTimeline();
    updateStats();

    if (selectedDate) {
        const dayNumber = parseInt(selectedDate.split('-')[2]);
        displayDayEvents(selectedDate, dayNumber);
    }
}

// Display Activity Timeline
function displayActivityTimeline() {
    const recentEvents = filteredEvents
        .sort((a, b) => new Date(b.date) - new Date(a.date))
        .slice(0, 8);

    if (recentEvents.length === 0) {
        activityTimeline.innerHTML = '<p class="text-slate-500 dark:text-slate-400 text-sm">Aucune activité récente</p>';
        return;
    }

    activityTimeline.innerHTML = recentEvents.map(event => {
        const date = new Date(event.date);
        const timeStr = date.toLocaleString('fr-FR', { month: 'short', day: 'numeric' });

        return `
            <div class="timeline-item">
                <div class="timeline-dot" style="background-color: ${getEventColor(event.type)};"></div>
                <div class="timeline-content">
                    <div class="timeline-time">${timeStr} à ${event.time}</div>
                    <div class="timeline-title">${event.title}</div>
                    <div class="timeline-description">${formatDepartment(event.department)}</div>
                </div>
            </div>
        `;
    }).join('');
}

// Get Event Color
function getEventColor(type) {
    const colors = {
        'event': '#3b82f6',
        'log': '#10b981',
        'meeting': '#a855f7',
        'important': '#ef4444',
        'pending': '#eab308'
    };
    return colors[type] || '#6b7280';
}

// Update Statistics
function updateStats() {
    const eventCount = filteredEvents.filter(e => e.type === 'event').length;
    const logCount = filteredEvents.filter(e => e.type === 'log').length;
    const meetingCount = filteredEvents.filter(e => e.type === 'meeting').length;

    totalEvents.textContent = eventCount;
    totalLogs.textContent = logCount;
    totalMeetings.textContent = meetingCount;

    // Department stats
    const deptStats = {};
    filteredEvents.forEach(event => {
        deptStats[event.department] = (deptStats[event.department] || 0) + 1;
    });

    departmentStats.innerHTML = Object.entries(deptStats)
        .sort((a, b) => b[1] - a[1])
        .map(([dept, count]) => `
            <div class="department-stat">
                <span class="department-stat-name">${formatDepartment(dept)}</span>
                <span class="department-stat-count">${count}</span>
            </div>
        `).join('');
}

// Show Event Modal
window.showEventModal = function(event) {
    document.getElementById('modalTitle').textContent = event.title;
    document.getElementById('modalContent').textContent = event.description;

    const typeMap = { 'event': 'ÉVÉNEMENT', 'log': 'JOURNAL', 'meeting': 'RÉUNION' };
    const statusMap = { 'normal': 'Normal', 'important': 'Important', 'pending': 'En Attente' };

    const metaHTML = `
        <div class="flex flex-col gap-2">
            <div><span class="font-semibold">Type:</span> ${typeMap[event.type] || event.type.toUpperCase()}</div>
            <div><span class="font-semibold">Département:</span> ${formatDepartment(event.department)}</div>
            <div><span class="font-semibold">Date:</span> ${new Date(event.date).toLocaleDateString('fr-FR')}</div>
            <div><span class="font-semibold">Heure:</span> ${event.time}</div>
            <div><span class="font-semibold">Statut:</span> <span class="text-blue-600 dark:text-blue-400 capitalize">${statusMap[event.status] || event.status}</span></div>
        </div>
    `;
    document.getElementById('modalMeta').innerHTML = metaHTML;
    eventModal.classList.remove('hidden');
};

// Close Modal
window.closeModal = function() {
    eventModal.classList.add('hidden');
};

// Update Filters Dropdown on Load
function updateFilters() {
    monthSelect.value = currentDate.getMonth();
    yearSelect.value = currentDate.getFullYear();
}

// Close modal when clicking outside
eventModal.addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Close export modal when clicking outside
exportModal.addEventListener('click', function(e) {
    if (e.target === this) {
        closeExportModal();
    }
});

// Initialize on load
document.addEventListener('DOMContentLoaded', init);
