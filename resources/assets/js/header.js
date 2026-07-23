document.addEventListener('DOMContentLoaded', () => {
  const header = document.getElementById('main-header');
  const logoImg = document.getElementById('logo-img');

  const handleScroll = () => {
    if (header) {
      header.classList.toggle('scrolled', window.scrollY > 80);
    }

    if (logoImg) {
      const scrolledLogo = logoImg.dataset.logoScrolled;
      const originalLogo = logoImg.src;

      if (window.scrollY > 50) {
        if (!logoImg.src.includes('logo1')) {
          logoImg.src = scrolledLogo;
        }
      } else {
        if (logoImg.src !== originalLogo) {
          logoImg.src = logoImg.dataset.original || originalLogo;
        }
      }
    }
  };

  window.addEventListener('scroll', handleScroll);
  handleScroll();
});

// ==================== MOBILE NAV TOGGLE - FULL SCREEN MENU ====================
document.addEventListener('DOMContentLoaded', function () {
  const hamburgerBtn = document.getElementById('hamburger-btn');
  const headerNav = document.getElementById('header-nav');
  const overlay = document.getElementById('mobile-nav-overlay');

  if (!hamburgerBtn || !headerNav) return;

  function openMenu() {
    hamburgerBtn.classList.add('open');
    headerNav.classList.add('open');
    if (overlay) overlay.classList.add('open');
    hamburgerBtn.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }

  function closeMenu() {
    hamburgerBtn.classList.remove('open');
    headerNav.classList.remove('open');
    if (overlay) overlay.classList.remove('open');
    hamburgerBtn.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
    headerNav.querySelectorAll('.nav-item.mobile-open').forEach(function (item) {
      item.classList.remove('mobile-open');
    });
  }

  hamburgerBtn.addEventListener('click', function () {
    headerNav.classList.contains('open') ? closeMenu() : openMenu();
  });

  if (overlay) {
    overlay.addEventListener('click', closeMenu);
  }

  // Close menu when clicking on plain navigation links
  headerNav.querySelectorAll('.header-link').forEach(function (link) {
    link.addEventListener('click', function (e) {
      const parent = link.closest('.nav-item');
      if (parent && parent.querySelector('.dropdown-menu.mega-menu')) {
        return; // Don't close for accordion items
      }
      if (!e.defaultPrevented) {
        closeMenu();
      }
    });
  });

  // Close on viewport resize back to desktop
  window.addEventListener('resize', function () {
    if (window.innerWidth > 900 && headerNav.classList.contains('open')) {
      closeMenu();
    }
  });

  // Accordion mega-menus on mobile
  headerNav.querySelectorAll('.nav-item').forEach(function (item) {
    const megaMenu = item.querySelector('.dropdown-menu.mega-menu');
    const link = item.querySelector('.header-link');
    if (!megaMenu || !link) return;

    link.addEventListener('click', function (e) {
      if (window.innerWidth > 900) return;
      e.preventDefault();
      const isOpen = item.classList.contains('mobile-open');
      headerNav.querySelectorAll('.nav-item.mobile-open').forEach(function (other) {
        if (other !== item) other.classList.remove('mobile-open');
      });
      item.classList.toggle('mobile-open', !isOpen);
    });
  });

  // Language button interactions
  const langButtons = document.querySelectorAll('.mobile-lang-btn');
  langButtons.forEach(function (btn) {
    btn.addEventListener('click', function () {
      langButtons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      setTimeout(closeMenu, 300);
    });
  });
});

// ==================== DYNAMIC MULTI-COLOR LOGO ====================
function updateLogoColor() {
  const logo = document.getElementById('logo-img');
  if (!logo) return;

  let targetFilter = 'none';

  const sections = document.querySelectorAll('section, .hero, .diversite-section, .services-section, .about-section, .pillars-section, .manifestation-section');

  for (let section of sections) {
    const rect = section.getBoundingClientRect();

    if (rect.top <= 120 && rect.bottom >= 60) {
      const bg = window.getComputedStyle(section).backgroundColor;

      if (isDark(bg)) {
        targetFilter = 'brightness(0) invert(1)';
      }
      else if (isGreen(bg)) {
        targetFilter = 'hue-rotate(85deg) saturate(2.2) brightness(1.15)';
      }
      else if (isBlue(bg)) {
        targetFilter = 'hue-rotate(210deg) saturate(1.8) brightness(1.1)';
      }
      else if (isTeal(bg)) {
        targetFilter = 'hue-rotate(170deg) saturate(2) brightness(1.1)';
      }
      else if (isPurple(bg)) {
        targetFilter = 'hue-rotate(270deg) saturate(1.7) brightness(1.05)';
      }
      else if (isRed(bg)) {
        targetFilter = 'hue-rotate(0deg) saturate(2.5) brightness(1.2)';
      }
      else if (isLight(bg)) {
        targetFilter = 'none';
      }
      break;
    }
  }

  logo.style.filter = targetFilter;
}

function isDark(color) {
  if (!color) return true;
  const rgb = color.match(/\d+/g);
  if (!rgb) return true;
  const brightness = (parseInt(rgb[0]) * 0.299 + parseInt(rgb[1]) * 0.587 + parseInt(rgb[2]) * 0.114);
  return brightness < 90;
}

function isLight(color) {
  if (!color) return false;
  const rgb = color.match(/\d+/g);
  if (!rgb) return false;
  const brightness = (parseInt(rgb[0]) * 0.299 + parseInt(rgb[1]) * 0.587 + parseInt(rgb[2]) * 0.114);
  return brightness > 200;
}

function isGreen(color) { return color && (color.includes('rgb(0, 128') || color.includes('rgb(34, 197') || color.toLowerCase().includes('green')); }
function isBlue(color) { return color && (color.includes('rgb(59, 130') || color.includes('rgb(37, 99') || color.toLowerCase().includes('blue')); }
function isTeal(color) { return color && (color.includes('rgb(45, 212') || color.includes('rgb(20, 184') || color.toLowerCase().includes('teal')); }
function isPurple(color) { return color && (color.includes('rgb(168, 85') || color.includes('rgb(147, 51') || color.toLowerCase().includes('purple')); }
function isRed(color) { return color && (color.includes('rgb(239, 68') || color.includes('rgb(248, 113') || color.toLowerCase().includes('red')); }

window.addEventListener('scroll', updateLogoColor);
window.addEventListener('load', updateLogoColor);
window.addEventListener('resize', updateLogoColor);

// ==================== MOBILE SEARCH TOGGLE ====================
document.addEventListener('DOMContentLoaded', () => {
  const searchContainer = document.querySelector('.search-container');
  const searchIcon = document.querySelector('.search-icon');
  const searchInput = document.getElementById('header-search');

  if (!searchContainer || !searchIcon || !searchInput) return;

  searchIcon.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();
    const isOpen = searchContainer.classList.toggle('search-open');
    if (isOpen) {
      setTimeout(() => searchInput.focus(), 50);
    } else {
      searchInput.blur();
    }
  });

  document.addEventListener('click', (e) => {
    if (!searchContainer.contains(e.target)) {
      searchContainer.classList.remove('search-open');
    }
  });

  searchInput.addEventListener('keyup', (e) => {
    if (e.key === 'Escape') {
      searchContainer.classList.remove('search-open');
      searchInput.blur();
    }
  });
});

// ==================== MOBILE FIXED-ELEMENT REPARENTING ====================
(function () {
  const mq = window.matchMedia('(max-width: 900px)');
  const idTargets = ['hamburger-btn', 'header-nav'];
  const selectorTargets = ['.search-container', '.mode-toggle', '.user-menu'];

  const tracked = [];

  function trackOriginalPositions() {
    idTargets.forEach((id) => {
      const el = document.getElementById(id);
      if (el) tracked.push({ el, parent: el.parentNode, next: el.nextSibling });
    });
    selectorTargets.forEach((sel) => {
      const el = document.querySelector(sel);
      if (el) tracked.push({ el, parent: el.parentNode, next: el.nextSibling });
    });
  }

  function moveToBody() {
    tracked.forEach(({ el }) => {
      if (el.parentNode !== document.body) {
        document.body.appendChild(el);
      }
    });
  }

  function restoreOriginal() {
    tracked.forEach(({ el, parent, next }) => {
      if (!parent || el.parentNode === parent) return;
      if (next && next.parentNode === parent) {
        parent.insertBefore(el, next);
      } else {
        parent.appendChild(el);
      }
    });
  }

  function applyState(isMobile) {
    if (isMobile) {
      moveToBody();
    } else {
      restoreOriginal();
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    trackOriginalPositions();
    applyState(mq.matches);
  });

  if (mq.addEventListener) {
    mq.addEventListener('change', (e) => applyState(e.matches));
  } else if (mq.addListener) {
    mq.addListener((e) => applyState(e.matches));
  }
})();
