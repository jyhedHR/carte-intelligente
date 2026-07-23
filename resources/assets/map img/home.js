
    
      // Language buttons
      document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.lang-btn').forEach(btn => {
          btn.addEventListener('click', () => {
            if (typeof applyLang === 'function') applyLang(btn.dataset.lang);
            document.querySelectorAll('.lang-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
          });
        });
        const savedLang = localStorage.getItem('ged_lang') || 'fr';
        document.querySelectorAll('.lang-btn').forEach(btn => {
          btn.classList.toggle('active', btn.dataset.lang === savedLang);
        });
      });
    

    
      // Header: show background only after scrolling past the hero
      window.addEventListener('scroll', () => {
        const header = document.getElementById('main-header');
        header.classList.toggle('scrolled', window.scrollY > 80);
      });
    


    

      const searchInput = document.getElementById('header-search');
      const searchIcon = document.querySelector('.search-icon');

      // Handle search submission
      searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
          const searchQuery = searchInput.value.trim();
          if (searchQuery) {
            console.log('Recherche:', searchQuery);

            searchInput.value = '';
          }
        }
      });

      // Close search on Escape key
      searchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
          searchInput.blur();
          searchInput.value = '';
        }
      });

      // Close dropdowns when clicking outside
      document.addEventListener('click', (e) => {
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
          if (!item.contains(e.target)) {

          }
        });
      });
    


    
      // Hero Slider with Arrows + Faster Speed
      let currentSlide = 0;
      const slides = document.querySelectorAll('.slide');
      const dotsContainer = document.getElementById('slider-dots');
      const prevArrow = document.getElementById('prev-arrow');
      const nextArrow = document.getElementById('next-arrow');

      // Create dots
      slides.forEach((_, index) => {
        const dot = document.createElement('div');
        dot.classList.add('dot');
        if (index === 0) dot.classList.add('active');
        dot.addEventListener('click', () => goToSlide(index));
        dotsContainer.appendChild(dot);
      });

      const dots = document.querySelectorAll('.dot');

      function goToSlide(n) {
        slides.forEach(s => s.classList.remove('active'));
        dots.forEach(d => d.classList.remove('active'));

        slides[n].classList.add('active');
        dots[n].classList.add('active');
        currentSlide = n;
      }


      function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        goToSlide(currentSlide);
      }

      function prevSlide() {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        goToSlide(currentSlide);
      }

      // Arrow clicks
      nextArrow.addEventListener('click', nextSlide);
      prevArrow.addEventListener('click', prevSlide);

      // Auto slide — faster (4.5 seconds)//badlatha lil slide asraa 
      let slideInterval = setInterval(nextSlide, 1800);

      // Pause on hover
      const slider = document.getElementById('hero-slider');
      slider.addEventListener('mouseenter', () => clearInterval(slideInterval));//slide lezem tezreb why not work
      slider.addEventListener('mouseleave', () => {
        slideInterval = setInterval(nextSlide, 1500);
      });
    

    
      // Scroll-reveal for .anim elements
      const observer = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
      }, { threshold: 0.15 });
      document.querySelectorAll('.anim').forEach(el => observer.observe(el));
    

    
      // Animated stat counters
      function animateCounter(el, target, suffix = '') {
        let val = 0;
        const step = Math.ceil(target / 40);
        const timer = setInterval(() => {
          val = Math.min(val + step, target);
          el.textContent = val + suffix;
          if (val >= target) clearInterval(timer);
        }, 40);
      }

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
      statsObserver.observe(document.querySelector('.about-stats'));
    

    
      // AI Chat
      function toggleChat() {
        const chat = document.getElementById('ai-chat');
        chat.style.display = (chat.style.display === 'flex') ? 'none' : 'flex';
      }

      function sendMessage() {
        const input = document.getElementById('chat-input');
        const body = document.getElementById('chat-body');
        if (!input.value.trim()) return;
        body.innerHTML += `<p style="text-align:right; margin:12px 0; color:var(--text);"><strong>Vous :</strong> ${input.value}</p>`;
        input.value = '';
        body.scrollTop = body.scrollHeight;
        setTimeout(() => {
          body.innerHTML += `<p style="margin:12px 0; color:var(--text2);"><strong>Assistant :</strong> Merci pour votre question. Je vous aide à remplir le formulaire pour la démarche sélectionnée.</p>`;
          body.scrollTop = body.scrollHeight;
        }, 800);
      }
    
    
      // ═════════════════════════════════════════════════
      // TUNISIA MAP WITH LEAFLET
      // ═════════════════════════════════════════════════

   const tunisiaCenter = [33.886917, 9.537499];
// Tighter bounds — cuts off neighboring countries
const tunisiaBounds = [[31.6366, 7.5244], [38.5409, 11.5657]];

const map = L.map('tunisia-map', {
  center: tunisiaCenter,
  zoom: 7,
  zoomControl: true,
  scrollWheelZoom: true,
  doubleClickZoom: true,
  maxBounds: tunisiaBounds,
  maxBoundsViscosity: 1.0,
  minZoom: 7,   // ← raised so user can't zoom out to see neighbors
  maxZoom: 12,
  dragging: true
});

map.on('drag', () => {
  map.panInsideBounds(tunisiaBounds, { animate: false });
});

// Dark tile layer
L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
  attribution: '© OpenStreetMap contributors © CARTO',
  maxZoom: 19
}).addTo(map);

// ── Tunisia BORDER MASK ──────────────────────────────────────────
// Fetch Tunisia's GeoJSON and add a dark overlay OUTSIDE the border
fetch('https://raw.githubusercontent.com/johan/world.geo.json/master/countries/TUN.geo.json')
  .then(r => r.json())
  .then(tunisiaGeoJSON => {
    // Build a "world minus Tunisia" polygon to darken everything outside
    const worldCoords = [
      [[-180, -90], [-180, 90], [180, 90], [180, -90], [-180, -90]]
    ];

    // Extract Tunisia's rings
    const tunisiaRings = [];
    tunisiaGeoJSON.features[0].geometry.coordinates.forEach(polygon => {
      polygon.forEach(ring => tunisiaRings.push(ring));
    });

    const maskGeoJSON = {
      type: "Feature",
      geometry: {
        type: "Polygon",
        coordinates: [
          worldCoords[0],   // outer ring (whole world)
          ...tunisiaRings   // inner rings (Tunisia cut-out)
        ]
      }
    };

    L.geoJSON(maskGeoJSON, {
      style: {
        fillColor: '#0a0806',   // match your .leaflet-container background
        fillOpacity: 1,
        color: '#c9a84c',       // golden border around Tunisia
        weight: 2,
        opacity: 0.6
      },
      interactive: false
    }).addTo(map);
  });
// ────────────────────────────────────────────────────────────────

// Fit Tunisia tightly on load
window.addEventListener('load', () => {
  map.fitBounds(tunisiaBounds, { padding: [20, 20], duration: 2 });
});

      // Locations data with emojis as images
      const locations = [
        {
          name: "Amphithéâtre d’El Jem",
          coords: [35.2967, 10.7067],
          category: "Monument Romain",
          description: "L’amphithéâtre d’El Jem est l’un des plus impressionnants monuments romains d’Afrique du Nord et figure parmi les plus grands amphithéâtres du monde romain. Construit au IIIe siècle après J.-C., il pouvait accueillir environ 35 000 spectateurs venus assister aux combats de gladiateurs et aux spectacles publics. Son architecture monumentale, faite de pierres massives et d’arches majestueuses, témoigne de la puissance de l’Empire romain en Afrique. Aujourd’hui classé au patrimoine mondial de l’UNESCO, ce site est un symbole historique majeur de la Tunisie.",
          img: "map img/colise_el_jem.jpg"
        },
        {
          name: "Site archéologique de Carthage",
          coords: [36.8533, 10.3258],
          category: "Ruines Antiques",
          description: "Le site archéologique de Carthage est l’un des lieux historiques les plus célèbres du bassin méditerranéen. Fondée au IXe siècle avant J.-C. par les Phéniciens, Carthage devint une puissance maritime et commerciale majeure avant d’entrer en conflit avec Rome lors des guerres puniques. Les visiteurs peuvent aujourd’hui découvrir les vestiges de cette civilisation prestigieuse, notamment les thermes d’Antonin, les ports puniques, les villas romaines et le célèbre Tophet. Classé au patrimoine mondial de l’UNESCO, le site offre une plongée fascinante dans l’histoire antique.",
          img: "map img/site-archeologique-de-carthage-en-tunisie.jpg"
        },
        {
          name: "Médina de Tunis",
          coords: [36.7986, 10.1710],
          category: "Patrimoine",
          description: "La médina de Tunis est le cœur historique de la capitale tunisienne et l’un des ensembles urbains islamiques les mieux conservés du monde arabe. Fondée au VIIe siècle, elle abrite plus de 700 monuments historiques, dont des mosquées, des palais, des médersas et des souks traditionnels. Ses ruelles étroites, ses marchés animés et son architecture traditionnelle témoignent de plusieurs siècles d’histoire et de culture. Classée au patrimoine mondial de l’UNESCO, la médina est aujourd’hui un lieu incontournable pour découvrir l’artisanat, la gastronomie et l’histoire de la Tunisie.",
          img: "map img/medina_tunis.jpg"
        },
        {
          name: "Grande Mosquée de Kairouan",
          coords: [35.6781, 10.0963],
          category: "Religieux",
          description: "La Grande Mosquée de Kairouan, également appelée mosquée Okba Ibn Nafi, est l’un des plus importants monuments de l’architecture islamique en Afrique. Construite au VIIe siècle, elle est considérée comme l’un des lieux saints de l’islam et un centre majeur d’enseignement religieux durant des siècles. Son immense cour, son minaret carré impressionnant et ses nombreuses colonnes antiques en font un chef-d’œuvre architectural. Située dans la ville historique de Kairouan, elle attire chaque année des visiteurs du monde entier.",
          img: "map img/la-Grande-Mosquee-de-Kairouan (1).jpg"
        },
        {
          name: "Ribat de Sousse",
          coords: [35.8256, 10.6410],
          category: "Forteresse",
          description: "Le Ribat de Sousse est une forteresse islamique construite au VIIIe siècle pour protéger la côte contre les invasions maritimes. Situé au cœur de la médina de Sousse, il servait à la fois de poste militaire et de lieu de retraite spirituelle pour les soldats-moines appelés murabitoun. Avec ses tours de guet, ses murs solides et sa vue panoramique sur la mer Méditerranée, le ribat constitue aujourd’hui un monument emblématique de l’histoire militaire et religieuse de la Tunisie.",
          img: "map img/ribat-sousse1-800x500.jpg"
        },
        {
          name: "Ribat de Monastir",
          coords: [35.7770, 10.8262],
          category: "Forteresse",
          description: "Le Ribat de Monastir est l’une des forteresses islamiques les plus anciennes et les mieux conservées du Maghreb. Construit au VIIIe siècle sous la dynastie aghlabide, il faisait partie d’un réseau défensif destiné à protéger les côtes de l’Afrique du Nord. Son architecture impressionnante, composée de tours, de remparts et de cours intérieures, reflète l’importance stratégique de ce monument. Aujourd’hui, il est également connu comme lieu de tournage de plusieurs films historiques.",
          img: "map img/ribat_monastir.jpg"
        },
        {
          name: "Dougga",
          coords: [36.4231, 9.2196],
          category: "Site Romain",
          description: "Dougga est considéré comme l’un des sites romains les mieux conservés d’Afrique du Nord. Située sur une colline dominant la campagne tunisienne, cette ancienne cité romaine abrite de nombreux monuments remarquables tels qu’un théâtre, un capitole, des temples, des thermes et des maisons richement décorées. Fondée à l’origine par les Numides, elle fut ensuite intégrée à l’Empire romain. Classé au patrimoine mondial de l’UNESCO, Dougga offre une vision exceptionnelle de la vie dans une ville antique.",
          img: "map img/Roman_Theatre_Dougga.jpg"
        },
        {
          name: "Bulla Regia",
          coords: [36.5613, 8.7546],
          category: "Site Archéologique",
          description: "Bulla Regia est un site archéologique unique en Tunisie, célèbre pour ses maisons souterraines datant de l’époque romaine. Ces habitations ingénieuses étaient construites sous terre afin de protéger leurs occupants de la chaleur intense de l’été. Le site comprend également un théâtre, des temples et de magnifiques mosaïques. Il témoigne du niveau avancé d’architecture et d’ingénierie des Romains dans cette région.",
          img: "map img/bulla-regia-08.jpg"
        },
        {
          name: "Musée National du Bardo",
          coords: [36.8092, 10.1354],
          category: "Musée",
          description: "Le Musée National du Bardo est l’un des musées les plus importants du monde consacré à l’art antique. Installé dans un ancien palais beylical à Tunis, il possède une collection exceptionnelle de mosaïques romaines, considérée comme l’une des plus riches au monde. Les visiteurs peuvent également découvrir des objets puniques, romains, byzantins et islamiques retraçant plusieurs millénaires d’histoire tunisienne.",
          img: "map img/musé-bardo-..jpg"
        },
        {
          name: "Mosquée Zitouna",
          coords: [36.7975, 10.1702],
          category: "Religieux",
          description: "La mosquée Zitouna est la plus ancienne et la plus importante mosquée de Tunis. Fondée au VIIIe siècle, elle a longtemps été un centre majeur d’enseignement religieux et scientifique dans le monde islamique. Située au cœur de la médina de Tunis, elle se distingue par son architecture traditionnelle, sa vaste cour entourée d’arcades et son minaret majestueux.",
          img: "map img/Mosquee-Zitouna.jpg"
        },
        {
          name: "Chott el Jerid",
          coords: [33.7000, 8.4000],
          category: "Nature",
          description: "Le Chott el Jerid est le plus grand lac salé de Tunisie et l’un des paysages naturels les plus spectaculaires du Sahara. Durant l’été, l’évaporation intense crée une immense étendue blanche scintillante formée de cristaux de sel. Le phénomène de mirage y est fréquent, donnant l’impression de voir de l’eau au milieu du désert. Ce lieu fascinant attire les photographes, les aventuriers et les voyageurs du monde entier.",
          img: "map img/76109546.jpg"
        },
        {
          name: "Ong Jemel",
          coords: [33.8800, 7.9500],
          category: "Désert",
          description: "Ong Jemel, qui signifie 'le cou du chameau', est une formation rocheuse spectaculaire située dans le désert près de Tozeur. Ce site naturel est célèbre pour avoir servi de décor à plusieurs films internationaux, notamment la saga Star Wars. Les paysages désertiques impressionnants, les dunes dorées et les formations rocheuses uniques en font une destination incontournable pour les amateurs de nature et de cinéma.",
          img: "map img/54.jpg"
        },
        {
          name: "Ksar Ouled Soltane",
          coords: [32.9231, 10.4515],
          category: "Ksar",
          description: "Ksar Ouled Soltane est l’un des plus beaux exemples de ksour berbères du sud tunisien. Construit au XVe siècle, ce grenier collectif servait à stocker les récoltes des tribus locales dans des cellules appelées 'ghorfas'. Son architecture impressionnante composée de plusieurs étages de chambres voûtées en fait un site historique et culturel unique.",
          img: "map img/Ksar_Ouled_Soltane_01.jpg"
        },
        {
          name: "Chenini",
          coords: [32.9275, 10.2900],
          category: "Village Berbère",
          description: "Chenini est un village berbère historique situé sur une colline rocheuse dans le sud de la Tunisie. Connu pour ses habitations troglodytes creusées dans la roche, il offre un aperçu unique du mode de vie traditionnel des populations berbères. Le village comprend également une ancienne mosquée blanche qui domine le paysage désertique environnant.",
          img: "map img/Chenini_2021.jpg"
        },
        {
          name: "Fort de Borj El Kebir",
          coords: [33.8800, 10.8500],
          category: "Forteresse",
          description: "Le fort de Borj El Kebir, situé sur l’île de Djerba, est une grande forteresse construite au XVe siècle pour protéger l’île contre les invasions maritimes. Ses remparts massifs et sa position stratégique face à la mer témoignent de son importance militaire dans l’histoire de la région.",
          img: "map img/site-archeologique-de-carthage-en-tunisie.jpg"
        },
        {
          name: "Synagogue El Ghriba",
          coords: [33.8076, 10.8553],
          category: "Religieux",
          description: "La synagogue El Ghriba à Djerba est l’une des plus anciennes synagogues du monde et un lieu sacré important pour la communauté juive. Selon la tradition, elle aurait plus de 2000 ans. Chaque année, elle accueille un célèbre pèlerinage réunissant des visiteurs venus de nombreux pays.",
          img: "map img/caption.jpg"
        }
      ];

      // Map category → legend color
      function getCategoryColor(category) {
        const theatrical = ["Monument Romain", "Site Romain", "Ruines Antiques"];
        const archaeological = ["Site Archéologique", "Forteresse", "Religieux"];
        if (theatrical.includes(category)) return "#394202";
        if (archaeological.includes(category)) return "#334e85";
        return "#cdaa80"; // Culturel (default)
      }

      // Custom marker class with diamond shape lezem nbadlou shape
      function createCustomMarker(location) {
        const color = getCategoryColor(location.category);
        const markerHTML = `
        <div style="position: relative; width: 20px; height: 20px; animation: pulse 2s ease-in-out infinite;">
          <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 100%; filter: drop-shadow(0 0 12px ${color}99) drop-shadow(0 0 6px ${color}66);">
            <defs>
              <filter id="glow-${location.name.replace(/\s/g, '_')}">
                <feGaussianBlur stdDeviation="1.5" result="coloredBlur"/>
                <feMerge>
                  <feMergeNode in="coloredBlur"/>
                  <feMergeNode in="SourceGraphic"/>
                </feMerge>
              </filter>
            </defs>
            <rect x="8" y="2" width="16" height="16" fill="${color}" stroke="#c9a84c" stroke-width="2.5" rx="2" transform="rotate(45 16 16)" opacity="0.95"/>
            <circle cx="16" cy="16" r="4" fill="#c9a84c" filter="url(#glow-${location.name.replace(/\s/g, '_')})"/>
          </svg>
        </div>
      `;

        const icon = L.divIcon({
          html: markerHTML,
          iconSize: [32, 32],
          iconAnchor: [16, 16],
          popupAnchor: [0, -20],
          className: ''
        });

        return L.marker(location.coords, { icon, title: location.name });
      }

      // Function to update info panel
      function updateInfoPanel(location) {
        const infoPanel = document.getElementById('info-panel');
        const infoImage = document.getElementById('info-image');
        const infoTitle = document.getElementById('info-title');
        const infoCategory = document.getElementById('info-category');
        const infoDescription = document.getElementById('info-description');
        const infoCoords = document.getElementById('info-coords');

        infoTitle.textContent = location.name;
        infoCategory.textContent = location.category;
        infoDescription.textContent = location.description;
        infoCoords.textContent = `📍 ${location.coords[0].toFixed(4)}°N, ${location.coords[1].toFixed(4)}°E`;

        infoImage.src = location.img;

        infoPanel.classList.add('active');
      }

      // Add markers to map
      locations.forEach(location => {
        const marker = createCustomMarker(location);
        marker.addTo(map);

        // Store reference for later
        marker.location = location;

        // Click handler for marker
        marker.on('click', () => {
          updateInfoPanel(location);
          map.flyTo(location.coords, 10, { duration: 1 });
          document.querySelectorAll('.location-item').forEach((item, idx) => {
            item.classList.toggle('active', locations[idx].name === location.name);
          });
        });
      });


      const locationsList = document.getElementById('locations-list');
      locations.forEach((location, index) => {
        const item = document.createElement('div');
        item.className = 'location-item';
        item.innerHTML = `
        <div class="location-name">${location.name}</div>
        <div class="location-category">${location.category}</div>
      `;
        item.addEventListener('click', () => {
          map.flyTo(location.coords, 10, { duration: 1 });
          updateInfoPanel(location);
          document.querySelectorAll('.location-item').forEach(el => el.classList.remove('active'));
          item.classList.add('active');
        });
        locationsList.appendChild(item);
      });

      // Close info panel button
      document.getElementById("info-close-btn").onclick = () => {
        document.getElementById("info-panel").classList.remove("active");
      };

      // Zoom controls
      document.getElementById('zoom-in').addEventListener('click', () => {
        map.zoomIn();
      });

      document.getElementById('zoom-out').addEventListener('click', () => {
        map.zoomOut();
      });

      // Animated zoom from world to Tunisia on load
      window.addEventListener('load', () => {
       map.fitBounds([[30.2366, 7.5244], [37.5409, 11.5657]], { padding: [0, 0] });
      });
    
    
      // Logo switch on scroll
      const logoImg = document.getElementById('logo-img');
      const header = document.getElementById('main-header');

      function updateLogo() {
        if (window.scrollY > 50) {
          if (logoImg.src.includes('logo1.png')) {
            logoImg.src = 'logo2.png';
          }
        } else {
          if (logoImg.src.includes('logo2.png')) {
            logoImg.src = 'logo1.png';
          }
        }
      }

      // Run on scroll
      window.addEventListener('scroll', updateLogo);


      window.addEventListener('load', updateLogo);

    
    
      const userBtn = document.getElementById("userBtn");
      const dropdown = document.getElementById("userDropdown");

      userBtn.addEventListener("click", (e) => {
        e.stopPropagation(); // prevent immediate close
        dropdown.classList.toggle("active");
      });

      // Close when clicking anywhere else
      document.addEventListener("click", () => {
        dropdown.classList.remove("active");
      });

      // Prevent closing when clicking inside menu
      dropdown.addEventListener("click", (e) => {
        e.stopPropagation();
      });
    

    const btn = document.getElementById("backToTop");

      window.onscroll = function () {
        if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
          btn.style.display = "block";
        } else {
          btn.style.display = "none";
        }
      };

      btn.onclick = function () {
        window.scrollTo({
          top: 0,
          behavior: "smooth"
        });
      };
    




    
      function switchLang(lang) {
        // Close dropdown
        document.getElementById('lang-dropdown').classList.remove('open');

        if (lang === 'fr') {
          // Clear GTranslate cookie then reload to restore French
          document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
          document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=' + location.hostname;
          document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=.' + location.hostname;
          localStorage.setItem('ged_lang', 'fr');
          location.reload();
          return;
        }

        // Arabic
        if (typeof doGTranslate === 'function') {
          doGTranslate('fr|ar');
          document.documentElement.setAttribute('lang', 'ar');
          document.documentElement.setAttribute('dir', 'rtl');
          document.documentElement.style.setProperty('--text-align', 'right');
          localStorage.setItem('ged_lang', 'ar');
        } else {
          setTimeout(() => switchLang(lang), 300);
        }
      }

      // Earth button toggle
      document.addEventListener('DOMContentLoaded', () => {
        const langBtn = document.getElementById('custom-lang-btn');
        const langDropdown = document.getElementById('lang-dropdown');

        langBtn.addEventListener('click', (e) => {
          e.stopPropagation();
          langDropdown.classList.toggle('open');
        });

        document.addEventListener('click', () => {
          langDropdown.classList.remove('open');
        });

        // Restore Arabic on page load if it was selected before
        if (localStorage.getItem('ged_lang') === 'ar') {
          setTimeout(() => switchLang('ar'), 500);
        }
      });
    

    
      // ══ SERVICES VIDEO BACKDROP ══
      (function () {
        const bg = document.getElementById('services-video-bg');
        const video = document.getElementById('svc-video');
        const cards = document.querySelectorAll('#services .service-card[data-video]');
        let hideTimer = null;

        cards.forEach(card => {
          card.addEventListener('mouseenter', () => {
            if (hideTimer) clearTimeout(hideTimer);
            const src = card.dataset.video;
            if (video.dataset.loaded !== src) {
              video.src = src;
              video.dataset.loaded = src;
              video.load();
            }
            video.play().catch(() => { });
            bg.classList.add('active');
          });

          card.addEventListener('mouseleave', () => {
            hideTimer = setTimeout(() => {
              bg.classList.remove('active');
              setTimeout(() => {
                video.pause();
                video.currentTime = 0;
              }, 700);
            }, 100);
          });
        });
      })();
    