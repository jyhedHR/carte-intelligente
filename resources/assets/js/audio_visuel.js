
//new new
// Audio-Visuel Feature Switcher
/* ══════════════════════════════════════════════
   FEATURE 1 — Risk Score
   v2: dossier selector replaces manual upload
══════════════════════════════════════════════ */

/* ── Synopses pulled from "user-submitted form fields" ── */

/* ── Dossier card CSS (injected once) ── */
(function injectStyles() {
  if (document.getElementById('feature1-styles')) return;
  const s = document.createElement('style');
  s.id = 'feature1-styles';
  s.textContent = `
    .dossier-pick-item {
      display: flex;
      align-items: flex-start;
      gap: 14px;
      padding: 12px 14px;
      border: 1.5px solid var(--border);
      border-radius: var(--radius-sm);
      cursor: pointer;
      transition: all 0.2s ease;
      background: var(--bg3);
    }
    .dossier-pick-item:hover {
      border-color: rgba(201,168,76,0.45);
      background: var(--gold-glow);
    }
    .dossier-pick-item.selected {
      border-color: var(--gold);
      background: var(--gold-glow);
      box-shadow: 0 0 0 1px rgba(201,168,76,0.2);
    }
    .dossier-pick-left { flex: 1; min-width: 0; }
    .dossier-pick-right { flex-shrink: 0; text-align: right; }

    .extracted-flag {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
      border: 1px solid;
    }
  `;
  document.head.appendChild(s);
})();

/* ── Select a dossier from the list ── */
window.selectDossier=function(el, ref, title, agent, type, nationalite, lieu, dates, status) {
  // Highlight selected card
  document.querySelectorAll('.dossier-pick-item').forEach(i => i.classList.remove('selected'));
  el.classList.add('selected');

  const data = dossierData[ref];
  if (!data) return;

  // Show the preview panel with a brief "loading" state
  const panel = document.getElementById('synopsis-preview-panel');
  panel.style.display = 'block';

  // Temp loading state
  document.getElementById('preview-ref').textContent = ref;
  document.getElementById('ex-title').textContent = '…';
  document.getElementById('ex-type').textContent = '…';
  document.getElementById('ex-lieu').textContent = '…';
  document.getElementById('ex-dates').textContent = '…';
  document.getElementById('ex-synopsis').textContent = 'Extraction en cours…';
  document.getElementById('auto-flags').innerHTML = '';

  // Simulate a ~600ms "fetch from dossier" delay
  setTimeout(() => {
    document.getElementById('preview-ref').textContent  = ref;
    document.getElementById('ex-title').textContent     = data.title;
    document.getElementById('ex-type').textContent      = data.type + ' · ' + data.nationalite;
    document.getElementById('ex-lieu').textContent      = data.lieu;
    document.getElementById('ex-dates').textContent     = data.dates;
    document.getElementById('ex-synopsis').textContent  = data.synopsis;

    // Render auto-detected flags
    const flagsContainer = document.getElementById('auto-flags');
    if (data.flags.length === 0) {
      flagsContainer.innerHTML = '<span style="font-size:12px;color:var(--text3);">Aucun élément sensible détecté automatiquement</span>';
    } else {
      flagsContainer.innerHTML = data.flags.map(f => `
        <span class="extracted-flag" style="color:${f.color};border-color:${f.color};background:${f.color}18;">
          ${f.label}
        </span>
      `).join('');
    }

    // Hide old result if re-selecting
    document.getElementById('risk-result').style.display = 'none';

    // Scroll preview into view
    panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }, 600);
}

/* ── Filter dossier list by search text or status ── */
window.filterDossiers=function(searchVal) {
  const search = (searchVal || document.getElementById('dossier-search')?.value || '').toLowerCase();
  const status = document.getElementById('dossier-status-filter')?.value || '';


  document.querySelectorAll('.dossier-pick-item').forEach(item => {
    const text   = item.textContent.toLowerCase();
    const istat  = item.dataset.status || '';
    const matchSearch = !search || text.includes(search);
    const matchStatus = !status || istat === status;
    item.style.display = matchSearch && matchStatus ? '' : 'none';
  });
}

/* ── Launch risk analysis (called from button inside preview panel) ── */
window.runRiskAnalysis=function() {
  const ref = document.getElementById('preview-ref')?.textContent || '—';
  document.getElementById('result-ref').textContent = ref;

  // Build and show risk result
  buildRiskBars();
  const result = document.getElementById('risk-result');
  result.style.display = 'block';
  result.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

/* ── Build animated risk bars ── */
window.buildRiskBars=function(){
  document.querySelectorAll('.risk-bar-row').forEach(row => {
    const score  = row.dataset.score;
    const color  = row.dataset.color;
    const label  = row.dataset.label;
    const detail = row.dataset.detail;
    row.innerHTML = `
      <div style="display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:12px;color:var(--text2);">${label}</span>
        <span style="font-size:12px;font-weight:800;font-family:var(--font-mono);color:${color};">${score}/100</span>
      </div>
      <div style="height:6px;background:var(--bg4);border-radius:3px;overflow:hidden;">
        <div style="width:0%;height:100%;background:${color};border-radius:3px;transition:width 0.8s ease;" data-target="${score}"></div>
      </div>
      <div style="font-size:10.5px;color:var(--text3);">${detail}</div>`;
  });
  setTimeout(() => {
    document.querySelectorAll('[data-target]').forEach(bar => {
      bar.style.width = bar.dataset.target + '%';
    });
  }, 50);
}

/* ── Risk view tabs ── */
window.switchRiskView=function(view) {
  ['agent', 'director', 'admin'].forEach(v => {
    const panel = document.getElementById('risk-view-' + v);
    const btn   = document.getElementById('rv-' + v);
    if (panel) panel.style.display = v === view ? 'block' : 'none';
    if (btn)   btn.classList.toggle('risk-view-active', v === view);
  });
}

/* ── Map filters ── */
window.filterMap=function(el, type) {
  document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
}

/* ── Map location detail ── */
const locationData = {
  tunis: {
    name: 'Grand Tunis', flag: '⚠️', color: 'var(--red)', count: 5, conflict: true,
    productions: [
      { title: "L'Ombre du Sahel", type: 'Film FR', dates: '18–28 Jul', status: 'red',   statusLabel: 'Conflit' },
      { title: 'Carthage Story',   type: 'Film DE', dates: '20–30 Jul', status: 'red',   statusLabel: 'Conflit' },
      { title: 'Clip Sami Fehri',  type: 'Clip TN', dates: '5–8 Jul',  status: 'green', statusLabel: 'Actif'   },
    ]
  },
  hammamet: {
    name: 'Hammamet / Nabeul', flag: '🎬', color: 'var(--green)', count: 2, conflict: false,
    productions: [
      { title: 'Mediterranean Blue', type: 'Docu IT', dates: '10–20 Jul', status: 'green', statusLabel: 'Actif' },
      { title: 'Pub TotalEnergies',  type: 'Pub FR',  dates: '22–24 Jul', status: 'green', statusLabel: 'Actif' },
    ]
  },
  tatouine: {
    name: 'Tataouine / Sud', flag: '⏳', color: 'var(--amber)', count: 3, conflict: false,
    productions: [
      { title: 'Desert Storm',     type: 'Film US',  dates: '1–15 Aug',  status: 'gold', statusLabel: 'En attente' },
      { title: 'Sahara Chronicles',type: 'Docu UK',  dates: '10–25 Aug', status: 'gold', statusLabel: 'En attente' },
      { title: 'Sand & Stars',     type: 'Film DE',  dates: '20 Aug+',   status: 'gold', statusLabel: 'En attente' },
    ]
  },
  djerba: {
    name: 'Djerba', flag: '🌍', color: 'var(--blue)', count: 1, conflict: false,
    productions: [
      { title: 'Island Memories', type: 'Film FR', dates: '2–18 Jul', status: 'blue', statusLabel: 'Étranger' },
    ]
  },
  sfax:     { name: 'Sfax',     flag:'🎬', color:'var(--green)', count:1, conflict:false, productions:[{ title:'Sfax Story',          type:'Film TN',  dates:'12–16 Jul', status:'green', statusLabel:'Actif' }] },
  kairouan: { name: 'Kairouan', flag:'🎬', color:'var(--green)', count:1, conflict:false, productions:[{ title:'Héritage Aghlabide', type:'Docu TN',  dates:'8–14 Jul',  status:'green', statusLabel:'Actif' }] },
};

window.showLocationDetail=function(key) {
  const data = locationData[key];
  if (!data) return;
  const container = document.getElementById('location-detail');
  container.innerHTML = `
    <div class="panel-head" style="${data.conflict ? 'background:var(--red-dim);' : ''}">
      <div>
        <div class="panel-title">${data.flag} ${data.name}</div>
        <div class="panel-sub">${data.count} production${data.count > 1 ? 's' : ''} · ${
          data.conflict
            ? '<span style="color:var(--red);">Conflit détecté</span>'
            : '<span style="color:var(--green);">Aucun conflit</span>'
        }</div>
      </div>
    </div>
    <div class="panel-body" style="padding:12px 16px;">
      ${data.productions.map(p => `
        <div style="padding:10px 0;border-bottom:1px solid var(--border);">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:3px;">
            <span style="font-size:12px;font-weight:600;color:var(--text);">${p.title}</span>
            <span class="badge ${p.status}" style="font-size:9px;">${p.statusLabel}</span>
          </div>
          <div style="font-size:10.5px;color:var(--text3);">${p.type} · 📅 ${p.dates}</div>
        </div>
      `).join('')}
    </div>`;
}

/* ── Audio feature mini nav ── */
// AUDIO-VISUEL MINI SIDEBAR — FIXED & CLEAN
window.showAudioFeature = function(n) {
  document.querySelectorAll('.audio-feature').forEach(el => {
    el.style.display = 'none';
    el.classList.remove('active');
  });

  document.querySelectorAll('[id^="audio-nav-"]').forEach(el => el.classList.remove('active'));

  const feature = document.getElementById('audio-feature-' + n);
  if (feature) {
    feature.style.display = 'block';
    feature.classList.add('active');
  }

  const navItem = document.getElementById('audio-nav-' + n);
  if (navItem) navItem.classList.add('active');

  if (n === 2 && typeof initTunisiaMap === 'function') initTunisiaMap();
  if (n === 3 && typeof buildFullCalendar === 'function') buildFullCalendar();
  if (n === 3 && typeof initBundleInsideFeature3 === 'function') initBundleInsideFeature3();
  if (n === 4 && typeof initBudgetFeature === 'function') initBudgetFeature();
  if (n === 5 && typeof initSuspiciousDetector === 'function') initSuspiciousDetector();
  if (n === 6 && typeof initComparateur === 'function') initComparateur();
}
/* ── Init ── */





/* ══════════════════════════════════════════════
   FEATURE 4 — SUIVI BUDGET & FINANCEMENT (NEW)
══════════════════════════════════════════════ */

const budgetEntities = [
  { id: 'E001', name: 'Cactus Prod',          type: 'Production',       allocated: 45000000, spent: 42000000, lastDate: 'il y a 3 j', status: 'ok' },
  { id: 'E002', name: 'Atlas Films International', type: 'Distribution',   allocated: 28000000, spent: 28000000, lastDate: "aujourd'hui", status: 'over' },
  { id: 'E003', name: 'Cinémaginaire SARL',   type: 'Production',       allocated: 35000000, spent: 31000000, lastDate: 'il y a 1 sem', status: 'warn' },
  { id: 'E004', name: 'Médina Production',    type: 'Production',       allocated: 22000000, spent: 18000000, lastDate: 'il y a 5 j', status: 'ok' },
  { id: 'E005', name: 'Sahara Screen',        type: 'Co-production',     allocated: 18000000, spent: 8000000,  lastDate: 'il y a 2 sem', status: 'ok' },
  { id: 'E006', name: 'Carthage Pictures',    type: 'Distribution',     allocated: 15000000, spent: 12000000, lastDate: 'il y a 4 j', status: 'warn' },
  { id: 'E007', name: 'Djerba Studios',       type: 'Production',       allocated: 12000000, spent: 6000000,  lastDate: 'il y a 3 sem', status: 'ok' },
];

window.getBudgetStatus=function(spent, allocated) {
  const pct = spent / allocated;
  if (pct >= 1) return 'over';
  if (pct >= 0.8) return 'warn';
  return 'ok';
}

window.renderBudgetTable=function(data) {
  const tbody = document.getElementById('quota-tbody');
  if (!tbody) return;

  tbody.innerHTML = data.map(e => {
    const pct = Math.round((e.spent / e.allocated) * 100);
    const status = getBudgetStatus(e.spent, e.allocated);
    const barClass = `q-${status}`;
    const badgeClass = `q-badge-${status}`;

    return `
      <tr data-status="${status}" data-type="${e.type}">
        <td><span class="quota-entity-name">${e.name}</span></td>
        <td><span style="font-size:11px;color:var(--text3);">${e.type}</span></td>
        <td><strong>${(e.allocated/1000000).toFixed(1)} M</strong></td>
        <td><strong>${(e.spent/1000000).toFixed(1)} M</strong></td>
        <td>
          <div class="quota-bar-wrap">
            <div class="quota-bar-track"><div class="quota-bar-fill ${barClass}" style="width:${pct}%"></div></div>
            <span class="quota-bar-pct q-${status}">${pct}%</span>
          </div>
        </td>
        <td style="color:var(--text3);font-size:11.5px;">${e.lastDate}</td>
        <td><span class="q-badge ${badgeClass}">${status === 'over' ? '🚨 Dépassé' : status === 'warn' ? '⚠️ Proche' : '✅ OK'}</span></td>
      </tr>`;
  }).join('');
}

window.filterBudgetTable=function() {
  const search = (document.getElementById('quota-search')?.value || '').toLowerCase().trim();
  const statusFilter = document.getElementById('quota-status-filter')?.value || '';
  const typeFilter   = document.getElementById('quota-type-filter')?.value  || '';

  const filtered = budgetEntities.filter(e => {
    const matchSearch = !search || e.name.toLowerCase().includes(search) || e.type.toLowerCase().includes(search);
    const matchStatus = !statusFilter || getBudgetStatus(e.spent, e.allocated) === statusFilter;
    const matchType   = !typeFilter   || e.type === typeFilter;
    return matchSearch && matchStatus && matchType;
  });

  renderBudgetTable(filtered);
}

/* Global budget breakdown chart (simple bars) */
window.renderGlobalBudgetChart=function()  {
  const container = document.getElementById('global-budget-chart');
  container.innerHTML = `
    <div style="display:flex;gap:12px;flex-direction:column;">
      <div class="bar-row"><div class="bar-label">Production</div><div class="bar"><div class="bar-fill" style="width:62%;background:var(--green)"></div></div><span>62%</span></div>
      <div class="bar-row"><div class="bar-label">Distribution</div><div class="bar"><div class="bar-fill" style="width:21%;background:var(--amber)"></div></div><span>21%</span></div>
      <div class="bar-row"><div class="bar-label">Post-production</div><div class="bar"><div class="bar-fill" style="width:12%;background:var(--teal)"></div></div><span>12%</span></div>
      <div class="bar-row"><div class="bar-label">Co-production</div><div class="bar"><div class="bar-fill" style="width:5%;background:var(--blue)"></div></div><span>5%</span></div>
    </div>`;
}
/* ── Monthly trend (simple bars) ── */
window.renderQuotaTrend=function() {
  const container = document.getElementById('quota-trend-container');
  if (!container) return;

  container.innerHTML = `
    <div class="quota-trend-row">
      <span class="quota-trend-month">Jan</span>
      <div class="quota-trend-bar-bg"><div class="quota-trend-bar-fg" style="width:45%"></div></div>
      <span class="quota-trend-count">14 M</span>
    </div>
    <div class="quota-trend-row">
      <span class="quota-trend-month">Fév</span>
      <div class="quota-trend-bar-bg"><div class="quota-trend-bar-fg" style="width:62%"></div></div>
      <span class="quota-trend-count">18 M</span>
    </div>
    <div class="quota-trend-row">
      <span class="quota-trend-month">Mar</span>
      <div class="quota-trend-bar-bg"><div class="quota-trend-bar-fg" style="width:71%"></div></div>
      <span class="quota-trend-count">22 M</span>
    </div>
    <div class="quota-trend-row">
      <span class="quota-trend-month">Avr</span>
      <div class="quota-trend-bar-bg"><div class="quota-trend-bar-fg" style="width:88%"></div></div>
      <span class="quota-trend-count">30 M</span>
    </div>
  `;
}

/* ── Top spenders ── */
window.renderQuotaRepeat=function() {
  const container = document.getElementById('quota-repeat-container');
  if (!container) return;

  container.innerHTML = `
    <div class="quota-repeat-item">
      <div class="quota-repeat-left">
        <span class="quota-repeat-name">🥇 Cactus Prod</span>
        <span class="quota-repeat-sub">Production</span>
      </div>
      <span class="quota-repeat-count">42 M</span>
    </div>
    <div class="quota-repeat-item">
      <div class="quota-repeat-left">
        <span class="quota-repeat-name">🥈 Atlas Films</span>
        <span class="quota-repeat-sub">Distribution</span>
      </div>
      <span class="quota-repeat-count">28 M</span>
    </div>
    <div class="quota-repeat-item">
      <div class="quota-repeat-left">
        <span class="quota-repeat-name">🥉 Cinémaginaire</span>
        <span class="quota-repeat-sub">Production</span>
      </div>
      <span class="quota-repeat-count">31 M</span>
    </div>
  `;
}
/* Keep the rest of your existing render functions (trend, repeat) — they still work perfectly */
window.initBudgetFeature=function() {
  // Table
  renderBudgetTable(budgetEntities);

  // Sidebar charts
  renderGlobalBudgetChart();
  renderQuotaTrend();
  renderQuotaRepeat();
}
/* Call this instead of initQuotaFeature() */
window.initBudgetFeature = initBudgetFeature;


//risk feature test:
// Sample data for each dossier
const dossierData = {
  1: { // Les Ombres de Casablanca
    synopsis: "L'histoire suit Ahmed, un ancien policier de Casablanca, qui découvre une conspiration impliquant des officiels corrompus. Le film contient des scènes de confrontation dans la médina, une poursuite en voiture près du port, et des dialogues sur la justice sociale...",
    meta: "Extrait automatique - 847 mots analysés",
    risks: [
      { label: "Contenu Sensible", value: 75, color: "risk-high" },
      { label: "Risques Tournage", value: 45, color: "risk-medium" },
      { label: "Permis Requis", value: 30, color: "risk-low" }
    ],
    score: "Elevé",
    recommendations: ["Révision manuelle requise par la commission", "Scènes politiques à valider", "Coordination sécurité pour tournage médina"]
  },
  2: { // Medina Stories
    synopsis: "Une série dramatique qui explore la vie quotidienne dans la médina de Fès à travers trois générations de femmes. Le scénario aborde les traditions, l'émancipation et les conflits familiaux.",
    meta: "Extrait automatique - 612 mots analysés",
    risks: [
      { label: "Contenu Sensible", value: 35, color: "risk-medium" },
      { label: "Risques Tournage", value: 60, color: "risk-medium" },
      { label: "Permis Requis", value: 25, color: "risk-low" }
    ],
    score: "Moyen",
    recommendations: ["Vérifier autorisations de tournage dans la médina", "Sensibilisation équipe sur respect des coutumes locales"]
  },
  3: { // Atlas Secret
    synopsis: "Documentaire sur les sites archéologiques cachés du Haut Atlas. L'équipe explore des grottes et ruines inaccessibles.",
    meta: "Extrait automatique - 389 mots analysés",
    risks: [
      { label: "Contenu Sensible", value: 15, color: "risk-low" },
      { label: "Risques Tournage", value: 80, color: "risk-high" },
      { label: "Permis Requis", value: 70, color: "risk-high" }
    ],
    score: "Elevé",
    recommendations: ["Autorisation spéciale du Ministère de la Culture obligatoire", "Équipement de sécurité renforcé"]
  },
  4: { // Tourisme Maroc
    synopsis: "Publicité promotionnelle pour le tourisme marocain. Images aériennes de paysages et villes impériales.",
    meta: "Extrait automatique - 214 mots analysés",
    risks: [
      { label: "Contenu Sensible", value: 10, color: "risk-low" },
      { label: "Risques Tournage", value: 20, color: "risk-low" },
      { label: "Permis Requis", value: 15, color: "risk-low" }
    ],
    score: "Conforme",
    recommendations: ["Aucune action requise"]
  }
};

// Make dossier items clickable
document.querySelectorAll('.dossier-item').forEach(item => {
  item.addEventListener('click', () => {
    // Remove active from all
    document.querySelectorAll('.dossier-item').forEach(d => d.classList.remove('active'));
    // Activate clicked one
    item.classList.add('active');

    const id = item.getAttribute('data-id');
    const data = dossierData[id];

    // Update synopsis
    document.getElementById('synopsis-text').textContent = data.synopsis;
    document.getElementById('synopsis-meta').textContent = data.meta;

    // Update risk bars
    const resultsHTML = data.risks.map(r => `
      <div class="risk-item">
        <div class="risk-label">
          <span>${r.label}</span>
          <span class="risk-value">${r.value}%</span>
        </div>
        <div class="risk-bar">
          <div class="risk-bar-fill ${r.color}" style="width: ${r.value}%;"></div>
        </div>
      </div>
    `).join('');
    document.getElementById('risk-results').innerHTML = resultsHTML;

    // Update final score
    const scoreHTML = `
      <div class="score-badge score-high">
        <span class="score-label">Score Final</span>
        <span class="score-value">${data.score}</span>
      </div>
      <div class="score-recommendations">
        <h4>Recommandations Auto</h4>
        <ul>${data.recommendations.map(rec => `<li>${rec}</li>`).join('')}</ul>
      </div>
    `;
    document.getElementById('final-score').innerHTML = scoreHTML;
  });
});

// Make recent analyses clickable
document.querySelectorAll('.recent-item').forEach(item => {
  item.addEventListener('click', () => {
    const name = item.getAttribute('data-name');
    alert(`📊 Analyse détaillée de "${name}"\n\nScore : Conforme / Moyen / Elevé\nDétails complets disponibles dans le rapport PDF.`);
  });
});
//stats feature 1
// Global Stats Charts
window.renderStatsCharts=function() {
  // Risky productions bar
  const riskyHTML = `
    <div class="bar-row"><div class="bar-label">Les Ombres de Casablanca</div><div class="bar"><div class="bar-fill" style="width:92%; background:#EF4444"></div></div><span class="font-bold text-red-600">92%</span></div>
    <div class="bar-row"><div class="bar-label">Rébellion</div><div class="bar"><div class="bar-fill" style="width:85%; background:#EF4444"></div></div><span class="font-bold text-red-600">85%</span></div>
    <div class="bar-row"><div class="bar-label">Marrakech Express</div><div class="bar"><div class="bar-fill" style="width:78%; background:#F59E0B"></div></div><span class="font-bold text-amber-600">78%</span></div>
  `;
  document.getElementById('risky-bar').innerHTML = riskyHTML;

  // Safe productions bar
  const safeHTML = `
    <div class="bar-row"><div class="bar-label">Désert Rouge</div><div class="bar"><div class="bar-fill" style="width:12%; background:#10B981"></div></div><span class="font-bold text-green-600">12%</span></div>
    <div class="bar-row"><div class="bar-label">Côte Sauvage</div><div class="bar"><div class="bar-fill" style="width:18%; background:#10B981"></div></div><span class="font-bold text-green-600">18%</span></div>
    <div class="bar-row"><div class="bar-label">Nuit Blanche</div><div class="bar"><div class="bar-fill" style="width:25%; background:#10B981"></div></div><span class="font-bold text-green-600">25%</span></div>
  `;
  document.getElementById('safe-bar').innerHTML = safeHTML;
}

// Call it when page loads
window.addEventListener('load', renderStatsCharts);

//map tunisia audio viseul feature 2
// Initialize Leaflet Map of Tunisia

// Initialize Leaflet Map
let tunisiaMap;

window.initTunisiaMap=function() {
  const mapContainer = document.getElementById('tunisia-map');
  if (!mapContainer) return;

  tunisiaMap = L.map('tunisia-map').setView([34.0, 9.5], 7);

  L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap France',
    maxZoom: 19,
    minZoom: 6
  }).addTo(tunisiaMap);

  const locations = [
    { id:1, lat: 36.8,  lng: 10.2,  name: "Tunis",        type: "conflict",  coord: "36.8065, 10.1815", company: "Ombre du Sahel", phone: "+216 22 555 123" },
    { id:2, lat: 36.87, lng: 10.33, name: "Sidi Bou Said",type: "conflict",  coord: "36.8700, 10.3400", company: "Carthage Film", phone: "+216 98 777 456" },
    { id:3, lat: 36.4,  lng: 10.6,  name: "Hammamet",     type: "active",    coord: "36.4000, 10.6167", company: "Mediterranean Blue", phone: "+216 71 234 567" },
    { id:4, lat: 34.75, lng: 10.8,  name: "Sfax",         type: "active",    coord: "34.7500, 10.8000", company: "Sfax Story", phone: "+216 74 123 890" },
    { id:5, lat: 33.0,  lng: 11.5,  name: "Djerba",       type: "active",    coord: "33.0000, 11.5000", company: "Island Memories", phone: "+216 75 987 654" },
    { id:6, lat: 32.9,  lng: 10.45, name: "Tataouine",    type: "pending",   coord: "32.9000, 10.4500", company: "Desert Storm", phone: "+216 29 111 222" }
  ];

  // Build clickable list
  let listHTML = '';
  locations.forEach(loc => {
    const statusColor = loc.type === "conflict" ? "red" : loc.type === "pending" ? "amber" : "green";
    const statusText = loc.type === "conflict" ? "⚠️ Conflit" : loc.type === "pending" ? "⏳ En attente" : "✅ Actif";

    listHTML += `
      <div class="location-item" data-id="${loc.id}" style="display:flex;justify-content:space-between;align-items:center;">
        <div>
          <strong>${loc.name}</strong><br>
          <span style="font-size:11px;color:#666;">${loc.coord}</span>
        </div>
        <div style="text-align:right;">
          <span style="font-size:11px;padding:3px 10px;border-radius:9999px;background:var(--${statusColor}-dim);color:var(--${statusColor});">
            ${statusText}
          </span>
        </div>
      </div>`;
  });
  document.getElementById('location-list').innerHTML = listHTML;

  // Add markers with strong glow for conflicts
  locations.forEach(loc => {
    const color = loc.type === "conflict" ? "#ef4444" : loc.type === "pending" ? "#f59e0b" : "#10b981";

    const marker = L.circleMarker([loc.lat, loc.lng], {
      radius: 11,
      fillColor: color,
      color: "#ffffff",
      weight: 4,
      opacity: 1,
      fillOpacity: 0.95
    }).addTo(tunisiaMap);

    if (loc.type === "conflict") {
      marker.getElement().classList.add("pin-pulse-red");
    }

    marker.bindPopup(`<b>${loc.name}</b><br>${loc.type === "conflict" ? "⚠️ Conflit détecté" : "✅ Actif"}`);

    marker.on('click', () => showLocationDetail(loc.name.toLowerCase()));
  });

  // Make list items clickable
  document.querySelectorAll('.location-item').forEach(item => {
    item.addEventListener('click', () => {
      const id = parseInt(item.getAttribute('data-id'));
      const loc = locations.find(l => l.id === id);
      if (loc && tunisiaMap) {
        tunisiaMap.flyTo([loc.lat, loc.lng], 12, { duration: 1.5 });
        showLocationDetail(loc.name.toLowerCase());
      }
    });
  });
}

// Auto-initialize when Feature 2 becomes visible
document.addEventListener('DOMContentLoaded', () => {
  const feature2 = document.getElementById('audio-feature-2');
  if (feature2) {
    const observer = new MutationObserver(() => {
      if (feature2.style.display !== 'none' && !tunisiaMap) {
        initTunisiaMap();
      }
    });
    observer.observe(feature2, { attributes: true, attributeFilter: ['style'] });
  }
});
//resolve model feature2
// Show Resolve Modal
window.showResolveModal=function() {
  document.getElementById('resolve-modal').style.display = 'flex';
}

// Hide Resolve Modal
window.hideResolveModal=function() {
  document.getElementById('resolve-modal').style.display = 'none';
}

// Send Notification (demo)
window.sendNotification=function() {
  const reason = document.getElementById('reason-text').value || "Conflit de dates détecté";
  document.getElementById('preview-reason').textContent = reason;
  alert("✅ Notification envoyée par email à la société concernée !\n\n(En vrai, ceci enverrait un email via le backend)");
  hideResolveModal();
}

// Update your initTunisiaMap() to include glowing + list click
// (I already gave you the full updated version in previous message — keep it, it already has the glowing red dots)

// Make list items clickable with dropdown info
document.addEventListener('DOMContentLoaded', () => {
  // ... your existing code ...

  // After building the list, add this:
  document.querySelectorAll('.location-item').forEach(item => {
    item.addEventListener('click', () => {
      const details = item.nextElementSibling;
      if (details && details.classList.contains('location-details')) {
        details.style.display = details.style.display === 'block' ? 'none' : 'block';
      } else {
        // Create expandable details on first click
        const detailHTML = `
          <div class="location-details" style="margin-top:6px;padding:12px 16px;background:#f0e6d0;border-radius:10px;font-size:13px;display:block;">
            <strong>Production :</strong> Ombre du Sahel<br>
            <strong>Téléphone :</strong> +216 22 555 123<br>
            <strong>Dates :</strong> 18 - 28 Juillet 2026<br>
            <strong>Statut :</strong> Conflit détecté (2 autorisations chevauchent)
          </div>`;
        item.insertAdjacentHTML('afterend', detailHTML);
      }
    });
  });
});

// Show modal
window.showResolveModal=function() {
  document.getElementById('resolve-modal').style.display = 'flex';
}

// Hide modal
window.hideResolveModal=function() {
  document.getElementById('resolve-modal').style.display = 'none';
}

// Live preview of the email
document.addEventListener('DOMContentLoaded', () => {
  const textarea = document.getElementById('reason-text');
  if (textarea) {
    textarea.addEventListener('input', function() {
      const preview = document.getElementById('preview-message');
      const reason = this.value.trim() || "Conflit de lieu détecté avec une autre production";
      preview.innerHTML = `
        Bonjour,<br><br>
        Nous vous informons qu’un conflit de lieu a été détecté pour votre production <strong>Carthage Story</strong>.<br><br>
        Raison : ${reason}<br><br>
        Merci de nous contacter rapidement pour régulariser la situation.<br><br>
        Cordialement,<br>
        Direction des Arts Audio-Visuels — Ministère de la Culture
      `;
    });
  }
});

// Send notification (demo)
window.sendNotification=function() {
  const reason = document.getElementById('reason-text').value.trim() || "Conflit de lieu détecté";
  alert(`✅ Notification envoyée avec succès à Carthage Story !\n\nRaison envoyée :\n${reason}\n\n(En vrai, ceci enverrait un email via Laravel Mail)`);
  hideResolveModal();
}

//feature 3
// ====================== FEATURE 3 — CALENDRIER DES TOURNAGES (FINAL FIXED) ======================

let currentDate = new Date(2026, 3, 1); // April 2026
let selectedDay = null;

/* ─────────────────────────────────────────────
   RICH DATA — each entry has full admin context
───────────────────────────────────────────── */
const tournagesData = [
  {
    id: 1, day: 1,
    title: 'Festival Carthage — Captation',
    company: 'Télévision Nationale Tunisienne (TNT)',
    nationality: '🇹🇳 Tunisienne',
    type: 'green',
    crew: 45,
    location: 'Médina de Tunis · Théâtre romain',
    dates: '1 – 15 Avril 2026',
    expiry: '15/04/2026',
    conflict: false,
    foreign: false,
    authorizations: [
      { label: 'Autorisation tournage', status: 'ok', color: 'var(--green)' },
      { label: 'Captation lieu public', status: 'ok', color: 'var(--green)' },
    ],
    contact: 'Nadia Chabbi — +216 22 100 200',
    notes: 'Captation officielle. Coordination avec la mairie de Tunis requise. Accès VIP + régie son.',
    dossierRef: 'DOS-2026-0301',
  },
  {
    id: 2, day: 3,
    title: 'Pub TotalEnergies',
    company: 'TotalEnergies Marketing Tunisie',
    nationality: '🇫🇷 Française',
    type: 'amber',
    crew: 12,
    location: 'Hammamet — Plage Yasmine',
    dates: '3 – 10 Avril 2026',
    expiry: '10/04/2026',
    conflict: false,
    foreign: true,
    authorizations: [
      { label: 'Autorisation tournage', status: 'ok',      color: 'var(--green)' },
      { label: 'Exonération TVA',       status: 'pending', color: 'var(--amber)' },
      { label: 'Exonération douane',    status: 'pending', color: 'var(--amber)' },
    ],
    contact: 'Pierre Lefèvre — +33 6 12 34 56 78',
    notes: 'Production étrangère. Équipement Sony FX9 importé temporairement. Douane en attente.',
    dossierRef: 'DOS-2026-0303',
  },
  {
    id: 3, day: 6,
    title: 'Island Memories',
    company: 'Ciné Méditerranée SA (Italie)',
    nationality: '🇮🇹 Italienne',
    type: 'blue',
    crew: 28,
    location: 'Djerba — Houmt Souk & plages',
    dates: '6 – 25 Avril 2026',
    expiry: '25/04/2026',
    conflict: false,
    foreign: true,
    authorizations: [
      { label: 'Autorisation tournage',   status: 'ok', color: 'var(--green)' },
      { label: 'Visa tournage CNCI',      status: 'ok', color: 'var(--green)' },
      { label: 'Exonération TVA',         status: 'ok', color: 'var(--green)' },
      { label: 'Exonération douane',      status: 'ok', color: 'var(--green)' },
    ],
    contact: 'Marco Rossi — +39 335 123 4567',
    notes: 'Besoin de 3 attestations Long métrage italo-tunisien. Co-production officielle. Toutes autorisations reçues.',
    dossierRef: 'DOS-2026-0306',
  },
  {
    id: 4, day: 7,
    title: "L'Ombre du Sahel",
    company: 'Lumières du Sud Productions (France)',
    nationality: '🇫🇷 Française',
    type: 'red',
    crew: 35,
    location: 'Médina de Tunis — Rue de la Kasbah',
    dates: '7 – 20 Avril 2026',
    expiry: '20/04/2026',
    conflict: true,
    conflictWith: 'Carthage Story (même zone 18–22 Avr)',
    foreign: true,
    authorizations: [
      { label: 'Autorisation tournage', status: 'conflict', color: 'var(--red)' },
      { label: 'Visa tournage CNCI',    status: 'ok',       color: 'var(--green)' },
      { label: 'Exonération TVA',       status: 'ok',       color: 'var(--green)' },
      { label: 'Zone sensible',         status: 'pending',  color: 'var(--amber)' },
    ],
    contact: 'Sophie Bernard — +33 6 98 76 54 32',
    notes: '⚠️ CONFLIT: chevauchement avec "Carthage Story" sur le même périmètre (18–22 Avr). Résolution requise avant le 12 Avr.',
    dossierRef: 'DOS-2026-0307',
  },
  {
    id: 5, day: 8,
    title: 'Clip Sami Fehri',
    company: 'SFP Productions Tunisie',
    nationality: '🇹🇳 Tunisienne',
    type: 'green',
    crew: 15,
    location: 'Tunis — Sidi Bou Saïd · Terrasses',
    dates: '8 – 12 Avril 2026',
    expiry: '12/04/2026',
    conflict: false,
    foreign: false,
    authorizations: [
      { label: 'Autorisation tournage', status: 'ok', color: 'var(--green)' },
      { label: 'Autorisation drone',    status: 'ok', color: 'var(--green)' },
    ],
    contact: 'Rania Gharbi — +216 55 200 300',
    notes: 'Clip musical. Drone autorisé avant 8h (lever du soleil). Équipe légère.',
    dossierRef: 'DOS-2026-0308',
  },
  {
    id: 6, day: 9,
    title: 'Desert Storm',
    company: 'Paramount Pictures International',
    nationality: '🇺🇸 Américaine',
    type: 'red',
    crew: 65,
    location: 'Tataouine — Ksar Ouled Soltane (Site classé)',
    dates: '9 – 18 Avril 2026',
    expiry: '18/04/2026',
    conflict: true,
    conflictWith: 'Sahara Chronicles sur même ksour (10–15 Avr)',
    foreign: true,
    authorizations: [
      { label: 'Autorisation tournage',      status: 'conflict', color: 'var(--red)' },
      { label: 'Site classé UNESCO',         status: 'pending',  color: 'var(--amber)' },
      { label: 'Visa tournage CNCI',         status: 'ok',       color: 'var(--green)' },
      { label: 'Autorisation pyrotechnie',   status: 'pending',  color: 'var(--amber)' },
      { label: 'Exonération TVA',            status: 'ok',       color: 'var(--green)' },
      { label: 'Exonération douane',         status: 'ok',       color: 'var(--green)' },
    ],
    contact: 'James Walker — +1 310 555 0199',
    notes: '⚠️ CONFLIT avec Sahara Chronicles. Pyrotechnie en attente approbation sécurité. Site UNESCO: surveillance requise.',
    dossierRef: 'DOS-2026-0309',
  },
  {
    id: 7, day: 10,
    title: 'Mediterranean Blue',
    company: 'Ciné Méditerranée SA (Italie)',
    nationality: '🇮🇹 Italienne',
    type: 'blue',
    crew: 22,
    location: 'Hammamet — Port de pêche & marché',
    dates: '10 – 22 Avril 2026',
    expiry: '22/04/2026',
    conflict: false,
    foreign: true,
    authorizations: [
      { label: 'Autorisation tournage', status: 'ok', color: 'var(--green)' },
      { label: 'Visa tournage CNCI',    status: 'ok', color: 'var(--green)' },
      { label: 'Exonération TVA',       status: 'ok', color: 'var(--green)' },
    ],
    contact: 'Giulia Conti — +39 347 987 6543',
    notes: 'Documentaire pêcheurs. Tournage en mer prévu (12–14 Avr). Coordination capitainerie.',
    dossierRef: 'DOS-2026-0310',
  },
  {
    id: 8, day: 12,
    title: 'Sfax Story',
    company: 'Synapse Cinéma Tunisie',
    nationality: '🇹🇳 Tunisienne',
    type: 'green',
    crew: 18,
    location: 'Sfax — Médina & port commercial',
    dates: '12 – 28 Avril 2026',
    expiry: '28/04/2026',
    conflict: false,
    foreign: false,
    authorizations: [
      { label: 'Autorisation tournage',    status: 'ok', color: 'var(--green)' },
      { label: 'Accès port commercial',    status: 'ok', color: 'var(--green)' },
    ],
    contact: 'Fares Abidi — +216 74 123 890',
    notes: 'Long métrage tunisien. Scènes nocturnes prévues 23h–2h. Coordination police requise.',
    dossierRef: 'DOS-2026-0312',
  },
  {
    id: 9, day: 14,
    title: 'Sahara Chronicles',
    company: 'BBC Earth Documentaries (UK)',
    nationality: '🇬🇧 Britannique',
    type: 'amber',
    crew: 14,
    location: 'Tataouine — Dahar & zones désertiques',
    dates: '14 – 5 Mai 2026',
    expiry: '05/05/2026',
    conflict: true,
    conflictWith: 'Desert Storm (Ksar Ouled Soltane 9–18 Avr)',
    foreign: true,
    authorizations: [
      { label: 'Autorisation tournage', status: 'conflict', color: 'var(--red)' },
      { label: 'Visa tournage CNCI',    status: 'ok',       color: 'var(--green)' },
      { label: 'Zone désertique',       status: 'ok',       color: 'var(--green)' },
    ],
    contact: 'Dr. Emma Clarke — +44 7911 123456',
    notes: 'Documentaire nature BBC. Conflit partiel avec Desert Storm (même zone ksour). Résolution en cours.',
    dossierRef: 'DOS-2026-0314',
  },
  {
    id: 10, day: 15,
    title: 'Tournage Djerba — Part II',
    company: 'Ciné Méditerranée SA (Italie)',
    nationality: '🇮🇹 Italienne',
    type: 'blue',
    crew: 30,
    location: 'Djerba — El Ghriba & Guellala',
    dates: '15 – 30 Avril 2026',
    expiry: '30/04/2026',
    conflict: false,
    foreign: true,
    authorizations: [
      { label: 'Autorisation tournage', status: 'ok', color: 'var(--green)' },
      { label: 'Synagogue El Ghriba',   status: 'ok', color: 'var(--green)' },
      { label: 'Autorisation drone',    status: 'ok', color: 'var(--green)' },
    ],
    contact: 'Marco Rossi — +39 335 123 4567',
    notes: 'Suite Island Memories. Accès synagogue El Ghriba accordé (protocole strict). Drone autorisé zones côtières uniquement.',
    dossierRef: 'DOS-2026-0315',
  },
  {
    id: 11, day: 18,
    title: 'Carthage Story',
    company: 'Kartago Films GmbH (Allemagne)',
    nationality: '🇩🇪 Allemande',
    type: 'red',
    crew: 45,
    location: 'Médina de Tunis · Carthage · Sidi Bou Saïd',
    dates: '18 – 25 Avril 2026',
    expiry: '25/04/2026',
    conflict: true,
    conflictWith: "L'Ombre du Sahel (Médina 18–20 Avr)",
    foreign: true,
    authorizations: [
      { label: 'Autorisation tournage',  status: 'conflict', color: 'var(--red)' },
      { label: 'Ruines Carthage',        status: 'ok',       color: 'var(--green)' },
      { label: 'Visa tournage CNCI',     status: 'ok',       color: 'var(--green)' },
      { label: 'Exonération TVA',        status: 'ok',       color: 'var(--green)' },
      { label: 'Exonération douane',     status: 'ok',       color: 'var(--green)' },
    ],
    contact: 'Klaus Fischer — +49 176 123 45678',
    notes: '⚠️ CONFLIT Médina (18–20 Avr) avec L\'Ombre du Sahel. Proposition: décalage de 2 jours ou répartition zones.',
    dossierRef: 'DOS-2026-0318',
  },
  {
    id: 12, day: 20,
    title: 'Heritage Aghlabide',
    company: 'Office National du Tourisme Tunisien',
    nationality: '🇹🇳 Tunisienne',
    type: 'green',
    crew: 19,
    location: 'Kairouan — Médina & Bassins aghlabides',
    dates: '20 – 10 Mai 2026',
    expiry: '10/05/2026',
    conflict: false,
    foreign: false,
    authorizations: [
      { label: 'Autorisation tournage', status: 'ok', color: 'var(--green)' },
      { label: 'Sites UNESCO Kairouan', status: 'ok', color: 'var(--green)' },
    ],
    contact: 'Sonia Karray — +216 77 200 400',
    notes: 'Documentaire touristique officiel. Accès complet sites UNESCO accordé. Guide officiel obligatoire.',
    dossierRef: 'DOS-2026-0320',
  },
  {
    id: 13, day: 22,
    title: 'Nuit Malouf — Captation',
    company: 'Radio Télévision Tunisienne (RTT)',
    nationality: '🇹🇳 Tunisienne',
    type: 'amber',
    crew: 40,
    location: 'Sidi Bou Saïd — Palais Ennejma Ezzahra',
    dates: '22 – 23 Avril 2026',
    expiry: '23/04/2026',
    conflict: false,
    foreign: false,
    authorizations: [
      { label: 'Autorisation tournage', status: 'ok',      color: 'var(--green)' },
      { label: 'Accès palais privé',    status: 'pending', color: 'var(--amber)' },
      { label: 'Captation musicale',    status: 'ok',      color: 'var(--green)' },
    ],
    contact: 'Zied Haddad — +216 71 800 700',
    notes: 'Captation grand concert Malouf. Accès palais en attente confirmation propriétaire. Date serrée.',
    dossierRef: 'DOS-2026-0322',
  },
  {
    id: 14, day: 25,
    title: 'Voices of Tunis',
    company: 'Al Jazeera Documentary (Qatar)',
    nationality: '🇶🇦 Qatarie',
    type: 'blue',
    crew: 12,
    location: 'Médina de Tunis — Souk & Mosquée',
    dates: '25 Avr – 2 Mai 2026',
    expiry: '02/05/2026',
    conflict: false,
    foreign: true,
    authorizations: [
      { label: 'Autorisation tournage',  status: 'ok',      color: 'var(--green)' },
      { label: 'Visa tournage CNCI',     status: 'ok',      color: 'var(--green)' },
      { label: 'Lieux de culte',         status: 'pending', color: 'var(--amber)' },
    ],
    contact: 'Khalid Al-Rashidi — +974 5555 1234',
    notes: 'Documentaire Al Jazeera. Autorisation lieux de culte: ministère des affaires religieuses requis.',
    dossierRef: 'DOS-2026-0325',
  },
  {
    id: 15, day: 27,
    title: 'Revolution Sunrise',
    company: 'Deutsche Welle Films (Allemagne)',
    nationality: '🇩🇪 Allemande',
    type: 'purple',
    crew: 55,
    location: 'Tunis — Avenue Bourguiba · Place du 14 Jan.',
    dates: '27 Avr – 15 Mai 2026',
    expiry: '15/05/2026',
    conflict: false,
    sensitiveZone: true,
    foreign: true,
    authorizations: [
      { label: 'Autorisation tournage',   status: 'ok',       color: 'var(--green)' },
      { label: 'Zone sensible — Intérieur', status: 'ok',     color: 'var(--green)' },
      { label: 'Visa tournage CNCI',      status: 'ok',       color: 'var(--green)' },
      { label: 'Escorte sécurité',        status: 'required', color: 'var(--purple)' },
      { label: 'Exonération TVA',         status: 'ok',       color: 'var(--green)' },
    ],
    contact: 'Anna Müller — +49 221 380 5678',
    notes: '🟣 ZONE SENSIBLE. Escorte sécurité obligatoire. Aucune scène manifestation fictive autorisée. Scénario validé MAE.',
    dossierRef: 'DOS-2026-0327',
  },
];

/* ─────────────────────────────────────────────
   BUILD CALENDAR
───────────────────────────────────────────── */
window.buildFullCalendar = function() {
  const monthView = document.getElementById('calendar-month-view');
  if (!monthView) return;
  monthView.innerHTML = '';

  const weekdays = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];
  weekdays.forEach(d => {
    const el = document.createElement('div');
    el.className = 'calendar-weekday';
    el.textContent = d;
    monthView.appendChild(el);
  });

  // April 2026 starts on Wednesday (day 3 = index 3)
  const firstDayOffset = 3;
  for (let i = 0; i < firstDayOffset; i++) {
    const empty = document.createElement('div');
    empty.className = 'calendar-day calendar-day-empty';
    monthView.appendChild(empty);
  }

  const today = 18; // simulate today = 18 Apr

  for (let day = 1; day <= 30; day++) {
    const dayEvents = tournagesData.filter(e => e.day === day);
    const hasConflict = dayEvents.some(e => e.conflict);
    const hasSensitive = dayEvents.some(e => e.sensitiveZone);

    const dayEl = document.createElement('div');
    let cls = 'calendar-day';
    if (day === today) cls += ' calendar-day-today';
    if (dayEvents.length > 0) cls += ' has-events';
    if (hasConflict) cls += ' has-conflict';
    dayEl.className = cls;

    if (dayEvents.length > 0) {
      dayEl.onclick = () => showDayDetail(day);
    }

    // Conflict indicator dot
    const conflictDot = hasConflict ? `<div class="day-conflict-indicator"></div>` : '';

    // Show max 2 events, then "+N more"
    const visibleEvents = dayEvents.slice(0, 2);
    const extraCount = dayEvents.length - 2;

    dayEl.innerHTML = `
      <div class="day-number">${day}</div>
      ${conflictDot}
      ${visibleEvents.map(ev => `
        <div class="calendar-event ${ev.type}">
          <span class="event-title">${ev.title}</span>
          <span class="event-meta">${ev.crew} pers.</span>
        </div>
      `).join('')}
      ${extraCount > 0 ? `<div class="cal-more-chip">+${extraCount} autre${extraCount > 1 ? 's' : ''}</div>` : ''}
    `;

    monthView.appendChild(dayEl);
  }
}

/* ─────────────────────────────────────────────
   SHOW DAY DETAIL
───────────────────────────────────────────── */
/* ─────────────────────────────────────────────
   SHOW DAY DETAIL — RICH RIGHT PANEL
───────────────────────────────────────────── */
window.showDayDetail = function(day){
  selectedDay = day;
  const events = tournagesData.filter(e => e.day === day);

  // Switch visibility: hide empty state, show rich content
  document.getElementById('cal-detail-empty').style.display = 'none';
  document.getElementById('cal-detail-content').style.display = 'block';

  const totalCrew = events.reduce((sum, e) => sum + (e.crew || 0), 0);
  const conflicts = events.filter(e => e.conflict).length;
  const foreign   = events.filter(e => e.foreign).length;

  // Header
  document.getElementById('cal-detail-header').innerHTML = `
    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
      <div>
        <div class="cal-detail-day-title">${day} Avril 2026</div>
        <div class="cal-detail-day-sub">
          <span>🎬 ${events.length} production${events.length > 1 ? 's' : ''}</span>
          <span>👥 ${totalCrew} personnes sur site</span>
          ${conflicts > 0 ? `<span style="color:var(--red);">⚠️ ${conflicts} conflit${conflicts > 1 ? 's' : ''}</span>` : ''}
          ${foreign > 0 ? `<span style="color:var(--blue);">🌍 ${foreign} étranger${foreign > 1 ? 's' : ''}</span>` : ''}
        </div>
      </div>
      <button onclick="closeDayDetail()" style="background:none;border:none;color:var(--text3);font-size:22px;cursor:pointer;">✕</button>
    </div>
  `;

  // Production cards
  const container = document.getElementById('cal-detail-productions');
  container.innerHTML = '';

  if (events.length === 0) {
    container.innerHTML = `<div style="text-align:center;padding:40px;color:var(--text3);">Aucune production ce jour.</div>`;
    return;
  }

  events.forEach(ev => {
    const card = document.createElement('div');
    card.className = `cal-prod-card ${ev.conflict ? 'conflict' : ''}`;

    const statusLabel = ev.conflict ? '⚠️ Conflit' : ev.foreign ? '🌍 Étranger' : '✅ Actif';
    const statusColor = ev.conflict ? 'var(--red)' : ev.foreign ? 'var(--blue)' : 'var(--green)';

    card.innerHTML = `
      <div class="cal-prod-card-header">
        <div style="flex:1;">
          <div class="cal-prod-name">${ev.title}</div>
          <div class="cal-prod-company">${ev.company}</div>
        </div>
        <span style="padding:4px 12px; border-radius:9999px; background:${statusColor}20; color:${statusColor}; font-size:11px; font-weight:700;">${statusLabel}</span>
      </div>

      <div class="cal-prod-body">
        <div class="cal-prod-row"><span class="cal-prod-row-icon">📍</span> <span class="cal-prod-row-label">Lieu</span> <span class="cal-prod-row-value">${ev.location}</span></div>
        <div class="cal-prod-row"><span class="cal-prod-row-icon">📅</span> <span class="cal-prod-row-label">Dates</span> <span class="cal-prod-row-value">${ev.dates}</span></div>
        <div class="cal-prod-row"><span class="cal-prod-row-icon">👥</span> <span class="cal-prod-row-label">Équipe</span> <span class="cal-prod-row-value">${ev.crew} personnes</span></div>
        <div class="cal-prod-row"><span class="cal-prod-row-icon">📞</span> <span class="cal-prod-row-label">Contact</span> <span class="cal-prod-row-value">${ev.contact}</span></div>
        ${ev.notes ? `<div class="cal-prod-notes">${ev.notes}</div>` : ''}
      </div>

      <div class="cal-prod-actions">
        <button class="btn btn-outline btn-sm" onclick="alert('📄 Dossier ${ev.dossierRef} ouvert')">📁 Dossier</button>
        <button class="btn btn-outline btn-sm" onclick="alert('📧 Notification envoyée à ${ev.contact.split('—')[0]}')">📧 Contacter</button>
        ${ev.conflict ? `<button class="btn btn-sm" style="background:var(--red-dim); color:var(--red);" onclick="showResolveModal()">⚠️ Résoudre conflit</button>` : ''}
      </div>
    `;

    container.appendChild(card);
  });
}

/* Close the detail panel */
window.closeDayDetail = function(){
  document.getElementById('cal-detail-empty').style.display = 'block';
  document.getElementById('cal-detail-content').style.display = 'none';
  selectedDay = null;
}

/* Check if a date expires within 7 days of April 7 (simulated today) */
window.isExpiringSoon = function(expiryStr) {
  const parts = expiryStr.split('/');
  if (parts.length < 3) return false;
  const expiry = new Date(parseInt(parts[2]), parseInt(parts[1]) - 1, parseInt(parts[0]));
  const simToday = new Date(2026, 3, 7); // April 7, 2026
  const diffDays = (expiry - simToday) / (1000 * 60 * 60 * 24);
  return diffDays >= 0 && diffDays <= 7;
}

/* ─────────────────────────────────────────────
   NAVIGATION & EXPORT
───────────────────────────────────────────── */
window.setCalendarView = function(view){
  document.getElementById('calendar-month-view').style.display = view === 'month' ? 'grid' : 'none';
  document.getElementById('calendar-week-view').style.display  = view === 'week'  ? 'block' : 'none';
  document.getElementById('btn-month').classList.toggle('active', view === 'month');
  document.getElementById('btn-week').classList.toggle('active',  view === 'week');
}

window.navigateCalendar = function(dir){
  currentDate.setMonth(currentDate.getMonth() + dir);
  const label = currentDate.toLocaleString('fr-FR', { month: 'long', year: 'numeric' });
  const capitalized = label.charAt(0).toUpperCase() + label.slice(1);
  document.getElementById('calendar-month-title').textContent = capitalized;
  buildFullCalendar();
  closeDayDetail();
}

window.exportCalendar = function(type){
  if (type === 'excel') alert('✅ Rapport Excel généré : Tournages_Avril_2026.xlsx\n\n15 tournages · 3 conflits · données complètes exportées');
  if (type === 'pdf')   alert('✅ Rapport PDF généré : Tournages_Avril_2026.pdf\n\nCalendrier mensuel avec fiches détaillées par production');
}
//end audio vis

//arts scenique
// ====================== ARTS SCÉNIQUES FEATURE SWITCHER ======================
window.showArtsFeature = function(n){
  // Hide ALL arts features
  document.querySelectorAll('.arts-feature').forEach(el => {
    el.style.display = 'none';
    el.classList.remove('active');
  });

  // Deactivate all mini-nav items
  document.querySelectorAll('.mini-nav-item[id^="arts-nav-"]').forEach(el => el.classList.remove('active'));

  // Show the selected feature
  const feature = document.getElementById('arts-feature-' + n);
  if (feature) {
    feature.style.display = 'block';
    feature.classList.add('active');
  }

  // Activate the sidebar item
  const navItem = document.getElementById('arts-nav-' + n);
  if (navItem) navItem.classList.add('active');

  // Auto-init Feature 1
// Auto-init Feature 1
if (n === 1 && typeof initArtsFeature1 === 'function') {
  initArtsFeature1();
}
// Auto-init Feature 2
if (n === 2 && typeof initArtsFeature2 === 'function') {
  initArtsFeature2();
}
// Auto-init Feature 3 HERE
 if (n === 3 && typeof initCommLog === 'function') {
    initCommLog();           // ← This is what was missing!
  }

  if (n === 4 && typeof initIdleDossierDetector === 'function') {
  initIdleDossierDetector();
}
}
// ====================== ARTS SCÉNIQUES — FEATURE 1 ======================
const sceniqueCandidates = [
  { id:1, name:"Leila Ben Amor", role:"Metteuse en scène", diploma:92, participation:78, experience:95, score:88, recommended:true },
  { id:2, name:"Karim Khlifi", role:"Acteur principal", diploma:75, participation:88, experience:82, score:81, recommended:true },
  { id:3, name:"Sofia Mansour", role:"Chorégraphe", diploma:95, participation:65, experience:90, score:85, recommended:true },
  { id:4, name:"Youssef Haddad", role:"Dramaturge", diploma:68, participation:92, experience:75, score:77, recommended:false },
];
window.renderCandidateList = function(){
  const container = document.getElementById('candidate-list');
  container.innerHTML = sceniqueCandidates.map(c => `
    <div class="candidate-item" onclick="selectCandidate(${c.id})">
      <strong>${c.name}</strong><br>
      <small style="color:var(--text3);">${c.role}</small>
      <div style="margin-top:6px;font-size:11px;">Score IA : <strong>${c.score}/100</strong></div>
    </div>
  `).join('');
}

let currentCandidate = null;

window.selectCandidate = function(id){
  currentCandidate = sceniqueCandidates.find(c => c.id === id);
  if (!currentCandidate) return;

  // Highlight active candidate
  document.querySelectorAll('.candidate-item').forEach(el => el.classList.remove('active'));
  const clickedItem = document.querySelector(`.candidate-item[onclick="selectCandidate(${id})"]`);
  if (clickedItem) clickedItem.classList.add('active');

  // Fill header
  document.getElementById('selected-candidate-header').innerHTML = `
    <strong>${currentCandidate.name}</strong><br>
    <small style="color:var(--text3);">${currentCandidate.role}</small>
  `;

  // Fill sliders with saved values
  document.getElementById('slider-diploma').value = currentCandidate.diploma;
  document.getElementById('slider-participation').value = currentCandidate.participation;
  document.getElementById('slider-experience').value = currentCandidate.experience;
  document.getElementById('val-diploma').textContent = currentCandidate.diploma + '%';
  document.getElementById('val-participation').textContent = currentCandidate.participation + '%';
  document.getElementById('val-experience').textContent = currentCandidate.experience + '%';

  // Show the evaluation panel
  document.getElementById('evaluation-panel').style.display = 'block';
  updateLiveScore();
}

window.updateLiveScore = function(){
  if (!currentCandidate) return;

  const d = parseInt(document.getElementById('slider-diploma').value);
  const p = parseInt(document.getElementById('slider-participation').value);
  const e = parseInt(document.getElementById('slider-experience').value);

  document.getElementById('val-diploma').textContent = d + '%';
  document.getElementById('val-participation').textContent = p + '%';
  document.getElementById('val-experience').textContent = e + '%';

  // Simple weighted average
  const finalScore = Math.round((d * 0.35) + (p * 0.30) + (e * 0.35));

  document.getElementById('final-score-display').textContent = finalScore;

  const badge = document.getElementById('recommended-badge');
  if (finalScore >= 80) {
    badge.style.background = 'var(--green)';
    badge.innerHTML = `⭐ Profil fortement recommandé`;
  } else if (finalScore >= 65) {
    badge.style.background = 'var(--amber)';
    badge.innerHTML = `⚠️ Profil intéressant`;
  } else {
    badge.style.background = 'var(--red)';
    badge.innerHTML = `📉 À revoir`;
  }
}

window.closeEvaluation = function() {
  document.getElementById('evaluation-panel').style.display = 'none';
  currentCandidate = null;
}

window.acceptCandidate = function() {
  alert("✅ Candidat accepté ! Notification envoyée à la commission des Arts Scéniques.");
  closeEvaluation();
}

window.rejectCandidate = function(){
  alert("✗ Candidat refusé. Raison enregistrée.");
  closeEvaluation();
}

window.requestMoreInfo = function(){
  alert("📧 Demande de complément d’information envoyée au candidat.");
}

// Auto-init when feature loads
window.initArtsFeature1 = function() {
  renderCandidateList();
}

// In your showAudioFeature / showPage, call: if (n === 1 && page === 'arts-sceniques') initArtsFeature1();
/* ══════════════════════════════════════════════
   ARTS SCÉNIQUES — FEATURE 2
   Commission Session Planner
   Add to your backend.js or include as separate script
══════════════════════════════════════════════ */

/* ─────────────────────────────────────────────
   STATIC DATA
───────────────────────────────────────────── */

const commissionSessions = [
  {
    id: 'S001',
    title: 'Session Printemps 2026 — Cartes Pro Arts Scéniques',
    date: '15 Avril 2026',
    time: '10h00',
    lieu: 'Salle des délibérations — Ministère',
    type: 'ordinaire',
    status: 'active',
    dossierCount: 18,
    decided: 14,
    membres: [
      { name: 'Mme Sana Karray',    initials: 'SK', role: 'Présidente de commission',  presence: 'confirmed' },
      { name: 'M. Tarek Belhaj',    initials: 'TB', role: 'Directeur Arts Scéniques',  presence: 'confirmed' },
      { name: 'Mme Leila Trabelsi', initials: 'LT', role: 'Représentante UGTT',        presence: 'confirmed' },
      { name: 'M. Hedi Slim',       initials: 'HS', role: 'Expert juridique',          presence: 'absent'    },
      { name: 'Mme Amel Boussi',    initials: 'AB', role: 'Chargée de dossiers',       presence: 'confirmed' },
    ],
    dossiers: [
      { ref: 'DOS-2026-0312', name: 'Leila Ben Amor',    role: 'Metteuse en scène',   score: 88, decision: 'approved',   notes: 'Profil exceptionnel. Diplôme ISAD + 12 ans expérience scénique. Recommandée à l\'unanimité.' },
      { ref: 'DOS-2026-0318', name: 'Karim Khlifi',      role: 'Acteur principal',    score: 81, decision: 'approved',   notes: 'Dossier complet. Participation Festival Carthage 3 éditions consécutives.' },
      { ref: 'DOS-2026-0325', name: 'Sofia Mansour',     role: 'Chorégraphe',         score: 85, decision: 'deferred',   notes: 'Ajourné : attestation CNSS manquante. Candidature à représenter dans 30 jours.' },
      { ref: 'DOS-2026-0331', name: 'Youssef Haddad',    role: 'Dramaturge',          score: 77, decision: 'complement', notes: 'Complément demandé : liste complète des productions dirigées 2020-2025.' },
      { ref: 'DOS-2026-0337', name: 'Amira Ferchichi',   role: 'Metteuse en scène',   score: 92, decision: 'approved',   notes: 'Excellente maîtrise des techniques contemporaines. Approuvée à l\'unanimité.' },
      { ref: 'DOS-2026-0344', name: 'Nabil Chaabane',    role: 'Comédien',            score: 68, decision: 'rejected',   notes: 'Dossier insuffisant. Expérience professionnelle insuffisante (&lt; 3 ans requis).' },
      { ref: 'DOS-2026-0350', name: 'Rania Belhadj',     role: 'Danseuse-chorégraphe',score: 79, decision: 'pending',    notes: '' },
      { ref: 'DOS-2026-0358', name: 'Mehdi Boukthir',    role: 'Acteur',              score: 83, decision: 'pending',    notes: '' },
    ]
  },
  {
    id: 'S002',
    title: 'Session Automne 2026 — Cartes Pro Arts Scéniques',
    date: '22 Septembre 2026',
    time: '09h30',
    lieu: 'Salle de conférence B — Cité de la Culture',
    type: 'ordinaire',
    status: 'upcoming',
    dossierCount: 29,
    decided: 0,
    membres: [
      { name: 'Mme Sana Karray',    initials: 'SK', role: 'Présidente de commission',  presence: 'confirmed' },
      { name: 'M. Tarek Belhaj',    initials: 'TB', role: 'Directeur Arts Scéniques',  presence: 'pending'   },
      { name: 'Mme Leila Trabelsi', initials: 'LT', role: 'Représentante UGTT',        presence: 'pending'   },
      { name: 'M. Hedi Slim',       initials: 'HS', role: 'Expert juridique',          presence: 'confirmed' },
      { name: 'Mme Amel Boussi',    initials: 'AB', role: 'Chargée de dossiers',       presence: 'confirmed' },
    ],
    dossiers: [
      { ref: 'DOS-2026-0412', name: 'Hana Triki',        role: 'Metteuse en scène',   score: 90, decision: 'pending', notes: '' },
      { ref: 'DOS-2026-0418', name: 'Sami Ben Slimane',  role: 'Comédien',            score: 74, decision: 'pending', notes: '' },
      { ref: 'DOS-2026-0424', name: 'Ines Meddeb',       role: 'Chorégraphe',         score: 86, decision: 'pending', notes: '' },
      { ref: 'DOS-2026-0431', name: 'Chaker Dhouib',     role: 'Metteur en scène',    score: 95, decision: 'pending', notes: '' },
    ]
  },
  {
    id: 'S003',
    title: 'Session Extraordinaire — Urgence CDC Mars 2026',
    date: '8 Mars 2026',
    time: '14h00',
    lieu: 'Salle des délibérations — Ministère',
    type: 'extraordinaire',
    status: 'closed',
    dossierCount: 6,
    decided: 6,
    membres: [
      { name: 'Mme Sana Karray',    initials: 'SK', role: 'Présidente de commission',  presence: 'confirmed' },
      { name: 'M. Tarek Belhaj',    initials: 'TB', role: 'Directeur Arts Scéniques',  presence: 'confirmed' },
      { name: 'Mme Amel Boussi',    initials: 'AB', role: 'Chargée de dossiers',       presence: 'confirmed' },
    ],
    dossiers: [
      { ref: 'DOS-2026-0201', name: 'Faouzi Thabet',    role: 'Directeur artistique', score: 91, decision: 'approved',  notes: 'Renouvellement accéléré — départ imminent à l\'international.' },
      { ref: 'DOS-2026-0207', name: 'Wided Ben Hmida',  role: 'Metteuse en scène',    score: 88, decision: 'approved',  notes: 'Approuvée. Contrat Festival Avignon à honorer.' },
      { ref: 'DOS-2026-0214', name: 'Lotfi Romdhane',   role: 'Acteur',               score: 72, decision: 'deferred',  notes: 'Ajourné — dossier incomplet malgré urgence signalée.' },
    ]
  }
];

/* Dossiers available to attach (not yet in a session) */
const availableDossiers = [
  { ref: 'DOS-2026-0501', name: 'Kalthoum Jebali',   role: 'Metteuse en scène',    score: 87 },
  { ref: 'DOS-2026-0508', name: 'Tahar Essid',        role: 'Comédien',             score: 76 },
  { ref: 'DOS-2026-0515', name: 'Mariem Hamdane',     role: 'Chorégraphe',          score: 83 },
  { ref: 'DOS-2026-0522', name: 'Zied Ben Romdhane',  role: 'Dramaturge',           score: 69 },
  { ref: 'DOS-2026-0529', name: 'Cyrine Chebil',      role: 'Actrice',              score: 91 },
  { ref: 'DOS-2026-0536', name: 'Anouar Brahem Jr.',  role: 'Metteur en scène',     score: 79 },
];

/* ─────────────────────────────────────────────
   STATE
───────────────────────────────────────────── */
let activeCommissionSessionId = null;
let activeDecisionDossierRef  = null;
let selectedDecisionValue     = null;
let selectedAttachRefs        = new Set();

/* ─────────────────────────────────────────────
   RENDER: Sessions list
───────────────────────────────────────────── */
window.renderCommissionSessions = function(){
  const container = document.getElementById('commission-sessions-list');
  if (!container) return;

  container.innerHTML = commissionSessions.map(s => {
    const typeLabel = s.type === 'ordinaire' ? 'Ordinaire' : s.type === 'extraordinaire' ? 'Extraordinaire' : 'Urgence CDC';
    const statusLabel = s.status === 'active' ? 'En cours' : s.status === 'upcoming' ? 'Planifiée' : 'Clôturée';
    const isSelected = s.id === activeCommissionSessionId;

    return `
      <div class="commission-session-card session-${s.status} ${isSelected ? 'selected' : ''}"
           onclick="selectCommissionSession('${s.id}')">
        <div class="session-card-top">
          <span class="session-card-title">${s.title}</span>
          <span class="session-type-badge ${s.type}">${typeLabel}</span>
        </div>
        <div class="session-card-meta">
          <span>📅 ${s.date} à ${s.time}</span>
          <span>📍 ${s.lieu}</span>
          <span>👥 ${s.membres.length} membres convoqués</span>
        </div>
        <div class="session-card-footer">
          <span class="session-dossier-count">
            🗂️ ${s.dossierCount} dossiers · ${s.decided} décisions rendues
          </span>
          <span class="session-status-pill ${s.status === 'active' ? 'active' : s.status === 'upcoming' ? 'upcoming' : 'closed'}">
            ${statusLabel}
          </span>
        </div>
      </div>
    `;
  }).join('');
}

/* ─────────────────────────────────────────────
   SELECT a session → show detail
───────────────────────────────────────────── */
window.selectCommissionSession = function(id){
  activeCommissionSessionId = id;
  const session = commissionSessions.find(s => s.id === id);
  if (!session) return;

  // Re-render list to update selected state
  renderCommissionSessions();

  // Show detail panel
  document.getElementById('commission-empty-state').style.display = 'none';
  document.getElementById('commission-session-detail').style.display = 'block';

  // Render header
  const typeLabel = session.type === 'ordinaire' ? 'Ordinaire' : 'Extraordinaire';
  const statusColor = session.status === 'active' ? 'var(--green)' : session.status === 'upcoming' ? 'var(--gold)' : 'var(--text3)';
  const statusLabel = session.status === 'active' ? 'En cours' : session.status === 'upcoming' ? 'Planifiée' : 'Clôturée';

  document.getElementById('commission-detail-header').innerHTML = `
    <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:10px;">
      <div>
        <div class="commission-detail-title">${session.title}</div>
        <div class="commission-detail-meta" style="margin-top:6px;">
          <span>📅 ${session.date} à ${session.time}</span>
          <span>📍 ${session.lieu}</span>
          <span>🗂️ ${session.dossierCount} dossiers</span>
        </div>
      </div>
      <div style="display:flex;gap:8px;align-items:center;flex-shrink:0;">
        <span style="font-size:11px;font-weight:700;padding:4px 12px;border-radius:10px;background:${statusColor}20;color:${statusColor};">
          ${statusLabel}
        </span>
        ${session.status !== 'closed' ? `<button class="btn btn-outline btn-sm" onclick="openNewSessionModal()">✏️ Modifier</button>` : ''}
      </div>
    </div>
  `;

  // Reset to first tab
  switchCommissionTab('dossiers', document.querySelector('.commission-tab'));

  // Render all tabs
  renderSessionDossiers(session);
  renderSessionDecisions(session);
  renderSessionMembres(session);
}

/* ─────────────────────────────────────────────
   RENDER: Dossiers tab
───────────────────────────────────────────── */
window.renderSessionDossiers = function(session){
  const container = document.getElementById('session-dossiers-list');
  if (!container) return;

  if (session.dossiers.length === 0) {
    container.innerHTML = `<div style="text-align:center;padding:30px;color:var(--text3);font-size:12px;">Aucun dossier attaché à cette session.<br>Cliquez sur "+ Attacher un dossier" pour commencer.</div>`;
    return;
  }

  container.innerHTML = session.dossiers.map(d => {
    const decisionMap = {
      approved:   ['✅ Approuvée',        'decision-approved'],
      rejected:   ['✕ Rejetée',           'decision-rejected'],
      deferred:   ['⏸ Ajournée',          'decision-deferred'],
      complement: ['📩 Complément requis', 'decision-complement'],
      pending:    ['⏳ En attente',         'decision-pending'],
    };
    const [decLabel, decClass] = decisionMap[d.decision] || decisionMap['pending'];

    return `
      <div class="session-dossier-row">
        <div class="session-dossier-info">
          <span class="session-dossier-name">${d.name}</span>
          <span class="session-dossier-sub">${d.ref} · ${d.role} · Score IA: <strong>${d.score}/100</strong></span>
        </div>
        <span class="session-dossier-decision ${decClass}">${decLabel}</span>
        ${session.status !== 'closed' && d.decision === 'pending' ? `
          <button class="btn btn-ghost btn-sm" onclick="openDecisionModal('${d.ref}', '${d.name}', '${d.role}')">
            ⚖️ Décider
          </button>
        ` : d.decision !== 'pending' ? `
          <button class="btn btn-ghost btn-sm" style="font-size:10px;" onclick="openDecisionModal('${d.ref}', '${d.name}', '${d.role}')">
            ✏️ Modifier
          </button>
        ` : ''}
      </div>
    `;
  }).join('');
}

/* ─────────────────────────────────────────────
   RENDER: Decisions tab
───────────────────────────────────────────── */
window.renderSessionDecisions = function(session) {
  const container = document.getElementById('session-decisions-list');
  if (!container) return;

  const decided = session.dossiers.filter(d => d.decision !== 'pending');

  if (decided.length === 0) {
    container.innerHTML = `<div style="text-align:center;padding:30px;color:var(--text3);font-size:12px;">Aucune décision enregistrée pour cette session.</div>`;
    return;
  }

  // Summary bar
  const counts = { approved: 0, rejected: 0, deferred: 0, complement: 0 };
  decided.forEach(d => { if (counts[d.decision] !== undefined) counts[d.decision]++; });
  const total = decided.length;

  container.innerHTML = `
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:18px;padding:14px;background:var(--bg3);border-radius:8px;">
      <div style="text-align:center;">
        <div style="font-size:20px;font-weight:800;font-family:var(--font-mono);color:var(--green);">${counts.approved}</div>
        <div style="font-size:10px;color:var(--text3);">Approuvées</div>
      </div>
      <div style="text-align:center;">
        <div style="font-size:20px;font-weight:800;font-family:var(--font-mono);color:var(--red);">${counts.rejected}</div>
        <div style="font-size:10px;color:var(--text3);">Rejetées</div>
      </div>
      <div style="text-align:center;">
        <div style="font-size:20px;font-weight:800;font-family:var(--font-mono);color:var(--blue);">${counts.deferred}</div>
        <div style="font-size:10px;color:var(--text3);">Ajournées</div>
      </div>
      <div style="text-align:center;">
        <div style="font-size:20px;font-weight:800;font-family:var(--font-mono);color:var(--purple);">${counts.complement}</div>
        <div style="font-size:10px;color:var(--text3);">Compléments</div>
      </div>
    </div>

    ${decided.map(d => {
      const colorMap = { approved: 'var(--green)', rejected: 'var(--red)', deferred: 'var(--blue)', complement: 'var(--purple)' };
      const labelMap = { approved: '✅ Approuvée', rejected: '✕ Rejetée', deferred: '⏸ Ajournée', complement: '📩 Complément' };
      const color = colorMap[d.decision] || 'var(--text3)';
      const label = labelMap[d.decision] || d.decision;

      return `
        <div class="decision-record-row">
          <div class="decision-record-top">
            <span class="decision-record-name">${d.name} <span style="font-size:10.5px;font-weight:400;color:var(--text3);">— ${d.role} · ${d.ref}</span></span>
            <span style="font-size:11px;font-weight:700;color:${color};padding:3px 10px;background:${color}18;border-radius:10px;">${label}</span>
          </div>
          ${d.notes ? `<div class="decision-record-notes">${d.notes}</div>` : '<div class="decision-record-notes" style="color:var(--text3);font-style:italic;">Aucune note enregistrée.</div>'}
          <div class="decision-record-footer">
            <span>📋 Score IA: ${d.score}/100</span>
            <span>👤 Rapporteur: Mme Sana Karray</span>
          </div>
        </div>
      `;
    }).join('')}
  `;
}

/* ─────────────────────────────────────────────
   RENDER: Membres tab
───────────────────────────────────────────── */
window.renderSessionMembres = function(session){
  const container = document.getElementById('session-membres-list');
  if (!container) return;

  const presenceLabel = { confirmed: '✅ Confirmé', pending: '⏳ En attente', absent: '✕ Absent' };
  const presenceClass = { confirmed: 'presence-confirmed', pending: 'presence-pending', absent: 'presence-absent' };

  container.innerHTML = session.membres.map(m => `
    <div class="membre-row">
      <div class="membre-avatar">${m.initials}</div>
      <div class="membre-info">
        <div class="membre-name">${m.name}</div>
        <div class="membre-role">${m.role}</div>
      </div>
      <span class="membre-presence ${presenceClass[m.presence]}">${presenceLabel[m.presence]}</span>
    </div>
  `).join('');
}

/* ─────────────────────────────────────────────
   TABS
───────────────────────────────────────────── */
window.switchCommissionTab = function(tab, btn) {
  // Hide all tab contents
  ['dossiers', 'decisions', 'membres'].forEach(t => {
    const el = document.getElementById('commission-tab-' + t);
    if (el) el.style.display = 'none';
  });

  // Deactivate all tab buttons
  document.querySelectorAll('.commission-tab').forEach(b => b.classList.remove('active'));

  // Show selected tab
  const target = document.getElementById('commission-tab-' + tab);
  if (target) target.style.display = 'block';

  // Activate button
  if (btn) btn.classList.add('active');
}

/* ─────────────────────────────────────────────
   MODAL: New Session
───────────────────────────────────────────── */
window.openNewSessionModal = function(){
  document.getElementById('new-session-modal').style.display = 'flex';
}

window.closeNewSessionModal = function(){
  document.getElementById('new-session-modal').style.display = 'none';
}

window.createSession = function(){
  alert('✅ Session planifiée avec succès !\n\nLes membres convoqués recevront une notification par email.\n\n(En production, ceci créerait la session en base de données)');
  closeNewSessionModal();
}

/* ─────────────────────────────────────────────
   MODAL: Attach Dossiers
───────────────────────────────────────────── */
window.openAttachModal = function(){
  selectedAttachRefs.clear();
  renderAttachList(availableDossiers);
  document.getElementById('attach-modal').style.display = 'flex';
}

window.closeAttachModal = function() {
  document.getElementById('attach-modal').style.display = 'none';
}

window.renderAttachList = function(data) {
  const container = document.getElementById('attach-dossier-list');
  if (!container) return;

  container.innerHTML = data.map(d => `
    <div class="attach-dossier-item ${selectedAttachRefs.has(d.ref) ? 'attach-selected' : ''}"
         onclick="toggleAttachDossier(this, '${d.ref}')">
      <input type="checkbox" ${selectedAttachRefs.has(d.ref) ? 'checked' : ''}>
      <div style="flex:1;">
        <strong style="font-size:12.5px;color:var(--text);">${d.name}</strong>
        <div style="font-size:10.5px;color:var(--text3);">${d.ref} · ${d.role} · Score IA: ${d.score}/100</div>
      </div>
    </div>
  `).join('');
}

window.toggleAttachDossier = function(el, ref){
  if (selectedAttachRefs.has(ref)) {
    selectedAttachRefs.delete(ref);
    el.classList.remove('attach-selected');
    el.querySelector('input').checked = false;
  } else {
    selectedAttachRefs.add(ref);
    el.classList.add('attach-selected');
    el.querySelector('input').checked = true;
  }
}

window.filterAttachList = function() {
  const search = (document.getElementById('attach-search')?.value || '').toLowerCase();
  const filtered = availableDossiers.filter(d =>
    d.name.toLowerCase().includes(search) ||
    d.ref.toLowerCase().includes(search) ||
    d.role.toLowerCase().includes(search)
  );
  renderAttachList(filtered);
}

window.attachSelectedDossiers = function(){
  const count = selectedAttachRefs.size;
  if (count === 0) {
    alert('Veuillez sélectionner au moins un dossier.');
    return;
  }
  alert(`✅ ${count} dossier(s) attaché(s) à la session avec succès !\n\n(En production, ceci mettrait à jour la base de données)`);
  closeAttachModal();
}

/* ─────────────────────────────────────────────
   MODAL: Record Decision
───────────────────────────────────────────── */
window.openDecisionModal = function(ref, name, role){
  activeDecisionDossierRef = ref;
  selectedDecisionValue = null;

  // Reset buttons
  document.querySelectorAll('.commission-decision-btn').forEach(b => b.classList.remove('decision-selected'));

  // Fill dossier info
  document.getElementById('decision-dossier-info').innerHTML = `
    <strong style="color:var(--text);">${name}</strong> — ${role}<br>
    <span style="font-family:var(--font-mono);font-size:11px;color:var(--text3);">${ref}</span>
  `;

  // Clear notes
  const notes = document.getElementById('decision-notes');
  if (notes) notes.value = '';

  document.getElementById('decision-modal').style.display = 'flex';
}

window.closeDecisionModal = function() {
  document.getElementById('decision-modal').style.display = 'none';
  activeDecisionDossierRef = null;
  selectedDecisionValue = null;
}

window.selectDecision = function(btn, value){
  // Deselect all
  document.querySelectorAll('.commission-decision-btn').forEach(b => b.classList.remove('decision-selected'));
  // Select clicked
  btn.classList.add('decision-selected');
  selectedDecisionValue = value;
}

window.saveDecision = function(){
  if (!selectedDecisionValue) {
    alert('Veuillez sélectionner une décision avant d\'enregistrer.');
    return;
  }

  const labelMap = {
    approved: 'Approuvée ✅',
    rejected: 'Rejetée ✕',
    deferred: 'Ajournée ⏸',
    complement: 'Complément requis 📩'
  };

  alert(`✅ Décision enregistrée : ${labelMap[selectedDecisionValue]}\nDossier : ${activeDecisionDossierRef}\n\n(En production, ceci notifierait le candidat)`);
  closeDecisionModal();

  // Update the static data to reflect decision (demo only)
  if (activeCommissionSessionId) {
    const session = commissionSessions.find(s => s.id === activeCommissionSessionId);
    if (session) {
      const dossier = session.dossiers.find(d => d.ref === activeDecisionDossierRef);
      if (dossier) {
        dossier.decision = selectedDecisionValue;
        dossier.notes = document.getElementById('decision-notes')?.value || '';
        session.decided = session.dossiers.filter(d => d.decision !== 'pending').length;
      }
      renderSessionDossiers(session);
      renderSessionDecisions(session);
      renderCommissionSessions(); // update count
    }
  }
}
 /* feature 2 arts scenique modifs :*/
 // ==================== AGENT APPROVAL RATE STATS (Feature 2) ====================

const agentStats = [
  { name: "Mme Sana Karray",   role: "Présidente",   total: 28, approved: 26, rate: 92.9, color: "#4ade80" },
  { name: "M. Tarek Belhaj",   role: "Directeur",    total: 19, approved: 14, rate: 73.7, color: "#fbbf24" },
  { name: "Mme Leila Trabelsi",role: "UGTT",         total: 22, approved: 19, rate: 86.4, color: "#4ade80" },
  { name: "Mme Amel Boussi",   role: "Chargée",      total: 31, approved: 21, rate: 67.7, color: "#f87171" },
  { name: "M. Hedi Slim",      role: "Expert",       total: 12, approved: 11, rate: 91.7, color: "#4ade80" }
];

window.renderAgentStats = function() {
  const container = document.getElementById('agent-stats-grid');
  if (!container) return;

  container.innerHTML = agentStats.map(agent => `
    <div class="agent-stat-card">
      <div class="agent-info">
        <div class="agent-name">${agent.name}</div>
        <div class="agent-role">${agent.role}</div>
      </div>
      <div class="agent-rate">
        <div class="agent-rate-bar">
          <div class="agent-rate-fill" style="width:${agent.rate}%; background:${agent.color};"></div>
        </div>
        <div class="agent-rate-text">${agent.rate}%</div>
      </div>
      <div class="agent-detail">
        ${agent.approved} / ${agent.total} approuvés
      </div>
    </div>
  `).join('');
}

// Call this in your init function
window.initArtsFeature2 = function(){
  renderCommissionSessions();
  renderAgentStats();                    // ← New line
  if (commissionSessions.length > 0) {
    selectCommissionSession(commissionSessions[0].id);
  }
}
/* ─────────────────────────────────────────────
   INIT — called by showArtsFeature(2)
───────────────────────────────────────────── */
window.initArtsFeature2 = function(){
  renderCommissionSessions();
  // Auto-select first session
  renderAgentStats();           // ← THIS WAS MISSING — now it will show the agent cards
  if (commissionSessions.length > 0) {
    selectCommissionSession(commissionSessions[0].id);
  }
}
(function(){

const COMM_DATA = [
  {
    id:1, comId:"COM-2026-000124", date:"08/04/2026", time:"09:34",
    candidate:"Amira Khlifi", type:"email", channel:"Email",
    subject:"Complément de dossier requis — Carte pro Arts Scéniques",
    sender:"Agent Leila Trabelsi", status:"read",
    hasAttachment:true, attachmentName:"attestation_formation.pdf", attachmentType:"PDF",
    fullMessage:"Bonjour Madame Khlifi,\n\nNous avons besoin du document manquant pour finaliser votre demande de carte professionnelle.\nMerci de nous le transmettre dans les plus brefs délais.\n\nCordialement,\nLeila Trabelsi",
    lifecycle:["sent","delivered","read"],
    smtp:"250 OK", provider:"Mailgun",
    auditLog:[
      {action:"Message créé",          by:"Leila Trabelsi", ts:"08/04/2026 09:30", color:"#34d399"},
      {action:"Envoyé via Mailgun",     by:"Système",        ts:"08/04/2026 09:34", color:"#34d399"},
      {action:"Délivré",               by:"Système",        ts:"08/04/2026 09:35", color:"#34d399"},
      {action:"Lu par destinataire",   by:"Système",        ts:"08/04/2026 10:12", color:"#34d399"}
    ]
  },
  {
    id:2, comId:"COM-2026-000125", date:"07/04/2026", time:"14:12",
    candidate:"Karim Ben Romdhane", type:"inapp", channel:"In-App",
    subject:"Votre carte professionnelle est prête à être retirée",
    sender:"Système", status:"delivered",
    hasAttachment:false,
    fullMessage:"Votre carte est maintenant disponible au guichet.\nMerci de vous présenter avec une pièce d'identité valide.",
    lifecycle:["sent","delivered"],
    smtp:"—", provider:"In-App",
    auditLog:[
      {action:"Notification déclenchée", by:"Système", ts:"07/04/2026 14:12", color:"#34d399"},
      {action:"Délivrée",               by:"Système", ts:"07/04/2026 14:12", color:"#34d399"}
    ]
  },
  {
    id:3, comId:"COM-2026-000126", date:"06/04/2026", time:"11:05",
    candidate:"Sofia Mansouri", type:"complement", channel:"Email",
    subject:"Demande de complément : Attestation de formation manquante",
    sender:"Agent Amel Boussi", status:"sent",
    hasAttachment:true, attachmentName:"formulaire_complement.docx", attachmentType:"DOCX",
    fullMessage:"Chère Sofia,\n\nNous avons remarqué que l'attestation de formation est manquante dans votre dossier.\nVeuillez la fournir dans un délai de 7 jours ouvrés.\n\nAgent Amel Boussi",
    lifecycle:["sent"],
    smtp:"250 Accepted", provider:"Mailgun",
    auditLog:[
      {action:"Message créé",       by:"Amel Boussi", ts:"06/04/2026 11:01", color:"#34d399"},
      {action:"Envoyé via Mailgun", by:"Système",     ts:"06/04/2026 11:05", color:"#34d399"}
    ]
  },
  {
    id:4, comId:"COM-2026-000127", date:"05/04/2026", time:"16:48",
    candidate:"Youssef Gafsi", type:"email", channel:"Email",
    subject:"Notification d'échéance — Renouvellement carte obligatoire",
    sender:"Agent Leila Trabelsi", status:"failed",
    hasAttachment:false,
    fullMessage:"Monsieur Gafsi,\n\nVotre carte professionnelle arrive à échéance le 15/05/2026.\nMerci d'entamer la procédure de renouvellement dès que possible.",
    lifecycle:["sent","failed"],
    smtp:"550 User unknown", provider:"Mailgun", bounceReason:"Adresse email invalide",
    auditLog:[
      {action:"Message créé",              by:"Leila Trabelsi", ts:"05/04/2026 16:44", color:"#34d399"},
      {action:"Tentative d'envoi",         by:"Système",        ts:"05/04/2026 16:48", color:"#34d399"},
      {action:"Échec — 550 User unknown",  by:"Système",        ts:"05/04/2026 16:48", color:"#f87171"}
    ]
  },
  {
    id:5, comId:"COM-2026-000128", date:"04/04/2026", time:"08:22",
    candidate:"Nour Belhadj", type:"inapp", channel:"In-App",
    subject:"Votre dossier a été transmis au comité de validation",
    sender:"Système", status:"pending",
    hasAttachment:false,
    fullMessage:"Votre dossier complet a été reçu et est en cours d'examen par le comité.\nDélai estimé : 5–10 jours ouvrés.",
    lifecycle:["pending"],
    smtp:"—", provider:"In-App",
    auditLog:[
      {action:"Notification planifiée", by:"Système", ts:"04/04/2026 08:20", color:"#fbbf24"}
    ]
  }
];

const STATUS_LABEL = {sent:"Envoyé",delivered:"Délivré",read:"Lu",pending:"En attente",failed:"Échoué"};
const STATUS_ICON  = {sent:"→",delivered:"✓",read:"✓✓",pending:"⏱",failed:"✕"};
const TYPE_CH      = {email:"cb-email",inapp:"cb-inapp",complement:"cb-complement",system:"cb-system"};

let commTab = "all";
let commActiveId = null;

// Expose tab setter globally so onclick="" in HTML works
window.setCommTab = function(el, val){
  document.querySelectorAll('#arts-feature-3 .filter-tab').forEach(t=>t.classList.remove('active'));
  el.classList.add('active');
  commTab = val;
  commActiveId = null;
  renderCommLog();
};

window.closeCommDrawer = function(){
  commActiveId = null;
  document.getElementById('comm-drawer').style.display = 'none';
  document.querySelectorAll('#comm-tbody tr').forEach(r=>r.classList.remove('comm-active'));
};

function getFiltered(){
  const s  = (document.getElementById('comm-search')||{value:''}).value.toLowerCase().trim();
  const sf = (document.getElementById('comm-status-filter')||{value:''}).value;
  return COMM_DATA.filter(d => {
    if(commTab !== 'all' && d.type !== commTab) return false;
    if(sf && d.status !== sf) return false;
    if(s && !d.candidate.toLowerCase().includes(s) &&
           !d.comId.toLowerCase().includes(s) &&
           !d.subject.toLowerCase().includes(s)) return false;
    return true;
  });
}

function lcHTML(lc, status){
  const steps = ["sent","delivered","read"];
  let h = "";
  steps.forEach((s,i)=>{
    let cls = "";
    if(status === "failed" && s === "sent") cls = "done";
    else if(status === "failed" && s === "delivered") cls = "fail";
    else if(lc.includes(s)) cls = "done";
    else if(status === "pending" && s === "sent") cls = "warn";
    h += `<div class="lc-dot ${cls}" title="${STATUS_LABEL[s]||s}"></div>`;
    if(i < steps.length-1){
      const lineDone = lc.includes(steps[i+1]) || (status==="failed" && i===0);
      h += `<div class="lc-line ${lineDone?"done":""}"></div>`;
    }
  });
  return `<div class="lc">${h}</div>`;
}

window.renderCommLog = function(){
  const rows = getFiltered();
  const tbody = document.getElementById('comm-tbody');
  if(!tbody) return;

  if(!rows.length){
    tbody.innerHTML = `<tr><td colspan="8"><div class="comm-empty">Aucune communication trouvée</div></td></tr>`;
    updateCommKPIs([]);
    return;
  }

  tbody.innerHTML = rows.map(d => `
    <tr onclick="toggleCommDetail(${d.id})" class="${commActiveId===d.id?'comm-active':''}">
      <td style="font-family:monospace;font-size:12px;color:var(--text3);">${d.comId}</td>
      <td style="font-family:monospace;font-size:12px;">${d.date} <span style="color:var(--text3)">${d.time}</span></td>
      <td><strong>${d.candidate}</strong></td>
      <td><span class="cb ${TYPE_CH[d.type]||'cb-system'}">${d.channel}</span></td>
      <td style="max-width:1px;" title="${d.subject}">${d.subject}</td>
      <td style="color:var(--text3);font-size:12.5px;">${d.sender}</td>
      <td><span class="cs cs-${d.status}">${STATUS_ICON[d.status]} ${STATUS_LABEL[d.status]}</span></td>
      <td>${lcHTML(d.lifecycle, d.status)}</td>
    </tr>`).join('');

  updateCommKPIs(rows);
  if(commActiveId) renderCommDrawer(COMM_DATA.find(d=>d.id===commActiveId));
  else document.getElementById('comm-drawer').style.display = 'none';
};

window.toggleCommDetail = function(id){
  if(commActiveId === id){
    window.closeCommDrawer();
  } else {
    commActiveId = id;
    renderCommLog();
    renderCommDrawer(COMM_DATA.find(d=>d.id===id));
  }
};

window.renderCommDrawer=function(d){
  if(!d) return;
  document.getElementById('comm-drawer-title').textContent = d.comId + ' — ' + d.candidate;

  const attHTML = d.hasAttachment ? `
    <div class="ds full">
      <div class="ds-lbl">Pièce jointe</div>
      <div class="att-row">
        <div class="att-icon">${d.attachmentType||'FIL'}</div>
        <div>
          <div style="font-weight:600;font-size:13px;">${d.attachmentName}</div>
          <div style="color:var(--text3);font-size:12px;">Téléchargé 0 fois · Scan antivirus : OK</div>
        </div>
        <button class="btn-sm" style="margin-left:auto;" onclick="alert('Téléchargement simulé')">Télécharger</button>
      </div>
    </div>` : '';

  const bounceHTML = d.bounceReason
    ? `<div class="ds-row"><span class="ds-key">Raison d'échec</span><span style="color:#f87171;">${d.bounceReason}</span></div>`
    : '';

  document.getElementById('comm-drawer-body').innerHTML = `
    <div class="ds">
      <div class="ds-lbl">Métadonnées</div>
      <div class="ds-row"><span class="ds-key">Date</span><span style="font-family:monospace;font-size:12px;">${d.date} ${d.time}</span></div>
      <div class="ds-row"><span class="ds-key">Type</span><span class="cb ${TYPE_CH[d.type]||'cb-system'}">${d.channel}</span></div>
      <div class="ds-row"><span class="ds-key">Expéditeur</span><span>${d.sender}</span></div>
      <div class="ds-row"><span class="ds-key">Statut</span><span class="cs cs-${d.status}">${STATUS_ICON[d.status]} ${STATUS_LABEL[d.status]}</span></div>
    </div>
    <div class="ds">
      <div class="ds-lbl">Livraison</div>
      <div class="ds-row"><span class="ds-key">Provider</span><span>${d.provider}</span></div>
      <div class="ds-row"><span class="ds-key">SMTP</span><span style="font-family:monospace;font-size:12px;">${d.smtp}</span></div>
      ${bounceHTML}
    </div>
    <div class="ds full">
      <div class="ds-lbl">Sujet</div>
      <div style="font-size:13.5px;font-weight:600;margin-top:4px;">${d.subject}</div>
      <div class="ds-lbl" style="margin-top:14px;">Message complet</div>
      <div class="msg-body">${d.fullMessage}</div>
    </div>
    ${attHTML}
    <div class="ds full">
      <div class="ds-lbl">Journal d'audit</div>
      ${d.auditLog.map(a=>`
        <div class="audit-step">
          <div class="audit-dot" style="background:${a.color};"></div>
          <div>
            <div style="font-weight:600;">${a.action}</div>
            <div style="color:var(--text3);font-size:12px;">${a.by} · ${a.ts}</div>
          </div>
        </div>`).join('')}
    </div>`;

  document.getElementById('comm-drawer').style.display = 'block';
}

window.updateCommKPIs=function(rows){
  const kpiRow = document.getElementById('comm-kpi-row');
  if(!kpiRow) return;
  const total   = rows.length;
  const read    = rows.filter(d=>d.status==='read').length;
  const failed  = rows.filter(d=>d.status==='failed').length;
  const attach  = rows.filter(d=>d.hasAttachment).length;
  kpiRow.innerHTML = `
    <div class="comm-kpi"><div class="comm-kpi-val">${total}</div><div class="comm-kpi-lbl">Total messages</div></div>
    <div class="comm-kpi"><div class="comm-kpi-val" style="color:#34d399;">${read}</div><div class="comm-kpi-lbl">Lus</div></div>
    <div class="comm-kpi"><div class="comm-kpi-val" style="color:#f87171;">${failed}</div><div class="comm-kpi-lbl">Échoués</div></div>
    <div class="comm-kpi"><div class="comm-kpi-val">${attach}</div><div class="comm-kpi-lbl">Avec pièces jointes</div></div>`;
}

// Auto-init when feature becomes visible.
// If you use a showFeature() function, call renderCommLog() there too.
// This handles the case where the section is already visible on load:
if(document.readyState === 'loading'){
  document.addEventListener('DOMContentLoaded', renderCommLog);
} else {
  renderCommLog();
}

})
// ====================== MULTI-ATTESTATION BUNDLE (inside Feature 3) ======================
// ====================== MULTI-ATTESTATION BUNDLE (inside Feature 3) ======================

window.selectBundleType = function(btn, type){
  document.querySelectorAll('.bundle-type-card').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
}

/* ── Live preview update when checkboxes toggle ── */
window.updateBundlePreview = function(){
  const checks = {
    tva:      document.querySelector('#check-tva input'),
    douane:   document.querySelector('#check-douane input'),
    tournage: document.querySelector('#check-tournage input'),
  };
  const previews = {
    tva:      document.getElementById('prev-tva'),
    douane:   document.getElementById('prev-douane'),
    tournage: document.getElementById('prev-tournage'),
  };

  let count = 0;
  let totalPages = 0;
  const pageCounts = { tva: 2, douane: 3, tournage: 4 };

  Object.keys(checks).forEach(key => {
    const checked = checks[key]?.checked;
    if (previews[key]) {
      previews[key].classList.toggle('dimmed', !checked);
    }
    if (checked) {
      count++;
      totalPages += pageCounts[key];
    }
  });

  const countEl = document.getElementById('preview-count');
  const pagesEl = document.getElementById('preview-pages');
  if (countEl) countEl.textContent = count + ' document' + (count !== 1 ? 's' : '');
  if (pagesEl) pagesEl.textContent = '~' + totalPages + ' pages · 1 référence';
}

/* ── Generate bundle ── */
window.generateBundle = function(){
  const prodName = document.getElementById('bundle-production')?.value || 'Production Inconnue';
  const ref = 'BUNDLE-' + Math.floor(10000 + Math.random() * 90000);
  const refEl = document.getElementById('bundle-ref');
  const resultEl = document.getElementById('bundle-result');

  if (refEl) refEl.textContent = 'Référence : ' + ref;
  if (resultEl) {
    resultEl.style.display = 'flex';
    resultEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }
  addToRecentBundles(ref, prodName);
}

/* ── Download (mock) ── */
window.downloadBundlePDF = function(){
  const ref = document.getElementById('bundle-ref')?.textContent || '';
  alert('📄 PDF Combiné prêt au téléchargement\n\n' + ref + '\n\nContenu :\n• Attestation Exonération TVA\n• Attestation Droits de Douane\n• Autorisation de Tournage');
}

/* ── Recent bundles list ── */
const recentBundles = [];

window.addToRecentBundles = function(ref, name){
  recentBundles.unshift({ ref, name, date: "Aujourd'hui" });
  if (recentBundles.length > 5) recentBundles.pop();
  renderRecentBundles();
}

window.renderRecentBundles = function(){
  const container = document.getElementById('recent-bundles');
  if (!container) return;
  container.innerHTML = recentBundles.map(b => `
    <div class="bundle-recent-item" onclick="viewBundleDetail('${b.ref}')">
      <div class="bundle-recent-dot"></div>
      <div class="bundle-recent-info">
        <div class="bundle-recent-ref">${b.ref}</div>
        <div class="bundle-recent-name">${b.name} · ${b.date}</div>
      </div>
      <button class="bundle-recent-action" onclick="event.stopPropagation();downloadByRef('${b.ref}')">PDF</button>
    </div>
  `).join('');
}

window.viewBundleDetail = function(ref){
  alert('Bundle ' + ref + '\n\nProduction : Carthage Story Production\nAttestations :\n• TVA\n• Douane\n• Autorisation de Tournage\n\nPDF disponible.');
}

window.downloadByRef = function(ref){
  alert('Téléchargement du bundle ' + ref);
}

/* ── Init ── */
window.initBundleInsideFeature3 = function(){
  const resultEl = document.getElementById('bundle-result');
  if (resultEl) resultEl.style.display = 'none';
  addToRecentBundles('BUNDLE-78421', 'Carthage Story Production');
  addToRecentBundles('BUNDLE-67309', 'Atlas Films International');
  updateBundlePreview();
}

//quota suspicous :
const alerts=[
  {id:'SPD-001',entity:'Atlas Films International',type:'TVA',severity:'critical',count:5,window:'18 jours',
   description:'5 demandes d\'exonération TVA distinctes soumises pour des productions différentes en 18 jours. Seuil habituel : 1 demande/45 jours.',
   flags:['Fréquence anormale','Montants cumulés : 4,2 M TND','3 productions distinctes'],
   history:[{date:'28/03/2026',doc:'TVA-2026-4412',montant:'840 000 TND',statut:'approved'},{date:'05/04/2026',doc:'TVA-2026-4501',montant:'1 200 000 TND',statut:'approved'},{date:'09/04/2026',doc:'TVA-2026-4598',montant:'760 000 TND',statut:'pending'},{date:'11/04/2026',doc:'TVA-2026-4621',montant:'900 000 TND',statut:'pending'},{date:'14/04/2026',doc:'TVA-2026-4705',montant:'500 000 TND',statut:'pending'}],
   registryMatch:false,registryNote:'Société présente au registre CCIT · Statut actif',resolution:'pending'},
  {id:'SPD-002',entity:'Sahara Screen Production',type:'Film étranger',severity:'critical',count:1,window:'—',
   description:'Autorisation tournage film étranger "Desert Horizons (USA)" délivrée à une société de production introuvable dans les registres CCIT, APII et la base CNSS.',
   flags:['Non-répertoriée CCIT','Non-répertoriée APII','Aucune déclaration CNSS détectée'],
   history:[{date:'02/04/2026',doc:'AUTH-2026-0892',montant:'—',statut:'approved'}],
   registryMatch:false,registryNote:'Aucune correspondance trouvée dans les 3 registres interrogés',resolution:'pending'},
  {id:'SPD-003',entity:'Médina Production',type:'Multi-tournage',severity:'critical',count:4,window:'12 jours',
   description:'4 autorisations de tournage simultanées émises pour des sites différents. Les équipes déclarées se chevauchent géographiquement.',
   flags:['Chevauchement géographique Tunis/Hammamet','92 personnes déclarées en double','Dates conflictuelles 7–18 Avr'],
   history:[{date:'03/04/2026',doc:'TURN-2026-1101',montant:'—',statut:'approved'},{date:'06/04/2026',doc:'TURN-2026-1145',montant:'—',statut:'approved'},{date:'10/04/2026',doc:'TURN-2026-1198',montant:'—',statut:'approved'},{date:'14/04/2026',doc:'TURN-2026-1254',montant:'—',statut:'pending'}],
   registryMatch:true,registryNote:'Société répertoriée · Cartes pro valides',resolution:'pending'},
  {id:'SPD-004',entity:'Ciné Express SARL',type:'Douane',severity:'critical',count:3,window:'22 jours',
   description:'3 demandes d\'exemption droits de douane sur matériel importé. Valeur déclarée totale (1,8 M TND) dépasse le plafond réglementaire annuel.',
   flags:['Plafond annuel dépassé','Matériel partiellement non-déclaré','Société créée il y a 4 mois'],
   history:[{date:'25/03/2026',doc:'DOUAN-2026-0312',montant:'520 000 TND',statut:'approved'},{date:'08/04/2026',doc:'DOUAN-2026-0398',montant:'680 000 TND',statut:'approved'},{date:'13/04/2026',doc:'DOUAN-2026-0441',montant:'600 000 TND',statut:'pending'}],
   registryMatch:true,registryNote:'Société enregistrée — créée 12/12/2025',resolution:'pending'},
  {id:'SPD-005',entity:'Cactus Prod',type:'TVA',severity:'warn',count:2,window:'30 jours',
   description:'2 demandes TVA en 30 jours. Dans la limite mais inhabituel pour cette entité (historique : 1 demande/trimestre).',
   flags:['Fréquence inhabituelle','Écart vs historique : +400%'],
   history:[{date:'15/03/2026',doc:'TVA-2026-3981',montant:'1 100 000 TND',statut:'approved'},{date:'11/04/2026',doc:'TVA-2026-4688',montant:'950 000 TND',statut:'pending'}],
   registryMatch:true,registryNote:'Société connue · Historique de 7 ans',resolution:'pending'},
  {id:'SPD-006',entity:'Nord Tunisie Films',type:'Film étranger',severity:'warn',count:1,window:'—',
   description:'Dossier film étranger "Italian Summer (IT)" — nom de la société de production italienne mentionnée n\'apparaît pas dans le registre de coproduction FICAB.',
   flags:['Société italienne non-répertoriée FICAB','Adresse siège incohérente','Coproducteur non-vérifiable'],
   history:[{date:'10/04/2026',doc:'AUTH-2026-0941',montant:'—',statut:'pending'}],
   registryMatch:false,registryNote:'Coproducteur étranger non-vérifiable',resolution:'pending'},
  {id:'SPD-007',entity:'Carthage Pictures',type:'Multi-tournage',severity:'warn',count:2,window:'8 jours',
   description:'2 autorisations simultanées sur site médina de Tunis. Chevauchement partiel de dates avec dossier de conflit existant.',
   flags:['Zone sensible médina','Conflit dates 18–21 Avr détecté'],
   history:[{date:'07/04/2026',doc:'TURN-2026-1133',montant:'—',statut:'approved'},{date:'12/04/2026',doc:'TURN-2026-1212',montant:'—',statut:'pending'}],
   registryMatch:true,registryNote:'Société répertoriée',resolution:'pending'},
  {id:'SPD-008',entity:'Djerba Studios',type:'Douane',severity:'info',count:1,window:'—',
   description:'Demande douane sur caméras RED V-Raptor — code douanier utilisé ne correspond pas au matériel déclaré.',
   flags:['Code douanier incorrect','Révision nomenclature requise'],
   history:[{date:'09/04/2026',doc:'DOUAN-2026-0422',montant:'210 000 TND',statut:'pending'}],
   registryMatch:true,registryNote:'Probablement erreur technique',resolution:'pending'},
];

let currentFilter='all';

window.renderAlerts = function(data){
  const list=document.getElementById('spd-list');
  if(!data.length){list.innerHTML='<div style="text-align:center;padding:32px;color:var(--color-text-secondary,#8b9ab8);font-size:13px;">Aucune alerte correspondante</div>';return;}
  list.innerHTML=data.map(a=>{
    const sc={critical:{c:'#ef4444',bg:'rgba(239,68,68,0.08)',bd:'rgba(239,68,68,0.2)',lbl:'Critique'},warn:{c:'#f59e0b',bg:'rgba(245,158,11,0.08)',bd:'rgba(245,158,11,0.2)',lbl:'Suspect'},info:{c:'#3b82f6',bg:'rgba(59,130,246,0.08)',bd:'rgba(59,130,246,0.2)',lbl:'En revue'}}[a.severity];
    const typeIcon={TVA:'💸',Douane:'📦','Film étranger':'🎬','Multi-tournage':'🎥'}[a.type]||'⚠️';
    const regIcon=a.registryMatch?'<span style="color:#10b981;font-size:11px;">✓ Registre OK</span>':'<span style="color:#ef4444;font-size:11px;">✕ Non-répertorié</span>';
    return `<div onclick="showDetail('${a.id}')" style="padding:13px 15px;background:${sc.bg};border:1px solid ${sc.bd};border-radius:10px;cursor:pointer;transition:all .15s;display:flex;align-items:center;gap:12px;" onmouseover="this.style.borderColor='${sc.c}'" onmouseout="this.style.borderColor='${sc.bd}'">
      <div style="width:34px;height:34px;background:${sc.c}18;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0;">${typeIcon}</div>
      <div style="flex:1;min-width:0;">
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:4px;">
          <span style="font-size:13px;font-weight:600;color:var(--color-text-primary,#f0f4ff);">${a.entity}</span>
          <span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:10px;background:${sc.c}20;color:${sc.c};letter-spacing:.4px;">${sc.lbl.toUpperCase()}</span>
          <span style="font-size:10.5px;padding:2px 8px;border-radius:10px;background:rgba(255,255,255,0.06);color:var(--color-text-secondary,#8b9ab8);">${a.type}</span>
        </div>
        <div style="font-size:11.5px;color:var(--color-text-secondary,#8b9ab8);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:480px;">${a.description}</div>
      </div>
      <div style="flex-shrink:0;text-align:right;display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
        ${regIcon}
        <span style="font-size:10px;color:var(--color-text-secondary,#8b9ab8);font-family:monospace;">${a.id}</span>
      </div>
      <div style="color:var(--color-text-secondary,#8b9ab8);font-size:16px;flex-shrink:0;">›</div>
    </div>`;
  }).join('');
}

window.filterAlerts = function(sev, btn){
  currentFilter=sev||'all';
  document.querySelectorAll('.spd-filter').forEach(b=>{b.style.background='';b.style.opacity='.7';});
  if(btn){btn.style.opacity='1';btn.style.background='rgba(255,255,255,0.12)';}
  const typeVal=document.getElementById('spd-type')?.value||'';
  let data=alerts;
  if(sev&&sev!=='all') data=data.filter(a=>a.severity===sev);
  if(typeVal) data=data.filter(a=>a.type===typeVal);
  renderAlerts(data);
  if(btn) btn.dataset.f=sev;
}

window.showDetail = function(id){
  const a=alerts.find(x=>x.id===id);
  if(!a) return;
  const sc={critical:{c:'#ef4444',lbl:'Critique',icon:'🚨'},warn:{c:'#f59e0b',lbl:'Suspect',icon:'⚠️'},info:{c:'#3b82f6',lbl:'En revue',icon:'🔍'}}[a.severity];
  const statusStyle={approved:'color:#10b981',pending:'color:#f59e0b',rejected:'color:#ef4444'};
  const statusLabel={approved:'Approuvé',pending:'En attente',rejected:'Rejeté'};
  document.getElementById('spd-detail-body').innerHTML=`
    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:16px;flex-wrap:wrap;">
      <div>
        <div style="font-size:15px;font-weight:600;color:var(--color-text-primary,#f0f4ff);margin-bottom:4px;">${a.entity}</div>
        <div style="display:flex;gap:6px;flex-wrap:wrap;">
          <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:8px;background:${sc.c}18;color:${sc.c};">${sc.icon} ${sc.lbl}</span>
          <span style="font-size:11px;padding:3px 10px;border-radius:8px;background:rgba(255,255,255,0.06);color:var(--color-text-secondary,#8b9ab8);">${a.type}</span>
          <span style="font-size:11px;padding:3px 10px;border-radius:8px;background:rgba(255,255,255,0.06);color:var(--color-text-secondary,#8b9ab8);font-family:monospace;">${a.id}</span>
        </div>
      </div>
      <div style="display:flex;gap:6px;">
        <button onclick="markReviewed('${a.id}')" style="padding:7px 14px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;border:1px solid rgba(16,185,129,0.3);background:rgba(16,185,129,0.1);color:#10b981;transition:all .15s;">✓ Marquer révisé</button>
        <button onclick="escalate('${a.id}')" style="padding:7px 14px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;border:1px solid rgba(239,68,68,0.3);background:rgba(239,68,68,0.1);color:#ef4444;transition:all .15s;">🔺 Escalader</button>
      </div>
    </div>

    <div style="padding:11px 13px;background:rgba(255,255,255,0.04);border-radius:8px;font-size:12.5px;color:var(--color-text-secondary,#8b9ab8);line-height:1.6;margin-bottom:14px;">${a.description}</div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;">
      <div style="padding:11px 13px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:8px;">
        <div style="font-size:10px;font-weight:700;color:var(--color-text-secondary,#8b9ab8);letter-spacing:.5px;margin-bottom:8px;">INDICATEURS</div>
        ${a.flags.map(f=>`<div style="display:flex;align-items:flex-start;gap:6px;font-size:12px;color:var(--color-text-primary,#f0f4ff);padding:3px 0;"><span style="color:${sc.c};margin-top:1px;flex-shrink:0;">•</span>${f}</div>`).join('')}
      </div>
      <div style="padding:11px 13px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:8px;">
        <div style="font-size:10px;font-weight:700;color:var(--color-text-secondary,#8b9ab8);letter-spacing:.5px;margin-bottom:8px;">VÉRIFICATION REGISTRE</div>
        <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px;">
          <span style="font-size:18px;">${a.registryMatch?'✅':'❌'}</span>
          <span style="font-size:12px;font-weight:600;color:${a.registryMatch?'#10b981':'#ef4444'};">${a.registryMatch?'Correspondance trouvée':'Aucune correspondance'}</span>
        </div>
        <div style="font-size:11.5px;color:var(--color-text-secondary,#8b9ab8);">${a.registryNote}</div>
      </div>
    </div>

    <div style="margin-bottom:14px;">
      <div style="font-size:10px;font-weight:700;color:var(--color-text-secondary,#8b9ab8);letter-spacing:.5px;margin-bottom:8px;">HISTORIQUE DES DEMANDES</div>
      <div style="border:1px solid rgba(255,255,255,0.07);border-radius:8px;overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;font-size:12px;">
          <thead><tr style="background:rgba(255,255,255,0.04);">
            <th style="padding:8px 12px;text-align:left;color:var(--color-text-secondary,#8b9ab8);font-weight:600;font-size:10.5px;">Date</th>
            <th style="padding:8px 12px;text-align:left;color:var(--color-text-secondary,#8b9ab8);font-weight:600;font-size:10.5px;">Référence</th>
            <th style="padding:8px 12px;text-align:left;color:var(--color-text-secondary,#8b9ab8);font-weight:600;font-size:10.5px;">Montant</th>
            <th style="padding:8px 12px;text-align:left;color:var(--color-text-secondary,#8b9ab8);font-weight:600;font-size:10.5px;">Statut</th>
          </tr></thead>
          <tbody>${a.history.map((h,i)=>`<tr style="border-top:1px solid rgba(255,255,255,0.05);${i%2?'background:rgba(255,255,255,0.02)':''}">
            <td style="padding:8px 12px;font-family:monospace;color:var(--color-text-secondary,#8b9ab8);">${h.date}</td>
            <td style="padding:8px 12px;font-family:monospace;color:var(--color-text-primary,#f0f4ff);">${h.doc}</td>
            <td style="padding:8px 12px;font-family:monospace;color:var(--color-text-primary,#f0f4ff);">${h.montant}</td>
            <td style="padding:8px 12px;font-weight:600;${statusStyle[h.statut]||''}">${statusLabel[h.statut]||h.statut}</td>
          </tr>`).join('')}</tbody>
        </table>
      </div>
    </div>

    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      <button onclick="sendNote('${a.id}')" style="flex:1;min-width:120px;padding:8px 12px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;border:1px solid rgba(255,255,255,0.12);background:rgba(255,255,255,0.06);color:var(--color-text-primary,#f0f4ff);">📧 Notifier l'entité</button>
      <button onclick="blockEntity('${a.id}')" style="flex:1;min-width:120px;padding:8px 12px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;border:1px solid rgba(239,68,68,0.3);background:rgba(239,68,68,0.07);color:#ef4444;">🚫 Bloquer (temporaire)</button>
      <button onclick="viewFullDossier('${a.id}')" style="flex:1;min-width:120px;padding:8px 12px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;border:1px solid rgba(59,130,246,0.3);background:rgba(59,130,246,0.07);color:#3b82f6;">📂 Voir dossier complet</button>
    </div>`;
  const wrap=document.getElementById('spd-detail-wrap');
  wrap.style.display='block';
  wrap.scrollIntoView({behavior:'smooth',block:'nearest'});
}

window.markReviewed = function(id){alert(`✅ Alerte ${id} marquée comme révisée.\nUn rapport de révision a été généré et transmis au superviseur.`);}
window.escalate = function(id){alert(`🔺 Alerte ${id} escaladée à la Direction.\nUne notification prioritaire a été envoyée.`);}
window.sendNote = function(id){alert(`📧 Notification envoyée à l'entité concernée.\nRéférence: ${id}`);}
window.blockEntity = function(id){alert(`⏸ Entité mise en attente temporaire.\nToutes les demandes en cours sont suspendues jusqu'à révision.`);}
window.viewFullDossier = function(id){alert(`📂 Ouverture du dossier complet pour ${id}`);}

renderAlerts(alerts);

// ====================== ARTS SCÉNIQUES — FEATURE 4 : IDLE DOSSIER DETECTOR ======================

const idleDossiers = [
  {
    id: "DOS-2026-0284",
    candidate: "Yassine Belkacem",
    role: "Metteur en scène",
    lastAction: "12 Mars 2026",
    daysIdle: 33,
    severity: "critical",
    status: "En attente de validation",
    notes: "Dossier complet reçu le 05/03. Aucun retour de la commission depuis.",
    suggestedAction: "Escalader à la présidente de commission"
  },
  {
    id: "DOS-2026-0319",
    candidate: "Amina Trabelsi",
    role: "Chorégraphe",
    lastAction: "28 Mars 2026",
    daysIdle: 17,
    severity: "warning",
    status: "Complément demandé",
    notes: "Attestation CNSS manquante. Relance envoyée le 28/03. Aucune réponse.",
    suggestedAction: "Envoyer rappel automatique"
  },
  {
    id: "DOS-2026-0297",
    candidate: "Mehdi Khelifi",
    role: "Acteur",
    lastAction: "08 Avril 2026",
    daysIdle: 6,
    severity: "mild",
    status: "En cours d'examen",
    notes: "Dossier reçu récemment. Attente de retour du rapporteur.",
    suggestedAction: "Aucune action pour le moment"
  },
  {
    id: "DOS-2026-0251",
    candidate: "Sofia Mansour",
    role: "Danseuse",
    lastAction: "02 Mars 2026",
    daysIdle: 43,
    severity: "critical",
    status: "En attente de décision",
    notes: "Profil très prometteur. Dossier bloqué sans raison apparente.",
    suggestedAction: "Réassigner à un autre rapporteur"
  }
];

window.renderIdleDossiers = function(data){
  const container = document.getElementById('idle-dossiers-list');
  if (!container) return;

  container.innerHTML = data.map(d => {
    const color = d.severity === 'critical' ? 'var(--red)' : d.severity === 'warning' ? 'var(--amber)' : 'var(--teal)';
    return `
      <div onclick="showIdleDetail('${d.id}')" style="padding:14px; background:var(--bg3); border:1px solid var(--border); border-radius:10px; margin-bottom:10px; cursor:pointer; transition:all .2s;" onmouseover="this.style.borderColor='${color}'">
        <div style="display:flex; justify-content:space-between; align-items:center;">
          <div>
            <strong>${d.candidate}</strong> — ${d.role}
            <div style="font-size:11.5px; color:var(--text3); margin-top:3px;">${d.id} • ${d.status}</div>
          </div>
          <div style="text-align:right;">
            <span style="color:${color}; font-weight:700; font-size:13px;">${d.daysIdle} jours inactif</span><br>
            <span style="font-size:10.5px; color:var(--text3);">Dernière action : ${d.lastAction}</span>
          </div>
        </div>
      </div>`;
  }).join('');
}

window.filterIdleDossiers = function(level, btn){
  document.querySelectorAll('.filter-tab').forEach(b => b.classList.remove('active'));
  if (btn) btn.classList.add('active');

  let filtered = idleDossiers;
  if (level === 'critical') filtered = idleDossiers.filter(d => d.daysIdle > 30);
  if (level === 'warning')  filtered = idleDossiers.filter(d => d.daysIdle >= 15 && d.daysIdle <= 30);
  if (level === 'mild')     filtered = idleDossiers.filter(d => d.daysIdle >= 8 && d.daysIdle < 15);

  renderIdleDossiers(filtered);
  document.getElementById('idle-count').textContent = `${filtered.length} dossier${filtered.length > 1 ? 's' : ''}`;
}

window.showIdleDetail = function(id){
  const dossier = idleDossiers.find(d => d.id === id);
  if (!dossier) return;

  const color = dossier.severity === 'critical' ? 'var(--red)' : dossier.severity === 'warning' ? 'var(--amber)' : 'var(--teal)';

  document.getElementById('idle-detail-body').innerHTML = `
    <div style="margin-bottom:20px;">
      <div style="font-size:16px; font-weight:600;">${dossier.candidate} — ${dossier.role}</div>
      <div style="font-family:var(--font-mono); color:var(--text3);">${dossier.id}</div>
    </div>

    <div style="padding:14px; background:rgba(255,255,255,0.05); border-radius:10px; margin-bottom:16px;">
      <strong>Inactif depuis ${dossier.daysIdle} jours</strong><br>
      <span style="color:var(--text3);">Dernière action : ${dossier.lastAction}</span>
    </div>

    <div style="margin-bottom:16px;">
      <div style="font-size:12px; color:var(--text3); margin-bottom:6px;">STATUT ACTUEL</div>
      <div style="padding:10px 14px; background:var(--bg4); border-radius:8px; font-size:13px;">${dossier.status}</div>
    </div>

    <div style="margin-bottom:20px;">
      <div style="font-size:12px; color:var(--text3); margin-bottom:6px;">NOTES</div>
      <div style="padding:12px; background:var(--bg4); border-radius:8px; font-size:13px; line-height:1.5;">${dossier.notes}</div>
    </div>

    <div style="font-size:12px; color:var(--text3); margin-bottom:8px;">ACTION RECOMMANDÉE</div>
    <div style="padding:14px; background:rgba(251,191,36,0.1); border:1px solid rgba(251,191,36,0.3); border-radius:10px; color:var(--amber); font-weight:600;">
      ${dossier.suggestedAction}
    </div>

    <div style="margin-top:24px; display:flex; gap:10px;">
      <button onclick="escalateIdleDossier('${dossier.id}')" class="btn btn-red" style="flex:1;">🔺 Escalader</button>
      <button onclick="reassignIdleDossier('${dossier.id}')" class="btn btn-outline" style="flex:1;">🔄 Réassigner</button>
      <button onclick="sendReminderIdle('${dossier.id}')" class="btn btn-gold" style="flex:1;">📧 Envoyer rappel</button>
    </div>
  `;

  document.getElementById('idle-detail-panel').style.display = 'block';
}

window.closeIdleDetail = function(){
  document.getElementById('idle-detail-panel').style.display = 'none';
}

window.escalateIdleDossier = function(id){
  alert(`✅ Dossier ${id} escaladé à la Direction des Arts Scéniques.`);
}

window.reassignIdleDossier = function(id){
  alert(`🔄 Dossier ${id} réassigné à un nouveau rapporteur.`);
}

window.sendReminderIdle = function(id) {
  alert(`📧 Rappel automatique envoyé au candidat pour le dossier ${id}.`);
}

// Init function
window.initIdleDossierDetector = function() {
  renderIdleDossiers(idleDossiers);
  document.getElementById('idle-count').textContent = `${idleDossiers.length} dossiers`;
}

// ============================================================
    // FEATURE 6 — SCRIPT audio visuel audiovisuel
// ============================================================

(function(){

/* ── DATA ── */
const CMP_DATA = [
  {
    id:'CMP-2026-0041', ref:'DOS-2024-0847', status:'pending',
    userName:'Amira Khlifi', userEmail:'a.khlifi@carthageprod.tn',
    userOrg:'Carthage Story Production', userAvatar:'AK',
    type:'Autorisation de tournage', submittedAt:'12/04/2026 09:17', urgency:'high',
    location:'Médina de Tunis · Sidi Bou Said', period:'18–28 Avril 2026',
    crewSize:42, budget:'320 000 TND',
    description:'Production d\'un long-métrage franco-tunisien traitant de la mémoire collective dans la vieille ville. Scènes de rue, intérieurs de maisons traditionnelles, et séquences nocturnes autour de la mosquée Zitouna.',
    docs:[
      {name:'Scenario_complet_v3.pdf',    type:'PDF', pages:112, matchScore:94, status:'ok',    ref:'DOC-REF-2024-A'},
      {name:'Budget_previsionnel.xlsx',   type:'XLS', pages:8,   matchScore:88, status:'ok',    ref:'DOC-REF-2024-B'},
      {name:'Autorisation_precedente.pdf',type:'PDF', pages:4,   matchScore:72, status:'warn',  ref:'DOC-REF-2023-C'},
      {name:'Assurance_tournage.pdf',     type:'PDF', pages:2,   matchScore:99, status:'ok',    ref:'DOC-REF-2024-D'},
    ],
    history:[
      {action:'Demande soumise',          by:'Amira Khlifi',       ts:'12/04/2026 09:17', color:'#60a5fa'},
      {action:'Affectée à l\'agent',      by:'Système',            ts:'12/04/2026 09:20', color:'#9ca3af'},
      {action:'Documents vérifiés (auto)',by:'Système IA',         ts:'12/04/2026 09:21', color:'#a78bfa'},
    ],
    diff:{
      old:['Durée : 25 jours','Équipe : 38 personnes','Budget : 290 000 TND','Lieu : Médina uniquement'],
      new:['Durée : 30 jours','Équipe : 42 personnes','Budget : 320 000 TND','Lieu : Médina + Sidi Bou Said'],
    },
    riskScore:62, riskLabel:'Moyen'
  },
  {
    id:'CMP-2026-0040', ref:'DOS-2024-0846', status:'review',
    userName:'Karim Ben Romdhane', userEmail:'k.benromdhane@medinaprod.tn',
    userOrg:'Médina Production', userAvatar:'KB',
    type:'Exonération TVA', submittedAt:'11/04/2026 14:30', urgency:'medium',
    location:'Hammamet · Nabeul', period:'20 Avr – 5 Mai 2026',
    crewSize:18, budget:'95 000 TND',
    description:'Demande d\'exonération TVA pour l\'importation de matériel de prise de vue (caméras RED V-Raptor, grues robotisées, éclairage LED) dans le cadre d\'une série documentaire sur l\'artisanat tunisien.',
    docs:[
      {name:'Facture_proforma_RED.pdf',   type:'PDF', pages:6,   matchScore:97, status:'ok',    ref:'DOC-REF-2024-E'},
      {name:'Liste_equipements.pdf',      type:'PDF', pages:14,  matchScore:91, status:'ok',    ref:'DOC-REF-2024-F'},
      {name:'Extrait_registre.pdf',       type:'PDF', pages:1,   matchScore:55, status:'warn',  ref:'DOC-REF-2024-G'},
    ],
    history:[
      {action:'Demande soumise',          by:'Karim Ben Romdhane', ts:'11/04/2026 14:30', color:'#60a5fa'},
      {action:'Affectée — Agent Trabelsi',by:'Système',            ts:'11/04/2026 14:35', color:'#9ca3af'},
      {action:'Mise en révision',         by:'Agent Leila Trabelsi',ts:'12/04/2026 10:12',color:'#fbbf24'},
      {action:'Note interne ajoutée',     by:'Agent Leila Trabelsi',ts:'12/04/2026 10:15',color:'#fbbf24'},
    ],
    diff:{
      old:['Matériel : 4 caméras','Valeur : 78 000 TND','Durée importation : 30 jours'],
      new:['Matériel : 6 caméras + grues','Valeur : 95 000 TND','Durée importation : 45 jours'],
    },
    riskScore:28, riskLabel:'Conforme'
  },
  {
    id:'CMP-2026-0039', ref:'DOS-2024-0845', status:'approved',
    userName:'Sofia Mansouri', userEmail:'sofia.m@atlasdocs.tn',
    userOrg:'Atlas Documentaires', userAvatar:'SM',
    type:'Carte professionnelle', submittedAt:'09/04/2026 11:00', urgency:'low',
    location:'Tataouine · Ksar Ouled Soltane', period:'1–15 Mai 2026',
    crewSize:8, budget:'45 000 TND',
    description:'Demande de carte professionnelle de réalisatrice documentaire. Expérience de 7 ans, 3 documentaires diffusés sur TV5 Monde. Dossier complet avec toutes pièces justificatives.',
    docs:[
      {name:'CV_Sofia_Mansouri.pdf',      type:'PDF', pages:3,   matchScore:100, status:'ok',  ref:'DOC-REF-2024-H'},
      {name:'Diplome_ISAMM.pdf',          type:'PDF', pages:2,   matchScore:99,  status:'ok',  ref:'DOC-REF-2024-I'},
      {name:'Filmographie_certifiee.pdf', type:'PDF', pages:5,   matchScore:98,  status:'ok',  ref:'DOC-REF-2024-J'},
      {name:'Photo_identite.jpg',         type:'IMG', pages:1,   matchScore:100, status:'ok',  ref:'DOC-REF-2024-K'},
    ],
    history:[
      {action:'Demande soumise',          by:'Sofia Mansouri',    ts:'09/04/2026 11:00', color:'#60a5fa'},
      {action:'Vérification auto OK',     by:'Système IA',        ts:'09/04/2026 11:02', color:'#a78bfa'},
      {action:'Affectée — Agent Boussi',  by:'Système',           ts:'09/04/2026 11:05', color:'#9ca3af'},
      {action:'Dossier validé',           by:'Agent Amel Boussi', ts:'10/04/2026 09:30', color:'#34d399'},
      {action:'Approuvée — Carte émise',  by:'Directrice Rym Ben Salah',ts:'10/04/2026 14:00',color:'#34d399'},
    ],
    diff:{old:['—'],new:['Nouveau dossier · Aucun historique précédent']},
    riskScore:8, riskLabel:'Conforme'
  },
  {
    id:'CMP-2026-0038', ref:'DOS-2024-0844', status:'rejected',
    userName:'Youssef Gafsi', userEmail:'y.gafsi@saharascreen.tn',
    userOrg:'Sahara Screen', userAvatar:'YG',
    type:'Renouvellement', submittedAt:'08/04/2026 08:45', urgency:'high',
    location:'Douz · Grand Erg Oriental', period:'12–30 Avril 2026',
    crewSize:55, budget:'780 000 TND',
    description:'Renouvellement d\'autorisation de tournage pour une production américaine. Demande de prolongation de 30 jours supplémentaires suite à des retards météorologiques. Documents fournis incomplets.',
    docs:[
      {name:'Contrat_production_US.pdf',  type:'PDF', pages:22,  matchScore:61, status:'warn',  ref:'DOC-REF-2024-L'},
      {name:'Assurance_manquante.pdf',    type:'PDF', pages:0,   matchScore:0,  status:'miss',  ref:'—'},
      {name:'Passeports_equipe.pdf',      type:'PDF', pages:18,  matchScore:77, status:'warn',  ref:'DOC-REF-2024-M'},
    ],
    history:[
      {action:'Demande soumise',          by:'Youssef Gafsi',     ts:'08/04/2026 08:45', color:'#60a5fa'},
      {action:'Docs incomplets détectés', by:'Système IA',        ts:'08/04/2026 08:47', color:'#f87171'},
      {action:'Notification envoyée',     by:'Système',           ts:'08/04/2026 08:50', color:'#9ca3af'},
      {action:'Rejetée — Dossier incomplet',by:'Agent Leila Trabelsi',ts:'09/04/2026 11:20',color:'#f87171'},
    ],
    diff:{
      old:['Durée initiale : 45 jours','Équipe : 42 personnes','Statut : Approuvée'],
      new:['Prolongation demandée : +30 j','Équipe : 55 personnes','Assurance manquante ❌'],
    },
    riskScore:81, riskLabel:'Élevé'
  },
  {
    id:'CMP-2026-0037', ref:'DOS-2024-0843', status:'pending',
    userName:'Nour Belhadj', userEmail:'nour.b@cinemaginaire.tn',
    userOrg:'Cinémaginaire SARL', userAvatar:'NB',
    type:'Autorisation de tournage', submittedAt:'10/04/2026 16:22', urgency:'medium',
    location:'Djerba · Houmt Souk', period:'2–18 Mai 2026',
    crewSize:24, budget:'155 000 TND',
    description:'Série documentaire sur la communauté juive de Djerba. Tournage dans la synagogue La Ghriba, le marché central et les ateliers de poterie. Co-production franco-tunisienne.',
    docs:[
      {name:'Autorisation_religieuse.pdf',type:'PDF', pages:3,  matchScore:95, status:'ok',   ref:'DOC-REF-2024-N'},
      {name:'Contrat_coproduction.pdf',   type:'PDF', pages:11, matchScore:89, status:'ok',   ref:'DOC-REF-2024-O'},
      {name:'Plan_tournage.pdf',          type:'PDF', pages:8,  matchScore:82, status:'ok',   ref:'DOC-REF-2024-P'},
    ],
    history:[
      {action:'Demande soumise',          by:'Nour Belhadj',      ts:'10/04/2026 16:22', color:'#60a5fa'},
      {action:'Vérification auto OK',     by:'Système IA',        ts:'10/04/2026 16:24', color:'#a78bfa'},
      {action:'En attente d\'affectation',by:'Système',           ts:'10/04/2026 16:25', color:'#9ca3af'},
    ],
    diff:{old:['Premier dépôt'],new:['Dossier initial · Pas de version précédente']},
    riskScore:21, riskLabel:'Conforme'
  },
  {
    id:'CMP-2026-0036', ref:'DOS-2024-0842', status:'review',
    userName:'Leila Jbeli', userEmail:'l.jbeli@desertfilm.tn',
    userOrg:'Desert Film Productions', userAvatar:'LJ',
    type:'Exonération TVA', submittedAt:'07/04/2026 13:10', urgency:'low',
    location:'Tozeur · Chott El Djerid', period:'15 Mai – 3 Juin 2026',
    crewSize:31, budget:'210 000 TND',
    description:'Demande d\'exonération de droits de douane pour l\'importation de drones professionnels (DJI Inspire 3, Matrice 350) et d\'équipements de captation 8K. Film de fiction en co-production avec une société allemande.',
    docs:[
      {name:'Facture_DJI_officielle.pdf', type:'PDF', pages:4,  matchScore:96, status:'ok',   ref:'DOC-REF-2024-Q'},
      {name:'Accord_coproduction_DE.pdf', type:'PDF', pages:9,  matchScore:88, status:'ok',   ref:'DOC-REF-2024-R'},
      {name:'Autorisation_CAA.pdf',       type:'PDF', pages:2,  matchScore:65, status:'warn', ref:'DOC-REF-2024-S'},
    ],
    history:[
      {action:'Demande soumise',          by:'Leila Jbeli',       ts:'07/04/2026 13:10', color:'#60a5fa'},
      {action:'Docs vérifiés auto',       by:'Système IA',        ts:'07/04/2026 13:12', color:'#a78bfa'},
      {action:'Mise en révision — CAA',   by:'Agent Amel Boussi', ts:'08/04/2026 09:00', color:'#fbbf24'},
    ],
    diff:{
      old:['2 drones déclarés','Valeur : 145 000 TND'],
      new:['4 drones + captation 8K','Valeur : 210 000 TND'],
    },
    riskScore:44, riskLabel:'Moyen'
  },
];

const STATUS_CFG = {
  pending:  {label:'En attente',  cls:'sb-pending',  dot:'#f59e0b', accentColor:'#f59e0b'},
  review:   {label:'En révision', cls:'sb-review',   dot:'#60a5fa', accentColor:'#3b82f6'},
  approved: {label:'Approuvée',   cls:'sb-approved', dot:'#34d399', accentColor:'#10b981'},
  rejected: {label:'Rejetée',     cls:'sb-rejected', dot:'#f87171', accentColor:'#ef4444'},
};
const URGENCY_CFG = {
  high:   {label:'Urgent', color:'#f87171'},
  medium: {label:'Normal', color:'#f59e0b'},
  low:    {label:'Basse',  color:'#9ca3af'},
};
const DOC_STATUS = {
  ok:   {icon:'✓', color:'#34d399', label:'Conforme'},
  warn: {icon:'⚠', color:'#f59e0b', label:'À vérifier'},
  miss: {icon:'✕', color:'#f87171', label:'Manquant'},
};

let cmpTab     = 'all';
let cmpView    = 'grid';
let cmpActive  = null;
let cmpDtab    = 'info';
let cmpAction  = null; // 'approve' | 'reject'

window.setCmpTab = function(el, val){
  document.querySelectorAll('#audio-feature-6 .cmp-tab').forEach(t=>t.classList.remove('active'));
  el.classList.add('active');
  cmpTab = val; cmpActive = null;
  document.getElementById('cmp-detail-panel').style.display = 'none';
  document.getElementById('cmp-confirm-strip').style.display = 'none';
  renderCmp();
};

window.setCmpView = function(v){
  cmpView = v;
  const gBtn = document.getElementById('cmp-view-grid');
  const lBtn = document.getElementById('cmp-view-list');
  if(gBtn){ gBtn.style.background = v==='grid'?'var(--gold)':'var(--bg3)'; gBtn.style.color = v==='grid'?'#1a1400':'var(--text2)'; }
  if(lBtn){ lBtn.style.background = v==='list'?'var(--gold)':'var(--bg3)'; lBtn.style.color = v==='list'?'#1a1400':'var(--text2)'; }
  renderCmp();
};

window.setCmpDtab = function(el, tab){
  document.querySelectorAll('#audio-feature-6 .cmp-dtab').forEach(t=>t.classList.remove('active'));
  el.classList.add('active');
  cmpDtab = tab;
  ['info','docs','hist'].forEach(t=>{
    const el2 = document.getElementById('cmp-dtab-'+t);
    if(el2) el2.style.display = t===tab?'block':'none';
  });
};

window.getFiltered=function(){
  const s  = (document.getElementById('cmp-search')||{value:''}).value.toLowerCase().trim();
  const tf = (document.getElementById('cmp-type-filter')||{value:''}).value;
  return CMP_DATA.filter(d=>{
    if(cmpTab !== 'all' && d.status !== cmpTab) return false;
    if(tf && d.type !== tf) return false;
    if(s && !d.userName.toLowerCase().includes(s) &&
           !d.userOrg.toLowerCase().includes(s) &&
           !d.id.toLowerCase().includes(s) &&
           !d.type.toLowerCase().includes(s)) return false;
    return true;
  });
}

window.renderCmp = function(){
  const rows = getFiltered();
  const grid = document.getElementById('cmp-card-grid');
  const list = document.getElementById('cmp-list-view');
  const empty= document.getElementById('cmp-empty');
  const count= document.getElementById('cmp-results-count');

  count.textContent = rows.length + ' demande' + (rows.length!==1?'s':'') + ' trouvée' + (rows.length!==1?'s':'');

  if(!rows.length){
    grid.style.display = 'none'; list.style.display='none'; empty.style.display='block';
    updateCmpKPIs(rows); return;
  }
  empty.style.display = 'none';

  if(cmpView === 'grid'){
    list.style.display = 'none';
    grid.style.display = 'grid';
    grid.innerHTML = rows.map(d => buildCard(d)).join('');
  } else {
    grid.style.display = 'none';
    list.style.display = 'flex';
    list.innerHTML = buildListHeader() + rows.map(d=>buildListRow(d)).join('');
  }
  updateCmpKPIs(CMP_DATA);
};

window.buildCard=function(d){
  const sc = STATUS_CFG[d.status];
  const uc = URGENCY_CFG[d.urgency]||URGENCY_CFG.medium;
  const rColor = d.riskScore>=70?'#f87171':d.riskScore>=40?'#f59e0b':'#34d399';
  return `
  <div class="cmp-card ${cmpActive===d.id?'cmp-selected':''}" onclick="selectCmp('${d.id}')">
    <div class="cmp-card-accent" style="background:${sc.accentColor};"></div>
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;">
      <div class="cmp-card-ref">${d.id}</div>
      ${d.urgency==='high'?`<span style="font-size:10px;font-weight:700;color:${uc.color};background:${uc.color}18;padding:2px 7px;border-radius:6px;">⚡ URGENT</span>`:''}
    </div>
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px;">
      <div style="width:34px;height:34px;border-radius:50%;background:rgba(201,168,76,.18);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:var(--gold);flex-shrink:0;">${d.userAvatar}</div>
      <div style="min-width:0;">
        <div class="cmp-card-name">${d.userName}</div>
        <div class="cmp-card-type">${d.userOrg}</div>
      </div>
    </div>
    <div style="margin:10px 0 8px;">
      <span class="tb">${d.type}</span>
    </div>
    <div style="font-size:11.5px;color:var(--text3);margin-bottom:10px;">
      📍 ${d.location}<br>
      📅 ${d.period} · 👥 ${d.crewSize} pers.
    </div>
    <div style="display:flex;align-items:center;gap:6px;margin-bottom:10px;">
      <div style="flex:1;height:4px;background:var(--bg4);border-radius:2px;overflow:hidden;">
        <div style="width:${d.riskScore}%;height:100%;background:${rColor};border-radius:2px;"></div>
      </div>
      <span style="font-size:10.5px;color:${rColor};font-weight:700;min-width:50px;text-align:right;">Risk ${d.riskScore}%</span>
    </div>
    <div class="cmp-card-footer">
      <span class="sb ${sc.cls}">${sc.label}</span>
      <span style="font-size:11px;color:var(--text3);">${d.submittedAt.split(' ')[0]}</span>
    </div>
  </div>`;
}

window=buildListHeader=function(){
  return `<div style="display:grid;grid-template-columns:110px 1fr 140px 120px 110px 90px;gap:10px;padding:7px 14px;font-size:11px;font-weight:700;color:var(--text3);letter-spacing:.04em;">
    <span>RÉFÉRENCE</span><span>DEMANDEUR</span><span>TYPE</span><span>LIEU</span><span>STATUT</span><span>DATE</span>
  </div>`;
}

window.buildListRow=function(d){
  const sc = STATUS_CFG[d.status];
  return `
  <div class="cmp-list-row ${cmpActive===d.id?'cmp-selected':''}" onclick="selectCmp('${d.id}')">
    <span style="font-family:var(--font-mono);font-size:11px;color:var(--text3);">${d.id}</span>
    <div>
      <div style="font-weight:600;font-size:13px;">${d.userName}</div>
      <div style="font-size:11px;color:var(--text3);">${d.userOrg}</div>
    </div>
    <span class="tb" style="font-size:11px;">${d.type}</span>
    <span style="font-size:12px;color:var(--text2);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${d.location.split('·')[0].trim()}</span>
    <span class="sb ${sc.cls}">${sc.label}</span>
    <span style="font-size:11.5px;color:var(--text3);">${d.submittedAt.split(' ')[0]}</span>
  </div>`;
}

window.selectCmp = function(id){
  if(cmpActive === id){ cmpActive=null; closeCmpDetail(); renderCmp(); return; }
  cmpActive = id;
  cmpAction = null;
  document.getElementById('cmp-confirm-strip').style.display = 'none';
  renderCmp();
  buildCmpDetail(CMP_DATA.find(d=>d.id===id));
  // reset to info tab
  setCmpDtab(document.getElementById('cdt-info'), 'info');
};

window.closeCmpDetail=function(){
  document.getElementById('cmp-detail-panel').style.display = 'none';
  document.getElementById('cmp-confirm-strip').style.display = 'none';
  cmpActive = null; cmpAction = null;
}

window.buildCmpDetail=function(d){
  const sc = STATUS_CFG[d.status];
  const panel = document.getElementById('cmp-detail-panel');
  panel.style.display = 'block';

  // Header
  document.getElementById('cmp-detail-hdr').innerHTML = `
    <div>
      <div style="font-size:10.5px;font-family:var(--font-mono);color:var(--text3);margin-bottom:4px;">${d.id} · ${d.ref}</div>
      <div style="font-size:14px;font-weight:700;color:var(--text);">${d.userName}</div>
      <div style="font-size:11.5px;color:var(--text3);">${d.userOrg}</div>
    </div>
    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;">
      <span class="sb ${sc.cls}">${sc.label}</span>
      <button onclick="closeCmpDetail()" style="background:none;border:none;font-size:17px;color:var(--text3);cursor:pointer;line-height:1;">✕</button>
    </div>`;

  // Info tab
  const rColor = d.riskScore>=70?'#f87171':d.riskScore>=40?'#f59e0b':'#34d399';
  document.getElementById('cmp-dtab-info').innerHTML = `
    <div class="info-row"><span class="info-key">Type de demande</span><span class="tb" style="font-size:11px;">${d.type}</span></div>
    <div class="info-row"><span class="info-key">Soumis le</span><span style="font-size:12.5px;">${d.submittedAt}</span></div>
    <div class="info-row"><span class="info-key">Lieu</span><span style="font-size:12.5px;text-align:right;max-width:220px;">${d.location}</span></div>
    <div class="info-row"><span class="info-key">Période</span><span style="font-size:12.5px;">${d.period}</span></div>
    <div class="info-row"><span class="info-key">Équipe</span><span style="font-size:12.5px;">${d.crewSize} personnes</span></div>
    <div class="info-row"><span class="info-key">Budget</span><span style="font-size:12.5px;font-weight:600;">${d.budget}</span></div>
    <div class="info-row"><span class="info-key">Email</span><span style="font-size:11.5px;color:var(--text3);">${d.userEmail}</span></div>
    <div style="margin-top:12px;">
      <div style="font-size:11px;color:var(--text3);margin-bottom:5px;">DESCRIPTION</div>
      <div style="font-size:12.5px;line-height:1.65;color:var(--text2);background:var(--bg3);padding:10px 12px;border-radius:8px;border:1px solid var(--border);">${d.description}</div>
    </div>
    <div style="margin-top:14px;">
      <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
        <span style="font-size:11px;color:var(--text3);">SCORE DE RISQUE</span>
        <span style="font-size:12px;font-weight:700;color:${rColor};">${d.riskLabel} (${d.riskScore}/100)</span>
      </div>
      <div style="height:6px;background:var(--bg4);border-radius:3px;overflow:hidden;">
        <div style="width:${d.riskScore}%;height:100%;background:${rColor};border-radius:3px;transition:width .6s ease;"></div>
      </div>
    </div>
    <div style="margin-top:16px;">
      <div style="font-size:11px;color:var(--text3);margin-bottom:8px;">COMPARAISON — MODIFICATIONS DÉTECTÉES</div>
      <div class="diff-view">
        <div>
          <div style="font-size:10px;color:#f87171;margin-bottom:4px;font-weight:600;">VERSION PRÉCÉDENTE</div>
          <div class="diff-col">${d.diff.old.map(l=>`<span class="diff-rem">– ${l}</span>`).join('')}</div>
        </div>
        <div>
          <div style="font-size:10px;color:#34d399;margin-bottom:4px;font-weight:600;">VERSION ACTUELLE</div>
          <div class="diff-col">${d.diff.new.map(l=>`<span class="diff-add">+ ${l}</span>`).join('')}</div>
        </div>
      </div>
    </div>`;

  // Docs tab
  document.getElementById('cmp-dtab-docs').innerHTML = `
    <div style="font-size:11px;color:var(--text3);margin-bottom:12px;">${d.docs.length} document(s) soumis · Analyse automatique effectuée</div>
    ${d.docs.map(doc=>{
      const ds = DOC_STATUS[doc.status]||DOC_STATUS.warn;
      const matchColor = doc.matchScore>=90?'#34d399':doc.matchScore>=70?'#f59e0b':'#f87171';
      return `
      <div class="cmp-doc-item">
        <div style="width:32px;height:32px;border-radius:6px;background:rgba(201,168,76,.12);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:var(--gold);flex-shrink:0;">
          ${doc.type}
        </div>
        <div style="flex:1;min-width:0;">
          <div style="font-size:12.5px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${doc.name}</div>
          <div style="font-size:10.5px;color:var(--text3);">Réf. ${doc.ref} · ${doc.pages} page${doc.pages!==1?'s':''}</div>
          <div class="cmp-doc-match">
            <span style="font-size:10px;color:var(--text3);min-width:60px;">Conformité</span>
            <div class="cmp-match-bar"><div class="cmp-match-fill" style="width:${doc.matchScore}%;background:${matchColor};"></div></div>
            <span style="font-size:10.5px;font-weight:700;color:${matchColor};min-width:32px;text-align:right;">${doc.matchScore}%</span>
          </div>
        </div>
        <span style="font-size:12px;font-weight:700;color:${ds.color};" title="${ds.label}">${ds.icon}</span>
      </div>`;
    }).join('')}`;

  // History tab
  document.getElementById('cmp-dtab-hist').innerHTML = d.history.map(h=>`
    <div class="cmp-hist-item">
      <div class="cmp-hist-dot" style="background:${h.color};"></div>
      <div>
        <div style="font-size:12.5px;font-weight:600;">${h.action}</div>
        <div style="font-size:11px;color:var(--text3);">${h.by} · ${h.ts}</div>
      </div>
    </div>`).join('');

  // Actions
  const actEl = document.getElementById('cmp-detail-actions');
  if(d.status === 'approved'){
    actEl.innerHTML = `<div style="font-size:12.5px;color:#34d399;font-weight:600;text-align:center;width:100%;">✓ Cette demande a été approuvée</div>`;
  } else if(d.status === 'rejected'){
    actEl.innerHTML = `<div style="font-size:12.5px;color:#f87171;font-weight:600;text-align:center;width:100%;">✕ Cette demande a été rejetée</div>`;
  } else {
    actEl.innerHTML = `
      <button class="btn-review" onclick="setCmpAction('${d.id}','review')">📋 Mettre en révision</button>
      <button class="btn-reject" onclick="setCmpAction('${d.id}','reject')">✕ Rejeter</button>
      <button class="btn-approve" onclick="setCmpAction('${d.id}','approve')">✓ Approuver</button>`;
  }

  // scroll detail into view on mobile
  panel.scrollIntoView({behavior:'smooth', block:'nearest'});
}

window.setCmpAction = function(id, action){
  cmpAction = action;
  const d = CMP_DATA.find(x=>x.id===id);
  const strip = document.getElementById('cmp-confirm-strip');
  strip.style.display = 'block';

  const isApprove = action === 'approve';
  const isReview  = action === 'review';

  const cfg = isApprove
    ? {title:'Confirmer l\'approbation', color:'#34d399', bg:'rgba(16,185,129,.1)', border:'rgba(16,185,129,.3)', icon:'✓', btn:'Confirmer l\'approbation', placeholder:'Note d\'approbation (optionnelle)…'}
    : isReview
    ? {title:'Mettre en révision', color:'#60a5fa', bg:'rgba(59,130,246,.08)', border:'rgba(59,130,246,.3)', icon:'📋', btn:'Confirmer la mise en révision', placeholder:'Raison de la mise en révision…'}
    : {title:'Confirmer le rejet', color:'#f87171', bg:'rgba(239,68,68,.1)', border:'rgba(239,68,68,.3)', icon:'✕', btn:'Confirmer le rejet', placeholder:'Motif du rejet (obligatoire)…'};

  strip.innerHTML = `
    <div style="background:${cfg.bg};border:1px solid ${cfg.border};border-radius:10px;padding:16px 18px;">
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
        <div style="width:32px;height:32px;border-radius:8px;background:${cfg.bg};display:flex;align-items:center;justify-content:center;font-size:16px;color:${cfg.color};border:1px solid ${cfg.border};">${cfg.icon}</div>
        <div>
          <div style="font-size:13.5px;font-weight:700;color:${cfg.color};">${cfg.title}</div>
          <div style="font-size:12px;color:var(--text3);">${d.id} — ${d.userName} · ${d.type}</div>
        </div>
        <button onclick="document.getElementById('cmp-confirm-strip').style.display='none'"
          style="margin-left:auto;background:none;border:none;font-size:18px;color:var(--text3);cursor:pointer;">✕</button>
      </div>
      <textarea class="cmp-confirm-input" id="cmp-confirm-note" placeholder="${cfg.placeholder}"></textarea>
      <div style="display:flex;gap:10px;margin-top:12px;">
        <button onclick="document.getElementById('cmp-confirm-strip').style.display='none'"
          style="flex:1;padding:9px 0;border:1px solid var(--border);border-radius:8px;background:transparent;color:var(--text2);font-size:13px;cursor:pointer;">Annuler</button>
        <button onclick="confirmCmpAction('${id}','${action}')"
          style="flex:2;padding:9px 0;border:none;border-radius:8px;background:${cfg.color};color:#0f1117;font-weight:700;font-size:13px;cursor:pointer;">${cfg.btn}</button>
      </div>
    </div>`;

  strip.scrollIntoView({behavior:'smooth', block:'nearest'});
};

window.confirmCmpAction = function(id, action){
  const d = CMP_DATA.find(x=>x.id===id);
  if(!d) return;
  const newStatus = action==='approve'?'approved':action==='reject'?'rejected':'review';
  d.status = newStatus;

  const note = (document.getElementById('cmp-confirm-note')||{value:''}).value || '—';
  const byUser = 'Admin Directeur';
  const now = new Date().toLocaleDateString('fr-FR')+' '+new Date().toLocaleTimeString('fr-FR',{hour:'2-digit',minute:'2-digit'});
  const actionLabel = action==='approve'?'Approuvée':action==='reject'?'Rejetée':'Mise en révision';
  d.history.push({action:`${actionLabel} — "${note.slice(0,40)}${note.length>40?'…':''}"`, by:byUser, ts:now, color: newStatus==='approved'?'#34d399':newStatus==='rejected'?'#f87171':'#60a5fa'});

  document.getElementById('cmp-confirm-strip').style.display = 'none';
  renderCmp();
  buildCmpDetail(d);
  // update KPIs
  updateCmpKPIs(CMP_DATA);
};

window.updateCmpKPIs=function(data){
  const total    = CMP_DATA.length;
  const pending  = CMP_DATA.filter(d=>d.status==='pending').length;
  const review   = CMP_DATA.filter(d=>d.status==='review').length;
  const approved = CMP_DATA.filter(d=>d.status==='approved').length;
  const rejected = CMP_DATA.filter(d=>d.status==='rejected').length;
  const strip = document.getElementById('cmp-kpi-strip');
  if(!strip) return;
  strip.innerHTML = `
    <div class="cmp-kpi"><div class="cmp-kpi-val">${total}</div><div class="cmp-kpi-lbl">Total demandes</div></div>
    <div class="cmp-kpi"><div class="cmp-kpi-val" style="color:#f59e0b;">${pending}</div><div class="cmp-kpi-lbl">En attente</div></div>
    <div class="cmp-kpi"><div class="cmp-kpi-val" style="color:#60a5fa;">${review}</div><div class="cmp-kpi-lbl">En révision</div></div>
    <div class="cmp-kpi"><div class="cmp-kpi-val" style="color:#34d399;">${approved}</div><div class="cmp-kpi-lbl">Approuvées</div></div>
    <div class="cmp-kpi"><div class="cmp-kpi-val" style="color:#f87171;">${rejected}</div><div class="cmp-kpi-lbl">Rejetées</div></div>`;
}

/* ── Init ── */
window.initComparateur = function(){
  cmpActive = null; cmpAction = null; cmpTab = 'all'; cmpView = 'grid';
  document.getElementById('cmp-confirm-strip').style.display = 'none';
  document.getElementById('cmp-detail-panel').style.display = 'none';
  renderCmp();
};

// Auto-init if already visible
if(document.readyState==='loading'){
  document.addEventListener('DOMContentLoaded', window.initComparateur);
} else {
  window.initComparateur();
}

})
// ====================== CLEAN INIT FOR AUDIO-VISUEL ======================
window.initArtsSceniques = function() {
    console.log('🎬 Initializing Arts Scéniques / Audio-Visuel module...');

    // Initialize all features safely
    if (typeof initArtsFeature1 === 'function') initArtsFeature1();
    if (typeof initArtsFeature2 === 'function') initArtsFeature2();

    // Feature 3: Calendar + Communication Log + Bundle
    if (typeof buildFullCalendar === 'function') buildFullCalendar();
    if (typeof initBundleInsideFeature3 === 'function') initBundleInsideFeature3();
    if (typeof renderCommLog === 'function') renderCommLog();

    // Feature 6: Comparateur
    if (typeof initComparateur === 'function') initComparateur();

    // Tunisia Map (Feature 2) will be initialized when shown
    // Default view
    showAudioFeature(1);
    switchRiskView('agent');

    console.log('✅ Audio-Visuel initialized successfully');
};
