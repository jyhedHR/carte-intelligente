<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class MapCategory extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'slug',
        'color',
        'order',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (MapCategory $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function locations(): HasMany
    {
        return $this->hasMany(MapLocation::class);
    }
}
