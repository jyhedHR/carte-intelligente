<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
     <script src="https://cdn.jsdelivr.net/npm/formiojs@4.21.6/dist/formio.full.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- For Opera and other browsers -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>GED Admin — Ministère des Affaires Culturelles</title>
     <!-- BPMN Modeler CSS -->
<link rel="stylesheet" href="https://unpkg.com/bpmn-js@17.11.1/dist/assets/diagram-js.css" />
<link rel="stylesheet" href="https://unpkg.com/bpmn-js@17.11.1/dist/assets/bpmn-js.css" />
<link rel="stylesheet" href="https://unpkg.com/bpmn-js@17.11.1/dist/assets/bpmn-font/css/bpmn.css" />
      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>



    <!-- Changed By khouloud -->
    <!-- Inter for French + Cairo for Arabic -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,100;14..32,200;14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800;14..32,900&family=Cairo:wght@200;300;400;500;600;700;800;900;1000&display=swap" rel="stylesheet">



    <!--<link
        href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Syne:wght@400;500;600;700;800&family=IBM+Plex+Sans+Arabic:wght@300;400;500;600&display=swap" rel="stylesheet">-->
<script type="importmap">
{
  "imports": {
    "@pdfme/common": "https://esm.sh/@pdfme/common@latest",
    "@pdfme/ui": "https://esm.sh/@pdfme/ui@latest",
    "@pdfme/generator": "https://esm.sh/@pdfme/generator@latest",
    "@pdfme/schemas": "https://esm.sh/@pdfme/schemas@latest"
  }
}
</script>
    <script src="https://cdn.tailwindcss.com"></script>
     <style>
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal.show { display: flex; }
        .modal-content {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease;
        }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .modal-header {
            padding: 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-title { font-size: 15px; font-weight: 700; color: var(--text); }
        .modal-close {
            background: none;
            border: none;
            font-size: 20px;
            color: var(--text3);
            cursor: pointer;
        }
        .modal-body { padding: 20px; }
        .modal-footer {
            padding: 16px 20px;
            border-top: 1px solid var(--border);
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 6px;
        }
        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 10px 12px;
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text);
            font-family: var(--font-body);
            font-size: 13px;
            box-sizing: border-box;
        }
        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 3px var(--gold-dim);
        }
        .form-textarea { resize: vertical; min-height: 100px; }
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--green);
            color: white;
            padding: 14px 20px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            z-index: 2000;
            animation: slideInRight 0.3s ease;
        }
        @keyframes slideInRight {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
    @vite(['resources/assets/css/backend.css'])
    @vite(['resources/assets/css/calendar.css'])
    @vite(['resources/assets/css/impresarios.css'])
    @vite(['resources/assets/css/theme.css'])
    @vite(['resources/assets/css/investisseurs.css'])
    @vite(['resources/assets/css/livre.css'])
    @vite(['resources/assets/css/map-backoffice.css'])

    <link href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.css" rel="stylesheet">


@stack('styles')
</head>

<body>
     <!-- ══ TOP SETUP GUIDE BAR ══ -->
  <div id="setupGuideTopbar" class="setup-guide-topbar">
    <div class="setup-guide-container">
        <div class="setup-guide-header">
            <div class="setup-guide-title">

            </div>
            <div class="setup-guide-actions">
                <!-- Circular progress ring -->
                <div class="progress-ring-wrap">
                    <svg width="36" height="36" viewBox="0 0 36 36">
                        <circle class="progress-ring-bg" cx="18" cy="18" r="14"/>
                        <circle class="progress-ring-fill" id="progressRingFill" cx="18" cy="18" r="14"/>
                    </svg>
                    
                </div>

            </div>
        </div>
        <div class="setup-guide-progress">
            <div class="progress-bar-container">
                <div class="progress-steps" id="progressSteps">
                    <!-- Steps inserted dynamically -->
                </div>
            </div>
        </div>
    </div>
</div>
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
    <div class="light-bg-overlay" aria-hidden="true"></div> <!-- default to dark, toggle will change to 'light' -->

    <!-- Background Calligraphy Layer (spans entire page) -->









    <div class="shell">

    {{-- ══ BACKGROUND CALLIGRAPHY LAYER (Dark Mode Only) ══ --}}
    <div aria-hidden="true" class="backend-calli-layer">
        {{-- Top-left --}}
        <img src="{{ Vite::asset('resources/assets/images/arabic-calligraphy4-Photoroom.png') }}"
             class="bc-img bc-top-left">

        {{-- Bottom-right --}}
        <img src="{{ Vite::asset('resources/assets/images/arabic-calligraphy5-Photoroom.png') }}"
             class="bc-img bc-bottom-right">

        {{-- Mid-left --}}
        <img src="{{ Vite::asset('resources/assets/images/arabic-calligraphy3-Photoroom.png') }}"
             class="bc-img bc-mid-left">

        {{-- Mid-right --}}
        <img src="{{ Vite::asset('resources/assets/images/arabic-calligraphy2-Photoroom.png') }}"
             class="bc-img bc-mid-right">

        {{-- Lower-left --}}
        <img src="{{ Vite::asset('resources/assets/images/arabic-calligraphy1-Photoroom.png') }}"
             class="bc-img bc-lower-left">
    </div>


        @include('shared.partials.backoffice_sidebar')

        <!-- Main Content -->
        <main class="main">
            @include('shared.partials.backoffice_header')
            @yield('content')
        </main>
    </div>


    <!-- Footer -->



<!-- Camunda BPMN Modeler with Properties Panel -->
<script src="https://unpkg.com/bpmn-js@11.5.0/dist/bpmn-modeler.development.js"></script>
<script src="https://unpkg.com/bpmn-js-properties-panel@1.8.0/dist/assets/bpmn-js-properties-panel.js"></script>
<script src="https://unpkg.com/bpmn-js-properties-panel@1.8.0/dist/assets/BpmnPropertiesPanel.js"></script>
<script src="https://unpkg.com/bpmn-js-properties-panel@1.8.0/dist/assets/PropertiesPanel.js"></script>

    <script src="https://unpkg.com/bpmn-js@17.11.1/dist/bpmn-modeler.development.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.js"></script>
        <!-- BPMN Modeler JS -->
<!-- GTranslate — same as frontoffice -->
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

    <!-- ══ DETAIL SHEET (dossier) ══ -->
    @vite('resources/assets/js/backend.js')
    @vite(['resources/assets/js/gtranslate.js'])
    @vite(['resources/assets/js/calendar.js'])
    @vite(['resources/assets/js/impresarios.js'])
    @vite(['resources/assets/js/graph.js'])
    @vite(['resources/assets/js/admin.js'])
    @vite(['resources/assets/js/livre.js'])
    @vite(['resources/assets/js/investisseurs.js'])

@stack('scripts')

</body>

</html>
