
@extends('shared.layouts.backoffice')

@section('content')
        <div class="section-head">
          <div>
            <div class="section-title">Workflows BPMN</div>
            <div class="section-sub">9 processus modélisés · Camunda BPMN 2.0</div>
          </div>
          <div style="display:flex;gap:8px;">
            <button class="btn btn-outline btn-sm">📥 Importer BPMN</button>
            <button class="btn btn-gold btn-sm">+ Nouveau workflow</button>
          </div>
        </div>

        <div class="workflow-grid">
          <div class="wf-card">
            <div class="wf-header"><span class="wf-icon">📜</span><span class="badge green">Actif</span></div>
            <div class="wf-name">Attestations & Certificats</div>
            <div class="wf-key" style="font-family:var(--font-mono);">wf-attestation-artistique · v1.2</div>
            <div class="wf-stats">
              <div class="wf-stat">
                <div class="wf-stat-val">41</div>
                <div class="wf-stat-lbl">Instances</div>
              </div>
              <div class="wf-stat">
                <div class="wf-stat-val" style="color:var(--green)">38h</div>
                <div class="wf-stat-lbl">Moy. traitement</div>
              </div>
              <div class="wf-stat">
                <div class="wf-stat-val" style="color:var(--gold)">3</div>
                <div class="wf-stat-lbl">Étapes</div>
              </div>
            </div>
          </div>
          <div class="wf-card">
            <div class="wf-header"><span class="wf-icon">🎭</span><span class="badge green">Actif</span></div>
            <div class="wf-name">Carte Professionnelle Artistique</div>
            <div class="wf-key" style="font-family:var(--font-mono);">wf-carte-professionnelle · v2.1</div>
            <div class="wf-stats">
              <div class="wf-stat">
                <div class="wf-stat-val">34</div>
                <div class="wf-stat-lbl">Instances</div>
              </div>
              <div class="wf-stat">
                <div class="wf-stat-val" style="color:var(--amber)">72h</div>
                <div class="wf-stat-lbl">Moy. traitement</div>
              </div>
              <div class="wf-stat">
                <div class="wf-stat-val" style="color:var(--gold)">5</div>
                <div class="wf-stat-lbl">Étapes</div>
              </div>
            </div>
          </div>
          <div class="wf-card">
            <div class="wf-header"><span class="wf-icon">🎬</span><span class="badge amber">Brouillon</span></div>
            <div class="wf-name">Autorisation de Tournage</div>
            <div class="wf-key" style="font-family:var(--font-mono);">wf-autorisation-tournage · v1.0</div>
            <div class="wf-stats">
              <div class="wf-stat">
                <div class="wf-stat-val">22</div>
                <div class="wf-stat-lbl">Instances</div>
              </div>
              <div class="wf-stat">
                <div class="wf-stat-val" style="color:var(--red)">120h</div>
                <div class="wf-stat-lbl">Moy. traitement</div>
              </div>
              <div class="wf-stat">
                <div class="wf-stat-val" style="color:var(--gold)">6</div>
                <div class="wf-stat-lbl">Étapes</div>
              </div>
            </div>
          </div>
          <div class="wf-card">
            <div class="wf-header"><span class="wf-icon">📚</span><span class="badge green">Actif</span></div>
            <div class="wf-name">Livre & Édition</div>
            <div class="wf-key" style="font-family:var(--font-mono);">wf-livre-edition · v1.1</div>
            <div class="wf-stats">
              <div class="wf-stat">
                <div class="wf-stat-val">15</div>
                <div class="wf-stat-lbl">Instances</div>
              </div>
              <div class="wf-stat">
                <div class="wf-stat-val" style="color:var(--green)">48h</div>
                <div class="wf-stat-lbl">Moy. traitement</div>
              </div>
              <div class="wf-stat">
                <div class="wf-stat-val" style="color:var(--gold)">4</div>
                <div class="wf-stat-lbl">Étapes</div>
              </div>
            </div>
          </div>
          <div class="wf-card">
            <div class="wf-header"><span class="wf-icon">🏛</span><span class="badge green">Actif</span></div>
            <div class="wf-name">Investisseurs Culturels</div>
            <div class="wf-key" style="font-family:var(--font-mono);">wf-investisseur-culturel · v1.0</div>
            <div class="wf-stats">
              <div class="wf-stat">
                <div class="wf-stat-val">9</div>
                <div class="wf-stat-lbl">Instances</div>
              </div>
              <div class="wf-stat">
                <div class="wf-stat-val" style="color:var(--red)">720h</div>
                <div class="wf-stat-lbl">Moy. traitement</div>
              </div>
              <div class="wf-stat">
                <div class="wf-stat-val" style="color:var(--gold)">7</div>
                <div class="wf-stat-lbl">Étapes</div>
              </div>
            </div>
          </div>
          <div class="wf-card">
            <div class="wf-header"><span class="wf-icon">🎵</span><span class="badge amber">Brouillon</span></div>
            <div class="wf-name">Diplômes de Musique</div>
            <div class="wf-key" style="font-family:var(--font-mono);">wf-diplome-musique · v2.0</div>
            <div class="wf-stats">
              <div class="wf-stat">
                <div class="wf-stat-val">0</div>
                <div class="wf-stat-lbl">Instances</div>
              </div>
              <div class="wf-stat">
                <div class="wf-stat-val" style="color:var(--text3)">—</div>
                <div class="wf-stat-lbl">Moy. traitement</div>
              </div>
              <div class="wf-stat">
                <div class="wf-stat-val" style="color:var(--gold)">5</div>
                <div class="wf-stat-lbl">Étapes</div>
              </div>
            </div>
          </div>
        </div>

@endsection
