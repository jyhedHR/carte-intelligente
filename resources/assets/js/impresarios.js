/**
 * Impresarios & Contracts Management System
 * Enhanced interactivity and user experience
 */

// Modal Management
window.openModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        console.log("[v0] Modal opened:", modalId);
    }
}

window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        console.log("[v0] Modal closed:", modalId);
    }
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[id*="Modal"]').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });
    });
});

// Tab Switching
window.switchTab = function(tabName) {
    console.log("[v0] Switching to tab:", tabName);

    // Hide all tabs
    const tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(tab => {
        tab.style.display = 'none';
    });

    // Remove active styling from all buttons
    const buttons = document.querySelectorAll('.tab-btn');
    buttons.forEach(btn => {
        btn.style.color = 'var(--text2)';
        btn.style.borderBottom = 'none';
    });

    // Show selected tab
    const selectedTab = document.getElementById(tabName);
    if (selectedTab) {
        selectedTab.style.display = 'block';
        console.log("[v0] Tab displayed:", tabName);
    }

    // Add active styling to clicked button
    if (event && event.target && event.target.classList.contains('tab-btn')) {
        event.target.style.color = 'var(--text)';
        event.target.style.borderBottom = '2px solid var(--gold)';
    }
}

// Search and Filter Functionality
window.initializeSearchAndFilters = function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const departmentFilter = document.getElementById('departmentFilter');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            filterTableRows();
        });
    }

    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            filterTableRows();
        });
    }

    if (departmentFilter) {
        departmentFilter.addEventListener('change', function() {
            filterTableRows();
        });
    }
}

window.filterTableRows = function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
    const statusValue = statusFilter ? statusFilter.value : '';

    const tables = document.querySelectorAll('table tbody');
    tables.forEach(table => {
        const rows = table.querySelectorAll('tr');
        rows.forEach(row => {
            let showRow = true;

            // Search filter
            if (searchTerm) {
                const text = row.textContent.toLowerCase();
                showRow = text.includes(searchTerm);
            }

            // Status filter
            if (showRow && statusValue) {
                const statusBadges = row.querySelectorAll('.badge');
                showRow = false;
                statusBadges.forEach(badge => {
                    if (statusValue === 'verified' && badge.textContent.includes('Vérifié')) {
                        showRow = true;
                    }
                    if (statusValue === 'pending' && badge.textContent.includes('Vérification')) {
                        showRow = true;
                    }
                    if (statusValue === 'suspended' && badge.textContent.includes('Suspendu')) {
                        showRow = true;
                    }
                });
            }

            row.style.display = showRow ? 'table-row' : 'none';
        });
    });

    console.log("[v0] Filters applied - Search:", searchTerm, "Status:", statusValue);
}

// Quick View Toggle
window.toggleQuickView = function() {
    const kpiCards = document.querySelector('.kpi-grid');
    if (kpiCards) {
        kpiCards.style.display = kpiCards.style.display === 'none' ? 'grid' : 'none';
        console.log("[v0] Quick view toggled");
    }
}

// Export Report Function
window.exportImpresariosReport = function() {
    const date = new Date().toLocaleDateString('fr-FR');
    let report = `RAPPORT GESTION DES IMPRÉSARIOS & CONTRATS\n`;
    report += `Généré le: ${date}\n`;
    report += `${'='.repeat(50)}\n\n`;

    // Gather data from tables
    const tables = document.querySelectorAll('table');
    tables.forEach((table, index) => {
        const title = table.parentElement.parentElement.querySelector('.panel-title');
        if (title) {
            report += `${title.textContent}\n`;
            report += `${'-'.repeat(50)}\n`;
        }

        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const rowData = Array.from(cells).map(cell => cell.textContent.trim()).join(' | ');
            report += `${rowData}\n`;
        });
        report += `\n`;
    });

    // Download
    const element = document.createElement('a');
    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(report));
    element.setAttribute('download', `Rapport_Impresarios_${date.replace(/\//g, '-')}.txt`);
    element.style.display = 'none';
    document.body.appendChild(element);
    element.click();
    document.body.removeChild(element);

    console.log("[v0] Report exported successfully");
    alert(`Rapport exporté: Rapport_Impresarios_${date.replace(/\//g, '-')}.txt`);
}

// Conflict Resolution Actions
window.resolveConflict = function(conflictType) {
    console.log("[v0] Resolving conflict:", conflictType);
    alert(`Action pour résoudre: ${conflictType}\n\nDétails:\n- Examiner les contrats\n- Contacter les parties\n- Mettre à jour le statut`);
}

// Form Validation
window.validateImpresarioForm = function() {
    const inputs = document.querySelectorAll('#addImpModal input[type="text"], #addImpModal input[type="email"]');
    let isValid = true;

    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.style.borderColor = 'var(--red)';
            isValid = false;
        } else {
            input.style.borderColor = 'var(--border)';
        }
    });

    if (!isValid) {
        console.log("[v0] Form validation failed");
        alert('Veuillez remplir tous les champs requis (*)');
    }

    return isValid;
}

// Initialize all features
document.addEventListener('DOMContentLoaded', function() {
    console.log("[v0] Impresarios management system initialized");
    initializeSearchAndFilters();

    // Auto-load first tab
    const firstTab = document.querySelector('.tab-content');
    if (firstTab) {
        firstTab.style.display = 'block';
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // ESC to close modals
    if (e.key === 'Escape') {
        document.querySelectorAll('[id*="Modal"]').forEach(modal => {
            modal.style.display = 'none';
        });
        document.body.style.overflow = 'auto';
    }

    // Ctrl+K to focus search
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.focus();
        }
    }
});

// Real-time notification simulation
window.checkConflictUpdates = function() {
    const conflictCount = document.querySelector('[style*="--red-dim"]');
    if (conflictCount) {
        console.log("[v0] Checking for conflict updates...");
        // In production, this would fetch from API
    }
}

// Set interval to check for updates
setInterval(checkConflictUpdates, 60000); // Every minute
window.showModal = function(modalId) {
    document.getElementById(modalId).style.display = 'flex';
}

window.closeModal = function(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

window.switchTab = function(tabName, event) {
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

window.toggleQuickView = function() {
    alert('Vue rapide - Statistiques en temps réel');
}

document.addEventListener('DOMContentLoaded', function() {
    if (typeof initializeSearchAndFilters === "function") {
        initializeSearchAndFilters();
    }
});
