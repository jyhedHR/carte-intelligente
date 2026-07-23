@extends('shared.layouts.backoffice')

@section('title', 'Tableau de bord')

@section('content')
<div class="main-content">
  <div class="page-header">
    <div class="header-left">
      <h1>Tableau de bord</h1>
      <p class="subtitle">Vue d'ensemble de l'activité administrative</p>
    </div>
    <div class="header-right">
      <button class="btn btn-primary">
        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
          <polyline points="17 21 17 13 7 13 7 21"></polyline>
        </svg>
        Exporter
      </button>
    </div>
  </div>

  <!-- KPI Cards -->
  <div class="kpi-grid">
    <div class="kpi-card">
      <div class="kpi-header">
        <span class="kpi-label">Demandes reçues</span>
        <span class="badge badge-info">1247</span>
      </div>
      <div class="kpi-value">1,247</div>
      <div class="kpi-footer">
        <span class="kpi-change positive">+12% ce mois</span>
      </div>
    </div>

    <div class="kpi-card">
      <div class="kpi-header">
        <span class="kpi-label">En cours</span>
        <span class="badge badge-warning">342</span>
      </div>
      <div class="kpi-value">342</div>
      <div class="kpi-footer">
        <span class="kpi-change">Traitement actif</span>
      </div>
    </div>

    <div class="kpi-card">
      <div class="kpi-header">
        <span class="kpi-label">Validées</span>
        <span class="badge badge-success">721</span>
      </div>
      <div class="kpi-value">721</div>
      <div class="kpi-footer">
        <span class="kpi-change positive">Taux: 87%</span>
      </div>
    </div>

    <div class="kpi-card">
      <div class="kpi-header">
        <span class="kpi-label">Refusées</span>
        <span class="badge badge-danger">184</span>
      </div>
      <div class="kpi-value">184</div>
      <div class="kpi-footer">
        <span class="kpi-change negative">15% refus</span>
      </div>
    </div>
  </div>

  <!-- Charts Section -->
  <div class="charts-grid">
    <!-- Monthly Histogram -->
    <div class="panel chart-panel">
      <div class="panel-header">
        <h3>Demandes par mois</h3>
        <select class="select select-sm">
          <option value="6m">6 derniers mois</option>
          <option value="12m">12 derniers mois</option>
          <option value="ytd">Année en cours</option>
        </select>
      </div>
      <div class="chart-container">
        <div style="display: flex; align-items: flex-end; justify-content: space-around; height: 250px; padding: 20px 0;">
          <div style="display: flex; flex-direction: column; align-items: center;">
            <div style="width: 30px; height: 120px; background: var(--gold); border-radius: 4px 4px 0 0;"></div>
            <span style="margin-top: 8px; font-size: 12px; color: var(--text2);">Jan</span>
          </div>
          <div style="display: flex; flex-direction: column; align-items: center;">
            <div style="width: 30px; height: 145px; background: var(--gold); border-radius: 4px 4px 0 0;"></div>
            <span style="margin-top: 8px; font-size: 12px; color: var(--text2);">Fév</span>
          </div>
          <div style="display: flex; flex-direction: column; align-items: center;">
            <div style="width: 30px; height: 160px; background: var(--gold); border-radius: 4px 4px 0 0;"></div>
            <span style="margin-top: 8px; font-size: 12px; color: var(--text2);">Mar</span>
          </div>
          <div style="display: flex; flex-direction: column; align-items: center;">
            <div style="width: 30px; height: 195px; background: var(--gold); border-radius: 4px 4px 0 0;"></div>
            <span style="margin-top: 8px; font-size: 12px; color: var(--text2);">Avr</span>
          </div>
          <div style="display: flex; flex-direction: column; align-items: center;">
            <div style="width: 30px; height: 172px; background: var(--blue); border-radius: 4px 4px 0 0;"></div>
            <span style="margin-top: 8px; font-size: 12px; color: var(--text2);">Mai</span>
          </div>
          <div style="display: flex; flex-direction: column; align-items: center;">
            <div style="width: 30px; height: 148px; background: var(--blue); border-radius: 4px 4px 0 0;"></div>
            <span style="margin-top: 8px; font-size: 12px; color: var(--text2);">Jun</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Type Distribution -->
    <div class="panel chart-panel">
      <div class="panel-header">
        <h3>Demandes par type</h3>
      </div>
      <div class="chart-container" style="display: flex; align-items: center; justify-content: center;">
        <div style="width: 200px; height: 200px; border-radius: 50%; background: conic-gradient(var(--gold) 0deg 101.3deg, var(--blue) 101.3deg 201.6deg, var(--green) 201.6deg 353.6deg, var(--purple) 353.6deg 360deg); margin-right: 40px;"></div>
        <div>
          <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
            <span style="width: 12px; height: 12px; background: var(--gold); border-radius: 2px;"></span>
            <span style="color: var(--text2); font-size: 13px;">Attestations: 350 (28%)</span>
          </div>
          <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
            <span style="width: 12px; height: 12px; background: var(--blue); border-radius: 2px;"></span>
            <span style="color: var(--text2); font-size: 13px;">Cartes Pro: 280 (22%)</span>
          </div>
          <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
            <span style="width: 12px; height: 12px; background: var(--green); border-radius: 2px;"></span>
            <span style="color: var(--text2); font-size: 13px;">Diplômes: 420 (34%)</span>
          </div>
          <div style="display: flex; align-items: center; gap: 8px;">
            <span style="width: 12px; height: 12px; background: var(--purple); border-radius: 2px;"></span>
            <span style="color: var(--text2); font-size: 13px;">Certificats: 197 (16%)</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Alerts Section -->
  <div class="alert alert-warning">
    <div class="alert-icon">⚠️</div>
    <div class="alert-content">
      <h4>3 dossier(s) en retard</h4>
      <p>3 demandes ont dépassé leur délai de traitement prévu.</p>
    </div>
    <a class="alert-action">Voir les dossiers</a>
  </div>

  <!-- Quick Actions -->
  <div class="quick-actions">
    <h3>Accès rapides</h3>
    <div class="actions-grid">
      <a class="action-btn">
        <span class="action-icon">+</span>
        <span class="action-text">Créer une demande</span>
      </a>
      <a class="action-btn">
        <span class="action-icon">🔴</span>
        <span class="action-text">Demandes urgentes</span>
      </a>
      <a class="action-btn">
        <span class="action-icon">📊</span>
        <span class="action-text">Générer un rapport</span>
      </a>
      <a class="action-btn">
        <span class="action-icon">⚙️</span>
        <span class="action-text">Configurer workflows</span>
      </a>
    </div>
  </div>

  <!-- Recent Activity -->
  <div class="panel">
    <div class="panel-header">
      <h3>Activité récente</h3>
      <a class="link-secondary">Voir tout</a>
    </div>
    <div class="activity-list">
      <div class="activity-item">
        <div class="activity-time">15 min</div>
        <div class="activity-content">
          <p class="activity-text"><strong>Marie Dupont</strong> a approuvé une demande</p>
          <span class="activity-user">Demande #12847 - Attestation musicale</span>
        </div>
        <div class="activity-badge" style="background-color: var(--green-dim); color: var(--green);">
          Approuvé
        </div>
      </div>
      <div class="activity-item">
        <div class="activity-time">1h</div>
        <div class="activity-content">
          <p class="activity-text"><strong>Jean Martin</strong> a modifié un formulaire</p>
          <span class="activity-user">Formulaire "Demande de Bourse"</span>
        </div>
        <div class="activity-badge" style="background-color: var(--amber-dim); color: var(--amber);">
          Modification
        </div>
      </div>
      <div class="activity-item">
        <div class="activity-time">3h</div>
        <div class="activity-content">
          <p class="activity-text">Nouveau flux de travail créé</p>
          <span class="activity-user">"Validation Photographie d'Œuvre"</span>
        </div>
        <div class="activity-badge" style="background-color: var(--blue-dim); color: var(--blue);">
          Workflow
        </div>
      </div>
      <div class="activity-item">
        <div class="activity-time">Hier</div>
        <div class="activity-content">
          <p class="activity-text">Base de données synchronisée</p>
          <span class="activity-user">Fonds national des arts plastiques</span>
        </div>
        <div class="activity-badge" style="background-color: var(--purple-dim); color: var(--purple);">
          Sync
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .main-content { padding: 24px; }
  .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
  .header-left h1 { font-size: 28px; font-weight: 700; color: var(--text); margin-bottom: 4px; }
  .subtitle { color: var(--text2); font-size: 14px; }
  .header-right { display: flex; gap: 12px; }

  .kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 32px;
  }

  .kpi-card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px;
    transition: all 0.3s ease;
  }

  .kpi-card:hover {
    border-color: var(--gold);
    box-shadow: 0 0 20px rgba(201, 168, 76, 0.1);
  }

  .kpi-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
  }

  .kpi-label { color: var(--text2); font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
  .kpi-value { font-size: 32px; font-weight: 700; color: var(--gold); margin: 8px 0; }
  .kpi-footer { color: var(--text3); font-size: 12px; }
  .kpi-change { display: inline-block; padding: 2px 8px; border-radius: 4px; }
  .kpi-change.positive { color: var(--green); background: var(--green-dim); }
  .kpi-change.negative { color: var(--red); background: var(--red-dim); }

  .badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
  }

  .badge-info { background: var(--blue-dim); color: var(--blue); }
  .badge-warning { background: var(--amber-dim); color: var(--amber); }
  .badge-success { background: var(--green-dim); color: var(--green); }
  .badge-danger { background: var(--red-dim); color: var(--red); }

  .charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 16px;
    margin-bottom: 32px;
  }

  .panel {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px;
  }

  .panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--border);
  }

  .panel-header h3 {
    font-size: 16px;
    font-weight: 600;
    color: var(--text);
  }

  .chart-container {
    height: 300px;
    position: relative;
  }

  .alert {
    background: var(--amber-dim);
    border: 1px solid var(--amber);
    border-radius: var(--radius);
    padding: 16px;
    margin-bottom: 24px;
    display: flex;
    gap: 16px;
    align-items: center;
  }

  .alert-icon { font-size: 24px; }
  .alert-content { flex: 1; }
  .alert-content h4 { color: var(--amber); font-weight: 600; margin-bottom: 4px; }
  .alert-content p { color: var(--text2); font-size: 14px; }
  .alert-action {
    padding: 8px 16px;
    background: var(--amber);
    color: var(--bg);
    border-radius: var(--radius-sm);
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .alert-action:hover {
    opacity: 0.9;
  }

  .quick-actions {
    margin-bottom: 32px;
  }

  .quick-actions h3 {
    font-size: 16px;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 12px;
  }

  .actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 12px;
  }

  .action-btn {
    background: var(--bg3);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 16px;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    cursor: pointer;
  }

  .action-btn:hover {
    background: var(--bg2);
    border-color: var(--gold);
  }

  .action-icon {
    font-size: 24px;
  }

  .action-text {
    color: var(--text2);
    font-size: 13px;
    text-align: center;
  }

  .activity-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  .activity-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 12px;
    background: var(--bg3);
    border-radius: var(--radius-sm);
  }

  .activity-time {
    color: var(--text3);
    font-size: 12px;
    min-width: 60px;
  }

  .activity-content {
    flex: 1;
  }

  .activity-text {
    color: var(--text);
    font-size: 14px;
    margin-bottom: 2px;
  }

  .activity-user {
    color: var(--text3);
    font-size: 12px;
  }

  .activity-badge {
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
  }

  .empty-state {
    text-align: center;
    padding: 40px 20px;
    color: var(--text3);
  }

  .btn {
    padding: 10px 16px;
    border-radius: var(--radius-sm);
    border: none;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
  }

  .btn-primary {
    background: var(--gold);
    color: var(--bg);
  }

  .btn-primary:hover {
    background: var(--gold2);
  }

  .icon {
    width: 18px;
    height: 18px;
  }

  .select {
    background: var(--bg3);
    color: var(--text);
    border: 1px solid var(--border);
    padding: 6px 10px;
    border-radius: var(--radius-sm);
    font-size: 12px;
    cursor: pointer;
  }

  .select-sm {
    padding: 4px 8px;
    font-size: 11px;
  }

  .link-secondary {
    color: var(--gold);
    text-decoration: none;
    font-size: 13px;
    transition: all 0.2s ease;
    cursor: pointer;
  }

  .link-secondary:hover {
    color: var(--gold2);
  }
</style>
@endsection
