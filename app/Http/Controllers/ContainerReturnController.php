<?php

namespace App\Http\Controllers;

use App\Models\ContainerStock;
use App\Models\ContainerTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContainerReturnController extends Controller
{
    function __construct()
    {
        //$this->middleware('permission:return containers');
    }

    /**
     * Display a listing of empty containers ready for return.
     */
    public function index(Request $request)
    {
        // ดึงข้อมูลจาก Container Stocks ที่มีสถานะเป็น "Empty" (3)
        $query = ContainerStock::where('status', 3)
            ->with(['containerOrderPlan.container', 'yardLocation']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('containerOrderPlan.container', function ($q) use ($search) {
                $q->where('container_no', 'like', '%' . $search . '%');
            });
        }

        $stocks = $query->paginate(6); // 6 cards per page for PDA layout

        return view('container-return.index', compact('stocks'));
    }

    /**
     * Process the container return.
     */
    public function returnContainer(Request $request, ContainerStock $stock)
    {
        DB::transaction(function () use ($request, $stock) {
            $orderPlan = $stock->containerOrderPlan;

            if (!$orderPlan) {
                // This is a safeguard in case the relationship is broken
                return back()->with('error', 'Associated Order Plan not found for this stock item.');
            }

            // 1. อัปเดตสถานะของ Order Plan เป็น "Returned" (4)
            $orderPlan->status = 4;
            $orderPlan->save();

            // 2. Create a Transaction Log for the 'Return' activity
            ContainerTransaction::create([
                'container_order_plan_id' => $orderPlan->id,
                'house_bl' => $orderPlan->house_bl,
                'user_id' => Auth::id(),
                'yard_location_id' => $stock->yard_location_id, // Record the location it was returned from
                'activity_type' => 'Return',
                'transaction_date' => now(),
                'remarks' => 'Container returned to owner.',
            ]);

            // 3. Delete the container from the stock
            $stock->delete();
        });

        return back()->with('success', 'Container has been returned successfully.');
    }
}
