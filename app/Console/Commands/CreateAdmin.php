<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CreateAdmin extends Command
{
    protected $signature = 'create:admin {email} {password}';

    protected $description = 'Créer un compte Super Admin';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        // Vérifier si l'utilisateur existe déjà
        if (User::where('email', $email)->exists()) {
            $this->error('Un utilisateur avec cet email existe déjà.');
            return;
        }

        // Créer l'utilisateur
        $user = User::create([
            'nom' => 'Admin',
            'prenom' => 'System',
            'email' => $email,
            'cin' => 'ADMIN_' . rand(100000, 999999),
            'email_verified_at' => now(),
            'mot_de_passe' => Hash::make($password),
            'langue' => 'FR',
            'actif' => true,
            'is_admin' => true,
        ]);

        // Ajouter le rôle SUPER_ADMIN (id = 1)
        DB::table('wfb_user_role')->insert([
            'user_id' => $user->id,
            'role_id' => 1,
        ]);

        $this->info('Compte Super Admin créé avec succès.');
    }
}
