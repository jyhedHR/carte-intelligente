@extends('shared.layouts.frontoffice')
@section('content')
<div style="max-width:600px;margin:80px auto;padding:40px;text-align:center;font-family:sans-serif;">
    <div style="font-size:64px;margin-bottom:24px;">🔒</div>

    @if($reason === 'max_submissions')
        <h1 style="color:#c9a84c;font-size:28px;margin-bottom:12px;">Nombre maximal de soumissions atteint</h1>
        <p style="color:#666;font-size:16px;line-height:1.6;margin-bottom:24px;">
            Vous avez déjà soumis <strong>{{ $form->titre }}</strong>
            {{ $submissionCount }} fois, soit le maximum autorisé ({{ $form->max_submissions }}).
        </p>
        <p style="color:#999;font-size:13px;margin-bottom:32px;">
            Vous ne pouvez plus soumettre ce formulaire.
        </p>
    @else
        <h1 style="color:#c9a84c;font-size:28px;margin-bottom:12px;">Document déjà en cours de validité</h1>
        <p style="color:#666;font-size:16px;line-height:1.6;margin-bottom:24px;">
            Vous avez déjà obtenu un <strong>{{ $form->titre }}</strong> qui reste valide jusqu'au :
        </p>
        <div style="background:#fef3c7;border:2px solid #c9a84c;border-radius:12px;padding:20px;margin-bottom:32px;">
            <span style="font-size:24px;font-weight:700;color:#92400e;">
                {{ \Carbon\Carbon::parse($validUntil)->format('d/m/Y') }}
            </span>
        </div>
        <p style="color:#999;font-size:13px;margin-bottom:32px;">
            Vous pourrez soumettre une nouvelle demande à partir de cette date.
        </p>
    @endif

    {{-- ── Appeal / dérogation section ─────────────────────────────── --}}
    <div style="border-top:1px solid #eee;margin-top:8px;padding-top:28px;">

        @if(session('success'))
            <div style="background:#dcfce7;border:1px solid #16a34a;color:#166534;border-radius:8px;padding:12px 16px;margin-bottom:20px;font-size:14px;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background:#fee2e2;border:1px solid #dc2626;color:#991b1b;border-radius:8px;padding:12px 16px;margin-bottom:20px;font-size:14px;">
                {{ session('error') }}
            </div>
        @endif

        {{-- Check for PENDING reclamation first --}}
        @if(isset($pendingReclamation) && $pendingReclamation)
            <p style="color:#92400e;font-size:14px;background:#fef3c7;border-radius:8px;padding:14px;">
                ⏳ Votre demande de dérogation est en cours d'examen par un administrateur.
            </p>
        @else
            {{-- Show the reclamation form - ALWAYS visible when blocked --}}
            <p style="color:#666;font-size:14px;margin-bottom:16px;">
                @if(isset($approvedReclamation) && $approvedReclamation)
                    ✓ Votre dernière demande de dérogation a été approuvée.
                    @if($approvedReclamation->action === 'extra_submission')
                        Vous avez reçu <strong>{{ $approvedReclamation->valeur }}</strong> soumission(s) supplémentaire(s).
                    @elseif($approvedReclamation->action === 'reset_compteur')
                        Votre compteur a été réinitialisé.
                    @elseif($approvedReclamation->action === 'prolonger_validite')
                        La validité a été prolongée de <strong>{{ $approvedReclamation->valeur }}</strong> mois.
                    @endif
                    <br><br>
                    Besoin d'une nouvelle dérogation ? Vous pouvez faire une nouvelle demande.
                @else
                    Une erreur dans une soumission précédente ? Vous pouvez demander à un administrateur
                    de vous accorder une dérogation.
                @endif
            </p>

            <form method="POST" action="{{ route('citoyen.reclamations.store', ['slug' => $form->slug]) }}" style="text-align:left;">
                @csrf
                <input type="hidden" name="department" value="{{ $department->name }}">
                <textarea name="motif" required minlength="10" maxlength="1000" rows="4"
                    placeholder="Expliquez pourquoi vous avez besoin d'une nouvelle soumission (ex : erreur dans le document précédent)..."
                    style="width:100%;border:1px solid #ddd;border-radius:8px;padding:12px;font-size:14px;font-family:inherit;resize:vertical;"></textarea>
                <button type="submit"
                    style="margin-top:12px;padding:10px 24px;background:#fff;border:2px solid #c9a84c;color:#c9a84c;border-radius:40px;font-weight:600;cursor:pointer;">
                    @if(isset($approvedReclamation) && $approvedReclamation)
                        Demander une nouvelle dérogation
                    @else
                        Demander une dérogation
                    @endif
                </button>
            </form>
        @endif
    </div>

    <a href="{{ route('profile.index') }}"
       style="display:inline-block;margin-top:28px;padding:12px 32px;background:#c9a84c;color:#fff;border-radius:40px;text-decoration:none;font-weight:600;">
        ← Retour à mon profil
    </a>
</div>
@endsection
