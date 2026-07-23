/* ══ SECTION SPY NAV ══ */
(function () {
  const nav = document.getElementById('section-spy-nav');
  if (!nav) return;

  const links = nav.querySelectorAll('.spy-nav-link');

  // Build a map of { href → section element }
  const sections = [];
  links.forEach(link => {
    const id = link.getAttribute('href');   // e.g. "#actualites"
    const el = document.querySelector(id);
    if (el) sections.push({ link, el });
  });

  // Click — smooth scroll
  links.forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      const id = link.getAttribute('href');
      const target = document.querySelector(id);
      if (!target) return;
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });

  // Scroll spy — highlight the section currently in view
  const observer = new IntersectionObserver(
    entries => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;

        // Find the matching link and activate it
        const activeSection = sections.find(s => s.el === entry.target);
        if (!activeSection) return;

        links.forEach(l => l.classList.remove('active'));
        activeSection.link.classList.add('active');
      });
    },
    {
      // trigger when section reaches ~20% from top of viewport
      rootMargin: '-10% 0px -80% 0px',
      threshold: 0
    }
  );

  sections.forEach(({ el }) => observer.observe(el));
})();
