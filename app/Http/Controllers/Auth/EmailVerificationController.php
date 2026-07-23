<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function notice()
    {
        $user = auth()->user();

        if ($user && $user->hasVerifiedEmail()) {
            return $this->redirectAfterVerification($user);
        }

        $isAdmin = session('verification_is_admin', false) || ($user && $user->is_admin);

        return view('auth.verify-email', compact('isAdmin'));
    }

    public function verify(EmailVerificationRequest $request)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Session expirée. Veuillez vous reconnecter.');
        }

        if ($user->hasVerifiedEmail()) {
            return $this->redirectAfterVerification($user);
        }

        // Mark as verified
        $user->forceFill(['email_verified_at' => now()])->save();
        event(new Verified($user));

        return $this->redirectAfterVerification($user);
    }

    public function resend(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->hasVerifiedEmail()) {
            return $this->redirectAfterVerification($user);
        }

        $user->sendEmailVerificationNotification();

        return back()->with('status', 'Un nouveau lien de vérification a été envoyé.');
    }

    /**
     * Decide where to redirect after email verification
     */
    private function redirectAfterVerification($user)
    {
        // If user has NOT completed MFA yet → force MFA setup
        if (!$user->two_factor_confirmed_at) {
            return redirect()->route('mfa.setup')
                ->with('info', 'Veuillez configurer votre authentification à deux facteurs pour continuer.');
        }

        // If MFA is already done → go to dashboard
        return redirect()->route('dashboard')
            ->with('status', 'Votre email a été vérifié avec succès !');
    }
}
