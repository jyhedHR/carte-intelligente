<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PieceJointe extends Model
{
    protected $fillable = ['nom', 'type', 'taille', 'chemin', 'uploaded_at'];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    /** telecharger — returns a temporary download URL */
    public function telecharger(): string
    {
        return Storage::url($this->chemin);
    }

    /** supprimer — deletes the file from disk and removes the record */
    public function supprimer(): bool
    {
        Storage::delete($this->chemin);
        return $this->delete();
    }
}
