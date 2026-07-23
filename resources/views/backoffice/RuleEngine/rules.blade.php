@extends('shared.layouts.backoffice')

@section('content')
        <div class="section-head">
          <div>
            <div class="section-title">Moteur de règles métier</div>
            <div class="section-sub">Drools (Red Hat KIE) · Sans redéploiement</div>
          </div>
          <button class="btn btn-gold btn-sm">+ Nouvelle règle</button>
        </div>
        <div class="panel">
          <div class="panel-body no-pad">
            <table>
              <thead>
                <tr>
                  <th>Règle</th>
                  <th>Condition</th>
                  <th>Action</th>
                  <th>Processus</th>
                  <th>Statut</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><strong>SLA-ALERT-5J</strong></td>
                  <td><span style="font-family:var(--font-mono);font-size:11px;">dossier.age &gt; 5 jours AND statut =
                      EN_ATTENTE</span></td>
                  <td>Notifier agent + escalader directeur</td>
                  <td><span class="badge gray">Tous</span></td>
                  <td><span class="badge green">Actif</span></td>
                  <td>
                    <div class="row-actions"><button class="btn btn-ghost btn-sm">✏</button><button
                        class="btn btn-ghost btn-sm">⊘</button></div>
                  </td>
                </tr>
                <tr>
                  <td><strong>CARTE-PRO-RENEW</strong></td>
                  <td><span style="font-family:var(--font-mono);font-size:11px;">user.carte_pro.expiry &lt; 30
                      jours</span></td>
                  <td>Email de rappel renouvellement</td>
                  <td><span class="badge gold">Carte pro</span></td>
                  <td><span class="badge green">Actif</span></td>
                  <td>
                    <div class="row-actions"><button class="btn btn-ghost btn-sm">✏</button></div>
                  </td>
                </tr>
                <tr>
                  <td><strong>DOCS-INCOMPLETS</strong></td>
                  <td><span style="font-family:var(--font-mono);font-size:11px;">soumission.docs.required
                      NOT_ALL_PRESENT</span></td>
                  <td>Bloquer soumission + message erreur</td>
                  <td><span class="badge gray">Tous</span></td>
                  <td><span class="badge green">Actif</span></td>
                  <td>
                    <div class="row-actions"><button class="btn btn-ghost btn-sm">✏</button></div>
                  </td>
                </tr>
                <tr>
                  <td><strong>INVESTISSEUR-MINISTER</strong></td>
                  <td><span style="font-family:var(--font-mono);font-size:11px;">investissement.montant &gt; 500000
                      TND</span></td>
                  <td>Route vers validation Ministre</td>
                  <td><span class="badge purple">Investisseur</span></td>
                  <td><span class="badge green">Actif</span></td>
                  <td>
                    <div class="row-actions"><button class="btn btn-ghost btn-sm">✏</button></div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

@endsection
