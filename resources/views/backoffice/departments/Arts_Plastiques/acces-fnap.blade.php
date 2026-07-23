@extends('shared.layouts.backoffice')

@section('title', 'Accès Fonds National - FNAP')
@section('breadcrumb', 'Accès Fonds National')

@section('content')

    {{-- ▸ SmartMatch Hero Banner --}}
    <div class="smartmatch-hero">
        <div class="sm-hero-glow"></div>
        <div class="sm-hero-content">
            <div class="sm-hero-badge">
                <span class="sm-pulse-dot"></span>
                IA Active — Scan en temps réel
            </div>
            <div class="sm-hero-title">
                <span class="sm-gradient-text">SmartMatch</span>
                <span class="sm-hero-sub">Opportunités en Temps Réel</span>
            </div>
            <p class="sm-hero-desc">
                Ton prochain projet te trouve tout seul.
                L'IA scanne en continu résidences, prêts, FNAP, spectacles, galeries et appels à projets
                — et pousse uniquement les opportunités à <strong>90 %+ de compatibilité</strong> avec chaque profil
                artiste.
            </p>
            <div class="sm-hero-stats">
                <div class="sm-stat">
                    <div class="sm-stat-val" id="sm-kpi-opps">247</div>
                    <div class="sm-stat-lbl">Opportunités scannées</div>
                </div>
                <div class="sm-stat-sep"></div>
                <div class="sm-stat">
                    <div class="sm-stat-val" id="sm-kpi-matches">63</div>
                    <div class="sm-stat-lbl">Matchs &ge;90%</div>
                </div>
                <div class="sm-stat-sep"></div>
                <div class="sm-stat">
                    <div class="sm-stat-val" id="sm-kpi-artists">18</div>
                    <div class="sm-stat-lbl">Artistes notifiés aujourd'hui</div>
                </div>
                <div class="sm-stat-sep"></div>
                <div class="sm-stat">
                    <div class="sm-stat-val">4 min</div>
                    <div class="sm-stat-lbl">Prochain scan</div>
                </div>
            </div>
        </div>
        <div class="sm-hero-visual">
            <div class="sm-radar">
                <div class="sm-radar-ring sm-r1"></div>
                <div class="sm-radar-ring sm-r2"></div>
                <div class="sm-radar-ring sm-r3"></div>
                <div class="sm-radar-sweep"></div>
                <div class="sm-radar-dot" style="top:28%; left:58%;" data-score="97"></div>
                <div class="sm-radar-dot sm-dot-amber" style="top:55%; left:72%;" data-score="91"></div>
                <div class="sm-radar-dot" style="top:65%; left:38%;" data-score="94"></div>
                <div class="sm-radar-dot sm-dot-teal" style="top:38%; left:25%;" data-score="88"></div>
                <div class="sm-radar-dot sm-dot-teal" style="top:72%; left:55%;" data-score="93"></div>
                <div class="sm-radar-center">🎯</div>
            </div>
        </div>
    </div>

    {{-- ▸ Onglets de mode --}}
    <div class="sm-tabs-row">
        <button class="sm-tab active" onclick="smSetTab(this, 'tab-opps')">
            🎯 Opportunités en cours
            <span class="sm-tab-count">12</span>
        </button>
        <button class="sm-tab" onclick="smSetTab(this, 'tab-artists')">
            🎨 Artistes par opportunité
            <span class="sm-tab-count">63</span>
        </button>
        <button class="sm-tab" onclick="smSetTab(this, 'tab-alerts')">
            🔔 Alertes poussées
            <span class="sm-tab-count sm-count-red">5</span>
        </button>
        <div style="margin-left:auto; display:flex; gap:8px; align-items:center;">
            <span class="sm-live-badge">
                <span class="sm-pulse-dot sm-dot-green"></span> Live
            </span>
            <button class="btn btn-gold btn-sm" onclick="smRunScan()">⚡ Lancer scan</button>
        </div>
    </div>

    {{-- ▸ TAB 1 : Opportunités --}}
    <div id="tab-opps" class="sm-tab-panel active">
        <div class="sm-opps-grid">

            {{-- Carte opportunité 1 --}}
            <div class="sm-opp-card sm-opp-hot" onclick="openModal('modal-sm-opp-1')">
                <div class="sm-opp-header">
                    <div class="sm-opp-type-badge sm-type-fnap">FNAP</div>
                    <div class="sm-opp-score">
                        <div class="sm-score-ring" data-score="97">
                            <svg viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="15.5" fill="none" stroke="var(--bg4)"
                                    stroke-width="3" />
                                <circle cx="18" cy="18" r="15.5" fill="none" stroke="var(--gold)"
                                    stroke-width="3" stroke-dasharray="97,100" stroke-dashoffset="25"
                                    stroke-linecap="round" />
                            </svg>
                            <span>97%</span>
                        </div>
                        <div class="sm-score-label">Fit Score</div>
                    </div>
                </div>
                <div class="sm-opp-title">Résidence de Création — Fondation Nationale</div>
                <div class="sm-opp-meta">
                    <span>📅 Clôture : 30/05/2024</span>
                    <span>💰 6 000 €</span>
                    <span>🏛️ Paris</span>
                </div>
                <div class="sm-opp-tags">
                    <span class="sm-tag">Peinture</span>
                    <span class="sm-tag">Sculpture</span>
                    <span class="sm-tag">Art contemporain</span>
                </div>
                <div class="sm-opp-footer">
                    <div class="sm-match-count">
                        <div class="sm-artist-avatars">
                            <div class="sm-av">IR</div>
                            <div class="sm-av sm-av2">VH</div>
                            <div class="sm-av sm-av3">SG</div>
                        </div>
                        <span>3 artistes matchés</span>
                    </div>
                    <span class="badge gold">Hot 🔥</span>
                </div>
            </div>

            {{-- Carte opportunité 2 --}}
            <div class="sm-opp-card" onclick="openModal('modal-sm-opp-2')">
                <div class="sm-opp-header">
                    <div class="sm-opp-type-badge sm-type-residence">Résidence</div>
                    <div class="sm-opp-score">
                        <div class="sm-score-ring" data-score="94">
                            <svg viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="15.5" fill="none" stroke="var(--bg4)"
                                    stroke-width="3" />
                                <circle cx="18" cy="18" r="15.5" fill="none" stroke="var(--teal)"
                                    stroke-width="3" stroke-dasharray="94,100" stroke-dashoffset="25"
                                    stroke-linecap="round" />
                            </svg>
                            <span>94%</span>
                        </div>
                        <div class="sm-score-label">Fit Score</div>
                    </div>
                </div>
                <div class="sm-opp-title">Appel à projets — Galeries Nationales Contemporaines</div>
                <div class="sm-opp-meta">
                    <span>📅 Clôture : 15/06/2024</span>
                    <span>💰 3 500 €</span>
                    <span>🏛️ Lyon</span>
                </div>
                <div class="sm-opp-tags">
                    <span class="sm-tag">Photographie</span>
                    <span class="sm-tag">Installation</span>
                </div>
                <div class="sm-opp-footer">
                    <div class="sm-match-count">
                        <div class="sm-artist-avatars">
                            <div class="sm-av">MD</div>
                            <div class="sm-av sm-av2">FA</div>
                        </div>
                        <span>2 artistes matchés</span>
                    </div>
                    <span class="badge green">Nouveau</span>
                </div>
            </div>

            {{-- Carte opportunité 3 --}}
            <div class="sm-opp-card" onclick="openModal('modal-sm-opp-3')">
                <div class="sm-opp-header">
                    <div class="sm-opp-type-badge sm-type-pret">Prêt d'œuvre</div>
                    <div class="sm-opp-score">
                        <div class="sm-score-ring" data-score="91">
                            <svg viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="15.5" fill="none" stroke="var(--bg4)"
                                    stroke-width="3" />
                                <circle cx="18" cy="18" r="15.5" fill="none" stroke="var(--purple)"
                                    stroke-width="3" stroke-dasharray="91,100" stroke-dashoffset="25"
                                    stroke-linecap="round" />
                            </svg>
                            <span>91%</span>
                        </div>
                        <div class="sm-score-label">Fit Score</div>
                    </div>
                </div>
                <div class="sm-opp-title">Prêt d'œuvre — Musée des Beaux-Arts Régional</div>
                <div class="sm-opp-meta">
                    <span>📅 Clôture : 10/07/2024</span>
                    <span>💰 Prêt 12 mois</span>
                    <span>🏛️ Bordeaux</span>
                </div>
                <div class="sm-opp-tags">
                    <span class="sm-tag">Peinture</span>
                    <span class="sm-tag">Dessin</span>
                </div>
                <div class="sm-opp-footer">
                    <div class="sm-match-count">
                        <div class="sm-artist-avatars">
                            <div class="sm-av">VH</div>
                        </div>
                        <span>1 artiste matchée</span>
                    </div>
                    <span class="badge blue">En cours</span>
                </div>
            </div>

            {{-- Carte opportunité 4 --}}
            <div class="sm-opp-card" onclick="openModal('modal-sm-opp-4')">
                <div class="sm-opp-header">
                    <div class="sm-opp-type-badge sm-type-spectacle">Spectacle</div>
                    <div class="sm-opp-score">
                        <div class="sm-score-ring" data-score="93">
                            <svg viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="15.5" fill="none" stroke="var(--bg4)"
                                    stroke-width="3" />
                                <circle cx="18" cy="18" r="15.5" fill="none" stroke="var(--amber)"
                                    stroke-width="3" stroke-dasharray="93,100" stroke-dashoffset="25"
                                    stroke-linecap="round" />
                            </svg>
                            <span>93%</span>
                        </div>
                        <div class="sm-score-label">Fit Score</div>
                    </div>
                </div>
                <div class="sm-opp-title">Festival Arts Vivants — Scène nationale de Montpellier</div>
                <div class="sm-opp-meta">
                    <span>📅 Clôture : 20/05/2024</span>
                    <span>💰 8 000 €</span>
                    <span>🏛️ Montpellier</span>
                </div>
                <div class="sm-opp-tags">
                    <span class="sm-tag">Performance</span>
                    <span class="sm-tag">Arts vivants</span>
                </div>
                <div class="sm-opp-footer">
                    <div class="sm-match-count">
                        <div class="sm-artist-avatars">
                            <div class="sm-av">SG</div>
                            <div class="sm-av sm-av2">IR</div>
                        </div>
                        <span>2 artistes matchées</span>
                    </div>
                    <span class="badge gold">Urgent ⏰</span>
                </div>
            </div>

        </div>
    </div>

    {{-- ▸ TAB 2 : Artistes par opportunité --}}
    <div id="tab-artists" class="sm-tab-panel">
        <div class="panel">
            <div class="panel-head">
                <div>
                    <div class="panel-title">Artistes les plus pertinents — Résidence de Création FNAP</div>
                    <div class="panel-sub">Classés par score de fit IA • Opportunité sélectionnée : <strong>Résidence
                            Fondation Nationale</strong></div>
                </div>
                <select class="form-select" style="width:260px;" onchange="smFilterOpp(this)">
                    <option>Résidence Fondation Nationale (97%)</option>
                    <option>Appel Galeries Contemporaines (94%)</option>
                    <option>Prêt Musée Régional (91%)</option>
                    <option>Festival Arts Vivants (93%)</option>
                </select>
            </div>
            <div class="panel-body no-pad">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Rang</th>
                                <th>Artiste</th>
                                <th>Discipline</th>
                                <th>Score Fit</th>
                                <th>Points forts IA</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="sm-artist-row sm-rank-1">
                                <td>
                                    <div class="sm-rank-badge sm-rank-gold">🥇 1</div>
                                </td>
                                <td>
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <div class="sm-av sm-av-lg">IR</div>
                                        <div>
                                            <div style="font-weight:600; font-size:13px;">Isabelle Renaud</div>
                                            <div style="font-size:11px; color:var(--text3);">Musée d'Art Moderne</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="sm-tag">Peinture</span></td>
                                <td>
                                    <div class="sm-fit-bar-wrap">
                                        <div class="sm-fit-bar">
                                            <div class="sm-fit-fill sm-fit-gold" style="width:97%"></div>
                                        </div>
                                        <span class="sm-fit-pct sm-pct-gold">97%</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="sm-reasons">
                                        <span class="sm-reason">✓ Style contemporain</span>
                                        <span class="sm-reason">✓ 8 expositions</span>
                                        <span class="sm-reason">✓ Région éligible</span>
                                    </div>
                                </td>
                                <td><span class="badge blue">Notifiée</span></td>
                                <td>
                                    <div class="row-actions">
                                        <button class="btn btn-ghost btn-sm" onclick="openModal('modal-sm-artist-1')">👁️
                                            Profil</button>
                                        <button class="btn btn-gold btn-sm"
                                            onclick="showToast('Notification envoyée à Isabelle!', 'success')">📨
                                            Notifier</button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="sm-artist-row">
                                <td>
                                    <div class="sm-rank-badge sm-rank-silver">🥈 2</div>
                                </td>
                                <td>
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <div class="sm-av sm-av-lg sm-av-teal">VH</div>
                                        <div>
                                            <div style="font-weight:600; font-size:13px;">Véronique Hubert</div>
                                            <div style="font-size:11px; color:var(--text3);">Fond Régional d'Art</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="sm-tag">Sculpture</span></td>
                                <td>
                                    <div class="sm-fit-bar-wrap">
                                        <div class="sm-fit-bar">
                                            <div class="sm-fit-fill sm-fit-teal" style="width:94%"></div>
                                        </div>
                                        <span class="sm-fit-pct" style="color:var(--teal);">94%</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="sm-reasons">
                                        <span class="sm-reason">✓ Sculpture 3D</span>
                                        <span class="sm-reason">✓ Lauréate 2022</span>
                                    </div>
                                </td>
                                <td><span class="badge green">Approuvée</span></td>
                                <td>
                                    <div class="row-actions">
                                        <button class="btn btn-ghost btn-sm" onclick="openModal('modal-sm-artist-2')">👁️
                                            Profil</button>
                                        <button class="btn btn-gold btn-sm"
                                            onclick="showToast('Notification envoyée à Véronique!', 'success')">📨
                                            Notifier</button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="sm-artist-row">
                                <td>
                                    <div class="sm-rank-badge sm-rank-bronze">🥉 3</div>
                                </td>
                                <td>
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <div class="sm-av sm-av-lg sm-av-purple">SG</div>
                                        <div>
                                            <div style="font-weight:600; font-size:13px;">Sandrine Gallet</div>
                                            <div style="font-size:11px; color:var(--text3);">Centre Culturel Local</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="sm-tag">Art contemporain</span></td>
                                <td>
                                    <div class="sm-fit-bar-wrap">
                                        <div class="sm-fit-bar">
                                            <div class="sm-fit-fill sm-fit-purple" style="width:91%"></div>
                                        </div>
                                        <span class="sm-fit-pct" style="color:var(--purple);">91%</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="sm-reasons">
                                        <span class="sm-reason">✓ Exposition 2024</span>
                                        <span class="sm-reason">✓ Dossier complet</span>
                                    </div>
                                </td>
                                <td><span class="badge gold">En attente</span></td>
                                <td>
                                    <div class="row-actions">
                                        <button class="btn btn-ghost btn-sm" onclick="openModal('modal-sm-artist-3')">👁️
                                            Profil</button>
                                        <button class="btn btn-gold btn-sm"
                                            onclick="showToast('Notification envoyée à Sandrine!', 'success')">📨
                                            Notifier</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ▸ TAB 3 : Alertes poussées --}}
    <div id="tab-alerts" class="sm-tab-panel">
        <div class="panel">
            <div class="panel-head">
                <div>
                    <div class="panel-title">Alertes SmartMatch envoyées</div>
                    <div class="panel-sub">Historique des notifications IA poussées aux artistes</div>
                </div>
                <button class="btn btn-outline btn-sm" onclick="showToast('Alertes exportées', 'info')">📥
                    Exporter</button>
            </div>
            <div class="panel-body no-pad">
                <div class="sm-alert-list">

                    <div class="sm-alert-row sm-alert-new">
                        <div class="sm-alert-icon" style="background:var(--gold-dim); color:var(--gold);">⚡</div>
                        <div class="sm-alert-info">
                            <div class="sm-alert-title">Match 97% — Isabelle Renaud × Résidence Fondation Nationale</div>
                            <div class="sm-alert-meta">Envoyée par email · il y a 12 min · Score précédent : 84%</div>
                        </div>
                        <span class="badge green">Lue</span>
                        <button class="btn btn-ghost btn-sm" onclick="openModal('modal-sm-alert-1')">Voir</button>
                    </div>

                    <div class="sm-alert-row sm-alert-new">
                        <div class="sm-alert-icon" style="background:var(--teal-dim); color:var(--teal);">🎯</div>
                        <div class="sm-alert-info">
                            <div class="sm-alert-title">Match 94% — Véronique Hubert × Appel Galeries Contemporaines</div>
                            <div class="sm-alert-meta">Envoyée par SMS + email · il y a 1 h · Score précédent : 78%</div>
                        </div>
                        <span class="badge blue">Envoyée</span>
                        <button class="btn btn-ghost btn-sm">Voir</button>
                    </div>

                    <div class="sm-alert-row">
                        <div class="sm-alert-icon" style="background:var(--red-dim); color:var(--red);">🔔</div>
                        <div class="sm-alert-info">
                            <div class="sm-alert-title">Match 93% — Sandrine Gallet × Festival Arts Vivants (URGENT)</div>
                            <div class="sm-alert-meta">Envoyée par email · il y a 3 h · Clôture dans 8 jours</div>
                        </div>
                        <span class="badge red">Urgente</span>
                        <button class="btn btn-ghost btn-sm">Voir</button>
                    </div>

                    <div class="sm-alert-row">
                        <div class="sm-alert-icon" style="background:var(--purple-dim); color:var(--purple);">🎨</div>
                        <div class="sm-alert-info">
                            <div class="sm-alert-title">Match 91% — Marc Delorme × Prêt d'œuvre Musée Régional</div>
                            <div class="sm-alert-meta">Envoyée par email · il y a 6 h</div>
                        </div>
                        <span class="badge blue">Envoyée</span>
                        <button class="btn btn-ghost btn-sm">Voir</button>
                    </div>

                    <div class="sm-alert-row">
                        <div class="sm-alert-icon" style="background:var(--amber-dim); color:var(--amber);">📌</div>
                        <div class="sm-alert-info">
                            <div class="sm-alert-title">Match 90% — Frédéric Arnould × Résidence Fondation Nationale</div>
                            <div class="sm-alert-meta">Non envoyée — Dossier incomplet détecté par IA</div>
                        </div>
                        <span class="badge gold">Bloquée</span>
                        <button class="btn btn-ghost btn-sm">Voir</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- ══════ MODALS SmartMatch ══════ --}}

    {{-- Modal Opportunité 1 --}}
    <div id="modal-sm-opp-1" class="modal">
        <div class="modal-content" style="max-width:620px;">
            <div class="modal-header">
                <div class="modal-title">🏛️ Résidence de Création — Fondation Nationale</div>
                <button class="modal-close" onclick="closeModal('modal-sm-opp-1')">×</button>
            </div>
            <div class="modal-body">
                <div class="sm-modal-score-banner">
                    <div class="sm-modal-score-ring">
                        <svg viewBox="0 0 80 80" width="80" height="80">
                            <circle cx="40" cy="40" r="34" fill="none" stroke="var(--bg4)"
                                stroke-width="6" />
                            <circle cx="40" cy="40" r="34" fill="none" stroke="var(--gold)"
                                stroke-width="6" stroke-dasharray="213,220" stroke-dashoffset="55"
                                stroke-linecap="round" />
                        </svg>
                        <div class="sm-modal-score-val">97%</div>
                    </div>
                    <div>
                        <div style="font-size:18px; font-weight:800; color:var(--text);">Score de compatibilité</div>
                        <div style="font-size:13px; color:var(--text2); margin-top:4px;">L'IA a analysé 14 critères •
                            Dernière mise à jour il y a 4 min</div>
                        <div style="display:flex; gap:8px; margin-top:10px; flex-wrap:wrap;">
                            <span class="badge gold">FNAP</span>
                            <span class="badge green">Hot 🔥</span>
                            <span class="badge blue">3 artistes matchés</span>
                        </div>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:18px;">
                    <div class="form-group">
                        <label class="form-label">Type</label>
                        <input class="form-input" value="Résidence de création" disabled>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Dotation</label>
                        <input class="form-input" value="6 000 €" disabled>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date de clôture</label>
                        <input class="form-input" value="30/05/2024" disabled>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Localisation</label>
                        <input class="form-input" value="Paris, Île-de-France" disabled>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Critères IA analysés</label>
                    <div class="sm-criteria-list">
                        <div class="sm-criterion match"><span>✓</span> Discipline artistique correspondante</div>
                        <div class="sm-criterion match"><span>✓</span> Tranche d'âge éligible</div>
                        <div class="sm-criterion match"><span>✓</span> Région de résidence</div>
                        <div class="sm-criterion match"><span>✓</span> Dossier complet</div>
                        <div class="sm-criterion match"><span>✓</span> Expositions internationales</div>
                        <div class="sm-criterion partial"><span>~</span> Nationalité (priorité française)</div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Artistes recommandés par l'IA</label>
                    <div class="sm-rec-artists">
                        <div class="sm-rec-artist" onclick="closeModal('modal-sm-opp-1'); openModal('modal-sm-artist-1')">
                            <div class="sm-av">IR</div>
                            <div class="sm-rec-info">
                                <div class="sm-rec-name">Isabelle Renaud</div>
                                <div class="sm-rec-score sm-pct-gold">97%</div>
                            </div>
                            <button class="btn btn-gold btn-sm"
                                onclick="event.stopPropagation(); showToast('Notification envoyée!', 'success')">📨</button>
                        </div>
                        <div class="sm-rec-artist">
                            <div class="sm-av sm-av-teal">VH</div>
                            <div class="sm-rec-info">
                                <div class="sm-rec-name">Véronique Hubert</div>
                                <div class="sm-rec-score" style="color:var(--teal);">94%</div>
                            </div>
                            <button class="btn btn-gold btn-sm"
                                onclick="event.stopPropagation(); showToast('Notification envoyée!', 'success')">📨</button>
                        </div>
                        <div class="sm-rec-artist">
                            <div class="sm-av sm-av-purple">SG</div>
                            <div class="sm-rec-info">
                                <div class="sm-rec-name">Sandrine Gallet</div>
                                <div class="sm-rec-score" style="color:var(--purple);">91%</div>
                            </div>
                            <button class="btn btn-gold btn-sm"
                                onclick="event.stopPropagation(); showToast('Notification envoyée!', 'success')">📨</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('modal-sm-opp-1')">Fermer</button>
                <button class="btn btn-gold"
                    onclick="showToast('Notifications envoyées à 3 artistes!', 'success'); closeModal('modal-sm-opp-1')">📨
                    Notifier tous (3)</button>
            </div>
        </div>
    </div>

    {{-- Modals opportunités 2, 3, 4 (structure simplifiée) --}}
    @foreach ([2, 3, 4] as $oi)
        <div id="modal-sm-opp-{{ $oi }}" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">Détails Opportunité</div>
                    <button class="modal-close" onclick="closeModal('modal-sm-opp-{{ $oi }}')">×</button>
                </div>
                <div class="modal-body">
                    <p style="color:var(--text2); font-size:13px;">Informations complètes sur l'opportunité et les artistes
                        matchés par l'IA.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline"
                        onclick="closeModal('modal-sm-opp-{{ $oi }}')">Fermer</button>
                    <button class="btn btn-gold" onclick="showToast('Notifications envoyées!', 'success')">📨 Notifier les
                        artistes</button>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Modal Profil Artiste 1 --}}
    <div id="modal-sm-artist-1" class="modal">
        <div class="modal-content" style="max-width:560px;">
            <div class="modal-header">
                <div class="modal-title">🎨 Profil Artiste — Isabelle Renaud</div>
                <button class="modal-close" onclick="closeModal('modal-sm-artist-1')">×</button>
            </div>
            <div class="modal-body">
                <div class="sm-artist-profile-header">
                    <div class="sm-av sm-av-xl">IR</div>
                    <div>
                        <div style="font-size:16px; font-weight:800; color:var(--text);">Isabelle Renaud</div>
                        <div style="font-size:12px; color:var(--text3); margin-top:2px;">Peintre contemporaine · Musée
                            d'Art Moderne</div>
                        <div style="display:flex; gap:6px; margin-top:8px; flex-wrap:wrap;">
                            <span class="sm-tag">Peinture</span>
                            <span class="sm-tag">Sculpture</span>
                            <span class="sm-tag">Art contemporain</span>
                        </div>
                    </div>
                    <div class="sm-av-score-badge">97%</div>
                </div>

                <div class="form-group" style="margin-top:18px;">
                    <label class="form-label">Compatibilité avec l'opportunité sélectionnée</label>
                    <div class="confidence-bar-row">
                        <div class="cb-label">Discipline artistique</div>
                        <div class="cb-bar">
                            <div class="cb-fill" style="width:100%; background:var(--gold);"></div>
                        </div>
                        <div class="cb-pct">100%</div>
                    </div>
                    <div class="confidence-bar-row">
                        <div class="cb-label">Portfolio & expositions</div>
                        <div class="cb-bar">
                            <div class="cb-fill" style="width:96%; background:var(--gold);"></div>
                        </div>
                        <div class="cb-pct">96%</div>
                    </div>
                    <div class="confidence-bar-row">
                        <div class="cb-label">Région éligible</div>
                        <div class="cb-bar">
                            <div class="cb-fill" style="width:100%; background:var(--gold);"></div>
                        </div>
                        <div class="cb-pct">100%</div>
                    </div>
                    <div class="confidence-bar-row">
                        <div class="cb-label">Dossier complet</div>
                        <div class="cb-bar">
                            <div class="cb-fill" style="width:95%; background:var(--teal);"></div>
                        </div>
                        <div class="cb-pct">95%</div>
                    </div>
                    <div class="confidence-bar-row">
                        <div class="cb-label">Disponibilité</div>
                        <div class="cb-bar">
                            <div class="cb-fill" style="width:88%; background:var(--teal);"></div>
                        </div>
                        <div class="cb-pct">88%</div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Recommandation IA</label>
                    <div class="sm-ia-reco">
                        🤖 Isabelle Renaud présente un profil exceptionnel pour cette résidence.
                        Son travail pictural contemporain et ses 8 expositions institutionnelles
                        correspondent précisément aux critères FNAP. Dossier prêt à soumettre.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('modal-sm-artist-1')">Fermer</button>
                <button class="btn btn-gold" onclick="showToast('Notification envoyée à Isabelle!', 'success')">📨 Envoyer
                    l'opportunité</button>
            </div>
        </div>
    </div>

    {{-- Modals artistes 2, 3 --}}
    @foreach ([2, 3] as $ai)
        <div id="modal-sm-artist-{{ $ai }}" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">🎨 Profil Artiste</div>
                    <button class="modal-close" onclick="closeModal('modal-sm-artist-{{ $ai }}')">×</button>
                </div>
                <div class="modal-body">
                    <p style="color:var(--text2); font-size:13px;">Analyse IA complète du profil artiste et
                        recommandations.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline"
                        onclick="closeModal('modal-sm-artist-{{ $ai }}')">Fermer</button>
                    <button class="btn btn-gold" onclick="showToast('Notification envoyée!', 'success')">📨 Envoyer
                        l'opportunité</button>
                </div>
            </div>
        </div>
    @endforeach


    {{-- ══════════════════════════════════════════════
     STYLES SmartMatch (à ajouter dans backend.css)
══════════════════════════════════════════════ --}}

    {{-- ══ SmartMatch JS ══ --}}

        <script>
            window.smSetTab = function(el, tabId) {
                el.closest('.sm-tabs-row').querySelectorAll('.sm-tab').forEach(t => t.classList.remove('active'));
                el.classList.add('active');
                document.querySelectorAll('.sm-tab-panel').forEach(p => p.classList.remove('active'));
                const panel = document.getElementById(tabId);
                if (panel) panel.classList.add('active');
            }

            window.smRunScan = function() {
                showToast('⚡ Scan IA lancé — analyse de 247 opportunités…', 'info');
                setTimeout(() => showToast('✅ 4 nouveaux matchs détectés!', 'success'), 2800);
            }

            window.smFilterOpp = function(sel) {
                showToast('Classement mis à jour pour : ' + sel.value, 'info');
            }

            // Animate SmartMatch KPIs on load
            document.addEventListener('DOMContentLoaded', () => {
                const smKpis = [{
                        id: 'sm-kpi-opps',
                        target: 247
                    },
                    {
                        id: 'sm-kpi-matches',
                        target: 63
                    },
                    {
                        id: 'sm-kpi-artists',
                        target: 18
                    },
                ];
                smKpis.forEach(({
                    id,
                    target
                }) => {
                    let val = 0;
                    const step = Math.ceil(target / 30);
                    const el = document.getElementById(id);
                    if (!el) return;
                    const timer = setInterval(() => {
                        val = Math.min(val + step, target);
                        el.textContent = val;
                        if (val >= target) clearInterval(timer);
                    }, 35);
                });

                // Radar dot tooltips
                document.querySelectorAll('.sm-radar-dot').forEach(dot => {
                    dot.title = 'Score: ' + dot.dataset.score + '%';
                });
            });
        </script>

@endsection
