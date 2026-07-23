<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'cin',
        'mot_de_passe',
        'actif',
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    protected $casts = [
        'actif'   => 'boolean',
        'is_admin' => 'boolean',
    ];

    // Tell Laravel which column is the password
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->prenom} {$this->nom}");
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }
}
