document.addEventListener('DOMContentLoaded', function() {
  const CARDS_PER_PAGE = 6;
  const AUTO_PLAY_INTERVAL = 3000;

  let currentSector = 0;
  let currentPage = 0;
  let autoPlayTimer = null;

  const groups = document.querySelectorAll('.div-slide-group');
 const tabs = document.querySelectorAll('#diversite .div-tab');
  const prevBtn = document.querySelector('.div-arrow.div-prev');
  const nextBtn = document.querySelector('.div-arrow.div-next');
  const dotsWrap = document.getElementById('diversite-dots');
  const sliderWrap = document.querySelector('.diversite-slider-wrap');

  const totalSectors = groups.length;

  function pagesInSector(sector) {
    const cards = groups[sector] ? groups[sector].querySelectorAll('.div-card') : [];
    return Math.ceil(cards.length / CARDS_PER_PAGE) || 1;
  }

  function showCards() {
    groups.forEach((g, i) => {
      if (i === currentSector) {
        g.classList.add('active');
      } else {
        g.classList.remove('active');
      }
    });

    const currentGroup = groups[currentSector];
    if (!currentGroup) return;

    const cards = currentGroup.querySelectorAll('.div-card');
    const start = currentPage * CARDS_PER_PAGE;
    const end = start + CARDS_PER_PAGE;

    cards.forEach((card, i) => {
      if (i >= start && i < end) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });

    // Arrows navigate between SECTORS
    if (prevBtn) prevBtn.disabled = (currentSector === 0);
    if (nextBtn) nextBtn.disabled = (currentSector >= totalSectors - 1);

    updateTabs();
    buildDots();
  }

  function updateTabs() {
    tabs.forEach((tab, i) => {
      if (i === currentSector) {
        tab.classList.add('active');
      } else {
        tab.classList.remove('active');
      }
    });
  }

  function buildDots() {
    if (!dotsWrap) return;
    dotsWrap.innerHTML = '';

    // Dots now represent SECTORS, not pages
    for (let i = 0; i < totalSectors; i++) {
      const dot = document.createElement('button');
      dot.className = 'div-dot' + (i === currentSector ? ' active' : '');
      dot.addEventListener('click', function() {
        currentSector = i;
        currentPage = 0;
        showCards();
        resetAutoPlay();
      });
      dotsWrap.appendChild(dot);
    }
  }

  function autoAdvance() {
    // Go to next sector (loop back to first)
    currentSector = (currentSector + 1) % totalSectors;
    currentPage = 0;
    showCards();
  }

  function startAutoPlay() {
    if (autoPlayTimer) clearInterval(autoPlayTimer);
    autoPlayTimer = setInterval(autoAdvance, AUTO_PLAY_INTERVAL);
  }

  function stopAutoPlay() {
    if (autoPlayTimer) {
      clearInterval(autoPlayTimer);
      autoPlayTimer = null;
    }
  }

  function resetAutoPlay() {
    stopAutoPlay();
    startAutoPlay();
  }

  if (sliderWrap) {
    sliderWrap.addEventListener('mouseenter', stopAutoPlay);
    sliderWrap.addEventListener('mouseleave', startAutoPlay);
  }

  // Arrow handlers - navigate between SECTORS
  if (prevBtn) {
    prevBtn.addEventListener('click', function() {
      if (currentSector > 0) {
        currentSector--;
        currentPage = 0;
        showCards();
        resetAutoPlay();
      }
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', function() {
      if (currentSector < totalSectors - 1) {
        currentSector++;
        currentPage = 0;
        showCards();
        resetAutoPlay();
      }
    });
  }

  // Tab click handlers
  tabs.forEach((tab, i) => {
    tab.addEventListener('click', function() {
      currentSector = i;
      currentPage = 0;
      showCards();
      resetAutoPlay();
    });
  });

  // Initialize
  showCards();
  startAutoPlay();
});
