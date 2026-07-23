@extends('shared.layouts.backoffice')

@section('page-title', 'Certification - Unité d\'Encadrement des Investisseurs')

@vite(['resources/assets/css/investisseurs.css'])

@section('content')
    <div class="page active">
        <div class="livre-header">
            <div>
                <div class="livre-title">🏅 Certification des investisseurs culturels</div>
                <div class="livre-subtitle">Attribution des certifications aux investisseurs qualifiés</div>
            </div>
            <button class="btn btn-gold" onclick="openCreateModal()"><span
                    class="livre-action-icon icon-add-btn"></span>Nouvelle demande</button>
        </div>

        <!-- Workflow Guide -->
        <div class="workflow-guide">
            <div class="workflow-guide-header"><span class="guide-icon"></span><span>📋 Guide - Certification</span><span
                    class="guide-badge">4 étapes</span></div>
            <div class="workflow-guide-steps">
                <div class="guide-step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <div class="step-title">📥 Demande</div>
                        <div class="step-desc">Soumission dossier</div>
                    </div>
                </div>
                <div class="guide-arrow">→</div>
                <div class="guide-step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <div class="step-title">🔍 Évaluation</div>
                        <div class="step-desc">Analyse critères</div>
                    </div>
                </div>
                <div class="guide-arrow">→</div>
                <div class="guide-step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <div class="step-title">✅ Décision</div>
                        <div class="step-desc">Validation certification</div>
                    </div>
                </div>
                <div class="guide-arrow">→</div>
                <div class="guide-step">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <div class="step-title">🔔 Notification</div>
                        <div class="step-desc">Certificat délivré</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-head">
                <div class="panel-title">📋 Demandes de certification</div>
            </div>
            <div class="panel-body">
                <div style="text-align: center; padding: 60px 20px; color: var(--text3);">
                    <div style="font-size: 48px; margin-bottom: 16px;">🏅</div>
                    <div style="font-size: 16px; font-weight: 600; margin-bottom: 8px;">Module en cours de développement
                    </div>
                    <div style="font-size: 13px;">Cette fonctionnalité sera disponible prochainement.</div>
                </div>
            </div>
        </div>
    </div>
@endsection
