<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\TunisiaRegions;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register', [
            'regions' => TunisiaRegions::all(),
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'nom'       => ['required', 'string', 'max:255'],
            'prenom'    => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:wfb_users,email'],
            'cin'       => ['required', 'string', 'max:8', 'unique:wfb_users,cin'],   // made required
            'region'    => ['nullable', 'string', Rule::in(TunisiaRegions::all())],
            'password'  => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'nom'          => $request->nom,
            'prenom'       => $request->prenom,
            'email'        => $request->email,
            'cin'          => $request->cin,
            'region'       => $request->region,
            'mot_de_passe' => Hash::make($request->password),   // ← Important change
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect to email verification if needed
        if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        return redirect()->intended(route('dashboard'));
    }
}
