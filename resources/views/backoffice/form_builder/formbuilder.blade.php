@extends('shared.layouts.backoffice')

@section('title', 'Form Builder — Générateur de Formulaires')
@section('breadcrumb', 'Form Builder')

@section('content')


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/formiojs@4.21.6/dist/formio.full.min.css">
    <input type="hidden" id="fbbFormId" value="{{ $formulaire->id ?? '' }}">
    {{-- ══ TOP TOOLBAR ══ --}}
    <div class="fbb-topbar">
        <div class="fbb-topbar-left">
            <div class="fbb-brand">
                <span class="fbb-brand-icon">⊞</span>
                <div>
                    <div class="fbb-brand-title">Form Builder</div>
                    <div class="fbb-brand-sub">Form.io · Drag & Drop natif</div>
                </div>
            </div>
            <div class="fbb-sep"></div>
            <input class="fbb-form-name" id="fbbFormName" value="Attestation d'exercice artistique"
                placeholder="Nom du formulaire…" title="Cliquez pour renommer">

<!-- DEPARTMENT SELECTOR -->
<select class="fbb-select" id="fbbDepartment">
    <option value="">-- Sélectionner un département --</option>
    @foreach($departments as $dept)
        <option value="{{ $dept->id }}"
                {{ (isset($formulaire) && $formulaire->department_id == $dept->id) ? 'selected' : '' }}>
            {{ $dept->name_fr ?? $dept->name }}
        </option>
    @endforeach
</select>
    <!-- END DEPARTMENT SELECTOR -->
            <select class="fbb-select" id="fbbWorkflow">
                <option value="">⏳ Chargement des workflows…</option>
            </select>
            <span class="fbb-badge fbb-badge-teal">FR / AR</span>
            <span class="fbb-badge fbb-badge-blue" id="fbbStatus">Brouillon</span>
            <span class="fbb-badge" id="fbbAutoSaveBadge" style="background:var(--bg3);color:var(--text3);display:none;">💾
                Sauvegardé</span>
        </div>
        <div class="fbb-topbar-right">
            <div class="fbb-stat"><span id="fbbFieldCount">0</span><small>champs</small></div>
            <div class="fbb-sep"></div>
            <button class="fbb-btn fbb-btn-ghost" onclick="fbbUndo()" title="Annuler (Ctrl+Z)">↩</button>
            <button class="fbb-btn fbb-btn-ghost" onclick="fbbRedo()" title="Rétablir">↪</button>
            <!-- NEW BUTTON -->
    <a class="fbb-btn fbb-btn-ghost"
       href="{{ route('admin.formbuilder.manage') }}"
       title="Voir tous les formulaires — page dédiée">
        📂 Gérer
    </a>

            <button class="fbb-btn fbb-btn-ghost" onclick="fbbShowForms()">📋 Formulaires</button>
            <button class="fbb-btn fbb-btn-ghost" onclick="fbbPreview()">👁 Aperçu</button>
            <button class="fbb-btn fbb-btn-ghost" onclick="fbbToggleJson()">{ } JSON</button>
            <button class="fbb-btn fbb-btn-ia" onclick="fbbOpenIa()">🤖 IA</button>
            <button class="fbb-btn fbb-btn-pub" onclick="fbbPublish()">🚀 Publier</button>
        </div>
    </div>

    {{-- ══ IA BANNER ══ --}}
    <div class="fbb-ia-banner" id="fbbIaBanner">
        <span class="fbb-ia-dot"></span>
        <span class="fbb-ia-txt" id="fbbIaTxt">
            🤖 <strong>IA :</strong> Ajoutez un champ <strong>IBAN</strong> — présent dans 84 % des formulaires similaires.
        </span>
        <button class="fbb-btn fbb-btn-ghost fbb-btn-xs" onclick="fbbIaAddField('textfield','IBAN bancaire','iban')">+
            Ajouter</button>
        <button class="fbb-btn fbb-btn-ghost fbb-btn-xs" onclick="fbbOpenIa()">Voir tout</button>
        <button class="fbb-ia-close" onclick="document.getElementById('fbbIaBanner').style.display='none'">✕</button>
    </div>

    {{-- ══ PROGRESS BAR ══ --}}
    <div class="fbb-progress-wrap">
        <div class="fbb-progress-label">
            <span>Complétude du formulaire</span>
            <span id="fbbPct" class="fbb-progress-pct">0 %</span>
        </div>
        <div class="fbb-progress-track">
            <div class="fbb-progress-fill" id="fbbProgressFill" style="width:0%"></div>
        </div>
        <div class="fbb-progress-chips" id="fbbChips">
            <span class="fbb-chip fbb-chip-warn">⚠ Aucun champ</span>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════
     MAIN 2-COLUMN LAYOUT
     Left: Form.io Builder  |  Right: Quick-Add Panel
══════════════════════════════════════════════ --}}
    <div class="fbb-main-shell">

        {{-- ══ BUILDER HOST ══ --}}
        <div class="fbb-builder-col">
            <div class="fbb-builder-host" id="fbbBuilderHost">
                <div class="fbb-loading" id="fbbLoading">
                    <div class="fbb-loader"></div>
                    <div class="fbb-loading-txt">Chargement du Form Builder…</div>
                </div>
            </div>

            {{-- JSON Panel --}}
            <div class="fbb-json-panel" id="fbbJsonPanel" style="display:none;">
                <div class="fbb-json-head">
                    <span>📄 Schéma JSON — Form.io</span>
                    <div style="display:flex;gap:6px;">
                        <button class="fbb-btn fbb-btn-ghost fbb-btn-xs" onclick="fbbCopyJson()">📋 Copier</button>
                        <button class="fbb-btn fbb-btn-ghost fbb-btn-xs" onclick="fbbImportJson()">📥 Importer</button>
                        <button class="fbb-btn fbb-btn-ghost fbb-btn-xs"
                            onclick="document.getElementById('fbbJsonPanel').style.display='none'">✕</button>
                    </div>
                </div>
                <textarea class="fbb-json-area" id="fbbJsonArea" spellcheck="false" placeholder="Le schéma JSON apparaîtra ici…"></textarea>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
         QUICK-ADD PANEL (right sidebar)
         The star feature: add any element instantly
    ══════════════════════════════════════════════ --}}
        <div class="fbb-qadd-panel" id="fbbQaddPanel">

            {{-- Panel header --}}
            <div class="fbb-qadd-head">
                <div class="fbb-qadd-title">
                    <span style="font-size:16px;">➕</span>
                    Ajouter un élément
                </div>
                <div class="fbb-qadd-sub">Cliquez pour insérer dans le formulaire</div>
            </div>

            {{-- ─── SECTION: Labels & Textes ─── --}}
            <div class="fbb-qadd-section">
                <div class="fbb-qadd-section-title" onclick="fbbToggleSection(this)">
                    <span>🏷️ Labels & Textes</span>
                    <span class="fbb-qadd-chevron">▾</span>
                </div>
                <div class="fbb-qadd-section-body">

                    {{-- Quick label composer --}}
                    <div class="fbb-qadd-composer">
                        <div class="fbb-qadd-composer-label">Texte du label / titre de section</div>
                        <div style="display:flex;gap:6px;">
                            <input type="text" class="fbb-qadd-input" id="qaddLabelText"
                                placeholder="Ex: Informations personnelles">
                            <button class="fbb-btn fbb-btn-pub fbb-btn-xs" onclick="qaddAddLabel()">+ OK</button>
                        </div>
                        <div class="fbb-qadd-type-pills">
                            <span class="fbb-qadd-pill active" onclick="qaddSetLabelType(this,'h2')" data-type="h2">H2
                                Titre</span>
                            <span class="fbb-qadd-pill" onclick="qaddSetLabelType(this,'h3')" data-type="h3">H3
                                Sous-titre</span>
                            <span class="fbb-qadd-pill" onclick="qaddSetLabelType(this,'p')"
                                data-type="p">Paragraphe</span>
                            <span class="fbb-qadd-pill" onclick="qaddSetLabelType(this,'notice')" data-type="notice">📢
                                Notice</span>
                            <span class="fbb-qadd-pill" onclick="qaddSetLabelType(this,'divider')" data-type="divider">─
                                Séparateur</span>
                        </div>
                    </div>

                    {{-- Quick label presets --}}
                    <div class="fbb-qadd-presets">
                        <div class="fbb-qadd-preset-label">Présets rapides :</div>
                        <div class="fbb-qadd-preset-grid">
                            <button class="fbb-qadd-preset" onclick="qaddAddLabelPreset('👤 Identité')">👤
                                Identité</button>
                            <button class="fbb-qadd-preset" onclick="qaddAddLabelPreset('📄 Documents')">📄
                                Documents</button>
                            <button class="fbb-qadd-preset" onclick="qaddAddLabelPreset('💼 Activité professionnelle')">💼
                                Activité</button>
                            <button class="fbb-qadd-preset" onclick="qaddAddLabelPreset('🏢 CNSS & Social')">🏢
                                CNSS</button>
                            <button class="fbb-qadd-preset" onclick="qaddAddLabelPreset('📍 Coordonnées')">📍
                                Coords</button>
                            <button class="fbb-qadd-preset" onclick="qaddAddLabelPreset('✍️ Signature & Engagement')">✍️
                                Signature</button>
                            <button class="fbb-qadd-preset" onclick="qaddAddLabelPreset('⚠️ Informations importantes')">⚠️
                                Notice</button>
                            <button class="fbb-qadd-preset" onclick="qaddAddLabelPreset('📅 Dates & Validité')">📅
                                Dates</button>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ─── SECTION: Champs simples ─── --}}
            <div class="fbb-qadd-section">
                <div class="fbb-qadd-section-title" onclick="fbbToggleSection(this)">
                    <span>📝 Champs simples</span>
                    <span class="fbb-qadd-chevron">▾</span>
                </div>
                <div class="fbb-qadd-section-body">
                    <div class="fbb-qadd-field-composer">
                        <input type="text" class="fbb-qadd-input" id="qaddFieldLabel"
                            placeholder="Libellé du champ (ex: Nom complet)">
                        <div style="display:flex;gap:6px;margin-top:6px;">
                            <select class="fbb-qadd-select" id="qaddFieldType" style="flex:1;">
                                <option value="textfield">Texte court</option>
                                <option value="textarea">Texte long</option>
                                <option value="number">Nombre</option>
                                <option value="email">Email</option>
                                <option value="phoneNumber">Téléphone</option>
                                <option value="datetime">Date</option>
                                <option value="select">Liste déroulante</option>
                                <option value="radio">Choix unique</option>
                                <option value="checkbox">Case à cocher</option>
                            </select>
                            <label class="fbb-qadd-req-toggle" title="Obligatoire">
                                <input type="checkbox" id="qaddFieldReq" checked>
                                <span>*</span>
                            </label>
                            <button class="fbb-btn fbb-btn-pub fbb-btn-xs" onclick="qaddAddField()">+</button>
                        </div>
                    </div>

                    {{-- One-click field chips --}}
                    <div class="fbb-qadd-chips-label">Ajout en 1 clic :</div>
                    <div class="fbb-qadd-chips-grid">
                        <button class="fbb-qadd-chip" onclick="fbbIaAddField('textfield','Nom complet','nom')">👤
                            Nom</button>
                        <button class="fbb-qadd-chip" onclick="fbbIaAddField('textfield','Prénom','prenom')">👤
                            Prénom</button>
                        <button class="fbb-qadd-chip" onclick="fbbIaAddField('textfield','Numéro CIN','cin')">🆔
                            CIN</button>
                        <button class="fbb-qadd-chip" onclick="fbbIaAddField('email','Email','email')">📧 Email</button>
                        <button class="fbb-qadd-chip" onclick="fbbIaAddField('phoneNumber','Téléphone','tel')">📞
                            Tél.</button>
                        <button class="fbb-qadd-chip" onclick="fbbIaAddField('textfield','Adresse','adresse')">📍
                            Adresse</button>
                        <button class="fbb-qadd-chip" onclick="fbbIaAddField('textfield','Numéro CNSS','cnss')">🏢
                            CNSS</button>
                        <button class="fbb-qadd-chip" onclick="fbbIaAddField('textfield','IBAN bancaire','iban')">💳
                            IBAN</button>
                        <button class="fbb-qadd-chip" onclick="fbbIaAddField('datetime','Date de naissance','dob')">📅
                            Naissance</button>
                        <button class="fbb-qadd-chip"
                            onclick="fbbIaAddField('textfield','Spécialité artistique','specialite')">🎵
                            Spécialité</button>
                        <button class="fbb-qadd-chip"
                            onclick="fbbIaAddField('number','Numéro de carte pro','carte_num')">💳 N° Carte</button>
                        <button class="fbb-qadd-chip" onclick="fbbIaAddField('textfield','Nationalité','nationalite')">🌍
                            Nationalité</button>
                    </div>
                </div>
            </div>

            {{-- ─── SECTION: Listes & Sélecteurs ─── --}}
            <div class="fbb-qadd-section">
                <div class="fbb-qadd-section-title" onclick="fbbToggleSection(this)">
                    <span>📋 Listes & Sélecteurs</span>
                    <span class="fbb-qadd-chevron">▾</span>
                </div>
                <div class="fbb-qadd-section-body">
                    <div class="fbb-qadd-chips-grid">
                        <button class="fbb-qadd-chip fbb-qadd-chip-blue"
                            onclick="qaddAddSelect('Type d\'artiste',['Musicien','Danseur','Instrumentiste'])">🎭 Type
                            artiste</button>
                        <button class="fbb-qadd-chip fbb-qadd-chip-blue"
                            onclick="qaddAddSelect('Département',['Arts Plastiques','Musique & Danse','Administration'])">🏛️
                            Département</button>
                        <button class="fbb-qadd-chip fbb-qadd-chip-blue"
                            onclick="qaddAddSelect('Langue',['Français','Arabe'])">🌐 Langue</button>
                        <button class="fbb-qadd-chip fbb-qadd-chip-blue"
                            onclick="qaddAddSelect('Statut',['En attente','Validé','Rejeté','Suspendu'])">📊
                            Statut</button>
                        <button class="fbb-qadd-chip fbb-qadd-chip-blue"
                            onclick="qaddAddSelect('Gouvernorat',['Tunis','Ariana','Sfax','Sousse','Nabeul','Bizerte','Gafsa','Kairouan','Monastir'])">🗺️
                            Gouvernorat</button>
                        <button class="fbb-qadd-chip fbb-qadd-chip-blue"
                            onclick="qaddAddSelect('Genre',['Homme','Femme'])">⚧ Genre</button>
                        <button class="fbb-qadd-chip fbb-qadd-chip-blue"
                            onclick="qaddAddRadio('Oui / Non',['Oui','Non'])">✅ Oui/Non</button>
                        <button class="fbb-qadd-chip fbb-qadd-chip-blue"
                            onclick="qaddAddSelect('Spécialité musicale',['Musique Arabe Classique','Malouf','Jazz','Pop','Classique','Folklore'])">🎵
                            Spécialité mus.</button>
                    </div>
                </div>
            </div>

            {{-- ─── SECTION: Fichiers & Pièces jointes ─── --}}
            <div class="fbb-qadd-section">
                <div class="fbb-qadd-section-title" onclick="fbbToggleSection(this)">
                    <span>📎 Fichiers & Pièces jointes</span>
                    <span class="fbb-qadd-chevron">▾</span>
                </div>
                <div class="fbb-qadd-section-body">
                    <div class="fbb-qadd-chips-grid">
                        <button class="fbb-qadd-chip fbb-qadd-chip-teal"
                            onclick="fbbIaAddField('file','Diplôme artistique','diplome')">🎓 Diplôme</button>
                        <button class="fbb-qadd-chip fbb-qadd-chip-teal"
                            onclick="fbbIaAddField('file','Copie CIN','copie_cin')">🆔 CIN (copie)</button>
                        <button class="fbb-qadd-chip fbb-qadd-chip-teal"
                            onclick="fbbIaAddField('file','Photo d\'identité','photo')">📸 Photo</button>
                        <button class="fbb-qadd-chip fbb-qadd-chip-teal"
                            onclick="fbbIaAddField('file','Carte professionnelle','carte_pro')">💳 Carte pro</button>
                        <button class="fbb-qadd-chip fbb-qadd-chip-teal"
                            onclick="fbbIaAddField('file','Extrait casier judiciaire B3','b3')">📋 B3</button>
                        <button class="fbb-qadd-chip fbb-qadd-chip-teal"
                            onclick="fbbIaAddField('file','Attestation CNSS','att_cnss')">🏢 Att. CNSS</button>
                        <button class="fbb-qadd-chip fbb-qadd-chip-teal"
                            onclick="fbbIaAddField('file','Convention de prêt','convention')">📜 Convention</button>
                        <button class="fbb-qadd-chip fbb-qadd-chip-teal"
                            onclick="fbbIaAddField('file','Pièce justificative','pj')">📄 Pièce justif.</button>
                    </div>
                </div>
            </div>

            {{-- ─── SECTION: Signature & Validation ─── --}}
            <div class="fbb-qadd-section">
                <div class="fbb-qadd-section-title" onclick="fbbToggleSection(this)">
                    <span>✍️ Signature & Validation</span>
                    <span class="fbb-qadd-chevron">▾</span>
                </div>
                <div class="fbb-qadd-section-body">
                    <div class="fbb-qadd-chips-grid">
                        <button class="fbb-qadd-chip fbb-qadd-chip-purple"
                            onclick="fbbIaAddField('signature','Signature de l\'artiste','esign_artiste')">✍️ Sig.
                            artiste</button>
                        <button class="fbb-qadd-chip fbb-qadd-chip-purple"
                            onclick="fbbIaAddField('signature','Signature du responsable','esign_resp')">✍️ Sig.
                            responsable</button>
                        <button class="fbb-qadd-chip fbb-qadd-chip-purple"
                            onclick="qaddAddCheckbox('Je certifie l\'exactitude des informations fournies')">☑️
                            Certification</button>
                        <button class="fbb-qadd-chip fbb-qadd-chip-purple"
                            onclick="qaddAddCheckbox('J\'accepte les conditions d\'utilisation')">☑️ CGU</button>
                        <button class="fbb-qadd-chip fbb-qadd-chip-purple"
                            onclick="qaddAddCheckbox('J\'autorise le traitement de mes données personnelles (RGPD)')">☑️
                            RGPD</button>
                        <button class="fbb-qadd-chip fbb-qadd-chip-purple"
                            onclick="fbbIaAddField('datetime','Date de signature','date_signature')">📅 Date
                            signature</button>
                    </div>
                </div>
            </div>

            {{-- ─── SECTION: Mise en page ─── --}}
            <div class="fbb-qadd-section">
                <div class="fbb-qadd-section-title" onclick="fbbToggleSection(this)">
                    <span>📐 Mise en page</span>
                    <span class="fbb-qadd-chevron">▾</span>
                </div>
                <div class="fbb-qadd-section-body">
                    <div class="fbb-qadd-chips-grid">
                        <button class="fbb-qadd-chip fbb-qadd-chip-gold" onclick="qaddAddLayout('panel')">📦 Panel /
                            Bloc</button>
                        <button class="fbb-qadd-chip fbb-qadd-chip-gold" onclick="qaddAddLayout('columns')">⊞ Colonnes
                            (2)</button>
                        <button class="fbb-qadd-chip fbb-qadd-chip-gold" onclick="qaddAddLayout('tabs')">📑
                            Onglets</button>
                        <button class="fbb-qadd-chip fbb-qadd-chip-gold" onclick="qaddAddLayout('fieldset')">🗂️ Groupe de
                            champs</button>
                        <button class="fbb-qadd-chip fbb-qadd-chip-gold" onclick="qaddAddLayout('table')">📊
                            Tableau</button>
                        <button class="fbb-qadd-chip fbb-qadd-chip-gold" onclick="qaddAddDivider()">─ Ligne de
                            séparation</button>
                    </div>
                </div>
            </div>

            {{-- ─── Champ personnalisé ─── --}}
            <div class="fbb-qadd-section">
                <div class="fbb-qadd-section-title" onclick="fbbToggleSection(this)">
                    <span>⚙️ Champ personnalisé</span>
                    <span class="fbb-qadd-chevron">▾</span>
                </div>
                <div class="fbb-qadd-section-body">
                    <div class="fbb-qadd-field-composer">
                        <div style="display:flex;flex-direction:column;gap:7px;">
                            <input type="text" class="fbb-qadd-input" id="qaCustomLabel" placeholder="Libellé *">
                            <input type="text" class="fbb-qadd-input" id="qaCustomKey"
                                placeholder="Clé technique (ex: num_matricule)">
                            <input type="text" class="fbb-qadd-input" id="qaCustomPlaceholder"
                                placeholder="Texte indicatif (placeholder)">
                            <select class="fbb-qadd-select" id="qaCustomType">
                                <option value="textfield">Texte court</option>
                                <option value="textarea">Texte long</option>
                                <option value="number">Nombre</option>
                                <option value="email">Email</option>
                                <option value="phoneNumber">Téléphone</option>
                                <option value="datetime">Date/Heure</option>
                                <option value="file">Fichier / Upload</option>
                                <option value="signature">Signature électronique</option>
                                <option value="checkbox">Case à cocher</option>
                                <option value="select">Liste déroulante</option>
                                <option value="radio">Boutons radio</option>
                                <option value="password">Mot de passe</option>
                                <option value="url">URL / Lien</option>
                                <option value="currency">Montant (monnaie)</option>
                                <option value="address">Adresse géolocalisée</option>
                                <option value="htmlelement">HTML personnalisé</option>
                            </select>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <label
                                    style="display:flex;align-items:center;gap:5px;font-size:11.5px;color:var(--text2);cursor:pointer;">
                                    <input type="checkbox" id="qaCustomReq" checked> Obligatoire
                                </label>
                                <label
                                    style="display:flex;align-items:center;gap:5px;font-size:11.5px;color:var(--text2);cursor:pointer;">
                                    <input type="checkbox" id="qaCustomHidden"> Masqué
                                </label>
                            </div>
                            <button class="fbb-btn fbb-btn-pub" style="width:100%;justify-content:center;"
                                onclick="qaddAddCustomField()">
                                ➕ Ajouter ce champ
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── Modèles de formulaires complets ─── --}}
            <div class="fbb-qadd-section">
                <div class="fbb-qadd-section-title" onclick="fbbToggleSection(this)">
                    <span>🗂️ Modèles prédéfinis</span>
                    <span class="fbb-qadd-chevron">▾</span>
                </div>
                <div class="fbb-qadd-section-body">
                    <div style="display:flex;flex-direction:column;gap:7px;">
                        <button class="fbb-qadd-template-btn" onclick="qaddLoadTemplate('attestation')">
                            <span class="fbb-qadd-tpl-icon">📜</span>
                            <div>
                                <div class="fbb-qadd-tpl-name">Attestation d'exercice</div>
                                <div class="fbb-qadd-tpl-sub">5 champs · Nom, CIN, Tel, Langue, Carte pro</div>
                            </div>
                            <span class="fbb-badge fbb-badge-green" style="flex-shrink:0;">Charger</span>
                        </button>
                        <button class="fbb-qadd-template-btn" onclick="qaddLoadTemplate('carte_pro')">
                            <span class="fbb-qadd-tpl-icon">💳</span>
                            <div>
                                <div class="fbb-qadd-tpl-name">Carte professionnelle</div>
                                <div class="fbb-qadd-tpl-sub">8 champs · Complet avec signature</div>
                            </div>
                            <span class="fbb-badge fbb-badge-green" style="flex-shrink:0;">Charger</span>
                        </button>
                        <button class="fbb-qadd-template-btn" onclick="qaddLoadTemplate('pret_fnap')">
                            <span class="fbb-qadd-tpl-icon">🖼️</span>
                            <div>
                                <div class="fbb-qadd-tpl-name">Prêt d'œuvres FNAP</div>
                                <div class="fbb-qadd-tpl-sub">10 champs · Convention + annexes</div>
                            </div>
                            <span class="fbb-badge fbb-badge-green" style="flex-shrink:0;">Charger</span>
                        </button>
                        <button class="fbb-qadd-template-btn" onclick="qaddLoadTemplate('etranger')">
                            <span class="fbb-qadd-tpl-icon">🌍</span>
                            <div>
                                <div class="fbb-qadd-tpl-name">Artiste étranger</div>
                                <div class="fbb-qadd-tpl-sub">6 champs · Passeport, visa, autorisation</div>
                            </div>
                            <span class="fbb-badge fbb-badge-green" style="flex-shrink:0;">Charger</span>
                        </button>
                        <button class="fbb-qadd-template-btn" onclick="qaddLoadTemplate('cnss')">
                            <span class="fbb-qadd-tpl-icon">🏢</span>
                            <div>
                                <div class="fbb-qadd-tpl-name">Vérification CNSS</div>
                                <div class="fbb-qadd-tpl-sub">4 champs · CNSS, CIN, Tel, Signature</div>
                            </div>
                            <span class="fbb-badge fbb-badge-green" style="flex-shrink:0;">Charger</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>{{-- end fbb-qadd-panel --}}
    </div>{{-- end fbb-main-shell --}}


    {{-- ══════════════════════════════════════════════
     MODALS (inchangés mais stylisés backend.css)
══════════════════════════════════════════════ --}}

    {{-- Preview --}}
    <div class="fbb-overlay" id="fbbPreviewOverlay" onclick="fbbClosePreview()"></div>
    <div class="fbb-modal fbb-modal-lg" id="fbbPreviewModal">
        <div class="fbb-modal-head">
            <div>
                <div class="fbb-modal-title">👁 Prévisualisation — <span id="fbbPreviewFormName"></span></div>
                <div class="fbb-modal-sub">Rendu exact côté utilisateur final</div>
            </div>
            <button class="fbb-modal-close" onclick="fbbClosePreview()">×</button>
        </div>
        <div class="fbb-modal-body">
            <div class="fbb-device-bar">
                <button class="fbb-device active" onclick="fbbSetDevice(this,'100%')">🖥 Desktop</button>
                <button class="fbb-device" onclick="fbbSetDevice(this,'768px')">📱 Tablette</button>
                <button class="fbb-device" onclick="fbbSetDevice(this,'390px')">📱 Mobile</button>
            </div>
            <div class="fbb-preview-frame" id="fbbPreviewFrame">
                <div id="fbbPreviewContent" style="padding:24px;"></div>
            </div>
        </div>
        <div class="fbb-modal-foot">
            <button class="fbb-btn fbb-btn-ghost" onclick="fbbClosePreview()">Fermer</button>
            <button class="fbb-btn fbb-btn-pub" onclick="fbbClosePreview(); fbbPublish()">🚀 Publier</button>
        </div>
    </div>

    {{-- Formulaires --}}
    <div class="fbb-overlay" id="fbbFormsOverlay" onclick="fbbCloseForms()"></div>
    <div class="fbb-modal fbb-modal-md" id="fbbFormsModal">
        <div class="fbb-modal-head">
            <div class="fbb-modal-title">📋 Mes Formulaires</div>
            <button class="fbb-modal-close" onclick="fbbCloseForms()">×</button>
        </div>
        <div class="fbb-modal-body">
            <div class="fbb-forms-toolbar">
                <input class="fbb-input" id="fbbFormsSearch" placeholder="🔍 Rechercher…"
                    oninput="fbbFilterForms(this.value)">
                <select class="fbb-select" id="fbbFormsFilter" onchange="fbbFilterByStatus(this.value)">
                    <option value="">Tous les statuts</option>
                    <option value="Publié">Publié</option>
                    <option value="Brouillon">Brouillon</option>
                    <option value="Archivé">Archivé</option>
                </select>
                <button class="fbb-btn fbb-btn-pub fbb-btn-xs" onclick="fbbNewForm()">+ Nouveau</button>
            </div>
            <div class="fbb-forms-grid" id="fbbFormsGrid"></div>
        </div>
        <div class="fbb-modal-foot">
            <button class="fbb-btn fbb-btn-ghost" onclick="fbbCloseForms()">Fermer</button>
        </div>
    </div>

    {{-- Publier --}}
<div class="fbb-overlay" id="fbbPublishOverlay" onclick="fbbClosePublish()"></div>
<div class="fbb-modal fbb-modal-sm" id="fbbPublishModal">
    <div class="fbb-modal-head">
        <div class="fbb-modal-title">🚀 Publier le formulaire</div>
        <button class="fbb-modal-close" onclick="fbbClosePublish()">×</button>
    </div>
    <div class="fbb-modal-body">
        <div class="fbb-field-group">
            <label class="fbb-label">Département</label>
            <input type="text" class="fbb-input" id="fbbPubDeptDisplay" readonly style="background:var(--fbb-bg4);">
        </div>
        <div class="fbb-field-group">
            <label class="fbb-label">Date d'activation</label>
            <input type="date" class="fbb-input" id="fbbPubDate">
        </div>
        <div class="fbb-field-group">
            <label class="fbb-label">Date d'expiration (optionnel)</label>
            <input type="date" class="fbb-input" id="fbbPubExpDate">
        </div>
        <div class="fbb-field-group">
    <label class="fbb-label">⏳ Validité du document (mois)</label>
    <input type="number" class="fbb-input" id="fbbValidityMonths"
           min="1" max="600" placeholder="Ex: 36 pour 3 ans — laisser vide = sans restriction">
    <div style="font-size:10.5px;color:var(--fbb-text3);margin-top:4px;">
        Empêche le citoyen de soumettre à nouveau avant expiration.
        Exemples : 12 = 1 an · 36 = 3 ans · 60 = 5 ans
    </div>
    <div class="fbb-field-group">
    <label class="fbb-label">🔁 Nombre maximal de soumissions</label>
    <input type="number" class="fbb-input" id="fbbMaxSubmissions"
           min="1" max="1000" placeholder="Ex: 1 — laisser vide = illimité">
    <div style="font-size:10.5px;color:var(--fbb-text3);margin-top:4px;">
        Bloque le citoyen une fois ce nombre de soumissions atteint.
        Exemples : 1 = une seule fois · 3 = trois tentatives max
    </div>
</div>
</div>
        <div class="fbb-notice fbb-notice-green">
            ✅ Le formulaire sera lié au workflow sélectionné et activé automatiquement.
        </div>
    </div>
    <div class="fbb-modal-foot">
        <button class="fbb-btn fbb-btn-ghost" onclick="fbbClosePublish()">Annuler</button>
        <button class="fbb-btn fbb-btn-pub" onclick="fbbConfirmPublish()">🚀 Confirmer</button>
    </div>
</div>

    {{-- IA --}}
    <div class="fbb-overlay" id="fbbIaOverlay" onclick="fbbCloseIa()"></div>
    <div class="fbb-modal fbb-modal-md" id="fbbIaModal">
        <div class="fbb-modal-head">
            <div>
                <div class="fbb-modal-title">🤖 Assistant IA — Form Builder</div>
                <div class="fbb-modal-sub">Génération automatique · Analyse · Suggestions · Traduction</div>
            </div>
            <button class="fbb-modal-close" onclick="fbbCloseIa()">×</button>
        </div>
        <div class="fbb-modal-body">
            <div class="fbb-tabs">
                <button class="fbb-tab active" onclick="fbbTab(this,'iaGen')">✨ Générer</button>
                <button class="fbb-tab" onclick="fbbTab(this,'iaAnalyze')">🔍 Analyser</button>
                <button class="fbb-tab" onclick="fbbTab(this,'iaSuggest')">💡 Suggestions</button>
                <button class="fbb-tab" onclick="fbbTab(this,'iaTranslate')">🌐 Traduire</button>
            </div>
            <div class="fbb-tab-panel" id="iaGen">
                <div class="fbb-field-group">
                    <label class="fbb-label">Décrivez le formulaire à créer</label>
                    <textarea class="fbb-textarea" id="iaPrompt" rows="3"
                        placeholder="Ex : Formulaire CNSS pour musiciens avec CIN, numéro CNSS, pièces justificatives, signature…"></textarea>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div class="fbb-field-group">
                        <label class="fbb-label">Type</label>
                        <select class="fbb-select" id="iaFormType">
                            <option>Demande administrative</option>
                            <option>Inscription / Enregistrement</option>
                            <option>Vérification / Audit</option>
                            <option>Rapport</option>
                        </select>
                    </div>
                    <div class="fbb-field-group">
                        <label class="fbb-label">Département</label>
                        <select class="fbb-select" id="iaDept">
                            <option>Arts Plastiques</option>
                            <option>Musique & Danse</option>
                            <option>Administration</option>
                        </select>
                    </div>
                </div>
                <div class="fbb-field-group">
                    <label class="fbb-label">Inclure automatiquement</label>
                    <div class="fbb-checkboxes">
                        <label><input type="checkbox" id="iaInclCin" checked> Lookup CIN</label>
                        <label><input type="checkbox" id="iaInclSig" checked> Signature</label>
                        <label><input type="checkbox" id="iaInclFile" checked> Pièces jointes</label>
                        <label><input type="checkbox" id="iaInclIban"> IBAN</label>
                        <label><input type="checkbox" id="iaInclCnss"> CNSS</label>
                    </div>
                </div>
                <div id="iaGenPreview" class="fbb-gen-preview" style="display:none;">
                    <div class="fbb-gen-preview-title" id="iaGenTitle"></div>
                    <div id="iaGenList" class="fbb-gen-list"></div>
                </div>
            </div>
            <div class="fbb-tab-panel" id="iaAnalyze" style="display:none;">
                <div class="fbb-analyze-count" id="iaAnalyzeIntro">Analyse du formulaire courant</div>
                <div id="iaAnalyzeCards"></div>
            </div>
            <div class="fbb-tab-panel" id="iaSuggest" style="display:none;">
                <div class="fbb-suggest-list">
                    <div class="fbb-suggest-row">
                        <div class="fbb-suggest-icon" style="background:var(--fbb-gold-dim);color:var(--fbb-gold);">💳
                        </div>
                        <div class="fbb-suggest-info">
                            <div class="fbb-suggest-name">IBAN bancaire</div>
                            <div class="fbb-suggest-meta">Priorité haute · 84 % des formulaires</div>
                        </div>
                        <button class="fbb-btn fbb-btn-pub fbb-btn-xs"
                            onclick="fbbIaAddField('textfield','IBAN bancaire','iban'); fbbCloseIa()">+ Ajouter</button>
                    </div>
                    <div class="fbb-suggest-row">
                        <div class="fbb-suggest-icon" style="background:var(--fbb-purple-dim);color:var(--fbb-purple);">✍
                        </div>
                        <div class="fbb-suggest-info">
                            <div class="fbb-suggest-name">Signature électronique</div>
                            <div class="fbb-suggest-meta">Requis pour documents officiels</div>
                        </div>
                        <button class="fbb-btn fbb-btn-pub fbb-btn-xs"
                            onclick="fbbIaAddField('signature','Signature électronique','esign'); fbbCloseIa()">+
                            Ajouter</button>
                    </div>
                    <div class="fbb-suggest-row">
                        <div class="fbb-suggest-icon" style="background:var(--fbb-teal-dim);color:var(--fbb-teal);">📍
                        </div>
                        <div class="fbb-suggest-info">
                            <div class="fbb-suggest-name">Adresse complète</div>
                            <div class="fbb-suggest-meta">Géolocalisation des artistes</div>
                        </div>
                        <button class="fbb-btn fbb-btn-pub fbb-btn-xs"
                            onclick="fbbIaAddField('textarea','Adresse complète','adresse'); fbbCloseIa()">+
                            Ajouter</button>
                    </div>
                    <div class="fbb-suggest-row">
                        <div class="fbb-suggest-icon" style="background:var(--fbb-blue-dim);color:var(--fbb-blue);">🏛️
                        </div>
                        <div class="fbb-suggest-info">
                            <div class="fbb-suggest-name">Numéro CNSS</div>
                            <div class="fbb-suggest-meta">Vérification affiliation automatique</div>
                        </div>
                        <button class="fbb-btn fbb-btn-pub fbb-btn-xs"
                            onclick="fbbIaAddField('textfield','Numéro CNSS','cnss'); fbbCloseIa()">+ Ajouter</button>
                    </div>
                    <div class="fbb-suggest-row">
                        <div class="fbb-suggest-icon" style="background:var(--fbb-green-dim);color:var(--fbb-green);">📅
                        </div>
                        <div class="fbb-suggest-info">
                            <div class="fbb-suggest-name">Date de naissance</div>
                            <div class="fbb-suggest-meta">Vérification éligibilité FNAP</div>
                        </div>
                        <button class="fbb-btn fbb-btn-pub fbb-btn-xs"
                            onclick="fbbIaAddField('datetime','Date de naissance','dob'); fbbCloseIa()">+ Ajouter</button>
                    </div>
                </div>
            </div>
            <div class="fbb-tab-panel" id="iaTranslate" style="display:none;">
                <div class="fbb-field-group">
                    <label class="fbb-label">Traduire les labels du formulaire</label>
                    <div style="display:flex;gap:12px;align-items:center;">
                        <select class="fbb-select" id="iaTransFrom">
                            <option>Français</option>
                            <option>Arabe</option>
                        </select>
                        <span style="color:var(--fbb-text3);font-size:18px;">→</span>
                        <select class="fbb-select" id="iaTransTo">
                            <option>Arabe</option>
                            <option>Français</option>
                        </select>
                    </div>
                </div>
                <div class="fbb-notice fbb-notice-blue">🌐 L'IA traduit tous les labels, placeholders et messages d'erreur.
                </div>
                <button class="fbb-btn fbb-btn-pub" style="width:100%;justify-content:center;"
                    onclick="fbbIaTranslate()">🌐 Traduire automatiquement</button>
            </div>
        </div>
        <div class="fbb-modal-foot">
            <button class="fbb-btn fbb-btn-ghost" onclick="fbbCloseIa()">Annuler</button>
            <button class="fbb-btn fbb-btn-pub" id="fbbIaPrimaryBtn" onclick="fbbIaPrimary()">✨ Générer</button>
        </div>
    </div>


    {{-- ════════════════════════════════════════
     STYLES — full backend.css integration
════════════════════════════════════════ --}}
    <style>
        :root,
        .light {
            --fbb-bg: var(--bg, #0b0d0f);
            --fbb-bg2: var(--bg2, #111316);
            --fbb-bg3: var(--bg3, #181b1f);
            --fbb-bg4: var(--bg4, #1e2228);
            --fbb-border: var(--border, rgba(255, 255, 255, .07));
            --fbb-border2: var(--border2, rgba(255, 255, 255, .12));
            --fbb-text: var(--text, #f0f0f0);
            --fbb-text2: var(--text2, #8a8f9a);
            --fbb-text3: var(--text3, #4a4f5a);
            --fbb-gold: var(--gold, #c9a84c);
            --fbb-gold2: var(--gold2, #e8c97a);
            --fbb-gold-dim: var(--gold-dim, rgba(201, 168, 76, .15));
            --fbb-teal: var(--teal, #2dd4bf);
            --fbb-teal-dim: var(--teal-dim, rgba(45, 212, 191, .12));
            --fbb-red: var(--red, #f87171);
            --fbb-red-dim: var(--red-dim, rgba(248, 113, 113, .12));
            --fbb-green: var(--green, #4ade80);
            --fbb-green-dim: var(--green-dim, rgba(74, 222, 128, .12));
            --fbb-amber: var(--amber, #fbbf24);
            --fbb-amber-dim: var(--amber-dim, rgba(251, 191, 36, .12));
            --fbb-blue: var(--blue, #60a5fa);
            --fbb-blue-dim: var(--blue-dim, rgba(96, 165, 250, .12));
            --fbb-purple: var(--purple, #a78bfa);
            --fbb-purple-dim: var(--purple-dim, rgba(167, 139, 250, .12));
            --fbb-font: var(--font-body, 'Playfair Display', system-ui, sans-serif);
            --fbb-mono: var(--font-mono, 'SF Mono', 'Menlo', monospace);
            --fbb-radius: var(--radius, 10px);
            --fbb-radius-sm: var(--radius-sm, 6px);
        }


        .fbb-topbar {
            background: var(--fbb-bg2);
            border: 1px solid var(--fbb-border);
            border-radius: var(--fbb-radius);
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            margin-bottom: 10px;
            flex-wrap: wrap;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .18);
            transition: background .2s, border-color .2s;
        }

        .fbb-topbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            min-width: 0;
            flex-wrap: wrap;
        }

        .fbb-topbar-right {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .fbb-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .fbb-brand-icon {
            font-size: 22px;
            filter: drop-shadow(0 0 6px var(--fbb-gold-dim));
        }

        .fbb-brand-title {
            font-size: 14px;
            font-weight: 800;
            color: var(--fbb-text);
            line-height: 1.2;
        }

        .fbb-brand-sub {
            font-size: 10.5px;
            color: var(--fbb-gold);
            opacity: .8;
        }

        .fbb-sep {
            width: 1px;
            height: 28px;
            background: var(--fbb-border);
            flex-shrink: 0;
        }

        /* Form name inline edit */
        .fbb-form-name {
            font-size: 13px;
            font-weight: 700;
            color: var(--fbb-text);
            background: transparent;
            border: none;
            border-bottom: 1.5px dashed var(--fbb-border2);
            padding: 3px 6px;
            font-family: var(--fbb-font);
            max-width: 220px;
            transition: border-color .2s, background .2s;
            border-radius: 4px 4px 0 0;
        }

        .fbb-form-name:focus {
            outline: none;
            border-bottom-color: var(--fbb-gold);
            background: var(--fbb-bg3);
        }

        .fbb-form-name::placeholder {
            color: var(--fbb-text3);
        }

        /* Select */
        .fbb-select {
            padding: 6px 10px;
            background: var(--fbb-bg3);
            border: 1px solid var(--fbb-border);
            border-radius: var(--fbb-radius-sm);
            color: var(--fbb-text);
            font-size: 12px;
            font-family: var(--fbb-font);
            cursor: pointer;
            transition: border-color .18s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%238a8f9a'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            padding-right: 28px;
        }

        .fbb-select:focus {
            outline: none;
            border-color: var(--fbb-gold);
        }

        /* Badges */
        .fbb-badge {
            font-size: 10.5px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            letter-spacing: .3px;
            white-space: nowrap;
        }

        .fbb-badge-teal {
            background: var(--fbb-teal-dim);
            color: var(--fbb-teal);
        }

        .fbb-badge-blue {
            background: var(--fbb-blue-dim);
            color: var(--fbb-blue);
        }

        .fbb-badge-green {
            background: var(--fbb-green-dim);
            color: var(--fbb-green);
        }

        .fbb-badge-amber {
            background: var(--fbb-amber-dim);
            color: var(--fbb-amber);
        }

        .fbb-badge-red {
            background: var(--fbb-red-dim);
            color: var(--fbb-red);
        }

        /* Field count stat */
        .fbb-stat {
            display: flex;
            align-items: baseline;
            gap: 4px;
            font-size: 15px;
            font-weight: 900;
            color: var(--fbb-gold);
            font-family: var(--fbb-mono);
        }

        .fbb-stat small {
            font-size: 10px;
            color: var(--fbb-text3);
            font-weight: 400;
        }


        .fbb-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 13px;
            border-radius: var(--fbb-radius-sm);
            font-size: 12px;
            font-weight: 600;
            font-family: var(--fbb-font);
            cursor: pointer;
            border: 1px solid transparent;
            transition: all .18s cubic-bezier(.4, 0, .2, 1);
            white-space: nowrap;
            user-select: none;
        }

        .fbb-btn:active {
            transform: scale(.97);
        }

        .fbb-btn-ghost {
            background: var(--fbb-bg3);
            border-color: var(--fbb-border);
            color: var(--fbb-text2);
        }

        .fbb-btn-ghost:hover {
            background: var(--fbb-bg4);
            border-color: var(--fbb-border2);
            color: var(--fbb-text);
        }

        .fbb-btn-ia {
            background: var(--fbb-purple-dim);
            border-color: rgba(167, 139, 250, .3);
            color: var(--fbb-purple);
        }

        .fbb-btn-ia:hover {
            background: rgba(167, 139, 250, .22);
        }

        .fbb-btn-save {
            background: var(--fbb-teal-dim);
            border-color: rgba(45, 212, 191, .3);
            color: var(--fbb-teal);
            font-weight: 700;
        }

        .fbb-btn-save:hover {
            background: rgba(45, 212, 191, .22);
        }

        .fbb-btn-pub {
            background: linear-gradient(135deg, var(--fbb-gold), var(--fbb-gold2));
            border-color: var(--fbb-gold);
            color: #111;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(201, 168, 76, .25);
        }

        .fbb-btn-pub:hover {
            background: linear-gradient(135deg, var(--fbb-gold2), var(--fbb-gold));
            box-shadow: 0 4px 14px rgba(201, 168, 76, .35);
        }

        .fbb-btn-xs {
            padding: 4px 10px;
            font-size: 11px;
        }

        .fbb-btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        /* ══════════════════════════════════════════════
           IA BANNER
        ══════════════════════════════════════════════ */
        .fbb-ia-banner {
            display: flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(90deg, var(--fbb-bg2), rgba(201, 168, 76, .04));
            border: 1px solid rgba(201, 168, 76, .2);
            border-radius: var(--fbb-radius);
            padding: 9px 16px;
            margin-bottom: 10px;
            flex-wrap: wrap;
            animation: fbb-fadein .4s ease;
        }

        .fbb-ia-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--fbb-gold);
            flex-shrink: 0;
            animation: fbb-pulse 1.8s infinite;
        }

        @keyframes fbb-pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .4;
                transform: scale(.65);
            }
        }

        .fbb-ia-txt {
            font-size: 12.5px;
            color: var(--fbb-text2);
            flex: 1;
            min-width: 0;
        }

        .fbb-ia-txt strong {
            color: var(--fbb-text);
        }

        .fbb-ia-close {
            background: none;
            border: none;
            color: var(--fbb-text3);
            cursor: pointer;
            font-size: 14px;
            padding: 0 4px;
        }

        .fbb-ia-close:hover {
            color: var(--fbb-text);
        }

        /* ══════════════════════════════════════════════
           PROGRESS BAR
        ══════════════════════════════════════════════ */
        .fbb-progress-wrap {
            background: var(--fbb-bg2);
            border: 1px solid var(--fbb-border);
            border-radius: var(--fbb-radius);
            padding: 12px 16px;
            margin-bottom: 10px;
            transition: background .2s;
        }

        .fbb-progress-label {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: var(--fbb-text2);
            margin-bottom: 7px;
        }

        .fbb-progress-pct {
            font-weight: 900;
            font-family: var(--fbb-mono);
            color: var(--fbb-gold);
        }

        .fbb-progress-track {
            height: 6px;
            background: var(--fbb-bg4);
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .fbb-progress-fill {
            height: 100%;
            border-radius: 3px;
            background: linear-gradient(90deg, var(--fbb-gold), var(--fbb-teal));
            transition: width .6s cubic-bezier(.4, 0, .2, 1);
        }

        .fbb-progress-chips {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .fbb-chip {
            font-size: 10.5px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .fbb-chip-warn {
            background: var(--fbb-amber-dim);
            color: var(--fbb-amber);
        }

        .fbb-chip-ok {
            background: var(--fbb-green-dim);
            color: var(--fbb-green);
        }

        .fbb-chip-info {
            background: var(--fbb-blue-dim);
            color: var(--fbb-blue);
        }

        .fbb-chip-error {
            background: var(--fbb-red-dim);
            color: var(--fbb-red);
        }

        /* ══════════════════════════════════════════════
           MAIN 2-COLUMN LAYOUT + RESPONSIVE
        ══════════════════════════════════════════════ */
        .fbb-main-shell {
            display: grid;
            grid-template-columns: 1fr 290px;
            gap: 12px;
            align-items: start;
        }

        .fbb-builder-col {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* Tablet: stack quick-add below builder */
        @media (max-width: 1100px) {
            .fbb-main-shell {
                grid-template-columns: 1fr;
            }

            .fbb-qadd-panel {
                position: static;
                max-height: none;
            }
        }

        /* Mobile: collapse topbar */
        @media (max-width: 640px) {
            .fbb-topbar {
                padding: 8px 12px;
                gap: 6px;
            }

            .fbb-brand-sub {
                display: none;
            }

            .fbb-form-name {
                max-width: 140px;
            }

            .fbb-sep {
                display: none;
            }

            .fbb-btn {
                padding: 6px 10px;
                font-size: 11px;
            }

            .fbb-btn-ghost:not([title]) {
                display: none;
            }

            /* hide undo/redo text on mobile */
            .fbb-progress-wrap {
                padding: 10px 12px;
            }

            .fbb-modal-lg {
                width: 98vw;
            }
        }

        /* ══════════════════════════════════════════════
           BUILDER HOST
        ══════════════════════════════════════════════ */
        .fbb-builder-host {
            background: var(--fbb-bg2);
            border: 1px solid var(--fbb-border);
            border-radius: var(--fbb-radius);
            min-height: 560px;
            position: relative;
            overflow: hidden;
            transition: background .2s, border-color .2s;
        }

        .fbb-loading {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 560px;
            gap: 16px;
        }

        .fbb-loader {
            width: 40px;
            height: 40px;
            border: 3px solid var(--fbb-border);
            border-top-color: var(--fbb-gold);
            border-radius: 50%;
            animation: fbb-spin 1s linear infinite;
        }

        @keyframes fbb-spin {
            to {
                transform: rotate(360deg);
            }
        }

        .fbb-loading-txt {
            font-size: 13px;
            color: var(--fbb-text3);
        }

        /* JSON panel */
        .fbb-json-panel {
            background: var(--fbb-bg2);
            border: 1px solid var(--fbb-border);
            border-radius: var(--fbb-radius);
            overflow: hidden;
        }

        .fbb-json-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            background: var(--fbb-bg3);
            border-bottom: 1px solid var(--fbb-border);
            font-size: 12.5px;
            font-weight: 700;
            color: var(--fbb-text2);
        }

        .fbb-json-area {
            display: block;
            width: 100%;
            padding: 14px;
            font-size: 11.5px;
            color: var(--fbb-teal);
            font-family: var(--fbb-mono);
            line-height: 1.65;
            max-height: 300px;
            min-height: 140px;
            background: var(--fbb-bg);
            border: none;
            resize: vertical;
        }

        .fbb-json-area:focus {
            outline: none;
        }

        /* ══════════════════════════════════════════════
           QUICK-ADD PANEL (right sidebar)
        ══════════════════════════════════════════════ */
        .fbb-qadd-panel {
            background: var(--fbb-bg2);
            border: 1px solid var(--fbb-border);
            border-radius: var(--fbb-radius);
            overflow: hidden;
            position: sticky;
            top: 72px;
            max-height: calc(100vh - 110px);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--fbb-bg4) transparent;
            transition: background .2s, border-color .2s;
        }

        .fbb-qadd-panel::-webkit-scrollbar {
            width: 4px;
        }

        .fbb-qadd-panel::-webkit-scrollbar-track {
            background: transparent;
        }

        .fbb-qadd-panel::-webkit-scrollbar-thumb {
            background: var(--fbb-bg4);
            border-radius: 2px;
        }

        .fbb-qadd-head {
            padding: 14px 16px;
            border-bottom: 1px solid var(--fbb-border);
            background: linear-gradient(135deg, var(--fbb-bg3), rgba(201, 168, 76, .04));
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .fbb-qadd-title {
            font-size: 13px;
            font-weight: 800;
            color: var(--fbb-text);
            display: flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 3px;
        }

        .fbb-qadd-sub {
            font-size: 10.5px;
            color: var(--fbb-text3);
        }

        /* Section accordion */
        .fbb-qadd-section {
            border-bottom: 1px solid var(--fbb-border);
        }

        .fbb-qadd-section:last-child {
            border-bottom: none;
        }

        .fbb-qadd-section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            cursor: pointer;
            font-size: 11.5px;
            font-weight: 700;
            color: var(--fbb-text2);
            user-select: none;
            transition: background .15s, color .15s;
        }

        .fbb-qadd-section-title:hover {
            background: var(--fbb-bg3);
            color: var(--fbb-text);
        }

        .fbb-qadd-chevron {
            font-size: 10px;
            color: var(--fbb-text3);
            transition: transform .2s;
        }

        .fbb-qadd-section-title.collapsed .fbb-qadd-chevron {
            transform: rotate(-90deg);
        }

        .fbb-qadd-section-body {
            padding: 10px 14px 12px;
        }

        /* Inputs inside panel */
        .fbb-qadd-composer {
            margin-bottom: 10px;
        }

        .fbb-qadd-composer-label {
            font-size: 10.5px;
            color: var(--fbb-text3);
            font-weight: 600;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: .6px;
        }

        .fbb-qadd-input,
        .fbb-input {
            width: 100%;
            padding: 7px 10px;
            background: var(--fbb-bg3);
            border: 1px solid var(--fbb-border2);
            border-radius: var(--fbb-radius-sm);
            color: var(--fbb-text);
            font-size: 12px;
            font-family: var(--fbb-font);
            outline: none;
            transition: border-color .18s, background .18s;
        }

        .fbb-qadd-input:focus,
        .fbb-input:focus {
            border-color: var(--fbb-gold);
            background: var(--fbb-bg4);
        }

        .fbb-qadd-input::placeholder,
        .fbb-input::placeholder {
            color: var(--fbb-text3);
        }

        .fbb-qadd-select {
            width: 100%;
            padding: 6px 10px;
            background: var(--fbb-bg3);
            border: 1px solid var(--fbb-border2);
            border-radius: var(--fbb-radius-sm);
            color: var(--fbb-text2);
            font-size: 11.5px;
            font-family: var(--fbb-font);
            cursor: pointer;
            outline: none;
            transition: border-color .18s;
        }

        .fbb-qadd-select:focus {
            border-color: var(--fbb-gold);
        }

        /* Type pills */
        .fbb-qadd-type-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 7px;
        }

        .fbb-qadd-pill {
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 10.5px;
            font-weight: 600;
            background: var(--fbb-bg4);
            color: var(--fbb-text3);
            cursor: pointer;
            border: 1px solid var(--fbb-border);
            transition: all .15s;
            user-select: none;
        }

        .fbb-qadd-pill:hover {
            color: var(--fbb-text2);
            border-color: var(--fbb-border2);
        }

        .fbb-qadd-pill.active {
            background: var(--fbb-gold-dim);
            color: var(--fbb-gold);
            border-color: rgba(201, 168, 76, .3);
        }

        /* Presets grid */
        .fbb-qadd-presets {
            margin-top: 10px;
        }

        .fbb-qadd-preset-label {
            font-size: 10.5px;
            color: var(--fbb-text3);
            font-weight: 600;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .6px;
        }

        .fbb-qadd-preset-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5px;
        }

        .fbb-qadd-preset {
            padding: 7px 8px;
            border-radius: var(--fbb-radius-sm);
            background: var(--fbb-bg3);
            border: 1px solid var(--fbb-border);
            color: var(--fbb-text2);
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            font-family: var(--fbb-font);
            text-align: left;
            transition: all .15s;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .fbb-qadd-preset:hover {
            background: var(--fbb-bg4);
            color: var(--fbb-text);
            border-color: var(--fbb-gold);
            transform: translateY(-1px);
        }

        /* 1-click chips */
        .fbb-qadd-chips-label {
            font-size: 10.5px;
            color: var(--fbb-text3);
            font-weight: 600;
            margin: 8px 0 5px;
            text-transform: uppercase;
            letter-spacing: .6px;
        }

        .fbb-qadd-chips-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5px;
        }

        .fbb-qadd-chip {
            padding: 7px 8px;
            border-radius: var(--fbb-radius-sm);
            background: var(--fbb-bg3);
            border: 1px solid var(--fbb-border);
            color: var(--fbb-text2);
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            font-family: var(--fbb-font);
            text-align: left;
            transition: all .15s;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .fbb-qadd-chip:hover {
            color: var(--fbb-text);
            background: var(--fbb-bg4);
            transform: translateY(-1px);
        }

        .fbb-qadd-chip:active {
            transform: scale(.97);
        }

        .fbb-qadd-chip-blue {
            border-left: 2px solid var(--fbb-blue);
        }

        .fbb-qadd-chip-blue:hover {
            border-color: var(--fbb-blue);
            color: var(--fbb-blue);
        }

        .fbb-qadd-chip-teal {
            border-left: 2px solid var(--fbb-teal);
        }

        .fbb-qadd-chip-teal:hover {
            border-color: var(--fbb-teal);
            color: var(--fbb-teal);
        }

        .fbb-qadd-chip-purple {
            border-left: 2px solid var(--fbb-purple);
        }

        .fbb-qadd-chip-purple:hover {
            border-color: var(--fbb-purple);
            color: var(--fbb-purple);
        }

        .fbb-qadd-chip-gold {
            border-left: 2px solid var(--fbb-gold);
        }

        .fbb-qadd-chip-gold:hover {
            border-color: var(--fbb-gold);
            color: var(--fbb-gold);
        }

        /* Field composer */
        .fbb-qadd-field-composer {
            margin-bottom: 10px;
        }

        .fbb-qadd-req-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: var(--fbb-radius-sm);
            background: var(--fbb-bg3);
            border: 1px solid var(--fbb-border2);
            cursor: pointer;
            font-size: 14px;
            font-weight: 700;
            color: var(--fbb-red);
            flex-shrink: 0;
            transition: background .15s;
        }

        .fbb-qadd-req-toggle input {
            display: none;
        }

        .fbb-qadd-req-toggle:has(input:not(:checked)) {
            color: var(--fbb-text3);
        }

        /* Template buttons */
        .fbb-qadd-template-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--fbb-radius-sm);
            background: var(--fbb-bg3);
            border: 1px solid var(--fbb-border);
            cursor: pointer;
            font-family: var(--fbb-font);
            transition: all .18s;
            text-align: left;
            width: 100%;
            margin-bottom: 6px;
        }

        .fbb-qadd-template-btn:hover {
            border-color: var(--fbb-gold);
            background: var(--fbb-bg4);
            transform: translateX(3px);
        }

        .fbb-qadd-tpl-icon {
            font-size: 20px;
            flex-shrink: 0;
        }

        .fbb-qadd-tpl-name {
            font-size: 12px;
            font-weight: 700;
            color: var(--fbb-text);
            margin-bottom: 2px;
        }

        .fbb-qadd-tpl-sub {
            font-size: 10px;
            color: var(--fbb-text3);
        }

        /* ══════════════════════════════════════════════
           MODALS
        ══════════════════════════════════════════════ */
        .fbb-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .65);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 9000;
        }

        .fbb-overlay.open {
            display: block;
            animation: fbb-fadein .2s ease;
        }

        .fbb-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--fbb-bg2);
            border: 1px solid var(--fbb-border2);
            border-radius: var(--fbb-radius);
            box-shadow: 0 32px 80px rgba(0, 0, 0, .7), 0 0 0 1px rgba(201, 168, 76, .06);
            z-index: 9100;
            max-height: 92vh;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--fbb-bg4) transparent;
        }

        .fbb-modal.open {
            display: block;
            animation: fbb-modalin .22s cubic-bezier(.34, 1.56, .64, 1);
        }

        @keyframes fbb-modalin {
            from {
                opacity: 0;
                transform: translate(-50%, -46%) scale(.96);
            }

            to {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
        }

        @keyframes fbb-fadein {
            from {
                opacity: 0;
                transform: translateY(4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fbb-modal-sm {
            width: 460px;
            max-width: 96vw;
        }

        .fbb-modal-md {
            width: 620px;
            max-width: 96vw;
        }

        .fbb-modal-lg {
            width: 860px;
            max-width: 96vw;
        }

        .fbb-modal-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding: 18px 20px;
            border-bottom: 1px solid var(--fbb-border);
            background: linear-gradient(135deg, var(--fbb-bg3), rgba(201, 168, 76, .03));
        }

        .fbb-modal-title {
            font-size: 15px;
            font-weight: 800;
            color: var(--fbb-text);
        }

        .fbb-modal-sub {
            font-size: 11px;
            color: var(--fbb-text3);
            margin-top: 3px;
        }

        .fbb-modal-close {
            background: var(--fbb-bg4);
            border: 1px solid var(--fbb-border);
            color: var(--fbb-text3);
            cursor: pointer;
            font-size: 16px;
            line-height: 1;
            padding: 4px 8px;
            border-radius: var(--fbb-radius-sm);
            transition: all .15s;
        }

        .fbb-modal-close:hover {
            background: var(--fbb-red-dim);
            color: var(--fbb-red);
            border-color: transparent;
        }

        .fbb-modal-body {
            padding: 18px 20px;
        }

        .fbb-modal-foot {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            padding: 14px 20px;
            border-top: 1px solid var(--fbb-border);
            background: var(--fbb-bg3);
        }

        /* Forms grid (inside modal) */
        .fbb-forms-toolbar {
            display: flex;
            gap: 8px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .fbb-forms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
            gap: 10px;
            max-height: 440px;
            overflow-y: auto;
            padding: 2px;
        }

        .fbb-form-card {
            background: var(--fbb-bg3);
            border: 1px solid var(--fbb-border);
            border-radius: var(--fbb-radius);
            padding: 14px;
            cursor: pointer;
            transition: all .2s;
        }

        .fbb-form-card:hover {
            border-color: var(--fbb-gold);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .3), 0 0 0 1px rgba(201, 168, 76, .15);
        }

        .fbb-form-card-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .fbb-form-card-name {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--fbb-text);
            margin-bottom: 2px;
        }

        .fbb-form-card-key {
            font-size: 10px;
            color: var(--fbb-text3);
            font-family: var(--fbb-mono);
            margin-bottom: 8px;
        }

        .fbb-form-card-stats {
            display: flex;
            gap: 8px;
            font-size: 10.5px;
            color: var(--fbb-text3);
        }

        .fbb-form-card-bar {
            margin-top: 8px;
        }

        .fbb-form-card-bar-label {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: var(--fbb-text3);
            margin-bottom: 4px;
        }

        .fbb-form-card-bar-track {
            height: 3px;
            background: var(--fbb-bg4);
            border-radius: 2px;
            overflow: hidden;
        }

        .fbb-form-card-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--fbb-gold), var(--fbb-teal));
        }

        /* ══════════════════════════════════════════════
           PREVIEW FRAME
        ══════════════════════════════════════════════ */
        .fbb-device-bar {
            display: flex;
            gap: 6px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .fbb-device {
            padding: 6px 14px;
            border-radius: var(--fbb-radius-sm);
            font-size: 12px;
            font-weight: 600;
            background: var(--fbb-bg3);
            border: 1px solid var(--fbb-border);
            color: var(--fbb-text2);
            cursor: pointer;
            font-family: var(--fbb-font);
            transition: all .15s;
        }

        .fbb-device.active {
            background: var(--fbb-gold-dim);
            border-color: rgba(201, 168, 76, .3);
            color: var(--fbb-gold);
        }

        .fbb-preview-frame {
            background: var(--fbb-bg3);
            border: 1px solid var(--fbb-border);
            border-radius: var(--fbb-radius-sm);
            max-width: 100%;
            margin: 0 auto;
            transition: max-width .3s ease;
            min-height: 300px;
            padding: 16px;
        }

        /* ══════════════════════════════════════════════
           IA MODAL — TABS & CONTENT
        ══════════════════════════════════════════════ */
        .fbb-tabs {
            display: flex;
            border-bottom: 1px solid var(--fbb-border);
            margin-bottom: 14px;
            overflow-x: auto;
            gap: 0;
        }

        .fbb-tab {
            padding: 9px 14px;
            font-size: 12px;
            font-weight: 600;
            color: var(--fbb-text3);
            cursor: pointer;
            border: none;
            border-bottom: 2px solid transparent;
            background: none;
            font-family: var(--fbb-font);
            transition: all .15s;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .fbb-tab:hover {
            color: var(--fbb-text2);
        }

        .fbb-tab.active {
            color: var(--fbb-gold);
            border-bottom-color: var(--fbb-gold);
        }

        .fbb-tab-panel {
            animation: fbb-fadein .2s ease;
        }

        /* Form controls inside modals */
        .fbb-field-group {
            margin-bottom: 14px;
        }

        .fbb-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: var(--fbb-text3);
            text-transform: uppercase;
            letter-spacing: .7px;
            margin-bottom: 6px;
        }

        .fbb-textarea {
            width: 100%;
            padding: 10px 12px;
            background: var(--fbb-bg3);
            border: 1px solid var(--fbb-border);
            border-radius: var(--fbb-radius-sm);
            color: var(--fbb-text);
            font-size: 12.5px;
            font-family: var(--fbb-font);
            resize: vertical;
            transition: border-color .18s, background .18s;
        }

        .fbb-textarea:focus {
            outline: none;
            border-color: var(--fbb-gold);
            background: var(--fbb-bg4);
        }

        .fbb-textarea::placeholder {
            color: var(--fbb-text3);
        }

        .fbb-checkboxes {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            font-size: 12px;
            color: var(--fbb-text2);
        }

        .fbb-checkboxes label {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }

        .fbb-checkboxes input[type="checkbox"] {
            accent-color: var(--fbb-gold);
        }

        /* Notices */
        .fbb-notice {
            padding: 10px 13px;
            border-radius: var(--fbb-radius-sm);
            font-size: 12px;
            line-height: 1.5;
            margin-bottom: 12px;
        }

        .fbb-notice-green {
            background: var(--fbb-green-dim);
            color: var(--fbb-green);
            border: 1px solid rgba(74, 222, 128, .2);
        }

        .fbb-notice-blue {
            background: var(--fbb-blue-dim);
            color: var(--fbb-blue);
            border: 1px solid rgba(96, 165, 250, .2);
        }

        .fbb-notice-amber {
            background: var(--fbb-amber-dim);
            color: var(--fbb-amber);
            border: 1px solid rgba(251, 191, 36, .2);
        }

        /* IA suggestions */
        .fbb-suggest-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .fbb-suggest-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            background: var(--fbb-bg3);
            border: 1px solid var(--fbb-border);
            border-radius: var(--fbb-radius-sm);
            transition: border-color .15s, transform .15s;
        }

        .fbb-suggest-row:hover {
            border-color: var(--fbb-gold);
            transform: translateX(3px);
        }

        .fbb-suggest-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .fbb-suggest-info {
            flex: 1;
        }

        .fbb-suggest-name {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--fbb-text);
        }

        .fbb-suggest-meta {
            font-size: 10.5px;
            color: var(--fbb-text3);
            margin-top: 2px;
        }

        /* Gen preview */
        .fbb-gen-preview {
            background: var(--fbb-bg3);
            border: 1px solid var(--fbb-border);
            border-radius: var(--fbb-radius-sm);
            padding: 12px 14px;
            margin-top: 12px;
        }

        .fbb-gen-preview-title {
            font-size: 12px;
            font-weight: 700;
            color: var(--fbb-gold);
            margin-bottom: 8px;
        }

        .fbb-gen-list {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .fbb-analyze-count {
            font-size: 12.5px;
            color: var(--fbb-text2);
            margin-bottom: 12px;
        }

        /* ══════════════════════════════════════════════
           FORM.IO BUILDER — DEEP THEME OVERRIDES
           Makes Form.io's own UI match the GED theme
           in both dark and light mode.
        ══════════════════════════════════════════════ */

        /* Panels / Cards */
        .fbb-builder-host .card,
        .fbb-builder-host .formio-builder-components .card {
            background: var(--fbb-bg3) !important;
            border-color: var(--fbb-border) !important;
            border-radius: var(--fbb-radius) !important;
            color: var(--fbb-text) !important;
        }

        .fbb-builder-host .card-header {
            background: var(--fbb-bg4) !important;
            border-color: var(--fbb-border) !important;
            color: var(--fbb-text2) !important;
            font-weight: 700 !important;
            font-size: 12px !important;
        }

        .fbb-builder-host .card-header:first-child {
            border-radius: var(--fbb-radius) var(--fbb-radius) 0 0 !important;
        }

        /* Component labels */
        .fbb-builder-host .formio-component-label,
        .fbb-builder-host label.col-form-label,
        .fbb-builder-host label.control-label {
            color: var(--fbb-text) !important;
            font-size: 12px !important;
            font-weight: 600 !important;
        }

        /* Inputs inside builder edit modals */
        .fbb-builder-host .form-control,
        .fbb-builder-host input[type="text"],
        .fbb-builder-host input[type="number"],
        .fbb-builder-host select.form-control,
        .fbb-builder-host textarea.form-control {
            background: var(--fbb-bg4) !important;
            border-color: var(--fbb-border2) !important;
            color: var(--fbb-text) !important;
            border-radius: var(--fbb-radius-sm) !important;
            font-size: 12px !important;
            transition: border-color .18s !important;
        }

        .fbb-builder-host .form-control:focus,
        .fbb-builder-host input[type="text"]:focus,
        .fbb-builder-host select.form-control:focus {
            border-color: var(--fbb-gold) !important;
            box-shadow: 0 0 0 3px var(--fbb-gold-dim) !important;
            outline: none !important;
        }

        /* Drag-and-drop zones */
        .fbb-builder-host .drag-container,
        .fbb-builder-host .formio-builder-components {
            border-color: var(--fbb-border) !important;
            border-radius: var(--fbb-radius) !important;
        }

        .fbb-builder-host [class*="builder-group-button"] {
            background: var(--fbb-bg3) !important;
            border-color: var(--fbb-border) !important;
            color: var(--fbb-text2) !important;
            border-radius: var(--fbb-radius-sm) !important;
            font-size: 11px !important;
            transition: all .15s !important;
        }

        .fbb-builder-host [class*="builder-group-button"]:hover {
            background: var(--fbb-bg4) !important;
            color: var(--fbb-text) !important;
            border-color: var(--fbb-gold) !important;
        }

        /* Component drag handles */
        .fbb-builder-host .component-btn-group .btn {
            background: var(--fbb-bg4) !important;
            border-color: var(--fbb-border) !important;
            color: var(--fbb-text2) !important;
            border-radius: var(--fbb-radius-sm) !important;
            transition: all .15s !important;
        }

        .fbb-builder-host .component-btn-group .btn:hover {
            color: var(--fbb-text) !important;
        }

        .fbb-builder-host .component-btn-group .btn-danger {
            background: var(--fbb-red-dim) !important;
            color: var(--fbb-red) !important;
        }

        .fbb-builder-host .component-btn-group .btn-success {
            background: var(--fbb-green-dim) !important;
            color: var(--fbb-green) !important;
        }

        .fbb-builder-host .component-btn-group .btn-primary {
            background: var(--fbb-blue-dim) !important;
            color: var(--fbb-blue) !important;
        }

        /* Builder sidebar component buttons */
        .fbb-builder-host .formcomponents .formcomponent {
            background: var(--fbb-bg3) !important;
            border: 1px solid var(--fbb-border) !important;
            color: var(--fbb-text2) !important;
            border-radius: var(--fbb-radius-sm) !important;
            font-size: 11.5px !important;
            font-weight: 600 !important;
            transition: all .15s !important;
            cursor: grab !important;
        }

        .fbb-builder-host .formcomponents .formcomponent:hover {
            background: var(--fbb-bg4) !important;
            color: var(--fbb-text) !important;
            border-color: var(--fbb-gold) !important;
            transform: translateX(2px);
        }

        .fbb-builder-host .formcomponents .formcomponent:active {
            cursor: grabbing !important;
        }

        /* Category headings */
        .fbb-builder-host .builder-group-button,
        .fbb-builder-host .builder-sidebar-header {
            background: var(--fbb-bg4) !important;
            color: var(--fbb-text2) !important;
            border: 1px solid var(--fbb-border) !important;
        }

        /* Drop zone empty state */
        .fbb-builder-host .drag-and-drop-alert {
            background: var(--fbb-bg3) !important;
            border: 2px dashed var(--fbb-border2) !important;
            color: var(--fbb-text3) !important;
            border-radius: var(--fbb-radius) !important;
            padding: 32px !important;
            text-align: center !important;
            font-size: 13px !important;
        }

        /* Edit component modal (Bootstrap modal used by Form.io) */
        .formio-dialog .formio-dialog-content {
            background: var(--fbb-bg2) !important;
            border: 1px solid var(--fbb-border2) !important;
            border-radius: var(--fbb-radius) !important;
            color: var(--fbb-text) !important;
            box-shadow: 0 32px 80px rgba(0, 0, 0, .7) !important;
        }

        .formio-dialog .nav-tabs {
            border-bottom-color: var(--fbb-border) !important;
        }

        .formio-dialog .nav-tabs .nav-link {
            color: var(--fbb-text3) !important;
            background: none !important;
            border-color: transparent !important;
            font-size: 12px !important;
            font-weight: 600 !important;
        }

        .formio-dialog .nav-tabs .nav-link.active {
            color: var(--fbb-gold) !important;
            border-bottom: 2px solid var(--fbb-gold) !important;
            background: transparent !important;
        }

        .formio-dialog .btn-primary {
            background: linear-gradient(135deg, var(--fbb-gold), var(--fbb-gold2)) !important;
            border-color: var(--fbb-gold) !important;
            color: #111 !important;
            font-weight: 700 !important;
        }

        .formio-dialog .btn-default,
        .formio-dialog .btn-secondary {
            background: var(--fbb-bg3) !important;
            border-color: var(--fbb-border) !important;
            color: var(--fbb-text2) !important;
        }

        .formio-dialog .btn-danger {
            background: var(--fbb-red-dim) !important;
            border-color: rgba(248, 113, 113, .3) !important;
            color: var(--fbb-red) !important;
        }

        /* Scrollbar inside builder */
        .fbb-builder-host *::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        .fbb-builder-host *::-webkit-scrollbar-track {
            background: var(--fbb-bg2);
        }

        .fbb-builder-host *::-webkit-scrollbar-thumb {
            background: var(--fbb-bg4);
            border-radius: 3px;
        }

        /* ══════════════════════════════════════════════
           LIGHT MODE OVERRIDES
           (body.light or .light anywhere in the tree)
        ══════════════════════════════════════════════ */
        .light .fbb-topbar,
        .light .fbb-progress-wrap,
        .light .fbb-ia-banner,
        .light .fbb-qadd-panel,
        .light .fbb-builder-host,
        .light .fbb-json-panel,
        .light .fbb-modal {
            box-shadow: 0 2px 12px rgba(0, 0, 0, .08);
        }

        .light .fbb-form-name:focus {
            background: var(--bg4);
        }

        .light .fbb-btn-pub {
            box-shadow: 0 2px 8px rgba(201, 168, 76, .2);
        }

        .light .fbb-builder-host .drag-and-drop-alert {
            border-color: rgba(0, 0, 0, .15) !important;
        }

        .light .formio-dialog .formio-dialog-content {
            box-shadow: 0 16px 48px rgba(0, 0, 0, .15) !important;
        }

        /* ══════════════════════════════════════════════
           SAVE BUTTON — make Ctrl+S feel responsive
        ══════════════════════════════════════════════ */
        .fbb-saving .fbb-btn-save {
            opacity: .7;
            pointer-events: none;
        }
    </style>


    {{-- ════════════════════════════════════════
     SCRIPTS
════════════════════════════════════════ --}}

    <script>
        'use strict';

        // ── State ──
        let _builder = null,
            _schema = {
                display: 'form',
                components: []
            };
        let _history = [],
            _redo = [],
            _activeTab = 'iaGen';
        let _labelType = 'h2';

        // ── Forms catalogue ──
        let _forms = [{
                name: "Attestation d'exercice artistique",
                key: 'wf-attestation-artistique',
                status: 'Publié',
                sub: '41 soumissions',
                fields: '5 champs',
                pct: 85
            },
            {
                name: "Carte Professionnelle Artistique",
                key: 'wf-carte-professionnelle',
                status: 'Publié',
                sub: '34 soumissions',
                fields: '8 champs',
                pct: 92
            },
            {
                name: "Prêt d'œuvres du FNAP",
                key: 'wf-fnap-access',
                status: 'Brouillon',
                sub: '0 soumissions',
                fields: '10 champs',
                pct: 60
            },
            {
                name: "Vérification CNSS",
                key: 'wf-cnss-verification',
                status: 'Publié',
                sub: '28 soumissions',
                fields: '4 champs',
                pct: 78
            },
            {
                name: "Autorisation artiste étranger",
                key: 'wf-artiste-etranger',
                status: 'Archivé',
                sub: '9 soumissions',
                fields: '6 champs',
                pct: 100
            },
            {
                name: "Diplôme musique arabe",
                key: 'wf-diplome-musique',
                status: 'Brouillon',
                sub: '0 soumissions',
                fields: '7 champs',
                pct: 45
            },
        ];
        let _filteredForms = [..._forms];

        // ── Wait for Form.io — booted at end of second <script> block ──
          // ── Wait for Form.io ──
        const _waitFormio = () => {
            if (typeof Formio !== 'undefined' && typeof Formio.builder === 'function') {
                _initBuilder();
                _rotateIaTip();
                _renderFormsGrid(_forms);
            } else {
                setTimeout(_waitFormio, 150);
            }
        };
        setTimeout(_waitFormio, 150);


        // ── Init Form.io Builder ──
        window._initBuilder = function() {
            const startSchema = {
                display: 'form',
                components: [{
                        type: 'textfield',
                        key: 'nom',
                        label: "Nom complet de l'artiste",
                        validate: {
                            required: true
                        }
                    },
                    {
                        type: 'textfield',
                        key: 'cin',
                        label: 'Numéro CIN',
                        placeholder: 'Ex: 08745632',
                        validate: {
                            required: true
                        }
                    },
                    {
                        type: 'phoneNumber',
                        key: 'tel',
                        label: 'Téléphone',
                        validate: {
                            required: true
                        }
                    },
                    {
                        type: 'select',
                        key: 'langue',
                        label: "Langue de l'attestation",
                        data: {
                            values: [{
                                label: 'Français',
                                value: 'fr'
                            }, {
                                label: 'Arabe',
                                value: 'ar'
                            }]
                        },
                        validate: {
                            required: true
                        }
                    },
                    {
                        type: 'file',
                        key: 'carte_pro',
                        label: 'Copie de la carte professionnelle',
                        storage: 'base64',
                        validate: {
                            required: true
                        }
                    },
                ]
            };

            const options = {
                noDefaultSubmitButton: true,
                language: 'fr',
                builder: {
                    basic: {
                        title: '📝 De base',
                        default: true,
                        weight: 0,
                        components: {
                            textfield: true,
                            textarea: true,
                            number: true,
                            email: true,
                            phoneNumber: true,
                            checkbox: true,
                            select: true,
                            radio: true,
                            file: true,
                            signature: true,
                            datetime: true
                        }
                    },
                    advanced: {
                        title: '⚙️ Avancé',
                        weight: 10,
                        components: {
                            address: true,
                            currency: true,
                            htmlelement: true,
                            content: true,
                            columns: true,
                            fieldset: true,
                            panel: true,
                            table: true,
                            tabs: true
                        }
                    },
                    layout: {
                        title: '📐 Mise en page',
                        weight: 20,
                        components: {
                            columns: true,
                            fieldset: true,
                            panel: true,
                            table: true,
                            tabs: true
                        }
                    },
                    data: {
                        title: '🗄️ Données',
                        weight: 30,
                        components: {
                            datagrid: true,
                            editgrid: true,
                            container: true
                        }
                    }
                },
                i18n: {
                    fr: {
                        'Drop components here': 'Glissez vos composants ici',
                        'Drag and Drop a form component': 'Faites glisser un composant',
                        'Submit': 'Soumettre',
                        'Cancel': 'Annuler',
                        'Save': 'Enregistrer',
                        'Delete': 'Supprimer',
                        'Edit': 'Modifier',
                        'Copy': 'Copier',
                        'required': 'Ce champ est obligatoire.',
                        'Label': 'Libellé',
                        'Property Name': 'Clé',
                        'Placeholder': 'Texte indicatif',
                        'Description': 'Description',
                        'Required': 'Obligatoire',
                    }
                }
            };

            Formio.builder(document.getElementById('fbbBuilderHost'), startSchema, options)
                .then(b => {
                    _builder = b;
                    _schema = startSchema;
                    const loader = document.getElementById('fbbLoading');
                    if (loader) loader.remove();
                    _updateStats(startSchema.components.length);
                    b.on('change', () => {
    const newSchema = b.schema;

    if (!newSchema) return;

    _history.push(JSON.stringify(_schema));
    _redo = [];

    _schema = newSchema;

    _updateStats(newSchema.components ? newSchema.components.length : 0);
    _autoSave();
});
                    b.on('saveComponent', c => _showToast('✅ "' + (c.label || c.key) + '" sauvegardé', 'success'));
                    b.on('deleteComponent', () => _showToast('🗑 Composant supprimé', 'info'));
                })
                .catch(err => {
                    document.getElementById('fbbBuilderHost').innerHTML = `
                <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:400px;gap:12px;color:var(--fbb-text3);">
                    <div style="font-size:32px;">⚠️</div>
                    <div style="text-align:center;">Impossible de charger le Form Builder.<br>Vérifiez votre connexion.</div>
                    <button class="fbb-btn fbb-btn-ghost" onclick="location.reload()">🔄 Réessayer</button>
                </div>`;
                });
        };

        // ── Stats ──
        window._updateStats = function(count) {
            document.getElementById('fbbFieldCount').textContent = count;
            const pct = count === 0 ? 0 : Math.min(100, Math.round((count / 8) * 100));
            document.getElementById('fbbPct').textContent = pct + ' %';
            document.getElementById('fbbProgressFill').style.width = pct + '%';
            const comps = _schema.components || [];
            const hasSig = comps.some(c => c.type === 'signature');
            const hasFile = comps.some(c => c.type === 'file');
            const hasReq = comps.some(c => c.validate && c.validate.required);
            let chips = '';
            if (count === 0) chips = '<span class="fbb-chip fbb-chip-warn">⚠ Aucun champ</span>';
            if (hasReq) chips += '<span class="fbb-chip fbb-chip-ok">✓ Validation active</span>';
            if (!hasReq && count > 0) chips += '<span class="fbb-chip fbb-chip-warn">⚠ Aucun obligatoire</span>';
            if (hasSig) chips += '<span class="fbb-chip fbb-chip-ok">✓ Signature</span>';
            if (!hasSig && count > 0) chips += '<span class="fbb-chip fbb-chip-info">ℹ Signature absente</span>';
            if (hasFile) chips += '<span class="fbb-chip fbb-chip-ok">✓ Fichier joint</span>';
            if (count >= 6) chips += '<span class="fbb-chip fbb-chip-ok">✓ Complet</span>';
            document.getElementById('fbbChips').innerHTML = chips;
        };

        // ── Auto-save ──
        let _autoSaveTimer;
        window._autoSave = function() {
            clearTimeout(_autoSaveTimer);
            _autoSaveTimer = setTimeout(() => {
                try {
                    localStorage.setItem('fbb_schema', JSON.stringify(_schema));
                    localStorage.setItem('fbb_name', document.getElementById('fbbFormName').value);
                    const badge = document.getElementById('fbbAutoSaveBadge');
                    if (badge) {
                        badge.style.display = '';
                        setTimeout(() => badge.style.display = 'none', 2000);
                    }
                } catch (e) {}
            }, 1500);
        };

        // ─────────────────────────────────────────
        // QUICK-ADD PANEL FUNCTIONS
        // ─────────────────────────────────────────

        // Section accordion toggle
        window.fbbToggleSection = function(titleEl) {
            titleEl.classList.toggle('collapsed');
            const body = titleEl.nextElementSibling;
            if (body) body.style.display = titleEl.classList.contains('collapsed') ? 'none' : '';
        };

        // Set label type pill
        window.qaddSetLabelType = function(el, type) {
            document.querySelectorAll('.fbb-qadd-type-pills .fbb-qadd-pill').forEach(p => p.classList.remove('active'));
            el.classList.add('active');
            _labelType = type;
        };

        // Add label / section heading
        window.qaddAddLabel = function() {
            const text = document.getElementById('qaddLabelText').value.trim();
            if (!text) {
                _showToast('Saisissez un texte pour le label', 'info');
                return;
            }

            let component;
            if (_labelType === 'divider') {
                component = {
                    type: 'htmlelement',
                    key: 'divider_' + Date.now(),
                    tag: 'hr',
                    content: '',
                    customClass: 'fbb-divider'
                };
            } else if (_labelType === 'notice') {
                component = {
                    type: 'htmlelement',
                    key: 'notice_' + Date.now(),
                    tag: 'div',
                    content: `<div style="padding:10px 14px;background:rgba(251,191,36,0.12);border-left:3px solid #fbbf24;border-radius:6px;font-size:13px;color:#fbbf24;">⚠️ ${text}</div>`
                };
            } else {
                const tagMap = {
                    h2: 'h2',
                    h3: 'h3',
                    p: 'p'
                };
                const tag = tagMap[_labelType] || 'h3';
                const styles = {
                    h2: `style="font-size:16px;font-weight:800;color:var(--text);margin:16px 0 8px;border-bottom:1px solid var(--border);padding-bottom:8px;"`,
                    h3: `style="font-size:14px;font-weight:700;color:var(--text);margin:12px 0 6px;"`,
                    p: `style="font-size:13px;color:var(--text2);line-height:1.6;margin:8px 0;"`
                };
                component = {
                    type: 'htmlelement',
                    key: 'lbl_' + Date.now(),
                    tag,
                    content: `<${tag} ${styles[_labelType]||''}>${text}</${tag}>`
                };
            }

            _addComponent(component);
            document.getElementById('qaddLabelText').value = '';
            _showToast(`✅ "${text}" ajouté`, 'success');
        };

        // Add label preset
        window.qaddAddLabelPreset = function(text) {
            const component = {
                type: 'htmlelement',
                key: 'lbl_' + Date.now(),
                tag: 'h3',
                content: `<h3 style="font-size:14px;font-weight:800;color:var(--text,#f0f0f0);margin:16px 0 8px;padding:8px 12px;background:rgba(201,168,76,0.08);border-left:3px solid #c9a84c;border-radius:4px;">${text}</h3>`
            };
            _addComponent(component);
            _showToast(`✅ Section "${text}" ajoutée`, 'success');
        };

        // Add simple field (from composer)
        window.qaddAddField = function() {
            const label = document.getElementById('qaddFieldLabel').value.trim();
            const type = document.getElementById('qaddFieldType').value;
            const req = document.getElementById('qaddFieldReq').checked;
            if (!label) {
                _showToast('Saisissez un libellé', 'info');
                return;
            }

            const key = label.toLowerCase().replace(/[^a-z0-9]/g, '_').replace(/__+/g, '_') + '_' + Date.now();
            const comp = {
                type,
                key,
                label,
                validate: {
                    required: req
                }
            };
            if (type === 'file') comp.storage = 'base64';

            _addComponent(comp);
            document.getElementById('qaddFieldLabel').value = '';
            _showToast(`✅ "${label}" (${type}) ajouté`, 'success');
        };

        // Add select with options
        window.qaddAddSelect = function(label, options) {
            const key = label.toLowerCase().replace(/[^a-z0-9]/g, '_') + '_' + Date.now();
            const comp = {
                type: 'select',
                key,
                label,
                data: {
                    values: options.map(o => ({
                        label: o,
                        value: o.toLowerCase().replace(/[^a-z0-9]/g, '_')
                    }))
                },
                validate: {
                    required: false
                }
            };
            _addComponent(comp);
            _showToast(`✅ Liste "${label}" ajoutée`, 'success');
        };

        // Add radio group
        window.qaddAddRadio = function(label, options) {
            const key = label.toLowerCase().replace(/[^a-z0-9]/g, '_') + '_' + Date.now();
            const comp = {
                type: 'radio',
                key,
                label,
                values: options.map(o => ({
                    label: o,
                    value: o.toLowerCase()
                })),
                validate: {
                    required: false
                }
            };
            _addComponent(comp);
            _showToast(`✅ "${label}" ajouté`, 'success');
        };

        // Add checkbox
        window.qaddAddCheckbox = function(label) {
            const key = 'chk_' + Date.now();
            _addComponent({
                type: 'checkbox',
                key,
                label,
                validate: {
                    required: false
                }
            });
            _showToast(`✅ Case "${label}" ajoutée`, 'success');
        };

        // Add divider line
        window.qaddAddDivider = function() {
            _addComponent({
                type: 'htmlelement',
                key: 'div_' + Date.now(),
                tag: 'hr',
                content: '',
                customClass: 'my-3'
            });
            _showToast('✅ Séparateur ajouté', 'success');
        };

        // Add layout component
        window.qaddAddLayout = function(type) {
            const labels = {
                panel: 'Nouveau bloc',
                columns: 'Mise en colonnes',
                tabs: 'Onglets',
                fieldset: 'Groupe de champs',
                table: 'Tableau de données'
            };
            const comp = {
                type,
                key: type + '_' + Date.now(),
                label: labels[type] || type
            };
            if (type === 'columns') {
                comp.columns = [{
                    components: [],
                    width: 6,
                    offset: 0,
                    push: 0,
                    pull: 0
                }, {
                    components: [],
                    width: 6,
                    offset: 0,
                    push: 0,
                    pull: 0
                }];
                delete comp.label;
            }
            if (type === 'tabs') {
                comp.components = [{
                    label: 'Onglet 1',
                    key: 'tab1',
                    components: []
                }];
            }
            _addComponent(comp);
            _showToast(`✅ "${labels[type]||type}" ajouté`, 'success');
        };

        // Add fully custom field
        window.qaddAddCustomField = function() {
            const label = document.getElementById('qaCustomLabel').value.trim();
            const keyInput = document.getElementById('qaCustomKey').value.trim();
            const placeholder = document.getElementById('qaCustomPlaceholder').value.trim();
            const type = document.getElementById('qaCustomType').value;
            const req = document.getElementById('qaCustomReq').checked;

            if (!label) {
                _showToast('Libellé obligatoire', 'info');
                return;
            }

            const key = keyInput || (label.toLowerCase().replace(/[^a-z0-9]/g, '_') + '_' + Date.now());
            const comp = {
                type,
                key,
                label,
                validate: {
                    required: req
                }
            };
            if (placeholder) comp.placeholder = placeholder;
            if (type === 'file') comp.storage = 'base64';
            if (document.getElementById('qaCustomHidden').checked) comp.hidden = true;

            _addComponent(comp);
            document.getElementById('qaCustomLabel').value = '';
            document.getElementById('qaCustomKey').value = '';
            document.getElementById('qaCustomPlaceholder').value = '';
            _showToast(`✅ Champ personnalisé "${label}" ajouté`, 'success');
        };

        // Load template
        window.qaddLoadTemplate = function(tpl) {
            const templates = {
                attestation: {
                    display: 'form',
                    components: [{
                            type: 'htmlelement',
                            key: 'lbl1',
                            tag: 'h3',
                            content: '<h3 style="font-size:14px;font-weight:800;color:var(--text,#f0f0f0);margin:0 0 12px;padding:8px 12px;background:rgba(201,168,76,0.08);border-left:3px solid #c9a84c;border-radius:4px;">👤 Identité de l\'artiste</h3>'
                        },
                        {
                            type: 'textfield',
                            key: 'nom',
                            label: "Nom complet de l'artiste",
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'textfield',
                            key: 'cin',
                            label: 'Numéro CIN',
                            placeholder: 'Ex: 08745632',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'phoneNumber',
                            key: 'tel',
                            label: 'Téléphone',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'select',
                            key: 'langue',
                            label: "Langue de l'attestation",
                            data: {
                                values: [{
                                    label: 'Français',
                                    value: 'fr'
                                }, {
                                    label: 'Arabe',
                                    value: 'ar'
                                }]
                            },
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'htmlelement',
                            key: 'lbl2',
                            tag: 'h3',
                            content: '<h3 style="font-size:14px;font-weight:800;color:var(--text,#f0f0f0);margin:16px 0 8px;padding:8px 12px;background:rgba(201,168,76,0.08);border-left:3px solid #c9a84c;border-radius:4px;">📄 Documents</h3>'
                        },
                        {
                            type: 'file',
                            key: 'carte_pro',
                            label: 'Copie de la carte professionnelle',
                            storage: 'base64',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'signature',
                            key: 'esign',
                            label: 'Signature de l\'artiste',
                            validate: {
                                required: true
                            }
                        },
                    ]
                },
                carte_pro: {
                    display: 'form',
                    components: [{
                            type: 'htmlelement',
                            key: 'lbl1',
                            tag: 'h3',
                            content: '<h3 style="font-size:14px;font-weight:800;color:var(--text,#f0f0f0);margin:0 0 12px;padding:8px 12px;background:rgba(201,168,76,0.08);border-left:3px solid #c9a84c;border-radius:4px;">👤 Identité</h3>'
                        },
                        {
                            type: 'textfield',
                            key: 'nom',
                            label: 'Nom complet',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'textfield',
                            key: 'prenom',
                            label: 'Prénom',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'textfield',
                            key: 'cin',
                            label: 'Numéro CIN',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'datetime',
                            key: 'dob',
                            label: 'Date de naissance',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'email',
                            key: 'email',
                            label: 'Email',
                            validate: {
                                required: false
                            }
                        },
                        {
                            type: 'select',
                            key: 'type_artiste',
                            label: "Type d'artiste",
                            data: {
                                values: [{
                                    label: 'Musicien',
                                    value: 'musicien'
                                }, {
                                    label: 'Danseur',
                                    value: 'danseur'
                                }, {
                                    label: 'Instrumentiste',
                                    value: 'instrumentiste'
                                }]
                            },
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'textfield',
                            key: 'specialite',
                            label: 'Spécialité artistique',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'htmlelement',
                            key: 'lbl2',
                            tag: 'h3',
                            content: '<h3 style="font-size:14px;font-weight:800;color:var(--text,#f0f0f0);margin:16px 0 8px;padding:8px 12px;background:rgba(201,168,76,0.08);border-left:3px solid #c9a84c;border-radius:4px;">📄 Pièces justificatives</h3>'
                        },
                        {
                            type: 'file',
                            key: 'diplome',
                            label: 'Diplôme artistique',
                            storage: 'base64',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'file',
                            key: 'copie_cin',
                            label: 'Copie CNI (recto/verso)',
                            storage: 'base64',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'file',
                            key: 'b3',
                            label: 'Extrait casier judiciaire B3',
                            storage: 'base64',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'textfield',
                            key: 'cnss',
                            label: 'Numéro CNSS',
                            validate: {
                                required: false
                            }
                        },
                        {
                            type: 'checkbox',
                            key: 'certif',
                            label: 'Je certifie l\'exactitude des informations fournies',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'signature',
                            key: 'esign',
                            label: 'Signature de l\'artiste',
                            validate: {
                                required: true
                            }
                        },
                    ]
                },
                pret_fnap: {
                    display: 'form',
                    components: [{
                            type: 'htmlelement',
                            key: 'lbl1',
                            tag: 'h3',
                            content: '<h3 style="font-size:14px;font-weight:800;color:var(--text,#f0f0f0);margin:0 0 12px;padding:8px 12px;background:rgba(96,165,250,0.08);border-left:3px solid #60a5fa;border-radius:4px;">🏛️ Institution emprunteuse</h3>'
                        },
                        {
                            type: 'textfield',
                            key: 'institution',
                            label: 'Nom de l\'institution',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'email',
                            key: 'email',
                            label: 'Email de contact',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'textarea',
                            key: 'description',
                            label: 'Description du projet d\'exposition',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'htmlelement',
                            key: 'lbl2',
                            tag: 'h3',
                            content: '<h3 style="font-size:14px;font-weight:800;color:var(--text,#f0f0f0);margin:16px 0 8px;padding:8px 12px;background:rgba(96,165,250,0.08);border-left:3px solid #60a5fa;border-radius:4px;">🖼️ Œuvres demandées</h3>'
                        },
                        {
                            type: 'textarea',
                            key: 'liste_oeuvres',
                            label: 'Liste des œuvres demandées (références)',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'datetime',
                            key: 'date_debut',
                            label: 'Date de début du prêt',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'datetime',
                            key: 'date_fin',
                            label: 'Date de fin du prêt',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'htmlelement',
                            key: 'lbl3',
                            tag: 'h3',
                            content: '<h3 style="font-size:14px;font-weight:800;color:var(--text,#f0f0f0);margin:16px 0 8px;padding:8px 12px;background:rgba(96,165,250,0.08);border-left:3px solid #60a5fa;border-radius:4px;">📎 Documents</h3>'
                        },
                        {
                            type: 'file',
                            key: 'convention',
                            label: 'Convention de prêt pré-remplie',
                            storage: 'base64',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'file',
                            key: 'assurance',
                            label: 'Certificat d\'assurance tous risques',
                            storage: 'base64',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'signature',
                            key: 'esign',
                            label: 'Signature du responsable',
                            validate: {
                                required: true
                            }
                        },
                    ]
                },
                etranger: {
                    display: 'form',
                    components: [{
                            type: 'textfield',
                            key: 'nom',
                            label: 'Nom complet',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'textfield',
                            key: 'nationalite',
                            label: 'Nationalité',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'textfield',
                            key: 'passeport',
                            label: 'Numéro de passeport',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'textfield',
                            key: 'specialite',
                            label: 'Spécialité artistique',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'file',
                            key: 'passeport_file',
                            label: 'Copie du passeport',
                            storage: 'base64',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'file',
                            key: 'visa',
                            label: 'Visa ou titre de séjour',
                            storage: 'base64',
                            validate: {
                                required: false
                            }
                        },
                        {
                            type: 'signature',
                            key: 'esign',
                            label: 'Signature',
                            validate: {
                                required: true
                            }
                        },
                    ]
                },
                cnss: {
                    display: 'form',
                    components: [{
                            type: 'textfield',
                            key: 'nom',
                            label: 'Nom complet',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'textfield',
                            key: 'cin',
                            label: 'Numéro CIN',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'textfield',
                            key: 'cnss',
                            label: 'Numéro CNSS',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'phoneNumber',
                            key: 'tel',
                            label: 'Téléphone',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'checkbox',
                            key: 'certif',
                            label: 'Je certifie l\'exactitude des informations',
                            validate: {
                                required: true
                            }
                        },
                        {
                            type: 'signature',
                            key: 'esign',
                            label: 'Signature',
                            validate: {
                                required: true
                            }
                        },
                    ]
                }
            };

            const schema = templates[tpl];
            if (!schema) return;
            if (_builder) _builder.setForm(schema);
            _schema = schema;
            _updateStats(schema.components.length);
            _showToast(`✅ Modèle "${tpl}" chargé — ${schema.components.length} champs`, 'success');
        };

        // ── Internal: add component to schema ──
        window._addComponent = function(comp) {
            if (!_builder) {
                _showToast('Builder non prêt', 'error');
                return;
            }
            const comps = [...(_schema.components || []), comp];
            const newSchema = {
                ..._schema,
                components: comps
            };
            _builder.setForm(newSchema);
            _schema = newSchema;
            _updateStats(comps.length);
        };

        // ── fbbIaAddField (also used by IA suggestions) ──
        window.fbbIaAddField = function(type, label, key) {
            if (!_builder) return _showToast('Builder non prêt', 'error');
            const comp = {
                type,
                key: key + '_' + Date.now(),
                label,
                validate: {
                    required: false
                }
            };
            if (type === 'file') comp.storage = 'base64';
            _addComponent(comp);
            _showToast(`✅ "${label}" ajouté`, 'success');
        };

        // ── Undo / Redo ──
        window.fbbUndo = () => {
            if (!_history.length) return _showToast('Aucune action à annuler', 'info');
            _redo.push(JSON.stringify(_schema));
            _schema = JSON.parse(_history.pop());
            if (_builder) _builder.setForm(_schema);
            _updateStats(_schema.components ? _schema.components.length : 0);
            _showToast('↩ Annulation', 'info');
        };
        window.fbbRedo = () => {
            if (!_redo.length) return _showToast('Aucune action à rétablir', 'info');
            _history.push(JSON.stringify(_schema));
            _schema = JSON.parse(_redo.pop());
            if (_builder) _builder.setForm(_schema);
            _updateStats(_schema.components ? _schema.components.length : 0);
            _showToast('↪ Rétablissement', 'info');
        };

        // ── Save / New ──

        window.fbbNewForm = () => {
            const empty = {
                display: 'form',
                components: []
            };
            if (_builder) _builder.setForm(empty);
            _schema = empty;
            document.getElementById('fbbFormName').value = 'Nouveau formulaire';
            document.getElementById('fbbStatus').textContent = 'Brouillon';
            document.getElementById('fbbStatus').className = 'fbb-badge fbb-badge-blue';
            _updateStats(0);
            fbbCloseForms();
            _showToast('✅ Nouveau formulaire créé', 'success');
        };

        // ── JSON ──
        window.fbbToggleJson = () => {
            const p = document.getElementById('fbbJsonPanel');
            const show = p.style.display === 'none';
            p.style.display = show ? '' : 'none';
            if (show) document.getElementById('fbbJsonArea').value = JSON.stringify(_schema, null, 2);
        };
        window.fbbCopyJson = () => {
            navigator.clipboard.writeText(JSON.stringify(_schema, null, 2)).then(() => _showToast('📋 JSON copié',
                'success'));
        };
        window.fbbImportJson = () => {
            try {
                const s = JSON.parse(document.getElementById('fbbJsonArea').value);
                if (_builder) {
                    _builder.setForm(s);
                    _schema = s;
                    _updateStats(s.components ? s.components.length : 0);
                }
                _showToast('✅ Schéma importé', 'success');
            } catch (e) {
                _showToast('❌ JSON invalide', 'error');
            }
        };

        // ── Preview ──
        window.fbbPreview = () => {
            document.getElementById('fbbPreviewFormName').textContent = document.getElementById('fbbFormName').value;
            _openModal('fbbPreviewModal', 'fbbPreviewOverlay');
            const el = document.getElementById('fbbPreviewContent');
            el.innerHTML = '<div style="padding:40px;text-align:center;color:var(--fbb-text3);">Chargement…</div>';
            Formio.createForm(el, _schema).catch(() => {
                el.innerHTML =
                    '<p style="padding:20px;color:var(--fbb-text3);">Erreur de prévisualisation.</p>';
            });
        };
        window.fbbClosePreview = () => _closeModal('fbbPreviewModal', 'fbbPreviewOverlay');
        window.fbbSetDevice = (el, w) => {
            document.querySelectorAll('.fbb-device').forEach(b => b.classList.remove('active'));
            el.classList.add('active');
            document.getElementById('fbbPreviewFrame').style.maxWidth = w;
        };

        // ── Publish ──
        window.fbbPublish = () => {
    // Get the selected department name
    const deptSelect = document.getElementById('fbbDepartment');
    const selectedOption = deptSelect.options[deptSelect.selectedIndex];
    const deptName = selectedOption ? selectedOption.text : 'Aucun département sélectionné';

    // Display it in the publish modal
    document.getElementById('fbbPubDeptDisplay').value = deptName;

    _openModal('fbbPublishModal', 'fbbPublishOverlay');
};
        window.fbbClosePublish = () => _closeModal('fbbPublishModal', 'fbbPublishOverlay');


        // ── Formulaires ──

        window.fbbCloseForms = () => _closeModal('fbbFormsModal', 'fbbFormsOverlay');
        window._renderFormsGrid = function(forms) {
            const html = forms.map(f => `
        <div class="fbb-form-card" onclick="fbbLoadForm(${JSON.stringify(f).replace(/"/g,'&quot;')})">
            <div class="fbb-form-card-top">
                <span style="font-size:18px;">📋</span>
                <span class="fbb-badge ${f.status==='Publié'?'fbb-badge-green':f.status==='Archivé'?'fbb-badge-amber':'fbb-badge-blue'}">${f.status}</span>
            </div>
            <div class="fbb-form-card-name">${f.name}</div>
            <div class="fbb-form-card-key">${f.key}</div>
            <div class="fbb-form-card-stats"><span>📬 ${f.sub}</span><span>🧩 ${f.fields}</span></div>
            <div class="fbb-form-card-bar">
                <div class="fbb-form-card-bar-label"><span>Complétude</span><span style="color:var(--fbb-gold)">${f.pct}%</span></div>
                <div class="fbb-form-card-bar-track"><div class="fbb-form-card-bar-fill" style="width:${f.pct}%"></div></div>
            </div>
        </div>`).join('');
            document.getElementById('fbbFormsGrid').innerHTML = html ||
                '<div style="padding:40px;text-align:center;color:var(--fbb-text3);">Aucun formulaire trouvé</div>';
        };

        window.fbbFilterForms = q => {
            const ql = q.toLowerCase();
            _filteredForms = _forms.filter(f => f.name.toLowerCase().includes(ql) || f.key.includes(ql));
            _renderFormsGrid(_filteredForms);
        };
        window.fbbFilterByStatus = s => {
            _filteredForms = s ? _forms.filter(f => f.status === s) : [..._forms];
            _renderFormsGrid(_filteredForms);
        };

        // ── IA ──
        window.fbbOpenIa = () => _openModal('fbbIaModal', 'fbbIaOverlay');
        window.fbbCloseIa = () => _closeModal('fbbIaModal', 'fbbIaOverlay');
        window.fbbTab = (el, panelId) => {
            document.querySelectorAll('.fbb-tab').forEach(t => t.classList.remove('active'));
            el.classList.add('active');
            document.querySelectorAll('.fbb-tab-panel').forEach(p => p.style.display = 'none');
            document.getElementById(panelId).style.display = '';
            _activeTab = panelId;
            const btn = document.getElementById('fbbIaPrimaryBtn');
            if (btn) {
                const labels = {
                    iaGen: '✨ Générer',
                    iaAnalyze: '🔍 Analyser',
                    iaSuggest: '💡 Appliquer',
                    iaTranslate: '🌐 Traduire'
                };
                btn.textContent = labels[panelId] || '✨ Générer';
            }
        };
        window.fbbIaPrimary = () => {
            if (_activeTab === 'iaGen') fbbIaGenerate();
            else if (_activeTab === 'iaTranslate') fbbIaTranslate();
            else fbbCloseIa();
        };
        window.fbbIaGenerate = () => {
            const comps = [{
                    type: 'textfield',
                    key: 'nom',
                    label: "Nom complet de l'artiste",
                    validate: {
                        required: true
                    }
                },
                {
                    type: 'textfield',
                    key: 'cin',
                    label: 'Numéro CIN',
                    validate: {
                        required: true
                    }
                },
                {
                    type: 'phoneNumber',
                    key: 'tel',
                    label: 'Téléphone',
                    validate: {
                        required: true
                    }
                },
                {
                    type: 'email',
                    key: 'email',
                    label: 'Email',
                    validate: {
                        required: true
                    }
                },
            ];
            if (document.getElementById('iaInclFile')?.checked)
                comps.push({
                    type: 'file',
                    key: 'piece',
                    label: 'Pièce justificative',
                    storage: 'base64'
                });
            if (document.getElementById('iaInclSig')?.checked)
                comps.push({
                    type: 'signature',
                    key: 'esign',
                    label: 'Signature électronique',
                    validate: {
                        required: true
                    }
                });
            const newSchema = {
                display: 'form',
                components: comps
            };
            if (_builder) _builder.setForm(newSchema);
            _schema = newSchema;
            _updateStats(comps.length);
            fbbCloseIa();
            _showToast('✨ Formulaire généré par IA !', 'success');
        };
        window.fbbIaTranslate = () => {
            fbbCloseIa();
            _showToast('🌐 Traduction en cours…', 'info');
            setTimeout(() => _showToast('✅ Traduction terminée', 'success'), 1800);
        };

        // ── Modal helpers ──
        window._openModal = (m, o) => {
            document.getElementById(m).classList.add('open');
            document.getElementById(o).classList.add('open');
            document.body.style.overflow = 'hidden';
        };
        window._closeModal = (m, o) => {
            document.getElementById(m).classList.remove('open');
            document.getElementById(o).classList.remove('open');
            document.body.style.overflow = '';
        };

        // ── Toast ──
        window._showToast = function(msg, type = 'info') {
            if (typeof showToast === 'function') {
                showToast(msg, type);
                return;
            }
            const el = document.createElement('div');
            el.style.cssText =
                `position:fixed;bottom:24px;right:24px;z-index:99999;background:var(--bg3,#181b1f);border:1px solid var(--border,rgba(255,255,255,.07));color:var(--text,#f0f0f0);padding:12px 18px;border-radius:8px;font-size:13px;box-shadow:0 10px 30px rgba(0,0,0,0.6);`;
            el.textContent = msg;
            document.body.appendChild(el);
            setTimeout(() => el.remove(), 3500);
        };

        // ── IA tip rotation ──
        const IA_TIPS = [
            '🤖 <strong>IA :</strong> Ajoutez un champ <strong>IBAN</strong> — présent dans 84 % des formulaires similaires.',
            '🤖 <strong>IA :</strong> La <strong>signature électronique</strong> est requise pour les documents officiels.',
            '🤖 <strong>IA :</strong> Utilisez des <strong>sections (H3)</strong> pour mieux organiser vos formulaires.',
            '🤖 <strong>IA :</strong> Le panneau Quick-Add à droite vous permet d\'ajouter tout sans glisser.',
        ];
        let _tipIdx = 0;
        window._rotateIaTip = function() {
            setInterval(() => {
                _tipIdx = (_tipIdx + 1) % IA_TIPS.length;
                const el = document.getElementById('fbbIaTxt');
                if (el) el.innerHTML = IA_TIPS[_tipIdx];
            }, 7000);
        };

        // ── Keyboard shortcuts ──
        document.addEventListener('keydown', e => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'z') {
                e.preventDefault();
                fbbUndo();
            }
            if ((e.ctrlKey || e.metaKey) && e.key === 'y') {
                e.preventDefault();
                fbbRedo();
            }
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                fbbSave();
            }
        });
    </script>
    {{-- ═══════════════════════════════════════════════════════════
     PASTE THIS <script> BLOCK at the very bottom of your blade,
     just before @endsection — it REPLACES the existing
     fbbSave / fbbConfirmPublish / fbbShowForms / fbbLoadForm
     ═══════════════════════════════════════════════════════════ --}}

    <script>
        // ── helpers ─────────────────────────────────────────────────
        const _csrf = () =>
            document.querySelector('meta[name="csrf-token"]')?.content ?? '';

        // ── auto-save every 60 s ────────────────────────────────────
        setInterval(() => {
            if (_schema?.components?.length) fbbSave(true);
        }, 60000);

        // ════════════════════════════════════════════════════════════
        //  SAVE  →  POST /admin/form-builder/save
        //  Returns the saved form id so publish can use it.
        // ════════════════════════════════════════════════════════════
       window.fbbSave = async function(silent = false) {
    const titre = document.getElementById('fbbFormName').value.trim();

    if (!titre) {
        _showToast('⚠ Donnez un titre au formulaire', 'error');
        return null;
    }
    if (!_schema?.components?.length) {
        _showToast('⚠ Ajoutez au moins un champ avant de sauvegarder', 'error');
        return null;
    }

    const badge = document.getElementById('fbbAutoSaveBadge');
    if (badge) badge.style.display = '';

    const rawId = document.getElementById('fbbFormId')?.value ?? '';
    const formId = rawId !== '' ? parseInt(rawId, 10) : undefined;

    // Get the selected workflow option's ID (not just the key)
const workflowSelect = document.getElementById('fbbWorkflow');
const selectedWorkflowId = workflowSelect.value
    ? (workflowSelect.options[workflowSelect.selectedIndex]?.getAttribute('data-workflow-id') || null)
    : null;
    try {
        const res = await fetch('/admin/form-builder/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': _csrf(),
                'Accept': 'application/json',
            },
body: JSON.stringify({
    ...(formId ? { id: formId } : {}),
    titre,
    version: '1.0',
    department_id: document.getElementById('fbbDepartment').value || null,
    workflow_id: selectedWorkflowId,
    schema_formio: _schema,
    validity_months: document.getElementById('fbbValidityMonths').value
        ? parseInt(document.getElementById('fbbValidityMonths').value, 10)
        : null,
    max_submissions: document.getElementById('fbbMaxSubmissions').value   // NEW
        ? parseInt(document.getElementById('fbbMaxSubmissions').value, 10)
        : null,
}),

        });

        const json = await res.json();

        if (json.success) {
            if (json.id) {
                document.getElementById('fbbFormId').value = json.id;
            }
            if (!silent) _showToast('💾 Formulaire sauvegardé — ' + json.last_saved, 'success');
            setTimeout(() => {
                if (badge) badge.style.display = 'none';
            }, 3000);
            return json.id;
        } else {
            const msg = json.errors ?
                Object.values(json.errors).flat().join(' | ') :
                (json.message ?? 'Sauvegarde échouée');
            _showToast('❌ ' + msg, 'error');
            return null;
        }

    } catch (e) {
        console.error('fbbSave network error:', e);
        _showToast('❌ Erreur réseau lors de la sauvegarde', 'error');
        return null;
    }
};

        // ════════════════════════════════════════════════════════════
        //  PUBLISH  →  saves first, then POST /admin/form-builder/{id}/publish
        // ════════════════════════════════════════════════════════════
        window.fbbConfirmPublish = async function() {
            fbbClosePublish();

            // Step 1 — always save first
            _showToast('💾 Sauvegarde en cours…', 'info');
            const savedId = await fbbSave(true); // silent save, returns id or null

            if (!savedId) {
                _showToast('❌ Impossible de publier : la sauvegarde a échoué.', 'error');
                return;
            }

            // Step 2 — publish
            try {
                const res = await fetch(`/admin/form-builder/${savedId}/publish`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': _csrf(),
                        'Accept': 'application/json',
                    },
                });

                const json = await res.json();

                if (json.success) {
                    document.getElementById('fbbStatus').textContent = 'Publié';
                    document.getElementById('fbbStatus').className = 'fbb-badge fbb-badge-green';
                    _showToast('🚀 Formulaire publié avec succès !', 'success');
                } else {
                    _showToast('❌ ' + (json.message ?? 'Publication échouée'), 'error');
                }

            } catch (e) {
                console.error('fbbConfirmPublish network error:', e);
                _showToast('❌ Erreur réseau lors de la publication', 'error');
            }
        };

        // ════════════════════════════════════════════════════════════
        //  LOAD FORMS LIST  →  GET /admin/form-builder/list
        // ════════════════════════════════════════════════════════════
        async function _loadForms() {
            try {
                const res = await fetch('/admin/form-builder/list', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const forms = await res.json();
                _forms = forms;
                _filteredForms = [...forms];
                window._renderFormsGrid(_filteredForms);
            } catch (e) {
                console.error('Impossible de charger les formulaires', e);
            }
        }

        window.fbbShowForms = () => {
            _openModal('fbbFormsModal', 'fbbFormsOverlay');
            _loadForms();
        };

        // ════════════════════════════════════════════════════════════
        //  LOAD WORKFLOWS  →  GET /api/workflows/deployed
        //  Populates #fbbWorkflow with workflows deployed via the modeler
        // ════════════════════════════════════════════════════════════
     async function _loadWorkflows() {
    const sel = document.getElementById('fbbWorkflow');
    try {
        const res = await fetch('/api/workflows/deployed', {
            headers: { 'Accept': 'application/json' }
        });
        const workflows = await res.json();

        sel.innerHTML = '<option value="">-- Sélectionner un workflow --</option>';
        workflows.forEach(wf => {
            const opt = document.createElement('option');
            opt.value = wf.process_key;
            opt.setAttribute('data-workflow-id', wf.id); // Store the actual workflow ID
            opt.textContent = wf.name + ' (' + wf.process_key + ')';
            sel.appendChild(opt);
        });

        // Restore pending selection
        if (sel.dataset.pendingId) {
            // Find option with matching workflow ID
            for (let i = 0; i < sel.options.length; i++) {
                if (sel.options[i].getAttribute('data-workflow-id') === sel.dataset.pendingId) {
                    sel.value = sel.options[i].value;
                    break;
                }
            }
            delete sel.dataset.pendingId;
        }

        if (workflows.length === 0) {
            sel.innerHTML = '<option value="">⚠ Aucun workflow déployé</option>';
        }
    } catch (e) {
        console.warn('Impossible de charger les workflows:', e);
        sel.innerHTML = '<option value="">❌ Erreur de chargement</option>';
    }
}
        // ── Boot: wait for Form.io then init everything ──────────────
        (function boot() {
            if (typeof Formio !== 'undefined' && typeof Formio.builder === 'function') {
                _initBuilder();
                _rotateIaTip();
                _renderFormsGrid(_forms);
            } else {
                setTimeout(boot, 150);
            }
        })();

        // Load dynamic workflow list on page load
        _loadWorkflows();

        // ════════════════════════════════════════════════════════════
        //  LOAD A FORM INTO THE BUILDER
        // ════════════════════════════════════════════════════════════
       window.fbbLoadForm = function(f) {
    document.getElementById('fbbFormName').value = f.name;
    document.getElementById('fbbFormId').value = f.id;
    document.getElementById('fbbValidityMonths').value = f.validity_months ?? '';
    document.getElementById('fbbMaxSubmissions').value = f.max_submissions ?? '';

    // Restore department selector
   if (f.department_id) {
    document.getElementById('fbbDepartment').value = f.department_id;
} else {
        document.getElementById('fbbDepartment').value = '';
    }

    // Restore workflow selector using workflow_id
    if (f.workflow_id) {
        const sel = document.getElementById('fbbWorkflow');
        // Find option with matching data-workflow-id
        let found = false;
        for (let i = 0; i < sel.options.length; i++) {
            if (sel.options[i].getAttribute('data-workflow-id') == f.workflow_id) {
                sel.value = sel.options[i].value;
                found = true;
                break;
            }
        }
        if (!found) {
            sel.dataset.pendingId = f.workflow_id;
        }
    } else {
        document.getElementById('fbbWorkflow').value = '';
    }

    const statusEl = document.getElementById('fbbStatus');
    statusEl.textContent = f.statut;
    statusEl.className = 'fbb-badge ' + (
        f.statut === 'Publié' ? 'fbb-badge-green' :
        f.statut === 'Archivé' ? 'fbb-badge-amber' : 'fbb-badge-blue'
    );

    if (f.schema_formio && _builder) {
        _builder.setForm(f.schema_formio);
        _schema = f.schema_formio;
        _updateStats(f.schema_formio.components?.length ?? 0);
    }

    fbbCloseForms();
    _showToast(`📋 "${f.titre || f.name}" chargé`, 'success');
};
    </script>

@endsection
