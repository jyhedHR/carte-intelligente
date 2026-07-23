<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\MapCategory;
use App\Models\MapLocation;

class MapPublicController extends Controller
{
    /**
     * Display the full-page interactive heritage map.
     * Uses the same data transformation as HomeController so the map works identically.
     * Category colors are used for star markers on the map.
     */
    public function show()
    {
        $categories = MapCategory::orderBy('order')->orderBy('name')->get();

        // Transform locations using same format as HomeController
        $locations = MapLocation::with('category')
            ->where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(function (MapLocation $loc) {
                return [
                    'name'         => $loc->name,
                    'coords'       => [$loc->lat, $loc->lng],           // Array format [lat, lng]
                    'category'     => $loc->category->name,
                    'categorySlug' => $loc->category->slug,
                    'color'        => $loc->category->color,             // Category color for star markers
                    'description'  => $loc->description,
                    'img'          => $loc->image_url,                    // null if no image
                ];
            })
            ->values();

        // Transform categories for legend
        $mapCategories = $categories->map(function (MapCategory $cat) {
            return [
                'name'  => $cat->name,
                'slug'  => $cat->slug,
                'color' => $cat->color,
            ];
        })->values();

        return view('Map locations', [
            'mapLocations'  => $locations,
            'mapCategories' => $mapCategories,
        ]);
    }
}
