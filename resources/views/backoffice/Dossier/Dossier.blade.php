@extends('shared.layouts.backoffice')

@section('content')

        <div class="section-head">
          <div>
            <div class="section-title">Gestion des dossiers</div>
            <div class="section-sub">143 dossiers actifs · Filtrés et triés</div>
          </div>
          <div style="display:flex;gap:8px;">
            <button class="btn btn-outline btn-sm">📥 Export CSV/PDF</button>
            <button class="btn btn-gold btn-sm">+ Nouveau dossier</button>
          </div>
        </div>

        <div class="filter-bar">
          <div class="filter-tab active" onclick="setFilter(this)">Tous <span class="filter-count">143</span></div>
          <div class="filter-tab" onclick="setFilter(this)">En attente <span class="filter-count">34</span></div>
          <div class="filter-tab" onclick="setFilter(this)">En instruction <span class="filter-count">47</span></div>
          <div class="filter-tab" onclick="setFilter(this)">Commission <span class="filter-count">12</span></div>
          <div class="filter-tab" onclick="setFilter(this)">Approuvés <span class="filter-count">38</span></div>
          <div class="filter-tab" onclick="setFilter(this)">Rejetés <span class="filter-count">8</span></div>
          <div class="filter-tab" onclick="setFilter(this)">SLA dépassé <span class="filter-count"
              style="background:var(--red-dim);color:var(--red)">4</span></div>
          <div style="margin-left:auto;display:flex;gap:8px;">
            <select
              style="background:var(--bg3);border:1px solid var(--border);border-radius:6px;padding:6px 10px;font-size:12px;color:var(--text2);font-family:var(--font-body);outline:none;">
              <option>Tous les processus</option>
              <option>Carte professionnelle</option>
              <option>Attestations</option>
              <option>Autorisations</option>
            </select>
          </div>
        </div>

        <div class="panel">
          <div class="panel-body no-pad">
            <div class="table-wrap">
              <table>
                <thead>
                  <tr>
                    <th>Référence</th>
                    <th>Usager</th>
                    <th>Type de demande</th>
                    <th>Étape courante</th>
                    <th>IA Score</th>
                    <th>Statut</th>
                    <th>Déposé le</th>
                    <th>SLA</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr onclick="openSheet()" style="cursor:pointer;">
                    <td><strong>#2026-0847</strong></td>
                    <td>Mohamed Ben Ali</td>
                    <td>Carte professionnelle artistique</td>
                    <td><span class="badge gold">Instruction</span></td>
                    <td><span style="font-family:var(--font-mono);font-size:12px;color:var(--green)">0.96</span></td>
                    <td><span class="badge gold">En cours</span></td>
                    <td style="font-family:var(--font-mono);font-size:12px;">07/04/2026</td>
                    <td><span style="font-family:var(--font-mono);font-size:12px;color:var(--green)">J+5</span></td>
                    <td>
                      <div class="row-actions">
                        <button class="btn btn-ghost btn-sm">👁</button>
                        <button class="btn btn-ghost btn-sm">✏</button>
                        <button class="btn btn-ghost btn-sm">→</button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td><strong>#2026-0846</strong></td>
                    <td>Fatima Zahra Mrad</td>
                    <td>Attestation CNSS</td>
                    <td><span class="badge teal">Bureau d'ordre</span></td>
                    <td><span style="font-family:var(--font-mono);font-size:12px;color:var(--green)">0.94</span></td>
                    <td><span class="badge teal">Nouveau</span></td>
                    <td style="font-family:var(--font-mono);font-size:12px;">07/04/2026</td>
                    <td><span style="font-family:var(--font-mono);font-size:12px;color:var(--green)">J+2</span></td>
                    <td>
                      <div class="row-actions"><button class="btn btn-ghost btn-sm">👁</button><button
                          class="btn btn-ghost btn-sm">→</button></div>
                    </td>
                  </tr>
                  <tr>
                    <td><strong>#2026-0843</strong></td>
                    <td>Société Cinéma Plus</td>
                    <td>Autorisation de tournage</td>
                    <td><span class="badge blue">Commission</span></td>
                    <td><span style="font-family:var(--font-mono);font-size:12px;color:var(--amber)">0.82</span></td>
                    <td><span class="badge blue">En commission</span></td>
                    <td style="font-family:var(--font-mono);font-size:12px;">04/04/2026</td>
                    <td><span style="font-family:var(--font-mono);font-size:12px;color:var(--amber)">J+3</span></td>
                    <td>
                      <div class="row-actions"><button class="btn btn-ghost btn-sm">👁</button><button
                          class="btn btn-ghost btn-sm">→</button></div>
                    </td>
                  </tr>
                  <tr>
                    <td><strong>#2026-0831</strong></td>
                    <td>Amira Khlifi</td>
                    <td>Diplôme de musique arabe</td>
                    <td><span class="badge red">Rejeté</span></td>
                    <td><span style="font-family:var(--font-mono);font-size:12px;color:var(--green)">0.91</span></td>
                    <td><span class="badge red">Rejeté</span></td>
                    <td style="font-family:var(--font-mono);font-size:12px;">01/04/2026</td>
                    <td style="font-family:var(--font-mono);font-size:12px;color:var(--red)">Dépassé</td>
                    <td>
                      <div class="row-actions"><button class="btn btn-ghost btn-sm">👁</button><button
                          class="btn btn-red btn-sm">↩ Retour</button></div>
                    </td>
                  </tr>
                  <tr>
                    <td><strong>#2026-0829</strong></td>
                    <td>Editions Kartage</td>
                    <td>Livre & Édition — Soutien</td>
                    <td><span class="badge purple">Directeur</span></td>
                    <td><span style="font-family:var(--font-mono);font-size:12px;color:var(--green)">0.93</span></td>
                    <td><span class="badge purple">Validation finale</span></td>
                    <td style="font-family:var(--font-mono);font-size:12px;">30/03/2026</td>
                    <td><span style="font-family:var(--font-mono);font-size:12px;color:var(--red)">J-1</span></td>
                    <td>
                      <div class="row-actions"><button class="btn btn-ghost btn-sm">👁</button><button
                          class="btn btn-gold btn-sm">✓ Valider</button></div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
@endsection

