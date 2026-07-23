/* ═══════════════════════════════════════════════════════════════════════════════
   DIGITAL CATALOG JAVASCRIPT
   Handles all interactive functionality for the digital catalog interface
   ════════════════════════════════════════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', function() {
    initializeCatalog();
});

/* ═══════════════════════════════════════════════════════════════════════════════
   TAB SWITCHING
   ════════════════════════════════════════════════════════════════════════════════ */

function switchTab(tabName) {
    // Hide all tabs
    const allTabs = document.querySelectorAll('.tab-pane');
    allTabs.forEach(tab => tab.classList.remove('active'));

    // Remove active class from all buttons
    const allButtons = document.querySelectorAll('.tab-btn');
    allButtons.forEach(btn => btn.classList.remove('active'));

    // Show selected tab
    const selectedTab = document.getElementById(tabName);
    if (selectedTab) {
        selectedTab.classList.add('active');
    }

    // Mark button as active
    event.target.classList.add('active');

    console.log('[v0] Switched to tab:', tabName);
}

/* ═══════════════════════════════════════════════════════════════════════════════
   MODAL MANAGEMENT
   ════════════════════════════════════════════════════════════════════════════════ */

function openModal(modalId) {
    let modal = document.getElementById(modalId);

    if (!modal) {
        modal = createModal(modalId);
    }

    modal.classList.add('active');
    document.body.style.overflow = 'hidden';

    console.log('[v0] Opened modal:', modalId);
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
        console.log('[v0] Closed modal:', modalId);
    }
}

function createModal(modalId) {
    const modal = document.createElement('div');
    modal.id = modalId;
    modal.className = 'modal';

    let title = 'Modal';
    if (modalId.includes('artist')) title = 'Artiste';
    if (modalId.includes('artwork')) title = 'Œuvre';

    modal.innerHTML = `
        <div class="modal-dialog">
            <div class="modal-header">
                <h5>${title}</h5>
                <button class="modal-close" onclick="closeModal('${modalId}')">&times;</button>
            </div>
            <div class="modal-body">
                <p>Contenu du formulaire...</p>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeModal('${modalId}')">Annuler</button>
                <button class="btn-primary" onclick="saveForm('${modalId}')">Enregistrer</button>
            </div>
        </div>
    `;

    document.body.appendChild(modal);

    // Close on overlay click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal(modalId);
    });

    return modal;
}

/* ═══════════════════════════════════════════════════════════════════════════════
   ARTWORK OPERATIONS
   ════════════════════════════════════════════════════════════════════════════════ */

function viewArtwork(id) {
    console.log('[v0] View artwork:', id);
    showNotification(`Affichage de l'œuvre #${id}`, 'info');
}

function editArtwork(id) {
    console.log('[v0] Edit artwork:', id);
    openModal('artworkModal');
    showNotification(`Édition de l'œuvre #${id}`, 'info');
}

function deleteArtwork(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette œuvre ?')) {
        console.log('[v0] Deleted artwork:', id);
        showNotification(`Œuvre #${id} supprimée`, 'success');
    }
}

/* ═══════════════════════════════════════════════════════════════════════════════
   ARTIST OPERATIONS
   ════════════════════════════════════════════════════════════════════════════════ */

function viewArtist(id) {
    console.log('[v0] View artist:', id);
    showNotification(`Affichage de l'artiste #${id}`, 'info');
}

function editArtist(id) {
    console.log('[v0] Edit artist:', id);
    openModal('artistModal');
    showNotification(`Édition de l'artiste #${id}`, 'info');
}

function deleteArtist(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet artiste ?')) {
        console.log('[v0] Deleted artist:', id);
        showNotification(`Artiste #${id} supprimé`, 'success');
    }
}

/* ═══════════════════════════════════════════════════════════════════════════════
   EXHIBITION OPERATIONS
   ════════════════════════════════════════════════════════════════════════════════ */

function viewExhibition(id) {
    console.log('[v0] View exhibition:', id);
    showNotification(`Affichage de l'exposition #${id}`, 'info');
}

function editExhibition(id) {
    console.log('[v0] Edit exhibition:', id);
    openModal('exhibitionModal');
    showNotification(`Édition de l'exposition #${id}`, 'info');
}

/* ═══════════════════════════════════════════════════════════════════════════════
   TIMELINE FUNCTIONS
   ════════════════════════════════════════════════════════════════════════════════ */

function updateTimeline(artistId) {
    console.log('[v0] Updated timeline for artist:', artistId);
    if (artistId) {
        showNotification(`Chronologie de l'artiste #${artistId} chargée`, 'info');
    }
}

function addTimelineEvent() {
    console.log('[v0] Adding timeline event');
    showNotification('Événement chronologique ajouté', 'success');
    closeModal('timelineForm');
}

/* ═══════════════════════════════════════════════════════════════════════════════
   FORM HANDLING
   ════════════════════════════════════════════════════════════════════════════════ */

function saveForm(formId) {
    console.log('[v0] Form submitted:', formId);
    showNotification('Données enregistrées avec succès', 'success');
    closeModal(formId);
}

/* ═══════════════════════════════════════════════════════════════════════════════
   NOTIFICATIONS
   ════════════════════════════════════════════════════════════════════════════════ */

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${getNotificationIcon(type)}"></i>
            <span>${message}</span>
        </div>
    `;

    // Add to page
    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => notification.classList.add('show'), 10);

    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

function getNotificationIcon(type) {
    const icons = {
        'success': 'check-circle',
        'error': 'exclamation-circle',
        'warning': 'exclamation-triangle',
        'info': 'info-circle'
    };
    return icons[type] || icons['info'];
}

/* ═══════════════════════════════════════════════════════════════════════════════
   INITIALIZATION
   ════════════════════════════════════════════════════════════════════════════════ */

function initializeCatalog() {
    console.log('[v0] Initializing digital catalog...');

    // Set first tab as active
    const firstTab = document.querySelector('.tab-btn');
    if (firstTab) {
        firstTab.classList.add('active');
    }

    // Initialize first pane
    const firstPane = document.querySelector('.tab-pane');
    if (firstPane) {
        firstPane.classList.add('active');
    }

    // Close modals on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modals = document.querySelectorAll('.modal.active');
            modals.forEach(modal => {
                modal.classList.remove('active');
            });
            document.body.style.overflow = 'auto';
        }
    });

    console.log('[v0] Digital catalog initialized');
}

/* ═══════════════════════════════════════════════════════════════════════════════
   UTILITY FUNCTIONS
   ════════════════════════════════════════════════════════════════════════════════ */

// Export functions for global scope
window.switchTab = switchTab;
window.openModal = openModal;
window.closeModal = closeModal;
window.viewArtwork = viewArtwork;
window.editArtwork = editArtwork;
window.deleteArtwork = deleteArtwork;
window.viewArtist = viewArtist;
window.editArtist = editArtist;
window.deleteArtist = deleteArtist;
window.viewExhibition = viewExhibition;
window.editExhibition = editExhibition;
window.updateTimeline = updateTimeline;
window.addTimelineEvent = addTimelineEvent;
window.saveForm = saveForm;
window.showNotification = showNotification;
