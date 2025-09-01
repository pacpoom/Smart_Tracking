<?php

namespace App\Http\Controllers;

use App\Models\ContainerStock;
use App\Models\ContainerTransaction;
use App\Models\YardLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContainerChangeLocationController extends Controller
{
    function __construct()
    {
        //$this->middleware('permission:change container location');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ContainerStock::with(['containerOrderPlan.container', 'yardLocation']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('containerOrderPlan.container', function ($q) use ($search) {
                $q->where('container_no', 'like', '%' . $search . '%');
            });
        }

        // FIX: Rename variable from $stocks to $containers to resolve the error.
        $containers = $query->paginate(3);
        
        // Pass the corrected variable to the view.
        return view('container-change-location.index', compact('containers'));
    }

    public function update(Request $request, ContainerStock $stock)
    {
        $request->validate([
            'new_yard_location_id' => 'required|exists:yard_locations,id',
        ]);

        $newLocationId = $request->new_yard_location_id;
        $oldLocationId = $stock->yard_location_id;

        if ($newLocationId == $oldLocationId) {
            return back()->with('error', 'New location cannot be the same as the current location.');
        }

        DB::transaction(function () use ($stock, $newLocationId) {
            // Get the container ID of the "Current Container" to update all related stocks.
            $containerId = $stock->container_id;

            // 1. Create a transaction log for the 'Move' activity.
            // This only needs to be logged once for the action.
            ContainerTransaction::create([
                'container_order_plan_id' => $stock->container_order_plan_id,
                'user_id' => Auth::id(),
                'yard_location_id' => $newLocationId, // The new location
                'activity_type' => 'Move',
                'transaction_date' => now(),
                'remarks' => 'Moved from ' . ($stock->yardLocation->location_code ?? 'N/A') . ' to ' . YardLocation::find($newLocationId)->location_code,
            ]);
            
            // 2. Update the yard_location_id for ALL stock records that have the same container_id.
            // This ensures that any "Current Container" (represented by container_id) exists in only one location across all its associated original plans.
            if ($containerId) {
                ContainerStock::where('container_id', $containerId)
                              ->update(['yard_location_id' => $newLocationId]);
            } else {
                // Fallback for data consistency, though container_id should always exist.
                $stock->update(['yard_location_id' => $newLocationId]);
            }
        });

        return back()->with('success', 'Container location updated successfully.');
    }
}
