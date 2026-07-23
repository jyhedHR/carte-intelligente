// ═══════════════════════════════════════════════════════════
// HEADER SCROLL EFFECT
// ═══════════════════════════════════════════════════════════

const header = document.getElementById('main-header');

window.addEventListener('scroll', () => {
  if (window.scrollY > 80) {
    header.classList.add('scrolled');
  } else {
    header.classList.remove('scrolled');
  }
});

// ═══════════════════════════════════════════════════════════
// DROPDOWN MENU FUNCTIONALITY
// ═══════════════════════════════════════════════════════════

const dropdownTriggers = document.querySelectorAll('.dropdown-trigger');

dropdownTriggers.forEach(trigger => {
  trigger.addEventListener('mouseenter', () => {
    const menu = trigger.querySelector('.dropdown-menu');
    if (menu) {
      menu.style.opacity = '1';
      menu.style.visibility = 'visible';
      menu.style.transform = 'translateY(0)';
    }
  });

  trigger.addEventListener('mouseleave', () => {
    const menu = trigger.querySelector('.dropdown-menu');
    if (menu) {
      menu.style.opacity = '0';
      menu.style.visibility = 'hidden';
      menu.style.transform = 'translateY(-10px)';
    }
  });
});

// ═══════════════════════════════════════════════════════════
// SEARCH BAR FUNCTIONALITY
// ═══════════════════════════════════════════════════════════

const searchInput = document.getElementById('searchInput');
const searchBtn = document.getElementById('searchBtn');

// Expand search on focus
searchInput.addEventListener('focus', () => {
  console.log('[v0] Search input focused');
});

// Handle search submission
searchBtn.addEventListener('click', (e) => {
  e.preventDefault();
  const query = searchInput.value.trim();
  if (query) {
    console.log('[v0] Search query:', query);
    // You can add actual search logic here
    alert(`Recherche: ${query}`);
    searchInput.value = '';
  }
});

// Allow Enter key to search
searchInput.addEventListener('keypress', (e) => {
  if (e.key === 'Enter') {
    searchBtn.click();
  }
});

// ═══════════════════════════════════════════════════════════
// SMOOTH SCROLL FOR ANCHOR LINKS
// ═══════════════════════════════════════════════════════════

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    const href = this.getAttribute('href');
    
    // Skip if it's just "#"
    if (href === '#') {
      e.preventDefault();
      return;
    }

    const target = document.querySelector(href);
    if (target) {
      e.preventDefault();
      target.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      });
    }
  });
});

// ═══════════════════════════════════════════════════════════
// INTERACTIVE SERVICE CARDS
// ═══════════════════════════════════════════════════════════

const serviceCards = document.querySelectorAll('.service-card');

serviceCards.forEach(card => {
  card.addEventListener('mouseenter', () => {
    card.style.transform = 'translateY(-8px)';
  });

  card.addEventListener('mouseleave', () => {
    card.style.transform = 'translateY(0)';
  });

  card.addEventListener('click', () => {
    const service = card.querySelector('h3').textContent;
    console.log('[v0] Service clicked:', service);
    // Navigate to service page or show modal
  });
});

// ═══════════════════════════════════════════════════════════
// STAT COUNTERS ANIMATION
// ═══════════════════════════════════════════════════════════

const animateCounter = (element, target, duration = 2000) => {
  let current = 0;
  const increment = target / (duration / 16);
  
  const updateCounter = () => {
    current += increment;
    if (current < target) {
      element.textContent = Math.floor(current);
      requestAnimationFrame(updateCounter);
    } else {
      element.textContent = target;
    }
  };

  updateCounter();
};

// Trigger stat counters on scroll into view
const observerOptions = {
  threshold: 0.5
};

const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting && entry.target.classList.contains('stat-number')) {
      const text = entry.target.textContent;
      const number = parseInt(text);
      
      if (!isNaN(number)) {
        animateCounter(entry.target, number);
        observer.unobserve(entry.target);
      }
    }
  });
}, observerOptions);

document.querySelectorAll('.stat-number').forEach(el => {
  observer.observe(el);
});

// ═══════════════════════════════════════════════════════════
// LEAFLET MAP INITIALIZATION
// ═══════════════════════════════════════════════════════════

// Check if Leaflet is available and map element exists
if (typeof L !== 'undefined' && document.getElementById('tunisia-map')) {
  // Initialize map with Tunisia center coordinates
  const map = L.map('tunisia-map').setView([33.8869, 9.5375], 6);

  // Add tile layer
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 19,
    tileSize: 256,
    zoomControl: true
  }).addTo(map);

  // Sample cultural locations in Tunisia
  const locations = [
    { name: 'Musée du Bardo', lat: 36.8065, lng: 10.1637, type: 'Musée' },
    { name: 'Amphithéâtre d\'El Djem', lat: 35.3003, lng: 10.7347, type: 'Monument' },
    { name: 'Médina de Tunis', lat: 36.7964, lng: 10.1815, type: 'Site Culturel' },
    { name: 'Kairouan', lat: 35.6752, lng: 9.9168, type: 'Ville Historique' },
    { name: 'Dougga', lat: 36.4161, lng: 9.2262, type: 'Site Archéologique' }
  ];

  // Add markers to map
  locations.forEach(location => {
    const marker = L.marker([location.lat, location.lng])
      .bindPopup(`
        <div style="color: #1a1520; font-weight: 600;">
          <div>${location.name}</div>
          <div style="font-size: 12px; color: #666; margin-top: 4px;">${location.type}</div>
        </div>
      `)
      .addTo(map);

    marker.on('click', () => {
      console.log('[v0] Location clicked:', location.name);
    });
  });

  // Make map responsive on window resize
  window.addEventListener('resize', () => {
    map.invalidateSize();
  });

  console.log('[v0] Tunisia map initialized');
}

// ═══════════════════════════════════════════════════════════
// FORM INTERACTIONS
// ═══════════════════════════════════════════════════════════

// Handle CTA button clicks
document.querySelectorAll('a.btn-gold, a.btn-outline').forEach(button => {
  button.addEventListener('click', (e) => {
    const text = button.textContent;
    console.log('[v0] Button clicked:', text);
  });
});

// ═══════════════════════════════════════════════════════════
// UTILITY FUNCTIONS
// ═══════════════════════════════════════════════════════════

// Debounce function for performance
const debounce = (func, delay) => {
  let timeoutId;
  return (...args) => {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => func(...args), delay);
  };
};

// Add active class to current nav link
const highlightCurrentNav = () => {
  const currentLocation = location.pathname;
  const navLinks = document.querySelectorAll('.header-link');
  
  navLinks.forEach(link => {
    link.classList.remove('active');
    if (link.getAttribute('href') === currentLocation) {
      link.classList.add('active');
    }
  });
};

highlightCurrentNav();

// ═══════════════════════════════════════════════════════════
// ACCESSIBILITY IMPROVEMENTS
// ═══════════════════════════════════════════════════════════

// Keyboard navigation for dropdown menus
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    // Close all dropdowns
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
      menu.style.opacity = '0';
      menu.style.visibility = 'hidden';
      menu.style.transform = 'translateY(-10px)';
    });
  }
});

// Focus management for keyboard navigation
const navItems = document.querySelectorAll('.nav-item');

navItems.forEach((item, index) => {
  const link = item.querySelector('.header-link');
  
  if (link) {
    link.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        const menu = item.querySelector('.dropdown-menu');
        if (menu) {
          e.preventDefault();
          menu.style.opacity = menu.style.opacity === '1' ? '0' : '1';
          menu.style.visibility = menu.style.visibility === 'visible' ? 'hidden' : 'visible';
        }
      }
    });
  }
});

// ═══════════════════════════════════════════════════════════
// PAGE LOAD OPTIMIZATION
// ═══════════════════════════════════════════════════════════

// Log initialization complete
console.log('[v0] Page initialization complete');

// Mark page as interactive for performance metrics
if ('PerformanceObserver' in window) {
  try {
    const observer = new PerformanceObserver((list) => {
      for (const entry of list.getEntries()) {
        console.log('[v0] Performance:', entry.name, entry.duration, 'ms');
      }
    });
    observer.observe({ entryTypes: ['navigation', 'resource'] });
  } catch (e) {
    console.log('[v0] Performance monitoring not available');
  }
}
