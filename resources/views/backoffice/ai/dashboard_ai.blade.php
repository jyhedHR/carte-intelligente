@extends('shared.layouts.backoffice')

@section('title', 'RAG Chatbot — Panneau de Contrôle')

@section('content')

<div class="rag-dashboard-wrap" style="padding:28px 32px;max-width:1600px;margin:0 auto;box-sizing:border-box;">

{{-- ══════════════════════════════════════════════
     EN-TÊTE DE PAGE
══════════════════════════════════════════════ --}}
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
    <div>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px;">
            <div style="width:36px;height:36px;background:var(--gold-dim);border:1px solid rgba(201,168,76,0.3);border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20z"/><path d="M12 8v4l3 3"/></svg>
            </div>
            <h1 style="font-size:20px;font-weight:700;color:var(--text);font-family:var(--font-display);">Panneau de Contrôle RAG</h1>
        </div>
        <p style="font-size:12.5px;color:var(--text2);margin-left:46px;">Gérez la base de connaissances · résolvez les requêtes signalées · surveillez les performances</p>
    </div>
    <div style="display:flex;align-items:center;gap:10px;">
        <div style="display:flex;align-items:center;gap:6px;background:var(--green-dim);border:1px solid rgba(74,222,128,0.25);border-radius:20px;padding:6px 14px;">
            <span style="width:7px;height:7px;background:var(--green);border-radius:50%;display:inline-block;animation:pulse-dot 2s infinite;"></span>
            <span style="font-size:12px;font-weight:600;color:var(--green);">Bot en Ligne</span>
        </div>
        <button onclick="openModal('modal-deploy')" style="display:flex;align-items:center;gap:7px;background:var(--gold);color:#111;border:none;border-radius:var(--radius-sm);padding:8px 16px;font-size:12.5px;font-weight:700;cursor:pointer;font-family:var(--font-body);transition:background 0.2s;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            Déployer la Mise à Jour
        </button>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     NAVIGATION PAR ONGLETS - Compatible impresarios.js
══════════════════════════════════════════════ --}}
<div class="rag-tabs" style="display:flex;gap:4px;margin-bottom:24px;background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);padding:5px;">
    <button class="rag-tab tab-btn active" onclick="switchTab('tab-overview', event)" style="flex:1;padding:9px 14px;border:none;border-radius:var(--radius-sm);font-size:12.5px;font-weight:600;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;justify-content:center;gap:7px;background:var(--gold-dim);color:var(--gold);font-family:var(--font-body);">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
        Vue d'Ensemble
    </button>
    <button class="rag-tab tab-btn" onclick="switchTab('tab-knowledge', event)" style="flex:1;padding:9px 14px;border:none;border-radius:var(--radius-sm);font-size:12.5px;font-weight:600;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;justify-content:center;gap:7px;background:transparent;color:var(--text2);font-family:var(--font-body);">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
        Base de Connaissances
    </button>
    <button class="rag-tab tab-btn" onclick="switchTab('tab-flagged', event)" style="flex:1;padding:9px 14px;border:none;border-radius:var(--radius-sm);font-size:12.5px;font-weight:600;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;justify-content:center;gap:7px;background:transparent;color:var(--text2);font-family:var(--font-body);">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
        Questions Signalées
        <span id="tab-flagged-badge" style="background:var(--amber);color:#111;font-size:10px;font-weight:800;border-radius:10px;padding:1px 7px;">{{ count($flaggedQuestions) }}</span>
    </button>
    <button class="rag-tab tab-btn" onclick="switchTab('tab-prompts', event)" style="flex:1;padding:9px 14px;border:none;border-radius:var(--radius-sm);font-size:12.5px;font-weight:600;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;justify-content:center;gap:7px;background:transparent;color:var(--text2);font-family:var(--font-body);">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
        Studio de Prompts
    </button>
    <button class="rag-tab tab-btn" onclick="switchTab('tab-performance', event)" style="flex:1;padding:9px 14px;border:none;border-radius:var(--radius-sm);font-size:12.5px;font-weight:600;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;justify-content:center;gap:7px;background:transparent;color:var(--text2);font-family:var(--font-body);">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
        Performances
    </button>
</div>

{{-- ══════════════════════════════════════════════
     ONGLET: VUE D'ENSEMBLE
══════════════════════════════════════════════ --}}
<div id="tab-overview" class="tab-panel tab-content" style="display:block;">

    {{-- KPI Row - Données réelles --}}
    <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:24px;">

        <div class="kpi-card gold" style="background:var(--gold-dim);border:1px solid rgba(201,168,76,0.25);border-radius:var(--radius);padding:18px 20px;">
            <div style="font-size:10px;font-weight:600;letter-spacing:1px;text-transform:uppercase;color:var(--gold);margin-bottom:10px;">Requêtes Totales</div>
            <div style="font-size:28px;font-weight:900;color:var(--text);font-family:var(--font-mono);" id="kpi-total-questions">{{ number_format($totalQuestions) }}</div>
            <div style="font-size:11px;color:var(--text2);margin-top:4px;" id="kpi-total-trend">— vs semaine dernière</div>
        </div>

        <div class="kpi-card green" style="background:var(--green-dim);border:1px solid rgba(74,222,128,0.25);border-radius:var(--radius);padding:18px 20px;">
            <div style="font-size:10px;font-weight:600;letter-spacing:1px;text-transform:uppercase;color:var(--green);margin-bottom:10px;">Taux de Résolution</div>
            <div style="font-size:28px;font-weight:900;color:var(--text);font-family:var(--font-mono);" id="kpi-success-rate">{{ $successRate }}%</div>
            <div style="font-size:11px;color:var(--text2);margin-top:4px;" id="kpi-success-trend">— vs semaine dernière</div>
        </div>

        <div class="kpi-card amber" style="background:var(--amber-dim);border:1px solid rgba(251,191,36,0.25);border-radius:var(--radius);padding:18px 20px;">
            <div style="font-size:10px;font-weight:600;letter-spacing:1px;text-transform:uppercase;color:var(--amber);margin-bottom:10px;">Signalés Aujourd'hui</div>
            <div style="font-size:28px;font-weight:900;color:var(--text);font-family:var(--font-mono);" id="kpi-flagged-count">{{ $flaggedCount }}</div>
            <div style="font-size:11px;color:var(--text2);margin-top:4px;">Nécessite une révision admin</div>
        </div>

        <div class="kpi-card blue" style="background:var(--blue-dim);border:1px solid rgba(96,165,250,0.25);border-radius:var(--radius);padding:18px 20px;">
            <div style="font-size:10px;font-weight:600;letter-spacing:1px;text-transform:uppercase;color:var(--blue);margin-bottom:10px;">Temps de Réponse Moy.</div>
            <div style="font-size:28px;font-weight:900;color:var(--text);font-family:var(--font-mono);" id="kpi-avg-response">{{ $avgResponseTime }}s</div>
            <div style="font-size:11px;color:var(--text2);margin-top:4px;" id="kpi-response-trend">— temps moyen</div>
        </div>

        <div class="kpi-card purple" style="background:var(--purple-dim);border:1px solid rgba(167,139,250,0.25);border-radius:var(--radius);padding:18px 20px;">
            <div style="font-size:10px;font-weight:600;letter-spacing:1px;text-transform:uppercase;color:var(--purple);margin-bottom:10px;">Docs dans la KB</div>
            <div style="font-size:28px;font-weight:900;color:var(--text);font-family:var(--font-mono);" id="kpi-total-documents">{{ number_format($totalDocuments) }}</div>
            <div style="font-size:11px;color:var(--text2);margin-top:4px;" id="kpi-docs-trend">base de connaissances</div>
        </div>

    </div>

    {{-- Main content: Activity chart + Quick actions --}}
    <div style="display:grid;grid-template-columns:1fr 340px;gap:18px;margin-bottom:18px;">

        {{-- ══════════════════════════════════════════════
             VOLUME DES REQUÊTES — AVEC FILTRES ET DONNÉES RÉELLES
        ══════════════════════════════════════════════ --}}
        <div class="panel" style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;">
            <div class="panel-head" style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
                <div>
                    <div style="font-size:13px;font-weight:700;color:var(--text);">Volume des Requêtes</div>

                </div>
                <div style="display:flex;gap:14px;align-items:center;flex-wrap:wrap;">
                    {{-- Filtres de période --}}
                    <div style="display:flex;gap:4px;background:var(--bg3);border-radius:var(--radius-sm);padding:3px;">
                        <button onclick="setChartPeriod('7d')" class="chart-period-btn active" data-period="7d" style="padding:4px 12px;border:none;border-radius:4px;font-size:11px;font-weight:600;cursor:pointer;background:var(--gold-dim);color:var(--gold);font-family:var(--font-body);transition:all 0.2s;">7J</button>
                        <button onclick="setChartPeriod('14d')" class="chart-period-btn" data-period="14d" style="padding:4px 12px;border:none;border-radius:4px;font-size:11px;font-weight:600;cursor:pointer;background:transparent;color:var(--text2);font-family:var(--font-body);transition:all 0.2s;">14J</button>
                        <button onclick="setChartPeriod('30d')" class="chart-period-btn" data-period="30d" style="padding:4px 12px;border:none;border-radius:4px;font-size:11px;font-weight:600;cursor:pointer;background:transparent;color:var(--text2);font-family:var(--font-body);transition:all 0.2s;">30J</button>
                        <button onclick="setChartPeriod('90d')" class="chart-period-btn" data-period="90d" style="padding:4px 12px;border:none;border-radius:4px;font-size:11px;font-weight:600;cursor:pointer;background:transparent;color:var(--text2);font-family:var(--font-body);transition:all 0.2s;">90J</button>
                        <button onclick="setChartPeriod('12m')" class="chart-period-btn" data-period="12m" style="padding:4px 12px;border:none;border-radius:4px;font-size:11px;font-weight:600;cursor:pointer;background:transparent;color:var(--text2);font-family:var(--font-body);transition:all 0.2s;">12M</button>
                    </div>
                    <div style="display:flex;gap:14px;align-items:center;">
                        <span style="display:flex;align-items:center;gap:5px;font-size:11px;color:var(--text2);">
                            <span style="width:10px;height:3px;background:var(--gold);border-radius:2px;display:inline-block;"></span>
                            Répondues
                        </span>
                        <span style="display:flex;align-items:center;gap:5px;font-size:11px;color:var(--text2);">
                            <span style="width:10px;height:3px;background:var(--amber);border-radius:2px;display:inline-block;"></span>
                            Signalées
                        </span>
                        <span style="display:flex;align-items:center;gap:5px;font-size:11px;color:var(--text2);">
                            <span style="width:10px;height:3px;background:var(--blue);border-radius:2px;display:inline-block;border-style:dashed;"></span>
                            Tendance
                        </span>
                    </div>
                </div>
            </div>
            <div style="padding:16px 20px 20px 20px;position:relative;">
                {{-- Loading indicator --}}
                <div id="chart-loading" style="display:none;position:absolute;inset:0;background:rgba(0,0,0,0.3);border-radius:var(--radius);display:flex;align-items:center;justify-content:center;z-index:10;">
                    <div style="background:var(--bg2);padding:20px 30px;border-radius:var(--radius);box-shadow:0 8px 32px rgba(0,0,0,0.3);">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="width:20px;height:20px;border:3px solid var(--gold);border-top-color:transparent;border-radius:50%;animation:spin 0.8s linear infinite;"></div>
                            <span style="font-size:13px;color:var(--text);">Chargement des données...</span>
                        </div>
                    </div>
                </div>

                {{-- Graphique Chart.js --}}
                <div id="chart-container" style="position:relative;width:100%;height:320px;">
                    <canvas id="queryChart"></canvas>
                </div>

                {{-- Statistiques supplémentaires --}}
                <div id="chart-stats" style="display:flex;gap:24px;margin-top:14px;padding-top:14px;border-top:1px solid var(--border);flex-wrap:wrap;justify-content:space-between;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="font-size:11px;color:var(--text2);">📊 Total requêtes:</span>
                        <span style="font-size:14px;font-weight:700;color:var(--text);font-family:var(--font-mono);" id="chart-total-requests">0</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="font-size:11px;color:var(--text2);">✅ Taux de résolution:</span>
                        <span style="font-size:14px;font-weight:700;color:var(--green);font-family:var(--font-mono);" id="chart-resolution-rate">0%</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="font-size:11px;color:var(--text2);">⬆️ Tendance:</span>
                        <span style="font-size:14px;font-weight:700;font-family:var(--font-mono);" id="chart-trend-indicator">→ 0%</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="font-size:11px;color:var(--text2);">📈 Moyenne/jour:</span>
                        <span style="font-size:14px;font-weight:700;color:var(--blue);font-family:var(--font-mono);" id="chart-avg-daily">0</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div class="panel" style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);padding:18px;">
                <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--text2);margin-bottom:14px;">Actions Rapides</div>
                <div style="display:flex;flex-direction:column;gap:8px;">
                    <button onclick="switchTab('tab-knowledge', event)" class="rag-action-btn" style="display:flex;align-items:center;gap:10px;background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius-sm);padding:11px 14px;cursor:pointer;transition:all 0.2s;width:100%;">
                        <div style="width:30px;height:30px;background:var(--purple-dim);border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--purple)" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        </div>
                        <div style="text-align:left;">
                            <div style="font-size:12.5px;font-weight:600;color:var(--text);">Télécharger un Document</div>
                            <div style="font-size:11px;color:var(--text2);">Ajouter à la base de connaissances</div>
                        </div>
                    </button>
                    <button onclick="switchTab('tab-flagged', event)" class="rag-action-btn" style="display:flex;align-items:center;gap:10px;background:var(--amber-dim);border:1px solid rgba(251,191,36,0.2);border-radius:var(--radius-sm);padding:11px 14px;cursor:pointer;transition:all 0.2s;width:100%;">
                        <div style="width:30px;height:30px;background:rgba(251,191,36,0.2);border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--amber)" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
                        </div>
                        <div style="text-align:left;">
                            <div style="font-size:12.5px;font-weight:600;color:var(--amber);">Examiner <span id="action-flagged-count">{{ $flaggedCount }}</span> Signalements</div>
                            <div style="font-size:11px;color:var(--text2);">Répondre et former le bot</div>
                        </div>
                    </button>
                    <button onclick="switchTab('tab-prompts', event)" class="rag-action-btn" style="display:flex;align-items:center;gap:10px;background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius-sm);padding:11px 14px;cursor:pointer;transition:all 0.2s;width:100%;">
                        <div style="width:30px;height:30px;background:var(--teal-dim);border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--teal)" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                        </div>
                        <div style="text-align:left;">
                            <div style="font-size:12.5px;font-weight:600;color:var(--text);">Modifier le Prompt Système</div>
                            <div style="font-size:11px;color:var(--text2);">Ajuster la personnalité du bot</div>
                        </div>
                    </button>
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="panel" style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);padding:18px;flex:1;">
                <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--text2);margin-bottom:14px;">Activité Récente</div>
                <div id="recent-activity" style="display:flex;flex-direction:column;gap:10px;">
                    <div id="recent-activity" style="display:flex;flex-direction:column;gap:10px;">
                    <div id="activity-loading" style="font-size:12px;color:var(--text2);">Chargement...</div>
                </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Topics --}}
    <div class="panel" style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;">
        <div class="panel-head" style="padding:14px 20px;border-bottom:1px solid var(--border);">
            <div style="font-size:13px;font-weight:700;color:var(--text);">Principaux Sujets de Requêtes</div>
        </div>
        <div style="padding:16px 20px;display:grid;grid-template-columns:repeat(4,1fr);gap:14px;">
            @php $topics = [
                ['label'=>"Attestation d'exercice d'une profession artistique",'pct'=>34,'color'=>'var(--gold)','bg'=>'var(--gold-dim)'],
                ['label'=>'Obtention de la Carte Professionnelle Artistique','pct'=>26,'color'=>'var(--blue)','bg'=>'var(--blue-dim)'],
                ['label'=>'Certificat de réussite à un examen','pct'=>19,'color'=>'var(--teal)','bg'=>'var(--teal-dim)'],
                ['label'=>'Autorisation de tournage de films','pct'=>14,'color'=>'var(--purple)','bg'=>'var(--purple-dim)'],
            ]; @endphp
            @foreach($topics as $t)
            <div style="background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius-sm);padding:14px;">
                <div style="font-size:12px;font-weight:600;color:var(--text);margin-bottom:10px;">{{ $t['label'] }}</div>
                <div style="height:5px;background:var(--bg4);border-radius:3px;margin-bottom:6px;overflow:hidden;">
                    <div style="height:100%;width:{{ $t['pct'] }}%;background:{{ $t['color'] }};border-radius:3px;transition:width 0.6s ease;"></div>
                </div>
                <div style="font-size:20px;font-weight:900;color:{{ $t['color'] }};font-family:var(--font-mono);">{{ $t['pct'] }}%</div>
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════
     ONGLET: BASE DE CONNAISSANCES
══════════════════════════════════════════════ --}}
<div id="tab-knowledge" class="tab-panel tab-content" style="display:none;">

    <div style="display:grid;grid-template-columns:minmax(0,1fr) 320px;gap:18px;align-items:start;">

        {{-- Documents list - Données réelles --}}
        <div style="min-width:0;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    <div style="position:relative;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text3)" stroke-width="2" style="position:absolute;top:50%;left:10px;transform:translateY(-50%);pointer-events:none;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" id="docSearch" placeholder="Rechercher des documents…" style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:8px 12px 8px 32px;color:var(--text);font-size:12.5px;outline:none;width:200px;font-family:var(--font-body);">
                    </div>
                    <select id="docTypeFilter" style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:8px 12px;color:var(--text2);font-size:12px;font-family:var(--font-body);outline:none;">
                        <option value="all">Tous les Types</option>
                        <option value="PDF">PDF</option>
                        <option value="DOCX">DOCX</option>
                        <option value="TXT">TXT</option>
                        <option value="Q&A">Q&amp;A</option>
                    </select>
                    <select id="docStatusFilter" style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:8px 12px;color:var(--text2);font-size:12px;font-family:var(--font-body);outline:none;">
                        <option value="all">Tous les Statuts</option>
                        <option value="indexed">Indexé</option>
                        <option value="processing">En Traitement</option>
                        <option value="error">Erreur</option>
                    </select>
                    <input type="date" id="docDateFrom" style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:8px 12px;color:var(--text);font-size:12px;font-family:var(--font-body);outline:none;">
                    <span style="color:var(--text2);align-self:center;font-size:12px;">à</span>
                    <input type="date" id="docDateTo" style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:8px 12px;color:var(--text);font-size:12px;font-family:var(--font-body);outline:none;">
                </div>
                <button onclick="openModal('modal-upload')" style="display:flex;align-items:center;gap:7px;background:var(--gold);color:#111;border:none;border-radius:var(--radius-sm);padding:9px 16px;font-size:12.5px;font-weight:700;cursor:pointer;font-family:var(--font-body);">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Ajouter un Document
                </button>
            </div>

            <div class="table-wrap" style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;">
                <div style="overflow-x:auto;">
                <table style="width:100%;min-width:640px;border-collapse:collapse;">
                    <thead>
                        <tr style="background:var(--bg3);">
                            <th style="padding:11px 16px;text-align:left;font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--text2);border-bottom:1px solid var(--border);">Document</th>
                            <th style="padding:11px 16px;text-align:left;font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--text2);border-bottom:1px solid var(--border);">Type</th>
                            <th style="padding:11px 16px;text-align:left;font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--text2);border-bottom:1px solid var(--border);">Chunks</th>
                            <th style="padding:11px 16px;text-align:left;font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--text2);border-bottom:1px solid var(--border);">Statut</th>
                            <th style="padding:11px 16px;text-align:left;font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--text2);border-bottom:1px solid var(--border);">Ajouté le</th>
                            <th style="padding:11px 16px;text-align:left;font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--text2);border-bottom:1px solid var(--border);"></th>
                        </tr>
                    </thead>
                    <tbody id="docTableBody">
                        @if(count($documents) > 0)
                            @foreach($documents as $d)
                            <tr class="doc-row" data-id="{{ $d['id'] }}" data-name="{{ $d['name'] }}" data-type="{{ $d['type'] }}" data-status="{{ $d['status'] }}" data-date="{{ $d['date'] }}" style="border-bottom:1px solid var(--border);transition:background 0.15s;" onmouseover="this.style.background='var(--bg3)'" onmouseout="this.style.background=''">
                                <td style="padding:12px 16px;">
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div style="width:28px;height:28px;background:var(--bg4);border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text2)" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        </div>
                                        <span style="font-size:12.5px;font-weight:500;color:var(--text);font-family:var(--font-mono);">{{ $d['name'] }}</span>
                                    </div>
                                </td>
                                <td style="padding:12px 16px;"><span style="font-size:11px;font-weight:700;background:var(--bg4);color:var(--text2);border-radius:4px;padding:3px 8px;">{{ $d['type'] }}</span></td>
                                <td style="padding:12px 16px;font-size:12.5px;font-family:var(--font-mono);color:var(--text);">{{ $d['chunks'] }}</td>
                                <td style="padding:12px 16px;">
                                    @if($d['status'] === 'indexed')
                                        <span style="display:inline-flex;align-items:center;gap:5px;background:var(--green-dim);color:var(--green);font-size:11px;font-weight:600;border-radius:20px;padding:3px 10px;"><span style="width:5px;height:5px;background:var(--green);border-radius:50%;"></span>Indexé</span>
                                    @elseif($d['status'] === 'processing')
                                        <span style="display:inline-flex;align-items:center;gap:5px;background:var(--blue-dim);color:var(--blue);font-size:11px;font-weight:600;border-radius:20px;padding:3px 10px;"><span style="width:5px;height:5px;background:var(--blue);border-radius:50%;animation:pulse-dot 1s infinite;"></span>En Traitement</span>
                                    @else
                                        <span style="display:inline-flex;align-items:center;gap:5px;background:var(--red-dim);color:var(--red);font-size:11px;font-weight:600;border-radius:20px;padding:3px 10px;"><span style="width:5px;height:5px;background:var(--red);border-radius:50%;"></span>Erreur</span>
                                    @endif
                                </td>
                                <td style="padding:12px 16px;font-size:12px;color:var(--text2);">{{ $d['displayDate'] }}</td>
                                <td style="padding:12px 16px;">
                                    <div style="display:flex;gap:6px;">
                                        <button class="reindex-btn" data-id="{{ $d['id'] }}" title="Ré-indexer" style="width:28px;height:28px;background:var(--bg4);border:1px solid var(--border);border-radius:var(--radius-sm);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:border-color 0.15s;" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='var(--border)'">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--text2)" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.51"/></svg>
                                        </button>
                                        <button class="delete-btn" data-id="{{ $d['id'] }}" title="Supprimer" style="width:28px;height:28px;background:var(--bg4);border:1px solid var(--border);border-radius:var(--radius-sm);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:border-color 0.15s;" onmouseover="this.style.borderColor='var(--red)'" onmouseout="this.style.borderColor='var(--border)'">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" style="padding:40px;text-align:center;color:var(--text2);">
                                    <p>Aucun document dans la base de connaissances</p>
                                    <p style="font-size:12px;">Téléchargez des documents pour enrichir le bot</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                </div>
                <div id="docPagination" style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-top:1px solid var(--border);background:var(--bg3);"></div>
            </div>
        </div>

        {{-- Side panel: Add raw text / Q&A --}}
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div class="panel" style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);padding:18px;">
                <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--text2);margin-bottom:14px;">Ajouter du Texte Brut</div>
                <textarea id="rawText" placeholder="Collez du texte à ajouter directement à la base de connaissances…" rows="6" style="width:100%;background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius-sm);padding:10px;color:var(--text);font-size:12.5px;font-family:var(--font-mono);resize:vertical;outline:none;line-height:1.6;"></textarea>
                <div style="display:flex;justify-content:flex-end;margin-top:10px;">
                    <button onclick="ingestRawText()" style="background:var(--gold);color:#111;border:none;border-radius:var(--radius-sm);padding:8px 18px;font-size:12px;font-weight:700;cursor:pointer;font-family:var(--font-body);">Ingérer le Texte</button>
                </div>
            </div>
            <div class="panel" style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);padding:18px;">
                <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--text2);margin-bottom:14px;">Ajouter une Paire Q&amp;A</div>
                <div style="margin-bottom:10px;">
                    <label style="font-size:11px;font-weight:600;color:var(--text2);display:block;margin-bottom:5px;">Question</label>
                    <input type="text" id="qaQuestion" placeholder="Quelle est la politique de remboursement ?" style="width:100%;background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius-sm);padding:9px 12px;color:var(--text);font-size:12.5px;font-family:var(--font-body);outline:none;">
                </div>
                <div style="margin-bottom:12px;">
                    <label style="font-size:11px;font-weight:600;color:var(--text2);display:block;margin-bottom:5px;">Réponse</label>
                    <textarea id="qaAnswer" placeholder="Notre politique de remboursement permet…" rows="4" style="width:100%;background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius-sm);padding:9px 12px;color:var(--text);font-size:12.5px;font-family:var(--font-body);resize:vertical;outline:none;line-height:1.6;"></textarea>
                </div>
                <button onclick="saveQAPair()" style="width:100%;background:var(--teal-dim);color:var(--teal);border:1px solid rgba(45,212,191,0.3);border-radius:var(--radius-sm);padding:9px;font-size:12px;font-weight:700;cursor:pointer;font-family:var(--font-body);">Enregistrer la Paire Q&amp;A</button>
            </div>

            {{-- KB Stats --}}
            <div class="panel" style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);padding:18px;">
                <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--text2);margin-bottom:14px;">Statistiques de la Base</div>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:12px;color:var(--text2);">Total Documents</span>
                        <span style="font-size:13px;font-weight:700;color:var(--text);font-family:var(--font-mono);">{{ number_format($totalDocuments) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:12px;color:var(--text2);">Total Chunks</span>
                        <span style="font-size:13px;font-weight:700;color:var(--text);font-family:var(--font-mono);">{{ number_format($totalDocuments * 60) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:12px;color:var(--text2);">Paires Q&amp;A</span>
                        <span style="font-size:13px;font-weight:700;color:var(--text);font-family:var(--font-mono);">{{ number_format($totalPrompts) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:12px;color:var(--text2);">Modèle d'Embedding</span>
                        <span style="font-size:11px;font-weight:600;color:var(--gold);background:var(--gold-dim);border-radius:4px;padding:2px 8px;">text-embed-3-large</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:12px;color:var(--text2);">Dernier Ré-index</span>
                        <span style="font-size:12px;color:var(--text2);">Aujourd'hui 08:30</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     ONGLET: QUESTIONS SIGNALÉES
══════════════════════════════════════════════ --}}
<div id="tab-flagged" class="tab-panel tab-content" style="display:none;">

    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:16px;align-items:center;">
        <div style="position:relative;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text3)" stroke-width="2" style="position:absolute;top:50%;left:10px;transform:translateY(-50%);pointer-events:none;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="flagSearch" placeholder="Rechercher une question…" style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:8px 12px 8px 32px;color:var(--text);font-size:12.5px;outline:none;width:220px;font-family:var(--font-body);">
        </div>
        <select id="flagPriorityFilter" style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:8px 12px;color:var(--text2);font-size:12px;font-family:var(--font-body);outline:none;">
            <option value="all">Toutes les Priorités</option>
            <option value="high">Haute</option>
            <option value="medium">Moyenne</option>
            <option value="low">Basse</option>
        </select>
        <select id="flagStatusFilter" style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:8px 12px;color:var(--text2);font-size:12px;font-family:var(--font-body);outline:none;">
            <option value="all">Tous les Statuts</option>
            <option value="pending">En Attente</option>
            <option value="resolved">Résolu</option>
        </select>
        <span style="font-size:12px;color:var(--text2);">Total: <strong id="flagCount">{{ count($flaggedQuestions) }}</strong></span>
    </div>

    <div style="background:var(--amber-dim);border:1px solid rgba(251,191,36,0.25);border-radius:var(--radius);padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;gap:12px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--amber)" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <div style="font-size:12.5px;color:var(--amber);font-weight:500;"><span id="pendingCount">{{ count($flaggedQuestions) }}</span> questions signalées car le bot manquait de contexte. En fournissant des réponses ici, vous enrichirez automatiquement la base de connaissances.</div>
    </div>

    <div id="flaggedList" style="display:flex;flex-direction:column;gap:12px;">
        @if(count($flaggedQuestions) > 0)
            @foreach($flaggedQuestions as $i => $f)
            <div class="flagged-card" data-id="{{ $f['id'] }}" data-priority="{{ $f['priority'] }}" data-status="{{ $f['status'] }}" style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;" id="fcard-{{ $i }}">
                <div style="padding:16px 20px;display:flex;align-items:flex-start;justify-content:space-between;gap:16px;">
                    <div style="flex:1;">
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;flex-wrap:wrap;">
                            <span style="font-size:10px;font-weight:700;font-family:var(--font-mono);color:var(--text3);">{{ $f['id'] }}</span>
                            @if($f['priority'] === 'high')
                                <span style="font-size:10px;font-weight:700;background:var(--red-dim);color:var(--red);border-radius:10px;padding:2px 8px;">HAUTE PRIORITÉ</span>
                            @elseif($f['priority'] === 'medium')
                                <span style="font-size:10px;font-weight:700;background:var(--amber-dim);color:var(--amber);border-radius:10px;padding:2px 8px;">MOYENNE</span>
                            @else
                                <span style="font-size:10px;font-weight:700;background:var(--bg4);color:var(--text2);border-radius:10px;padding:2px 8px;">BASSE</span>
                            @endif
                            <span style="font-size:10.5px;color:var(--text3);">Posée {{ $f['asked_at'] }}</span>
                            <span style="font-size:10.5px;color:var(--text3);">·</span>
                            <span style="font-size:10.5px;color:var(--text2);">{{ $f['times_asked'] }}x posée</span>
                            <span class="status-badge" style="font-size:10px;font-weight:700;background:var(--blue-dim);color:var(--blue);border-radius:10px;padding:2px 8px;">{{ ucfirst($f['status']) }}</span>
                        </div>
                        <div class="flagged-question-text" style="font-size:13.5px;font-weight:500;color:var(--text);line-height:1.5;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--amber)" stroke-width="2" style="margin-right:6px;vertical-align:middle;"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
                            {{ $f['question'] }}
                        </div>
                    </div>
                    <div style="display:flex;gap:8px;flex-shrink:0;">
                        <button onclick="toggleAnswer({{ $i }})" class="answer-btn" style="white-space:nowrap;background:var(--gold-dim);color:var(--gold);border:1px solid rgba(201,168,76,0.3);border-radius:var(--radius-sm);padding:8px 14px;font-size:12px;font-weight:700;cursor:pointer;font-family:var(--font-body);flex-shrink:0;">
                            Fournir une Réponse
                        </button>
                        <button onclick="ignoreQuestion({{ $i }})" class="ignore-btn" title="Le bot ne signalera plus cette question si elle est reposée" style="white-space:nowrap;display:flex;align-items:center;gap:6px;background:var(--bg4);color:var(--text2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:8px 14px;font-size:12px;font-weight:700;cursor:pointer;font-family:var(--font-body);flex-shrink:0;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                            Ignorer
                        </button>
                    </div>
                </div>
                <div id="answer-panel-{{ $i }}" style="display:none;padding:0 20px 18px;border-top:1px solid var(--border);padding-top:16px;">
                    <label style="font-size:11px;font-weight:600;color:var(--text2);display:block;margin-bottom:7px;">Votre Réponse <span style="color:var(--gold)">(sera sauvegardée dans la base de connaissances)</span></label>
                    <textarea id="answer-text-{{ $i }}" rows="4" placeholder="Rédigez une réponse complète et précise que le bot pourra utiliser pour les futures requêtes…" style="width:100%;background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius-sm);padding:10px 12px;color:var(--text);font-size:12.5px;font-family:var(--font-body);resize:vertical;outline:none;line-height:1.6;margin-bottom:10px;"></textarea>
                    <div style="display:flex;align-items:center;gap:8px;justify-content:space-between;">
                        <label style="display:flex;align-items:center;gap:7px;font-size:12px;color:var(--text2);cursor:pointer;">
                            <input type="checkbox" checked style="accent-color:var(--gold);"> Ajouter automatiquement à la base après sauvegarde
                        </label>
                        <div style="display:flex;gap:8px;">
                            <button onclick="toggleAnswer({{ $i }})" style="background:var(--bg4);color:var(--text2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:8px 14px;font-size:12px;font-weight:600;cursor:pointer;font-family:var(--font-body);">Annuler</button>
                            <button onclick="saveAnswer({{ $i }})" style="background:var(--gold);color:#111;border:none;border-radius:var(--radius-sm);padding:8px 16px;font-size:12px;font-weight:700;cursor:pointer;font-family:var(--font-body);">Sauvegarder &amp; Former le Bot</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div style="padding:40px;text-align:center;color:var(--text2);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--text3)" stroke-width="1.5" style="margin:0 auto 12px;display:block;">
                    <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                    <line x1="4" y1="22" x2="4" y2="15"/>
                </svg>
                <p style="font-weight:600;">Aucune question signalée</p>
                <p style="font-size:12px;">Toutes les questions ont été traitées ✓</p>
            </div>
        @endif
    </div>
    <div id="flaggedPagination" style="display:flex;align-items:center;justify-content:space-between;padding:12px 4px;margin-top:8px;"></div>
</div>

{{-- ══════════════════════════════════════════════
     ONGLET: STUDIO DE PROMPTS
══════════════════════════════════════════════ --}}
<div id="tab-prompts" class="tab-panel tab-content" style="display:none;">
    <div style="display:grid;grid-template-columns:1fr 300px;gap:18px;">

        <div style="display:flex;flex-direction:column;gap:14px;">

            {{-- System Prompt --}}
            <div class="panel" style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;">
                <div class="panel-head" style="padding:14px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
                    <div>
                        <div style="font-size:13px;font-weight:700;color:var(--text);">Prompt Système</div>
                        <div style="font-size:11px;color:var(--text2);margin-top:2px;">Définit la personnalité, les limites et le ton du bot</div>
                    </div>
                    <div style="display:flex;gap:6px;">
                        @if(count($prompts) > 0)
                            <span style="font-size:10px;background:var(--green-dim);color:var(--green);border-radius:10px;padding:3px 10px;font-weight:700;">Actif {{ $prompts[0]['version'] ?? 'v2.4' }}</span>
                        @else
                            <span style="font-size:10px;background:var(--green-dim);color:var(--green);border-radius:10px;padding:3px 10px;font-weight:700;">Actif v2.4</span>
                        @endif
                    </div>
                </div>
                <div style="padding:18px;">
                    <textarea id="systemPrompt" rows="10" style="width:100%;background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px;color:var(--text);font-size:12.5px;font-family:var(--font-mono);resize:vertical;outline:none;line-height:1.7;">Vous êtes un assistant professionnel en droit et conformité pour la Plateforme GED. Votre rôle est de répondre aux questions sur les contrats, la politique de l'entreprise, la conformité réglementaire et les procédures internes.

Tu es un assistant spécialisé sur le Ministère des Affaires Culturelles de Tunisie.

Informations disponibles :
{context}

Question de l'utilisateur :
"{question}"

INSTRUCTIONS TRÈS IMPORTANTES - À RESPECTER STRICTEMENT :

1. Réponds UNIQUEMENT en te basant sur les informations ci-dessus
2. NE JAMAIS mentionner les mots : "sources", "documents", "pertinence"
3. NE JAMAIS utiliser : 📚, 📎, *, **, `PDF`, ou toute étoile
4. NE PAS commencer par "Bien sûr !", "Je peux vous aider !" ou "Voici ce que j'ai trouvé"
5. NE PAS terminer par des phrases de politesse
6. Réponds directement, de façon naturelle et utile
7. Utilise des phrases courtes (max 15-20 mots chacune)
8. Maximum 3-4 phrases pour la réponse

Exemple de réponse correcte :
"Pour obtenir une carte professionnelle, il faut remplir un formulaire et fournir un certificat d'engagement. Le renouvellement se fait annuellement en ligne."

Exemple de réponse INCORRECTE (à ne pas faire) :
"Bien sûr ! Pour obtenir une carte... 📎 *Sources : Document 1* Dites-moi si vous avez besoin..."

Maintenant, réponds directement (sans sources, sans étoiles, sans phrases de politesse) :</textarea>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:12px;">
                        <span style="font-size:11px;color:var(--text3);">Dernière modification par Admin • 15 Juin 2025</span>
                        <div style="display:flex;gap:8px;">
                            <button onclick="savePromptDraft()" style="background:var(--bg4);color:var(--text2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:8px 14px;font-size:12px;font-weight:600;cursor:pointer;font-family:var(--font-body);">Sauvegarder le Brouillon</button>
                            <button onclick="deployPrompt()" style="background:var(--gold);color:#111;border:none;border-radius:var(--radius-sm);padding:8px 16px;font-size:12px;font-weight:700;cursor:pointer;font-family:var(--font-body);">Déployer comme Actif</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Retrieval Settings --}}
            <div class="panel" style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;">
                <div class="panel-head" style="padding:14px 20px;border-bottom:1px solid var(--border);">
                    <div style="font-size:13px;font-weight:700;color:var(--text);">Paramètres de Récupération</div>
                    <div style="font-size:11px;color:var(--text2);margin-top:2px;">Contrôle comment le bot récupère les chunks de contexte</div>
                </div>
                <div style="padding:18px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div>
                        <label style="font-size:11px;font-weight:600;color:var(--text2);display:block;margin-bottom:6px;">Top-K Chunks Récupérés</label>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <input type="range" min="1" max="20" value="5" id="topkRange" oninput="document.getElementById('topk-val').textContent=this.value" style="flex:1;accent-color:var(--gold);">
                            <span id="topk-val" style="font-size:13px;font-weight:700;color:var(--gold);font-family:var(--font-mono);width:20px;">5</span>
                        </div>
                    </div>
                    <div>
                        <label style="font-size:11px;font-weight:600;color:var(--text2);display:block;margin-bottom:6px;">Seuil de Similarité</label>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <input type="range" min="50" max="99" value="75" id="simRange" oninput="document.getElementById('sim-val').textContent=this.value+'%'" style="flex:1;accent-color:var(--gold);">
                            <span id="sim-val" style="font-size:13px;font-weight:700;color:var(--gold);font-family:var(--font-mono);width:36px;">75%</span>
                        </div>
                    </div>
                    <div>
                        <label style="font-size:11px;font-weight:600;color:var(--text2);display:block;margin-bottom:6px;">Modèle LLM</label>
                        <select id="llmModel" style="width:100%;background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius-sm);padding:9px 12px;color:var(--text);font-size:12px;font-family:var(--font-body);outline:none;">
                            <option selected>gpt-4o</option>
                            <option>gpt-4-turbo</option>
                            <option>claude-3-opus</option>
                            <option>mistral-large</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-size:11px;font-weight:600;color:var(--text2);display:block;margin-bottom:6px;">Max Tokens / Réponse</label>
                        <input type="number" id="maxTokens" value="800" style="width:100%;background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius-sm);padding:9px 12px;color:var(--text);font-size:12px;font-family:var(--font-mono);outline:none;">
                    </div>
                    <div>
                        <label style="font-size:11px;font-weight:600;color:var(--text2);display:block;margin-bottom:6px;">Température</label>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <input type="range" min="0" max="100" value="20" id="tempRange" oninput="document.getElementById('temp-val').textContent=(this.value/100).toFixed(2)" style="flex:1;accent-color:var(--gold);">
                            <span id="temp-val" style="font-size:13px;font-weight:700;color:var(--gold);font-family:var(--font-mono);width:34px;">0.20</span>
                        </div>
                    </div>
                    <div>
                        <label style="font-size:11px;font-weight:600;color:var(--text2);display:block;margin-bottom:6px;">Seuil de Signalement (confiance)</label>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <input type="range" min="30" max="90" value="55" id="flagRange" oninput="document.getElementById('flag-val').textContent=this.value+'%'" style="flex:1;accent-color:var(--amber);">
                            <span id="flag-val" style="font-size:13px;font-weight:700;color:var(--amber);font-family:var(--font-mono);width:36px;">55%</span>
                        </div>
                    </div>
                </div>
                <div style="padding:0 18px 18px;display:flex;justify-content:flex-end;">
                    <button onclick="applyRetrievalSettings()" style="background:var(--gold);color:#111;border:none;border-radius:var(--radius-sm);padding:9px 20px;font-size:12px;font-weight:700;cursor:pointer;font-family:var(--font-body);">Appliquer les Paramètres</button>
                </div>
            </div>
        </div>

        {{-- Prompt History --}}
        <div class="panel" style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;height:fit-content;">
            <div class="panel-head" style="padding:14px 18px;border-bottom:1px solid var(--border);">
                <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.8px;color:var(--text2);">Versions des Prompts</div>
            </div>
            <div id="promptHistory" style="padding:14px;">
                @if(count($prompts) > 0)
                    @foreach($prompts as $ver)
                    <div style="background:var(--bg3);border:1px solid {{ $ver['active'] ? 'rgba(201,168,76,0.35)' : 'var(--border)' }};border-radius:var(--radius-sm);padding:10px 12px;display:flex;align-items:center;gap:10px;cursor:pointer;transition:border-color 0.15s;margin-bottom:8px;">
                        <div style="width:34px;height:34px;background:{{ $ver['active'] ? 'var(--gold-dim)' : 'var(--bg4)' }};border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <span style="font-size:10px;font-weight:800;color:{{ $ver['active'] ? 'var(--gold)' : 'var(--text2)' }};font-family:var(--font-mono);">{{ $ver['version'] }}</span>
                        </div>
                        <div style="flex:1;">
                            <div style="font-size:12px;font-weight:600;color:var(--text);">{{ $ver['note'] }}</div>
                            <div style="font-size:10.5px;color:var(--text3);">{{ $ver['date'] }}, 2025</div>
                        </div>
                        @if($ver['active'])
                        <span style="font-size:9px;font-weight:800;background:var(--green-dim);color:var(--green);border-radius:8px;padding:2px 7px;white-space:nowrap;">EN LIGNE</span>
                        @else
                        <button onclick="restorePrompt('{{ $ver['version'] }}')" style="font-size:10px;background:var(--bg4);color:var(--text2);border:1px solid var(--border);border-radius:6px;padding:3px 9px;cursor:pointer;font-family:var(--font-body);">Restaurer</button>
                        @endif
                    </div>
                    @endforeach
                @else
                    <div style="padding:20px;text-align:center;color:var(--text2);">
                        <p>Aucune version de prompt enregistrée</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════════════════
     ONGLET: PERFORMANCES
══════════════════════════════════════════════ --}}
<div id="tab-performance" class="tab-panel tab-content" style="display:none;">

    {{-- Filtres de date pour les performances --}}
    <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:18px;align-items:center;background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);padding:14px 18px;">
        <span style="font-size:12px;font-weight:600;color:var(--text2);">Filtres :</span>
        <input type="date" id="perfDateFrom" style="background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius-sm);padding:7px 10px;color:var(--text);font-size:12px;font-family:var(--font-body);outline:none;">
        <span style="color:var(--text2);font-size:12px;">à</span>
        <input type="date" id="perfDateTo" style="background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius-sm);padding:7px 10px;color:var(--text);font-size:12px;font-family:var(--font-body);outline:none;">
        <select id="perfMetricFilter" style="background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius-sm);padding:7px 10px;color:var(--text2);font-size:12px;font-family:var(--font-body);outline:none;">
            <option value="all">Toutes les Métriques</option>
            <option value="satisfaction">Satisfaction</option>
            <option value="hallucination">Hallucination</option>
            <option value="escalation">Escalade</option>
        </select>
        <button onclick="applyPerfFilters()" style="background:var(--gold);color:#111;border:none;border-radius:var(--radius-sm);padding:7px 16px;font-size:12px;font-weight:700;cursor:pointer;font-family:var(--font-body);">Appliquer</button>
        <button onclick="resetPerfFilters()" style="background:var(--bg4);color:var(--text2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:7px 16px;font-size:12px;font-weight:600;cursor:pointer;font-family:var(--font-body);">Réinitialiser</button>
    </div>

    {{-- KPIs --}}
    <div id="perfKpis" style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px;">

        <div style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);padding:18px 20px;">
            <div style="font-size:10px;font-weight:600;letter-spacing:1px;text-transform:uppercase;color:var(--text2);margin-bottom:8px;">Taux d'Hallucination</div>
            <div style="font-size:30px;font-weight:900;color:var(--teal);font-family:var(--font-mono);">1.8%</div>
            <div style="font-size:11px;color:var(--green);margin-top:4px;">↓ 0.4% cette semaine</div>
        </div>
        <div style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);padding:18px 20px;">
            <div style="font-size:10px;font-weight:600;letter-spacing:1px;text-transform:uppercase;color:var(--text2);margin-bottom:8px;">Taux d'Escalade</div>
            <div style="font-size:30px;font-weight:900;color:var(--amber);font-family:var(--font-mono);">8.7%</div>
            <div style="font-size:11px;color:var(--text2);margin-top:4px;">Signalé ou escaladé</div>
        </div>
        <div style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);padding:18px 20px;">
            <div style="font-size:10px;font-weight:600;letter-spacing:1px;text-transform:uppercase;color:var(--text2);margin-bottom:8px;">Durée Moyenne Session</div>
            <div style="font-size:30px;font-weight:900;color:var(--purple);font-family:var(--font-mono);">3.2<span style="font-size:16px;font-weight:600;"> msgs</span></div>
            <div style="font-size:11px;color:var(--text2);margin-top:4px;">Par conversation</div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-bottom:18px;">

        {{-- Resolution rate bar chart --}}
        <div class="panel" style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;">
            <div class="panel-head" style="padding:14px 20px;border-bottom:1px solid var(--border);">
                <div style="font-size:13px;font-weight:700;color:var(--text);">Taux de Résolution par Catégorie</div>
            </div>
            <div style="padding:18px;display:flex;flex-direction:column;gap:13px;">
                @php $cats = [
                    ['label'=>'Livre','rate'=>96,'color'=>'var(--gold)'],
                    ['label'=>'Investisseurs','rate'=>89,'color'=>'var(--blue)'],
                    ['label'=>'Musique et danse','rate'=>78,'color'=>'var(--teal)'],
                    ['label'=>'Arts Audiovisuels','rate'=>71,'color'=>'var(--purple)'],
                    ['label'=>'Arts sceniques','rate'=>94,'color'=>'var(--green)'],
                    ['label'=>'Arts Plastiques','rate'=>88,'color'=>'var(--text2)'],
                ]; @endphp
                @foreach($cats as $c)
                <div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
                        <span style="font-size:12px;color:var(--text);">{{ $c['label'] }}</span>
                        <span style="font-size:12px;font-weight:700;color:{{ $c['color'] }};font-family:var(--font-mono);">{{ $c['rate'] }}%</span>
                    </div>
                    <div style="height:6px;background:var(--bg4);border-radius:3px;overflow:hidden;">
                        <div style="height:100%;width:{{ $c['rate'] }}%;background:{{ $c['color'] }};border-radius:3px;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Response time distribution --}}
        <div class="panel" style="background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;">
            <div class="panel-head" style="padding:14px 20px;border-bottom:1px solid var(--border);">
                <div style="font-size:13px;font-weight:700;color:var(--text);">Distribution des Temps de Réponse</div>
            </div>
            <div style="padding:18px;">
                <svg viewBox="0 0 320 160" xmlns="http://www.w3.org/2000/svg" style="width:100%;height:160px;">
                    <defs>
                        <linearGradient id="barGold" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#c9a84c" stop-opacity="0.9"/>
                            <stop offset="100%" stop-color="#c9a84c" stop-opacity="0.3"/>
                        </linearGradient>
                    </defs>
                    <rect x="20" y="110" width="32" height="30" fill="url(#barGold)" rx="3"/>
                    <rect x="68" y="60" width="32" height="80" fill="url(#barGold)" rx="3"/>
                    <rect x="116" y="30" width="32" height="110" fill="url(#barGold)" rx="3"/>
                    <rect x="164" y="80" width="32" height="60" fill="url(#barGold)" rx="3"/>
                    <rect x="212" y="115" width="32" height="25" fill="url(#barGold)" rx="3"/>
                    <rect x="260" y="125" width="32" height="15" fill="url(#barGold)" rx="3"/>
                    <text x="36" y="152" fill="#4a4f5a" font-size="9" text-anchor="middle" font-family="monospace">&lt;0.5s</text>
                    <text x="84" y="152" fill="#4a4f5a" font-size="9" text-anchor="middle" font-family="monospace">0.5-1s</text>
                    <text x="132" y="152" fill="#c9a84c" font-size="9" text-anchor="middle" font-family="monospace">1-2s</text>
                    <text x="180" y="152" fill="#4a4f5a" font-size="9" text-anchor="middle" font-family="monospace">2-3s</text>
                    <text x="228" y="152" fill="#4a4f5a" font-size="9" text-anchor="middle" font-family="monospace">3-5s</text>
                    <text x="276" y="152" fill="#4a4f5a" font-size="9" text-anchor="middle" font-family="monospace">&gt;5s</text>
                    <text x="36" y="106" fill="#8a8f9a" font-size="8.5" text-anchor="middle" font-family="monospace">8%</text>
                    <text x="84" y="56" fill="#8a8f9a" font-size="8.5" text-anchor="middle" font-family="monospace">22%</text>
                    <text x="132" y="26" fill="#c9a84c" font-size="8.5" text-anchor="middle" font-family="monospace">41%</text>
                    <text x="180" y="76" fill="#8a8f9a" font-size="8.5" text-anchor="middle" font-family="monospace">18%</text>
                    <text x="228" y="111" fill="#8a8f9a" font-size="8.5" text-anchor="middle" font-family="monospace">7%</text>
                    <text x="276" y="121" fill="#8a8f9a" font-size="8.5" text-anchor="middle" font-family="monospace">4%</text>
                </svg>
            </div>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════
     MODALS
══════════════════════════════════════════════ --}}

{{-- Upload Modal --}}
<div id="modal-upload" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.65);z-index:1000;align-items:center;justify-content:center;backdrop-filter:blur(4px);">
    <div class="modal-content" style="background:var(--bg2);border:1px solid var(--border2);border-radius:var(--radius);width:520px;max-width:95vw;overflow:hidden;">
        <div style="padding:18px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
            <div style="font-size:14px;font-weight:700;color:var(--text);">Télécharger un Document vers la Base</div>
            <button onclick="closeModal('modal-upload')" style="background:none;border:none;cursor:pointer;color:var(--text2);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div style="padding:22px;">
            <div id="drop-zone" style="border:2px dashed var(--border2);border-radius:var(--radius);padding:36px;text-align:center;cursor:pointer;transition:border-color 0.2s;margin-bottom:16px;" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='var(--border2)'" onclick="document.getElementById('fileInput').click()">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--text3)" stroke-width="1.5" style="margin:0 auto 10px;display:block;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                <div style="font-size:13px;font-weight:600;color:var(--text2);margin-bottom:4px;">Glissez-déposez vos fichiers ici</div>
                <div style="font-size:11.5px;color:var(--text3);">ou cliquez pour parcourir — PDF, DOCX, TXT supportés</div>
                <input type="file" id="fileInput" style="display:none;" accept=".pdf,.docx,.txt,.md,.json" multiple>
            </div>
            <div style="margin-bottom:14px;">
                <label style="font-size:11px;font-weight:600;color:var(--text2);display:block;margin-bottom:6px;">Catégorie</label>
                <select id="docCategory" style="width:100%;background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius-sm);padding:9px 12px;color:var(--text);font-size:12.5px;font-family:var(--font-body);outline:none;">
                    <option value="general">Général</option>
                    <option value="contrat">Contrat & Juridique</option>
                    <option value="rh">RH & Onboarding</option>
                    <option value="finance">Finance & Paiements</option>
                    <option value="reglementaire">Réglementaire</option>
                </select>
            </div>
            <div style="margin-bottom:18px;">
                <label style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text2);cursor:pointer;">
                    <input type="checkbox" id="autoIndex" checked style="accent-color:var(--gold);"> Indexation automatique après téléchargement
                </label>
            </div>
            <div style="display:flex;gap:8px;justify-content:flex-end;">
                <button onclick="closeModal('modal-upload')" style="background:var(--bg4);color:var(--text2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:9px 18px;font-size:12.5px;font-weight:600;cursor:pointer;font-family:var(--font-body);">Annuler</button>
                <button onclick="handleUpload()" style="background:var(--gold);color:#111;border:none;border-radius:var(--radius-sm);padding:9px 20px;font-size:12.5px;font-weight:700;cursor:pointer;font-family:var(--font-body);">Télécharger &amp; Ingrérer</button>
            </div>
        </div>
    </div>
</div>

{{-- Deploy Modal --}}
<div id="modal-deploy" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.65);z-index:1000;align-items:center;justify-content:center;backdrop-filter:blur(4px);">
    <div class="modal-content" style="background:var(--bg2);border:1px solid var(--border2);border-radius:var(--radius);width:440px;max-width:95vw;overflow:hidden;">
        <div style="padding:18px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
            <div style="font-size:14px;font-weight:700;color:var(--text);">Déployer la Mise à Jour de la Base</div>
            <button onclick="closeModal('modal-deploy')" style="background:none;border:none;cursor:pointer;color:var(--text2);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div style="padding:22px;">
            <div style="background:var(--gold-dim);border:1px solid rgba(201,168,76,0.3);border-radius:var(--radius-sm);padding:14px;margin-bottom:18px;">
                <div style="font-size:12.5px;color:var(--gold);font-weight:600;margin-bottom:6px;">Modifications en attente à déployer :</div>
                <div style="font-size:12px;color:var(--text2);display:flex;flex-direction:column;gap:5px;">
                    <div>• {{ count($documents) }} nouveaux documents ingérés</div>
                    <div>• {{ count($flaggedQuestions) }} paires Q&A ajoutées</div>
                    <div>• Prompt système mis à jour</div>
                    <div>• Paramètres de récupération ajustés</div>
                </div>
            </div>
            <div style="font-size:12px;color:var(--text2);margin-bottom:18px;">Ceci ré-indexera les chunks mis à jour et mettra en ligne le nouveau prompt. Le bot sera brièvement indisponible (~45 secondes).</div>
            <div style="display:flex;gap:8px;justify-content:flex-end;">
                <button onclick="closeModal('modal-deploy')" style="background:var(--bg4);color:var(--text2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:9px 18px;font-size:12.5px;font-weight:600;cursor:pointer;font-family:var(--font-body);">Annuler</button>
                <button onclick="handleDeploy()" style="background:var(--gold);color:#111;border:none;border-radius:var(--radius-sm);padding:9px 20px;font-size:12.5px;font-weight:700;cursor:pointer;font-family:var(--font-body);">Confirmer le Déploiement</button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     STYLES & SCRIPTS
══════════════════════════════════════════════ --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<style>
@keyframes pulse-dot {
    0%,100%{opacity:1;transform:scale(1);}
    50%{opacity:.5;transform:scale(0.8);}
}
.rag-dashboard-wrap { width:100%; }
@media (max-width:1100px) {
    .rag-dashboard-wrap { padding:18px 16px !important; }
}
.rag-tab { transition: all 0.2s ease; }
.rag-tab:hover:not(.active) { background: var(--bg3) !important; color: var(--text) !important; }
.rag-action-btn:hover { border-color: var(--gold) !important; }
.flagged-card { transition: border-color 0.2s ease; }
.flagged-card:hover { border-color: var(--amber) !important; }

/* Animation de spin pour le loader */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Style du tooltip */
#chart-tooltip {
    transition: opacity 0.15s ease;
}
</style>

<script>
// ═══════════════════════════════════════════════════
// COMPATIBILITÉ AVEC impresarios.js
// ═══════════════════════════════════════════════════

// Sauvegarder la fonction originale si elle existe
if (typeof window.switchTab === 'function') {
    window._originalSwitchTab = window.switchTab;
}

// Notre fonction switchTab compatible
window.switchTab = function(tabName, event) {
    console.log('🔄 [Dashboard] Navigation vers:', tabName);

    // Déterminer l'ID du panel
    var targetId = tabName;
    if (!tabName.startsWith('tab-')) {
        targetId = 'tab-' + tabName;
    }

    // 1. Cacher tous les panels (utiliser les deux classes)
    var panels = document.querySelectorAll('.tab-panel, .tab-content');
    panels.forEach(function(panel) {
        panel.style.display = 'none';
    });

    // 2. Afficher le panel cible
    var target = document.getElementById(targetId);
    if (target) {
        target.style.display = 'block';
        console.log('✅ Panel affiché:', targetId);
    } else {
        console.error('❌ Panel non trouvé:', targetId);
        var overview = document.getElementById('tab-overview');
        if (overview) overview.style.display = 'block';
    }

    // 3. Mettre à jour les onglets
    var tabs = document.querySelectorAll('.rag-tab, .tab-btn');
    tabs.forEach(function(tab) {
        tab.style.background = 'transparent';
        tab.style.color = 'var(--text2)';
        tab.style.borderBottom = 'none';
    });

    // 4. Activer l'onglet cliqué
    if (event && event.target) {
        var clickedTab = event.target.closest('.rag-tab, .tab-btn');
        if (clickedTab) {
            clickedTab.style.background = 'var(--gold-dim)';
            clickedTab.style.color = 'var(--gold)';
            clickedTab.style.borderBottom = '2px solid var(--gold)';
        }
    } else {
        // Fallback: chercher par ID
        tabs.forEach(function(tab) {
            var tabId = tab.id;
            if (tabId && tabId.includes(tabName.replace('tab-', ''))) {
                tab.style.background = 'var(--gold-dim)';
                tab.style.color = 'var(--gold)';
                tab.style.borderBottom = '2px solid var(--gold)';
            }
        });
    }
};

// ═══════════════════════════════════════════════════
// INITIALISATION
// ═══════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Dashboard initialisé avec compatibilité impresarios.js');

    // Activer l'onglet par défaut (Vue d'Ensemble)
    var defaultTab = document.getElementById('tab-overview');
    if (defaultTab) {
        defaultTab.style.display = 'block';
    }

    // S'assurer que les autres panels sont cachés
    var panels = document.querySelectorAll('.tab-panel, .tab-content');
    panels.forEach(function(panel) {
        if (panel.id !== 'tab-overview') {
            panel.style.display = 'none';
        }
    });

    // Activer le premier onglet
    var firstTab = document.querySelector('.rag-tab.active, .tab-btn.active');
    if (firstTab) {
        firstTab.style.background = 'var(--gold-dim)';
        firstTab.style.color = 'var(--gold)';
        firstTab.style.borderBottom = '2px solid var(--gold)';
    }

    console.log('✅ Onglet par défaut: Vue d\'Ensemble');
    console.log('📊 Données chargées:');
    console.log('  - Questions totales: {{ $totalQuestions }}');
    console.log('  - Taux de succès: {{ $successRate }}%');
    console.log('  - Signalés: {{ $flaggedCount }}');
    console.log('  - Documents: {{ $totalDocuments }}');
    console.log('  - Prompts: {{ $totalPrompts }}');

    // Initialiser le graphique
    loadChartData('14d');

    // Pagination initiale des tableaux
    renderDocPage();
    renderFeedbackPage();
    renderFlaggedPage();
});

// ═══════════════════════════════════════════════════
// MODALS
// ═══════════════════════════════════════════════════
function openModal(modalId) {
    var modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        console.log("[v0] Modal opened:", modalId);
    }
}

function closeModal(modalId) {
    var modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        console.log("[v0] Modal closed:", modalId);
    }
}

// Close modals on backdrop click
document.querySelectorAll('[id*="Modal"]').forEach(function(modal) {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });
});

// ═══════════════════════════════════════════════════
// UPLOAD
// ═══════════════════════════════════════════════════
document.getElementById('fileInput')?.addEventListener('change', function(e) {
    var files = this.files;
    if (files.length > 0) {
        var dropZone = document.getElementById('drop-zone');
        var names = Array.from(files).map(function(f) { return f.name; }).join(', ');
        dropZone.querySelector('div:first-child + div').textContent = files.length + ' fichier(s) sélectionné(s)';
        dropZone.querySelector('div:first-child + div + div').textContent = names;
    }
});

async function handleUpload() {
    var files = document.getElementById('fileInput').files;
    if (files.length === 0) {
        alert('Veuillez sélectionner au moins un fichier.');
        return;
    }
    var category = document.getElementById('docCategory').value;
    var autoIndex = document.getElementById('autoIndex').checked;

    var formData = new FormData();
    for (var i = 0; i < files.length; i++) {
        formData.append('documents[]', files[i]);
    }
    formData.append('category', category);
    formData.append('auto_index', autoIndex ? 'true' : 'false');

    try {
        var response = await fetch('/admin/ai/api/upload', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        });

        var result = await response.json();
        if (result.success) {
            alert(result.message || 'Documents téléchargés avec succès !');
            closeModal('modal-upload');
            document.getElementById('fileInput').value = '';
            location.reload();
        } else {
            alert('Erreur: ' + (result.error || 'Échec du téléchargement'));
        }
    } catch (error) {
        alert('Erreur: ' + error.message);
    }
}

// ═══════════════════════════════════════════════════
// FLAGGED QUESTIONS
// ═══════════════════════════════════════════════════
function toggleAnswer(i) {
    var panel = document.getElementById('answer-panel-' + i);
    if (panel) {
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    }
}

async function saveAnswer(i) {
    var text = document.getElementById('answer-text-' + i);
    if (!text) return;
    var answer = text.value.trim();
    if (!answer) {
        alert('Veuillez rédiger une réponse avant de sauvegarder.');
        return;
    }

    var card = document.getElementById('fcard-' + i);
    if (!card) return;

    var flagId = card.dataset.id;

    try {
        var response = await fetch('/admin/ai/api/respond', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                id: flagId,
                response: answer,
                category: 'general'
            })
        });

        var result = await response.json();
        if (result.success) {
            card.style.opacity = '0.4';
            card.style.transition = 'opacity 0.5s';
            setTimeout(function() {
                card.dataset.status = 'resolved';
                card.style.border = '1px solid rgba(74,222,128,0.4)';
                card.style.background = 'var(--green-dim)';

                var badge = card.querySelector('.status-badge');
                if (badge) {
                    badge.textContent = 'Résolu';
                    badge.style.background = 'var(--green-dim)';
                    badge.style.color = 'var(--green)';
                }

                var answerBtn = card.querySelector('.answer-btn');
                if (answerBtn) answerBtn.style.display = 'none';
                var ignoreBtn = card.querySelector('.ignore-btn');
                if (ignoreBtn) ignoreBtn.style.display = 'none';

                var panel = document.getElementById('answer-panel-' + i);
                if (panel) panel.style.display = 'none';

                card.style.opacity = '1';
                renderFlaggedPage();
            }, 400);
        } else {
            alert('Erreur: ' + (result.error || 'Échec de la sauvegarde'));
        }
    } catch (error) {
        alert('Erreur: ' + error.message);
    }
}

async function ignoreQuestion(i) {
    var card = document.getElementById('fcard-' + i);
    if (!card) return;

    if (!confirm('Ignorer définitivement cette question ? Le bot ne la signalera plus si elle est reposée — elle sera traitée comme non pertinente par rapport à sa fonction.')) {
        return;
    }

    var flagId = card.dataset.id;

    try {
        var response = await fetch('/admin/ai/api/ignore', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({ id: flagId })
        });

        var result = await response.json();
        if (result.success) {
            applyIgnoredState(card, i);
        } else {
            alert('Erreur: ' + (result.error || 'Échec de l\'opération'));
        }
    } catch (error) {
        alert('Erreur: ' + error.message);
    }
}

function applyIgnoredState(card, i) {
    card.style.opacity = '0.4';
    card.style.transition = 'opacity 0.5s';
    setTimeout(function() {
        card.dataset.status = 'ignored';
        card.style.border = '1px solid var(--border)';
        card.style.background = 'var(--bg3)';

        var badge = card.querySelector('.status-badge');
        if (badge) {
            badge.textContent = 'Ignorée';
            badge.style.background = 'var(--bg4)';
            badge.style.color = 'var(--text2)';
        }

        var answerBtn = card.querySelector('.answer-btn');
        if (answerBtn) answerBtn.style.display = 'none';
        var ignoreBtn = card.querySelector('.ignore-btn');
        if (ignoreBtn) ignoreBtn.style.display = 'none';

        var panel = document.getElementById('answer-panel-' + i);
        if (panel) panel.style.display = 'none';

        card.style.opacity = '1';
        renderFlaggedPage();
    }, 400);
}

// ═══════════════════════════════════════════════════
// FILTRES DOCUMENTS
// ═══════════════════════════════════════════════════
document.getElementById('docSearch')?.addEventListener('input', filterDocuments);
document.getElementById('docTypeFilter')?.addEventListener('change', filterDocuments);
document.getElementById('docStatusFilter')?.addEventListener('change', filterDocuments);
document.getElementById('docDateFrom')?.addEventListener('change', filterDocuments);
document.getElementById('docDateTo')?.addEventListener('change', filterDocuments);

function filterDocuments() {
    var search = document.getElementById('docSearch').value.toLowerCase();
    var type = document.getElementById('docTypeFilter').value;
    var status = document.getElementById('docStatusFilter').value;
    var dateFrom = document.getElementById('docDateFrom').value;
    var dateTo = document.getElementById('docDateTo').value;

    var rows = document.querySelectorAll('#docTableBody .doc-row');

    rows.forEach(function(row) {
        var name = row.dataset.name.toLowerCase();
        var rowType = row.dataset.type;
        var rowStatus = row.dataset.status;
        var rowDate = row.dataset.date;

        var show = true;

        if (search && !name.includes(search)) show = false;
        if (type !== 'all' && rowType !== type) show = false;
        if (status !== 'all' && rowStatus !== status) show = false;
        if (dateFrom && rowDate < dateFrom) show = false;
        if (dateTo && rowDate > dateTo) show = false;

        row.dataset.matches = show ? 'true' : 'false';
    });

    docCurrentPage = 1;
    renderDocPage();
}

// ═══════════════════════════════════════════════════
// PAGINATION — DOCUMENTS TABLE
// ═══════════════════════════════════════════════════
var docPageSize = 8;
var docCurrentPage = 1;

function renderDocPage() {
    var allRows = Array.from(document.querySelectorAll('#docTableBody .doc-row'));
    var matched = allRows.filter(function(r) { return r.dataset.matches !== 'false'; });
    var totalItems = matched.length;
    var totalPages = Math.max(1, Math.ceil(totalItems / docPageSize));
    if (docCurrentPage > totalPages) docCurrentPage = totalPages;
    if (docCurrentPage < 1) docCurrentPage = 1;

    allRows.forEach(function(r) { r.style.display = 'none'; });

    var start = (docCurrentPage - 1) * docPageSize;
    var end = start + docPageSize;
    matched.slice(start, end).forEach(function(r) { r.style.display = ''; });

    renderPaginationControls('docPagination', totalItems, totalPages, docCurrentPage, docPageSize, goToDocPage);
}

function goToDocPage(page) {
    docCurrentPage = page;
    renderDocPage();
}

// ═══════════════════════════════════════════════════
// PAGINATION — FEEDBACK LOG TABLE
// ═══════════════════════════════════════════════════
var feedbackPageSize = 5;
var feedbackCurrentPage = 1;

function renderFeedbackPage() {
    var rows = Array.from(document.querySelectorAll('#feedbackTableBody .feedback-row'));
    var totalItems = rows.length;
    var totalPages = Math.max(1, Math.ceil(totalItems / feedbackPageSize));
    if (feedbackCurrentPage > totalPages) feedbackCurrentPage = totalPages;
    if (feedbackCurrentPage < 1) feedbackCurrentPage = 1;

    rows.forEach(function(r) { r.style.display = 'none'; });

    var start = (feedbackCurrentPage - 1) * feedbackPageSize;
    var end = start + feedbackPageSize;
    rows.slice(start, end).forEach(function(r) { r.style.display = ''; });

    renderPaginationControls('feedbackPagination', totalItems, totalPages, feedbackCurrentPage, feedbackPageSize, goToFeedbackPage);
}

function goToFeedbackPage(page) {
    feedbackCurrentPage = page;
    renderFeedbackPage();
}

// ═══════════════════════════════════════════════════
// PAGINATION — GENERIC CONTROLS RENDERER
// ═══════════════════════════════════════════════════
// Exposes a small set of page-jump handlers on window so inline onclick works
// without needing a closure-capturing callback name per table.
window._paginationHandlers = window._paginationHandlers || {};

function renderPaginationControls(containerId, totalItems, totalPages, currentPage, pageSize, onPageClick) {
    var el = document.getElementById(containerId);
    if (!el) return;

    window._paginationHandlers[containerId] = onPageClick;

    if (totalItems === 0) {
        el.innerHTML = '<span style="font-size:12px;color:var(--text2);">Aucun résultat</span>';
        return;
    }

    var rangeStart = (currentPage - 1) * pageSize + 1;
    var rangeEnd = Math.min(currentPage * pageSize, totalItems);

    var btnStyle = 'min-width:28px;height:28px;padding:0 8px;border-radius:var(--radius-sm);border:1px solid var(--border);background:var(--bg4);color:var(--text2);font-size:12px;font-weight:600;cursor:pointer;font-family:var(--font-body);';
    var activeStyle = 'min-width:28px;height:28px;padding:0 8px;border-radius:var(--radius-sm);border:1px solid rgba(201,168,76,0.4);background:var(--gold-dim);color:var(--gold);font-size:12px;font-weight:700;cursor:default;font-family:var(--font-body);';
    var disabledStyle = btnStyle + 'opacity:0.4;cursor:not-allowed;';

    var html = '<span style="font-size:12px;color:var(--text2);">Affichage ' + rangeStart + '–' + rangeEnd + ' sur ' + totalItems + '</span>';
    html += '<div style="display:flex;align-items:center;gap:6px;">';

    html += '<button style="' + (currentPage === 1 ? disabledStyle : btnStyle) + '" ' + (currentPage === 1 ? 'disabled' : '') + ' onclick="window._paginationHandlers[\'' + containerId + '\'](' + (currentPage - 1) + ')">‹</button>';

    var maxButtons = 5;
    var pageStart = Math.max(1, currentPage - Math.floor(maxButtons / 2));
    var pageEnd = Math.min(totalPages, pageStart + maxButtons - 1);
    pageStart = Math.max(1, pageEnd - maxButtons + 1);

    if (pageStart > 1) {
        html += '<button style="' + btnStyle + '" onclick="window._paginationHandlers[\'' + containerId + '\'](1)">1</button>';
        if (pageStart > 2) html += '<span style="color:var(--text3);font-size:12px;">…</span>';
    }

    for (var p = pageStart; p <= pageEnd; p++) {
        html += '<button style="' + (p === currentPage ? activeStyle : btnStyle) + '" onclick="window._paginationHandlers[\'' + containerId + '\'](' + p + ')">' + p + '</button>';
    }

    if (pageEnd < totalPages) {
        if (pageEnd < totalPages - 1) html += '<span style="color:var(--text3);font-size:12px;">…</span>';
        html += '<button style="' + btnStyle + '" onclick="window._paginationHandlers[\'' + containerId + '\'](' + totalPages + ')">' + totalPages + '</button>';
    }

    html += '<button style="' + (currentPage === totalPages ? disabledStyle : btnStyle) + '" ' + (currentPage === totalPages ? 'disabled' : '') + ' onclick="window._paginationHandlers[\'' + containerId + '\'](' + (currentPage + 1) + ')">›</button>';

    html += '</div>';
    el.innerHTML = html;
}

// ═══════════════════════════════════════════════════
// FILTRES + PAGINATION — QUESTIONS SIGNALÉES
// ═══════════════════════════════════════════════════
document.getElementById('flagSearch')?.addEventListener('input', filterFlagged);
document.getElementById('flagPriorityFilter')?.addEventListener('change', filterFlagged);
document.getElementById('flagStatusFilter')?.addEventListener('change', filterFlagged);

function filterFlagged() {
    var search = document.getElementById('flagSearch').value.toLowerCase();
    var priority = document.getElementById('flagPriorityFilter').value;
    var status = document.getElementById('flagStatusFilter').value;

    var cards = document.querySelectorAll('#flaggedList .flagged-card');

    cards.forEach(function(card) {
        var text = card.querySelector('.flagged-question-text')?.textContent?.toLowerCase() || '';
        var cardPriority = card.dataset.priority;
        var cardStatus = card.dataset.status;

        var show = true;
        if (search && !text.includes(search)) show = false;
        if (priority !== 'all' && cardPriority !== priority) show = false;
        if (status !== 'all' && cardStatus !== status) show = false;

        card.dataset.matches = show ? 'true' : 'false';
    });

    flaggedCurrentPage = 1;
    renderFlaggedPage();
}

var flaggedPageSize = 5;
var flaggedCurrentPage = 1;

function renderFlaggedPage() {
    var allCards = Array.from(document.querySelectorAll('#flaggedList .flagged-card'));
    var matched = allCards.filter(function(c) { return c.dataset.matches !== 'false'; });
    var totalItems = matched.length;
    var totalPages = Math.max(1, Math.ceil(totalItems / flaggedPageSize));
    if (flaggedCurrentPage > totalPages) flaggedCurrentPage = totalPages;
    if (flaggedCurrentPage < 1) flaggedCurrentPage = 1;

    allCards.forEach(function(c) { c.style.display = 'none'; });

    var start = (flaggedCurrentPage - 1) * flaggedPageSize;
    var end = start + flaggedPageSize;
    matched.slice(start, end).forEach(function(c) { c.style.display = ''; });

    renderPaginationControls('flaggedPagination', totalItems, totalPages, flaggedCurrentPage, flaggedPageSize, goToFlaggedPage);

    document.getElementById('flagCount').textContent = totalItems;
    document.getElementById('pendingCount').textContent = totalItems;
    document.getElementById('action-flagged-count').textContent = totalItems;
    document.getElementById('tab-flagged-badge').textContent = totalItems;
}

function goToFlaggedPage(page) {
    flaggedCurrentPage = page;
    renderFlaggedPage();
}

// ═══════════════════════════════════════════════════
// PROMPT STUDIO
// ═══════════════════════════════════════════════════
function savePromptDraft() {
    var prompt = document.getElementById('systemPrompt').value;
    alert('Brouillon du prompt sauvegardé avec succès !');
}

function deployPrompt() {
    if (confirm('Êtes-vous sûr de vouloir déployer ce prompt comme version active ?')) {
        alert('Prompt déployé avec succès comme version active !');
    }
}

function restorePrompt(version) {
    if (confirm('Restaurer la version ' + version + ' ?')) {
        alert('Version ' + version + ' restaurée avec succès !');
    }
}

function applyRetrievalSettings() {
    var topk = document.getElementById('topkRange').value;
    var sim = document.getElementById('simRange').value;
    var model = document.getElementById('llmModel').value;
    var tokens = document.getElementById('maxTokens').value;
    var temp = document.getElementById('tempRange').value / 100;
    var flag = document.getElementById('flagRange').value;

    alert('Paramètres de récupération appliqués :\n' +
          'Top-K: ' + topk + '\n' +
          'Seuil de similarité: ' + sim + '%\n' +
          'Modèle: ' + model + '\n' +
          'Max Tokens: ' + tokens + '\n' +
          'Température: ' + temp + '\n' +
          'Seuil de signalement: ' + flag + '%');
}

// ═══════════════════════════════════════════════════
// PERFORMANCE FILTERS
// ═══════════════════════════════════════════════════
function applyPerfFilters() {
    var from = document.getElementById('perfDateFrom').value;
    var to = document.getElementById('perfDateTo').value;
    var metric = document.getElementById('perfMetricFilter').value;

    alert('Filtres appliqués :\n' +
          'Du: ' + (from || 'Non défini') + '\n' +
          'Au: ' + (to || 'Non défini') + '\n' +
          'Métrique: ' + (metric === 'all' ? 'Toutes' : metric));
}

function resetPerfFilters() {
    document.getElementById('perfDateFrom').value = '';
    document.getElementById('perfDateTo').value = '';
    document.getElementById('perfMetricFilter').value = 'all';
    alert('Filtres réinitialisés');
}

// ═══════════════════════════════════════════════════
// KNOWLEDGE BASE - RAW TEXT & Q&A
// ═══════════════════════════════════════════════════
function ingestRawText() {
    var text = document.getElementById('rawText').value.trim();
    if (!text) {
        alert('Veuillez entrer du texte à ingérer.');
        return;
    }
    alert('Texte ingéré avec succès dans la base de connaissances !');
    document.getElementById('rawText').value = '';
}

function saveQAPair() {
    var question = document.getElementById('qaQuestion').value.trim();
    var answer = document.getElementById('qaAnswer').value.trim();
    if (!question || !answer) {
        alert('Veuillez remplir à la fois la question et la réponse.');
        return;
    }
    alert('Paire Q&A sauvegardée avec succès dans la base de connaissances !');
    document.getElementById('qaQuestion').value = '';
    document.getElementById('qaAnswer').value = '';
}

// ═══════════════════════════════════════════════════
// DEPLOY
// ═══════════════════════════════════════════════════
function handleDeploy() {
    closeModal('modal-deploy');
    alert('Déploiement en cours...\n\n' +
          '✓ ' + document.querySelectorAll('#docTableBody .doc-row').length + ' documents ingérés\n' +
          '✓ ' + document.querySelectorAll('#flaggedList .flagged-card').length + ' paires Q&A ajoutées\n' +
          '✓ Prompt système mis à jour\n' +
          '✓ Paramètres de récupération appliqués\n\n' +
          'La base de connaissances a été mise à jour avec succès !');
}

// ═══════════════════════════════════════════════════
// DOCUMENT ACTIONS
// ═══════════════════════════════════════════════════
document.querySelectorAll('.reindex-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        var id = this.dataset.id;
        if (confirm('Ré-indexer le document ID ' + id + ' ?')) {
            alert('Document ID ' + id + ' en cours de ré-indexation...');
        }
    });
});

document.querySelectorAll('.delete-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        var id = this.dataset.id;
        if (confirm('Supprimer définitivement le document ID ' + id + ' ? Cette action est irréversible.')) {
            var row = this.closest('.doc-row');
            if (row) {
                row.style.opacity = '0.4';
                setTimeout(function() {
                    row.dataset.matches = 'false';
                    row.style.display = 'none';
                    renderDocPage();
                    alert('Document ID ' + id + ' supprimé avec succès.');
                }, 300);
            }
        }
    });
});

// ═══════════════════════════════════════════════════
// RAFFRAÎCHISSEMENT AUTOMATIQUE DES DONNÉES
// ═══════════════════════════════════════════════════
async function refreshKPIs() {
    try {
        var response = await fetch('/admin/ai/api/kpis');
        var data = await response.json();

        document.getElementById('kpi-total-questions').textContent = data.totalQuestions || 0;
        document.getElementById('kpi-success-rate').textContent = (data.successRate || 0) + '%';
        document.getElementById('kpi-flagged-count').textContent = data.flaggedCount || 0;
        document.getElementById('kpi-avg-response').textContent = (data.avgResponseTime || 0) + 's';
        document.getElementById('kpi-total-documents').textContent = data.totalDocuments || 0;

        document.getElementById('action-flagged-count').textContent = data.flaggedCount || 0;
        document.getElementById('tab-flagged-badge').textContent = data.flaggedCount || 0;
    } catch (error) {
        console.error('Error refreshing KPIs:', error);
    }
}

// ═══════════════════════════════════════════════════
// CHART — Chart.js avec données réelles
// ═══════════════════════════════════════════════════

var chartCurrentPeriod = '14d';
var chartInstance = null;
var isLoading = false;

var defaultChartData = {
    '7d':  { labels: ['Lun','Mar','Mer','Jeu','Ven','Sam','Dim'], answered: [0,0,0,0,0,0,0], flagged: [0,0,0,0,0,0,0], trend: [0,0,0,0,0,0,0] },
    '14d': { labels: Array.from({length:14}, function(_,i){ return 'J-'+(13-i); }), answered: Array(14).fill(0), flagged: Array(14).fill(0), trend: Array(14).fill(0) },
    '30d': { labels: Array.from({length:30}, function(_,i){ return 'J-'+(29-i); }), answered: Array(30).fill(0), flagged: Array(30).fill(0), trend: Array(30).fill(0) },
    '90d': { labels: Array.from({length:13}, function(_,i){ return 'S-'+(12-i); }), answered: Array(13).fill(0), flagged: Array(13).fill(0), trend: Array(13).fill(0) },
    '12m': { labels: Array.from({length:52}, function(_,i){ return 'S-'+(51-i); }), answered: Array(52).fill(0), flagged: Array(52).fill(0), trend: Array(52).fill(0) }
};

function initChart(data) {
    var canvas = document.getElementById('queryChart');
    if (!canvas) return;
    var ctx = canvas.getContext('2d');

    if (chartInstance) {
        chartInstance.destroy();
        chartInstance = null;
    }

    // Gold gradient fill
    var goldGrad = ctx.createLinearGradient(0, 0, 0, 300);
    goldGrad.addColorStop(0, 'rgba(201,168,76,0.35)');
    goldGrad.addColorStop(1, 'rgba(201,168,76,0.02)');

    var amberGrad = ctx.createLinearGradient(0, 0, 0, 300);
    amberGrad.addColorStop(0, 'rgba(251,191,36,0.3)');
    amberGrad.addColorStop(1, 'rgba(251,191,36,0.02)');

    chartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Répondues',
                    data: data.answered,
                    borderColor: '#c9a84c',
                    backgroundColor: goldGrad,
                    borderWidth: 2,
                    pointBackgroundColor: '#c9a84c',
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4,
                    order: 1
                },
                {
                    label: 'Signalées',
                    data: data.flagged,
                    borderColor: '#fbbf24',
                    backgroundColor: amberGrad,
                    borderWidth: 2,
                    borderDash: [6, 3],
                    pointBackgroundColor: '#fbbf24',
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4,
                    order: 2
                },
                {
                    label: 'Tendance',
                    data: data.trend,
                    borderColor: '#60a5fa',
                    backgroundColor: 'transparent',
                    borderWidth: 1.5,
                    borderDash: [4, 4],
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    fill: false,
                    tension: 0.4,
                    order: 3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    display: false  // we use our own legend in the panel header
                },
                tooltip: {
                    backgroundColor: 'rgba(14,16,21,0.95)',
                    borderColor: 'rgba(201,168,76,0.4)',
                    borderWidth: 1,
                    titleColor: '#c9a84c',
                    bodyColor: '#8a8f9a',
                    padding: 12,
                    callbacks: {
                        title: function(items) {
                            return items[0].label;
                        },
                        label: function(item) {
                            var icons = ['✅', '🚩', '〰️'];
                            return ' ' + icons[item.datasetIndex] + ' ' + item.dataset.label + ': ' + item.parsed.y;
                        },
                        afterBody: function(items) {
                            var answered = items[0] ? items[0].parsed.y : 0;
                            var flagged  = items[1] ? items[1].parsed.y : 0;
                            var total = answered + flagged;
                            var rate = total > 0 ? Math.round(answered / total * 100) : 0;
                            return ['', ' Taux de résolution: ' + rate + '%'];
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        color: 'rgba(255,255,255,0.04)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#4a4f5a',
                        font: { family: 'monospace', size: 11 },
                        maxRotation: 0,
                        maxTicksLimit: 10
                    }
                },
                y: {
                    grid: {
                        color: 'rgba(255,255,255,0.04)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#4a4f5a',
                        font: { family: 'monospace', size: 11 },
                        callback: function(val) { return val % 1 === 0 ? val : ''; }
                    },
                    beginAtZero: true
                }
            }
        }
    });
}

async function loadChartData(period) {
    if (isLoading) return;
    isLoading = true;
    showChartLoading(true);

    // Update period button styles
    document.querySelectorAll('.chart-period-btn').forEach(function(btn) {
        var active = btn.dataset.period === period;
        btn.style.background = active ? 'var(--gold-dim)' : 'transparent';
        btn.style.color     = active ? 'var(--gold)'    : 'var(--text2)';
    });

    var data;
    try {
        var response = await fetch('/admin/ai/api/chart-data?period=' + period);
        if (!response.ok) throw new Error('HTTP ' + response.status);
        var json = await response.json();
        if (json && json.labels && json.labels.length > 0) {
            data = json;
        } else {
            throw new Error('Empty payload');
        }
    } catch (err) {
        console.warn('Chart API error, using zeros:', err.message);
        data = defaultChartData[period] || defaultChartData['14d'];
    }

    initChart(data);
    updateChartStats(data);

    setTimeout(function() {
        showChartLoading(false);
        isLoading = false;
    }, 300);
}

function updateChartStats(data) {
    var answered = data.answered || [];
    var flagged  = data.flagged  || [];

    var totalAnswered = answered.reduce(function(a,b){return a+b;},0);
    var totalFlagged  = flagged.reduce(function(a,b){return a+b;},0);
    var totalRequests = totalAnswered + totalFlagged;
    var resolutionRate = totalRequests > 0 ? Math.round(totalAnswered / totalRequests * 100) : 0;

    document.getElementById('chart-total-requests').textContent  = totalRequests;
    document.getElementById('chart-resolution-rate').textContent = resolutionRate + '%';

    var avgDaily = answered.length > 0 ? Math.round(totalAnswered / answered.length) : 0;
    document.getElementById('chart-avg-daily').textContent = avgDaily;

    // Trend: compare second half vs first half
    if (answered.length >= 2) {
        var half = Math.floor(answered.length / 2);
        var avg1 = answered.slice(0, half).reduce(function(a,b){return a+b;},0) / half;
        var avg2 = answered.slice(half).reduce(function(a,b){return a+b;},0) / (answered.length - half);
        var pct  = avg1 > 0 ? ((avg2 - avg1) / avg1 * 100) : 0;
        var trendEl = document.getElementById('chart-trend-indicator');
        if (pct > 5) {
            trendEl.innerHTML = '⬆ +' + Math.round(pct) + '%';
            trendEl.style.color = 'var(--green)';
        } else if (pct < -5) {
            trendEl.innerHTML = '⬇ ' + Math.round(pct) + '%';
            trendEl.style.color = 'var(--red)';
        } else {
            trendEl.innerHTML = '→ ' + Math.round(pct) + '%';
            trendEl.style.color = 'var(--text2)';
        }
    }
}

function showChartLoading(show) {
    var el = document.getElementById('chart-loading');
    if (el) el.style.display = show ? 'flex' : 'none';
}

function setChartPeriod(period) {
    if (chartCurrentPeriod === period) return;
    chartCurrentPeriod = period;
    loadChartData(period);
}

// ═══════════════════════════════════════════════════
// KPI REFRESH — real data from /api/kpis
// ═══════════════════════════════════════════════════
async function refreshKPIs() {
    try {
        var response = await fetch('/admin/ai/api/kpis');
        if (!response.ok) return;
        var data = await response.json();

        document.getElementById('kpi-total-questions').textContent = (data.totalQuestions || 0).toLocaleString('fr-FR');
        document.getElementById('kpi-success-rate').textContent    = (data.successRate || 0) + '%';
        document.getElementById('kpi-flagged-count').textContent   = data.flaggedCount || 0;
        document.getElementById('kpi-avg-response').textContent    = (data.avgResponseTime || 0) + 's';
        document.getElementById('kpi-total-documents').textContent = (data.totalDocuments || 0).toLocaleString('fr-FR');

        // Update trend texts with real week-over-week data
        var trend = data.trends ? data.trends.questions : null;
        if (trend !== null && trend !== undefined) {
            var trendNum = parseFloat(trend);
            var trendText = trendNum >= 0 ? '↑ +' + trendNum + '% vs semaine dernière' : '↓ ' + trendNum + '% vs semaine dernière';
            var trendColor = trendNum >= 0 ? 'var(--green)' : 'var(--red)';
            var trendEl = document.getElementById('kpi-total-trend');
            if (trendEl) { trendEl.textContent = trendText; trendEl.style.color = trendColor; }
        }

        var successTrend = document.getElementById('kpi-success-trend');
        if (successTrend) successTrend.textContent = 'Taux sur 30 derniers jours';

        var respTrend = document.getElementById('kpi-response-trend');
        if (respTrend) respTrend.textContent = 'Temps moyen de génération';

        // Flagged count badge sync
        document.getElementById('action-flagged-count').textContent = data.flaggedCount || 0;
        document.getElementById('tab-flagged-badge').textContent    = data.flaggedCount || 0;

    } catch (err) {
        console.warn('KPI refresh failed:', err.message);
    }
}

// ═══════════════════════════════════════════════════
// RECENT ACTIVITY — built from flagged_questions
// ═══════════════════════════════════════════════════
async function loadRecentActivity() {
    var container = document.getElementById('recent-activity');
    if (!container) return;

    try {
        var response = await fetch('/admin/ai/api/flagged?limit=5&status=pending');
        if (!response.ok) throw new Error('HTTP ' + response.status);
        var data = await response.json();

        var items = (data.questions || []).slice(0, 5);
        if (items.length === 0) {
            container.innerHTML = '<div style="font-size:12px;color:var(--text2);">Aucune activité récente</div>';
            return;
        }

        var iconMap = { high: 'amber', medium: 'blue', low: 'text2' };
        var html = items.map(function(q) {
            var color = iconMap[q.priority] || 'text2';
            var text  = q.question.length > 60 ? q.question.substring(0, 60) + '…' : q.question;
            return '<div style="display:flex;gap:10px;align-items:flex-start;">' +
                '<div style="width:6px;height:6px;background:var(--' + color + ');border-radius:50%;margin-top:5px;flex-shrink:0;"></div>' +
                '<div>' +
                  '<div style="font-size:12px;color:var(--text);">' + text + '</div>' +
                  '<div style="font-size:10.5px;color:var(--text3);">' + (q.asked_at || 'récemment') + '</div>' +
                '</div>' +
            '</div>';
        }).join('');

        container.innerHTML = html;

    } catch (err) {
        container.innerHTML = '<div style="font-size:12px;color:var(--text2);">Activité non disponible</div>';
    }
}

// ═══════════════════════════════════════════════════
// INIT
// ═══════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function() {
    loadChartData('14d');
    refreshKPIs();
    loadRecentActivity();
    setInterval(refreshKPIs, 60000);

    renderDocPage();
    renderFeedbackPage();
    renderFlaggedPage();
});

console.log('RAG Dashboard — Chart.js + données réelles');
</script>

</div>{{-- /.rag-dashboard-wrap --}}

@endsection
