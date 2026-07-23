window.switchLang = function (lang) {
  // Close any dropdown
  var dd = document.getElementById('lang-dropdown');
  if (dd) dd.classList.remove('open');

  if (lang === 'ar') {
    applyRTL();
    localStorage.setItem('ged_lang', 'ar');
    // Let GTranslate handle translation + its reload
    if (typeof doGTranslate === 'function') {
      doGTranslate('fr|ar');
    }

  } else {
    // French = full reset: clear GTranslate cookie then reload clean
    applyLTR();
    localStorage.setItem('ged_lang', 'fr');

    // Clear the googtrans cookie on all domain variants
    var cookieBase = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
    document.cookie = cookieBase;
    document.cookie = cookieBase + '; domain=' + location.hostname;
    document.cookie = cookieBase + '; domain=.' + location.hostname;

    // Reload without GTranslate active — page loads in original French
    location.reload();
  }
};
