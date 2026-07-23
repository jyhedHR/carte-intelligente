// ── USER DROPDOWN + BACK TO TOP ──
document.addEventListener('DOMContentLoaded', () => {

  // User dropdown
  const userBtn = document.getElementById('userBtn');
  const dropdown = document.getElementById('userDropdown');
  if (userBtn && dropdown) {
    userBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      dropdown.classList.toggle('active');
    });
    dropdown.addEventListener('click', (e) => e.stopPropagation());
    document.addEventListener('click', () => dropdown.classList.remove('active'));
  }

  // Back to top
  const btn = document.getElementById('backToTop');
  if (btn) {
    // FIX: addEventListener instead of window.onscroll (never overwrites other scroll listeners)
    window.addEventListener('scroll', () => {
      btn.style.display = (document.documentElement.scrollTop > 200) ? 'block' : 'none';
    });
    btn.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }
});
