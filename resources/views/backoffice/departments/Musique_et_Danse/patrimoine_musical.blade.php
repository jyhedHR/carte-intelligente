@extends('shared.layouts.backoffice')

@section('content')

<!-- Page Header -->
<div class="section-head">
    <div>
        <h1 class="section-title">🎵 Base de Données du Patrimoine Musical</h1>
        <p class="section-sub">Protéger et organiser le patrimoine musical tunisien - Catalogue, archivage audio/vidéo</p>
    </div>
    <button class="btn btn-gold" onclick="showModal('addHeritageModal')">+ Ajouter Patrimoine</button>
</div>

<!-- KPI Cards -->
<div class="kpi-grid">
    <div class="kpi-card gold">
        <div class="kpi-icon">🎼</div>
        <div class="kpi-value">5,234</div>
        <div class="kpi-label">Chansons Cataloguées</div>
        <div class="kpi-delta up">↑ 128 ce mois</div>
    </div>
    <div class="kpi-card teal">
        <div class="kpi-icon">🎸</div>
        <div class="kpi-value">847</div>
        <div class="kpi-label">Instruments Traditionn.</div>
        <div class="kpi-delta up">↑ 12 nouveaux</div>
    </div>
    <div class="kpi-card purple">
        <div class="kpi-icon">📹</div>
        <div class="kpi-value">1,543</div>
        <div class="kpi-label">Vidéos Archivées</div>
        <div class="kpi-delta up">↑ 45 vidéos</div>
    </div>
    <div class="kpi-card green">
        <div class="kpi-icon">🗺️</div>
        <div class="kpi-value">24</div>
        <div class="kpi-label">Régions Couvertes</div>
        <div class="kpi-delta flat">→ Toute la Tunisie</div>
    </div>
</div>

<!-- Navigation Tabs -->
<div style="display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 0;">
    <button class="tab-btn active" onclick="switchTab('tab-songs')" style="padding: 12px 16px; border: none; background: none; color: var(--text); font-weight: 600; font-size: 13px; cursor: pointer; border-bottom: 2px solid var(--gold);">Chansons</button>
    <button class="tab-btn" onclick="switchTab('tab-styles')" style="padding: 12px 16px; border: none; background: none; color: var(--text2); font-weight: 600; font-size: 13px; cursor: pointer;">Styles Musicaux</button>
    <button class="tab-btn" onclick="switchTab('tab-instruments')" style="padding: 12px 16px; border: none; background: none; color: var(--text2); font-weight: 600; font-size: 13px; cursor: pointer;">Instruments</button>
    <button class="tab-btn" onclick="switchTab('tab-map')" style="padding: 12px 16px; border: none; background: none; color: var(--text2); font-weight: 600; font-size: 13px; cursor: pointer;">Carte Régionale</button>
    <button class="tab-btn" onclick="switchTab('tab-archive')" style="padding: 12px 16px; border: none; background: none; color: var(--text2); font-weight: 600; font-size: 13px; cursor: pointer;">Archivage A/V</button>
</div>

<!-- Filters -->
<div class="panel" style="margin-bottom: 20px;">
    <div class="panel-body" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <div class="topbar-search" style="width: 280px;">
            <span class="topbar-search-icon">🔍</span>
            <input type="text" placeholder="Rechercher chanson, style ou instrument...">
        </div>
        <select style="padding: 6px 10px; background: var(--bg3); border: 1px solid var(--border); border-radius: 6px; color: var(--text); cursor: pointer;">
            <option>Tous les styles</option>
            <option>Musique Arabe Classique</option>
            <option>Malouf</option>
            <option>Danse Folklorique</option>
            <option>Musique Gnaoua</option>
        </select>
        <select style="padding: 6px 10px; background: var(--bg3); border: 1px solid var(--border); border-radius: 6px; color: var(--text); cursor: pointer;">
            <option>Toutes les régions</option>
            <option>Tunis</option>
            <option>Sfax</option>
            <option>Sousse</option>
            <option>Kairouan</option>
        </select>
    </div>
</div>

<!-- Chansons Tab -->
<div id="tab-songs" class="tab-content">
    <div class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">🎼 Catalogue de Chansons</div>
                <div class="panel-sub">5,234 chansons cataloguées</div>
            </div>
        </div>
        <div class="panel-body no-pad">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Artiste/Compositeur</th>
                            <th>Style</th>
                            <th>Région</th>
                            <th>Année</th>
                            <th>Détenteur Patrimoine</th>
                            <th>Archivé</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Khotwa Khotwa</strong></td>
                            <td>Ahmed Ben Ali</td>
                            <td>Musique Arabe Classique</td>
                            <td>Tunis</td>
                            <td>1995</td>
                            <td>Ahmed Ben Ali</td>
                            <td><span class="badge green">✓</span></td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-sm btn-ghost" title="Écouter">🔊</button>
                                    <button class="btn btn-sm btn-ghost" title="Détails">👁️</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Sebeaa Sahara</strong></td>
                            <td>Fatima Kaddour</td>
                            <td>Danse Folklorique</td>
                            <td>Sfax</td>
                            <td>2001</td>
                            <td>Fatima Kaddour</td>
                            <td><span class="badge green">✓</span></td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-sm btn-ghost" title="Écouter">🔊</button>
                                    <button class="btn btn-sm btn-ghost" title="Détails">👁️</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Taouz Janoub</strong></td>
                            <td>Mohamed Saïd</td>
                            <td>Musique Traditionnelle</td>
                            <td>Kairouan</td>
                            <td>1988</td>
                            <td>Mohamed Saïd</td>
                            <td><span class="badge green">✓</span></td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-sm btn-ghost" title="Écouter">🔊</button>
                                    <button class="btn btn-sm btn-ghost" title="Détails">👁️</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Styles Musicaux Tab -->
<div id="tab-styles" class="tab-content" style="display: none;">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">🎵 Styles Musicaux</div>
        </div>
        <div class="panel-body">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                <div style="background: var(--bg3); padding: 16px; border-radius: 6px; border-left: 3px solid var(--gold);">
                    <div style="font-weight: 600; font-size: 13px; color: var(--text); margin-bottom: 8px;">🎼 Musique Arabe Classique</div>
                    <div style="font-size: 11px; color: var(--text2); margin-bottom: 10px;">Style mélodique sophistiqué basé sur des maqams</div>
                    <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                        <span style="background: var(--bg4); padding: 2px 6px; border-radius: 3px; font-size: 10px;">1,247 chansons</span>
                        <span style="background: var(--bg4); padding: 2px 6px; border-radius: 3px; font-size: 10px;">18 artistes</span>
                    </div>
                    <button class="btn btn-sm btn-outline" style="width: 100%;">Voir Détails</button>
                </div>

                <div style="background: var(--bg3); padding: 16px; border-radius: 6px; border-left: 3px solid var(--teal);">
                    <div style="font-weight: 600; font-size: 13px; color: var(--text); margin-bottom: 8px;">🕺 Danse Folklorique</div>
                    <div style="font-size: 11px; color: var(--text2); margin-bottom: 10px;">Danses traditionnelles par région</div>
                    <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                        <span style="background: var(--bg4); padding: 2px 6px; border-radius: 3px; font-size: 10px;">856 chansons</span>
                        <span style="background: var(--bg4); padding: 2px 6px; border-radius: 3px; font-size: 10px;">12 artistes</span>
                    </div>
                    <button class="btn btn-sm btn-outline" style="width: 100%;">Voir Détails</button>
                </div>

                <div style="background: var(--bg3); padding: 16px; border-radius: 6px; border-left: 3px solid var(--purple);">
                    <div style="font-weight: 600; font-size: 13px; color: var(--text); margin-bottom: 8px;">🎸 Musique Malouf</div>
                    <div style="font-size: 11px; color: var(--text2); margin-bottom: 10px;">Tradition musicale du Sahel tunisien</div>
                    <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                        <span style="background: var(--bg4); padding: 2px 6px; border-radius: 3px; font-size: 10px;">742 chansons</span>
                        <span style="background: var(--bg4); padding: 2px 6px; border-radius: 3px; font-size: 10px;">8 artistes</span>
                    </div>
                    <button class="btn btn-sm btn-outline" style="width: 100%;">Voir Détails</button>
                </div>

                <div style="background: var(--bg3); padding: 16px; border-radius: 6px; border-left: 3px solid var(--green);">
                    <div style="font-weight: 600; font-size: 13px; color: var(--text); margin-bottom: 8px;">🎺 Musique Gnaoua</div>
                    <div style="font-size: 11px; color: var(--text2); margin-bottom: 10px;">Traditions mystiques du sud tunisien</div>
                    <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                        <span style="background: var(--bg4); padding: 2px 6px; border-radius: 3px; font-size: 10px;">389 chansons</span>
                        <span style="background: var(--bg4); padding: 2px 6px; border-radius: 3px; font-size: 10px;">6 artistes</span>
                    </div>
                    <button class="btn btn-sm btn-outline" style="width: 100%;">Voir Détails</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Instruments Tab -->
<div id="tab-instruments" class="tab-content" style="display: none;">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">🎸 Instruments Traditionnels</div>
        </div>
        <div class="panel-body">
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px;">
                <div style="background: var(--bg3); padding: 14px; border-radius: 6px; text-align: center;">
                    <div style="font-size: 28px; margin-bottom: 8px;">🎸</div>
                    <div style="font-weight: 600; font-size: 12px; color: var(--text); margin-bottom: 4px;">Oud</div>
                    <div style="font-size: 10px; color: var(--text3);">142 références</div>
                </div>
                <div style="background: var(--bg3); padding: 14px; border-radius: 6px; text-align: center;">
                    <div style="font-size: 28px; margin-bottom: 8px;">🪘</div>
                    <div style="font-weight: 600; font-size: 12px; color: var(--text); margin-bottom: 4px;">Darbouka</div>
                    <div style="font-size: 10px; color: var(--text3);">89 références</div>
                </div>
                <div style="background: var(--bg3); padding: 14px; border-radius: 6px; text-align: center;">
                    <div style="font-size: 28px; margin-bottom: 8px;">🎺</div>
                    <div style="font-weight: 600; font-size: 12px; color: var(--text); margin-bottom: 4px;">Ney</div>
                    <div style="font-size: 10px; color: var(--text3);">67 références</div>
                </div>
                <div style="background: var(--bg3); padding: 14px; border-radius: 6px; text-align: center;">
                    <div style="font-size: 28px; margin-bottom: 8px;">🎹</div>
                    <div style="font-weight: 600; font-size: 12px; color: var(--text); margin-bottom: 4px;">Kanoun</div>
                    <div style="font-size: 10px; color: var(--text3);">54 références</div>
                </div>
                <div style="background: var(--bg3); padding: 14px; border-radius: 6px; text-align: center;">
                    <div style="font-size: 28px; margin-bottom: 8px;">🎻</div>
                    <div style="font-weight: 600; font-size: 12px; color: var(--text); margin-bottom: 4px;">Rebab</div>
                    <div style="font-size: 10px; color: var(--text3);">43 références</div>
                </div>
                <div style="background: var(--bg3); padding: 14px; border-radius: 6px; text-align: center;">
                    <div style="font-size: 28px; margin-bottom: 8px;">🪘</div>
                    <div style="font-weight: 600; font-size: 12px; color: var(--text); margin-bottom: 4px;">Bendire</div>
                    <div style="font-size: 10px; color: var(--text3);">31 références</div>
                </div>
                <div style="background: var(--bg3); padding: 14px; border-radius: 6px; text-align: center;">
                    <div style="font-size: 28px; margin-bottom: 8px;">🎺</div>
                    <div style="font-weight: 600; font-size: 12px; color: var(--text); margin-bottom: 4px;">Mizwed</div>
                    <div style="font-size: 10px; color: var(--text3);">28 références</div>
                </div>
                <div style="background: var(--bg3); padding: 14px; border-radius: 6px; text-align: center;">
                    <div style="font-size: 28px; margin-bottom: 8px;">🎵</div>
                    <div style="font-weight: 600; font-size: 12px; color: var(--text); margin-bottom: 4px;">+ 39 autres</div>
                    <div style="font-size: 10px; color: var(--text3);">Complètement documentés</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Carte Régionale Tab -->
<div id="tab-map" class="tab-content" style="display: none;">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">🗺️ Distribution Régionale</div>
        </div>
        <div class="panel-body">
            <div style="background: linear-gradient(135deg, var(--bg3), var(--bg4)); border: 1px solid var(--border); border-radius: 6px; padding: 40px 20px; text-align: center; min-height: 350px; display: flex; align-items: center; justify-content: center;">
                <div>
                    <div style="font-size: 36px; margin-bottom: 16px;">🗺️</div>
                    <div style="font-size: 12px; color: var(--text2); margin-bottom: 16px;">Carte interactive des styles musicaux par région</div>
                    <button class="btn btn-outline">🌍 Charger Carte Tunisie</button>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-top: 16px;">
                <div style="background: var(--gold-dim); padding: 12px; border-radius: 6px; border-left: 3px solid var(--gold);">
                    <div style="font-weight: 600; font-size: 12px; color: var(--gold2); margin-bottom: 4px;">🏛️ Tunis</div>
                    <div style="font-size: 10px; color: var(--text2);">1,247 chansons | 28 artistes</div>
                </div>
                <div style="background: var(--teal-dim); padding: 12px; border-radius: 6px; border-left: 3px solid var(--teal);">
                    <div style="font-weight: 600; font-size: 12px; color: var(--teal); margin-bottom: 4px;">🌊 Sousse</div>
                    <div style="font-size: 10px; color: var(--text2);">856 chansons | 18 artistes</div>
                </div>
                <div style="background: var(--purple-dim); padding: 12px; border-radius: 6px; border-left: 3px solid var(--purple);">
                    <div style="font-weight: 600; font-size: 12px; color: var(--purple); margin-bottom: 4px;">🏜️ Sfax</div>
                    <div style="font-size: 10px; color: var(--text2);">1,156 chansons | 32 artistes</div>
                </div>
                <div style="background: var(--green-dim); padding: 12px; border-radius: 6px; border-left: 3px solid var(--green);">
                    <div style="font-weight: 600; font-size: 12px; color: var(--green); margin-bottom: 4px;">🗻 Kairouan</div>
                    <div style="font-size: 10px; color: var(--text2);">742 chansons | 15 artistes</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Archivage A/V Tab -->
<div id="tab-archive" class="tab-content" style="display: none;">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">📹 Archivage Audio & Vidéo</div>
        </div>
        <div class="panel-body no-pad">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Type</th>
                            <th>Artiste</th>
                            <th>Format</th>
                            <th>Durée</th>
                            <th>Date Archivage</th>
                            <th>Qualité</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Khotwa Khotwa</strong></td>
                            <td>Audio</td>
                            <td>Ahmed Ben Ali</td>
                            <td>MP3 320kbps</td>
                            <td>4:32</td>
                            <td>12/04/2024</td>
                            <td><span class="badge green">HD</span></td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-sm btn-ghost" title="Écouter">🔊</button>
                                    <button class="btn btn-sm btn-ghost" title="Télécharger">⬇️</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Festival Sfax 2023</strong></td>
                            <td>Vidéo</td>
                            <td>Collectif</td>
                            <td>MP4 1080p</td>
                            <td>45:20</td>
                            <td>08/03/2024</td>
                            <td><span class="badge green">Full HD</span></td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-sm btn-ghost" title="Voir">👁️</button>
                                    <button class="btn btn-sm btn-ghost" title="Télécharger">⬇️</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Chant Traditionnel Malouf</strong></td>
                            <td>Audio</td>
                            <td>Fatima Kaddour</td>
                            <td>FLAC Lossless</td>
                            <td>6:15</td>
                            <td>22/02/2024</td>
                            <td><span class="badge blue">Lossless</span></td>
                            <td>
                                <div class="row-actions">
                                    <button class="btn btn-sm btn-ghost" title="Écouter">🔊</button>
                                    <button class="btn btn-sm btn-ghost" title="Télécharger">⬇️</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques & Protégé Patrimoine -->
<div class="grid-2" style="margin-top: 24px;">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">📊 Couverture Patrimoine</div>
        </div>
        <div class="panel-body">
            <div class="process-row">
                <div class="process-name">Chansons Cataloguées</div>
                <div style="flex: 1;">
                    <div class="progress-bar">
                        <div class="progress-fill gold" style="width: 76%;"></div>
                    </div>
                </div>
                <div class="process-count">76%</div>
            </div>
            <div class="process-row">
                <div class="process-name">Archivage A/V</div>
                <div style="flex: 1;">
                    <div class="progress-bar">
                        <div class="progress-fill purple" style="width: 62%;"></div>
                    </div>
                </div>
                <div class="process-count">62%</div>
            </div>
            <div class="process-row">
                <div class="process-name">Métadonnées Complètes</div>
                <div style="flex: 1;">
                    <div class="progress-bar">
                        <div class="progress-fill teal" style="width: 89%;"></div>
                    </div>
                </div>
                <div class="process-count">89%</div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">🔒 Détenteurs du Patrimoine</div>
        </div>
        <div class="panel-body">
            <div class="feed-item">
                <div class="feed-dot gold"></div>
                <div style="flex: 1;">
                    <div class="feed-text"><strong>Ahmed Ben Ali</strong> - 142 chansons</div>
                </div>
                <div class="feed-time">Vérifié</div>
            </div>
            <div class="feed-item">
                <div class="feed-dot teal"></div>
                <div style="flex: 1;">
                    <div class="feed-text"><strong>Fatima Kaddour</strong> - 98 chansons</div>
                </div>
                <div class="feed-time">Vérifié</div>
            </div>
            <div class="feed-item">
                <div class="feed-dot purple"></div>
                <div style="flex: 1;">
                    <div class="feed-text"><strong>Mohamed Saïd</strong> - 87 chansons</div>
                </div>
                <div class="feed-time">Vérifié</div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Ajouter Patrimoine -->
<div id="addHeritageModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: var(--bg2); border: 1px solid var(--border); border-radius: var(--radius); width: 90%; max-width: 700px; max-height: 90vh; overflow-y: auto;">
        <div style="padding: 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h2 style="font-size: 16px; font-weight: 700; color: var(--text);">Ajouter au Patrimoine Musical</h2>
            <button onclick="closeModal('addHeritageModal')" style="background: none; border: none; color: var(--text3); cursor: pointer; font-size: 20px;">✕</button>
        </div>

        <form style="padding: 20px;">
            <!-- Type de Patrimoine -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text3); text-transform: uppercase; margin-bottom: 8px;">📋 Type de Patrimoine</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
                    <label style="display: flex; align-items: center; cursor: pointer; padding: 10px; background: var(--bg3); border: 1px solid var(--border); border-radius: 6px;">
                        <input type="radio" name="heritage_type" value="song" style="margin-right: 8px;">
                        <span style="font-size: 12px;">🎼 Chanson</span>
                    </label>
                    <label style="display: flex; align-items: center; cursor: pointer; padding: 10px; background: var(--bg3); border: 1px solid var(--border); border-radius: 6px;">
                        <input type="radio" name="heritage_type" value="style" style="margin-right: 8px;">
                        <span style="font-size: 12px;">🎵 Style</span>
                    </label>
                    <label style="display: flex; align-items: center; cursor: pointer; padding: 10px; background: var(--bg3); border: 1px solid var(--border); border-radius: 6px;">
                        <input type="radio" name="heritage_type" value="instrument" style="margin-right: 8px;">
                        <span style="font-size: 12px;">🎸 Instrument</span>
                    </label>
                </div>
            </div>

            <!-- Informations -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text3); text-transform: uppercase; margin-bottom: 8px;">ℹ️ Informations</label>
                <input type="text" placeholder="Titre/Nom..." style="width: 100%; padding: 9px 12px; background: var(--bg3); border: 1px solid var(--border); border-radius: 6px; color: var(--text); margin-bottom: 10px;">
                <input type="text" placeholder="Détenteur du patrimoine..." style="width: 100%; padding: 9px 12px; background: var(--bg3); border: 1px solid var(--border); border-radius: 6px; color: var(--text); margin-bottom: 10px;">
            </div>

            <!-- Région & Année -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text3); text-transform: uppercase; margin-bottom: 8px;">📍 Région</label>
                    <select style="width: 100%; padding: 9px 12px; background: var(--bg3); border: 1px solid var(--border); border-radius: 6px; color: var(--text);">
                        <option>Tunis</option>
                        <option>Sfax</option>
                        <option>Sousse</option>
                        <option>Kairouan</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text3); text-transform: uppercase; margin-bottom: 8px;">📅 Année</label>
                    <input type="year" placeholder="2024" style="width: 100%; padding: 9px 12px; background: var(--bg3); border: 1px solid var(--border); border-radius: 6px; color: var(--text);">
                </div>
            </div>

            <!-- Upload Média -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text3); text-transform: uppercase; margin-bottom: 8px;">📁 Fichier Audio/Vidéo</label>
                <div style="border: 2px dashed var(--border); border-radius: 6px; padding: 28px; text-align: center; cursor: pointer;">
                    <div style="font-size: 24px; margin-bottom: 8px;">🎵</div>
                    <div style="font-size: 12px; color: var(--text2);">Glisser-déposer audio (MP3, FLAC) ou vidéo (MP4)</div>
                    <input type="file" style="display: none;">
                </div>
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-gold" style="flex: 1;">Ajouter Patrimoine</button>
                <button type="button" onclick="closeModal('addHeritageModal')" class="btn btn-outline" style="flex: 1;">Annuler</button>
            </div>
        </form>
    </div>
</div>

<script>
function showModal(id) { document.getElementById(id).style.display = 'flex'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }
function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
    document.getElementById(tabName).style.display = 'block';
    document.querySelectorAll('.tab-btn').forEach(btn => btn.style.borderBottom = 'none');
    event.target.style.borderBottom = '2px solid var(--gold)';
}
</script>

@endsection
