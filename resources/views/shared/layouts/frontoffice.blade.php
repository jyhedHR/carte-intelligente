<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">  {{-- ← ADD THIS --}}
    <title>GED — Portail des Démarches Culturelles</title>
    @vite(['resources/assets/css/shared.css'])

    <!-- Leaflet.js CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
     @vite(['resources/assets/css/base.css'])
     @vite(['resources/assets/css/starfield.css'])
     @vite(['resources/assets/css/hero.css'])
    @vite(['resources/assets/css/map.css'])
    @vite(['resources/assets/css/locations.css'])
    @vite(['resources/assets/css/services.css'])
    @vite(['resources/assets/css/pillars.css'])
    @vite(['resources/assets/css/responsive.css'])

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>


    @vite(['resources/assets/css/header.css'])
    @vite(['resources/assets/css/slider.css'])
    @vite(['resources/assets/css/about.css'])
    @vite(['resources/assets/css/sponsors.css'])
    @vite(['resources/assets/css/news.css'])
    @vite(['resources/assets/css/footer-utils.css'])
    @vite(['resources/assets/css/modern-chatbot.css'])
    @vite(['resources/assets/css/rtl.css'])
    @vite(['resources/assets/css/diversite.css'])
    @vite(['resources/assets/css/manifestation.css'])
    @vite(['resources/assets/css/spy-nav.css'])
</head>

<body>
    <div class="bg-canvas"></div>
    <div class="bg-grid"></div>
    <div class="bg-ornament top-right">م</div>
    <div class="bg-ornament bot-left">ث</div>


@include('shared.partials.frontoffice_header')

<!-- Main Content -->
<main class="main-area fix">
    @yield('content')
</main>

<!-- Footer -->
@include('shared.partials.frontoffice_footer')
        <!-- ══ FLOATING AI CHATBOT ══ -->

        <!-- ══ SCRIPTS ══ -->
        {{-- <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script> --}}


<!-- ═══ PAGE PRELOADER ═══ -->
<script>
  (function() {
    var theme = localStorage.getItem('theme') || 'dark';
    document.documentElement.setAttribute('data-preloader-theme', theme);
  })();
</script>

<style>
  #page-preloader {
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: #16161e;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.4s ease, visibility 0.4s ease;
  }
  [data-preloader-theme="light"] #page-preloader {
    background: #f5f5f0;
  }
  #page-preloader.hidden {
    opacity: 0;
    visibility: hidden;
  }
  .preloader-inner {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
  }
  .preloader-spinner {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    border: 3px solid rgba(212, 175, 55, 0.15);
    border-top-color: #D4AF37;
    animation: preloader-spin 0.85s linear infinite;
  }
  @keyframes preloader-spin {
    to { transform: rotate(360deg); }
  }
  .preloader-title {
    font-size: 13px;
    font-weight: 600;
    color: #D4AF37;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    margin: 0;
  }
  .preloader-sub {
    font-size: 12px;
    color: rgba(128, 128, 128, 0.6);
    margin: -8px 0 0;
  }
  .preloader-bar {
    width: 160px;
    height: 2px;
    background: rgba(212, 175, 55, 0.12);
    border-radius: 2px;
    overflow: hidden;
  }
  .preloader-bar-fill {
    height: 100%;
    width: 0%;
    background: #D4AF37;
    animation: preloader-fill 1.6s ease-in-out forwards;
  }
  @keyframes preloader-fill {
    0%   { width: 0%; }
    70%  { width: 80%; }
    100% { width: 100%; }
  }
</style>

<div id="page-preloader">
  <div class="preloader-inner">
    <div class="preloader-spinner"></div>
    <p class="preloader-title">Ministère des Affaires Culturelles</p>
    <p class="preloader-sub">GED Admin</p>
    <div class="preloader-bar"><div class="preloader-bar-fill"></div></div>
  </div>
</div>
<div class="gtranslate_wrapper" style="display:none;"></div>
<script>
  window.addEventListener('load', function () {
    const el = document.getElementById('page-preloader');
    if (el) setTimeout(function () { el.classList.add('hidden'); }, 300);
  });
</script>
<!-- ═══ END PRELOADER ═══ -->








        <!-- GTranslate: these 2 MUST stay inline in HTML, order matters -->
        <script>
            window.gtranslateSettings = {
                "default_language": "ang",
                "languages": ["fr", "ar"],
                "url_structure": "none",
                "flag_style": "3d",
                "wrapper_selector": ".gtranslate_wrapper",
                "float_switcher_open_direction": "bottom",
                "switcher_horizontal_position": "center",
                "switcher_vertical_position": "top",
                "alt_flags": true
            };
        </script>
        <script src="https://cdn.gtranslate.net/widgets/latest/float.js"></script>

        <!-- Your scripts — defer so they wait for the DOM -->
        @vite(['resources/js/app.js'])
        @vite(['resources/assets/js/diversite.js'])
        @vite(['resources/assets/js/i18n.js'])
        @vite(['resources/assets/js/shared.js'])
        @vite(['resources/assets/js/lang-buttons.js'])
        @vite(['resources/assets/js/header.js'])
        @vite(['resources/assets/js/search.js'])
        @vite(['resources/assets/js/slider.js'])
        @vite(['resources/assets/js/animations.js'])
        @vite(['resources/assets/js/chat.js'])
        @vite(['resources/assets/js/map.js'])
        @vite(['resources/assets/js/ui.js'])
        @vite(['resources/assets/js/gtranslate.js'])
        @vite(['resources/assets/js/services-video.js'])
        @vite(['resources/assets/js/manifestation.js'])
        @vite(['resources/assets/js/spy-nav.js'])

</body>

</html>
