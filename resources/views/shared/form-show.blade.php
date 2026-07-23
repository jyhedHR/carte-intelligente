@extends('shared.layouts.frontoffice')

@section('page-title', $form->titre)

@section('content')
<style>
    /* ============================================
       ENHANCED FORM STYLES - Matching Profile Theme
    ============================================ */
    .form-page-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 24px;
    }

    /* Header Card */
    .form-header-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 32px 40px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow);
    }

    .form-header-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--gold), var(--gold2), var(--gold));
    }

    .form-header-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 24px;
    }

    .form-title-section {
        flex: 1;
    }

    .form-department-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }

    .form-title {
        font-family: var(--font-display);
        font-size: 32px;
        font-weight: 700;
        color: var(--text);
        margin: 0 0 8px 0;
    }

    .form-title em {
        color: var(--gold);
        font-style: italic;
    }

    .form-subtitle {
        font-size: 14px;
        color: var(--text2);
        margin: 0 0 20px 0;
    }

    .breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: var(--text3);
        flex-wrap: wrap;
    }

    .breadcrumb-nav a {
        color: var(--gold);
        text-decoration: none;
        transition: color 0.2s;
    }

    .breadcrumb-nav a:hover {
        color: var(--gold2);
    }

    .breadcrumb-nav .separator {
        color: var(--text3);
    }

    .breadcrumb-nav .active {
        color: var(--gold);
        font-weight: 500;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 24px;
        background: transparent;
        border: 1.5px solid var(--border);
        border-radius: 40px;
        text-decoration: none;
        color: var(--text2);
        font-size: 13px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .btn-back:hover {
        border-color: var(--gold);
        color: var(--gold);
        transform: translateX(-4px);
    }

    /* Alert Styles */
    .alert-custom {
        padding: 16px 24px;
        border-radius: 12px;
        margin-bottom: 24px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success-custom {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #10b981;
    }

    .alert-error-custom {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
    }

    .alert-icon {
        font-size: 18px;
    }

    .alert-list {
        margin: 8px 0 0 20px;
        font-size: 13px;
    }

    /* Form Container */
    .form-main-container {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 40px;
        box-shadow: var(--shadow);
    }

    /* Form.io Custom Styling */
    #formio-container .formio-form {
        background: transparent;
    }

    #formio-container .formio-component {
        margin-bottom: 28px;
    }

    #formio-container .form-label {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 8px;
        display: block;
    }

    #formio-container .form-label .required {
        color: #ef4444;
    }

    #formio-container .form-label .required:after {
        content: " *";
    }

    #formio-container input:not([type="checkbox"]):not([type="radio"]),
    #formio-container select,
    #formio-container textarea {
        width: 100%;
        padding: 12px 16px;
        background: var(--bg);
        border: 1.5px solid var(--border);
        border-radius: 10px;
        color: var(--text);
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s;
    }

    #formio-container input:focus,
    #formio-container select:focus,
    #formio-container textarea:focus {
        outline: none;
        border-color: var(--gold);
        box-shadow: 0 0 0 3px rgba(201, 168, 76, 0.12);
    }

    #formio-container .has-error input,
    #formio-container .has-error select,
    #formio-container .has-error textarea {
        border-color: #ef4444;
    }

    #formio-container .help-block {
        color: #ef4444;
        font-size: 12px;
        margin-top: 6px;
    }

    /* Panel Styling */
    #formio-container .formio-component-panel {
        background: var(--bg2);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 28px;
    }

    #formio-container .panel-title {
        font-family: var(--font-display);
        font-size: 20px;
        font-weight: 700;
        color: var(--gold);
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--gold);
        display: inline-block;
    }

    /* Checkbox & Radio */
    #formio-container .formio-component-checkbox,
    #formio-container .formio-component-radio {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 0;
    }

    #formio-container .formio-component-checkbox input,
    #formio-container .formio-component-radio input {
        width: 18px;
        height: 18px;
        margin: 0;
        cursor: pointer;
        accent-color: var(--gold);
    }

    #formio-container .formio-component-checkbox label,
    #formio-container .formio-component-radio label {
        margin: 0;
        cursor: pointer;
        font-weight: normal;
        color: var(--text2);
    }

    /* File Upload */
    #formio-container .formio-component-file .fileSelector {
        padding: 32px;
        background: var(--bg);
        border: 2px dashed var(--border);
        border-radius: 16px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    #formio-container .formio-component-file .fileSelector:hover {
        border-color: var(--gold);
        background: var(--gold-soft);
    }

    #formio-container .formio-component-file .fileSelector .btn {
        background: linear-gradient(135deg, var(--gold), var(--gold3)) !important;
        color: #111 !important;
        border: none !important;
        padding: 10px 24px !important;
        border-radius: 40px !important;
        cursor: pointer !important;
        font-weight: 600 !important;
        margin-top: 16px !important;
        transition: all 0.3s !important;
    }

    #formio-container .formio-component-file .fileSelector .btn:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 12px rgba(201, 168, 76, 0.3) !important;
    }

    /* Select Dropdown */
    #formio-container select {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%239a8f80' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 16px center;
        background-size: 16px;
    }

    /* Grid Layout */
    #formio-container .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -12px;
    }

    #formio-container .col-xs-6,
    #formio-container .col-sm-6,
    #formio-container .col-md-6 {
        flex: 0 0 50%;
        padding: 0 12px;
    }

    #formio-container .col-xs-12,
    #formio-container .col-sm-12,
    #formio-container .col-md-12 {
        flex: 0 0 100%;
        padding: 0 12px;
    }

    /* Signature Pad */
    .signature-pad-container {
        border: 1.5px solid var(--border);
        border-radius: 12px;
        background: var(--bg);
        padding: 8px;
    }

    .signature-pad-container canvas {
        border-radius: 8px;
        width: 100%;
        height: 200px;
        cursor: crosshair;
    }

    .signature-actions {
        display: flex;
        gap: 8px;
        margin-top: 12px;
    }

    .btn-signature-clear {
        padding: 8px 16px;
        background: transparent;
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text2);
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-signature-clear:hover {
        border-color: var(--gold);
        color: var(--gold);
    }

    /* Form Actions */
    .form-actions-footer {
        margin-top: 40px;
        padding-top: 24px;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: flex-end;
        gap: 16px;
    }

    .btn-cancel-form {
        padding: 12px 32px;
        background: transparent;
        border: 1.5px solid var(--border);
        border-radius: 40px;
        color: var(--text2);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 14px;
    }

    .btn-cancel-form:hover {
        border-color: var(--gold);
        color: var(--gold);
        transform: translateY(-2px);
    }

    .btn-submit-form {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 40px;
        background: linear-gradient(135deg, var(--gold), var(--gold3));
        border: none;
        border-radius: 40px;
        color: #111;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 14px rgba(201, 168, 76, 0.35);
        font-size: 14px;
    }

    .btn-submit-form:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(201, 168, 76, 0.45);
    }

    /* Loading State */
    .form-loading {
        text-align: center;
        padding: 60px;
        color: var(--text3);
    }

    .form-loading::after {
        content: '';
        display: inline-block;
        width: 24px;
        height: 24px;
        margin-left: 12px;
        border: 2px solid var(--gold);
        border-top-color: transparent;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        vertical-align: middle;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-page-wrapper {
            padding: 20px 16px;
        }

        .form-header-card {
            padding: 24px;
        }

        .form-header-content {
            flex-direction: column;
        }

        .form-title {
            font-size: 26px;
        }

        .form-main-container {
            padding: 24px;
        }

        #formio-container .col-xs-6,
        #formio-container .col-sm-6,
        #formio-container .col-md-6 {
            flex: 0 0 100%;
        }

        .form-actions-footer {
            flex-direction: column-reverse;
        }

        .btn-cancel-form,
        .btn-submit-form {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="form-page-wrapper">
    {{-- Header Card --}}
    <div class="form-header-card">
        <div class="form-header-content">
            <div class="form-title-section">
                <div class="form-department-icon">
                    @if($department === 'audiovisuel') 🎬
                    @elseif($department === 'livre') 📚
                    @elseif($department === 'musique') 🎵
                    @elseif($department === 'arts-plastiques') 🎨
                    @elseif($department === 'scenique') 🎭
                    @elseif($department === 'investisseurs') 💰
                    @else 📄
                    @endif
                </div>
                <h1 class="form-title">Mon <em>{{ $form->titre }}</em></h1>
                <p class="form-subtitle">Veuillez remplir tous les champs obligatoires marqués d'un <span style="color: #ef4444;">*</span></p>
                <div class="breadcrumb-nav">
                    <a href="{{ route('home') }}">Accueil</a>
                    <span class="separator">›</span>
                    <a href="{{ route('profile.index') }}">Mon Profil</a>
                    <span class="separator">›</span>
                    <span>{{ $departmentName ?? ucfirst($department) }}</span>
                    <span class="separator">›</span>
                    <span class="active">{{ $form->titre }}</span>
                </div>
            </div>
            <a href="{{ route('profile.index') }}" class="btn-back">
                <span>←</span>
                Retour au profil
            </a>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert-custom alert-success-custom">
            <span class="alert-icon">✅</span>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="alert-custom alert-error-custom">
            <span class="alert-icon">❌</span>
            <div>
                <strong>Veuillez corriger les erreurs suivantes :</strong>
                <ul class="alert-list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
{{-- Prefill notice --}}
@if(isset($prefillData) && count($prefillData) > 0)
<div class="alert-custom" style="background:rgba(201,168,76,0.08);border:1px solid rgba(201,168,76,0.3);border-radius:10px;padding:14px 20px;margin-bottom:20px;display:flex;gap:12px;align-items:flex-start;">
    <span style="font-size:20px;">📋</span>
    <div>
        <strong style="color:var(--gold);">Formulaire pré-rempli</strong>
        <p style="font-size:13px;color:var(--text2);margin:4px 0 0;">
            Les informations de votre demande précédente ont été copiées. Vérifiez et modifiez ce qui a changé avant de soumettre.
        </p>
    </div>
</div>
@endif
    {{-- Form Container --}}
    <div class="form-main-container">
        {{-- AFTER (correct — $departmentSlug is the plain string) --}}
<form id="dynamicForm" method="POST" action="{{ route('citoyen.forms.submit', [$departmentSlug, $form->slug]) }}" enctype="multipart/form-data">
            @csrf
            <div id="formio-container">
                <div class="form-loading">Chargement du formulaire</div>
            </div>
            <div class="form-actions-footer">
                <button type="button" class="btn-cancel-form" onclick="window.location.href='{{ route('profile.index') }}'">
                    Annuler
                </button>
                <button type="submit" class="btn-submit-form" id="submitBtn">
                    <span>📤</span>
                    Soumettre la demande
                </button>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.form.io/formiojs/formio.full.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let formSchema = @json($formSchema);
        const department = @json($department);

        if (!formSchema.components) {
            formSchema.components = [];
        }

        // Add hidden user_id field
        formSchema.components.push({
            type: "hidden",
            key: "user_id",
            defaultValue: {{ Auth::id() }},
            input: true
        });

        const container = document.getElementById('formio-container');
        if (!container) return;

        container.innerHTML = '';
const prefillData = @json($prefillData ?? []);

Formio.createForm(container, formSchema).then(form => {
    console.log('Form loaded successfully');

    // ── Prefill: inject previous submission data after form renders ──
    if (prefillData && Object.keys(prefillData).length > 0) {
        // Merge with user_id so it doesn't get wiped
        form.setValue({
            data: {
                ...prefillData,
                user_id: {{ Auth::id() }}
            }
        });
        console.log('Prefill applied:', prefillData);
    }
    // ────────────────────────────────────────────────────────────────

            const dynamicForm = document.getElementById('dynamicForm');
            const submitBtn = document.getElementById('submitBtn');

            if (submitBtn) {
                submitBtn.addEventListener('click', async function(e) {
                    e.preventDefault();

                    try {
                        // Get the submission data from Form.io
                        const submission = await form.submit();
                        console.log('Form validation passed', submission);

                        // Clear any existing hidden inputs
                        const existingInputs = dynamicForm.querySelectorAll('input[data-formio-field]');
                        existingInputs.forEach(input => input.remove());

                        // Add hidden inputs for all form data
                        Object.keys(submission.data).forEach(key => {
                            const value = submission.data[key];
                            if (value !== undefined && value !== null && value !== '') {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = key;
                                input.value = typeof value === 'object' ? JSON.stringify(value) : value;
                                input.setAttribute('data-formio-field', key);
                                dynamicForm.appendChild(input);
                            }
                        });

                        // Submit the form
                        dynamicForm.submit();

                    } catch (error) {
                        console.error('Validation error:', error);
                        showToast('Veuillez remplir tous les champs obligatoires correctement', 'error');
                    }
                });
            }
        }).catch(error => {
            console.error('Error loading form:', error);
            container.innerHTML = `
                <div class="alert-custom alert-error-custom">
                    <span class="alert-icon">❌</span>
                    <div>Erreur lors du chargement du formulaire.</div>
                </div>
            `;
        });
    });

    function showToast(message, type = 'success') {
        const existingToast = document.getElementById('form-toast');
        if (existingToast) existingToast.remove();

        const toast = document.createElement('div');
        toast.id = 'form-toast';
        toast.style.cssText = `
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            padding: 14px 28px;
            border-radius: 50px;
            color: white;
            font-size: 14px;
            font-weight: 600;
            z-index: 10000;
            background: ${type === 'success' ? '#10b981' : '#ef4444'};
            animation: slideIn 0.3s ease;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            white-space: nowrap;
        `;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }
</script>
@endsection
