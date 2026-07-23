<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\MapCategory;
use App\Models\MapLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MapLocationController extends Controller
{
    public function index()
    {
        $locations = MapLocation::with('category')
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        return view('backoffice.home.map_locations.index', compact('locations'));
    }

    public function create()
    {
        $categories = MapCategory::orderBy('name')->get();

        return view('backoffice.home.map_locations.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('map-locations', 'public');
        }

        MapLocation::create($validated);

        return redirect()
            ->route('map-locations.index')
            ->with('success', 'Lieu ajouté avec succès.');
    }

    public function edit(MapLocation $mapLocation)
    {
        $categories = MapCategory::orderBy('name')->get();

        return view('backoffice.home.map_locations.edit', [
            'location'   => $mapLocation,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, MapLocation $mapLocation)
    {
        $validated = $this->validateData($request);

        if ($request->hasFile('image')) {
            // delete the old image before storing the new one
            if ($mapLocation->image) {
                Storage::disk('public')->delete($mapLocation->image);
            }
            $validated['image'] = $request->file('image')->store('map-locations', 'public');
        }

        $mapLocation->update($validated);

        return redirect()
            ->route('map-locations.index')
            ->with('success', 'Lieu mis à jour avec succès.');
    }

    public function destroy(MapLocation $mapLocation)
    {
        if ($mapLocation->image) {
            Storage::disk('public')->delete($mapLocation->image);
        }

        $mapLocation->delete();

        return redirect()
            ->route('map-locations.index')
            ->with('success', 'Lieu supprimé.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'map_category_id' => ['required', 'exists:map_categories,id'],
            'name'             => ['required', 'string', 'max:150'],
            'lat'              => ['required', 'numeric', 'between:-90,90'],
            'lng'              => ['required', 'numeric', 'between:-180,180'],
            'description'      => ['required', 'string'],
            'image'            => ['nullable', 'image', 'max:4096'], // 4MB max
            'is_active'        => ['nullable', 'boolean'],
            'order'            => ['nullable', 'integer'],
        ]) + ['is_active' => $request->boolean('is_active')];
    }
}
