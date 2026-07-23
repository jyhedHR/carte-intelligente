// ── SCROLL REVEAL + STAT COUNTERS ──
document.addEventListener('DOMContentLoaded', () => {

  // Scroll-reveal for .anim elements
  const revealObserver = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) e.target.classList.add('visible');
    });
  }, { threshold: 0.15 });

  document.querySelectorAll('.anim').forEach(el => revealObserver.observe(el));

  // Animated stat counters
  function animateCounter(el, target, suffix = '') {
    if (!el) return;
    let val = 0;
    const step = Math.ceil(target / 40);
    const timer = setInterval(() => {
      val = Math.min(val + step, target);
      el.textContent = val + suffix;
      if (val >= target) clearInterval(timer);
    }, 40);
  }

  const aboutStats = document.querySelector('.about-stats');
  if (aboutStats) {
    const statsObserver = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          animateCounter(document.getElementById('astat1'), 18);
          animateCounter(document.getElementById('astat2'), 4200, '+');
          animateCounter(document.getElementById('astat3'), 48, 'h');
          statsObserver.disconnect();
        }
      });
    }, { threshold: 0.4 });
    statsObserver.observe(aboutStats);
  }
});
