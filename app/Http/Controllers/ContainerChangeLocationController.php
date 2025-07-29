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

        $stocks = $query->paginate(10);
        // ไม่ต้องส่ง $locations ไปที่ view แล้ว
        return view('container-change-location.index', compact('stocks'));
    }

    /**
     * Update the specified resource in storage.
     */
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

        DB::transaction(function () use ($stock, $newLocationId, $oldLocationId) {
            // 1. Update the location in the container_stocks table
            $stock->update(['yard_location_id' => $newLocationId]);

            // 2. Create a transaction log for the 'Move' activity
            ContainerTransaction::create([
                'container_order_plan_id' => $stock->container_order_plan_id,
                'user_id' => Auth::id(),
                'yard_location_id' => $newLocationId, // The new location
                'activity_type' => 'Move',
                'transaction_date' => date('Y-m-d H:i:s'),
                'remarks' => 'Moved from ' . ($stock->yardLocation->location_code ?? 'N/A') . ' to ' . YardLocation::find($newLocationId)->location_code,
            ]);
        });

        return back()->with('success', 'Container location updated successfully.');
    }
}
