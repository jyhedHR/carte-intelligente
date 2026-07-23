<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = $this->findUserByLogin($request->input('login'));

        if (!$user || !Hash::check($request->password, $user->getAuthPassword())) {
            throw ValidationException::withMessages([
                'login' => 'Identifiants incorrects.',
            ]);
        }

        if (!$user->actif) {
            throw ValidationException::withMessages([
                'login' => 'Votre compte est désactivé.',
            ]);
        }

Auth::login($user, $request->boolean('remember'));
$request->session()->regenerate();

return redirect()->intended(route('home'));
    }

    protected function findUserByLogin(string $loginInput): ?User
    {
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            return User::where('email', $loginInput)->first();
        }

        if (is_numeric($loginInput) && strlen($loginInput) === 8) {
            return User::where('cin', $loginInput)->first();
        }

        return User::where('nom', $loginInput)->first();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
