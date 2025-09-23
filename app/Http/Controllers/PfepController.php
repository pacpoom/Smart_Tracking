<?php

namespace App\Http\Controllers;

use App\Models\Pfep;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function create()
    {
        // ✅ FIX: Added 'Oversea' and 'Overseas' to the exclusion list
        $excludedPartTypes = ['Oversea', '', '-', '0'];
        $part_types = DB::table('pfep')->select('part_type')->distinct()
            ->whereNotNull('part_type')->whereNotIn('part_type', $excludedPartTypes)
            ->orderBy('part_type')->pluck('part_type');

        $pull_types = DB::table('pfep')->select('pull_type')->distinct()
            ->whereNotNull('pull_type')->where('pull_type', '!=', '')->where('pull_type', '!=', '0')->where('pull_type', '!=', '-')
            ->orderBy('pull_type')->pluck('pull_type');

        $line_sides = DB::table('pfep')->select('line_side')->distinct()
            ->whereNotNull('line_side')->where('line_side', '!=', '')->where('line_side', '!=', '0')->where('line_side', '!=', '-')
            ->orderBy('line_side')->pluck('line_side');

        return view('pfeps.create', compact('part_types', 'pull_types', 'line_sides'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'material_number' => 'required|string|exists:material,material_number',
            'model' => 'nullable|string|max:255',
            'part_type' => 'nullable|string|max:255',
            'uloc' => 'nullable|string|max:255',
            'pull_type' => 'nullable|string|max:255',
            'line_side' => 'nullable|string|max:255',
        ]);

        $material = Material::where('material_number', $request->material_number)->firstOrFail();

        $data = $request->except('material_number');
        $data['material_id'] = $material->id;

        Pfep::create($data);

        return redirect()->route('materials.index')->with('success', 'PFEP record created successfully.');
    }

    public function edit(Pfep $pfep)
    {
        // ✅ FIX: Added 'Oversea' and 'Overseas' to the exclusion list
        $excludedPartTypes = ['Oversea', 'Overseas', '', '-', '0'];
        $part_types = DB::table('pfep')->select('part_type')->distinct()
            ->whereNotNull('part_type')->whereNotIn('part_type', $excludedPartTypes)
            ->orderBy('part_type')->pluck('part_type');

        $pull_types = DB::table('pfep')->select('pull_type')->distinct()
            ->whereNotNull('pull_type')->where('pull_type', '!=', '')->where('pull_type', '!=', '0')->where('pull_type', '!=', '-')
            ->orderBy('pull_type')->pluck('pull_type');

        $line_sides = DB::table('pfep')->select('line_side')->distinct()
            ->whereNotNull('line_side')->where('line_side', '!=', '')->where('line_side', '!=', '0')->where('line_side', '!=', '-')
            ->orderBy('line_side')->pluck('line_side');

        return view('pfeps.edit', compact('pfep', 'part_types', 'pull_types', 'line_sides'));
    }

    public function update(Request $request, Pfep $pfep)
    {
        $request->validate([
            'material_number' => 'required|string|exists:material,material_number',
            'model' => 'nullable|string|max:255',
            'part_type' => 'nullable|string|max:255',
            'uloc' => 'nullable|string|max:255',
            'pull_type' => 'nullable|string|max:255',
            'line_side' => 'nullable|string|max:255',
        ]);

        $material = Material::where('material_number', $request->material_number)->firstOrFail();

        $data = $request->except('material_number');
        $data['material_id'] = $material->id;

        $pfep->update($data);

        return redirect()->route('materials.index')->with('success', 'PFEP record updated successfully.');
    }

    public function destroy(Pfep $pfep)
    {
        $pfep->delete();
        return redirect()->route('materials.index')->with('success', 'PFEP record deleted successfully.');
    }

    /**
     * Set the given PFEP as the primary one for its material.
     *
     * @param  \App\Models\Pfep  $pfep
     * @return \Illuminate\Http\Response
     */
    public function setPrimary(Pfep $pfep)
    {
        DB::transaction(function () use ($pfep) {
            // First, set all existing primary flags to 0 for this material
            Pfep::where('material_id', $pfep->material_id)
                ->update(['is_primary' => 0]);

            // Then, set the selected PFEP's primary flag to 1 and save it.
            // Using direct assignment and save() avoids mass assignment issues.
            $pfep->is_primary = 1;
            $pfep->save();
        });

        return redirect()->route('materials.index')
            ->with('success', 'Primary PFEP for material ' . $pfep->material->material_number . ' has been updated.');
    }
}