// ── SEARCH BAR ──
document.addEventListener('DOMContentLoaded', () => {
  const searchInput = document.getElementById('header-search');
  if (!searchInput) return;

  searchInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
      const query = searchInput.value.trim();
      if (query) {
        console.log('Recherche:', query);
        searchInput.value = '';
      }
    }
  });

  searchInput.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      searchInput.blur();
      searchInput.value = '';
    }
  });
});
