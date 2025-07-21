<?php

namespace App\Http\Controllers;

use App\Models\Part;
use Illuminate\Http\Request;

class PartController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:view parts|create parts|edit parts|delete parts', ['only' => ['index']]);
        // $this->middleware('permission:create parts', ['only' => ['create', 'store']]);
        // $this->middleware('permission:edit parts', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:delete parts', ['only' => ['destroy', 'bulkDestroy']]);
        // $this->middleware('auth')->only('search');
    }

    public function index(Request $request)
    {
        $query = Part::with('stock');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('part_number', 'like', '%' . $search . '%')
                  ->orWhere('part_name_thai', 'like', '%' . $search . '%')
                  ->orWhere('part_name_eng', 'like', '%' . $search . '%');
            });
        }

        $parts = $query->paginate(10);
        return view('parts.index', compact('parts'));
    }

    /**
     * Search for parts and include stock quantity.
     */
    public function search(Request $request)
    {
        $search = $request->term;
        // Eager load the stock relationship
        $parts = Part::with('stock')
                     ->where(function($query) use ($search) {
                         $query->where('part_number', 'LIKE', "%{$search}%")
                               ->orWhere('part_name_eng', 'LIKE', "%{$search}%")
                               ->orWhere('part_name_thai', 'LIKE', "%{$search}%");
                     })
                     ->limit(15)
                     ->get();

        $formatted_parts = [];
        foreach ($parts as $part) {
            // Get stock quantity, default to 0 if no stock record exists
            $stockQty = $part->stock->qty ?? 0;
            $formatted_parts[] = [
                'id' => $part->id,
                // Add stock quantity to the display text
                'text' => $part->part_number . ' - ' . ($part->part_name_eng ?: $part->part_name_thai) . ' (Stock: ' . $stockQty . ')',
                'stock' => $stockQty // Pass stock quantity as a separate data attribute
            ];
        }

        return response()->json($formatted_parts);
    }

    public function create()
    {
        return view('parts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'part_number' => 'required|string|unique:parts,part_number',
            'part_name_thai' => 'nullable|string|max:255',
            'part_name_eng' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'model_no' => 'nullable|string',
        ]);
        Part::create($request->all());
        return redirect()->route('parts.index')->with('success', 'Part created successfully.');
    }

    public function edit(Part $part)
    {
        return view('parts.edit', compact('part'));
    }

    public function update(Request $request, Part $part)
    {
        $request->validate([
            'part_number' => 'required|string|unique:parts,part_number,'.$part->id,
            'part_name_thai' => 'nullable|string|max:255',
            'part_name_eng' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'model_no' => 'nullable|string',
        ]);
        $part->update($request->all());
        return redirect()->route('parts.index')->with('success', 'Part updated successfully.');
    }

    public function destroy(Part $part)
    {
        $part->delete();
        return redirect()->route('parts.index')->with('success', 'Part deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:parts,id',
        ]);
        Part::whereIn('id', $request->ids)->delete();
        return redirect()->route('parts.index')->with('success', 'Selected parts have been deleted successfully.');
    }
}
