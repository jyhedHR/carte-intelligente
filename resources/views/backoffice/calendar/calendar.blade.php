@extends('shared.layouts.backoffice')

@section('title', 'Calendrier')
@section('breadcrumb', 'Calendrier')

@section('content')

    {{-- ══ IA ASSISTANT STRIP ══ --}}
    <div class="cal-ia-strip">
        <div class="cal-ia-strip-inner">
            <div class="cal-ia-badge"><span class="cal-pulse"></span> IA Calendrier Active</div>
            <div class="cal-ia-insights" id="iaInsightText">
                🤖 <strong>Analyse IA :</strong> Juin est votre mois le plus chargé (+3 événements importants).
                Réunion de département prévue dans <strong>3 jours</strong>. Aucun conflit détecté cette semaine.
            </div>
            <button class="btn btn-gold btn-sm" onclick="calOpenIaPanel()">🤖 Assistant IA</button>
        </div>
    </div>

    {{-- ══ KPI ROW ══ --}}
    <div class="kpi-grid" style="margin-bottom:20px;">
        <div class="kpi-card blue">
            <div class="kpi-icon">📅</div>
            <div class="kpi-value" id="kpiEvents">0</div>
            <div class="kpi-label">Événements</div>
            <div class="kpi-delta up" id="kpiEventsDelta">↑ ce mois</div>
        </div>
        <div class="kpi-card green">
            <div class="kpi-icon">📋</div>
            <div class="kpi-value" id="kpiLogs">0</div>
            <div class="kpi-label">Journaux</div>
            <div class="kpi-delta flat">→ Total</div>
        </div>
        <div class="kpi-card gold">
            <div class="kpi-icon">🤝</div>
            <div class="kpi-value" id="kpiMeetings">0</div>
            <div class="kpi-label">Réunions</div>
            <div class="kpi-delta up">↑ planifiées</div>
        </div>
        <div class="kpi-card red">
            <div class="kpi-icon">⚠️</div>
            <div class="kpi-value" id="kpiImportant">0</div>
            <div class="kpi-label">Prioritaires</div>
            <div class="kpi-delta down">↓ à traiter</div>
        </div>
    </div>

    {{-- ══ TOOLBAR ══ --}}
    <div class="panel" style="margin-bottom:16px;">
        <div class="panel-body" style="padding:14px 20px;">
            <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">

                {{-- Month nav --}}
                <div style="display:flex; align-items:center; gap:8px;">
                    <button class="btn btn-outline btn-sm" id="calPrevBtn">◀</button>
                    <div class="cal-month-display" id="calMonthDisplay">Avril 2026</div>
                    <button class="btn btn-outline btn-sm" id="calNextBtn">▶</button>
                    <button class="btn btn-ghost btn-sm" id="calTodayBtn">Aujourd'hui</button>
                </div>

                <div class="cal-toolbar-sep"></div>

                {{-- Filters --}}
                <select class="form-select" id="calDeptFilter" style="width:170px;">
                    <option value="">Tous Départements</option>
                    <option value="art_plastique">Art Plastique</option>
                    <option value="musique">Musique</option>
                    <option value="dance">Danse</option>
                    <option value="theater">Théâtre</option>
                    <option value="administration">Administration</option>
                </select>

                <select class="form-select" id="calTypeFilter" style="width:150px;">
                    <option value="">Tous les types</option>
                    <option value="event">Événements</option>
                    <option value="log">Journaux</option>
                    <option value="meeting">Réunions</option>
                </select>

                <input class="form-input" id="calSearch" placeholder="🔍 Rechercher…" style="width:200px; flex:none;">

                <div style="margin-left:auto; display:flex; gap:8px;">
                    <button class="btn btn-outline btn-sm" onclick="calOpenReport()">📊 Rapport</button>
                    <button class="btn btn-gold btn-sm" onclick="calOpenAdd()">+ Événement</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ MAIN GRID ══ --}}
    <div class="cal-layout">

        {{-- LEFT — Calendar --}}
        <div class="cal-main-panel">
            <div class="panel" style="height:100%;">
                <div class="panel-body" style="padding:0;">

                    {{-- Day headers --}}
                    <div class="cal-day-headers">
                        <div>Lun</div>
                        <div>Mar</div>
                        <div>Mer</div>
                        <div>Jeu</div>
                        <div>Ven</div>
                        <div class="cal-weekend">Sam</div>
                        <div class="cal-weekend">Dim</div>
                    </div>

                    {{-- Grid --}}
                    <div class="cal-grid" id="calGrid"></div>

                </div>
            </div>
        </div>

        {{-- RIGHT — Sidebar --}}
        <div class="cal-sidebar">

            {{-- Day detail panel --}}
            <div class="panel" id="calDayPanel">
                <div class="panel-head" style="padding:14px 16px;">
                    <div>
                        <div class="panel-title" id="calDayPanelTitle">Sélectionnez un jour</div>
                        <div class="panel-sub" id="calDayPanelSub">Cliquez sur une date</div>
                    </div>
                    <button class="btn btn-gold btn-sm" id="calAddEventDay" onclick="calOpenAdd()" style="display:none;">+
                        Ajouter</button>
                </div>

                {{-- IA analysis for selected day --}}
                <div id="calDayIa" class="cal-day-ia" style="display:none;">
                    <div class="cal-day-ia-header">
                        <span>🤖 Analyse IA du jour</span>
                    </div>
                    <div class="cal-day-ia-text" id="calDayIaText"></div>
                </div>

                <div class="panel-body no-pad" id="calDayEvents">
                    <div class="cal-empty-day">
                        <div style="font-size:32px; opacity:.3;">📅</div>
                        <div style="font-size:13px; color:var(--text3); margin-top:8px;">Sélectionnez un jour</div>
                    </div>
                </div>
            </div>

            {{-- Upcoming --}}
            <div class="panel">
                <div class="panel-head" style="padding:14px 16px;">
                    <div class="panel-title">À venir (7j)</div>
                    <span class="badge blue" id="calUpcomingCount">0</span>
                </div>
                <div class="panel-body no-pad" id="calUpcoming">
                    <div class="cal-empty-day" style="padding:20px;">
                        <div style="font-size:11px; color:var(--text3);">Aucun événement imminent</div>
                    </div>
                </div>
            </div>

            {{-- Dept stats --}}
            <div class="panel">
                <div class="panel-head" style="padding:14px 16px;">
                    <div class="panel-title">Par département</div>
                </div>
                <div class="panel-body" id="calDeptStats" style="display:flex; flex-direction:column; gap:8px;"></div>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════
     MODAL — DAY DETAIL (full view)
══════════════════════════════════ --}}
    <div id="modal-cal-day" class="modal">
        <div class="modal-content" style="max-width:620px;">
            <div class="modal-header">
                <div class="modal-title" id="calDayModalTitle">Événements du jour</div>
                <button class="modal-close" onclick="closeModal('modal-cal-day')">×</button>
            </div>
            <div class="modal-body" id="calDayModalBody"></div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('modal-cal-day')">Fermer</button>
                <button class="btn btn-gold" onclick="closeModal('modal-cal-day'); calOpenAdd()">+ Ajouter
                    événement</button>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════
     MODAL — EVENT DETAIL
══════════════════════════════════ --}}
    <div id="modal-cal-event" class="modal">
        <div class="modal-content" style="max-width:500px;">
            <div class="modal-header">
                <div>
                    <div class="modal-title" id="calEvtTitle"></div>
                    <div style="margin-top:4px;" id="calEvtBadges"></div>
                </div>
                <button class="modal-close" onclick="closeModal('modal-cal-event')">×</button>
            </div>
            <div class="modal-body">
                <div class="cal-evt-detail-grid">
                    <div class="cal-evt-field"><span class="cal-evt-lbl">📅 Date</span><span id="calEvtDate"></span>
                    </div>
                    <div class="cal-evt-field"><span class="cal-evt-lbl">🕐 Heure</span><span id="calEvtTime"></span>
                    </div>
                    <div class="cal-evt-field"><span class="cal-evt-lbl">🏛️ Département</span><span
                            id="calEvtDept"></span></div>
                    <div class="cal-evt-field"><span class="cal-evt-lbl">📌 Statut</span><span id="calEvtStatus"></span>
                    </div>
                </div>
                <div class="form-group" style="margin-top:14px;">
                    <label class="form-label">Description</label>
                    <div class="cal-evt-desc" id="calEvtDesc"></div>
                </div>
                <div class="cal-evt-ia-reco" id="calEvtIa"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('modal-cal-event')">Fermer</button>
                <button class="btn btn-ghost btn-sm btn-red"
                    onclick="showToast('Événement supprimé', 'success'); closeModal('modal-cal-event')">🗑️
                    Supprimer</button>
                <button class="btn btn-gold" onclick="closeModal('modal-cal-event'); calOpenEdit()">✏️ Modifier</button>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════
     MODAL — ADD / EDIT EVENT
══════════════════════════════════ --}}
    <div id="modal-cal-add" class="modal">
        <div class="modal-content" style="max-width:520px;">
            <div class="modal-header">
                <div class="modal-title" id="calAddTitle">+ Nouvel Événement</div>
                <button class="modal-close" onclick="closeModal('modal-cal-add')">×</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Titre *</label>
                    <input type="text" class="form-input" id="calAddName"
                        placeholder="Ex: Exposition d'Art Contemporain">
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                    <div class="form-group">
                        <label class="form-label">Type *</label>
                        <select class="form-select" id="calAddType">
                            <option value="event">Événement</option>
                            <option value="meeting">Réunion</option>
                            <option value="log">Journal</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Priorité</label>
                        <select class="form-select" id="calAddStatus">
                            <option value="normal">Normal</option>
                            <option value="important">Important</option>
                            <option value="pending">En attente</option>
                        </select>
                    </div>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                    <div class="form-group">
                        <label class="form-label">Date *</label>
                        <input type="date" class="form-input" id="calAddDate">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Heure *</label>
                        <input type="time" class="form-input" id="calAddTime" value="09:00">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Département</label>
                    <select class="form-select" id="calAddDept">
                        <option value="administration">Administration</option>
                        <option value="art_plastique">Art Plastique</option>
                        <option value="musique">Musique</option>
                        <option value="dance">Danse</option>
                        <option value="theater">Théâtre</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-textarea" id="calAddDesc" placeholder="Détails de l'événement…" rows="3"></textarea>
                </div>
                {{-- IA Conflict checker --}}
                <div class="cal-ia-conflict-check" id="calConflictCheck" style="display:none;">
                    <div style="display:flex; align-items:center; gap:8px; font-size:12.5px;">
                        <span class="cal-pulse"></span>
                        <span id="calConflictText">IA vérifie les conflits…</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('modal-cal-add')">Annuler</button>
                <button class="btn btn-outline btn-sm" onclick="calCheckConflicts()">🤖 Vérifier conflits IA</button>
                <button class="btn btn-gold" onclick="calSaveEvent()">💾 Enregistrer</button>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════
     MODAL — RAPPORT (Day/Month/Year filter)
══════════════════════════════════ --}}
    <div id="modal-cal-report" class="modal">
        <div class="modal-content" style="max-width:580px;">
            <div class="modal-header">
                <div class="modal-title">📊 Générer un Rapport</div>
                <button class="modal-close" onclick="closeModal('modal-cal-report')">×</button>
            </div>
            <div class="modal-body">

                {{-- Mode selector --}}
                <div class="form-group">
                    <label class="form-label">Mode de période</label>
                    <div class="cal-report-modes">
                        <button class="cal-rmode active" data-mode="month" onclick="calSetReportMode(this,'month')">📅
                            Mois</button>
                        <button class="cal-rmode" data-mode="range" onclick="calSetReportMode(this,'range')">📆 Plage de
                            dates</button>
                        <button class="cal-rmode" data-mode="year" onclick="calSetReportMode(this,'year')">🗓️
                            Année</button>
                        <button class="cal-rmode" data-mode="day" onclick="calSetReportMode(this,'day')">☀️ Jour</button>
                    </div>
                </div>

                {{-- Mode: Month --}}
                <div id="rmode-month" class="cal-rmode-panel">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                        <div class="form-group">
                            <label class="form-label">Mois</label>
                            <select class="form-select" id="rMonthSingle">
                                <option value="0">Janvier</option>
                                <option value="1">Février</option>
                                <option value="2">Mars</option>
                                <option value="3">Avril</option>
                                <option value="4">Mai</option>
                                <option value="5">Juin</option>
                                <option value="6">Juillet</option>
                                <option value="7">Août</option>
                                <option value="8">Septembre</option>
                                <option value="9">Octobre</option>
                                <option value="10">Novembre</option>
                                <option value="11">Décembre</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Année</label>
                            <select class="form-select" id="rYearSingle">
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                                <option value="2026" selected>2026</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Mode: Range --}}
                <div id="rmode-range" class="cal-rmode-panel" style="display:none;">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                        <div class="form-group">
                            <label class="form-label">Mois début</label>
                            <select class="form-select" id="rStartMonth">
                                <option value="0">Janvier</option>
                                <option value="1">Février</option>
                                <option value="2">Mars</option>
                                <option value="3">Avril</option>
                                <option value="4">Mai</option>
                                <option value="5">Juin</option>
                                <option value="6">Juillet</option>
                                <option value="7">Août</option>
                                <option value="8">Septembre</option>
                                <option value="9">Octobre</option>
                                <option value="10">Novembre</option>
                                <option value="11">Décembre</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Mois fin</label>
                            <select class="form-select" id="rEndMonth">
                                <option value="0">Janvier</option>
                                <option value="1">Février</option>
                                <option value="2">Mars</option>
                                <option value="3">Avril</option>
                                <option value="4">Mai</option>
                                <option value="5">Juin</option>
                                <option value="6">Juillet</option>
                                <option value="7">Août</option>
                                <option value="8">Septembre</option>
                                <option value="9">Octobre</option>
                                <option value="10">Novembre</option>
                                <option value="11">Décembre</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Année</label>
                        <select class="form-select" id="rRangeYear">
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026" selected>2026</option>
                        </select>
                    </div>
                </div>

                {{-- Mode: Year --}}
                <div id="rmode-year" class="cal-rmode-panel" style="display:none;">
                    <div class="form-group">
                        <label class="form-label">Année complète</label>
                        <select class="form-select" id="rYear">
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026" selected>2026</option>
                        </select>
                    </div>
                </div>

                {{-- Mode: Day --}}
                <div id="rmode-day" class="cal-rmode-panel" style="display:none;">
                    <div class="form-group">
                        <label class="form-label">Date précise</label>
                        <input type="date" class="form-input" id="rDay" value="2026-04-12">
                    </div>
                </div>

                {{-- Common filters --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-top:4px;">
                    <div class="form-group">
                        <label class="form-label">Département</label>
                        <select class="form-select" id="rDept">
                            <option value="">Tous</option>
                            <option value="art_plastique">Art Plastique</option>
                            <option value="musique">Musique</option>
                            <option value="dance">Danse</option>
                            <option value="theater">Théâtre</option>
                            <option value="administration">Administration</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Type d'événement</label>
                        <select class="form-select" id="rType">
                            <option value="">Tous</option>
                            <option value="event">Événements</option>
                            <option value="meeting">Réunions</option>
                            <option value="log">Journaux</option>
                        </select>
                    </div>
                </div>

                {{-- Preview --}}
                <div class="cal-report-preview" id="calReportPreview">
                    <div class="cal-report-preview-header">
                        <span>📋 Aperçu du rapport</span>
                        <button class="btn btn-ghost btn-sm" onclick="calPreviewReport()">🔄 Actualiser</button>
                    </div>
                    <div id="calReportPreviewBody" style="font-size:12px; color:var(--text2); line-height:1.8;">
                        Configurez les filtres puis cliquez sur Actualiser.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('modal-cal-report')">Annuler</button>
                <button class="btn btn-outline" onclick="calPreviewReport()">👁️ Aperçu</button>
                <button class="btn btn-gold" onclick="calDownloadReport()">📥 Télécharger</button>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════
     MODAL — IA ASSISTANT
══════════════════════════════════ --}}
    <div id="modal-cal-ia" class="modal">
        <div class="modal-content" style="max-width:560px;">
            <div class="modal-header">
                <div>
                    <div class="modal-title">🤖 Assistant IA — Calendrier</div>
                    <div style="font-size:11px; color:var(--text3); margin-top:3px;">Analyse intelligente & recommandations
                    </div>
                </div>
                <button class="modal-close" onclick="closeModal('modal-cal-ia')">×</button>
            </div>
            <div class="modal-body">

                {{-- IA tabs --}}
                <div class="cal-ia-tabs">
                    <button class="cal-ia-tab active" onclick="calIaTab(this,'ia-insights')">💡 Insights</button>
                    <button class="cal-ia-tab" onclick="calIaTab(this,'ia-conflicts')">⚠️ Conflits</button>
                    <button class="cal-ia-tab" onclick="calIaTab(this,'ia-suggestions')">✨ Suggestions</button>
                    <button class="cal-ia-tab" onclick="calIaTab(this,'ia-load')">📊 Charge</button>
                </div>

                {{-- Insights --}}
                <div id="ia-insights" class="cal-ia-tab-panel active">
                    <div class="cal-ia-insight-card cal-ia-gold">
                        <div class="cal-ia-ic-icon">🔥</div>
                        <div>
                            <div class="cal-ia-ic-title">Pic d'activité détecté</div>
                            <div class="cal-ia-ic-desc">Juin 2026 concentre 6 événements dont 2 prioritaires. Pensez à
                                répartir la charge sur juillet.</div>
                        </div>
                    </div>
                    <div class="cal-ia-insight-card cal-ia-teal">
                        <div class="cal-ia-ic-icon">📈</div>
                        <div>
                            <div class="cal-ia-ic-title">Tendance : Art Plastique</div>
                            <div class="cal-ia-ic-desc">Le département Art Plastique a +40% d'activité ce trimestre vs Q1.
                                Allocation budgétaire à revoir.</div>
                        </div>
                    </div>
                    <div class="cal-ia-insight-card cal-ia-blue">
                        <div class="cal-ia-ic-icon">⏰</div>
                        <div>
                            <div class="cal-ia-ic-title">Rappel automatique</div>
                            <div class="cal-ia-ic-desc">3 événements importants dans les 14 prochains jours. Les
                                participants n'ont pas encore été notifiés.</div>
                        </div>
                    </div>
                </div>

                {{-- Conflicts --}}
                <div id="ia-conflicts" class="cal-ia-tab-panel" style="display:none;">
                    <div class="cal-ia-insight-card cal-ia-red">
                        <div class="cal-ia-ic-icon">⚡</div>
                        <div>
                            <div class="cal-ia-ic-title">Conflit potentiel — 25 Juin</div>
                            <div class="cal-ia-ic-desc">Dance Summer Intensive et Mid-Year Review partagent la même salle
                                principale. Vérifier la disponibilité.</div>
                        </div>
                    </div>
                    <div class="cal-ia-insight-card cal-ia-amber">
                        <div class="cal-ia-ic-icon">📅</div>
                        <div>
                            <div class="cal-ia-ic-title">Chevauchement — Département Musique</div>
                            <div class="cal-ia-ic-desc">2 événements musicaux le même weekend de juillet. Risque de fatigue
                                des participants.</div>
                        </div>
                    </div>
                    <div
                        style="padding:12px; background:var(--green-dim); border-radius:var(--radius-sm); font-size:12.5px; color:var(--green);">
                        ✅ Aucun conflit critique détecté pour les 7 prochains jours.
                    </div>
                </div>

                {{-- Suggestions --}}
                <div id="ia-suggestions" class="cal-ia-tab-panel" style="display:none;">
                    <div class="cal-ia-sugg-list">
                        <div class="cal-ia-sugg">
                            <div class="cal-ia-sugg-icon" style="background:var(--gold-dim); color:var(--gold);">💡</div>
                            <div class="cal-ia-sugg-body">
                                <div class="cal-ia-sugg-title">Planifier révision budgétaire T3</div>
                                <div class="cal-ia-sugg-meta">Recommandé pour fin juillet · Administration</div>
                            </div>
                            <button class="btn btn-gold btn-sm"
                                onclick="calIaAddSugg('Révision Budgétaire T3', '2026-07-28')">+ Ajouter</button>
                        </div>
                        <div class="cal-ia-sugg">
                            <div class="cal-ia-sugg-icon" style="background:var(--teal-dim); color:var(--teal);">🎨</div>
                            <div class="cal-ia-sugg-body">
                                <div class="cal-ia-sugg-title">Workshop Art Digital — tendance détectée</div>
                                <div class="cal-ia-sugg-meta">Recommandé pour août · Art Plastique</div>
                            </div>
                            <button class="btn btn-gold btn-sm"
                                onclick="calIaAddSugg('Workshop Art Digital', '2026-08-20')">+ Ajouter</button>
                        </div>
                        <div class="cal-ia-sugg">
                            <div class="cal-ia-sugg-icon" style="background:var(--purple-dim); color:var(--purple);">🤝
                            </div>
                            <div class="cal-ia-sugg-body">
                                <div class="cal-ia-sugg-title">Réunion de rentrée — tous départements</div>
                                <div class="cal-ia-sugg-meta">Recommandé 1er septembre · Administration</div>
                            </div>
                            <button class="btn btn-gold btn-sm"
                                onclick="calIaAddSugg('Réunion Rentrée Générale', '2026-09-01')">+ Ajouter</button>
                        </div>
                    </div>
                </div>

                {{-- Load analysis --}}
                <div id="ia-load" class="cal-ia-tab-panel" style="display:none;">
                    <div style="margin-bottom:12px; font-size:12.5px; color:var(--text2);">Charge par département · 12 mois
                        2026</div>
                    <div style="display:flex; flex-direction:column; gap:10px;">
                        <div class="confidence-bar-row">
                            <div class="cb-label">Art Plastique</div>
                            <div class="cb-bar">
                                <div class="cb-fill" style="width:85%; background:var(--gold);"></div>
                            </div>
                            <div class="cb-pct">85%</div>
                        </div>
                        <div class="confidence-bar-row">
                            <div class="cb-label">Administration</div>
                            <div class="cb-bar">
                                <div class="cb-fill" style="width:100%; background:var(--teal);"></div>
                            </div>
                            <div class="cb-pct">100%</div>
                        </div>
                        <div class="confidence-bar-row">
                            <div class="cb-label">Musique</div>
                            <div class="cb-bar">
                                <div class="cb-fill" style="width:68%; background:var(--blue);"></div>
                            </div>
                            <div class="cb-pct">68%</div>
                        </div>
                        <div class="confidence-bar-row">
                            <div class="cb-label">Danse</div>
                            <div class="cb-bar">
                                <div class="cb-fill" style="width:72%; background:var(--purple);"></div>
                            </div>
                            <div class="cb-pct">72%</div>
                        </div>
                        <div class="confidence-bar-row">
                            <div class="cb-label">Théâtre</div>
                            <div class="cb-bar">
                                <div class="cb-fill" style="width:30%; background:var(--red);"></div>
                            </div>
                            <div class="cb-pct">30%</div>
                        </div>
                    </div>
                    <div class="cal-ia-reco" style="margin-top:16px;">
                        🤖 Théâtre est sous-utilisé (30%). Envisagez de réaffecter 2 événements musique vers ce créneau pour
                        équilibrer la charge.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeModal('modal-cal-ia')">Fermer</button>
                <button class="btn btn-gold"
                    onclick="showToast('Rapport IA généré!', 'success'); closeModal('modal-cal-ia')">📥 Exporter rapport
                    IA</button>
            </div>
        </div>
    </div>




    <style>
        /* ══ IA STRIP ══ */
        .cal-ia-strip {
            background: linear-gradient(90deg, var(--bg2), var(--gold-glow));
            border: 1px solid rgba(201, 168, 76, .2);
            border-radius: var(--radius);
            margin-bottom: 16px;
            padding: 10px 18px;
        }

        .cal-ia-strip-inner {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .cal-ia-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--gold);
            background: var(--gold-dim);
            padding: 4px 10px;
            border-radius: 20px;
            border: 1px solid rgba(201, 168, 76, .25);
            flex-shrink: 0;
        }

        .cal-pulse {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--gold);
            animation: cal-blink 1.6s infinite;
            flex-shrink: 0;
            display: inline-block;
        }

        @keyframes cal-blink {

            0%,
            100% {
                opacity: 1;
                transform: scale(1)
            }

            50% {
                opacity: .4;
                transform: scale(.7)
            }
        }

        .cal-ia-insights {
            flex: 1;
            font-size: 12.5px;
            color: var(--text2);
        }

        .cal-ia-insights strong {
            color: var(--text);
        }

        /* ══ TOOLBAR ══ */
        .cal-month-display {
            font-size: 15px;
            font-weight: 800;
            color: var(--text);
            min-width: 140px;
            text-align: center;
        }

        .cal-toolbar-sep {
            width: 1px;
            height: 28px;
            background: var(--border);
            flex-shrink: 0;
        }

        /* ══ LAYOUT ══ */
        .cal-layout {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 16px;
            align-items: start;
        }

        .cal-main-panel {
            min-width: 0;
        }

        .cal-sidebar {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* ══ DAY HEADERS ══ */
        .cal-day-headers {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            padding: 12px 16px 8px;
            border-bottom: 1px solid var(--border);
            gap: 4px;
        }

        .cal-day-headers div {
            text-align: center;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--text3);
            padding: 4px 0;
        }

        .cal-weekend {
            color: var(--gold) !important;
        }

        /* ══ GRID ══ */
        .cal-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 3px;
            padding: 12px;
        }

        .cal-cell {
            min-height: 90px;
            padding: 8px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--bg3);
            cursor: pointer;
            transition: all .18s;
            display: flex;
            flex-direction: column;
            gap: 4px;
            position: relative;
        }

        .cal-cell:hover {
            border-color: var(--gold);
            background: var(--bg4);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(201, 168, 76, .1);
        }

        .cal-cell.cal-other {
            opacity: .38;
            cursor: default;
            pointer-events: none;
        }

        .cal-cell.cal-other:hover {
            transform: none;
        }

        .cal-cell.cal-today {
            background: var(--gold-dim);
            border-color: var(--gold);
        }

        .cal-cell.cal-selected {
            border-color: var(--gold);
            box-shadow: 0 0 0 2px var(--gold);
            background: var(--bg4);
        }

        .cal-cell.cal-has-events {
            background: var(--bg2);
        }

        .cal-cell-num {
            font-size: 12px;
            font-weight: 700;
            color: var(--text2);
            line-height: 1;
        }

        .cal-today .cal-cell-num {
            color: var(--gold);
            font-size: 13px;
        }

        .cal-cell-num-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cal-today-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--gold);
            flex-shrink: 0;
        }

        .cal-cell-events {
            display: flex;
            flex-direction: column;
            gap: 3px;
            flex: 1;
            overflow: hidden;
        }

        .cal-mini-evt {
            font-size: 9.5px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: pointer;
            line-height: 1.4;
        }

        .cal-mini-evt.event {
            background: var(--blue-dim);
            color: var(--blue);
        }

        .cal-mini-evt.meeting {
            background: var(--purple-dim);
            color: var(--purple);
        }

        .cal-mini-evt.log {
            background: var(--green-dim);
            color: var(--green);
        }

        .cal-mini-evt.important {
            background: var(--red-dim);
            color: var(--red);
        }

        .cal-mini-evt:hover {
            opacity: .8;
        }

        .cal-more-badge {
            font-size: 9px;
            font-weight: 700;
            color: var(--text3);
            padding: 1px 5px;
            background: var(--bg4);
            border-radius: 4px;
            cursor: pointer;
            width: fit-content;
        }

        /* ══ SIDEBAR ══ */
        .cal-empty-day {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 28px 16px;
        }

        .cal-day-ia {
            border-top: 1px solid var(--border);
            margin: 0 16px;
            padding: 10px 0;
        }

        .cal-day-ia-header {
            font-size: 10.5px;
            font-weight: 700;
            color: var(--gold);
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 6px;
        }

        .cal-day-ia-text {
            font-size: 12px;
            color: var(--text2);
            line-height: 1.6;
        }

        .cal-side-evt {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            cursor: pointer;
            transition: background .15s;
        }

        .cal-side-evt:hover {
            background: var(--bg3);
        }

        .cal-side-evt:last-child {
            border-bottom: none;
        }

        .cal-side-evt-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: 4px;
        }

        .cal-side-evt-info {
            flex: 1;
            min-width: 0;
        }

        .cal-side-evt-title {
            font-size: 12.5px;
            font-weight: 600;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .cal-side-evt-meta {
            font-size: 11px;
            color: var(--text3);
            margin-top: 2px;
        }

        .cal-upcoming-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 16px;
            border-bottom: 1px solid var(--border);
            cursor: pointer;
            transition: background .15s;
        }

        .cal-upcoming-item:hover {
            background: var(--bg3);
        }

        .cal-upcoming-item:last-child {
            border-bottom: none;
        }

        .cal-upcoming-date {
            min-width: 38px;
            text-align: center;
            padding: 4px 6px;
            background: var(--bg4);
            border-radius: 6px;
            border: 1px solid var(--border);
        }

        .cal-upcoming-date-d {
            font-size: 14px;
            font-weight: 900;
            color: var(--text);
            line-height: 1;
        }

        .cal-upcoming-date-m {
            font-size: 9px;
            color: var(--text3);
            text-transform: uppercase;
        }

        .cal-upcoming-info {
            flex: 1;
            min-width: 0;
        }

        .cal-upcoming-title {
            font-size: 12px;
            font-weight: 600;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .cal-upcoming-dept {
            font-size: 10.5px;
            color: var(--text3);
        }

        /* ══ EVENT DETAIL MODAL ══ */
        .cal-evt-detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .cal-evt-field {
            display: flex;
            flex-direction: column;
            gap: 3px;
            padding: 10px 12px;
            background: var(--bg3);
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
        }

        .cal-evt-lbl {
            font-size: 10px;
            color: var(--text3);
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .cal-evt-desc {
            font-size: 13px;
            color: var(--text2);
            line-height: 1.65;
            padding: 12px;
            background: var(--bg3);
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
        }

        .cal-evt-ia-reco {
            margin-top: 14px;
            padding: 12px 14px;
            background: var(--gold-dim);
            border-left: 3px solid var(--gold);
            border-radius: var(--radius-sm);
            font-size: 12px;
            color: var(--text2);
            line-height: 1.6;
        }

        /* ══ ADD EVENT ══ */
        .cal-ia-conflict-check {
            padding: 10px 12px;
            background: var(--blue-dim);
            border: 1px solid rgba(96, 165, 250, .25);
            border-radius: var(--radius-sm);
            margin-top: 8px;
        }

        /* ══ REPORT MODAL ══ */
        .cal-report-modes {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .cal-rmode {
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

        .cal-rmode:hover {
            border-color: var(--border2);
            color: var(--text);
        }

        .cal-rmode.active {
            border-color: var(--gold);
            color: var(--gold);
            background: var(--gold-dim);
        }

        .cal-rmode-panel {
            margin-top: 4px;
        }

        .cal-report-preview {
            margin-top: 16px;
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .cal-report-preview-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
            font-size: 11.5px;
            font-weight: 700;
            color: var(--text2);
        }

        #calReportPreviewBody {
            padding: 12px 14px;
        }

        .cal-report-preview-row {
            display: flex;
            gap: 12px;
            padding: 6px 0;
            border-bottom: 1px solid var(--border);
            font-size: 12px;
        }

        .cal-report-preview-row:last-child {
            border-bottom: none;
        }

        .cal-rpr-date {
            color: var(--text3);
            width: 80px;
            flex-shrink: 0;
            font-family: var(--font-mono);
        }

        .cal-rpr-title {
            color: var(--text);
            font-weight: 600;
            flex: 1;
        }

        .cal-rpr-dept {
            color: var(--text3);
            font-size: 11px;
        }

        /* ══ IA PANEL ══ */
        .cal-ia-tabs {
            display: flex;
            gap: 4px;
            margin-bottom: 14px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 0;
        }

        .cal-ia-tab {
            padding: 8px 14px;
            border: none;
            background: transparent;
            color: var(--text2);
            font-size: 12.5px;
            font-weight: 500;
            font-family: var(--font-body);
            cursor: pointer;
            border-bottom: 2px solid transparent;
            margin-bottom: -1px;
            transition: all .18s;
            border-radius: var(--radius-sm) var(--radius-sm) 0 0;
        }

        .cal-ia-tab:hover {
            color: var(--text);
            background: var(--bg3);
        }

        .cal-ia-tab.active {
            color: var(--gold);
            border-bottom-color: var(--gold);
        }

        .cal-ia-tab-panel {
            display: none;
            flex-direction: column;
            gap: 10px;
        }

        .cal-ia-tab-panel.active {
            display: flex;
        }

        .cal-ia-insight-card {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 14px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
        }

        .cal-ia-gold {
            background: var(--gold-dim);
            border-color: rgba(201, 168, 76, .25);
        }

        .cal-ia-teal {
            background: var(--teal-dim);
            border-color: rgba(45, 212, 191, .2);
        }

        .cal-ia-blue {
            background: var(--blue-dim);
            border-color: rgba(96, 165, 250, .2);
        }

        .cal-ia-red {
            background: var(--red-dim);
            border-color: rgba(248, 113, 113, .2);
        }

        .cal-ia-amber {
            background: var(--amber-dim);
            border-color: rgba(251, 191, 36, .2);
        }

        .cal-ia-ic-icon {
            font-size: 20px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .cal-ia-ic-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 4px;
        }

        .cal-ia-ic-desc {
            font-size: 12px;
            color: var(--text2);
            line-height: 1.6;
        }

        .cal-ia-sugg-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .cal-ia-sugg {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            transition: border-color .15s;
        }

        .cal-ia-sugg:hover {
            border-color: var(--border2);
        }

        .cal-ia-sugg-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .cal-ia-sugg-body {
            flex: 1;
            min-width: 0;
        }

        .cal-ia-sugg-title {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--text);
        }

        .cal-ia-sugg-meta {
            font-size: 11px;
            color: var(--text3);
            margin-top: 2px;
        }

        .cal-ia-reco {
            font-size: 12.5px;
            color: var(--text2);
            line-height: 1.65;
            padding: 12px 14px;
            background: var(--gold-dim);
            border-left: 3px solid var(--gold);
            border-radius: var(--radius-sm);
        }

        /* ══ RESPONSIVE ══ */
        @media (max-width:1100px) {
            .cal-layout {
                grid-template-columns: 1fr;
            }

            .cal-sidebar {
                grid-row: 1;
                flex-direction: row;
                overflow-x: auto;
            }

            .cal-sidebar .panel {
                min-width: 260px;
            }
        }

        @media (max-width:700px) {
            .cal-cell {
                min-height: 60px;
                padding: 4px;
            }

            .cal-mini-evt {
                display: none;
            }
        }
    </style>



    <script>
        // ══ DATA ══
        const CAL_EVENTS = [{
                date: '2026-01-05',
                type: 'event',
                title: 'Exposition Art',
                department: 'art_plastique',
                description: 'Cérémonie d\'ouverture exposition annuelle',
                status: 'important',
                time: '14:00'
            },
            {
                date: '2026-01-08',
                type: 'log',
                title: 'Révision Budgétaire',
                department: 'administration',
                description: 'Révision et approbation budget T1',
                status: 'normal',
                time: '10:30'
            },
            {
                date: '2026-01-12',
                type: 'meeting',
                title: 'Réunion Chefs Dép.',
                department: 'administration',
                description: 'Coordination mensuelle chefs de département',
                status: 'normal',
                time: '09:00'
            },
            {
                date: '2026-01-15',
                type: 'event',
                title: 'Concert Hiver',
                department: 'musique',
                description: 'Série de concerts classiques hiver',
                status: 'normal',
                time: '19:00'
            },
            {
                date: '2026-01-20',
                type: 'log',
                title: 'Inscription Étudiants',
                department: 'administration',
                description: 'Clôture inscriptions semestre printemps',
                status: 'pending',
                time: '17:00'
            },
            {
                date: '2026-02-01',
                type: 'event',
                title: 'Spectacle de Danse',
                department: 'dance',
                description: 'Showcase de danse contemporaine',
                status: 'normal',
                time: '18:00'
            },
            {
                date: '2026-02-05',
                type: 'log',
                title: 'Formation Personnel',
                department: 'administration',
                description: 'Programme de formation annuel',
                status: 'normal',
                time: '09:00'
            },
            {
                date: '2026-02-10',
                type: 'meeting',
                title: 'Révision Curriculum',
                department: 'art_plastique',
                description: 'Mise à jour des standards pédagogiques',
                status: 'normal',
                time: '14:00'
            },
            {
                date: '2026-02-14',
                type: 'event',
                title: 'Concert Saint-Valentin',
                department: 'musique',
                description: 'Concert spécial Saint-Valentin',
                status: 'important',
                time: '19:30'
            },
            {
                date: '2026-02-20',
                type: 'log',
                title: 'Inventaire Matériel',
                department: 'art_plastique',
                description: 'Audit des fournitures artistiques',
                status: 'pending',
                time: '11:00'
            },
            {
                date: '2026-03-03',
                type: 'event',
                title: 'Festival Danse Printemps',
                department: 'dance',
                description: 'Festival danse multi-département',
                status: 'important',
                time: '19:00'
            },
            {
                date: '2026-03-08',
                type: 'log',
                title: 'Maintenance Système',
                department: 'administration',
                description: 'Maintenance base de données et serveurs',
                status: 'normal',
                time: '22:00'
            },
            {
                date: '2026-03-12',
                type: 'meeting',
                title: 'Planification Budget',
                department: 'administration',
                description: 'Budget prochain exercice fiscal',
                status: 'normal',
                time: '10:00'
            },
            {
                date: '2026-03-17',
                type: 'event',
                title: 'Masterclass Art',
                department: 'art_plastique',
                description: 'Masterclass artiste international',
                status: 'important',
                time: '15:00'
            },
            {
                date: '2026-03-25',
                type: 'log',
                title: 'Évaluations Personnel',
                department: 'administration',
                description: 'Saison évaluations de performance',
                status: 'pending',
                time: '13:00'
            },
            {
                date: '2026-04-02',
                type: 'event',
                title: 'Récital Printemps',
                department: 'musique',
                description: 'Récital musical de printemps étudiant',
                status: 'normal',
                time: '19:00'
            },
            {
                date: '2026-04-07',
                type: 'log',
                title: 'Commandes Équipement',
                department: 'administration',
                description: 'Soumission demandes équipement',
                status: 'pending',
                time: '12:00'
            },
            {
                date: '2026-04-12',
                type: 'meeting',
                title: 'Inspection Sécurité',
                department: 'administration',
                description: 'Inspection sécurité des installations',
                status: 'normal',
                time: '11:00'
            },
            {
                date: '2026-04-20',
                type: 'event',
                title: 'Workshop Chorégraphie',
                department: 'dance',
                description: 'Atelier chorégraphie avancée',
                status: 'normal',
                time: '10:00'
            },
            {
                date: '2026-04-28',
                type: 'log',
                title: 'Données Inscriptions',
                department: 'administration',
                description: 'Mise à jour statistiques inscriptions',
                status: 'normal',
                time: '14:00'
            },
            {
                date: '2026-05-05',
                type: 'event',
                title: 'Symposium Art',
                department: 'art_plastique',
                description: 'Symposium théorie art contemporain',
                status: 'important',
                time: '10:00'
            },
            {
                date: '2026-05-12',
                type: 'log',
                title: 'Dossiers Subventions',
                department: 'administration',
                description: 'Soumission dossiers subventions fédérales',
                status: 'pending',
                time: '17:00'
            },
            {
                date: '2026-05-18',
                type: 'meeting',
                title: 'CA Printemps',
                department: 'administration',
                description: 'Réunion conseil d\'administration printemps',
                status: 'normal',
                time: '14:00'
            },
            {
                date: '2026-05-25',
                type: 'event',
                title: 'Cérémonie Remise Diplômes',
                department: 'administration',
                description: 'Célébration annuelle remise des diplômes',
                status: 'important',
                time: '10:00'
            },
            {
                date: '2026-06-01',
                type: 'log',
                title: 'Planification Été',
                department: 'administration',
                description: 'Réunion planification session estivale',
                status: 'normal',
                time: '09:00'
            },
            {
                date: '2026-06-05',
                type: 'event',
                title: 'Concerts Été',
                department: 'musique',
                description: 'Série concerts en plein air début été',
                status: 'normal',
                time: '18:00'
            },
            {
                date: '2026-06-10',
                type: 'meeting',
                title: 'Révision Installations',
                department: 'administration',
                description: 'Révision propositions rénovation',
                status: 'normal',
                time: '13:00'
            },
            {
                date: '2026-06-15',
                type: 'event',
                title: 'Camp Art Estival',
                department: 'art_plastique',
                description: 'Programme intensif art jeunes été',
                status: 'normal',
                time: '09:00'
            },
            {
                date: '2026-06-20',
                type: 'log',
                title: 'Bilan Mi-Année',
                department: 'administration',
                description: 'Bilan financier et opérationnel mi-année',
                status: 'pending',
                time: '14:00'
            },
            {
                date: '2026-06-25',
                type: 'event',
                title: 'Intensif Danse Été',
                department: 'dance',
                description: 'Intensif professionnel danse été',
                status: 'important',
                time: '10:00'
            },
            {
                date: '2026-07-04',
                type: 'event',
                title: 'Concert Fête Nationale',
                department: 'musique',
                description: 'Célébration musicale patriotique',
                status: 'important',
                time: '19:00'
            },
            {
                date: '2026-08-15',
                type: 'event',
                title: 'Rentrée Semestre',
                department: 'administration',
                description: 'Accueil et orientation nouveaux étudiants',
                status: 'normal',
                time: '10:00'
            },
            {
                date: '2026-09-01',
                type: 'log',
                title: 'Nouvelle Année Académique',
                department: 'administration',
                description: 'Démarrage officiel année académique',
                status: 'important',
                time: '08:00'
            },
            {
                date: '2026-10-31',
                type: 'event',
                title: 'Spectacle Halloween',
                department: 'dance',
                description: 'Spectacle danse thème Halloween',
                status: 'normal',
                time: '20:00'
            },
            {
                date: '2026-11-15',
                type: 'meeting',
                title: 'Planification Fêtes',
                department: 'administration',
                description: 'Organisation événements de fin d\'année',
                status: 'normal',
                time: '11:00'
            },
            {
                date: '2026-12-20',
                type: 'event',
                title: 'Concert Noël',
                department: 'musique',
                description: 'Concert annuel de Noël',
                status: 'important',
                time: '19:00'
            },
        ];

        // ══ STATE ══
        let calDate = new Date(2026, 3, 1);
        let calSelected = null;
        let calFiltered = [...CAL_EVENTS];
        let calCurrentEvent = null;
        let calReportMode = 'month';

        const MONTHS_FR = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre',
            'Novembre', 'Décembre'
        ];
        const MONTHS_SHORT = ['jan', 'fév', 'mars', 'avr', 'mai', 'juin', 'juil', 'août', 'sep', 'oct', 'nov', 'déc'];
        const DEPT_LABELS = {
            art_plastique: 'Art Plastique',
            musique: 'Musique',
            dance: 'Danse',
            theater: 'Théâtre',
            administration: 'Administration'
        };
        const TYPE_COLORS = {
            event: 'var(--blue)',
            log: 'var(--green)',
            meeting: 'var(--purple)',
            important: 'var(--red)',
            pending: 'var(--amber)'
        };

        // ══ RENDER CALENDAR ══
       window.calRender = function() {
            const y = calDate.getFullYear(),
                m = calDate.getMonth();
            document.getElementById('calMonthDisplay').textContent = MONTHS_FR[m] + ' ' + y;

            const firstDay = new Date(y, m, 1).getDay(); // 0=Sun
            const daysInMonth = new Date(y, m + 1, 0).getDate();
            const startOffset = (firstDay === 0 ? 6 : firstDay - 1); // Mon-start
            const today = new Date();

            const grid = document.getElementById('calGrid');
            grid.innerHTML = '';

            // Prev month fill
            const prevDays = new Date(y, m, 0).getDate();
            for (let i = startOffset - 1; i >= 0; i--) {
                const cell = calMakeCell(y, m - 1, prevDays - i, true);
                grid.appendChild(cell);
            }

            // Current month
            for (let d = 1; d <= daysInMonth; d++) {
                const dateStr = `${y}-${String(m+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
                const dayEvts = calFiltered.filter(e => e.date === dateStr);
                const isToday = (today.getFullYear() === y && today.getMonth() === m && today.getDate() === d);
                const isSelected = (calSelected === dateStr);
                const cell = document.createElement('div');
                cell.className = 'cal-cell' + (isToday ? ' cal-today' : '') + (isSelected ? ' cal-selected' : '') + (dayEvts
                    .length ? ' cal-has-events' : '');
                cell.dataset.date = dateStr;

                // Number row
                const numWrap = document.createElement('div');
                numWrap.className = 'cal-cell-num-wrap';
                const num = document.createElement('div');
                num.className = 'cal-cell-num';
                num.textContent = d;
                numWrap.appendChild(num);
                if (isToday) {
                    const dot = document.createElement('div');
                    dot.className = 'cal-today-dot';
                    numWrap.appendChild(dot);
                }
                cell.appendChild(numWrap);

                // Events
                const evtsWrap = document.createElement('div');
                evtsWrap.className = 'cal-cell-events';
                const maxShow = 2;
                dayEvts.slice(0, maxShow).forEach(evt => {
                    const tag = document.createElement('div');
                    const cls = evt.status === 'important' ? 'important' : evt.type;
                    tag.className = `cal-mini-evt ${cls}`;
                    tag.textContent = evt.time + ' ' + evt.title;
                    tag.onclick = (e) => {
                        e.stopPropagation();
                        calShowEvent(evt);
                    };
                    evtsWrap.appendChild(tag);
                });
                if (dayEvts.length > maxShow) {
                    const more = document.createElement('div');
                    more.className = 'cal-more-badge';
                    more.textContent = '+' + (dayEvts.length - maxShow) + ' autres';
                    more.onclick = (e) => {
                        e.stopPropagation();
                        calOpenDayModal(dateStr, dayEvts);
                    };
                    evtsWrap.appendChild(more);
                }
                cell.appendChild(evtsWrap);

                cell.onclick = () => calSelectDay(dateStr, dayEvts);
                grid.appendChild(cell);
            }

            // Next month fill
            const totalCells = startOffset + daysInMonth;
            const remainder = (7 - (totalCells % 7)) % 7;
            for (let d = 1; d <= remainder; d++) {
                grid.appendChild(calMakeCell(y, m + 1, d, true));
            }

            calUpdateStats();
            calUpdateUpcoming();
            calUpdateDeptStats();
        }

        window.calMakeCell = function(y, m, d, isOther) {
            const cell = document.createElement('div');
            cell.className = 'cal-cell cal-other';
            const num = document.createElement('div');
            num.className = 'cal-cell-num';
            num.textContent = d;
            cell.appendChild(num);
            return cell;
        }

        // ══ SELECT DAY ══
        window.calSelectDay = function(dateStr, dayEvts) {
            calSelected = dateStr;
            calRender();

            const d = new Date(dateStr);
            const label = d.toLocaleDateString('fr-FR', {
                weekday: 'long',
                day: 'numeric',
                month: 'long'
            });
            document.getElementById('calDayPanelTitle').textContent = label;
            document.getElementById('calDayPanelSub').textContent = dayEvts.length + ' événement' + (dayEvts.length !== 1 ?
                's' : '');
            document.getElementById('calAddEventDay').style.display = '';

            // IA analysis
            const iaEl = document.getElementById('calDayIa');
            const iaText = document.getElementById('calDayIaText');
            if (dayEvts.length > 0) {
                iaEl.style.display = '';
                const importants = dayEvts.filter(e => e.status === 'important').length;
                const depts = [...new Set(dayEvts.map(e => DEPT_LABELS[e.department] || e.department))].join(', ');
                iaText.textContent =
                    `${dayEvts.length} événement${dayEvts.length>1?'s':''} ce jour — ${depts}. ${importants > 0 ? importants + ' prioritaire(s) à traiter en priorité.' : 'Aucun conflit détecté.'} Charge : ${dayEvts.length >= 3 ? 'Élevée ⚠' : 'Normale ✓'}`;
            } else {
                iaEl.style.display = '';
                iaText.textContent =
                    '✅ Aucun événement planifié ce jour. Bon créneau pour des tâches administratives ou réunions spontanées.';
            }

            // Render side events
            const sideEl = document.getElementById('calDayEvents');
            if (dayEvts.length === 0) {
                sideEl.innerHTML =
                    '<div class="cal-empty-day"><div style="font-size:28px;opacity:.25;">🗓️</div><div style="font-size:12px;color:var(--text3);margin-top:8px;">Aucun événement ce jour</div></div>';
                return;
            }
            sideEl.innerHTML = dayEvts.map(evt => {
                const dot = evt.status === 'important' ? 'var(--red)' : TYPE_COLORS[evt.type] || 'var(--text3)';
                return `<div class="cal-side-evt" onclick="calShowEvent(${JSON.stringify(evt).replace(/"/g,'&quot;')})">
            <div class="cal-side-evt-dot" style="background:${dot};"></div>
            <div class="cal-side-evt-info">
                <div class="cal-side-evt-title">${evt.title}</div>
                <div class="cal-side-evt-meta">${evt.time} · ${DEPT_LABELS[evt.department]||evt.department}</div>
            </div>
            <span class="badge ${evt.type === 'event' ? 'blue' : evt.type === 'meeting' ? 'gold' : 'green'}" style="font-size:9px;">${calTypeLbl(evt.type)}</span>
        </div>`;
            }).join('');
        }

        window.calTypeLbl = function(t) {
            return t === 'event' ? 'Événement' : t === 'meeting' ? 'Réunion' : 'Journal';
        }

        // ══ DAY FULL MODAL ══
        window.calOpenDayModal = function(dateStr, dayEvts) {
            const d = new Date(dateStr);
            document.getElementById('calDayModalTitle').textContent = d.toLocaleDateString('fr-FR', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            const body = document.getElementById('calDayModalBody');
            body.innerHTML = dayEvts.map(evt => {
                const dot = evt.status === 'important' ? 'var(--red)' : TYPE_COLORS[evt.type] || 'var(--text3)';
                return `<div class="cal-side-evt" style="border:1px solid var(--border); border-radius:8px; margin-bottom:8px;" onclick="closeModal('modal-cal-day'); calShowEvent(${JSON.stringify(evt).replace(/"/g,"'")})">
            <div class="cal-side-evt-dot" style="background:${dot};"></div>
            <div class="cal-side-evt-info">
                <div class="cal-side-evt-title">${evt.title}</div>
                <div class="cal-side-evt-meta">${evt.time} · ${DEPT_LABELS[evt.department]||evt.department} · ${evt.description}</div>
            </div>
        </div>`;
            }).join('');
            openModal('modal-cal-day');
        }

        // ══ EVENT DETAIL ══
        window.calShowEvent = function(evt) {
            calCurrentEvent = evt;
            document.getElementById('calEvtTitle').textContent = evt.title;
            document.getElementById('calEvtDate').textContent = new Date(evt.date).toLocaleDateString('fr-FR', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            document.getElementById('calEvtTime').textContent = evt.time;
            document.getElementById('calEvtDept').textContent = DEPT_LABELS[evt.department] || evt.department;
            document.getElementById('calEvtStatus').textContent = evt.status === 'important' ? '🔴 Important' : evt
                .status === 'pending' ? '🟡 En attente' : '🟢 Normal';
            document.getElementById('calEvtDesc').textContent = evt.description;
            const bMap = {
                event: 'blue',
                meeting: 'gold',
                log: 'green'
            };
            document.getElementById('calEvtBadges').innerHTML =
                `<span class="badge ${bMap[evt.type]||'gray'}">${calTypeLbl(evt.type)}</span>`;
            document.getElementById('calEvtIa').textContent =
                `🤖 Recommandation IA : Cet événement est ${evt.status==='important'?'prioritaire — notifiez tous les participants 48h à l\'avance.':'standard — un rappel 24h avant suffit. Aucun conflit détecté sur ce créneau.'}`;
            openModal('modal-cal-event');
        }

        // ══ STATS ══
        window.calUpdateStats = function() {
            const y = calDate.getFullYear(),
                m = calDate.getMonth();
            const monthEvts = calFiltered.filter(e => {
                const d = new Date(e.date);
                return d.getFullYear() === y && d.getMonth() === m;
            });
            document.getElementById('kpiEvents').textContent = calFiltered.filter(e => e.type === 'event').length;
            document.getElementById('kpiLogs').textContent = calFiltered.filter(e => e.type === 'log').length;
            document.getElementById('kpiMeetings').textContent = calFiltered.filter(e => e.type === 'meeting').length;
            document.getElementById('kpiImportant').textContent = calFiltered.filter(e => e.status === 'important').length;
            document.getElementById('kpiEventsDelta').textContent = '↑ ' + monthEvts.length + ' ce mois';
        }

        // ══ UPCOMING ══
        window.calUpdateUpcoming = function() {
            const today = new Date();
            const in7 = new Date(today);
            in7.setDate(today.getDate() + 7);
            const upcoming = calFiltered.filter(e => {
                const d = new Date(e.date);
                return d >= today && d <= in7;
            }).sort((a, b) => new Date(a.date) - new Date(b.date));

            document.getElementById('calUpcomingCount').textContent = upcoming.length;
            const el = document.getElementById('calUpcoming');
            if (!upcoming.length) {
                el.innerHTML =
                    '<div class="cal-empty-day" style="padding:16px;"><div style="font-size:11px;color:var(--text3);">Aucun événement dans 7 jours</div></div>';
                return;
            }
            el.innerHTML = upcoming.slice(0, 5).map(evt => {
                const d = new Date(evt.date);
                return `<div class="cal-upcoming-item" onclick="calShowEvent(${JSON.stringify(evt).replace(/"/g,"'")})">
            <div class="cal-upcoming-date">
                <div class="cal-upcoming-date-d">${d.getDate()}</div>
                <div class="cal-upcoming-date-m">${MONTHS_SHORT[d.getMonth()]}</div>
            </div>
            <div class="cal-upcoming-info">
                <div class="cal-upcoming-title">${evt.title}</div>
                <div class="cal-upcoming-dept">${DEPT_LABELS[evt.department]||evt.department} · ${evt.time}</div>
            </div>
        </div>`;
            }).join('');
        }

        // ══ DEPT STATS ══
        window.calUpdateDeptStats = function() {
            const counts = {};
            calFiltered.forEach(e => {
                counts[e.department] = (counts[e.department] || 0) + 1;
            });
            const max = Math.max(...Object.values(counts), 1);
            const colors = {
                art_plastique: 'var(--gold)',
                musique: 'var(--blue)',
                dance: 'var(--purple)',
                theater: 'var(--teal)',
                administration: 'var(--green)'
            };
            document.getElementById('calDeptStats').innerHTML = Object.entries(counts)
                .sort((a, b) => b[1] - a[1])
                .map(([dept, cnt]) => `
            <div style="display:flex; flex-direction:column; gap:4px;">
                <div style="display:flex; justify-content:space-between; font-size:11.5px; color:var(--text2);">
                    <span>${DEPT_LABELS[dept]||dept}</span>
                    <span style="font-weight:700; font-family:var(--font-mono); color:var(--text);">${cnt}</span>
                </div>
                <div style="height:4px; background:var(--bg4); border-radius:2px; overflow:hidden;">
                    <div style="height:100%; border-radius:2px; background:${colors[dept]||'var(--teal)'}; width:${Math.round(cnt/max*100)}%; transition:width .5s;"></div>
                </div>
            </div>
        `).join('');
        }

        // ══ FILTERS ══
        window.calApplyFilters = function() {
            const dept = document.getElementById('calDeptFilter').value;
            const type = document.getElementById('calTypeFilter').value;
            const search = document.getElementById('calSearch').value.toLowerCase();
            calFiltered = CAL_EVENTS.filter(e => {
                const mDept = !dept || e.department === dept;
                const mType = !type || e.type === type;
                const mSearch = !search || e.title.toLowerCase().includes(search) || e.description.toLowerCase()
                    .includes(search);
                return mDept && mType && mSearch;
            });
            calRender();
            if (calSelected) calSelectDay(calSelected, calFiltered.filter(e => e.date === calSelected));
        }

        // ══ REPORT ══
        window.calOpenReport = function() {
            const m = calDate.getMonth(),
                y = calDate.getFullYear();
            document.getElementById('rMonthSingle').value = m;
            document.getElementById('rYearSingle').value = y;
            document.getElementById('rRangeYear').value = y;
            document.getElementById('rYear').value = y;
            openModal('modal-cal-report');
        }

        window.calSetReportMode = function(el, mode) {
            calReportMode = mode;
            document.querySelectorAll('.cal-rmode').forEach(b => b.classList.remove('active'));
            el.classList.add('active');
            ['month', 'range', 'year', 'day'].forEach(m => {
                const p = document.getElementById('rmode-' + m);
                if (p) p.style.display = m === mode ? '' : 'none';
            });
        }

        window.calGetReportEvents = function() {
            let dept = document.getElementById('rDept').value;
            let type = document.getElementById('rType').value;
            let events = CAL_EVENTS.filter(e => (!dept || e.department === dept) && (!type || e.type === type));
            if (calReportMode === 'month') {
                const m = parseInt(document.getElementById('rMonthSingle').value);
                const y = parseInt(document.getElementById('rYearSingle').value);
                events = events.filter(e => {
                    const d = new Date(e.date);
                    return d.getMonth() === m && d.getFullYear() === y;
                });
            } else if (calReportMode === 'range') {
                const sm = parseInt(document.getElementById('rStartMonth').value);
                const em = parseInt(document.getElementById('rEndMonth').value);
                const y = parseInt(document.getElementById('rRangeYear').value);
                events = events.filter(e => {
                    const d = new Date(e.date);
                    return d.getFullYear() === y && d.getMonth() >= sm && d.getMonth() <= em;
                });
            } else if (calReportMode === 'year') {
                const y = parseInt(document.getElementById('rYear').value);
                events = events.filter(e => new Date(e.date).getFullYear() === y);
            } else if (calReportMode === 'day') {
                const day = document.getElementById('rDay').value;
                events = events.filter(e => e.date === day);
            }
            return events.sort((a, b) => new Date(a.date) - new Date(b.date));
        }

        window.calPreviewReport = function() {
            const evts = calGetReportEvents();
            const body = document.getElementById('calReportPreviewBody');
            if (!evts.length) {
                body.innerHTML =
                '<div style="color:var(--text3);font-size:12px;">Aucun événement pour cette période.</div>';
                return;
            }
            body.innerHTML =
                `<div style="font-size:11px;color:var(--text3);margin-bottom:8px;font-family:var(--font-mono);">${evts.length} événement${evts.length>1?'s':''} trouvé${evts.length>1?'s':''}</div>` +
                evts.slice(0, 8).map(e => {
                    const d = new Date(e.date);
                    return `<div class="cal-report-preview-row">
                <div class="cal-rpr-date">${d.getDate()} ${MONTHS_SHORT[d.getMonth()]}</div>
                <div style="flex:1;min-width:0;">
                    <div class="cal-rpr-title">${e.title}</div>
                    <div class="cal-rpr-dept">${DEPT_LABELS[e.department]||e.department} · ${e.time}</div>
                </div>
            </div>`;
                }).join('') + (evts.length > 8 ?
                    `<div style="font-size:11px;color:var(--text3);padding-top:8px;">… et ${evts.length-8} autres</div>` :
                    '');
        }

        window.calDownloadReport = function() {
            const evts = calGetReportEvents();
            const now = new Date().toLocaleDateString('fr-FR');
            let txt =
                `═══════════════════════════════════════\nRAPPORT CALENDRIER — Généré le ${now}\n═══════════════════════════════════════\n\nTotal événements : ${evts.length}\n\n`;
            const byDept = {};
            evts.forEach(e => {
                (byDept[e.department] = byDept[e.department] || []).push(e);
            });
            Object.entries(byDept).forEach(([dept, des]) => {
                txt += `\n── ${DEPT_LABELS[dept]||dept} (${des.length}) ──\n`;
                des.forEach(e => {
                    const d = new Date(e.date);
                    txt +=
                        `  • ${d.toLocaleDateString('fr-FR')} ${e.time} | [${calTypeLbl(e.type).toUpperCase()}] ${e.title}\n    ${e.description}\n`;
                });
            });
            txt += `\n═══════════════════════════════════════\n`;
            const blob = new Blob([txt], {
                type: 'text/plain;charset=utf-8'
            });
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = `Rapport_Calendrier_${calReportMode}_${new Date().toISOString().slice(0,10)}.txt`;
            a.click();
            showToast('📥 Rapport téléchargé!', 'success');
            closeModal('modal-cal-report');
        }

        // ══ ADD EVENT ══
        window.calOpenAdd = function() {
            const today = calSelected || new Date().toISOString().slice(0, 10);
            document.getElementById('calAddDate').value = today;
            document.getElementById('calConflictCheck').style.display = 'none';
            openModal('modal-cal-add');
        }

        window.calCheckConflicts = function() {
            const date = document.getElementById('calAddDate').value;
            const conflicts = CAL_EVENTS.filter(e => e.date === date);
            const el = document.getElementById('calConflictCheck');
            const txt = document.getElementById('calConflictText');
            el.style.display = '';
            if (conflicts.length > 0) {
                el.style.background = 'var(--amber-dim)';
                el.style.border = '1px solid rgba(251,191,36,.3)';
                txt.textContent =
                    `⚠️ ${conflicts.length} événement${conflicts.length>1?'s':''} déjà planifié${conflicts.length>1?'s':''} ce jour. Vérifiez les disponibilités.`;
            } else {
                el.style.background = 'var(--green-dim)';
                el.style.border = '1px solid rgba(74,222,128,.2)';
                txt.textContent = '✅ Aucun conflit détecté — créneau libre.';
            }
        }

        window.calSaveEvent = function() {
            const title = document.getElementById('calAddName').value.trim();
            if (!title) {
                showToast('Le titre est requis', 'error');
                return;
            }
            const newEvt = {
                date: document.getElementById('calAddDate').value,
                type: document.getElementById('calAddType').value,
                title,
                department: document.getElementById('calAddDept').value,
                description: document.getElementById('calAddDesc').value || 'Aucune description',
                status: document.getElementById('calAddStatus').value,
                time: document.getElementById('calAddTime').value,
            };
            CAL_EVENTS.push(newEvt);
            calFiltered = [...CAL_EVENTS];
            calRender();
            closeModal('modal-cal-add');
            showToast('✅ Événement "' + title + '" créé!', 'success');
        }

        window.calOpenEdit = function() {
            if (!calCurrentEvent) return;
            document.getElementById('calAddTitle').textContent = '✏️ Modifier Événement';
            document.getElementById('calAddName').value = calCurrentEvent.title;
            document.getElementById('calAddType').value = calCurrentEvent.type;
            document.getElementById('calAddStatus').value = calCurrentEvent.status;
            document.getElementById('calAddDate').value = calCurrentEvent.date;
            document.getElementById('calAddTime').value = calCurrentEvent.time;
            document.getElementById('calAddDept').value = calCurrentEvent.department;
            document.getElementById('calAddDesc').value = calCurrentEvent.description;
            openModal('modal-cal-add');
        }

        // ══ IA PANEL ══
        window.calOpenIaPanel = function() {
            openModal('modal-cal-ia');
        }

        window.calIaTab = function(el, panelId) {
            el.closest('.cal-ia-tabs').querySelectorAll('.cal-ia-tab').forEach(t => t.classList.remove('active'));
            el.classList.add('active');
            document.querySelectorAll('.cal-ia-tab-panel').forEach(p => p.style.display = 'none');
            const p = document.getElementById(panelId);
            if (p) {
                p.style.display = 'flex';
                p.classList.add('active');
            }
        }

        window.calIaAddSugg = function(title, date) {
            CAL_EVENTS.push({
                date,
                type: 'meeting',
                title,
                department: 'administration',
                description: 'Suggestion IA auto-planifiée',
                status: 'normal',
                time: '10:00'
            });
            calFiltered = [...CAL_EVENTS];
            calRender();
            showToast('✅ "' + title + '" ajouté au calendrier!', 'success');
            closeModal('modal-cal-ia');
        }

        // ══ IA INSIGHT ROTATOR ══
        const iaInsights = [
            '🤖 <strong>Analyse IA :</strong> Juin est votre mois le plus chargé (+3 événements importants). Aucun conflit détecté cette semaine.',
            '🤖 <strong>Suggestion IA :</strong> Département Théâtre sous-utilisé ce semestre. Planifiez 2 événements supplémentaires.',
            '🤖 <strong>Rappel IA :</strong> 3 événements importants dans les 14 prochains jours — participants non encore notifiés.',
            '🤖 <strong>Tendance IA :</strong> Art Plastique +40% d\'activité ce trimestre vs Q1. Revoir l\'allocation budgétaire.',
        ];
        let iaIdx = 0;
        setInterval(() => {
            iaIdx = (iaIdx + 1) % iaInsights.length;
            document.getElementById('iaInsightText').innerHTML = iaInsights[iaIdx];
        }, 6000);

        // ══ NAV ══
        document.getElementById('calPrevBtn').onclick = () => {
            calDate.setMonth(calDate.getMonth() - 1);
            calRender();
        };
        document.getElementById('calNextBtn').onclick = () => {
            calDate.setMonth(calDate.getMonth() + 1);
            calRender();
        };
        document.getElementById('calTodayBtn').onclick = () => {
            calDate = new Date();
            calDate.setDate(1);
            calRender();
        };
        document.getElementById('calDeptFilter').onchange = calApplyFilters;
        document.getElementById('calTypeFilter').onchange = calApplyFilters;
        document.getElementById('calSearch').oninput = calApplyFilters;

        // ══ INIT ══
        document.addEventListener('DOMContentLoaded', () => {
            calRender();
        });
    </script>
@endsection
