@extends('shared.layouts.backoffice')

@section('title', 'Formulaires & Templates')

@section('content')
<div class="main-content">
  <div class="page-header">
    <div class="header-left">
      <h1>Formulaires & Templates</h1>
      <p class="subtitle">Gérez et configurez les formulaires de demande</p>
    </div>
    <div class="header-right">
      <button class="btn btn-primary">+ Nouveau Formulaire</button>
    </div>
  </div>

  <!-- Tabs -->
  <div style="display: flex; gap: 20px; margin-bottom: 24px; border-bottom: 1px solid var(--border); padding-bottom: 0;">
    <button style="padding: 12px 0; border: none; background: none; color: var(--gold); font-size: 14px; font-weight: 600; border-bottom: 2px solid var(--gold); cursor: pointer;">Formulaires Actifs</button>
    <button style="padding: 12px 0; border: none; background: none; color: var(--text2); font-size: 14px; font-weight: 600; cursor: pointer;">Brouillons</button>
    <button style="padding: 12px 0; border: none; background: none; color: var(--text2); font-size: 14px; font-weight: 600; cursor: pointer;">Archivés</button>
  </div>

  <!-- Forms Grid -->
  <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">
    @php
      $forms = [
        ['Demande Attestation Musicale', 'v1.0', 'green', 'Créé le 05/01/2026', '14 champs', 234],
        ['Carte Professionnelle', 'v2.1', 'green', 'Créé le 28/02/2026', '8 champs', 156],
        ['Diplôme d\'Arts', 'v1.5', 'green', 'Créé le 10/03/2026', '12 champs', 389],
        ['Certificat de Participation', 'v1.0', 'green', 'Créé le 20/03/2026', '6 champs', 67],
        ['Autorisation Artiste Étranger', 'v2.0', 'green', 'Créé le 01/02/2026', '9 champs', 145],
        ['Prêt d\'Œuvre', 'v1.3', 'green', 'Créé le 15/03/2026', '7 champs', 98],
      ];
    @endphp

    @foreach($forms as $form)
      <div class="panel">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
          <div>
            <h3 style="font-size: 16px; font-weight: 600; color: var(--text); margin-bottom: 4px;">{{ $form[0] }}</h3>
            <p style="color: var(--text3); font-size: 11px;">{{ $form[1] }}</p>
          </div>
          <button style="background: none; border: none; color: var(--text2); cursor: pointer; font-size: 18px;">⋮</button>
        </div>

        <div style="space-y: 8px; margin-bottom: 16px;">
          <div style="display: flex; gap: 8px; margin-bottom: 8px;">
            <span style="background: var(--green-dim); color: var(--green); padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase;">ACTIF</span>
            <span style="color: var(--text3); font-size: 12px;">{{ $form[3] }}</span>
          </div>
          <p style="color: var(--text2); font-size: 13px;">{{ $form[4] }}</p>
          <p style="color: var(--text2); font-size: 13px;">{{ $form[5] }} utilisations</p>
        </div>

        <div style="background: var(--bg3); border-radius: 4px; padding: 12px; margin-bottom: 16px;">
          <p style="color: var(--text3); font-size: 11px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase;">Champs</p>
          <div style="space-y: 4px;">
            <p style="color: var(--text3); font-size: 12px;">• Nom complet (texte)</p>
            <p style="color: var(--text3); font-size: 12px;">• Email (email)</p>
            <p style="color: var(--text3); font-size: 12px;">• Date de demande (date)</p>
            <p style="color: var(--text3); font-size: 11px; margin-top: 4px;">... +{{ $form[5] - 3 }} champs supplémentaires</p>
          </div>
        </div>

        <div style="display: flex; gap: 8px;">
          <button class="btn" style="flex: 1; background: var(--gold); color: var(--bg);">Éditer</button>
          <button class="btn" style="flex: 1; background: var(--blue); color: white;">Aperçu</button>
          <button class="btn" style="flex: 1; background: var(--bg3); color: var(--text); border: 1px solid var(--border);">Dupliquer</button>
        </div>
      </div>
    @endforeach
  </div>

  <!-- Form Editor Preview -->
  <div class="panel" style="margin-top: 32px;">
    <h2 style="font-size: 20px; font-weight: 600; color: var(--text); margin-bottom: 24px;">Aperçu de l'Éditeur</h2>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
      <!-- Field List -->
      <div>
        <h3 style="font-size: 14px; font-weight: 600; color: var(--text); margin-bottom: 16px;">Champs du Formulaire</h3>
        <div style="space-y: 8px; background: var(--bg3); border-radius: 4px; padding: 12px;">
          @php
            $fields = [
              ['Nom Complet', 'Texte', true],
              ['Adresse Email', 'Email', true],
              ['Numéro de Téléphone', 'Téléphone', false],
              ['Date de Naissance', 'Date', true],
              ['Domaine Artistique', 'Sélection', true],
            ];
          @endphp
          @foreach($fields as $field)
            <div style="display: flex; justify-content: space-between; align-items: center; background: var(--bg2); padding: 12px; border-radius: 4px; margin-bottom: 8px;">
              <div style="display: flex; align-items: center; gap: 8px;">
                <span style="color: var(--text2);">⋮⋮</span>
                <div>
                  <p style="color: var(--text); font-weight: 600; font-size: 13px;">{{ $field[0] }}</p>
                  <p style="color: var(--text3); font-size: 11px;">{{ $field[1] }} - {{ $field[2] ? 'Obligatoire' : 'Optionnel' }}</p>
                </div>
              </div>
              <div style="display: flex; gap: 6px;">
                <button style="background: none; border: none; color: var(--blue); cursor: pointer;">✏️</button>
                <button style="background: none; border: none; color: var(--red); cursor: pointer;">🗑️</button>
              </div>
            </div>
          @endforeach
        </div>
        <button class="btn" style="width: 100%; margin-top: 12px; background: var(--bg3); color: var(--text); border: 1px solid var(--border);">+ Ajouter Champ</button>
      </div>

      <!-- Form Preview -->
      <div>
        <label style="display: block; font-size: 14px; font-weight: 600; color: var(--text); margin-bottom: 16px;">Aperçu du Formulaire</label>
        <div style="background: var(--bg3); border-radius: 4px; padding: 24px; space-y: 16px;">
          <div style="margin-bottom: 16px;">
            <label style="display: block; color: var(--text); font-weight: 600; font-size: 13px; margin-bottom: 6px;">Nom Complet *</label>
            <input type="text" placeholder="Entrez votre nom complet" style="width: 100%; background: var(--bg2); border: 1px solid var(--border); color: var(--text); padding: 8px 12px; border-radius: 4px; font-size: 13px;" />
          </div>
          <div style="margin-bottom: 16px;">
            <label style="display: block; color: var(--text); font-weight: 600; font-size: 13px; margin-bottom: 6px;">Adresse Email *</label>
            <input type="email" placeholder="votre.email@example.com" style="width: 100%; background: var(--bg2); border: 1px solid var(--border); color: var(--text); padding: 8px 12px; border-radius: 4px; font-size: 13px;" />
          </div>
          <div style="margin-bottom: 16px;">
            <label style="display: block; color: var(--text); font-weight: 600; font-size: 13px; margin-bottom: 6px;">Date de Naissance *</label>
            <input type="date" style="width: 100%; background: var(--bg2); border: 1px solid var(--border); color: var(--text); padding: 8px 12px; border-radius: 4px; font-size: 13px;" />
          </div>
          <div style="margin-bottom: 16px;">
            <label style="display: block; color: var(--text); font-weight: 600; font-size: 13px; margin-bottom: 6px;">Domaine Artistique *</label>
            <select style="width: 100%; background: var(--bg2); border: 1px solid var(--border); color: var(--text); padding: 8px 12px; border-radius: 4px; font-size: 13px;">
              <option>Sélectionner...</option>
              <option>Musique</option>
              <option>Danse</option>
              <option>Arts Plastiques</option>
            </select>
          </div>
          <div style="display: flex; gap: 8px; margin-top: 24px;">
            <button class="btn" style="flex: 1; background: var(--bg2); color: var(--text); border: 1px solid var(--border);">Annuler</button>
            <button class="btn" style="flex: 1; background: var(--gold); color: var(--bg);">Soumettre</button>
          </div>
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
  .btn { padding: 10px 16px; border-radius: var(--radius-sm); border: none; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; }
  .btn-primary { background: var(--gold); color: var(--bg); }
  .btn-primary:hover { opacity: 0.9; }
  .panel { background: var(--bg2); border: 1px solid var(--border); border-radius: var(--radius); padding: 20px; }
</style>
@endsection
