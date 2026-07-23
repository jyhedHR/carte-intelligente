<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

public function register(Request $request)
{
    $request->validate([
        'nom'      => ['required', 'string', 'max:100'],
        'prenom'   => ['required', 'string', 'max:100'],
        'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
        'cin'      => ['required', 'string', 'size:8', 'unique:users,cin'],
        'password' => ['required', 'confirmed', Password::min(8)],
    ]);

    User::create([
        'nom'          => $request->nom,
        'prenom'       => $request->prenom,
        'email'        => $request->email,
        'cin'          => $request->cin,
        'mot_de_passe' => Hash::make($request->password),
        'actif'        => true,
    ]);

    return redirect()->route('login')->with('status', 'Compte créé avec succès. Connectez-vous.');
}
}
