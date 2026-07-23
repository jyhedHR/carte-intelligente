<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink($request->only('email'));

        // Check if request came from admin area (has referer or specific input)
        $fromAdmin = str_contains($request->headers->get('referer', ''), '/admin/settings') ||
                     $request->has('from_admin');

        if ($status === Password::RESET_LINK_SENT) {
            if ($fromAdmin) {
                return redirect()->route('admin.settings.index')
                    ->with('success', 'Un lien de réinitialisation a été envoyé à votre adresse e-mail.');
            }
            return back()->with('status', 'Un lien de réinitialisation a été envoyé à votre adresse e-mail.');
        }

        if ($fromAdmin) {
            return redirect()->route('admin.settings.index')
                ->withErrors(['email' => 'Aucun utilisateur trouvé avec cette adresse e-mail.']);
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
