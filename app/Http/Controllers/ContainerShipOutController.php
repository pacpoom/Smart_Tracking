<?php

namespace App\Http\Controllers;

use App\Models\ContainerPullingPlan;
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
        ]);

        DB::transaction(function () use ($request, $pullingPlan) {
            $orderPlan = $pullingPlan->containerOrderPlan;
            $stock = $orderPlan->containerStock;

            if (!$stock) {
                return back()->with('error', 'Cannot ship out. Stock record not found.');
            }

            // 1. Update Pulling Plan status
            $pullingPlan->status = 3; // Completed
            $pullingPlan->save();

            // 2. Determine new stock status based on plan type
            $newStockStatus = ($pullingPlan->plan_type === 'all') ? 3 : 2; // 3 = Empty, 2 = Partial

            // 3. Update Container Stock status
            $stock->status = $newStockStatus;
            $stock->save();

            // 4. Update Order Plan status if the stock is now empty
            if ($newStockStatus === 3) {
                $orderPlan->status = 3; // Shipped Out
                $orderPlan->departure_date = $request->departure_date;
                $orderPlan->save();
            }

            // 5. Create Transaction Log
            ContainerTransaction::create([
                'container_order_plan_id' => $orderPlan->id,
                'house_bl' => $orderPlan->house_bl,
                'user_id' => Auth::id(),
                'yard_location_id' => $stock->yard_location_id,
                'activity_type' => 'Ship Out',
                'transaction_date' => now(),
                'remarks' => 'Plan Type: ' . ucfirst($pullingPlan->plan_type) . '. ' . $request->remarks,
            ]);
        });

        return back()->with('success', 'Container has been shipped out successfully.');
    }
}
