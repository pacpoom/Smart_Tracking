<?php

namespace App\Http\Controllers;

use App\Models\YardLocation;
use App\Models\YardCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\ContainerStock;
use App\Models\ContainerTransaction;
use Illuminate\Support\Facades\DB;

class YardLocationController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:view yard locations|create yard locations|edit yard locations|delete yard locations', ['only' => ['index']]);
        // $this->middleware('permission:create yard locations', ['only' => ['create', 'store']]);
        // $this->middleware('permission:edit yard locations', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:delete yard locations', ['only' => ['destroy', 'bulkDestroy']]);
    }

    public function index(Request $request)
    {
        // Eager load relationships to prevent N+1 query problem
        $query = YardLocation::with(['locationType', 'zone', 'area', 'bin']);

        if ($request->filled('search')) {
            $search = $request->search;
            // Improved search to include related category names
            $query->where(function($q) use ($search) {
                $q->where('location_code', 'like', '%' . $search . '%')
                  ->orWhereHas('locationType', function ($subQ) use ($search) {
                      $subQ->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('zone', function ($subQ) use ($search) {
                      $subQ->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        $locations = $query->latest()->paginate(10);
        return view('yard-locations.index', compact('locations'));
    }

    public function create()
    {
        
        $locationTypes = YardCategory::where('type', 'location_type')->pluck('name', 'id');
        $zones = YardCategory::where('type', 'zone')->pluck('name', 'id');
        $areas = YardCategory::where('type', 'area')->pluck('name', 'id');
        $bins = YardCategory::where('type', 'bin')->pluck('name', 'id');

        return view('yard-locations.create', compact('locationTypes', 'zones', 'areas', 'bins'));

    }

    public function store(Request $request)
    {
        $request->validate([
            'location_type_id' => 'nullable|exists:yard_categories,id',
            'zone_id' => 'nullable|exists:yard_categories,id',
            'area_id' => 'nullable|exists:yard_categories,id',
            'bin_id' => 'nullable|exists:yard_categories,id',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        
        // Generate location_code from selected category names
        $parts = [];
        if($request->zone_id) $parts[] = YardCategory::find($request->zone_id)->name;
        if($request->area_id) $parts[] = YardCategory::find($request->area_id)->name;
        if($request->bin_id) $parts[] = YardCategory::find($request->bin_id)->name;
        $locationCode = implode('-', $parts);

        // Validate uniqueness of the generated code
        if (empty($locationCode) || YardLocation::where('location_code', $locationCode)->exists()) {
            throw ValidationException::withMessages([
               'location_code' => 'This combination of Zone, Area, and Bin already exists or is invalid.',
            ]);
        }
        $data['location_code'] = $locationCode;

        YardLocation::create($data);
        return redirect()->route('yard-locations.index')->with('success', 'Location created successfully.');
    }

    public function edit(YardLocation $yardLocation)
    {
        // Fetch categories for dropdowns
        $locationTypes = YardCategory::where('category_type', 'location_type')->pluck('name', 'id');
        $zones = YardCategory::where('category_type', 'zone')->pluck('name', 'id');
        $areas = YardCategory::where('category_type', 'area')->pluck('name', 'id');
        $bins = YardCategory::where('category_type', 'bin')->pluck('name', 'id');

        return view('yard-locations.edit', compact('yardLocation', 'locationTypes', 'zones', 'areas', 'bins'));
    }

    public function update(Request $request, YardLocation $yardLocation)
    {
        $request->validate([
            'location_type_id' => 'nullable|exists:yard_categories,id',
            'zone_id' => 'nullable|exists:yard_categories,id',
            'area_id' => 'nullable|exists:yard_categories,id',
            'bin_id' => 'nullable|exists:yard_categories,id',
        ]);
        
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        // Generate location_code from selected category names
        $parts = [];
        if($request->zone_id) $parts[] = YardCategory::find($request->zone_id)->name;
        if($request->area_id) $parts[] = YardCategory::find($request->area_id)->name;
        if($request->bin_id) $parts[] = YardCategory::find($request->bin_id)->name;
        $locationCode = implode('-', $parts);

        // Validate uniqueness of the generated code, excluding the current model
        if (empty($locationCode) || YardLocation::where('location_code', $locationCode)->where('id', '!=', $yardLocation->id)->exists()) {
            throw ValidationException::withMessages([
               'location_code' => 'This combination of Zone, Area, and Bin already exists or is invalid.',
            ]);
        }
        $data['location_code'] = $locationCode;

        $yardLocation->update($data);
        return redirect()->route('yard-locations.index')->with('success', 'Location updated successfully.');
    }

    public function destroy(YardLocation $yardLocation)
    {
        // Check for related records before deleting
        if ($yardLocation->containerTransactions()->exists() || ContainerStock::where('yard_location_id', $yardLocation->id)->exists()) {
            return redirect()->route('yard-locations.index')->with('error', 'Cannot delete location. It is currently in use by a transaction or has stock.');
        }

        $yardLocation->delete();
        return redirect()->route('yard-locations.index')->with('success', 'Location deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:yard_locations,id',
        ]);

        $ids = $request->input('ids');
        $undeletedLocations = [];
        $deletedCount = 0;

        // Get all locations to be deleted
        $locations = YardLocation::find($ids);

        // Get all stock locations for the given IDs to check dependency in one query
        $stockLocations = ContainerStock::whereIn('yard_location_id', $ids)->pluck('yard_location_id')->unique();

        foreach ($locations as $location) {
            // Check for container transactions or if it's in the stock locations list
            if ($location->containerTransactions()->exists() || $stockLocations->contains($location->id)) {
                $undeletedLocations[] = $location->location_code;
            } else {
                $location->delete();
                $deletedCount++;
            }
        }

        if (count($undeletedLocations) > 0) {
            $message = 'Could not delete locations because they are in use: ' . implode(', ', $undeletedLocations);
            if ($deletedCount > 0) {
                return redirect()->route('yard-locations.index')
                    ->with('success', $deletedCount . ' location(s) deleted successfully.')
                    ->with('error', $message);
            }
            return redirect()->route('yard-locations.index')->with('error', $message);
        }

        return redirect()->route('yard-locations.index')->with('success', 'Selected locations have been deleted successfully.');
    }

     /**
     * Search for locations for Select2 AJAX.
     */
    public function search(Request $request)
    {
        $search = $request->term;
        $excludeId = $request->exclude;

        // Get IDs of all locations that already have containers
        $occupiedLocationIds = ContainerStock::pluck('yard_location_id')->unique()->toArray();

        $query = YardLocation::where('is_active', true);

        if ($search) {
            $query->where('location_code', 'LIKE', "%{$search}%");
        }

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        // Filter out locations that are already occupied
        //$query->whereNotIn('id', $occupiedLocationIds);

        $locations = $query->limit(40)->get(['id', 'location_code']);

        $formatted_locations = [];
        foreach ($locations as $location) {
            $formatted_locations[] = [
                'id' => $location->id,
                'text' => $location->location_code
            ];
        }

        return response()->json($formatted_locations);
    }

    public function searchDock(Request $request)
    {
        $search = $request->term;
        $excludeId = $request->exclude;

        // Get IDs of all locations that already have containers
        $occupiedLocationIds = ContainerStock::pluck('yard_location_id')->unique()->toArray();

        $query = YardLocation::where('is_active', true);

        $query->where('location_type_id', 179); // Assuming 179 is the ID for 'Dock'

        // if ($excludeId) {
        //     $query->where('id', '!=', $excludeId);
        // }

        // Filter out locations that are already occupied
        //$query->whereNotIn('id', $occupiedLocationIds);

        $locations = $query->limit(15)->get(['id', 'location_code']);

        $formatted_locations = [];
        foreach ($locations as $location) {
            $formatted_locations[] = [
                'id' => $location->id,
                'text' => $location->location_code
            ];
        }

        return response()->json($formatted_locations);
    }
}
