<?php

namespace App\Http\Controllers;

use App\Models\ContainerStock;
use App\Models\ContainerTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContainerShipOutController extends Controller
{
    function __construct()
    {
        //$this->middleware('permission:ship out containers');
    }

    /**
     * Display a listing of containers available for shipping out.
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

        return view('container-ship-out.index', compact('stocks'));
    }

    /**
     * Process the container ship out.
     */
    public function shipOut(Request $request, ContainerStock $stock)
    {
        $request->validate([
            'departure_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $stock) {
            $plan = $stock->containerOrderPlan;

            // 1. Update the status of the Order Plan to "Shipped Out" (3)
            $plan->status = 3;
            $plan->departure_date = $request->departure_date;
            $plan->save();

            // 2. Create a Transaction Log for the 'Ship Out' activity
            ContainerTransaction::create([
                'container_order_plan_id' => $plan->id,
                'house_bl' => $plan->house_bl,
                'user_id' => Auth::id(),
                'yard_location_id' => $stock->yard_location_id, // Record the location it shipped out from
                'activity_type' => 'Ship Out',
                'transaction_date' => now(),
                'remarks' => $request->remarks,
            ]);

            // 3. Remove the container from the stock
            $stock->delete();
        });

        return back()->with('success', 'Container has been shipped out successfully.');
    }
}
