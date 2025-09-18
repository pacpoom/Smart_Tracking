<?php

namespace App\Http\Controllers;

use App\Models\Pfep;
use App\Models\Material;
use Illuminate\Http\Request;

class PfepController extends Controller
{
    public function index(Request $request)
    {
        $query = Pfep::with('material');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('material', function ($q) use ($search) {
                $q->where('material_number', 'like', '%' . $search . '%')
                    ->orWhere('material_name', 'like', '%' . $search . '%');
            })->orWhere('model', 'like', '%' . $search . '%');
        }

        $perPage = $request->input('per_page', 25);
        $pfeps = $query->orderBy('id', 'desc')->paginate($perPage);

        return view('pfeps.index', compact('pfeps', 'perPage'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'material_id' => 'required|exists:material,id|unique:pfep,material_id',
            'model' => 'nullable|string|max:255',
            'part_type' => 'nullable|string|max:255',
            'uloc' => 'nullable|string|max:255',
            'pull_type' => 'nullable|string|max:255',
            'line_side' => 'nullable|string|max:255',
        ]);

        Pfep::create($request->all());

        return redirect()->route('pfeps.index')->with('success', 'PFEP record created successfully.');
    }

    public function edit(Pfep $pfep)
    {
        return view('pfeps.edit', compact('pfep'));
    }

    public function update(Request $request, Pfep $pfep)
    {
        $request->validate([
            'material_id' => 'required|exists:material,id|unique:pfep,material_id,' . $pfep->id,
            'model' => 'nullable|string|max:255',
            'part_type' => 'nullable|string|max:255',
            'uloc' => 'nullable|string|max:255',
            'pull_type' => 'nullable|string|max:255',
            'line_side' => 'nullable|string|max:255',
        ]);

        $pfep->update($request->all());

        return redirect()->route('pfeps.index')->with('success', 'PFEP record updated successfully.');
    }

    public function destroy(Pfep $pfep)
    {
        $pfep->delete();
        return redirect()->route('pfeps.index')->with('success', 'PFEP record deleted successfully.');
    }
}
