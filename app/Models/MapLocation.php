<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MapLocation extends Model
{
    use HasFactory;

    

    protected $fillable = [
        'map_category_id',
        'name',
        'lat',
        'lng',
        'description',
        'image',
        'is_active',
        'order',
    ];

    protected $casts = [
        'lat'       => 'float',
        'lng'       => 'float',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(MapCategory::class, 'map_category_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::url($this->image) : null;
    }
}
