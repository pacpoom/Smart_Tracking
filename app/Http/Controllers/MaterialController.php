<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;


class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get per_page value from request, default to 15
        $perPage = $request->input('per_page', 15);

        // Eager load both all pfeps and the primary one for efficiency
        $query = Material::with(['pfeps', 'primaryPfep']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            // Group the search conditions to avoid conflicts with other clauses
            $query->where(function ($q) use ($search) {
                $q->where('material_number', 'like', "%{$search}%")
                  ->orWhere('material_name', 'like', "%{$search}%");
            });
        }

        // Use the perPage variable for pagination and order by latest
        $materials = $query->latest()->paginate($perPage);
        
        // Pass materials and perPage to the view
        return view('materials.index', compact('materials', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('materials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'material_number' => 'required|string|max:255|unique:material,material_number',
            'material_name' => 'required|string|max:255',
            'unit' => 'nullable|string|max:50',
        ]);

        Material::create($request->all());

        return redirect()->route('materials.index')
            ->with('success', 'Material created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        // Not used for this module, redirect to index
        return redirect()->route('materials.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Material $material)
    {
        return view('materials.edit', compact('material'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material)
    {
        $request->validate([
            'material_number' => 'required|string|max:255|unique:material,material_number,' . $material->id,
            'material_name' => 'required|string|max:255',
            'unit' => 'nullable|string|max:50',
        ]);

        $material->update($request->all());

        return redirect()->route('materials.index')
            ->with('success', 'Material updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material)
    {
        $material->delete();

        return redirect()->route('materials.index')
            ->with('success', 'Material deleted successfully.');
    }

    public function search(Request $request)
    {
        $search = $request->term;
        $materials = Material::where('material_number', 'LIKE', "%{$search}%")
            ->orWhere('material_name', 'LIKE', "%{$search}%")
            ->limit(15)
            ->get();

        return response()->json($materials);
    }
}