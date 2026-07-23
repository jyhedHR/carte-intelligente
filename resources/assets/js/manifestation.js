document.addEventListener('DOMContentLoaded', function() {

  // ── FILTER TABS ──
  const mfestTabs = document.querySelectorAll('#manifests [data-mfest]');
  const manifestCards = document.querySelectorAll('#manifests-grid .div-card');

  mfestTabs.forEach(tab => {
    tab.addEventListener('click', function() {
      mfestTabs.forEach(t => t.classList.remove('active'));
      this.classList.add('active');

      const filter = this.dataset.mfest;

      manifestCards.forEach(card => {
        const tag = card.dataset.tag;
        const match = filter === 'all' || tag === filter;

        // Respect show-more state
        const grid = document.getElementById('manifests-grid');
        const isExpanded = grid.classList.contains('expanded');
        const index = Array.from(manifestCards).indexOf(card);

        if (match) {
          card.style.display = (index >= 6 && !isExpanded) ? 'none' : 'flex';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });

  // ── SHOW MORE ──
  const showMoreBtn = document.getElementById('manifests-show-more');
  if (showMoreBtn) {
    showMoreBtn.addEventListener('click', function() {
      const grid = document.getElementById('manifests-grid');
      grid.classList.toggle('expanded');

      const activeFilter = document.querySelector('[data-mfest].active')?.dataset.mfest || 'all';

      manifestCards.forEach((card, i) => {
        if (i >= 6) {
          const tag = card.dataset.tag;
          const match = activeFilter === 'all' || tag === activeFilter;
          card.style.display = (grid.classList.contains('expanded') && match) ? 'flex' : 'none';
        }
      });

      this.textContent = grid.classList.contains('expanded')
        ? 'Voir moins ↑'
        : 'Voir plus d\'événements →';
    });
  }

});
