@extends('shared.layouts.frontoffice')

@section('page-title', ' GDE - Home')

@section('content')

    <div class="page-wrap">
        <!-- pc page section kol— spans entire page -->
        <div aria-hidden="true"
            style="
  position: fixed;
  inset: 0;
  z-index: 0;
  pointer-events: none;
  overflow: hidden;
">
            <!-- Top-left -->
            <img src="{{ Vite::asset('resources/assets/images/arabic-calligraphy4-Photoroom.png') }}"
                style="
    position: absolute;
    top: 5vh; left: -5vw;
    width: 65vw; opacity: 0.35;
    transform: rotate(-18deg);
    mix-blend-mode: screen;
    user-select: none;
  ">
            <!-- Bottom-right -->
            <img src="{{ Vite::asset('resources/assets/images/arabic-calligraphy5-Photoroom.png') }}"
                style="
    position: absolute;
    bottom: 5vh; right: -5vw;
    width: 62vw; opacity: 0.32;
    transform: rotate(14deg);
    mix-blend-mode: screen;
    user-select: none;
  ">
            <!-- Mid-left -->
            <img src="{{ Vite::asset('resources/assets/images/arabic-calligraphy3-Photoroom.png') }}"
                style="
    position: absolute;
    top: 42vh; left: -8vw;
    width: 58vw; opacity: 0.28;
    transform: rotate(-8deg);
    mix-blend-mode: screen;
    user-select: none;
  ">
            <!-- Mid-right -->
            <img src="{{ Vite::asset('resources/assets/images/arabic-calligraphy2-Photoroom.png') }}"
                style="
    position: absolute;
    top: 28vh; right: -6vw;
    width: 60vw; opacity: 0.30;
    transform: rotate(20deg);
    mix-blend-mode: screen;
    user-select: none;
  ">
        </div>
    </div>






    <!-- ══ HERO SLIDER ══ -->
    <section class="hero-slider" id="hero-slider">

        <!-- Slide 1 -->
        <div class="slide active" style="background-image: url('{{ Vite::asset('resources/assets/images/hero1.jpg') }}');">
            <div class="slide-content">
                <div class="slide-badge">
                    <span
                        style="background:var(--gold);width:8px;height:8px;border-radius:50%;display:inline-block;"></span>
                    PORTAIL OFFICIEL
                </div>
                <h1 class="slide-title">La culture<br>Au Service Du Citoyen</h1>
                <p class="slide-subtitle">Dématérialisez Vos Démarches Culturelles En Toute Simplicité</p>
                <a href="register.html" class="slide-cta">Commencer maintenant →</a>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="slide" style="background-image: url('{{ Vite::asset('resources/assets/images/hero2.jpg') }}');">
            <div class="slide-content">
                <div class="slide-badge">MINISTÈRE DES AFFAIRES CULTURELLES</div>
                <h1 class="slide-title">Patrimoine & Création</h1>
                <p class="slide-subtitle">Un portail unique pour artistes, créateurs et institutions tunisiennes</p>
                <a href="register.html" class="slide-cta">Déposer une demande</a>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="slide" style="background-image: url('{{ Vite::asset('resources/assets/images/hero3.jpg') }}');">
            <div class="slide-content">
                <div class="slide-badge">TUNISIE CULTURELLE</div>
                <h1 class="slide-title">Votre Dossier<br>En Quelques Clics</h1>
                <p class="slide-subtitle">Suivi en temps réel Workflows automatisés Intelligence artificielle</p>
                <a href="login.html" class="slide-cta">Suivre mon dossier</a>
            </div>
        </div>


        <!-- Slide 4 -->
        <div class="slide" style="background-image: url('{{ Vite::asset('resources/assets/images/hero4.jpg') }}');">
            <div class="slide-content">
                <div class="slide-badge">ARTS & PATRIMOINE</div>
                <h1 class="slide-title">Ensemble Valorisons<br>Notre Identité</h1>
                <p class="slide-subtitle">Carte professionnelle • Autorisations • Diplômes • Investissement culturel</p>
                <a href="#services" class="slide-cta">Découvrir nos services</a>
            </div>
        </div>
        <!-- Arrows -->
        <div class="slider-arrow left" id="prev-arrow">‹</div>
        <div class="slider-arrow right" id="next-arrow">›</div>

        <!-- Dots -->
        <div class="slider-dots" id="slider-dots"></div>
    </section>

    <!-- ══ QUI SOMMES-NOUS ══ -->
    <section class="about-section" id="about">
        <div class="about-container">

            <div class="about-content">
                <div class="about-eyebrow">
                    <div class="about-eyebrow-line"></div>
                    <span class="about-eyebrow-text">À propos de nous</span>
                </div>

                <h2>Qui<br>sommes-<em>nous</em>&nbsp;?</h2>
                <span class="about-subtitle-tag">Ministère des Affaires Culturelles · Tunisie</span>

                <p>
                    Le <strong>Portail des Démarches Culturelles</strong> est l'initiative numérique du Ministère des
                    Affaires
                    Culturelles de Tunisie.
                    Nous modernisons et simplifions les procédures administratives pour tous les acteurs du secteur
                    culturel.
                </p>

                <div class="about-quote">
                    <p>Nous digitalisons les démarches culturelles pour accompagner artistes, auteurs et professionnels
                        dans
                        l'accès à leurs services et reconnaissances officielles.</p>
                </div>

                <div class="about-divider"></div>

                <p>
                    Que vous soyez artiste, auteur, producteur, investisseur culturel ou institution, notre plateforme
                    vous
                    permet de déposer vos demandes,
                    suivre leur évolution en temps réel et bénéficier d'un accompagnement intelligent tout au long du
                    processus.
                </p>

                <a href="#" class="about-cta">En savoir plus sur notre mission <span>→</span></a>
            </div>

            <div class="about-visual-col">

                <div class="about-visual">
                    <img src="{{ Vite::asset('resources/assets/images/about-visual.jpg') }}"
                        alt="Ministère des Affaires Culturelles"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="about-visual-svg" style="display:none; position:absolute; inset:0;">
                        <svg width="300" height="220" viewBox="0 0 300 220">
                            <g fill="none" stroke="rgba(201,168,76,0.18)" stroke-width="1">
                                <path d="M150 190 Q150 60 80 60" />
                                <path d="M150 190 Q150 60 220 60" />
                                <path d="M150 190 Q150 40 50 40" />
                                <path d="M150 190 Q150 40 250 40" />
                            </g>
                            <circle cx="150" cy="110" r="56" stroke="rgba(201,168,76,0.22)"
                                stroke-width="0.8" />
                            <circle cx="150" cy="110" r="40" stroke="rgba(201,168,76,0.3)" stroke-width="0.8" />
                            <circle cx="150" cy="110" r="26" fill="rgba(201,168,76,0.06)"
                                stroke="rgba(201,168,76,0.45)" stroke-width="1" />
                            <text x="150" y="119" text-anchor="middle" font-size="26" fill="rgba(201,168,76,0.75)"
                                font-family="serif">م</text>
                            <circle cx="150" cy="54" r="3" fill="rgba(201,168,76,0.5)" />
                            <circle cx="206" cy="110" r="3" fill="rgba(201,168,76,0.5)" />
                            <circle cx="150" cy="166" r="3" fill="rgba(201,168,76,0.5)" />
                            <circle cx="94" cy="110" r="3" fill="rgba(201,168,76,0.5)" />
                            <rect x="32" y="8" width="88" height="20" rx="10" fill="rgba(201,168,76,0.1)"
                                stroke="rgba(201,168,76,0.3)" stroke-width="0.7" />
                            <text x="76" y="21" text-anchor="middle" font-size="8.5" fill="rgba(201,168,76,0.8)"
                                font-family="sans-serif" letter-spacing="1">PORTAIL OFFICIEL</text>
                            <rect x="180" y="8" width="88" height="20" rx="10" fill="rgba(201,168,76,0.1)"
                                stroke="rgba(201,168,76,0.3)" stroke-width="0.7" />
                            <text x="224" y="21" text-anchor="middle" font-size="8.5" fill="rgba(201,168,76,0.8)"
                                font-family="sans-serif" letter-spacing="1">TUNISIE — 2026</text>
                        </svg>
                    </div>
                </div>

                <div class="about-stats">
                    <div class="about-stat about-pillar">
                        <div class="about-pillar-icon">📊</div>
                        <div>
                            <span class="about-stat-val" id="astat1">0</span>
                            <span class="about-stat-lbl">Services en ligne</span>
                        </div>
                    </div>
                    <div class="about-stat about-pillar">
                        <div class="about-pillar-icon">📁</div>
                        <div>
                            <span class="about-stat-val" id="astat2">0+</span>
                            <span class="about-stat-lbl">Dossiers traités</span>
                        </div>
                    </div>
                    <div class="about-stat about-pillar">
                        <div class="about-pillar-icon">⏱️</div>
                        <div>
                            <span class="about-stat-val" id="astat3">0h</span>
                            <span class="about-stat-lbl">Délai moyen</span>
                        </div>
                    </div>
                </div>

                <div class="about-pillars">
                    <div class="about-pillar">
                        <div class="about-pillar-icon">🎭</div>
                        <div>
                            <div class="about-pillar-name">Artistes & Créateurs</div>
                            <div class="about-pillar-desc">Cartes, agréments, reconnaissances</div>
                        </div>
                    </div>
                    <div class="about-pillar">
                        <div class="about-pillar-icon">🏛️</div>
                        <div>
                            <div class="about-pillar-name">Institutions culturelles</div>
                            <div class="about-pillar-desc">Autorisations, investissements</div>
                        </div>
                    </div>
                    <div class="about-pillar">
                        <div class="about-pillar-icon">📜</div>
                        <div>
                            <div class="about-pillar-name">Patrimoine & Édition</div>
                            <div class="about-pillar-desc">Dépôt légal, ISBN, soutien</div>
                        </div>
                    </div>
                    <div class="about-pillar">
                        <div class="about-pillar-icon">🤖</div>
                        <div>
                            <div class="about-pillar-name">IA & Automatisation</div>
                            <div class="about-pillar-desc">Workflows intelligents, temps réel</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ══ SERVICES ══ -->
    <div class="services-wrapper">

        <!-- Video backdrop -->
        <div class="services-video-bg" id="services-video-bg">
            <video id="svc-video" muted loop playsinline preload="none"></video>
            <div class="vid-overlay"></div>
        </div>

        <section class="section" id="services">

            <div class="about-eyebrow">
                <div class="about-eyebrow-line"></div>
                <span class="about-eyebrow-text">Nos services</span>
            </div>

            <h2 class="section-title anim anim-d1">Choisissez votre démarche</h2>
            <p class="section-sub anim anim-d2">Accédez à l'ensemble des services du ministère en ligne</p>

            <div class="services-grid">

                <!-- Card 1 -->
                <div class="service-card anim anim-d1"
                    data-video="{{ Vite::asset('resources/assets/videos/service1.mp4') }}"
                    onclick="location.href='form.html?svc=s1'">
                    <div class="card-inner">
                        <div class="card-front">
                            <span class="sc-icon">🎭</span>
                            <div class="sc-name">Carte Professionnelle Artistique</div>
                            <div class="sc-desc">Demande, renouvellement ou duplicata de carte professionnelle
                                d'artiste</div>
                            <span class="badge badge-green">Dématérialisé</span>
                        </div>
                        <div class="card-back">
                            <div class="cb-title">Carte Professionnelle</div>
                            <div class="cb-items">
                                <div class="cb-item"><span class="cb-label">⏱ Délai</span><span class="cb-value">7–14
                                        jours</span>
                                </div>
                                <div class="cb-item"><span class="cb-label">📄 Documents</span><span
                                        class="cb-value">CIN, photos,
                                        justificatif d'activité</span></div>
                                <div class="cb-item"><span class="cb-label">💰 Coût</span><span
                                        class="cb-value">Gratuit</span></div>
                                <div class="cb-item"><span class="cb-label">🔄 Renouvellement</span><span
                                        class="cb-value">Tous les 2
                                        ans</span></div>
                            </div>
                            <a href="form.html?svc=s1" class="cb-cta">Démarrer →</a>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="service-card anim anim-d1"
                    data-video="{{ Vite::asset('resources/assets/videos/service2.mp4') }}"
                    onclick="location.href='form.html?svc=s2'">
                    <div class="card-inner">
                        <div class="card-front">
                            <span class="sc-icon">📜</span>
                            <div class="sc-name">Attestations & Certificats</div>
                            <div class="sc-desc">Attestation CNSS, certificats d'exercice, attestations de
                                participation</div>
                            <span class="badge badge-gold">Délai: 48h</span>
                        </div>
                        <div class="card-back">
                            <div class="cb-title">Attestations & Certificats</div>
                            <div class="cb-items">
                                <div class="cb-item"><span class="cb-label">⏱ Délai</span><span class="cb-value">48
                                        heures</span>
                                </div>
                                <div class="cb-item"><span class="cb-label">📄 Documents</span><span
                                        class="cb-value">CIN, dossier
                                        d'adhésion CNSS</span></div>
                                <div class="cb-item"><span class="cb-label">💰 Coût</span><span
                                        class="cb-value">Gratuit</span></div>
                                <div class="cb-item"><span class="cb-label">📋 Type</span><span
                                        class="cb-value">Numérique + cachet
                                        officiel</span></div>
                            </div>
                            <a href="form.html?svc=s2" class="cb-cta">Démarrer →</a>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="service-card anim anim-d2"
                    data-video="{{ Vite::asset('resources/assets/videos/service3.mp4') }}"
                    onclick="location.href='form.html?svc=s3'">
                    <div class="card-inner">
                        <div class="card-front">
                            <span class="sc-icon">🎬</span>
                            <div class="sc-name">Autorisation de Tournage</div>
                            <div class="sc-desc">Demandes d'autorisation pour productions cinématographiques et
                                audiovisuelles</div>
                            <span class="badge badge-teal">5–7 jours</span>
                        </div>
                        <div class="card-back">
                            <div class="cb-title">Autorisation de Tournage</div>
                            <div class="cb-items">
                                <div class="cb-item"><span class="cb-label">⏱ Délai</span><span class="cb-value">5–7
                                        jours
                                        ouvrables</span></div>
                                <div class="cb-item"><span class="cb-label">📄 Documents</span><span
                                        class="cb-value">Scénario, plan
                                        de tournage, équipe</span></div>
                                <div class="cb-item"><span class="cb-label">💰 Coût</span><span class="cb-value">Selon
                                        lieu &
                                        durée</span></div>
                                <div class="cb-item"><span class="cb-label">📍 Zones</span><span
                                        class="cb-value">Intérieur &
                                        extérieur Tunisie</span></div>
                            </div>
                            <a href="form.html?svc=s3" class="cb-cta">Démarrer →</a>
                        </div>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="service-card anim anim-d2"
                    data-video="{{ Vite::asset('resources/assets/videos/service4.mp4') }}"
                    onclick="location.href='form.html?svc=s4'">
                    <div class="card-inner">
                        <div class="card-front">
                            <span class="sc-icon">📚</span>
                            <div class="sc-name">Livre & Édition</div>
                            <div class="sc-desc">Dépôt légal, ISBN, soutien éditorial, salons du livre</div>
                            <span class="badge badge-teal">Commission</span>
                        </div>
                        <div class="card-back">
                            <div class="cb-title">Livre & Édition</div>
                            <div class="cb-items">
                                <div class="cb-item"><span class="cb-label">⏱ Délai</span><span class="cb-value">10–21
                                        jours</span>
                                </div>
                                <div class="cb-item"><span class="cb-label">📄 Documents</span><span
                                        class="cb-value">Manuscrit, fiche
                                        auteur, ISBN</span></div>
                                <div class="cb-item"><span class="cb-label">💰 Coût</span><span class="cb-value">Gratuit
                                        (dépôt
                                        légal)</span></div>
                                <div class="cb-item"><span class="cb-label">📚 Exemplaires</span><span class="cb-value">4
                                        exemplaires
                                        obligatoires</span></div>
                            </div>
                            <a href="form.html?svc=s4" class="cb-cta">Démarrer →</a>
                        </div>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="service-card anim anim-d3"
                    data-video="{{ Vite::asset('resources/assets/videos/service5.mp4') }}"
                    onclick="location.href='form.html?svc=s5'">
                    <div class="card-inner">
                        <div class="card-front">
                            <span class="sc-icon">🎵</span>
                            <div class="sc-name">Diplômes de Musique</div>
                            <div class="sc-desc">Inscriptions aux examens de musique arabe et d'instrumentiste</div>
                            <span class="badge badge-gold">Candidature</span>
                        </div>
                        <div class="card-back">
                            <div class="cb-title">Diplômes de Musique</div>
                            <div class="cb-items">
                                <div class="cb-item"><span class="cb-label">⏱ Délai</span><span class="cb-value">Session
                                        annuelle</span></div>
                                <div class="cb-item"><span class="cb-label">📄 Documents</span><span
                                        class="cb-value">CIN, relevés de
                                        notes, photo</span></div>
                                <div class="cb-item"><span class="cb-label">💰 Coût</span><span class="cb-value">Frais
                                        d'inscription
                                        applicables</span></div>
                                <div class="cb-item"><span class="cb-label">🎼 Spécialités</span><span
                                        class="cb-value">Musique arabe,
                                        instrumentiste</span></div>
                            </div>
                            <a href="form.html?svc=s5" class="cb-cta">Démarrer →</a>
                        </div>
                    </div>
                </div>

                <!-- Card 6 -->
                <div class="service-card anim anim-d3"
                    data-video="{{ Vite::asset('resources/assets/videos/service6.mp4') }}"
                    onclick="location.href='form.html?svc=s6'">
                    <div class="card-inner">
                        <div class="card-front">
                            <span class="sc-icon">🏛️</span>
                            <div class="sc-name">Investisseurs Culturels</div>
                            <div class="sc-desc">Dossiers d'investissement culturel, agréments et certifications</div>
                            <span class="badge badge-gold">Haute priorité</span>
                        </div>
                        <div class="card-back">
                            <div class="cb-title">Investisseurs Culturels</div>
                            <div class="cb-items">
                                <div class="cb-item"><span class="cb-label">⏱ Délai</span><span class="cb-value">30–45
                                        jours</span>
                                </div>
                                <div class="cb-item"><span class="cb-label">📄 Documents</span><span
                                        class="cb-value">Business plan,
                                        registre commerce</span></div>
                                <div class="cb-item"><span class="cb-label">💰 Coût</span><span class="cb-value">Selon
                                        type
                                        d'investissement</span></div>
                                <div class="cb-item"><span class="cb-label">⭐ Priorité</span><span
                                        class="cb-value">Traitement
                                        accéléré garanti</span></div>
                            </div>
                            <a href="form.html?svc=s6" class="cb-cta">Démarrer →</a>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <!-- ══ NEWS / ACTUALITES ══ -->
    <section class="news-section" id="actualites">
        <div class="news-container">

            <div class="news-header">
                <div class="news-header-left">
                    <div class="news-eyebrow">
                        <div class="news-eyebrow-line"></div>
                        <span class="news-eyebrow-text">Actualites Culturelles</span>
                    </div>
                    <h2>L'art en <em>mouvement</em></h2>
                    <p class="news-header-sub">Restez informes des dernieres nouvelles du monde artistique et culturel
                        tunisien
                    </p>
                </div>
                <a href="#" class="news-view-all">Voir toutes les actualites <span>&#8594;</span></a>
            </div>

            <div class="news-grid">

                <!-- Featured Article -->
                <article class="news-card featured anim">
                    <div class="news-card-image">
                        <img src="{{ Vite::asset('resources/assets/images/wmremove-transformed.jpeg') }}"
                            alt="Biennale de Tunis">
                        <span class="news-card-category">Evenement</span>
                    </div>
                    <div class="news-card-body">
                        <div class="news-card-meta">
                            <span class="news-card-date">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                15 Mars 2026
                            </span>
                            <span class="news-card-author">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                Ministere
                            </span>
                        </div>
                        <h3>La Biennale d'Art Contemporain de Tunis revient en 2026</h3>
                        <p class="news-card-excerpt">Le plus grand evenement artistique du Maghreb accueillera plus de
                            200
                            artistes internationaux. Une celebration de la creativite mediterraneenne qui promet d'etre
                            exceptionnelle avec des installations, performances et expositions dans toute la capitale.
                        </p>
                        <a href="#" class="news-card-link">
                            Lire la suite
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </article>

                <!-- News Card 2 -->
                <article class="news-card anim anim-d1">
                    <div class="news-card-image">
                        <img src="{{ Vite::asset('resources/assets/images/close-up-hands-working-pottery.jpg') }}"
                            alt="Exposition peinture">
                        <span class="news-card-category">Exposition</span>
                    </div>
                    <div class="news-card-body">
                        <div class="news-card-meta">
                            <span class="news-card-date">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                12 Mars 2026
                            </span>
                        </div>
                        <h3>Nouvelle exposition au Musee du Bardo</h3>
                        <p class="news-card-excerpt">Decouvrez les tresors de l'art islamique a travers une collection
                            exceptionnelle de manuscrits enlumines.</p>
                        <a href="#" class="news-card-link">
                            Lire la suite
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </article>

                <!-- News Card 3 -->
                <article class="news-card anim anim-d2">
                    <div class="news-card-image">
                        <img src="https://images.unsplash.com/photo-1511379938547-c1f69419868d?w=600&q=80"
                            alt="Festival musique">
                        <span class="news-card-category">Musique</span>
                    </div>
                    <div class="news-card-body">
                        <div class="news-card-meta">
                            <span class="news-card-date">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                10 Mars 2026
                            </span>
                        </div>
                        <h3>Festival International de Musique de Carthage</h3>
                        <p class="news-card-excerpt">La programmation 2026 devoilee avec des artistes de renommee
                            mondiale et des
                            talents locaux.</p>
                        <a href="#" class="news-card-link">
                            Lire la suite
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </article>

                <!-- News Card 4 -->
                <article class="news-card anim anim-d2">
                    <div class="news-card-image">
                        <img src="https://images.unsplash.com/photo-1518998053901-5348d3961a04?w=600&q=80"
                            alt="Ceramique tunisienne">
                        <span class="news-card-category">Artisanat</span>
                    </div>
                    <div class="news-card-body">
                        <div class="news-card-meta">
                            <span class="news-card-date">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                8 Mars 2026
                            </span>
                        </div>
                        <h3>Reconnaissance de l'artisanat tunisien par l'UNESCO</h3>
                        <p class="news-card-excerpt">La ceramique de Sejnane et le tissage de Gafsa inscrits au
                            patrimoine
                            immateriel mondial.</p>
                        <a href="#" class="news-card-link">
                            Lire la suite
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </article>

                <!-- News Card 5 -->
                <article class="news-card anim anim-d3">
                    <div class="news-card-image">
                        <img src="https://images.unsplash.com/photo-1485846234645-a62644f84728?w=600&q=80"
                            alt="Cinema tunisien">
                        <span class="news-card-category">Cinema</span>
                    </div>
                    <div class="news-card-body">
                        <div class="news-card-meta">
                            <span class="news-card-date">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                5 Mars 2026
                            </span>
                        </div>
                        <h3>Prix du meilleur film aux JCC pour un realisateur tunisien</h3>
                        <p class="news-card-excerpt">Le cinema tunisien rayonne a l'international avec ce prix
                            prestigieux decerne
                            a Carthage.</p>
                        <a href="#" class="news-card-link">
                            Lire la suite
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </article>

                <!-- News Card 6 -->
                <article class="news-card anim anim-d3">
                    <div class="news-card-image">
                        <img src="https://images.unsplash.com/photo-1544928147-79a2dbc1f389?w=600&q=80"
                            alt="Theatre national">
                        <span class="news-card-category">Theatre</span>
                    </div>
                    <div class="news-card-body">
                        <div class="news-card-meta">
                            <span class="news-card-date">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                2 Mars 2026
                            </span>
                        </div>
                        <h3>Renovation du Theatre Municipal de Tunis</h3>
                        <p class="news-card-excerpt">Un projet ambitieux pour redonner vie a ce joyau architectural et
                            culturel de
                            la capitale.</p>
                        <a href="#" class="news-card-link">
                            Lire la suite
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </article>
                <!-- News Card — Concours 1 -->
                <article class="news-card anim anim-d1">
                    <div class="news-card-image">
                        <img src="https://images.unsplash.com/photo-1452587925148-ce544e77e70d?w=600&q=80"
                            alt="Concours photographie">
                        <span class="news-card-category concours">Concours</span>
                    </div>
                    <div class="news-card-body">
                        <div class="news-card-meta">
                            <span class="news-card-date">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                18 Avril 2026
                            </span>
                        </div>
                        <h3>Concours National de Photographie 2026</h3>
                        <p class="news-card-excerpt">Participez au plus grand concours de photographie culturelle de
                            Tunisie. Soumettez vos œuvres autour du thème "Patrimoine Vivant" avant le 30 Mai.</p>
                        <a href="#" class="news-card-link">
                            Lire la suite
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </article>

                <!-- News Card — Concours 2 -->
                <article class="news-card anim anim-d2">
                    <div class="news-card-image">
                        <img src="https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=600&q=80"
                            alt="Appel candidatures">
                        <span class="news-card-category concours">Concours</span>
                    </div>
                    <div class="news-card-body">
                        <div class="news-card-meta">
                            <span class="news-card-date">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                22 Avril 2026
                            </span>
                        </div>
                        <h3>Appel à Candidatures — Résidence Artistique 2026</h3>
                        <p class="news-card-excerpt">Le Ministère lance un appel à candidatures pour sa résidence
                            artistique annuelle. Ouvert aux jeunes créateurs tunisiens de moins de 35 ans.</p>
                        <a href="#" class="news-card-link">
                            Lire la suite
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </article>
                <!-- News Card — Concours 2 -->
                <article class="news-card anim anim-d2">
                    <div class="news-card-image">
                        <img src="https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=600&q=80"
                            alt="Appel candidatures">
                        <span class="news-card-category concours">Concours</span>
                    </div>
                    <div class="news-card-body">
                        <div class="news-card-meta">
                            <span class="news-card-date">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                22 Avril 2026
                            </span>
                        </div>
                        <h3>Appel à Candidatures — Résidence Artistique 2026</h3>
                        <p class="news-card-excerpt">Le Ministère lance un appel à candidatures pour sa résidence
                            artistique annuelle. Ouvert aux jeunes créateurs tunisiens de moins de 35 ans.</p>
                        <a href="#" class="news-card-link">
                            Lire la suite
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </section>



    <!-- STARFIELD BACKGROUND -->
    <div class="starfield"></div>

    <!-- PAGE CONTAINER -->
    <div class="page-container">

        <!-- HERO & MAP SECTION -->
        <section id="patrimoine" class="hero-map">
            <div class="hero-content">
                <h1>Le Patrimoine Tunisien Culturel Un Clic à La Fois</h1>
                <p>Naviguez sur la carte interactive pour découvrir les lieux culturels importants de la Tunisie. Cliquez
                    sur
                    les marqueurs pour en savoir plus.</p>
                <div class="zoom-controls">
                    <button class="zoom-btn" id="zoom-in" title="Zoom avant">+</button>
                    <button class="zoom-btn" id="zoom-out" title="Zoom arrière">−</button>
                </div>
            </div>
            <div class="map-wrapper">
                <div id="tunisia-map"></div>
                <div class="locations-panel">
                    <div class="panel-title">Lieux</div>
                    <div id="locations-list"></div>
                </div>
            </div>
        </section>

        <!-- MAP LEGEND — under the hero/map section -->
        <div class="map-legend-bar">
    <div class="legend-bar-title">Clés</div>
    <div class="legend-bar-items">
        @foreach ($mapCategories as $i => $cat)
            <div class="legend-bar-item">
                <span class="legend-bar-dot"
                    style="background:{{ $cat['color'] }}; box-shadow: 0 0 10px {{ $cat['color'] }}99;"></span>
                <span class="legend-bar-label">{{ $cat['name'] }}</span>
            </div>
            @if (!$loop->last)
                <div class="legend-bar-divider"></div>
            @endif
        @endforeach
    </div>
</div>


{{-- ════════════ 2) INJECT THE DATA — place this BEFORE map.js's <script> tag ════════════ --}}



        <div class="info-panel" id="info-panel">

            <div class="info-image">
                <button class="info-back-btn" id="info-close-btn">←</button>
                <img id="info-image" src="{{ Vite::asset('resources/assets/images/sponsor1.png') }}"
                    alt="location image">
            </div>

            <div class="info-content">
                <h2 class="info-title" id="info-title">Location Name</h2>

                <div class="info-category" id="info-category">CATEGORY</div>

                <p class="info-description" id="info-description">
                    Description of the location
                </p>

                <div class="info-coords" id="info-coords"></div>

                <button class="info-btn">Explore</button>
            </div>

        </div>
        <!-- SERVICES SECTION -->

    </div>




    <!-- ══ DIVERSITÉ CULTURELLE ══ -->

    <section id="diversite" class="diversite-section">
        <div class="diversite-inner">

            <div class="about-eyebrow">
                <div class="about-eyebrow-line"></div>
                <span class="about-eyebrow-text">Rubrique Culturelle Variée</span>
            </div>
            <h2 class="diversite-title">Nos <em>Secteurs</em> Culturels</h2>

            <!-- Sector Tabs -->
            <div class="diversite-tabs">
                <button class="div-tab active" data-sector="0">Musique & Danse</button>
                <button class="div-tab" data-sector="1">Arts Audio-visuels</button>
                <button class="div-tab" data-sector="2">Arts Plastiques</button>
                <button class="div-tab" data-sector="3">Arts Scéniques</button>
                <button class="div-tab" data-sector="4">Le Livre</button>
                <button class="div-tab" data-sector="5">Investisseurs</button>
            </div>

            <!-- Slider Wrapper -->
            <div class="diversite-slider-wrap">
                <button class="div-arrow div-prev">‹</button>

                <div class="diversite-viewport">
                    <div class="diversite-track" id="diversite-track">

                        <!-- ==================== SECTOR 0: Musique & Danse ==================== -->
                        <div class="div-slide-group active" data-sector="0">
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image:url('{{ Vite::asset('resources/assets/images/hero11.jpg') }}')">

                                    <span class="div-card-cat">Musique & Danse</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 15 Avril 2026</div>
                                    <h4>Festival de Musique Arabe</h4>
                                    <p>Soirée musicale réunissant les meilleurs artistes du monde arabe.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero22.jpg') }}')">
                                    <span class="div-card-cat">Musique & Danse</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 22 Avril 2026</div>
                                    <h4>Nuit du Malouf Tunisien</h4>
                                    <p>Concert exceptionnel dédié au patrimoine musical tunisien.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero33.jpg') }}')">
                                    <span class="div-card-cat">Musique & Danse</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 30 Avril 2026</div>
                                    <h4>Spectacle de Danse Contemporaine</h4>
                                    <p>Une fusion entre traditions et modernité sur scène.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero44.jpg') }}')">
                                    <span class="div-card-cat">Musique & Danse</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 5 Mai 2026</div>
                                    <h4>Atelier Rythmes Africains</h4>
                                    <p>Initiation aux percussions et danses d'Afrique subsaharienne.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero5.jpg') }}')">
                                    <span class="div-card-cat">Musique & Danse</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 12 Mai 2026</div>
                                    <h4>Gala de Danse Classique</h4>
                                    <p>Les étoiles de l'Institut National de Musique en représentation.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero6.jpg') }}')">
                                    <span class="div-card-cat">Musique & Danse</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 20 Mai 2026</div>
                                    <h4>Journée Mondiale de la Musique</h4>
                                    <p>Événements gratuits dans toutes les maisons de culture.</p>
                                </div>
                            </div>
                        </div>

                        <!-- ==================== SECTOR 1: Arts Audio-visuels ==================== -->
                        <div class="div-slide-group" data-sector="1">
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero7.jpg') }}')">
                                    <span class="div-card-cat">Arts Audio-visuels</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 18 Avril 2026</div>
                                    <h4>JCC — Journées Cinématographiques</h4>
                                    <p>Projection de films tunisiens primés à l'international.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero8.jpg') }}')">
                                    <span class="div-card-cat">Arts Audio-visuels</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 25 Avril 2026</div>
                                    <h4>Masterclass Réalisation</h4>
                                    <p>Atelier avec des réalisateurs tunisiens de renom.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero9.jpg') }}')">
                                    <span class="div-card-cat">Arts Audio-visuels</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 2 Mai 2026</div>
                                    <h4>Festival du Court-Métrage</h4>
                                    <p>Compétition internationale de films courts à Tunis.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero111.jpg') }}')">
                                    <span class="div-card-cat">Arts Audio-visuels</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 9 Mai 2026</div>
                                    <h4>Ciné-Débat Patrimoine</h4>
                                    <p>Documentaires sur le patrimoine immatériel tunisien.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero10.jpg') }}')">
                                    <span class="div-card-cat">Arts Audio-visuels</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 16 Mai 2026</div>
                                    <h4>Exposition Photo Jeunes</h4>
                                    <p>Regard des jeunes photographes sur la Tunisie contemporaine.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero12.jpg') }}')">
                                    <span class="div-card-cat">Arts Audio-visuels</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 23 Mai 2026</div>
                                    <h4>Nuit du Cinéma Arabe</h4>
                                    <p>Soirée spéciale dédiée au 7ème art du monde arabe.</p>
                                </div>
                            </div>
                        </div>

                        <!-- ==================== SECTOR 2: Arts Plastiques ==================== -->
                        <div class="div-slide-group" data-sector="2">
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero13.jpg') }}')">
                                    <span class="div-card-cat">Arts Plastiques</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 20 Avril 2026</div>
                                    <h4>Exposition Art Contemporain</h4>
                                    <p>Œuvres d'artistes tunisiens émergents au Palais Abdellia.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero18.jpg') }}')">
                                    <span class="div-card-cat">Arts Plastiques</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 27 Avril 2026</div>
                                    <h4>Atelier Calligraphie Arabe</h4>
                                    <p>Initiation à l'art de la calligraphie arabe traditionnelle.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero14.jpg') }}')">
                                    <span class="div-card-cat">Arts Plastiques</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 4 Mai 2026</div>
                                    <h4>Salon de Peinture Nationale</h4>
                                    <p>Les meilleures œuvres de la saison exposées à la Cité de la Culture.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero15.jpg') }}')">
                                    <span class="div-card-cat">Arts Plastiques</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 11 Mai 2026</div>
                                    <h4>Sculpture & Céramique</h4>
                                    <p>Démonstrations live par des maîtres artisans de Nabeul.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero16.jpg') }}')">
                                    <span class="div-card-cat">Arts Plastiques</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 18 Mai 2026</div>
                                    <h4>Street Art Médina</h4>
                                    <p>Fresques murales dans les ruelles historiques de la Médina.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero17.jpg') }}')">
                                    <span class="div-card-cat">Arts Plastiques</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 25 Mai 2026</div>
                                    <h4>Prix National des Arts</h4>
                                    <p>Cérémonie de remise des prix aux artistes tunisiens distingués.</p>
                                </div>
                            </div>
                        </div>

                        <!-- ==================== SECTOR 3: Arts Scéniques ==================== -->
                        <div class="div-slide-group" data-sector="3">
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero19.jpg') }}')">
                                    <span class="div-card-cat">Arts Scéniques</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 17 Avril 2026</div>
                                    <h4>Théâtre de Carthage</h4>
                                    <p>Pièce historique retraçant l'épopée carthaginoise.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero20.jpg') }}')">
                                    <span class="div-card-cat">Arts Scéniques</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 24 Avril 2026</div>
                                    <h4>Festival du Théâtre Arabe</h4>
                                    <p>Troupes arabes en compétition au Théâtre Municipal de Tunis.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero21.jpg') }}')">
                                    <span class="div-card-cat">Arts Scéniques</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 1 Mai 2026</div>
                                    <h4>Marionnettes & Karagöz</h4>
                                    <p>Spectacle traditionnel de marionnettes pour petits et grands.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero222.jpg') }}')">
                                    <span class="div-card-cat">Arts Scéniques</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 8 Mai 2026</div>
                                    <h4>Opéra Tunisien</h4>
                                    <p>Première nationale d'un opéra composé par un maestro tunisien.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero23.jpg') }}')">
                                    <span class="div-card-cat">Arts Scéniques</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 15 Mai 2026</div>
                                    <h4>Slam & Poésie Scénique</h4>
                                    <p>Soirée poétique ouverte aux jeunes talents tunisiens.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero24.jpg') }}')">
                                    <span class="div-card-cat">Arts Scéniques</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 22 Mai 2026</div>
                                    <h4>Nuit des Tréteaux</h4>
                                    <p>Festival de théâtre de rue dans les espaces publics de Tunis.</p>
                                </div>
                            </div>
                        </div>

                        <!-- ==================== SECTOR 4: Le Livre ==================== -->
                        <div class="div-slide-group" data-sector="4">
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero25.jpg') }}')">
                                    <span class="div-card-cat">Le Livre</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 19 Avril 2026</div>
                                    <h4>Salon International du Livre</h4>
                                    <p>La plus grande foire du livre de Tunisie ouvre ses portes.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero26.jpg') }}')">
                                    <span class="div-card-cat">Le Livre</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 26 Avril 2026</div>
                                    <h4>Rencontre avec l'Auteur</h4>
                                    <p>Séance de dédicaces et échanges avec des écrivains tunisiens.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero27.jpg') }}')">
                                    <span class="div-card-cat">Le Livre</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 3 Mai 2026</div>
                                    <h4>Club de Lecture Jeunesse</h4>
                                    <p>Ateliers de lecture pour enfants dans les bibliothèques publiques.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero28.jpg') }}')">
                                    <span class="div-card-cat">Le Livre</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 10 Mai 2026</div>
                                    <h4>Prix Comar d'Or</h4>
                                    <p>Cérémonie de remise des prix littéraires les plus prestigieux de Tunisie.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero29.jpg') }}')">
                                    <span class="div-card-cat">Le Livre</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 17 Mai 2026</div>
                                    <h4>Traduction & Dialogue</h4>
                                    <p>Symposium sur la traduction littéraire arabe-français.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero30.jpg') }}')">
                                    <span class="div-card-cat">Le Livre</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 24 Mai 2026</div>
                                    <h4>Bibliothèque Numérique</h4>
                                    <p>Lancement de la plateforme nationale de lecture numérique.</p>
                                </div>
                            </div>
                        </div>

                        <!-- ==================== SECTOR 5: Investisseurs ==================== -->
                        <div class="div-slide-group" data-sector="5">
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero31.jpg') }}')">
                                    <span class="div-card-cat">Investissement</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 21 Avril 2026</div>
                                    <h4>Forum de l'Investissement Culturel</h4>
                                    <p>Rencontre entre investisseurs et porteurs de projets culturels.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero32.jpg') }}')">
                                    <span class="div-card-cat">Investissement</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 28 Avril 2026</div>
                                    <h4>Pitch Culture — Startups</h4>
                                    <p>Présentation de startups culturelles devant un jury d'investisseurs.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero333.jpg') }}')">
                                    <span class="div-card-cat">Investissement</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 5 Mai 2026</div>
                                    <h4>Guide de l'Investisseur Culturel</h4>
                                    <p>Publication du guide officiel des procédures et avantages fiscaux.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero34.jpg') }}')">
                                    <span class="div-card-cat">Investissement</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 12 Mai 2026</div>
                                    <h4>Partenariats Public-Privé</h4>
                                    <p>Signature d'accords entre le Ministère et des entreprises privées.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero35.jpg') }}')">
                                    <span class="div-card-cat">Investissement</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 19 Mai 2026</div>
                                    <h4>Appel à Projets 2026</h4>
                                    <p>Lancement du fonds national de soutien aux industries créatives.</p>
                                </div>
                            </div>
                            <div class="div-card">
                                <div class="div-card-img"
                                    style="background-image: url('{{ Vite::asset('resources/assets/images/hero36.jpg') }}')">
                                    <span class="div-card-cat">Investissement</span>
                                </div>
                                <div class="div-card-body">
                                    <div class="div-card-date">📅 26 Mai 2026</div>
                                    <h4>Zones Culturelles Libres</h4>
                                    <p>Présentation des nouvelles zones franches dédiées à la culture.</p>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <button class="div-arrow div-next">›</button>
            </div>



        </div>
    </section>
    <section>
        <div class="map-legend-bar-seperate">

        </div>
        <!-- ══ MANIFESTATIONS CULTURELLES ET FESTIVALS ══ -->
        <section id="manifests" class="manifests-section">


            <div class="manifests-inner">

                <div class="about-eyebrow">
                    <div class="about-eyebrow-line"></div>
                    <span class="about-eyebrow-text">Agenda Culturel</span>
                </div>
                <h2 class="diversite-title">Manifestations & <em>Festivals</em></h2>

                <!-- Filter Tabs -->
                <div class="diversite-tabs">
                    <button class="div-tab active" data-mfest="all">Tous</button>
                    <button class="div-tab" data-mfest="festival">Festivals</button>
                    <button class="div-tab" data-mfest="exposition">Expositions</button>
                    <button class="div-tab" data-mfest="spectacle">Spectacles</button>
                    <button class="div-tab" data-mfest="atelier">Ateliers</button>
                </div>

                <!-- Grid -->
                <div class="manifests-grid" id="manifests-grid">

                    <div class="div-card" data-tag="festival">
                        <div class="div-card-img"
                            style="background-image:url('{{ Vite::asset('resources/assets/images/hero11.jpg') }}')">
                            <span class="div-card-cat">Festival</span>
                        </div>
                        <div class="div-card-body">
                            <div class="div-card-date">📅 15 Avril 2026</div>
                            <h4>Festival International de Carthage</h4>
                            <p>Le plus grand festival estival de Tunisie sous les étoiles des ruines antiques.</p>
                        </div>
                    </div>

                    <div class="div-card" data-tag="exposition">
                        <div class="div-card-img"
                            style="background-image:url('{{ Vite::asset('resources/assets/images/hero13.jpg') }}')">
                            <span class="div-card-cat">Exposition</span>
                        </div>
                        <div class="div-card-body">
                            <div class="div-card-date">📅 20 Avril 2026</div>
                            <h4>Exposition Art Islamique</h4>
                            <p>Manuscrits enluminés et objets d'art du patrimoine islamique tunisien.</p>
                        </div>
                    </div>

                    <div class="div-card" data-tag="spectacle">
                        <div class="div-card-img"
                            style="background-image:url('{{ Vite::asset('resources/assets/images/hero19.jpg') }}')">
                            <span class="div-card-cat">Spectacle</span>
                        </div>
                        <div class="div-card-body">
                            <div class="div-card-date">📅 25 Avril 2026</div>
                            <h4>Nuit du Théâtre Tunisien</h4>
                            <p>Représentations simultanées dans tous les théâtres municipaux du pays.</p>
                        </div>
                    </div>

                    <div class="div-card" data-tag="festival">
                        <div class="div-card-img"
                            style="background-image:url('{{ Vite::asset('resources/assets/images/hero7.jpg') }}')">
                            <span class="div-card-cat">Festival</span>
                        </div>
                        <div class="div-card-body">
                            <div class="div-card-date">📅 1 Mai 2026</div>
                            <h4>Festival du Film Méditerranéen</h4>
                            <p>Cinéma des deux rives de la Méditerranée à l'honneur pendant une semaine.</p>
                        </div>
                    </div>

                    <div class="div-card" data-tag="atelier">
                        <div class="div-card-img"
                            style="background-image:url('{{ Vite::asset('resources/assets/images/hero18.jpg') }}')">
                            <span class="div-card-cat">Atelier</span>
                        </div>
                        <div class="div-card-body">
                            <div class="div-card-date">📅 8 Mai 2026</div>
                            <h4>Atelier Calligraphie & Art</h4>
                            <p>Initiation à la calligraphie arabe et aux arts décoratifs traditionnels.</p>
                        </div>
                    </div>

                    <div class="div-card" data-tag="spectacle">
                        <div class="div-card-img"
                            style="background-image:url('{{ Vite::asset('resources/assets/images/hero22.jpg') }}')">
                            <span class="div-card-cat">Spectacle</span>
                        </div>
                        <div class="div-card-body">
                            <div class="div-card-date">📅 15 Mai 2026</div>
                            <h4>Concert Malouf — Sidi Bou Saïd</h4>
                            <p>Soirée de musique andalouse dans le cadre enchanteur de Sidi Bou Saïd.</p>
                        </div>
                    </div>

                    <div class="div-card" data-tag="festival">
                        <div class="div-card-img"
                            style="background-image:url('{{ Vite::asset('resources/assets/images/hero25.jpg') }}')">
                            <span class="div-card-cat">Festival</span>
                        </div>
                        <div class="div-card-body">
                            <div class="div-card-date">📅 22 Mai 2026</div>
                            <h4>Salon International du Livre</h4>
                            <p>La plus grande foire littéraire de Tunisie réunit éditeurs et auteurs du monde arabe.</p>
                        </div>
                    </div>

                    <div class="div-card" data-tag="exposition">
                        <div class="div-card-img"
                            style="background-image:url('{{ Vite::asset('resources/assets/images/hero15.jpg') }}')">
                            <span class="div-card-cat">Exposition</span>
                        </div>
                        <div class="div-card-body">
                            <div class="div-card-date">📅 28 Mai 2026</div>
                            <h4>Biennale de Céramique</h4>
                            <p>Les maîtres potiers de Nabeul et Guellala exposent leurs créations contemporaines.</p>
                        </div>
                    </div>

                    <div class="div-card" data-tag="atelier">
                        <div class="div-card-img"
                            style="background-image:url('{{ Vite::asset('resources/assets/images/hero33.jpg') }}')">
                            <span class="div-card-cat">Atelier</span>
                        </div>
                        <div class="div-card-body">
                            <div class="div-card-date">📅 5 Juin 2026</div>
                            <h4>École d'Été Culturelle</h4>
                            <p>Deux semaines d'ateliers intensifs pour jeunes artistes tunisiens de 16 à 25 ans.</p>
                        </div>
                    </div>

                </div>

                <!-- Show More -->
                <div style="text-align:center; margin-top:40px;">
                    <button class="div-tab" id="manifests-show-more" style="padding:12px 32px; font-size:14px;">
                        Voir plus d'événements →
                    </button>
                </div>

            </div>
        </section>
        <div class="map-legend-bar-seperate">

        </div>
    </section>

    <!-- ══ SPONSORS ══ -->

    <section id="sponsors" class="sponsors-section">
        <div class="sponsors-inner">
            <div class="sponsors-header">
                <div class="about-eyebrow">
                    <div class="about-eyebrow-line"></div>
                    <span class="about-eyebrow-text">Nos partenaires institutionnels</span>
                </div>
                <h2>Ils nous <em>accompagnent</em></h2>
            </div>
            <div class="sponsors-scroll-wrapper">
                <div class="sponsors-track" id="sponsors-track">
                    <div class="sponsor-item"><img src="{{ Vite::asset('resources/assets/images/sponsor1.png') }}"
                            alt="Sponsor 1"></div>
                    <div class="sponsor-item"><img src="{{ Vite::asset('resources/assets/images/sponsor2.png') }}"
                            alt="Sponsor 2"></div>
                    <div class="sponsor-item"><img src="{{ Vite::asset('resources/assets/images/sponsor3.png') }}"
                            alt="Sponsor 3"></div>
                    <div class="sponsor-item"><img src="{{ Vite::asset('resources/assets/images/sponsor4.png') }}"
                            alt="Sponsor 4"></div>
                    <div class="sponsor-item"><img src="{{ Vite::asset('resources/assets/images/sponsor5.png') }}"
                            alt="Sponsor 5"></div>
                    <div class="sponsor-item"><img src="{{ Vite::asset('resources/assets/images/sponsor6.png') }}"
                            alt="Sponsor 6"></div>
                    <div class="sponsor-item"><img src="{{ Vite::asset('resources/assets/images/sponsor1.png') }}"
                            alt="Sponsor 1"></div>
                    <div class="sponsor-item"><img src="{{ Vite::asset('resources/assets/images/sponsor2.png') }}"
                            alt="Sponsor 2"></div>
                    <div class="sponsor-item"><img src="{{ Vite::asset('resources/assets/images/sponsor3.png') }}"
                            alt="Sponsor 3"></div>
                    <div class="sponsor-item"><img src="{{ Vite::asset('resources/assets/images/sponsor4.png') }}"
                            alt="Sponsor 4"></div>
                    <div class="sponsor-item"><img src="{{ Vite::asset('resources/assets/images/sponsor5.png') }}"
                            alt="Sponsor 5"></div>
                    <div class="sponsor-item"><img src="{{ Vite::asset('resources/assets/images/sponsor6.png') }}"
                            alt="Sponsor 6"></div>
                </div>
            </div>
        </div>
    </section>


    <!-- ══ CTA BAND ══ -->

    <div id="cta-band" class="cta-band anim">
        <div class="cta-band-text">
            <h2 data-i18n="home_cta_track">Suivre un dossier existant</h2>
            <p data-i18n="hero_desc">Dépôt de dossiers, suivi en temps réel, workflows automatisés.</p>
        </div>
        <div class="cta-band-actions">
            <a class="btn btn-outline" href="login.html">Suivre un dossier existant</a>
            <a class="btn btn-gold" href="login.html">Se connecter →</a>
        </div>
    </div>
    <!-- ══ VERTICAL SECTION NAV ══ -->
    <!-- ══ VERTICAL SECTION SPY NAV ══ -->
    <nav class="section-spy-nav" id="section-spy-nav" aria-label="Navigation sections">
        <div class="spy-nav-inner">
            <div class="spy-nav-label">Sections</div>
            <ul class="spy-nav-list">
                <li class="spy-nav-item">
                    <a href="#hero-slider" class="spy-nav-link active">
                        <span class="spy-dot"></span>
                        <span class="spy-label">Accueil</span>
                    </a>
                </li>
                <li class="spy-nav-item">
                    <a href="#about" class="spy-nav-link">
                        <span class="spy-dot"></span>
                        <span class="spy-label">Qui sommes-nous</span>
                    </a>
                </li>
                <li class="spy-nav-item">
                    <a href="#services" class="spy-nav-link">
                        <span class="spy-dot"></span>
                        <span class="spy-label">Nos services</span>
                    </a>
                </li>
                <li class="spy-nav-item">
                    <a href="#actualites" class="spy-nav-link">
                        <span class="spy-dot"></span>
                        <span class="spy-label">Actualités</span>
                    </a>
                </li>
                <li class="spy-nav-item">
                    <a href="#patrimoine" class="spy-nav-link">
                        <span class="spy-dot"></span>
                        <span class="spy-label">Patrimoine culturel</span>
                    </a>
                </li>
                <li class="spy-nav-item">
                    <a href="#diversite" class="spy-nav-link">
                        <span class="spy-dot"></span>
                        <span class="spy-label">Secteurs Culturels</span>
                    </a>
                </li>
                <li class="spy-nav-item">
                    <a href="#manifests" class="spy-nav-link">
                        <span class="spy-dot"></span>
                        <span class="spy-label">Manifestations & Festivals</span>
                    </a>
                </li>
                <li class="spy-nav-item">
                    <a href="#sponsors" class="spy-nav-link">
                        <span class="spy-dot"></span>
                        <span class="spy-label">Partenaires</span>
                    </a>
                </li>
                <li class="spy-nav-item" id="cta-band">
                    <a href="#cta-band" class="spy-nav-link">
                        <span class="spy-dot"></span>
                        <span class="spy-label">Dépôt de dossiers</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

@endsection
<script>
  
    window.mapLocations = @json($mapLocations);
    window.mapCategoriesMeta = @json($mapCategories);
</script>
