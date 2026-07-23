<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#D4AF37">
    <meta name="description" content="Portail des démarches culturelles - Authentification">
    <title>@yield('title', 'Authentification') — GED Portail Culturel</title>

    {{-- Shared styles (your existing file) --}}
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
    @vite(['resources/assets/css/rtl.css'])
    @vite(['resources/assets/css/login.css'])



    @stack('styles')
</head>

<body>
    <div aria-hidden="true"
        style="
  position: fixed;
  inset: 0;
  z-index: 0;
  pointer-events: none;
  overflow: hidden;
">
        <!-- Top-left -->
        <img src="{{ Vite::asset('resources/assets/images/arabic-calligraphy4-Photoroom.png') }}"
            style="
    position: absolute;
    top: 5vh; left: -5vw;
    width: 65vw; opacity: 0.35;
    transform: rotate(-18deg);
    mix-blend-mode: screen;
    user-select: none;
  ">
        <!-- Bottom-right -->
        <img src="{{ Vite::asset('resources/assets/images/arabic-calligraphy5-Photoroom.png') }}"
            style="
    position: absolute;
    bottom: 5vh; right: -5vw;
    width: 62vw; opacity: 0.32;
    transform: rotate(14deg);
    mix-blend-mode: screen;
    user-select: none;
  ">
        <!-- Mid-left -->
        <img src="{{ Vite::asset('resources/assets/images/arabic-calligraphy3-Photoroom.png') }}"
            style="
    position: absolute;
    top: 42vh; left: -8vw;
    width: 58vw; opacity: 0.28;
    transform: rotate(-8deg);
    mix-blend-mode: screen;
    user-select: none;
  ">
        <!-- Mid-right -->
        <img src="{{ Vite::asset('resources/assets/images/arabic-calligraphy2-Photoroom.png') }}"
            style="
    position: absolute;
    top: 28vh; right: -6vw;
    width: 60vw; opacity: 0.30;
    transform: rotate(20deg);
    mix-blend-mode: screen;
    user-select: none;
  ">
        {{--
    TO ADD A VIDEO BACKGROUND:
    1. Uncomment the <video> tag below
    2. Place your video file in public/videos/ folder
    3. Update the src path to your video
    4. Uncomment the video CSS styles in login.css
    --}}

    <div class="video-container">
    <img src="{{ asset('login_gif7.gif') }}" class="video-bg" alt="login background">

    <div class="video-overlay"></div>
</div>

    </div>
    <div class="page-wrap">
        <!-- Background decorations (hidden on mobile) -->

        <div class="bg-canvas"></div>
        <div class="bg-grid"></div>

        <!-- Top-right controls -->
        <div class="auth-topnav">

            <div class="custom-lang-btn" id="custom-lang-btn" title="Changer la langue">
                <img src="{{ Vite::asset('resources/assets/images/earth_icon.png') }}" alt="langue"
                    style="width:28px; height:28px; object-fit:contain; pointer-events:none;">
                <div class="lang-dropdown" id="lang-dropdown">
                    <button onclick="switchLang('fr')">Français</button>
                    <button onclick="switchLang('ar')">العربية</button>
                </div>
            </div>
            <a class="btn btn-ghost btn-sm" href="{{ route('home') }}">Accueil</a>
        </div>
        <div class="gtranslate_wrapper" style="display:none " id="gt-wrapper-27219054"></div>
        <div class="login-layout">
            <!-- ══ FORM PANEL ══ -->
            <div class="login-right">
                <div class="login-form-wrap">

                    <!-- Mobile brand -->
                    <div class="mobile-brand">
                        <div class="logo-mark">م</div>
                        <span style="font-size:13px;font-weight:700">وزارة الشؤون الثقافية</span>
                        <span style="font-size:11px;color:var(--text3);margin-top:3px">Portail des Démarches
                            Culturelles</span>
                    </div>

                    @yield('form-content')

                </div>
            </div>
        </div>





        <script>
            function togglePass(id, btn) {
                const inp = document.getElementById(id);
                if (inp.type === 'password') {
                    inp.type = 'text';
                    btn.textContent = '🙈';
                } else {
                    inp.type = 'password';
                    btn.textContent = '👁';
                }
            }
        </script>
<script>
  // GTranslate element init — no auto-reload, no float widget
  function googleTranslateElementInit() {
    new google.translate.TranslateElement({
      pageLanguage: 'fr',
      includedLanguages: 'fr,ar',
      autoDisplay: false,
      layout: google.translate.TranslateElement.InlineLayout.SIMPLE
    }, 'gt-wrapper-27219054');
  }
</script>
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        <!-- Your scripts — defer so they wait for the DOM -->
        @vite(['resources/js/app.js'])
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

        @stack('scripts')
</body>

</html>
