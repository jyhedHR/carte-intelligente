<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\MapCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MapCategoryController extends Controller
{
    public function index()
    {
        $categories = MapCategory::orderBy('order')->orderBy('name')->get();

        return view('backoffice.home.map_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('backoffice.home.map_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'color' => ['required', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'order' => ['nullable', 'integer'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // ensure slug uniqueness
        $baseSlug = $validated['slug'];
        $i = 1;
        while (MapCategory::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $baseSlug . '-' . $i++;
        }

        MapCategory::create($validated);

        return redirect()
            ->route('map-categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    public function edit(MapCategory $mapCategory)
    {
        return view('backoffice.home.map_categories.edit', ['category' => $mapCategory]);
    }

    public function update(Request $request, MapCategory $mapCategory)
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:100'],
            'color' => ['required', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'order' => ['nullable', 'integer'],
        ]);

        $mapCategory->update($validated);

        return redirect()
            ->route('map-categories.index')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function destroy(MapCategory $mapCategory)
    {
        // Locations will cascade-delete via the FK constraint (cascadeOnDelete in migration).
        $mapCategory->delete();

        return redirect()
            ->route('   map-categories.index')
            ->with('success', 'Catégorie supprimée.');
    }
}
