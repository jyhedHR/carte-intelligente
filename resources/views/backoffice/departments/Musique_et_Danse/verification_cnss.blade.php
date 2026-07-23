@extends('shared.layouts.backoffice')

@section('title', 'Vérification CNSS')
@section('breadcrumb', 'Vérification CNSS')

@section('content')

{{-- ══ IA COMMAND STRIP ══ --}}
<div class="cnss-ia-strip">
    <div class="cnss-ia-left">
        <div class="cnss-ia-badge">
            <span class="cnss-pulse"></span>
            IA CNSS — Surveillance Active
        </div>
        <div class="cnss-ia-msg" id="cnssIaMsg">
            🤖 <strong>48 affiliations expirent dans les 90 prochains jours.</strong>
            L'IA recommande un envoi groupé de rappels aujourd'hui — 23 artistes n'ont pas encore été contactés.
        </div>
    </div>
    <div class="cnss-ia-actions">
        <button class="btn btn-outline btn-sm" onclick="openModal('modal-cnss-ia')">🤖 Analyse IA complète</button>
        <button class="btn btn-gold btn-sm" onclick="cnssIaNotifyAll()">📨 Notifier tous (23)</button>
    </div>
</div>

{{-- ══ KPI GRID ══ --}}
<div class="kpi-grid">
    <div class="kpi-card green">
        <div class="kpi-icon">✅</div>
        <div class="kpi-value" id="cnssKpi1">892</div>
        <div class="kpi-label">Affiliés Actifs</div>
        <div class="kpi-delta up">↑ 8% ce mois</div>
    </div>
    <div class="kpi-card red">
        <div class="kpi-icon">❌</div>
        <div class="kpi-value" id="cnssKpi2">156</div>
        <div class="kpi-label">Non Affiliés</div>
        <div class="kpi-delta down">↓ −3%</div>
    </div>
    <div class="kpi-card gold">
        <div class="kpi-icon">⚠️</div>
        <div class="kpi-value" id="cnssKpi3">48</div>
        <div class="kpi-label">Affiliation Expirée</div>
        <div class="kpi-delta down">↑ Urgent</div>
    </div>
    <div class="kpi-card blue">
        <div class="kpi-icon">🔄</div>
        <div class="kpi-value" id="cnssKpi4">34</div>
        <div class="kpi-label">À Vérifier</div>
        <div class="kpi-delta flat">→ En cours</div>
    </div>
</div>

{{-- ══ IA RISK RADAR ══ --}}
<div class="cnss-radar-banner">
    <div class="cnss-radar-glow"></div>

    <div class="cnss-radar-content">
        <div class="cnss-radar-title">
            <span class="cnss-radar-icon">🛡️</span>
            Radar de Risque IA — Vue Gestionnaire
        </div>
        <div class="cnss-radar-subtitle">L'IA évalue en temps réel les risques CNSS de votre portefeuille d'artistes</div>

        <div class="cnss-risk-cards">
            <div class="cnss-risk-card cnss-risk-red" onclick="cnssTab('tab-expired')">
                <div class="cnss-risk-val">48</div>
                <div class="cnss-risk-lbl">Expirées</div>
                <div class="cnss-risk-ia">⚡ Action immédiate</div>
            </div>
            <div class="cnss-risk-card cnss-risk-amber" onclick="cnssTab('tab-expiring')">
                <div class="cnss-risk-val">23</div>
                <div class="cnss-risk-lbl">Expirent &lt;90j</div>
                <div class="cnss-risk-ia">⏰ Préventif</div>
            </div>
            <div class="cnss-risk-card cnss-risk-blue" onclick="cnssTab('tab-pending')">
                <div class="cnss-risk-val">34</div>
                <div class="cnss-risk-lbl">En vérification</div>
                <div class="cnss-risk-ia">🔄 En cours</div>
            </div>
            <div class="cnss-risk-card cnss-risk-purple">
                <div class="cnss-risk-val">7</div>
                <div class="cnss-risk-lbl">N° invalides</div>
                <div class="cnss-risk-ia">🚨 Fraude ?</div>
            </div>
        </div>
    </div>

    <div class="cnss-radar-visual">
        <div class="cnss-gauge" id="cnssGauge">
            <svg viewBox="0 0 120 80" width="140">
                <path d="M10,70 A50,50 0 0,1 110,70" fill="none" stroke="var(--bg4)" stroke-width="10" stroke-linecap="round"/>
                <path d="M10,70 A50,50 0 0,1 110,70" fill="none" stroke="url(#cnssGrad)" stroke-width="10" stroke-linecap="round"
                    stroke-dasharray="157" stroke-dashoffset="40" id="cnssGaugeArc"/>
                <defs>
                    <linearGradient id="cnssGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="var(--red)"/>
                        <stop offset="50%" stop-color="var(--amber)"/>
                        <stop offset="100%" stop-color="var(--green)"/>
                    </linearGradient>
                </defs>
            </svg>
            <div class="cnss-gauge-val">85%</div>
            <div class="cnss-gauge-lbl">Taux conformité</div>
        </div>
    </div>
</div>

{{-- ══ TOOLBAR ══ --}}
<div class="panel" style="margin-bottom:16px;">
    <div class="panel-body" style="padding:12px 20px;">
        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <input class="form-input" id="cnssSearch" placeholder="🔍 Artiste, N° CNSS, CIN…" style="width:260px; flex:none;" oninput="cnssApplyFilters()">
            <select class="form-select" id="cnssRegion" style="width:160px;" onchange="cnssApplyFilters()">
                <option value="">Toutes les régions</option>
                <option value="Tunis">Tunis</option>
                <option value="Sfax">Sfax</option>
                <option value="Sousse">Sousse</option>
                <option value="Kairouan">Kairouan</option>
                <option value="Gafsa">Gafsa</option>
            </select>
            <select class="form-select" id="cnssType" style="width:150px;" onchange="cnssApplyFilters()">
                <option value="">Tous les types</option>
                <option value="Musicien">Musicien</option>
                <option value="Danseuse">Danseuse</option>
                <option value="Instrumentiste">Instrumentiste</option>
                <option value="Comédien">Comédien</option>
                <option value="Plasticien">Plasticien</option>
            </select>
            <div style="margin-left:auto; display:flex; gap:8px;">
                <button class="btn btn-outline btn-sm" onclick="showToast('Export Excel généré!','info')">📊 Export Excel</button>
                <button class="btn btn-outline btn-sm" onclick="cnssIaBulkRenew()">🔄 Renouvellement groupé IA</button>
                <button class="btn btn-gold btn-sm" onclick="openModal('modal-cnss-verify')">+ Vérifier Affiliation</button>
            </div>
        </div>
    </div>
</div>

{{-- ══ TABS ══ --}}
<div class="cnss-tabs-row">
    <button class="cnss-tab active" data-tab="tab-all" onclick="cnssTab('tab-all', this)">
        Tous <span class="cnss-tab-count">1130</span>
    </button>
    <button class="cnss-tab" data-tab="tab-active" onclick="cnssTab('tab-active', this)">
        Affiliés ✅ <span class="cnss-tab-count cnss-tc-green">892</span>
    </button>
    <button class="cnss-tab" data-tab="tab-expiring" onclick="cnssTab('tab-expiring', this)">
        Expirent bientôt ⏰ <span class="cnss-tab-count cnss-tc-amber">23</span>
    </button>
    <button class="cnss-tab" data-tab="tab-expired" onclick="cnssTab('tab-expired', this)">
        Expirées ❌ <span class="cnss-tab-count cnss-tc-red">48</span>
    </button>
    <button class="cnss-tab" data-tab="tab-none" onclick="cnssTab('tab-none', this)">
        Non Affiliés 🚫 <span class="cnss-tab-count cnss-tc-red">156</span>
    </button>
    <button class="cnss-tab" data-tab="tab-pending" onclick="cnssTab('tab-pending', this)">
        En vérification 🔄 <span class="cnss-tab-count cnss-tc-blue">34</span>
    </button>
</div>

{{-- ══ TAB: TOUS ══ --}}
<div id="tab-all" class="cnss-tab-panel active">
    <div class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">Tous les artistes — CNSS</div>
                <div class="panel-sub" id="cnssAllCount">1130 artistes au total</div>
            </div>
            <div class="cnss-ia-inline-badge">
                <span class="cnss-pulse cnss-pulse-blue"></span>
                Scoring IA actif
            </div>
        </div>
        <div class="panel-body no-pad">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Artiste</th>
                            <th>N° CNSS</th>
                            <th>Type</th>
                            <th>Région</th>
                            <th>Statut</th>
                            <th>Expiration</th>
                            <th>Score IA</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="cnssTableAll">
                        {{-- Row 1 --}}
                        <tr data-name="Ahmed Ben Ali" data-region="Tunis" data-type="Musicien" data-status="active">
                            <td>
                                <div class="cnss-artist-cell">
                                    <div class="cnss-av cnss-av-green">AB</div>
                                    <div>
                                        <div class="cnss-artist-name">Ahmed Ben Ali</div>
                                        <div class="cnss-artist-sub">CIN: 08745632 · Tunis</div>
                                    </div>
                                </div>
                            </td>
                            <td><code class="cnss-code">156784392</code></td>
                            <td>Musicien</td>
                            <td>Tunis</td>
                            <td><span class="badge green">Actif</span></td>
                            <td>
                                <div class="cnss-exp-cell">
                                    <span>15/01/2028</span>
                                    <span class="cnss-days-left cnss-days-ok">+1370j</span>
                                </div>
                            </td>
                            <td>
                                <div class="cnss-score-wrap">
                                    <div class="cnss-score-bar"><div class="cnss-score-fill" style="width:96%; background:var(--green);"></div></div>
                                    <span class="cnss-score-num" style="color:var(--green);">96</span>
                                </div>
                            </td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-ghost btn-sm" onclick="cnssShowDetail('Ahmed Ben Ali','156784392','Musicien','Tunis','Actif','15/01/2028','96')">👁️ Voir</button>
                                    <button class="btn btn-ghost btn-sm" onclick="showToast('Historique ouvert','info')">📜</button>
                                </div>
                            </td>
                        </tr>
                        {{-- Row 2 --}}
                        <tr data-name="Fatima Kaddour" data-region="Sfax" data-type="Danseuse" data-status="active">
                            <td>
                                <div class="cnss-artist-cell">
                                    <div class="cnss-av cnss-av-teal">FK</div>
                                    <div>
                                        <div class="cnss-artist-name">Fatima Kaddour</div>
                                        <div class="cnss-artist-sub">CIN: 09123456 · Sfax</div>
                                    </div>
                                </div>
                            </td>
                            <td><code class="cnss-code">156784393</code></td>
                            <td>Danseuse</td>
                            <td>Sfax</td>
                            <td><span class="badge green">Actif</span></td>
                            <td>
                                <div class="cnss-exp-cell">
                                    <span>08/03/2027</span>
                                    <span class="cnss-days-left cnss-days-ok">+695j</span>
                                </div>
                            </td>
                            <td>
                                <div class="cnss-score-wrap">
                                    <div class="cnss-score-bar"><div class="cnss-score-fill" style="width:88%; background:var(--teal);"></div></div>
                                    <span class="cnss-score-num" style="color:var(--teal);">88</span>
                                </div>
                            </td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-ghost btn-sm" onclick="cnssShowDetail('Fatima Kaddour','156784393','Danseuse','Sfax','Actif','08/03/2027','88')">👁️ Voir</button>
                                    <button class="btn btn-ghost btn-sm" onclick="showToast('Historique ouvert','info')">📜</button>
                                </div>
                            </td>
                        </tr>
                        {{-- Row 3 — expires soon --}}
                        <tr data-name="Mohamed Saïd" data-region="Sousse" data-type="Instrumentiste" data-status="expiring">
                            <td>
                                <div class="cnss-artist-cell">
                                    <div class="cnss-av cnss-av-amber">MS</div>
                                    <div>
                                        <div class="cnss-artist-name">Mohamed Saïd</div>
                                        <div class="cnss-artist-sub">CIN: 07456123 · Sousse</div>
                                    </div>
                                </div>
                            </td>
                            <td><code class="cnss-code">156784394</code></td>
                            <td>Instrumentiste</td>
                            <td>Sousse</td>
                            <td><span class="badge gold">Expire bientôt</span></td>
                            <td>
                                <div class="cnss-exp-cell">
                                    <span>22/11/2026</span>
                                    <span class="cnss-days-left cnss-days-warn">+223j</span>
                                </div>
                            </td>
                            <td>
                                <div class="cnss-score-wrap">
                                    <div class="cnss-score-bar"><div class="cnss-score-fill" style="width:72%; background:var(--amber);"></div></div>
                                    <span class="cnss-score-num" style="color:var(--amber);">72</span>
                                </div>
                            </td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-ghost btn-sm" onclick="cnssShowDetail('Mohamed Saïd','156784394','Instrumentiste','Sousse','Expire bientôt','22/11/2026','72')">👁️ Voir</button>
                                    <button class="btn btn-gold btn-sm" onclick="showToast('Renouvellement lancé!','success')">🔄</button>
                                </div>
                            </td>
                        </tr>
                        {{-- Row 4 — expired --}}
                        <tr data-name="Nadia Turki" data-region="Kairouan" data-type="Comédien" data-status="expired" style="background:rgba(248,113,113,0.04);">
                            <td>
                                <div class="cnss-artist-cell">
                                    <div class="cnss-av cnss-av-red">NT</div>
                                    <div>
                                        <div class="cnss-artist-name">Nadia Turki</div>
                                        <div class="cnss-artist-sub">CIN: 06789012 · Kairouan</div>
                                    </div>
                                </div>
                            </td>
                            <td><code class="cnss-code">156784395</code></td>
                            <td>Comédien</td>
                            <td>Kairouan</td>
                            <td><span class="badge red">Expirée</span></td>
                            <td>
                                <div class="cnss-exp-cell">
                                    <span>12/09/2023</span>
                                    <span class="cnss-days-left cnss-days-over">+594j</span>
                                </div>
                            </td>
                            <td>
                                <div class="cnss-score-wrap">
                                    <div class="cnss-score-bar"><div class="cnss-score-fill" style="width:28%; background:var(--red);"></div></div>
                                    <span class="cnss-score-num" style="color:var(--red);">28</span>
                                </div>
                            </td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-ghost btn-sm" onclick="cnssShowDetail('Nadia Turki','156784395','Comédien','Kairouan','Expirée','12/09/2023','28')">👁️ Voir</button>
                                    <button class="btn btn-gold btn-sm" onclick="openModal('modal-cnss-renew')">🔄 Renouveler</button>
                                </div>
                            </td>
                        </tr>
                        {{-- Row 5 — not affiliated --}}
                        <tr data-name="Sami Hadj" data-region="Gafsa" data-type="Plasticien" data-status="none">
                            <td>
                                <div class="cnss-artist-cell">
                                    <div class="cnss-av" style="background:var(--bg4); color:var(--text3);">SH</div>
                                    <div>
                                        <div class="cnss-artist-name">Sami Hadj</div>
                                        <div class="cnss-artist-sub">CIN: 05321678 · Gafsa</div>
                                    </div>
                                </div>
                            </td>
                            <td><code class="cnss-code" style="color:var(--text3);">—</code></td>
                            <td>Plasticien</td>
                            <td>Gafsa</td>
                            <td><span class="badge red">Non affilié</span></td>
                            <td>
                                <div class="cnss-exp-cell">
                                    <span style="color:var(--text3);">—</span>
                                </div>
                            </td>
                            <td>
                                <div class="cnss-score-wrap">
                                    <div class="cnss-score-bar"><div class="cnss-score-fill" style="width:10%; background:var(--red);"></div></div>
                                    <span class="cnss-score-num" style="color:var(--red);">10</span>
                                </div>
                            </td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-ghost btn-sm" onclick="cnssShowDetail('Sami Hadj','—','Plasticien','Gafsa','Non affilié','—','10')">👁️ Voir</button>
                                    <button class="btn btn-gold btn-sm" onclick="showToast('Dossier d\'affiliation créé!','success')">📋 Affilier</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ══ TAB: AFFILIÉS ACTIFS ══ --}}
<div id="tab-active" class="cnss-tab-panel" style="display:none;">
    <div class="panel">
        <div class="panel-head">
            <div><div class="panel-title">✅ Affiliés Actifs</div><div class="panel-sub">892 artistes en règle</div></div>
            <span class="badge green">✓ Conformes</span>
        </div>
        <div class="panel-body">
            <p style="font-size:13px; color:var(--text2);">Affichage filtré sur les artistes avec affiliation CNSS active et valide. Les données complètes sont dans l'onglet <strong>Tous</strong>.</p>
            <div style="margin-top:14px; padding:14px; background:var(--green-dim); border-radius:var(--radius); border:1px solid rgba(74,222,128,.2); font-size:12.5px; color:var(--green);">
                ✅ 892 artistes conformes · Aucune action requise · Taux de conformité : <strong>85%</strong>
            </div>
        </div>
    </div>
</div>

{{-- ══ TAB: EXPIRENT BIENTÔT ══ --}}
<div id="tab-expiring" class="cnss-tab-panel" style="display:none;">
    <div class="panel">
        <div class="panel-head">
            <div><div class="panel-title">⏰ Expirent dans moins de 90 jours</div><div class="panel-sub">23 artistes — Action préventive recommandée</div></div>
            <button class="btn btn-gold btn-sm" onclick="cnssIaNotifyAll()">📨 Notifier tous (IA)</button>
        </div>
        <div class="panel-body no-pad">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Artiste</th>
                            <th>N° CNSS</th>
                            <th>Expiration</th>
                            <th>Jours restants</th>
                            <th>Urgence IA</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><div class="cnss-artist-cell"><div class="cnss-av cnss-av-amber">MS</div><div><div class="cnss-artist-name">Mohamed Saïd</div><div class="cnss-artist-sub">Instrumentiste · Sousse</div></div></div></td>
                            <td><code class="cnss-code">156784394</code></td>
                            <td>22/11/2026</td>
                            <td><span class="badge gold">223 jours</span></td>
                            <td><div class="cnss-urgency cnss-urg-medium">⚠ Moyen</div></td>
                            <td><div class="row-actions">
                                <button class="btn btn-ghost btn-sm" onclick="showToast('Email envoyé à Mohamed Saïd','success')">📧 Rappel</button>
                                <button class="btn btn-gold btn-sm" onclick="openModal('modal-cnss-renew')">🔄 Renouveler</button>
                            </div></td>
                        </tr>
                        <tr>
                            <td><div class="cnss-artist-cell"><div class="cnss-av cnss-av-amber">LB</div><div><div class="cnss-artist-name">Leila Bouazizi</div><div class="cnss-artist-sub">Musicienne · Tunis</div></div></div></td>
                            <td><code class="cnss-code">156784401</code></td>
                            <td>30/06/2026</td>
                            <td><span class="badge red">78 jours</span></td>
                            <td><div class="cnss-urgency cnss-urg-high">🔴 Élevé</div></td>
                            <td><div class="row-actions">
                                <button class="btn btn-ghost btn-sm" onclick="showToast('Email envoyé à Leila Bouazizi','success')">📧 Rappel</button>
                                <button class="btn btn-gold btn-sm" onclick="openModal('modal-cnss-renew')">🔄 Renouveler</button>
                            </div></td>
                        </tr>
                        <tr>
                            <td><div class="cnss-artist-cell"><div class="cnss-av cnss-av-amber">KA</div><div><div class="cnss-artist-name">Khaled Amri</div><div class="cnss-artist-sub">Plasticien · Sfax</div></div></div></td>
                            <td><code class="cnss-code">156784408</code></td>
                            <td>15/05/2026</td>
                            <td><span class="badge red">32 jours</span></td>
                            <td><div class="cnss-urgency cnss-urg-critical">🚨 Critique</div></td>
                            <td><div class="row-actions">
                                <button class="btn btn-ghost btn-sm" onclick="showToast('Email envoyé à Khaled Amri','success')">📧 Rappel</button>
                                <button class="btn btn-gold btn-sm" onclick="openModal('modal-cnss-renew')">🔄 Renouveler</button>
                            </div></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ══ TAB: EXPIRÉES ══ --}}
<div id="tab-expired" class="cnss-tab-panel" style="display:none;">
    <div class="panel">
        <div class="panel-head">
            <div><div class="panel-title">❌ Affiliations Expirées</div><div class="panel-sub">48 artistes — Renouvellement requis</div></div>
            <button class="btn btn-gold btn-sm" onclick="cnssIaBulkRenew()">⚡ Renouvellement groupé IA</button>
        </div>
        <div class="panel-body no-pad">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Artiste</th>
                            <th>N° CNSS</th>
                            <th>Date Expiration</th>
                            <th>Jours écoulés</th>
                            <th>Statut Carte</th>
                            <th>Risque IA</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="background:rgba(248,113,113,0.03);">
                            <td><div class="cnss-artist-cell"><div class="cnss-av cnss-av-red">NT</div><div><div class="cnss-artist-name">Nadia Turki</div><div class="cnss-artist-sub">Comédien · Kairouan</div></div></div></td>
                            <td><code class="cnss-code">156784395</code></td>
                            <td>12/09/2023</td>
                            <td><span class="badge red">+594 jours</span></td>
                            <td><span class="badge red">Suspendue</span></td>
                            <td><div class="cnss-urgency cnss-urg-critical">🚨 Critique</div></td>
                            <td><div class="row-actions">
                                <button class="btn btn-ghost btn-sm" onclick="cnssShowDetail('Nadia Turki','156784395','Comédien','Kairouan','Expirée','12/09/2023','28')">👁️</button>
                                <button class="btn btn-gold btn-sm" onclick="openModal('modal-cnss-renew')">🔄 Renouveler</button>
                            </div></td>
                        </tr>
                        <tr style="background:rgba(248,113,113,0.03);">
                            <td><div class="cnss-artist-cell"><div class="cnss-av cnss-av-red">SH</div><div><div class="cnss-artist-name">Sami Hadj Salah</div><div class="cnss-artist-sub">Musicien · Tunis</div></div></div></td>
                            <td><code class="cnss-code">156784396</code></td>
                            <td>05/06/2024</td>
                            <td><span class="badge red">+309 jours</span></td>
                            <td><span class="badge red">Suspendue</span></td>
                            <td><div class="cnss-urgency cnss-urg-high">🔴 Élevé</div></td>
                            <td><div class="row-actions">
                                <button class="btn btn-ghost btn-sm" onclick="cnssShowDetail('Sami Hadj Salah','156784396','Musicien','Tunis','Expirée','05/06/2024','35')">👁️</button>
                                <button class="btn btn-gold btn-sm" onclick="openModal('modal-cnss-renew')">🔄 Renouveler</button>
                            </div></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ══ TAB: NON AFFILIÉS ══ --}}
<div id="tab-none" class="cnss-tab-panel" style="display:none;">
    <div class="panel">
        <div class="panel-head">
            <div><div class="panel-title">🚫 Non Affiliés</div><div class="panel-sub">156 artistes sans affiliation CNSS</div></div>
            <button class="btn btn-gold btn-sm" onclick="showToast('Campagne d\'affiliation lancée pour 156 artistes!','success')">📨 Campagne IA</button>
        </div>
        <div class="panel-body no-pad">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Artiste</th>
                            <th>Type</th>
                            <th>Région</th>
                            <th>Contact</th>
                            <th>Priorité IA</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><div class="cnss-artist-cell"><div class="cnss-av" style="background:var(--bg4);color:var(--text3);">YB</div><div><div class="cnss-artist-name">Yassine Belhadj</div><div class="cnss-artist-sub">CIN: 04123789</div></div></div></td>
                            <td>Comédien</td><td>Tunis</td>
                            <td><a style="color:var(--blue); font-size:12px;">yassine@email.com</a></td>
                            <td><div class="cnss-urgency cnss-urg-high">🔴 Élevé</div></td>
                            <td><div class="row-actions">
                                <button class="btn btn-ghost btn-sm" onclick="showToast('Email envoyé à Yassine','success')">📧 Contacter</button>
                                <button class="btn btn-gold btn-sm" onclick="openModal('modal-cnss-verify')">📋 Initier</button>
                            </div></td>
                        </tr>
                        <tr>
                            <td><div class="cnss-artist-cell"><div class="cnss-av" style="background:var(--bg4);color:var(--text3);">RG</div><div><div class="cnss-artist-name">Rim Gharbi</div><div class="cnss-artist-sub">CIN: 03987654</div></div></div></td>
                            <td>Danseuse</td><td>Sfax</td>
                            <td><a style="color:var(--blue); font-size:12px;">rim@email.com</a></td>
                            <td><div class="cnss-urgency cnss-urg-medium">⚠ Moyen</div></td>
                            <td><div class="row-actions">
                                <button class="btn btn-ghost btn-sm" onclick="showToast('Email envoyé à Rim','success')">📧 Contacter</button>
                                <button class="btn btn-gold btn-sm" onclick="openModal('modal-cnss-verify')">📋 Initier</button>
                            </div></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ══ TAB: EN VÉRIFICATION ══ --}}
<div id="tab-pending" class="cnss-tab-panel" style="display:none;">
    <div class="panel">
        <div class="panel-head">
            <div><div class="panel-title">🔄 En cours de vérification</div><div class="panel-sub">34 dossiers — IA analyse en temps réel</div></div>
            <div class="cnss-ia-inline-badge"><span class="cnss-pulse cnss-pulse-blue"></span> Analyse IA active</div>
        </div>
        <div class="panel-body">
            <div style="display:flex; flex-direction:column; gap:10px;">
                <div class="cnss-pending-row">
                    <div class="cnss-av cnss-av-blue">TM</div>
                    <div style="flex:1;">
                        <div style="font-size:13px; font-weight:700; color:var(--text);">Tarek Mansouri</div>
                        <div style="font-size:11px; color:var(--text3);">N° CNSS: 156784410 · Soumis il y a 2h</div>
                    </div>
                    <div class="cnss-pending-progress">
                        <div style="display:flex; justify-content:space-between; font-size:11px; margin-bottom:4px; color:var(--text3);">
                            <span>Vérification IA…</span><span style="font-family:var(--font-mono); color:var(--blue);">68%</span>
                        </div>
                        <div class="cnss-prog-bar"><div class="cnss-prog-fill" style="width:68%; background:var(--blue);"></div></div>
                    </div>
                    <span class="badge blue">En cours</span>
                </div>
                <div class="cnss-pending-row">
                    <div class="cnss-av cnss-av-teal">HB</div>
                    <div style="flex:1;">
                        <div style="font-size:13px; font-weight:700; color:var(--text);">Habib Bensalah</div>
                        <div style="font-size:11px; color:var(--text3);">N° CNSS: 156784411 · Soumis il y a 4h</div>
                    </div>
                    <div class="cnss-pending-progress">
                        <div style="display:flex; justify-content:space-between; font-size:11px; margin-bottom:4px; color:var(--text3);">
                            <span>Vérification IA…</span><span style="font-family:var(--font-mono); color:var(--teal);">92%</span>
                        </div>
                        <div class="cnss-prog-bar"><div class="cnss-prog-fill" style="width:92%; background:var(--teal);"></div></div>
                    </div>
                    <span class="badge green">Presque prêt</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ BOTTOM GRID ══ --}}
<div class="grid-2" style="margin-top:20px;">

    {{-- Alertes IA --}}
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">🔔 Alertes IA Automatiques</div>
            <span class="cnss-ia-inline-badge"><span class="cnss-pulse"></span> Live</span>
        </div>
        <div class="panel-body" style="display:flex; flex-direction:column; gap:10px;">

            <div class="cnss-alert-row cnss-alert-red">
                <div class="cnss-alert-icon" style="background:var(--red-dim); color:var(--red);">🚨</div>
                <div class="cnss-alert-info">
                    <div class="cnss-alert-title">48 affiliations expirées — Risque légal</div>
                    <div class="cnss-alert-meta">L'IA recommande un renouvellement groupé immédiat</div>
                </div>
                <button class="btn btn-gold btn-sm" onclick="cnssIaBulkRenew()">Agir</button>
            </div>

            <div class="cnss-alert-row cnss-alert-amber">
                <div class="cnss-alert-icon" style="background:var(--amber-dim); color:var(--amber);">⏰</div>
                <div class="cnss-alert-info">
                    <div class="cnss-alert-title">23 expirations dans 90 jours</div>
                    <div class="cnss-alert-meta">Envoyer rappels préventifs — 0 email envoyé à ce jour</div>
                </div>
                <button class="btn btn-outline btn-sm" onclick="cnssIaNotifyAll()">Notifier</button>
            </div>

            <div class="cnss-alert-row cnss-alert-red">
                <div class="cnss-alert-icon" style="background:var(--red-dim); color:var(--red);">⚡</div>
                <div class="cnss-alert-info">
                    <div class="cnss-alert-title">7 numéros CNSS invalides détectés</div>
                    <div class="cnss-alert-meta">L'IA suspecte des erreurs de saisie ou fraude potentielle</div>
                </div>
                <button class="btn btn-outline btn-sm" onclick="openModal('modal-cnss-ia')">Analyser</button>
            </div>

            <div class="cnss-alert-row">
                <div class="cnss-alert-icon" style="background:var(--blue-dim); color:var(--blue);">🔄</div>
                <div class="cnss-alert-info">
                    <div class="cnss-alert-title">12 renouvellements en attente de validation</div>
                    <div class="cnss-alert-meta">Dossiers complets — prêts à être approuvés</div>
                </div>
                <button class="btn btn-gold btn-sm" onclick="showToast('12 renouvellements validés!','success')">Valider</button>
            </div>
        </div>
    </div>

    {{-- Stats IA --}}
    <div class="panel">
        <div class="panel-head"><div class="panel-title">📊 Statistiques CNSS</div></div>
        <div class="panel-body" style="display:flex; flex-direction:column; gap:14px;">
            <div class="confidence-bar-row">
                <div class="cb-label">Taux d'affiliation global</div>
                <div class="cb-bar"><div class="cb-fill" style="width:85%; background:var(--green);"></div></div>
                <div class="cb-pct" style="color:var(--green);">85%</div>
            </div>
            <div class="confidence-bar-row">
                <div class="cb-label">Affiliations actives</div>
                <div class="cb-bar"><div class="cb-fill" style="width:78%; background:var(--teal);"></div></div>
                <div class="cb-pct" style="color:var(--teal);">78%</div>
            </div>
            <div class="confidence-bar-row">
                <div class="cb-label">Vérifications réussies IA</div>
                <div class="cb-bar"><div class="cb-fill" style="width:92%; background:var(--blue);"></div></div>
                <div class="cb-pct" style="color:var(--blue);">92%</div>
            </div>
            <div class="confidence-bar-row">
                <div class="cb-label">Détection fraudes IA</div>
                <div class="cb-bar"><div class="cb-fill" style="width:97%; background:var(--gold);"></div></div>
                <div class="cb-pct" style="color:var(--gold);">97%</div>
            </div>
            <div class="confidence-bar-row">
                <div class="cb-label">Taux de conformité global</div>
                <div class="cb-bar"><div class="cb-fill" style="width:73%; background:var(--purple);"></div></div>
                <div class="cb-pct" style="color:var(--purple);">73%</div>
            </div>

            <div style="padding:12px; background:var(--gold-dim); border-radius:var(--radius-sm); border:1px solid rgba(201,168,76,.2); margin-top:4px;">
                <div style="font-size:11px; font-weight:700; color:var(--gold); margin-bottom:4px; text-transform:uppercase; letter-spacing:.5px;">🤖 Recommandation IA</div>
                <div style="font-size:12px; color:var(--text2); line-height:1.6;">
                    Priorité : traiter les 7 numéros invalides et les 3 expirations critiques (&lt;32j) avant vendredi.
                    Impact estimé : +6% taux de conformité.
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════
     MODALS
══════════════════════ --}}

{{-- Modal: Vérifier Affiliation --}}
<div id="modal-cnss-verify" class="modal">
    <div class="modal-content" style="max-width:520px;">
        <div class="modal-header">
            <div class="modal-title">🔍 Vérifier Affiliation CNSS</div>
            <button class="modal-close" onclick="closeModal('modal-cnss-verify')">×</button>
        </div>
        <div class="modal-body">
            <div class="cnss-verify-ia-hint">
                <span style="font-size:16px;">🤖</span>
                <div>
                    <div style="font-weight:700; font-size:12.5px; color:var(--text);">Vérification IA instantanée</div>
                    <div style="font-size:11.5px; color:var(--text3); margin-top:2px;">L'IA consulte la base CNSS et valide le numéro en temps réel.</div>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Artiste *</label>
                <input type="text" class="form-input" id="vArtiste" placeholder="Ex: Ahmed Ben Ali">
            </div>
            <div class="form-group">
                <label class="form-label">Numéro CNSS *</label>
                <input type="text" class="form-input" id="vCnss" placeholder="Ex: 156784392" oninput="cnssRealTimeValidate(this.value)">
            </div>
            <div id="cnssValidateResult" style="display:none; margin-top:8px; padding:10px 12px; border-radius:var(--radius-sm); font-size:12.5px;"></div>
            <div class="form-group">
                <label class="form-label">Type d'artiste</label>
                <select class="form-select">
                    <option>Musicien</option><option>Danseuse</option>
                    <option>Instrumentiste</option><option>Comédien</option><option>Plasticien</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Région</label>
                <select class="form-select">
                    <option>Tunis</option><option>Sfax</option><option>Sousse</option>
                    <option>Kairouan</option><option>Gafsa</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-cnss-verify')">Annuler</button>
            <button class="btn btn-gold" onclick="cnssRunVerify()">🔍 Vérifier via IA</button>
        </div>
    </div>
</div>

{{-- Modal: Renouveler --}}
<div id="modal-cnss-renew" class="modal">
    <div class="modal-content" style="max-width:500px;">
        <div class="modal-header">
            <div class="modal-title">🔄 Renouvellement Affiliation CNSS</div>
            <button class="modal-close" onclick="closeModal('modal-cnss-renew')">×</button>
        </div>
        <div class="modal-body">
            <div class="cnss-verify-ia-hint" style="border-color:rgba(251,191,36,.3); background:var(--amber-dim);">
                <span style="font-size:16px;">⚠️</span>
                <div>
                    <div style="font-weight:700; font-size:12.5px; color:var(--amber);">Renouvellement requis</div>
                    <div style="font-size:11.5px; color:var(--text3); margin-top:2px;">L'IA pré-remplit les données existantes pour accélérer le traitement.</div>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Artiste</label>
                <input type="text" class="form-input" value="Nadia Turki" readonly>
            </div>
            <div class="form-group">
                <label class="form-label">N° CNSS actuel</label>
                <input type="text" class="form-input" value="156784395" readonly>
            </div>
            <div class="form-group">
                <label class="form-label">Nouvelle date d'expiration</label>
                <input type="date" class="form-input" value="2030-09-12">
            </div>
            <div class="form-group">
                <label class="form-label">Documents joints</label>
                <input type="file" class="form-input" multiple>
            </div>
            <div class="form-group">
                <label class="form-label">Motif / Observations</label>
                <textarea class="form-textarea" rows="2" placeholder="Notes du gestionnaire…"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-cnss-renew')">Annuler</button>
            <button class="btn btn-gold" onclick="closeModal('modal-cnss-renew'); showToast('✅ Renouvellement traité par l\'IA!','success')">✅ Valider le renouvellement</button>
        </div>
    </div>
</div>

{{-- Modal: Détail Artiste --}}
<div id="modal-cnss-detail" class="modal">
    <div class="modal-content" style="max-width:580px;">
        <div class="modal-header">
            <div>
                <div class="modal-title" id="detailArtistName">—</div>
                <div style="margin-top:4px;" id="detailArtistBadges"></div>
            </div>
            <button class="modal-close" onclick="closeModal('modal-cnss-detail')">×</button>
        </div>
        <div class="modal-body">
            <div class="cnss-detail-grid">
                <div class="cnss-detail-field"><span class="cnss-detail-lbl">N° CNSS</span><span id="dCnss" class="cnss-code-lg"></span></div>
                <div class="cnss-detail-field"><span class="cnss-detail-lbl">Type</span><span id="dType"></span></div>
                <div class="cnss-detail-field"><span class="cnss-detail-lbl">Région</span><span id="dRegion"></span></div>
                <div class="cnss-detail-field"><span class="cnss-detail-lbl">Statut</span><span id="dStatus"></span></div>
                <div class="cnss-detail-field"><span class="cnss-detail-lbl">Expiration</span><span id="dExp"></span></div>
                <div class="cnss-detail-field"><span class="cnss-detail-lbl">Score IA</span><span id="dScore" style="font-weight:900; font-family:var(--font-mono);"></span></div>
            </div>

            <div class="form-group" style="margin-top:16px;">
                <label class="form-label">Analyse IA du dossier</label>
                <div class="cnss-ia-reco" id="detailIaReco"></div>
            </div>

            <div class="form-group">
                <label class="form-label">Actions recommandées par l'IA</label>
                <div id="detailIaActions" style="display:flex; flex-direction:column; gap:6px;"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-cnss-detail')">Fermer</button>
            <button class="btn btn-ghost btn-sm" onclick="showToast('Historique complet ouvert','info')">📜 Historique</button>
            <button class="btn btn-gold" onclick="closeModal('modal-cnss-detail'); openModal('modal-cnss-renew')">🔄 Renouveler</button>
        </div>
    </div>
</div>

{{-- Modal: IA Analyse complète --}}
<div id="modal-cnss-ia" class="modal">
    <div class="modal-content" style="max-width:580px;">
        <div class="modal-header">
            <div>
                <div class="modal-title">🤖 Analyse IA CNSS — Rapport Gestionnaire</div>
                <div style="font-size:11px; color:var(--text3); margin-top:3px;">Insights temps réel · Risques · Recommandations prioritaires</div>
            </div>
            <button class="modal-close" onclick="closeModal('modal-cnss-ia')">×</button>
        </div>
        <div class="modal-body">

            <div class="cnss-ia-tabs">
                <button class="cnss-ia-tab active" onclick="cnssIaTab(this,'iat-risk')">⚠️ Risques</button>
                <button class="cnss-ia-tab" onclick="cnssIaTab(this,'iat-fraud')">🔍 Fraudes</button>
                <button class="cnss-ia-tab" onclick="cnssIaTab(this,'iat-plan')">📋 Plan d'action</button>
                <button class="cnss-ia-tab" onclick="cnssIaTab(this,'iat-trends')">📈 Tendances</button>
            </div>

            <div id="iat-risk" class="cnss-iat-panel active">
                <div class="cnss-ia-card cnss-ia-red">
                    <div class="cnss-ia-card-icon">🚨</div>
                    <div>
                        <div class="cnss-ia-card-title">3 artistes — Expiration critique (&lt;30 jours)</div>
                        <div class="cnss-ia-card-desc">Khaled Amri (32j), Leila Bouazizi (78j en attente) et 1 autre. Risque légal immédiat si non traité.</div>
                    </div>
                </div>
                <div class="cnss-ia-card cnss-ia-amber">
                    <div class="cnss-ia-card-icon">⏰</div>
                    <div>
                        <div class="cnss-ia-card-title">48 affiliations expirées depuis +30 jours</div>
                        <div class="cnss-ia-card-desc">Nadia Turki (+594j) et Sami Hadj (+309j) présentent les délais les plus critiques. Action groupée recommandée.</div>
                    </div>
                </div>
                <div class="cnss-ia-card cnss-ia-blue">
                    <div class="cnss-ia-card-icon">📊</div>
                    <div>
                        <div class="cnss-ia-card-title">156 artistes non affiliés — Impact sur quotas</div>
                        <div class="cnss-ia-card-desc">Le taux de conformité global (73%) est en dessous du seuil réglementaire recommandé de 80%.</div>
                    </div>
                </div>
            </div>

            <div id="iat-fraud" class="cnss-iat-panel" style="display:none;">
                <div class="cnss-ia-card cnss-ia-red">
                    <div class="cnss-ia-card-icon">⚡</div>
                    <div>
                        <div class="cnss-ia-card-title">7 numéros CNSS suspects — Vérification requise</div>
                        <div class="cnss-ia-card-desc">L'IA a détecté des numéros qui ne correspondent pas au format officiel ou qui apparaissent en doublon dans la base.</div>
                    </div>
                </div>
                <div class="cnss-ia-card cnss-ia-amber">
                    <div class="cnss-ia-card-icon">🔍</div>
                    <div>
                        <div class="cnss-ia-card-title">Pattern suspect — Région Gafsa</div>
                        <div class="cnss-ia-card-desc">3 numéros consécutifs enregistrés le même jour pour des artistes de régions différentes. Corrélation inhabituelle détectée.</div>
                    </div>
                </div>
                <div style="padding:12px 14px; background:var(--green-dim); border-radius:var(--radius-sm); font-size:12.5px; color:var(--green);">
                    ✅ 1123 numéros CNSS validés et conformes — Aucun autre problème détecté.
                </div>
            </div>

            <div id="iat-plan" class="cnss-iat-panel" style="display:none;">
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <div class="cnss-action-step">
                        <div class="cnss-step-num cnss-step-red">1</div>
                        <div class="cnss-step-body">
                            <div class="cnss-step-title">Aujourd'hui — Traiter les 3 critiques (&lt;30j)</div>
                            <div class="cnss-step-desc">Lancer le renouvellement pour Khaled Amri, puis valider les 2 autres dossiers prêts.</div>
                        </div>
                        <button class="btn btn-gold btn-sm" onclick="openModal('modal-cnss-renew'); closeModal('modal-cnss-ia')">Agir</button>
                    </div>
                    <div class="cnss-action-step">
                        <div class="cnss-step-num cnss-step-amber">2</div>
                        <div class="cnss-step-body">
                            <div class="cnss-step-title">Cette semaine — Envoi de rappels groupés</div>
                            <div class="cnss-step-desc">L'IA envoie automatiquement 23 emails de rappel aux artistes qui expirent dans 90j.</div>
                        </div>
                        <button class="btn btn-outline btn-sm" onclick="cnssIaNotifyAll(); closeModal('modal-cnss-ia')">Lancer</button>
                    </div>
                    <div class="cnss-action-step">
                        <div class="cnss-step-num cnss-step-blue">3</div>
                        <div class="cnss-step-body">
                            <div class="cnss-step-title">Ce mois — Vérifier les 7 numéros suspects</div>
                            <div class="cnss-step-desc">Croiser avec la base CNSS officielle et signaler les anomalies aux autorités compétentes.</div>
                        </div>
                        <button class="btn btn-outline btn-sm" onclick="showToast('Rapport fraude généré!','info'); closeModal('modal-cnss-ia')">Générer</button>
                    </div>
                </div>
            </div>

            <div id="iat-trends" class="cnss-iat-panel" style="display:none;">
                <div style="margin-bottom:12px; font-size:12.5px; color:var(--text2);">Évolution du taux de conformité — 6 derniers mois</div>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <div class="confidence-bar-row"><div class="cb-label">Novembre 2025</div><div class="cb-bar"><div class="cb-fill" style="width:68%; background:var(--amber);"></div></div><div class="cb-pct">68%</div></div>
                    <div class="confidence-bar-row"><div class="cb-label">Décembre 2025</div><div class="cb-bar"><div class="cb-fill" style="width:70%; background:var(--amber);"></div></div><div class="cb-pct">70%</div></div>
                    <div class="confidence-bar-row"><div class="cb-label">Janvier 2026</div><div class="cb-bar"><div class="cb-fill" style="width:74%; background:var(--gold);"></div></div><div class="cb-pct">74%</div></div>
                    <div class="confidence-bar-row"><div class="cb-label">Février 2026</div><div class="cb-bar"><div class="cb-fill" style="width:78%; background:var(--gold);"></div></div><div class="cb-pct">78%</div></div>
                    <div class="confidence-bar-row"><div class="cb-label">Mars 2026</div><div class="cb-bar"><div class="cb-fill" style="width:81%; background:var(--teal);"></div></div><div class="cb-pct">81%</div></div>
                    <div class="confidence-bar-row"><div class="cb-label">Avril 2026</div><div class="cb-bar"><div class="cb-fill" style="width:85%; background:var(--green);"></div></div><div class="cb-pct">85%</div></div>
                </div>
                <div class="cnss-ia-reco" style="margin-top:14px;">
                    📈 Tendance positive : +17 points en 6 mois. L'IA prévoit d'atteindre 90% d'ici septembre 2026 si le rythme actuel est maintenu.
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('modal-cnss-ia')">Fermer</button>
            <button class="btn btn-gold" onclick="showToast('Rapport IA exporté!','success'); closeModal('modal-cnss-ia')">📥 Exporter rapport IA</button>
        </div>
    </div>
</div>




<style>
/* ══ IA STRIP ══ */
.cnss-ia-strip {
    display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;
    background: linear-gradient(90deg, var(--bg2), var(--gold-glow));
    border: 1px solid rgba(201,168,76,.2); border-radius: var(--radius);
    padding: 12px 20px; margin-bottom: 18px;
}
.cnss-ia-left { display:flex; align-items:center; gap:12px; flex:1; min-width:0; }
.cnss-ia-badge {
    display:inline-flex; align-items:center; gap:6px;
    font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.5px;
    color:var(--gold); background:var(--gold-dim); padding:4px 11px;
    border-radius:20px; border:1px solid rgba(201,168,76,.25); flex-shrink:0;
}
.cnss-pulse {
    width:6px; height:6px; border-radius:50%; background:var(--gold);
    flex-shrink:0; display:inline-block;
    animation: cnss-blink 1.6s infinite;
}
.cnss-pulse-blue  { background:var(--blue); }
@keyframes cnss-blink { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.4;transform:scale(.7)} }
.cnss-ia-msg { font-size:12.5px; color:var(--text2); }
.cnss-ia-msg strong { color:var(--text); }
.cnss-ia-actions { display:flex; gap:8px; flex-shrink:0; }

/* ══ RADAR BANNER ══ */
.cnss-radar-banner {
    position:relative; display:flex; align-items:center; gap:28px;
    background:var(--bg2); border:1px solid var(--border);
    border-radius:var(--radius); padding:22px 28px; margin-bottom:20px; overflow:hidden;
}
.cnss-radar-glow {
    position:absolute; inset:0;
    background:radial-gradient(ellipse 60% 100% at 90% 50%, rgba(201,168,76,0.06) 0%, transparent 70%);
    pointer-events:none;
}
.cnss-radar-content { flex:1; z-index:1; }
.cnss-radar-title {
    display:flex; align-items:center; gap:8px;
    font-size:14px; font-weight:800; color:var(--text); margin-bottom:4px;
}
.cnss-radar-icon { font-size:18px; }
.cnss-radar-subtitle { font-size:12px; color:var(--text3); margin-bottom:16px; }
.cnss-risk-cards { display:flex; gap:10px; flex-wrap:wrap; }
.cnss-risk-card {
    padding:12px 16px; border-radius:var(--radius); border:1px solid var(--border);
    text-align:center; min-width:90px; cursor:pointer; transition:all .2s;
}
.cnss-risk-card:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,0,0,.3); }
.cnss-risk-red    { background:var(--red-dim);    border-color:rgba(248,113,113,.3); }
.cnss-risk-amber  { background:var(--amber-dim);  border-color:rgba(251,191,36,.3); }
.cnss-risk-blue   { background:var(--blue-dim);   border-color:rgba(96,165,250,.2); }
.cnss-risk-purple { background:var(--purple-dim); border-color:rgba(167,139,250,.2); }
.cnss-risk-val { font-size:24px; font-weight:900; font-family:var(--font-mono); color:var(--text); }
.cnss-risk-lbl { font-size:11px; color:var(--text3); margin-top:2px; }
.cnss-risk-ia  { font-size:10px; font-weight:700; color:var(--text3); margin-top:4px; }
.cnss-radar-visual { flex-shrink:0; z-index:1; }
.cnss-gauge { display:flex; flex-direction:column; align-items:center; gap:2px; }
.cnss-gauge-val { font-size:22px; font-weight:900; font-family:var(--font-mono); color:var(--text); }
.cnss-gauge-lbl { font-size:10.5px; color:var(--text3); }

/* ══ TABS ══ */
.cnss-tabs-row {
    display:flex; gap:4px; margin-bottom:16px;
    border-bottom:1px solid var(--border); padding-bottom:0; flex-wrap:wrap;
}
.cnss-tab {
    display:inline-flex; align-items:center; gap:7px;
    padding:10px 14px; border:none; background:transparent;
    color:var(--text2); font-size:12.5px; font-weight:500; font-family:var(--font-body);
    cursor:pointer; border-bottom:2px solid transparent; margin-bottom:-1px;
    transition:all .18s; border-radius:var(--radius-sm) var(--radius-sm) 0 0;
}
.cnss-tab:hover { color:var(--text); background:var(--bg3); }
.cnss-tab.active { color:var(--gold); border-bottom-color:var(--gold); }
.cnss-tab-count {
    font-size:10px; font-family:var(--font-mono); font-weight:700;
    padding:2px 7px; border-radius:10px; background:var(--bg4); color:var(--text3);
}
.cnss-tab.active .cnss-tab-count { background:var(--gold-dim); color:var(--gold); }
.cnss-tc-green { background:var(--green-dim)!important; color:var(--green)!important; }
.cnss-tc-amber { background:var(--amber-dim)!important; color:var(--amber)!important; }
.cnss-tc-red   { background:var(--red-dim)!important;   color:var(--red)!important; }
.cnss-tc-blue  { background:var(--blue-dim)!important;  color:var(--blue)!important; }
.cnss-tab-panel { display:none; }
.cnss-tab-panel.active { display:block; }

/* ══ TABLE CELLS ══ */
.cnss-artist-cell { display:flex; align-items:center; gap:10px; }
.cnss-av {
    width:36px; height:36px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:11px; font-weight:800; flex-shrink:0;
    background:linear-gradient(135deg, var(--gold), #a07830); color:#111;
}
.cnss-av-green  { background:linear-gradient(135deg, var(--green), #1e7e34); color:#fff; }
.cnss-av-teal   { background:linear-gradient(135deg, var(--teal), #1a8f80); color:#fff; }
.cnss-av-amber  { background:linear-gradient(135deg, var(--amber), #c97a10); color:#111; }
.cnss-av-red    { background:linear-gradient(135deg, var(--red), #c0392b); color:#fff; }
.cnss-av-blue   { background:linear-gradient(135deg, var(--blue), #1a5fa8); color:#fff; }
.cnss-artist-name { font-size:13px; font-weight:700; color:var(--text); }
.cnss-artist-sub  { font-size:10.5px; color:var(--text3); font-family:var(--font-mono); margin-top:1px; }
.cnss-code { background:var(--bg3); padding:2px 7px; border-radius:4px; font-size:10.5px; font-family:var(--font-mono); color:var(--text2); }
.cnss-code-lg { font-size:14px; font-weight:800; font-family:var(--font-mono); color:var(--text); }
.cnss-exp-cell { display:flex; flex-direction:column; gap:2px; font-size:12px; }
.cnss-days-left { font-size:10px; font-family:var(--font-mono); font-weight:700; }
.cnss-days-ok   { color:var(--green); }
.cnss-days-warn { color:var(--amber); }
.cnss-days-over { color:var(--red); }

/* Score bar */
.cnss-score-wrap { display:flex; align-items:center; gap:8px; min-width:110px; }
.cnss-score-bar { flex:1; height:5px; background:var(--bg4); border-radius:3px; overflow:hidden; }
.cnss-score-fill { height:100%; border-radius:3px; transition:width .5s; }
.cnss-score-num { font-size:11.5px; font-weight:800; font-family:var(--font-mono); width:26px; text-align:right; }

/* Urgency */
.cnss-urgency {
    display:inline-flex; align-items:center; gap:5px;
    font-size:11px; font-weight:700; padding:4px 9px; border-radius:20px;
}
.cnss-urg-critical { background:var(--red-dim);    color:var(--red); }
.cnss-urg-high     { background:var(--amber-dim);  color:var(--amber); }
.cnss-urg-medium   { background:var(--blue-dim);   color:var(--blue); }

/* IA inline badge */
.cnss-ia-inline-badge {
    display:inline-flex; align-items:center; gap:6px;
    font-size:10.5px; font-weight:700; color:var(--gold);
    background:var(--gold-dim); padding:4px 10px; border-radius:20px;
    border:1px solid rgba(201,168,76,.2);
}

/* Pending */
.cnss-pending-row {
    display:flex; align-items:center; gap:14px;
    padding:12px 14px; background:var(--bg3);
    border-radius:var(--radius-sm); border:1px solid var(--border);
}
.cnss-pending-progress { flex:1; min-width:120px; }
.cnss-prog-bar { height:5px; background:var(--bg4); border-radius:3px; overflow:hidden; }
.cnss-prog-fill { height:100%; border-radius:3px; transition:width .5s; }

/* Alerts */
.cnss-alert-row {
    display:flex; align-items:center; gap:12px;
    padding:12px 14px; border-radius:var(--radius-sm);
    border:1px solid var(--border); background:var(--bg3); transition:border-color .15s;
}
.cnss-alert-row:hover { border-color:var(--border2); }
.cnss-alert-red   { border-color:rgba(248,113,113,.25); background:rgba(248,113,113,.04); }
.cnss-alert-amber { border-color:rgba(251,191,36,.25);  background:rgba(251,191,36,.04); }
.cnss-alert-icon { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0; }
.cnss-alert-info { flex:1; }
.cnss-alert-title { font-size:13px; font-weight:700; color:var(--text); }
.cnss-alert-meta  { font-size:11px; color:var(--text3); margin-top:2px; }

/* ══ MODAL DETAIL ══ */
.cnss-detail-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
.cnss-detail-field {
    display:flex; flex-direction:column; gap:4px;
    padding:10px 12px; background:var(--bg3);
    border-radius:var(--radius-sm); border:1px solid var(--border);
}
.cnss-detail-lbl { font-size:10px; color:var(--text3); text-transform:uppercase; letter-spacing:.5px; }
.cnss-ia-reco {
    font-size:12.5px; color:var(--text2); line-height:1.65;
    padding:12px 14px; background:var(--gold-dim);
    border-left:3px solid var(--gold); border-radius:var(--radius-sm);
}

/* ══ VERIFY MODAL ══ */
.cnss-verify-ia-hint {
    display:flex; align-items:flex-start; gap:12px;
    padding:12px 14px; background:var(--gold-dim);
    border:1px solid rgba(201,168,76,.25); border-radius:8px; margin-bottom:16px;
}

/* ══ IA MODAL TABS ══ */
.cnss-ia-tabs { display:flex; gap:4px; margin-bottom:14px; border-bottom:1px solid var(--border); padding-bottom:0; }
.cnss-ia-tab {
    padding:8px 14px; border:none; background:transparent;
    color:var(--text2); font-size:12px; font-weight:500; font-family:var(--font-body);
    cursor:pointer; border-bottom:2px solid transparent; margin-bottom:-1px;
    transition:all .18s;
}
.cnss-ia-tab:hover { color:var(--text); background:var(--bg3); }
.cnss-ia-tab.active { color:var(--gold); border-bottom-color:var(--gold); }
.cnss-iat-panel { display:none; flex-direction:column; gap:10px; }
.cnss-iat-panel.active { display:flex; }

.cnss-ia-card {
    display:flex; align-items:flex-start; gap:12px;
    padding:12px 14px; border-radius:var(--radius-sm); border:1px solid var(--border);
}
.cnss-ia-red    { background:var(--red-dim);   border-color:rgba(248,113,113,.2); }
.cnss-ia-amber  { background:var(--amber-dim); border-color:rgba(251,191,36,.2); }
.cnss-ia-blue   { background:var(--blue-dim);  border-color:rgba(96,165,250,.2); }
.cnss-ia-card-icon { font-size:20px; flex-shrink:0; margin-top:2px; }
.cnss-ia-card-title { font-size:13px; font-weight:700; color:var(--text); margin-bottom:4px; }
.cnss-ia-card-desc  { font-size:12px; color:var(--text2); line-height:1.6; }

/* Action steps */
.cnss-action-step {
    display:flex; align-items:center; gap:14px;
    padding:12px 14px; background:var(--bg3);
    border-radius:var(--radius-sm); border:1px solid var(--border);
}
.cnss-step-num {
    width:32px; height:32px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:14px; font-weight:900; flex-shrink:0;
}
.cnss-step-red    { background:var(--red-dim);   color:var(--red); }
.cnss-step-amber  { background:var(--amber-dim); color:var(--amber); }
.cnss-step-blue   { background:var(--blue-dim);  color:var(--blue); }
.cnss-step-body { flex:1; }
.cnss-step-title { font-size:13px; font-weight:700; color:var(--text); }
.cnss-step-desc  { font-size:11.5px; color:var(--text3); margin-top:3px; }
</style>



<script>
// ══ TABS ══
window.cnssTab = function (tabId, el) {
    document.querySelectorAll('.cnss-tab-panel').forEach(p => { p.style.display='none'; p.classList.remove('active'); });
    document.querySelectorAll('.cnss-tab').forEach(b => b.classList.remove('active'));
    const panel = document.getElementById(tabId);
    if (panel) { panel.style.display='block'; panel.classList.add('active'); }
    if (el) el.classList.add('active');
}

// ══ IA MODAL TABS ══
window.cnssIaTab = function (el, panelId) {
    el.closest('.cnss-ia-tabs').querySelectorAll('.cnss-ia-tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    document.querySelectorAll('.cnss-iat-panel').forEach(p => { p.style.display='none'; p.classList.remove('active'); });
    const p = document.getElementById(panelId);
    if (p) { p.style.display='flex'; p.classList.add('active'); }
}

// ══ FILTER ══
window.cnssApplyFilters = function () {
    const search = document.getElementById('cnssSearch').value.toLowerCase();
    const region = document.getElementById('cnssRegion').value;
    const type   = document.getElementById('cnssType').value;
    let count = 0;
    document.querySelectorAll('#cnssTableAll tr[data-name]').forEach(row => {
        const name   = (row.dataset.name   || '').toLowerCase();
        const rg     = row.dataset.region || '';
        const tp     = row.dataset.type   || '';
        const match  = (!search || name.includes(search))
                    && (!region || rg === region)
                    && (!type   || tp === type);
        row.style.display = match ? '' : 'none';
        if (match) count++;
    });
    const el = document.getElementById('cnssAllCount');
    if (el) el.textContent = count + ' artiste' + (count!==1?'s':'') + ' trouvé' + (count!==1?'s':'');
}

// ══ REAL-TIME CNSS VALIDATION ══
window.cnssRealTimeValidate = function (val) {
    const el = document.getElementById('cnssValidateResult');
    if (!val || val.length < 3) { el.style.display='none'; return; }
    el.style.display = '';
    if (/^\d{9}$/.test(val)) {
        el.style.background = 'var(--green-dim)';
        el.style.borderLeft = '3px solid var(--green)';
        el.style.color = 'var(--green)';
        el.textContent = '✅ Format valide — Vérification CNSS en cours…';
    } else if (val.length >= 6) {
        el.style.background = 'var(--amber-dim)';
        el.style.borderLeft = '3px solid var(--amber)';
        el.style.color = 'var(--amber)';
        el.textContent = '⚠ Format incomplet — Un numéro CNSS contient 9 chiffres.';
    } else {
        el.style.background = 'var(--bg3)';
        el.style.borderLeft = '3px solid var(--border2)';
        el.style.color = 'var(--text3)';
        el.textContent = '🔍 Saisie en cours…';
    }
}

// ══ RUN VERIFY ══
window.cnssRunVerify = function () {
    const artiste = document.getElementById('vArtiste').value.trim();
    const cnss    = document.getElementById('vCnss').value.trim();
    if (!artiste || !cnss) { showToast('Veuillez remplir les champs requis', 'error'); return; }
    const el = document.getElementById('cnssValidateResult');
    el.style.display = '';
    el.style.background = 'var(--blue-dim)';
    el.style.borderLeft = '3px solid var(--blue)';
    el.style.color = 'var(--blue)';
    el.textContent = '🔄 L\'IA consulte la base CNSS…';
    setTimeout(() => {
        if (/^\d{9}$/.test(cnss)) {
            el.style.background = 'var(--green-dim)';
            el.style.borderLeft = '3px solid var(--green)';
            el.style.color = 'var(--green)';
            el.textContent = '✅ Affiliation vérifiée — ' + artiste + ' est affilié(e) et actif(ve).';
            setTimeout(() => { closeModal('modal-cnss-verify'); showToast('✅ Affiliation de ' + artiste + ' confirmée!', 'success'); }, 1500);
        } else {
            el.style.background = 'var(--red-dim)';
            el.style.borderLeft = '3px solid var(--red)';
            el.style.color = 'var(--red)';
            el.textContent = '❌ Numéro CNSS invalide ou non trouvé dans la base.';
        }
    }, 1800);
}

// ══ SHOW DETAIL ══
window.cnssShowDetail = function (name, cnss, type, region, status, exp, score) {
    document.getElementById('detailArtistName').textContent = name;
    document.getElementById('dCnss').textContent    = cnss;
    document.getElementById('dType').textContent    = type;
    document.getElementById('dRegion').textContent  = region;
    document.getElementById('dExp').textContent     = exp;
    const sc = parseInt(score);
    const scoreColor = sc >= 80 ? 'var(--green)' : sc >= 60 ? 'var(--amber)' : 'var(--red)';
    document.getElementById('dScore').textContent = score + '/100';
    document.getElementById('dScore').style.color = scoreColor;

    const statusMap = { 'Actif':'green', 'Expire bientôt':'gold', 'Expirée':'red', 'Non affilié':'red' };
    document.getElementById('dStatus').innerHTML = `<span class="badge ${statusMap[status]||'gray'}">${status}</span>`;

    const badgeMap = { 'Actif':'green', 'Expire bientôt':'gold', 'Expirée':'red', 'Non affilié':'red' };
    document.getElementById('detailArtistBadges').innerHTML = `<span class="badge ${badgeMap[status]||'gray'}">${status}</span> <span class="badge gray">${type}</span>`;

    // IA Reco
    let reco = '';
    if (sc >= 80) reco = `✅ Dossier en ordre. L'artiste ${name} est conforme. Aucune action requise avant l'expiration (${exp}).`;
    else if (sc >= 60) reco = `⚠️ Attention requise. L'affiliation de ${name} arrive à expiration. L'IA recommande un renouvellement préventif dans les 30 prochains jours.`;
    else reco = `🚨 Situation critique pour ${name}. L'IA détecte un risque légal élevé. Traitement prioritaire requis — initier le renouvellement immédiatement.`;
    document.getElementById('detailIaReco').textContent = reco;

    // IA Actions
    const actions = sc >= 80
        ? ['<span style="color:var(--green);font-size:12.5px;">✓ Aucune action requise — Dossier conforme</span>']
        : sc >= 60
        ? ['<div class="cnss-urgency cnss-urg-medium">⚠ Envoyer rappel de renouvellement</div>','<div class="cnss-urgency cnss-urg-medium">📋 Préparer dossier de renouvellement</div>']
        : ['<div class="cnss-urgency cnss-urg-critical">🚨 Lancer renouvellement d\'urgence</div>','<div class="cnss-urgency cnss-urg-high">📧 Contacter l\'artiste immédiatement</div>','<div class="cnss-urgency cnss-urg-high">📋 Signaler au responsable CNSS</div>'];
    document.getElementById('detailIaActions').innerHTML = actions.join('');

    openModal('modal-cnss-detail');
}

// ══ BULK ACTIONS ══
window.cnssIaNotifyAll = function () {
    showToast('📨 L\'IA envoie 23 emails de rappel…', 'info');
    setTimeout(() => showToast('✅ 23 artistes notifiés automatiquement!', 'success'), 2000);
}
window.cnssIaBulkRenew = function () {
    showToast('🔄 Renouvellement groupé IA lancé pour 12 dossiers…', 'info');
    setTimeout(() => showToast('✅ 12 renouvellements traités en 1 clic!', 'success'), 2500);
}

// ══ IA MSG ROTATOR ══
const iaMsgs = [
    '🤖 <strong>48 affiliations expirées.</strong> L\'IA recommande un renouvellement groupé — 12 dossiers prêts.',
    '🤖 <strong>Alerte fraude :</strong> 7 numéros CNSS suspects détectés. Vérification croisée recommandée.',
    '🤖 <strong>Tendance positive :</strong> +17% de conformité en 6 mois. Objectif 90% atteignable en septembre 2026.',
    '🤖 <strong>Action urgente :</strong> Khaled Amri expire dans 32 jours — dossier de renouvellement à initier aujourd\'hui.',
];
let iaMsgIdx = 0;
setInterval(() => {
    iaMsgIdx = (iaMsgIdx + 1) % iaMsgs.length;
    const el = document.getElementById('cnssIaMsg');
    if (el) el.innerHTML = iaMsgs[iaMsgIdx];
}, 7000);

// ══ INIT ══
document.addEventListener('DOMContentLoaded', () => {
    // Animate gauge arc
    setTimeout(() => {
        const arc = document.getElementById('cnssGaugeArc');
        if (arc) arc.style.transition = 'stroke-dashoffset 1s ease';
    }, 300);
    // Init tab
    cnssTab('tab-all', document.querySelector('.cnss-tab[data-tab="tab-all"]'));
});
</script>

@endsection
