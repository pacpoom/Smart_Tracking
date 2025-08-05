<?php

namespace App\Http\Controllers;

use App\Models\ContainerPullingPlan;
use App\Models\ContainerStock;
use App\Models\ContainerTransaction;
use App\Models\YardLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ContainerShipOutController extends Controller
{
    function __construct()
    {
        //$this->middleware('permission:ship out containers');
    }

    public function index(Request $request)
    {
        $query = ContainerPullingPlan::where('status', '!=', 3)
            ->with(['containerOrderPlan.container', 'containerOrderPlan.containerStock.yardLocation']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('containerOrderPlan.container', function ($q) use ($search) {
                $q->where('container_no', 'like', '%' . $search . '%');
            });
        }

        $pullingPlans = $query->paginate(10);

        return view('container-ship-out.index', compact('pullingPlans'));
    }

    public function shipOut(Request $request, ContainerPullingPlan $pullingPlan)
    {
        $request->validate([
            'departure_date' => 'required|date',
            'remarks' => 'nullable|string',
            'plan_type' => 'required|in:all,pull',
            'new_yard_location_id' => 'nullable|exists:yard_locations,id',
        ]);

        DB::transaction(function () use ($request, $pullingPlan) {
            $orderPlan = $pullingPlan->containerOrderPlan;
            $stock = $orderPlan->containerStock;

            if (!$stock) {
                throw ValidationException::withMessages(['general' => 'Cannot ship out. Stock record not found.']);
            }

            $newLocationId = $request->new_yard_location_id;
            $finalLocationId = $stock->yard_location_id;

            // 1. Handle Location Change if a new location is selected
            if ($newLocationId && $newLocationId != $stock->yard_location_id) {
                $oldLocationCode = $stock->yardLocation->location_code ?? 'N/A';
                $newLocation = YardLocation::find($newLocationId);
                
                $stock->update(['yard_location_id' => $newLocationId]);
                $finalLocationId = $newLocationId;

                // Create a 'Move' transaction log
                ContainerTransaction::create([
                    'container_order_plan_id' => $orderPlan->id,
                    'house_bl' => $orderPlan->house_bl,
                    'user_id' => Auth::id(),
                    'yard_location_id' => $newLocationId,
                    'activity_type' => 'Move',
                    'transaction_date' => now()->subSecond(), // To ensure it's logged before ship out
                    'remarks' => 'Moved from ' . $oldLocationCode . ' to ' . $newLocation->location_code . ' for shipping out.',
                ]);
            }

            // 2. Update the Pulling Plan status and type
            $pullingPlan->status = 3; // Completed
            $pullingPlan->plan_type = $request->plan_type;
            $pullingPlan->save();

            // 3. Determine new stock status based on the updated plan type
            $newStockStatus = ($pullingPlan->plan_type === 'all') ? 3 : 2; // 3 = Empty, 2 = Partial

            // 4. Update Container Stock status
            $stock->status = $newStockStatus;
            $stock->save();

            // 5. Update Order Plan status to "Shipped Out"
            $orderPlan->status = 3;
            $orderPlan->departure_date = $request->departure_date;
            $orderPlan->save();

            // 6. Create a 'Ship Out' transaction log
            ContainerTransaction::create([
                'container_order_plan_id' => $orderPlan->id,
                'house_bl' => $orderPlan->house_bl,
                'user_id' => Auth::id(),
                'yard_location_id' => $finalLocationId,
                'activity_type' => 'Ship Out',
                'transaction_date' => now(),
                'remarks' => 'Plan Type: ' . ucfirst($pullingPlan->plan_type) . '. ' . $request->remarks,
            ]);
        });

        return back()->with('success', 'Container has been shipped out successfully.');
    }
}
