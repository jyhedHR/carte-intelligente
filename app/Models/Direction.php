<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direction extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'code'];

    public function demandes()
    {
        return $this->hasMany(Demande::class, 'id_direction');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id_direction');
    }
}
