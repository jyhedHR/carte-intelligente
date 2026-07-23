/* ═══════════════════════════════════════════
   i18n.js — Traductions FR / AR
   GED — Portail Ministère Affaires Culturelles
═══════════════════════════════════════════ */

const i18n = {
  fr: {
    /* NAV */
    nav_home:      'Accueil',
    nav_login:     'Connexion',
    nav_register:  'Créer un compte',
    nav_tagline:   'Portail des Démarches Culturelles — Tunisie',
    nav_brand:     'وزارة الشؤون الثقافية · Ministère des Affaires Culturelles',

    /* HOME — hero */
    hero_badge:    'Portail Officiel · Dématérialisation des Démarches',
    hero_title_1:  'Gérez vos',
    hero_title_2:  'démarches culturelles',
    hero_title_3:  'en ligne',
    hero_desc:     'Dépôt de dossiers, suivi en temps réel, workflows automatisés et intelligence artificielle — tout en un seul portail sécurisé.',
    hero_cta_main: 'Déposer une demande',
    hero_cta_sec:  'Suivre mon dossier',

    /* HOME — stats */
    stat_1_val: '27+', stat_1_lbl: 'Processus',
    stat_2_val: '6',   stat_2_lbl: 'Directions',
    stat_3_val: '48h', stat_3_lbl: 'Délai moyen',
    stat_4_val: '100%',stat_4_lbl: 'Numérique',

    /* HOME — services */
    services_label: 'Nos services',
    services_title: 'Choisissez votre démarche',
    services_sub:   'Accédez à l\'ensemble des services du ministère en ligne',
    s1_name: 'Carte Professionnelle Artistique',
    s1_desc: 'Demande, renouvellement ou duplicata de carte professionnelle d\'artiste',
    s2_name: 'Attestations & Certificats',
    s2_desc: 'Attestation CNSS, certificats d\'exercice, attestations de participation',
    s3_name: 'Autorisation de Tournage',
    s3_desc: 'Demandes d\'autorisation pour productions cinématographiques et audiovisuelles',
    s4_name: 'Livre & Édition',
    s4_desc: 'Dépôt légal, ISBN, soutien éditorial, salons du livre',
    s5_name: 'Diplômes de Musique',
    s5_desc: 'Inscriptions aux examens de musique arabe et d\'instrumentiste',
    s6_name: 'Investisseurs Culturels',
    s6_desc: 'Dossiers d\'investissement culturel, agréments et certifications',
    badge_demat: 'Dématérialisé',
    badge_48h: 'Délai: 48h',
    badge_days: '5–7 jours',
    badge_com: 'Commission',
    badge_cand: 'Candidature',
    badge_prio: 'Haute priorité',

    /* HOME — bottom CTA */
    home_cta_track:  'Suivre un dossier existant',
    home_cta_login:  'Se connecter →',

    /* LOGIN */
    login_title:    'Connexion',
    login_sub:      'Bienvenue. Entrez vos identifiants pour accéder à votre espace.',
    login_email_lbl:'Adresse email ou CIN',
    login_email_ph: 'votre@email.tn ou 12345678',
    login_pass_lbl: 'Mot de passe',
    login_pass_ph:  '••••••••••',
    login_remember: 'Se souvenir de moi',
    login_forgot:   'Mot de passe oublié ?',
    login_btn:      'Se connecter',
    login_sso:      'Connexion SSO Institutionnel',
    login_no_acc:   'Pas encore de compte ?',
    login_create:   'Créer un compte',
    login_back:     '← Retour au portail',

    /* REGISTER */
    reg_title:      'Créer un compte',
    reg_sub:        'Rejoignez le portail officiel du Ministère des Affaires Culturelles',
    reg_type_label: 'Type de compte',
    reg_type_phys:  'Personne Physique',
    reg_type_moral: 'Personne Morale',
    reg_fname:      'Prénom',
    reg_fname_ph:   'Foulen',
    reg_lname:      'Nom',
    reg_lname_ph:   'Ben Mansour',
    reg_cin_lbl:    'Numéro CIN',
    reg_cin_ph:     '12345678',
    reg_cin_hint:   'Vérification automatique via le registre national',
    reg_cin_ok:     '✓ Identité vérifiée',
    reg_cin_err:    '✗ CIN introuvable',
    reg_mf_lbl:     'Matricule Fiscal',
    reg_mf_ph:      '0000000/A/M/000',
    reg_email_lbl:  'Adresse email',
    reg_email_ph:   'votre@email.tn',
    reg_phone_lbl:  'Téléphone',
    reg_phone_ph:   '+216 XX XXX XXX',
    reg_pass_lbl:   'Mot de passe',
    reg_pass_ph:    'Min. 8 caractères',
    reg_pass2_lbl:  'Confirmer le mot de passe',
    reg_pass2_ph:   'Répétez le mot de passe',
    reg_terms:      'J\'accepte les',
    reg_terms_link: 'conditions d\'utilisation',
    reg_terms_and:  'et la',
    reg_priv_link:  'politique de confidentialité',
    reg_btn:        'Créer mon compte',
    reg_have_acc:   'Déjà inscrit ?',
    reg_login_link: 'Se connecter',
    reg_back:       '← Retour au portail',

    /* FOOTER */
    footer_rights:  '© 2026 Ministère des Affaires Culturelles — République Tunisienne',
    footer_legal:   'Mentions légales',
    footer_contact: 'Contact',
    footer_faq:     'FAQ',
    nav_workflow:   'Workflow ⚙️',
    nav_admin:      'Admin',
    nav_dashboard:  'Mon espace',
    nav_apply:      'Déposer',
  },

  ar: {
    /* NAV */
    nav_home:      'الرئيسية',
    nav_login:     'تسجيل الدخول',
    nav_register:  'إنشاء حساب',
    nav_tagline:   'بوابة الإجراءات الثقافية — تونس',
    nav_brand:     'وزارة الشؤون الثقافية · Ministère des Affaires Culturelles',

    /* HOME — hero */
    hero_badge:    'البوابة الرسمية · رقمنة الإجراءات الإدارية',
    hero_title_1:  'أنجز معاملاتك',
    hero_title_2:  'الثقافية',
    hero_title_3:  'عبر الإنترنت',
    hero_desc:     'إيداع الملفات، والمتابعة الآنية، وسير العمل الآلي والذكاء الاصطناعي — كل ذلك في بوابة آمنة واحدة.',
    hero_cta_main: 'إيداع طلب',
    hero_cta_sec:  'متابعة ملفي',

    /* HOME — stats */
    stat_1_val: '+٢٧', stat_1_lbl: 'إجراء',
    stat_2_val: '٦',   stat_2_lbl: 'إدارات',
    stat_3_val: '٤٨س', stat_3_lbl: 'متوسط المدة',
    stat_4_val: '١٠٠%',stat_4_lbl: 'رقمي',

    /* HOME — services */
    services_label: 'خدماتنا',
    services_title: 'اختر إجراءك',
    services_sub:   'يمكنك الوصول إلى جميع خدمات الوزارة عبر الإنترنت',
    s1_name: 'البطاقة المهنية للفنانين',
    s1_desc: 'طلب أو تجديد أو نسخة للبطاقة المهنية للفنان',
    s2_name: 'الشهادات والمصادقات',
    s2_desc: 'شهادة ممارسة المهنة الفنية، شهادات الانخراط',
    s3_name: 'إذن التصوير',
    s3_desc: 'طلبات التصاريح للإنتاجات السينمائية والسمعية البصرية',
    s4_name: 'الكتاب والنشر',
    s4_desc: 'الإيداع القانوني، الدعم، المعارض',
    s5_name: 'شهادات الموسيقى',
    s5_desc: 'التسجيل في امتحانات الموسيقى العربية والعزف',
    s6_name: 'المستثمرون الثقافيون',
    s6_desc: 'ملفات الاستثمار الثقافي، الاعتمادات والشهادات',
    badge_demat: 'رقمي',
    badge_48h: 'المدة: ٤٨ س',
    badge_days: '٥–٧ أيام',
    badge_com: 'لجنة',
    badge_cand: 'ترشح',
    badge_prio: 'أولوية عالية',

    /* HOME — bottom CTA */
    home_cta_track: 'متابعة ملف موجود',
    home_cta_login: 'تسجيل الدخول ←',

    /* LOGIN */
    login_title:    'تسجيل الدخول',
    login_sub:      'مرحباً. أدخل بيانات الدخول للوصول إلى فضائك الشخصي.',
    login_email_lbl:'البريد الإلكتروني أو رقم البطاقة الوطنية',
    login_email_ph: 'votre@email.tn أو 12345678',
    login_pass_lbl: 'كلمة المرور',
    login_pass_ph:  '••••••••••',
    login_remember: 'تذكّرني',
    login_forgot:   'نسيت كلمة المرور؟',
    login_btn:      'تسجيل الدخول',
    login_sso:      'الدخول عبر SSO المؤسسي',
    login_no_acc:   'لا تملك حساباً؟',
    login_create:   'إنشاء حساب',
    login_back:     '← العودة للبوابة',

    /* REGISTER */
    reg_title:      'إنشاء حساب',
    reg_sub:        'انضم إلى البوابة الرسمية لوزارة الشؤون الثقافية',
    reg_type_label: 'نوع الحساب',
    reg_type_phys:  'شخص طبيعي',
    reg_type_moral: 'شخص معنوي',
    reg_fname:      'الاسم',
    reg_fname_ph:   'فلان',
    reg_lname:      'اللقب',
    reg_lname_ph:   'بن منصور',
    reg_cin_lbl:    'رقم البطاقة الوطنية',
    reg_cin_ph:     '12345678',
    reg_cin_hint:   'التحقق التلقائي عبر السجل الوطني',
    reg_cin_ok:     '✓ تم التحقق من الهوية',
    reg_cin_err:    '✗ البطاقة غير موجودة',
    reg_mf_lbl:     'المعرف الجبائي',
    reg_mf_ph:      '0000000/A/M/000',
    reg_email_lbl:  'البريد الإلكتروني',
    reg_email_ph:   'votre@email.tn',
    reg_phone_lbl:  'الهاتف',
    reg_phone_ph:   '+216 XX XXX XXX',
    reg_pass_lbl:   'كلمة المرور',
    reg_pass_ph:    '٨ أحرف على الأقل',
    reg_pass2_lbl:  'تأكيد كلمة المرور',
    reg_pass2_ph:   'أعد كتابة كلمة المرور',
    reg_terms:      'أوافق على',
    reg_terms_link: 'شروط الاستخدام',
    reg_terms_and:  'و',
    reg_priv_link:  'سياسة الخصوصية',
    reg_btn:        'إنشاء حسابي',
    reg_have_acc:   'لديك حساب بالفعل؟',
    reg_login_link: 'تسجيل الدخول',
    reg_back:       '← العودة للبوابة',

    /* FOOTER */
    footer_rights:  '© ٢٠٢٦ وزارة الشؤون الثقافية — الجمهورية التونسية',
    footer_legal:   'ملاحظات قانونية',
    footer_contact: 'اتصل بنا',
    footer_faq:     'الأسئلة الشائعة',
    nav_workflow:   'سير العمل ⚙️',
    nav_admin:      'إدارة 🛡',
    nav_dashboard:  'مساحتي',
    nav_apply:      'إيداع',
  }
};

/* ── Apply language to DOM ── */
function applyLang(lang) {
  const t = i18n[lang];
  const dir = lang === 'ar' ? 'rtl' : 'ltr';
  document.documentElement.setAttribute('lang', lang);
  document.documentElement.setAttribute('dir', dir);
  document.documentElement.style.setProperty('--text-align', dir === 'rtl' ? 'right' : 'left');

  /* update all [data-i18n] elements */
  document.querySelectorAll('[data-i18n]').forEach(el => {
    const key = el.getAttribute('data-i18n');
    if (t[key] !== undefined) {
      if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') el.placeholder = t[key];
      else el.textContent = t[key];
    }
  });

  /* toggle lang buttons */
  document.querySelectorAll('.lang-btn').forEach(btn => {
    btn.classList.toggle('active', btn.dataset.lang === lang);
  });

  /* persist */
  localStorage.setItem('ged_lang', lang);
}

/*function initLang() {
  const saved = localStorage.getItem('ged_lang') || 'fr';
  applyLang(saved);
}*/

/* Call on every page */
document.addEventListener('DOMContentLoaded', initLang);