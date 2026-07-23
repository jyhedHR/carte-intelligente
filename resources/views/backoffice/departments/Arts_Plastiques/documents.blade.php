@extends('shared.layouts.backoffice')

@section('title', 'Gestion des Documents')
@section('breadcrumb', 'Gestion des Documents')

@section('content')

    <!-- KPI Cards -->
    <div class="kpi-grid">
        <div class="kpi-card gold">
            <div class="kpi-icon">📁</div>
            <div class="kpi-value">1,547</div>
            <div class="kpi-label">Documents Total</div>
            <div class="kpi-delta up">↑ +125 ce mois</div>
        </div>
        <div class="kpi-card green">
            <div class="kpi-icon">💾</div>
            <div class="kpi-value">24.5 GB</div>
            <div class="kpi-label">Stockage Utilisé</div>
            <div class="kpi-delta flat">→ 42% de 50 GB</div>
        </div>
        <div class="kpi-card red">
            <div class="kpi-icon">⚠️</div>
            <div class="kpi-value">8</div>
            <div class="kpi-label">Documents Quarantainés</div>
            <div class="kpi-delta down">↓ À vérifier</div>
        </div>
        <div class="kpi-card blue">
            <div class="kpi-icon">📥</div>
            <div class="kpi-value">342</div>
            <div class="kpi-label">Téléchargements (30j)</div>
            <div class="kpi-delta up">↑ +15%</div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════
     🤖 AI GHOSTWRITER BANNER
════════════════════════════════════════════ --}}
    <div class="gw-hero">
        <div class="gw-hero-glow"></div>

        {{-- Left: copy + stats --}}
        <div class="gw-hero-left">
            <div class="gw-badge">
                <span class="gw-pulse"></span>
                Ghostwriter IA — Actif
            </div>
            <div class="gw-title">
                <span class="gw-gradient">AI Ghostwriter</span>
                <span class="gw-sub">du Dossier</span>
            </div>
            <p class="gw-desc">
                Dès le premier upload, l'IA fantôme remplit <strong>80 % du formulaire</strong>
                automatiquement — portfolio, anciens dossiers, réseaux analysés en secondes.
                L'admin valide en <strong>1 clic.</strong>
            </p>
            <div class="gw-stats-row">
                <div class="gw-stat">
                    <div class="gw-stat-val" id="gw-kpi-filled">80<span style="font-size:14px;">%</span></div>
                    <div class="gw-stat-lbl">Remplissage auto moyen</div>
                </div>
                <div class="gw-stat-sep"></div>
                <div class="gw-stat">
                    <div class="gw-stat-val" id="gw-kpi-saved">14<span style="font-size:14px;">s</span></div>
                    <div class="gw-stat-lbl">Temps d'analyse</div>
                </div>
                <div class="gw-stat-sep"></div>
                <div class="gw-stat">
                    <div class="gw-stat-val" id="gw-kpi-dossiers">38</div>
                    <div class="gw-stat-lbl">Dossiers auto-complétés</div>
                </div>
                <div class="gw-stat-sep"></div>
                <div class="gw-stat">
                    <div class="gw-stat-val" id="gw-kpi-valide">12</div>
                    <div class="gw-stat-lbl">Validés en 1 clic aujourd'hui</div>
                </div>
            </div>
        </div>

        {{-- Right: animated completion visual --}}
        <div class="gw-hero-right">
            <div class="gw-preview-card">
                <div class="gw-preview-header">
                    <div class="gw-preview-dot gw-dot-red"></div>
                    <div class="gw-preview-dot gw-dot-amber"></div>
                    <div class="gw-preview-dot gw-dot-green"></div>
                    <span style="font-size:11px; color:var(--text3); margin-left:8px;">Dossier_FNAP_Sophie.pdf</span>
                    <span class="gw-ai-badge">🤖 IA</span>
                </div>
                <div class="gw-preview-body">
                    <div class="gw-field-row gw-filled" style="animation-delay:.1s">
                        <span class="gw-field-label">Nom complet</span>
                        <span class="gw-field-val gw-typing">Sophie Bernard</span>
                        <span class="gw-ai-chip">IA ✓</span>
                    </div>
                    <div class="gw-field-row gw-filled" style="animation-delay:.3s">
                        <span class="gw-field-label">Discipline</span>
                        <span class="gw-field-val gw-typing">Peinture contemporaine</span>
                        <span class="gw-ai-chip">IA ✓</span>
                    </div>
                    <div class="gw-field-row gw-filled" style="animation-delay:.5s">
                        <span class="gw-field-label">Expositions</span>
                        <span class="gw-field-val gw-typing">12 expositions (2018–2024)</span>
                        <span class="gw-ai-chip">IA ✓</span>
                    </div>
                    <div class="gw-field-row gw-filled" style="animation-delay:.7s">
                        <span class="gw-field-label">Portfolio</span>
                        <span class="gw-field-val gw-typing">47 œuvres indexées</span>
                        <span class="gw-ai-chip">IA ✓</span>
                    </div>
                    <div class="gw-field-row gw-warn" style="animation-delay:.9s">
                        <span class="gw-field-label">Justificatif fiscal</span>
                        <span class="gw-field-val" style="color:var(--amber);">Manquant</span>
                        <span class="gw-ai-chip gw-chip-warn">⚠ Requis</span>
                    </div>
                    <div class="gw-completeness-wrap">
                        <div class="gw-completeness-label">
                            <span>Complétude IA</span>
                            <span class="gw-completeness-pct">82%</span>
                        </div>
                        <div class="gw-completeness-bar">
                            <div class="gw-completeness-fill" style="width:82%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ════ DOSSIERS IA — Vue Admin en temps réel ════ --}}
    <div class="panel" style="margin-bottom:20px;">
        <div class="panel-head">
            <div>
                <div class="panel-title">🤖 Dossiers analysés par le Ghostwriter IA</div>
                <div class="panel-sub">Taux de complétude + 3 points faibles détectés avant ouverture • Admin valide en 1
                    clic</div>
            </div>
            <div style="display:flex; gap:8px; align-items:center;">
                <span class="gw-live-badge"><span class="gw-pulse gw-pulse-green"></span> Live</span>
                <button class="btn btn-outline btn-sm" onclick="showToast('Filtres appliqués', 'info')">🔽
                    Filtrer</button>
                <button class="btn btn-gold btn-sm" onclick="gwValidateAll()">⚡ Valider tous (≥80%)</button>
            </div>
        </div>

        {{-- Filter row --}}
        <div class="panel-body" style="padding-bottom:0; border-bottom:1px solid var(--border);">
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <button class="gw-filter active" onclick="gwFilter(this,'all')">Tous <span
                        class="gw-fc">5</span></button>
                <button class="gw-filter" onclick="gwFilter(this,'ready')">Prêts à valider <span
                        class="gw-fc gw-fc-green">3</span></button>
                <button class="gw-filter" onclick="gwFilter(this,'incomplete')">Incomplets <span
                        class="gw-fc gw-fc-amber">2</span></button>
                <button class="gw-filter" onclick="gwFilter(this,'processing')">En analyse <span
                        class="gw-fc gw-fc-blue">1</span></button>
            </div>
        </div>

        <div class="panel-body no-pad">
            <div class="gw-dossier-list">

                {{-- Dossier 1 — 92% ready --}}
                <div class="gw-dossier-row gw-ds-ready" data-state="ready">
                    <div class="gw-ds-left">
                        <div class="gw-ds-avatar">SB</div>
                        <div class="gw-ds-info">
                            <div class="gw-ds-name">Sophie Bernard</div>
                            <div class="gw-ds-meta">
                                <span>📄 Dossier FNAP</span>
                                <span>·</span>
                                <span>Uploadé il y a 4 min</span>
                                <span>·</span>
                                <span class="gw-ds-source">Portfolio · 3 anciens dossiers · LinkedIn</span>
                            </div>
                        </div>
                    </div>

                    <div class="gw-ds-score-wrap">
                        <div class="gw-ds-ring" data-pct="92">
                            <svg viewBox="0 0 40 40">
                                <circle cx="20" cy="20" r="17" fill="none" stroke="var(--bg4)"
                                    stroke-width="3.5" />
                                <circle cx="20" cy="20" r="17" fill="none" stroke="var(--green)"
                                    stroke-width="3.5" stroke-dasharray="98,107" stroke-dashoffset="27"
                                    stroke-linecap="round" />
                            </svg>
                            <span>92%</span>
                        </div>
                        <div class="gw-ds-score-lbl">Complétude</div>
                    </div>

                    <div class="gw-ds-weakpoints">
                        <div class="gw-ds-weak-title">Points faibles IA :</div>
                        <div class="gw-weak-tag gw-weak-amber">⚠ Justificatif fiscal manquant</div>
                        <div class="gw-weak-tag gw-weak-amber">⚠ Photo identité basse résolution</div>
                        <div class="gw-weak-tag gw-weak-green">✓ Tous champs critiques OK</div>
                    </div>

                    <div class="gw-ds-actions">
                        <span class="badge green" style="margin-bottom:6px;">Prêt ✓</span>
                        <button class="btn btn-gold btn-sm gw-validate-btn"
                            onclick="gwValidateOne(this, 'Sophie Bernard')">⚡ Valider</button>
                        <button class="btn btn-ghost btn-sm" onclick="openModal('modal-gw-detail-1')">👁️ Détail
                            IA</button>
                    </div>
                </div>

                {{-- Dossier 2 — 87% ready --}}
                <div class="gw-dossier-row gw-ds-ready" data-state="ready">
                    <div class="gw-ds-left">
                        <div class="gw-ds-avatar gw-av-teal">MR</div>
                        <div class="gw-ds-info">
                            <div class="gw-ds-name">Marie Rousseau</div>
                            <div class="gw-ds-meta">
                                <span>📄 Photos d'Œuvres</span>
                                <span>·</span>
                                <span>Uploadé il y a 22 min</span>
                                <span>·</span>
                                <span class="gw-ds-source">Portfolio · Instagram · 2 anciens dossiers</span>
                            </div>
                        </div>
                    </div>

                    <div class="gw-ds-score-wrap">
                        <div class="gw-ds-ring" data-pct="87">
                            <svg viewBox="0 0 40 40">
                                <circle cx="20" cy="20" r="17" fill="none" stroke="var(--bg4)"
                                    stroke-width="3.5" />
                                <circle cx="20" cy="20" r="17" fill="none" stroke="var(--teal)"
                                    stroke-width="3.5" stroke-dasharray="93,107" stroke-dashoffset="27"
                                    stroke-linecap="round" />
                            </svg>
                            <span>87%</span>
                        </div>
                        <div class="gw-ds-score-lbl">Complétude</div>
                    </div>

                    <div class="gw-ds-weakpoints">
                        <div class="gw-ds-weak-title">Points faibles IA :</div>
                        <div class="gw-weak-tag gw-weak-amber">⚠ Biographie courte insuffisante</div>
                        <div class="gw-weak-tag gw-weak-amber">⚠ Lien portfolio mort détecté</div>
                        <div class="gw-weak-tag gw-weak-red">✗ Contrat de cession absent</div>
                    </div>

                    <div class="gw-ds-actions">
                        <span class="badge green" style="margin-bottom:6px;">Prêt ✓</span>
                        <button class="btn btn-gold btn-sm gw-validate-btn"
                            onclick="gwValidateOne(this, 'Marie Rousseau')">⚡ Valider</button>
                        <button class="btn btn-ghost btn-sm" onclick="openModal('modal-gw-detail-2')">👁️ Détail
                            IA</button>
                    </div>
                </div>

                {{-- Dossier 3 — 81% ready --}}
                <div class="gw-dossier-row gw-ds-ready" data-state="ready">
                    <div class="gw-ds-left">
                        <div class="gw-ds-avatar gw-av-purple">JD</div>
                        <div class="gw-ds-info">
                            <div class="gw-ds-name">Jean Dupont</div>
                            <div class="gw-ds-meta">
                                <span>📄 Artistes Étrangers</span>
                                <span>·</span>
                                <span>Uploadé il y a 1 h</span>
                                <span>·</span>
                                <span class="gw-ds-source">CV · Portfolio · 1 ancien dossier</span>
                            </div>
                        </div>
                    </div>

                    <div class="gw-ds-score-wrap">
                        <div class="gw-ds-ring" data-pct="81">
                            <svg viewBox="0 0 40 40">
                                <circle cx="20" cy="20" r="17" fill="none" stroke="var(--bg4)"
                                    stroke-width="3.5" />
                                <circle cx="20" cy="20" r="17" fill="none" stroke="var(--gold)"
                                    stroke-width="3.5" stroke-dasharray="87,107" stroke-dashoffset="27"
                                    stroke-linecap="round" />
                            </svg>
                            <span>81%</span>
                        </div>
                        <div class="gw-ds-score-lbl">Complétude</div>
                    </div>

                    <div class="gw-ds-weakpoints">
                        <div class="gw-ds-weak-title">Points faibles IA :</div>
                        <div class="gw-weak-tag gw-weak-red">✗ Visa de travail non fourni</div>
                        <div class="gw-weak-tag gw-weak-amber">⚠ Adresse incomplète</div>
                        <div class="gw-weak-tag gw-weak-green">✓ Œuvres correctement référencées</div>
                    </div>

                    <div class="gw-ds-actions">
                        <span class="badge gold" style="margin-bottom:6px;">À revoir</span>
                        <button class="btn btn-gold btn-sm gw-validate-btn" onclick="gwValidateOne(this, 'Jean Dupont')">⚡
                            Valider</button>
                        <button class="btn btn-ghost btn-sm" onclick="openModal('modal-gw-detail-3')">👁️ Détail
                            IA</button>
                    </div>
                </div>

                {{-- Dossier 4 — 54% incomplete --}}
                <div class="gw-dossier-row gw-ds-incomplete" data-state="incomplete">
                    <div class="gw-ds-left">
                        <div class="gw-ds-avatar gw-av-amber">LM</div>
                        <div class="gw-ds-info">
                            <div class="gw-ds-name">Luc Martin</div>
                            <div class="gw-ds-meta">
                                <span>📄 Prêts d'Œuvres</span>
                                <span>·</span>
                                <span>Uploadé il y a 3 h</span>
                                <span>·</span>
                                <span class="gw-ds-source">1 document uniquement · Profil vide</span>
                            </div>
                        </div>
                    </div>

                    <div class="gw-ds-score-wrap">
                        <div class="gw-ds-ring" data-pct="54">
                            <svg viewBox="0 0 40 40">
                                <circle cx="20" cy="20" r="17" fill="none" stroke="var(--bg4)"
                                    stroke-width="3.5" />
                                <circle cx="20" cy="20" r="17" fill="none" stroke="var(--amber)"
                                    stroke-width="3.5" stroke-dasharray="58,107" stroke-dashoffset="27"
                                    stroke-linecap="round" />
                            </svg>
                            <span>54%</span>
                        </div>
                        <div class="gw-ds-score-lbl">Complétude</div>
                    </div>

                    <div class="gw-ds-weakpoints">
                        <div class="gw-ds-weak-title">Points faibles IA :</div>
                        <div class="gw-weak-tag gw-weak-red">✗ Portfolio introuvable</div>
                        <div class="gw-weak-tag gw-weak-red">✗ Aucune exposition référencée</div>
                        <div class="gw-weak-tag gw-weak-red">✗ Informations bancaires manquantes</div>
                    </div>

                    <div class="gw-ds-actions">
                        <span class="badge red" style="margin-bottom:6px;">Incomplet</span>
                        <button class="btn btn-outline btn-sm"
                            onclick="showToast('Demande de complétion envoyée à Luc!', 'info')">📨 Relancer</button>
                        <button class="btn btn-ghost btn-sm" onclick="openModal('modal-gw-detail-4')">👁️ Détail
                            IA</button>
                    </div>
                </div>

                {{-- Dossier 5 — In progress --}}
                <div class="gw-dossier-row gw-ds-processing" data-state="processing">
                    <div class="gw-ds-left">
                        <div class="gw-ds-avatar gw-av-blue">FC</div>
                        <div class="gw-ds-info">
                            <div class="gw-ds-name">Florence Chénier</div>
                            <div class="gw-ds-meta">
                                <span>📄 FNAP</span>
                                <span>·</span>
                                <span>Upload en cours...</span>
                                <span>·</span>
                                <span class="gw-ds-source">Analyse IA en cours — 3 sources détectées</span>
                            </div>
                        </div>
                    </div>

                    <div class="gw-ds-score-wrap">
                        <div class="gw-ds-ring gw-ring-spin">
                            <svg viewBox="0 0 40 40">
                                <circle cx="20" cy="20" r="17" fill="none" stroke="var(--bg4)"
                                    stroke-width="3.5" />
                                <circle cx="20" cy="20" r="17" fill="none" stroke="var(--blue)"
                                    stroke-width="3.5" stroke-dasharray="40,107" stroke-dashoffset="27"
                                    stroke-linecap="round" style="animation: gw-dash-anim 1.5s linear infinite;" />
                            </svg>
                            <span style="color:var(--blue); font-size:9px;">…</span>
                        </div>
                        <div class="gw-ds-score-lbl">Analyse…</div>
                    </div>

                    <div class="gw-ds-weakpoints">
                        <div class="gw-ds-weak-title">Analyse en cours :</div>
                        <div class="gw-processing-line">
                            <span class="gw-pulse gw-pulse-blue"></span>
                            Lecture du portfolio…
                        </div>
                        <div class="gw-processing-line" style="opacity:.5;">
                            <span class="gw-pulse gw-pulse-blue"></span>
                            Scan réseaux sociaux…
                        </div>
                        <div class="gw-processing-line" style="opacity:.3;">
                            <span class="gw-pulse gw-pulse-blue"></span>
                            Comparaison anciens dossiers…
                        </div>
                    </div>

                    <div class="gw-ds-actions">
                        <span class="badge blue" style="margin-bottom:6px;">En analyse</span>
                        <button class="btn btn-outline btn-sm" disabled>⏳ En cours…</button>
                        <button class="btn btn-ghost btn-sm" disabled>👁️ Détail IA</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Filters & Actions -->
    <div class="panel" style="margin-bottom: 24px;">
        <div class="panel-head">
            <div>
                <div class="panel-title">Filtres & Actions</div>
                <div class="panel-sub">Organisez et gérez tous les documents relatifs aux demandes</div>
            </div>
            <button class="btn btn-gold" onclick="openModal('modal-upload-doc')">+ Uploader Document</button>
        </div>
        <div class="panel-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
                <input type="text" class="form-input" placeholder="Rechercher par nom...">
                <select class="form-select">
                    <option>Tous les statuts</option>
                    <option>Approuvé</option>
                    <option>En Attente</option>
                    <option>Quarantainé</option>
                    <option>Rejeté</option>
                </select>
                <select class="form-select">
                    <option>Tous les types</option>
                    <option>Certificats</option>
                    <option>Photos/Images</option>
                    <option>Contrats</option>
                    <option>Autorisations</option>
                </select>
                <select class="form-select">
                    <option>Tous les dossiers</option>
                    <option>Photos d'Œuvres</option>
                    <option>FNAP</option>
                    <option>Artistes Étrangers</option>
                    <option>Prêts d'Œuvres</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Documents Table -->
    <div class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">Documents Récents</div>
                <div class="panel-sub">Suivi des documents et validations</div>
            </div>
            <div style="display: flex; gap: 8px;">
                <button class="btn btn-outline btn-sm" onclick="showToast('Export CSV réalisé!', 'info')">📥
                    Exporter</button>
                <button class="btn btn-outline btn-sm" onclick="showToast('Document imprimé!', 'info')">🖨️
                    Imprimer</button>
            </div>
        </div>
        <div class="panel-body no-pad">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nom du Document</th>
                            <th>Type</th>
                            <th>Taille</th>
                            <th>Dossier</th>
                            <th>Date Upload</th>
                            <th>Statut</th>
                            <th>Uploadé par</th>
                            <th>IA Score</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="code">📄 Certificat_Auth_001.pdf</span></td>
                            <td><span class="badge gray">Certificat</span></td>
                            <td>2.3 MB</td>
                            <td>Photos d'Œuvres</td>
                            <td>15/05/2024</td>
                            <td><span class="badge green">Approuvé</span></td>
                            <td>
                                <div class="row-user">
                                    <div class="avatar-sm">MR</div>
                                    <div>
                                        <strong>Marie Rousseau</strong><br>
                                        <span class="text-muted">marie@art.fr</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="gw-inline-score gw-score-green">
                                    <span>87%</span>
                                    <div class="gw-inline-bar">
                                        <div style="width:87%; background:var(--green);"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-ghost btn-sm" onclick="showToast('Téléchargement...')"
                                        title="Télécharger">📥</button>
                                    <button class="btn btn-ghost btn-sm" onclick="openModal('modal-view-doc')"
                                        title="Aperçu">👁️</button>
                                    <button class="btn btn-ghost btn-sm btn-gold"
                                        onclick="openModal('modal-edit-doc')">✏️</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="code">🖼️ Artwork_Photo_156.jpg</span></td>
                            <td><span class="badge gray">Photo</span></td>
                            <td>5.8 MB</td>
                            <td>Photos d'Œuvres</td>
                            <td>15/05/2024</td>
                            <td><span class="badge green">Approuvé</span></td>
                            <td>
                                <div class="row-user">
                                    <div class="avatar-sm">JD</div>
                                    <div>
                                        <strong>Jean Dupont</strong><br>
                                        <span class="text-muted">jean@art.fr</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="gw-inline-score gw-score-gold">
                                    <span>81%</span>
                                    <div class="gw-inline-bar">
                                        <div style="width:81%; background:var(--gold);"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-ghost btn-sm" onclick="showToast('Téléchargement...')"
                                        title="Télécharger">📥</button>
                                    <button class="btn btn-ghost btn-sm" onclick="openModal('modal-view-doc')"
                                        title="Aperçu">👁️</button>
                                    <button class="btn btn-ghost btn-sm btn-gold"
                                        onclick="openModal('modal-edit-doc')">✏️</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="code">📋 Demande_FNAP_087.docx</span></td>
                            <td><span class="badge gray">Document</span></td>
                            <td>1.2 MB</td>
                            <td>FNAP</td>
                            <td>14/05/2024</td>
                            <td><span class="badge blue">En Attente</span></td>
                            <td>
                                <div class="row-user">
                                    <div class="avatar-sm">SB</div>
                                    <div>
                                        <strong>Sophie Bernard</strong><br>
                                        <span class="text-muted">sophie@art.fr</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="gw-inline-score gw-score-green">
                                    <span>92%</span>
                                    <div class="gw-inline-bar">
                                        <div style="width:92%; background:var(--green);"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-ghost btn-sm" onclick="showToast('Téléchargement...')"
                                        title="Télécharger">📥</button>
                                    <button class="btn btn-ghost btn-sm" onclick="openModal('modal-view-doc')"
                                        title="Aperçu">👁️</button>
                                    <button class="btn btn-ghost btn-sm btn-gold"
                                        onclick="openModal('modal-edit-doc')">✏️</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="code">📋 Contrat_Pret_042.pdf</span></td>
                            <td><span class="badge gray">Contrat</span></td>
                            <td>980 KB</td>
                            <td>Prêts d'Œuvres</td>
                            <td>12/05/2024</td>
                            <td><span class="badge blue">En Attente</span></td>
                            <td>
                                <div class="row-user">
                                    <div class="avatar-sm">LM</div>
                                    <div>
                                        <strong>Luc Martin</strong><br>
                                        <span class="text-muted">luc@art.fr</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="gw-inline-score gw-score-amber">
                                    <span>54%</span>
                                    <div class="gw-inline-bar">
                                        <div style="width:54%; background:var(--amber);"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-ghost btn-sm" onclick="showToast('Téléchargement...')"
                                        title="Télécharger">📥</button>
                                    <button class="btn btn-ghost btn-sm" onclick="openModal('modal-view-doc')"
                                        title="Aperçu">👁️</button>
                                    <button class="btn btn-ghost btn-sm btn-gold"
                                        onclick="openModal('modal-edit-doc')">✏️</button>
                                </div>
                            </td>
                        </tr>
                        <tr style="background: var(--red-dim); opacity: 0.8;">
                            <td><span class="code">📄 Fichier_Crypt.pdf</span></td>
                            <td><span class="badge red">Fichier corrompu</span></td>
                            <td>—</td>
                            <td>—</td>
                            <td>08/05/2024</td>
                            <td><span class="badge red">Quarantainé</span></td>
                            <td>—</td>
                            <td>
                                <div class="gw-inline-score">
                                    <span style="color:var(--text3);">N/A</span>
                                </div>
                            </td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-ghost btn-sm" onclick="showToast('Tentative de réparation...')"
                                        title="Réparer">🔧</button>
                                    <button class="btn btn-ghost btn-sm btn-red" onclick="openModal('modal-delete-doc')"
                                        title="Supprimer">🗑️</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ════ MODALS GHOSTWRITER IA ════ --}}

    {{-- Modal Détail IA — Sophie Bernard (Dossier 1) --}}
    <div id="modal-gw-detail-1" class="modal">
        <div class="modal-content" style="max-width:640px;">
            <div class="modal-header">
                <div class="modal-title">🤖 Rapport Ghostwriter IA — Sophie Bernard</div>
                <button class="modal-close" onclick="closeModal('modal-gw-detail-1')">×</button>
            </div>
            <div class="modal-body">

                {{-- Score banner --}}
                <div class="gw-modal-banner">
                    <div class="gw-modal-ring">
                        <svg viewBox="0 0 80 80" width="80" height="80">
                            <circle cx="40" cy="40" r="34" fill="none" stroke="var(--bg4)"
                                stroke-width="6" />
                            <circle cx="40" cy="40" r="34" fill="none" stroke="var(--green)"
                                stroke-width="6" stroke-dasharray="196,213" stroke-dashoffset="55"
                                stroke-linecap="round" />
                        </svg>
                        <div class="gw-modal-ring-val">92%</div>
                    </div>
                    <div>
                        <div style="font-size:17px; font-weight:800; color:var(--text);">Dossier quasi-complet</div>
                        <div style="font-size:12px; color:var(--text2); margin-top:4px;">Analysé en 11s · 4 sources · 18
                            champs remplis sur 22</div>
                        <div style="display:flex; gap:6px; margin-top:10px; flex-wrap:wrap;">
                            <span class="badge green">Prêt à valider ✓</span>
                            <span class="badge blue">FNAP</span>
                            <span class="badge gray">Sophie Bernard</span>
                        </div>
                    </div>
                </div>

                {{-- Sources analysées --}}
                <div class="form-group">
                    <label class="form-label">Sources analysées par l'IA</label>
                    <div class="gw-sources">
                        <div class="gw-source-item">
                            <span class="gw-source-icon" style="background:var(--blue-dim); color:var(--blue);">📁</span>
                            <div class="gw-source-info">
                                <div class="gw-source-name">3 anciens dossiers FNAP</div>
                                <div class="gw-source-meta">2019, 2021, 2023 — données croisées</div>
                            </div>
                            <span class="badge green">✓</span>
                        </div>
                        <div class="gw-source-item">
                            <span class="gw-source-icon"
                                style="background:var(--purple-dim); color:var(--purple);">🎨</span>
                            <div class="gw-source-info">
                                <div class="gw-source-name">Portfolio en ligne</div>
                                <div class="gw-source-meta">47 œuvres indexées · artiste-sophie.fr</div>
                            </div>
                            <span class="badge green">✓</span>
                        </div>
                        <div class="gw-source-item">
                            <span class="gw-source-icon" style="background:var(--teal-dim); color:var(--teal);">🔗</span>
                            <div class="gw-source-info">
                                <div class="gw-source-name">LinkedIn professionnel</div>
                                <div class="gw-source-meta">12 expositions · 5 résidences référencées</div>
                            </div>
                            <span class="badge green">✓</span>
                        </div>
                        <div class="gw-source-item">
                            <span class="gw-source-icon" style="background:var(--gold-dim); color:var(--gold);">📋</span>
                            <div class="gw-source-info">
                                <div class="gw-source-name">Document uploadé</div>
                                <div class="gw-source-meta">Dossier_FNAP_Sophie.pdf · 1.2 MB</div>
                            </div>
                            <span class="badge green">✓</span>
                        </div>
                    </div>
                </div>

                {{-- Champs remplis par l'IA --}}
                <div class="form-group">
                    <label class="form-label">Champs auto-complétés par l'IA (18/22)</label>
                    <div class="gw-fields-grid">
                        <div class="gw-field-chip gw-chip-ok">✓ Nom complet</div>
                        <div class="gw-field-chip gw-chip-ok">✓ Date de naissance</div>
                        <div class="gw-field-chip gw-chip-ok">✓ Discipline artistique</div>
                        <div class="gw-field-chip gw-chip-ok">✓ Adresse principale</div>
                        <div class="gw-field-chip gw-chip-ok">✓ Nationalité</div>
                        <div class="gw-field-chip gw-chip-ok">✓ Email professionnel</div>
                        <div class="gw-field-chip gw-chip-ok">✓ Téléphone</div>
                        <div class="gw-field-chip gw-chip-ok">✓ Site web / Portfolio</div>
                        <div class="gw-field-chip gw-chip-ok">✓ Biographie (FR)</div>
                        <div class="gw-field-chip gw-chip-ok">✓ Liste expositions</div>
                        <div class="gw-field-chip gw-chip-ok">✓ Références professionnelles</div>
                        <div class="gw-field-chip gw-chip-ok">✓ IBAN (pré-rempli)</div>
                        <div class="gw-field-chip gw-chip-ok">✓ Œuvres proposées</div>
                        <div class="gw-field-chip gw-chip-ok">✓ Techniques utilisées</div>
                        <div class="gw-field-chip gw-chip-ok">✓ Dimensions moyennes</div>
                        <div class="gw-field-chip gw-chip-ok">✓ Prix de vente estimé</div>
                        <div class="gw-field-chip gw-chip-ok">✓ Nombre d'œuvres</div>
                        <div class="gw-field-chip gw-chip-ok">✓ Description projet</div>
                        <div class="gw-field-chip gw-chip-warn">⚠ Justificatif fiscal</div>
                        <div class="gw-field-chip gw-chip-warn">⚠ Photo identité HD</div>
                        <div class="gw-field-chip gw-chip-missing">✗ Signature numérique</div>
                        <div class="gw-field-chip gw-chip-missing">✗ Attestation assurance</div>
                    </div>
                </div>

                {{-- Recommandation IA --}}
                <div class="form-group">
                    <label class="form-label">Recommandation IA</label>
                    <div class="gw-ia-reco">
                        🤖 <strong>Dossier validable.</strong> Le profil de Sophie Bernard est solide et cohérent avec les
                        critères FNAP.
                        Les 2 documents manquants (justificatif fiscal + photo HD) peuvent être demandés
                        post-validation sans bloquer le traitement. Score de confiance élevé.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('modal-gw-detail-1')">Fermer</button>
                <button class="btn btn-outline" onclick="showToast('Demande envoyée à Sophie!', 'info')">📨 Demander
                    documents manquants</button>
                <button class="btn btn-gold"
                    onclick="gwValidateOne(null, 'Sophie Bernard'); closeModal('modal-gw-detail-1')">⚡ Valider en 1
                    clic</button>
            </div>
        </div>
    </div>

    {{-- Modals simplifées pour dossiers 2, 3, 4 --}}
    @foreach ([
            2 => ['Marie Rousseau', '87', 'var(--teal)', 'Photos d\'Œuvres'],
            3 => ['Jean Dupont', '81', 'var(--gold)', 'Artistes Étrangers'],
            4 => ['Luc Martin', '54', 'var(--amber)', 'Prêts d\'Œuvres'],
        ] as $idx => $data)
        <div id="modal-gw-detail-{{ $idx }}" class="modal">
            <div class="modal-content" style="max-width:560px;">
                <div class="modal-header">
                    <div class="modal-title">🤖 Rapport Ghostwriter IA — {{ $data[0] }}</div>
                    <button class="modal-close" onclick="closeModal('modal-gw-detail-{{ $idx }}')">×</button>
                </div>
                <div class="modal-body">
                    <div class="gw-modal-banner">
                        <div class="gw-modal-ring">
                            <svg viewBox="0 0 80 80" width="80" height="80">
                                <circle cx="40" cy="40" r="34" fill="none" stroke="var(--bg4)"
                                    stroke-width="6" />
                                <circle cx="40" cy="40" r="34" fill="none" stroke="{{ $data[2] }}"
                                    stroke-width="6" stroke-dasharray="{{ round((213 * intval($data[1])) / 100) }},213"
                                    stroke-dashoffset="55" stroke-linecap="round" />
                            </svg>
                            <div class="gw-modal-ring-val" style="color:{{ $data[2] }}">{{ $data[1] }}%</div>
                        </div>
                        <div>
                            <div style="font-size:17px; font-weight:800; color:var(--text);">Analyse IA —
                                {{ $data[3] }}</div>
                            <div style="font-size:12px; color:var(--text2); margin-top:4px;">Rapport complet disponible ·
                                Artiste : {{ $data[0] }}</div>
                            <div style="margin-top:10px;">
                                <span class="badge {{ intval($data[1]) >= 80 ? 'gold' : 'red' }}">
                                    {{ intval($data[1]) >= 80 ? 'À valider' : 'Incomplet' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <p style="font-size:13px; color:var(--text2); line-height:1.7;">
                        Analyse IA détaillée en cours de chargement. Cliquez sur "Valider en 1 clic" pour approuver
                        le dossier ou "Demander documents" pour relancer l'artiste sur les points manquants détectés.
                    </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline"
                        onclick="closeModal('modal-gw-detail-{{ $idx }}')">Fermer</button>
                    <button class="btn btn-outline" onclick="showToast('Demande envoyée!', 'info')">📨 Demander
                        documents</button>
                    @if (intval($data[1]) >= 80)
                        <button class="btn btn-gold"
                            onclick="gwValidateOne(null, '{{ $data[0] }}'); closeModal('modal-gw-detail-{{ $idx }}')">⚡
                            Valider en 1 clic</button>
                    @endif
                </div>
            </div>
        </div>
    @endforeach

    {{-- Modal Upload amélioré avec Ghostwriter --}}
    <div class="modal" id="modal-upload-doc">
        <div class="modal-content" style="max-width:560px;">
            <div class="modal-header">
                <div class="modal-title">+ Uploader — Ghostwriter IA activé</div>
                <button class="modal-close" onclick="closeModal('modal-upload-doc')">×</button>
            </div>
            <div class="modal-body">
                <div class="gw-upload-banner">
                    <span style="font-size:20px;">🤖</span>
                    <div>
                        <div style="font-weight:700; font-size:13px; color:var(--text);">Ghostwriter IA prêt</div>
                        <div style="font-size:11.5px; color:var(--text3); margin-top:2px;">
                            Dès l'upload, l'IA analysera le document et pré-remplira automatiquement 80% du formulaire.
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Artiste / Demandeur</label>
                    <input type="text" class="form-input" placeholder="Ex: Sophie Bernard" id="gw-upload-artist">
                </div>
                <div class="form-group">
                    <label class="form-label">Type de Document</label>
                    <select class="form-select">
                        <option>Certificat</option>
                        <option>Photo/Image</option>
                        <option>Contrat</option>
                        <option>Autorisation</option>
                        <option>Rapport</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Dossier de Destination</label>
                    <select class="form-select">
                        <option>Photos d'Œuvres</option>
                        <option>FNAP</option>
                        <option>Artistes Étrangers</option>
                        <option>Prêts d'Œuvres</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Sélectionner Fichier(s)</label>
                    <div class="gw-dropzone" id="gw-dropzone" onclick="document.getElementById('gw-file-input').click()">
                        <div class="gw-dz-icon">📂</div>
                        <div class="gw-dz-text">Glissez vos fichiers ici ou <span style="color:var(--gold);">cliquez pour
                                parcourir</span></div>
                        <div class="gw-dz-sub">PDF, DOCX, JPG, PNG · max 50 MB</div>
                        <input type="file" id="gw-file-input" multiple style="display:none;"
                            onchange="gwSimulateUpload()">
                    </div>
                    <div id="gw-upload-progress" style="display:none; margin-top:12px;">
                        <div class="gw-progress-label">
                            <span class="gw-pulse"></span>
                            <span id="gw-progress-text">L'IA analyse le document…</span>
                            <span id="gw-progress-pct"
                                style="font-family:var(--font-mono); font-weight:700; color:var(--gold);">0%</span>
                        </div>
                        <div class="gw-completeness-bar" style="margin-top:8px;">
                            <div class="gw-completeness-fill" id="gw-progress-bar"
                                style="width:0%; transition:width .3s;"></div>
                        </div>
                        <div id="gw-progress-fields" style="display:flex; gap:6px; flex-wrap:wrap; margin-top:10px;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('modal-upload-doc')">Annuler</button>
                <button class="btn btn-gold" onclick="gwSimulateUpload()">🤖 Uploader + Analyser</button>
            </div>
        </div>
    </div>

    <!-- Standard Modals -->
    <div class="modal" id="modal-view-doc">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Aperçu du Document</div>
                <button class="modal-close" onclick="closeModal('modal-view-doc')">✕</button>
            </div>
            <div class="modal-body">
                <div class="form-group"><label class="form-label">Nom du Document</label><input type="text"
                        class="form-input" value="Certificat_Auth_001.pdf" readonly></div>
                <div class="form-group"><label class="form-label">Type</label><input type="text" class="form-input"
                        value="Certificat" readonly></div>
                <div class="form-group"><label class="form-label">Dossier</label><input type="text"
                        class="form-input" value="Photos d'Œuvres" readonly></div>
                <div class="form-group"><label class="form-label">Uploadé par</label><input type="text"
                        class="form-input" value="Marie Rousseau (marie@art.fr)" readonly></div>
                <div class="form-group"><label class="form-label">Date Upload</label><input type="text"
                        class="form-input" value="15/05/2024 14:32" readonly></div>
                <div class="form-group"><label class="form-label">Statut</label><input type="text" class="form-input"
                        value="Approuvé" readonly></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('modal-view-doc')">Fermer</button>
                <button class="btn btn-gold"
                    onclick="closeModal('modal-view-doc'); showToast('Téléchargement en cours...', 'success')">📥
                    Télécharger</button>
            </div>
        </div>
    </div>

    <div class="modal" id="modal-edit-doc">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Éditer le Document</div>
                <button class="modal-close" onclick="closeModal('modal-edit-doc')">✕</button>
            </div>
            <div class="modal-body">
                <div class="form-group"><label class="form-label">Statut</label><select class="form-select">
                        <option>Approuvé</option>
                        <option>En Attente</option>
                        <option>Rejeté</option>
                        <option>Quarantainé</option>
                    </select></div>
                <div class="form-group"><label class="form-label">Dossier</label><select class="form-select">
                        <option>Photos d'Œuvres</option>
                        <option>FNAP</option>
                        <option>Artistes Étrangers</option>
                        <option>Prêts d'Œuvres</option>
                    </select></div>
                <div class="form-group"><label class="form-label">Commentaires</label>
                    <textarea class="form-input" placeholder="Ajoutez vos observations..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('modal-edit-doc')">Annuler</button>
                <button class="btn btn-gold"
                    onclick="closeModal('modal-edit-doc'); showToast('Document mis à jour!', 'success')">Enregistrer</button>
            </div>
        </div>
    </div>

    <div class="modal" id="modal-delete-doc">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Supprimer le Document</div>
                <button class="modal-close" onclick="closeModal('modal-delete-doc')">✕</button>
            </div>
            <div class="modal-body">
                <div style="padding:20px 0; text-align:center;">
                    <div style="font-size:48px; margin-bottom:15px;">⚠️</div>
                    <div style="font-size:15px; font-weight:600; color:var(--text); margin-bottom:8px;">Êtes-vous certain
                        de vouloir supprimer ce document?</div>
                    <div style="font-size:13px; color:var(--text2); margin-bottom:20px;">Cette action est irréversible. Le
                        document sera supprimé définitivement du système.</div>
                    <div
                        style="background:var(--bg3); border:1px solid var(--border); border-radius:6px; padding:12px; font-size:12px; color:var(--text2); text-align:left;">
                        <strong>Document:</strong> Fichier_Crypt.pdf<br>
                        <strong>Dossier:</strong> Prêts d'Œuvres
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('modal-delete-doc')">Annuler</button>
                <button class="btn btn-red"
                    onclick="closeModal('modal-delete-doc'); showToast('Document supprimé!', 'success')">🗑️ Supprimer
                    définitivement</button>
            </div>
        </div>
    </div>




    <style>
        /* ══ GHOSTWRITER HERO ══ */
        .gw-hero {
            position: relative;
            display: flex;
            align-items: center;
            gap: 32px;
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 28px 32px;
            margin-bottom: 20px;
            overflow: hidden;
            min-height: 210px;
        }

        .gw-hero-glow {
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 55% 90% at 85% 50%, rgba(201, 168, 76, 0.07) 0%, transparent 70%);
            pointer-events: none;
        }

        .gw-hero-left {
            flex: 1;
            z-index: 1;
        }

        .gw-hero-right {
            flex-shrink: 0;
            z-index: 1;
        }

        .gw-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: var(--gold-dim);
            border: 1px solid rgba(201, 168, 76, 0.25);
            color: var(--gold);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .5px;
            padding: 5px 12px;
            border-radius: 20px;
            margin-bottom: 14px;
            text-transform: uppercase;
        }

        .gw-pulse {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--gold);
            flex-shrink: 0;
            animation: gw-blink 1.6s infinite;
        }

        .gw-pulse-green {
            background: var(--green);
        }

        .gw-pulse-blue {
            background: var(--blue);
        }

        @keyframes gw-blink {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .45;
                transform: scale(.7);
            }
        }

        .gw-title {
            display: flex;
            align-items: baseline;
            gap: 12px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .gw-gradient {
            font-size: 27px;
            font-weight: 900;
            background: linear-gradient(135deg, var(--gold), var(--gold2), var(--amber));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .gw-sub {
            font-size: 14px;
            color: var(--text2);
            font-weight: 500;
        }

        .gw-desc {
            font-size: 13px;
            color: var(--text2);
            line-height: 1.65;
            max-width: 480px;
            margin-bottom: 20px;
        }

        .gw-desc strong {
            color: var(--gold);
        }

        .gw-stats-row {
            display: flex;
            align-items: center;
            gap: 0;
            flex-wrap: wrap;
        }

        .gw-stat {
            padding: 0 20px 0 0;
        }

        .gw-stat:first-child {
            padding-left: 0;
        }

        .gw-stat-val {
            font-size: 22px;
            font-weight: 800;
            font-family: var(--font-mono);
            color: var(--text);
        }

        .gw-stat-lbl {
            font-size: 11px;
            color: var(--text3);
            margin-top: 2px;
        }

        .gw-stat-sep {
            width: 1px;
            height: 32px;
            background: var(--border);
            margin-right: 20px;
            flex-shrink: 0;
        }

        /* ── Preview Card ── */
        .gw-preview-card {
            width: 300px;
            background: var(--bg3);
            border: 1px solid var(--border2);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.4);
        }

        .gw-preview-header {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 14px;
            background: var(--bg4);
            border-bottom: 1px solid var(--border);
            font-size: 11px;
        }

        .gw-preview-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .gw-dot-red {
            background: #f87171;
        }

        .gw-dot-amber {
            background: #fbbf24;
        }

        .gw-dot-green {
            background: #4ade80;
        }

        .gw-ai-badge {
            margin-left: auto;
            background: var(--gold-dim);
            color: var(--gold);
            font-size: 9px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 10px;
        }

        .gw-preview-body {
            padding: 12px 14px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .gw-field-row {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 7px 10px;
            border-radius: 6px;
            background: var(--bg2);
            border: 1px solid var(--border);
            animation: gw-row-in .4s ease both;
            font-size: 11.5px;
        }

        @keyframes gw-row-in {
            from {
                opacity: 0;
                transform: translateX(-8px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .gw-field-row.gw-warn {
            border-color: rgba(251, 191, 36, .3);
            background: var(--amber-dim);
        }

        .gw-field-label {
            color: var(--text3);
            font-size: 10px;
            width: 80px;
            flex-shrink: 0;
        }

        .gw-field-val {
            flex: 1;
            color: var(--text);
            font-weight: 600;
            font-size: 11px;
        }

        .gw-ai-chip {
            font-size: 9px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 10px;
            background: var(--green-dim);
            color: var(--green);
            white-space: nowrap;
        }

        .gw-chip-warn {
            background: var(--amber-dim);
            color: var(--amber);
        }

        /* Typing animation */
        .gw-typing {
            overflow: hidden;
            white-space: nowrap;
            animation: gw-type .6s steps(20) both;
        }

        @keyframes gw-type {
            from {
                width: 0;
            }

            to {
                width: 100%;
            }
        }

        .gw-completeness-wrap {
            margin-top: 6px;
            padding-top: 8px;
            border-top: 1px solid var(--border);
        }

        .gw-completeness-label {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: var(--text2);
            margin-bottom: 5px;
        }

        .gw-completeness-pct {
            font-weight: 800;
            color: var(--green);
            font-family: var(--font-mono);
        }

        .gw-completeness-bar {
            height: 5px;
            background: var(--bg4);
            border-radius: 3px;
            overflow: hidden;
        }

        .gw-completeness-fill {
            height: 100%;
            border-radius: 3px;
            background: linear-gradient(90deg, var(--gold), var(--green));
            transition: width .8s ease;
        }

        /* ══ DOSSIERS LIST ══ */
        .gw-filter {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 14px;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text2);
            font-size: 12.5px;
            font-weight: 500;
            font-family: var(--font-body);
            border-radius: 20px;
            cursor: pointer;
            transition: all .18s;
        }

        .gw-filter:hover {
            border-color: var(--border2);
            color: var(--text);
        }

        .gw-filter.active {
            border-color: var(--gold);
            color: var(--gold);
            background: var(--gold-dim);
        }

        .gw-fc {
            font-size: 10px;
            font-family: var(--font-mono);
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 10px;
            background: var(--bg4);
            color: var(--text3);
        }

        .gw-fc-green {
            background: var(--green-dim);
            color: var(--green);
        }

        .gw-fc-amber {
            background: var(--amber-dim);
            color: var(--amber);
        }

        .gw-fc-blue {
            background: var(--blue-dim);
            color: var(--blue);
        }

        .gw-live-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 600;
            color: var(--green);
            padding: 4px 10px;
            background: var(--green-dim);
            border-radius: 20px;
        }

        .gw-dossier-list {
            display: flex;
            flex-direction: column;
        }

        .gw-dossier-row {
            display: grid;
            grid-template-columns: 1fr 90px 220px 160px;
            align-items: center;
            gap: 20px;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            transition: background .15s;
        }

        .gw-dossier-row:last-child {
            border-bottom: none;
        }

        .gw-dossier-row:hover {
            background: var(--bg3);
        }

        .gw-ds-ready {
            border-left: 3px solid var(--green);
        }

        .gw-ds-incomplete {
            border-left: 3px solid var(--amber);
        }

        .gw-ds-processing {
            border-left: 3px solid var(--blue);
            opacity: .85;
        }

        .gw-ds-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .gw-ds-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), #a07830);
            color: #111;
            font-size: 13px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .gw-av-teal {
            background: linear-gradient(135deg, var(--teal), #1a8f80);
            color: #fff;
        }

        .gw-av-purple {
            background: linear-gradient(135deg, var(--purple), #6344c2);
            color: #fff;
        }

        .gw-av-amber {
            background: linear-gradient(135deg, var(--amber), #c97a10);
            color: #111;
        }

        .gw-av-blue {
            background: linear-gradient(135deg, var(--blue), #1a5fa8);
            color: #fff;
        }

        .gw-ds-info {
            min-width: 0;
        }

        .gw-ds-name {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--text);
        }

        .gw-ds-meta {
            font-size: 11px;
            color: var(--text3);
            margin-top: 3px;
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
            font-family: var(--font-mono);
        }

        .gw-ds-source {
            color: var(--blue);
        }

        /* Score ring --*/
        .gw-ds-score-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .gw-ds-ring {
            position: relative;
            width: 52px;
            height: 52px;
        }

        .gw-ds-ring svg {
            transform: rotate(-90deg);
        }

        .gw-ds-ring span {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 900;
            color: var(--text);
            font-family: var(--font-mono);
        }

        .gw-ds-score-lbl {
            font-size: 9.5px;
            color: var(--text3);
        }

        .gw-ring-spin svg circle:last-child {
            animation: gw-dash-anim 1.5s linear infinite;
        }

        @keyframes gw-dash-anim {
            0% {
                stroke-dashoffset: 27;
            }

            100% {
                stroke-dashoffset: -80;
            }
        }

        /* Weak points */
        .gw-ds-weakpoints {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .gw-ds-weak-title {
            font-size: 10px;
            font-weight: 600;
            color: var(--text3);
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 2px;
        }

        .gw-weak-tag {
            font-size: 11.5px;
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 6px;
            white-space: nowrap;
        }

        .gw-weak-green {
            background: var(--green-dim);
            color: var(--green);
        }

        .gw-weak-amber {
            background: var(--amber-dim);
            color: var(--amber);
        }

        .gw-weak-red {
            background: var(--red-dim);
            color: var(--red);
        }

        .gw-processing-line {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 11.5px;
            color: var(--text3);
        }

        /* Actions column */
        .gw-ds-actions {
            display: flex;
            flex-direction: column;
            gap: 6px;
            align-items: flex-end;
        }

        .gw-validate-btn {
            white-space: nowrap;
        }

        /* ── Inline score in table ── */
        .gw-inline-score {
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 70px;
        }

        .gw-inline-score span {
            font-size: 12px;
            font-weight: 800;
            font-family: var(--font-mono);
        }

        .gw-inline-bar {
            height: 4px;
            background: var(--bg4);
            border-radius: 2px;
            overflow: hidden;
        }

        .gw-inline-bar div {
            height: 100%;
            border-radius: 2px;
        }

        .gw-score-green span {
            color: var(--green);
        }

        .gw-score-gold span {
            color: var(--gold);
        }

        .gw-score-amber span {
            color: var(--amber);
        }

        /* ══ MODAL GHOSTWRITER ══ */
        .gw-modal-banner {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 16px;
            background: var(--bg3);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            margin-bottom: 20px;
        }

        .gw-modal-ring {
            position: relative;
            flex-shrink: 0;
        }

        .gw-modal-ring svg {
            transform: rotate(-90deg);
        }

        .gw-modal-ring-val {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 900;
            color: var(--green);
            font-family: var(--font-mono);
        }

        .gw-sources {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .gw-source-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: 8px;
            transition: border-color .15s;
        }

        .gw-source-item:hover {
            border-color: var(--border2);
        }

        .gw-source-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .gw-source-info {
            flex: 1;
        }

        .gw-source-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
        }

        .gw-source-meta {
            font-size: 11px;
            color: var(--text3);
            margin-top: 2px;
        }

        .gw-fields-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
        }

        .gw-field-chip {
            font-size: 11px;
            font-weight: 500;
            padding: 5px 11px;
            border-radius: 20px;
        }

        .gw-chip-ok {
            background: var(--green-dim);
            color: var(--green);
        }

        .gw-chip-warn {
            background: var(--amber-dim);
            color: var(--amber);
        }

        .gw-chip-missing {
            background: var(--red-dim);
            color: var(--red);
        }

        .gw-ia-reco {
            font-size: 12.5px;
            color: var(--text2);
            line-height: 1.65;
            padding: 12px 14px;
            background: var(--bg3);
            border-radius: var(--radius-sm);
            border-left: 3px solid var(--gold);
        }

        .gw-ia-reco strong {
            color: var(--gold);
        }

        /* ── Upload modal --*/
        .gw-upload-banner {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 14px;
            background: var(--gold-dim);
            border: 1px solid rgba(201, 168, 76, .25);
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .gw-dropzone {
            border: 2px dashed var(--border2);
            border-radius: var(--radius);
            padding: 28px 20px;
            text-align: center;
            cursor: pointer;
            transition: all .2s;
            background: var(--bg3);
        }

        .gw-dropzone:hover {
            border-color: var(--gold);
            background: var(--gold-glow);
        }

        .gw-dz-icon {
            font-size: 28px;
            margin-bottom: 8px;
        }

        .gw-dz-text {
            font-size: 13px;
            color: var(--text2);
            margin-bottom: 4px;
        }

        .gw-dz-sub {
            font-size: 11px;
            color: var(--text3);
        }

        .gw-progress-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12.5px;
            color: var(--text2);
        }
    </style>



    <script>
        // ── Ghostwriter filter tabs ──
        window.gwFilter = function(el, state) {
            el.closest('.panel-body').querySelectorAll('.gw-filter').forEach(b => b.classList.remove('active'));
            el.classList.add('active');
            document.querySelectorAll('.gw-dossier-row').forEach(row => {
                const rs = row.dataset.state;
                row.style.display = (state === 'all' || rs === state) ? '' : 'none';
            });
        }

        // ── Validate one ──
        window.gwValidateOne = function(btn, name) {
            if (btn) {
                const row = btn.closest('.gw-dossier-row');
                if (row) {
                    row.style.opacity = '.5';
                    row.style.pointerEvents = 'none';
                }
            }
            showToast('✅ Dossier de ' + name + ' validé en 1 clic!', 'success');
        }

        // ── Validate all ──
        window.gwValidateAll = function() {
            document.querySelectorAll('.gw-dossier-row[data-state="ready"]').forEach(row => {
                row.style.opacity = '.45';
                row.style.pointerEvents = 'none';
            });
            showToast('⚡ 3 dossiers validés en 1 clic — Ghostwriter IA!', 'success');
        }

        // ── Upload simulation ──
        window.gwSimulateUpload = function() {
            const wrap = document.getElementById('gw-upload-progress');
            const bar = document.getElementById('gw-progress-bar');
            const pct = document.getElementById('gw-progress-pct');
            const txt = document.getElementById('gw-progress-text');
            const flds = document.getElementById('gw-progress-fields');
            if (!wrap) return;

            wrap.style.display = 'block';
            flds.innerHTML = '';

            const steps = [{
                    p: 15,
                    t: '🔍 Lecture du document…'
                },
                {
                    p: 35,
                    t: '🎨 Scan portfolio en ligne…'
                },
                {
                    p: 55,
                    t: '📂 Comparaison anciens dossiers…'
                },
                {
                    p: 72,
                    t: '🔗 Analyse des réseaux…'
                },
                {
                    p: 88,
                    t: '✍️ Remplissage du formulaire…'
                },
                {
                    p: 100,
                    t: '✅ Analyse terminée — 80% rempli!'
                },
            ];

            const fields = ['✓ Nom', '✓ Discipline', '✓ Expositions', '✓ Portfolio', '✓ Biographie', '⚠ Fiscal'];
            let si = 0;
            const iv = setInterval(() => {
                if (si >= steps.length) {
                    clearInterval(iv);
                    return;
                }
                const s = steps[si];
                bar.style.width = s.p + '%';
                pct.textContent = s.p + '%';
                txt.textContent = s.t;
                if (si > 0 && si <= fields.length) {
                    const chip = document.createElement('span');
                    const f = fields[si - 1];
                    chip.className = 'gw-field-chip ' + (f.startsWith('⚠') ? 'gw-chip-warn' : 'gw-chip-ok');
                    chip.textContent = f;
                    flds.appendChild(chip);
                }
                si++;
            }, 700);

            setTimeout(() => showToast('🤖 Ghostwriter IA — 80% du dossier complété!', 'success'), 4500);
        }
    </script>

@endsection
