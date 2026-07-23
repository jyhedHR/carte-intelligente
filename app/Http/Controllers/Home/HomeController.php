<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\MapCategory;
use App\Models\MapLocation;

class HomeController extends Controller
{
    /**
     * Renders the home page (used by both '/' and '/dashboard').
     *
     * If you already have other data the home view needs (stats, news,
     * announcements, etc.), add it to the array passed to view() below —
     * just don't remove 'mapLocations' / 'mapCategories', the map section
     * of home.blade.php depends on them.
     */
    public function index()
    {
        $categories = MapCategory::orderBy('order')->orderBy('name')->get();

        $locations = MapLocation::with('category')
            ->where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(function (MapLocation $loc) {
                return [
                    'name'         => $loc->name,
                    'coords'       => [$loc->lat, $loc->lng],
                    'category'     => $loc->category->name,
                    'categorySlug' => $loc->category->slug,
                    'color'        => $loc->category->color,
                    'description'  => $loc->description,
                    'img'          => $loc->image_url, // null if no image uploaded
                ];
            })
            ->values();

        $mapCategories = $categories->map(function (MapCategory $cat) {
            return [
                'name'  => $cat->name,
                'slug'  => $cat->slug,
                'color' => $cat->color,
            ];
        })->values();

        return view('home', [
            // ── add any other existing data your home page needs here ──
            'mapLocations'  => $locations,
            'mapCategories' => $mapCategories,
        ]);
    }
}
