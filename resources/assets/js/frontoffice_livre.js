/**
 * GED Frontoffice - Citoyen / Éditeur
 * Version: 2026
 */

document.addEventListener('DOMContentLoaded', function () {

    console.log('%c✅ Frontoffice initialized - Ministry of Culture 2026', 'color: #d4af77; font-weight: bold');

    // Toggle Sidebar on mobile
    window.toggleSidebar = function() {
        const sidebar = document.querySelector('.frontoffice-sidebar');
        if (sidebar) {
            sidebar.classList.toggle('-translate-x-full');
        }
    };

    // Language switcher (demo)
    window.switchLanguage = function(lang) {
        alert(`🌐 Langue changée en : ${lang.toUpperCase()}\n(Support RTL complet à venir)`);
    };

    // Render dynamic requests if element exists
    const requestsContainer = document.getElementById('myRequests');
    if (requestsContainer) {
        const mockRequests = [
            { id: "LIV-DRO-20260012", title: "Le Chant des Dunes", status: "En cours", progress: 65 },
            { id: "LIV-DRO-20260008", title: "Mémoires d’un Tunisien", status: "Validée", progress: 100 },
            { id: "LIV-DRO-20260003", title: "Poésie Moderne", status: "Signature en attente", progress: 85 }
        ];

        let html = '';
        mockRequests.forEach(req => {
            html += `
                <div onclick="window.location.href='/citoyen/livre/show/${req.id}'"
                     class="stat-card cursor-pointer">
                    <div class="font-medium text-lg">${req.title}</div>
                    <div class="text-sm text-zinc-400 mt-2">${req.id}</div>
                    <div class="mt-6 flex justify-between items-end">
                        <span class="text-amber-400 text-sm">${req.status}</span>
                        <span class="text-xs text-zinc-500">${req.progress}%</span>
                    </div>
                </div>
            `;
        });
        requestsContainer.innerHTML = html;
    }
});
