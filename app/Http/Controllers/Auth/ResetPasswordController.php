<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class ResetPasswordController extends Controller
{
    public function showForm(Request $request, string $token)
    {
        return view('auth.reset-password', ['request' => $request, 'token' => $token]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                // ✅ Important: Use 'mot_de_passe' instead of 'password'
                $user->forceFill([
                    'mot_de_passe'   => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // Check if request came from admin area
        $fromAdmin = str_contains($request->headers->get('referer', ''), '/admin') ||
                     str_contains($request->url(), 'admin');

        if ($status === Password::PASSWORD_RESET) {
            if ($fromAdmin) {
                return redirect()->route('admin.login')
                    ->with('status', 'Votre mot de passe a été réinitialisé avec succès. Veuillez vous connecter.');
            }
            return redirect()->route('login')
                ->with('status', 'Votre mot de passe a été réinitialisé avec succès.');
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
