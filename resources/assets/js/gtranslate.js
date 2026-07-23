// ── UNIFIED GTRANSLATE HANDLER — works on both frontoffice and backoffice ──
(function () {

  // Clean up old conflicting localStorage key from previous broken implementation
  localStorage.removeItem('language');

  // ── DOM direction + button states only — GTranslate handles actual translation ──
  function applyRTL() {
    document.documentElement.lang = 'ar';
    document.documentElement.dir = 'rtl';
    document.body.classList.add('lang-ar');
    document.body.classList.remove('lang-fr');
    document.querySelectorAll('[data-lang="fr"], .lang-btn-fr')
      .forEach(b => b.classList.remove('active'));
    document.querySelectorAll('[data-lang="ar"], .lang-btn-ar')
      .forEach(b => b.classList.add('active'));
  }

  function applyLTR() {
    document.documentElement.lang = 'fr';
    document.documentElement.dir = 'ltr';
    document.body.classList.add('lang-fr');
    document.body.classList.remove('lang-ar');
    document.querySelectorAll('[data-lang="ar"], .lang-btn-ar')
      .forEach(b => b.classList.remove('active'));
    document.querySelectorAll('[data-lang="fr"], .lang-btn-fr')
      .forEach(b => b.classList.add('active'));
  }

  // ── Public API ──
window.switchLang = function (lang) {
  var dd = document.getElementById('lang-dropdown');
  if (dd) dd.classList.remove('open');

  if (lang === 'ar') {
    applyRTL();
    localStorage.setItem('ged_lang', 'ar');
    if (typeof doGTranslate === 'function') {
      doGTranslate('fr|ar');
    }
  } else {
    applyLTR();
    localStorage.setItem('ged_lang', 'fr');
    // Nuke the GTranslate cookie and hard reload — only way to get original text back
    var base = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
    document.cookie = base;
    document.cookie = base + '; domain=' + location.hostname;
    document.cookie = base + '; domain=.' + location.hostname;
    location.reload();
  }
};
  // Backward compat — backoffice header calls switchLanguage()
  window.switchLanguage = window.switchLang;

  document.addEventListener('DOMContentLoaded', function () {

    // ── Wire up earth icon dropdown (auth/front pages) ──
    var langBtn = document.getElementById('custom-lang-btn');
    var langDropdown = document.getElementById('lang-dropdown');
    if (langBtn && langDropdown) {
      langBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        langDropdown.classList.toggle('open');
      });
      document.addEventListener('click', function () {
        langDropdown.classList.remove('open');
      });
    }

    // ── Restore saved direction/button state ──
    // GTranslate restores translation via its own cookie — we just sync the DOM
    var saved = localStorage.getItem('ged_lang') || 'fr';
    if (saved === 'ar') {
      applyRTL();
    } else {
      applyLTR();
    }

  });

})();
