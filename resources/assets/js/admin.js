
        // Modal Management
       // Modal Management
window.openModal = function (modalId) {
    const modal = document.getElementById(modalId);

    if (modal) {
        modal.classList.add('show');
    } else {
        console.error(`Modal with id "${modalId}" not found!`);
        // Optional: show error toast
        showToast(`Erreur: Modal "${modalId}" introuvable`, 'error');
    }
}

window.closeModal = function (modalId) {
    const modal = document.getElementById(modalId);

    if (modal) {
        modal.classList.remove('show');
    } else {
        console.error(`Modal with id "${modalId}" not found!`);
    }
}

        // Close modal on background click
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal')) {
                e.target.classList.remove('show');
            }
        });

        // Toast notification
        window.showToast = function(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = 'toast';
            toast.textContent = message;
            if (type === 'error') toast.style.background = 'var(--red)';
            if (type === 'info') toast.style.background = 'var(--blue)';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        // Form submission simulation
        document.addEventListener('submit', function(e) {
            if (e.target.classList.contains('form-action')) {
                e.preventDefault();
                const modalId = e.target.dataset.modalId;
                closeModal(modalId);
                showToast('Données enregistrées avec succès!');
            }
        });

