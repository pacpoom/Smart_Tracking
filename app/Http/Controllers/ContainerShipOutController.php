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

    /**
     * Display a listing of pulling plans ready for shipping out.
     */
    public function index(Request $request)
    {
        // ดึงข้อมูลจาก Pulling Plans ที่ยังไม่เสร็จสิ้น (status != 3)
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

    /**
     * Process the container ship out based on a pulling plan.
     */
    public function shipOut(Request $request, ContainerPullingPlan $pullingPlan)
    {
        $request->validate([
            'departure_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        $orderPlan = $pullingPlan->containerOrderPlan;
        
        // แก้ไข: ค้นหา stock record โดยตรงจาก container_stocks table
        $stock = ContainerStock::where('container_order_plan_id', $orderPlan->id)->first();

        // 1. ตรวจสอบว่ามีข้อมูลสต็อกอยู่จริงหรือไม่ (ย้ายมาอยู่นอก transaction เพื่อความเสถียร)
        if (!$stock) {
            return back()->with('error', 'Cannot ship out. Stock record not found for this container plan.');
        }

        DB::transaction(function () use ($request, $pullingPlan, $orderPlan, $stock) {
            // 2. Update the status of the Pulling Plan to "Completed" (3)
            $pullingPlan->status = 3;
            $pullingPlan->save();

            // 3. Update the status of the Order Plan to "Shipped Out" (3)
            $orderPlan->status = 3;
            $orderPlan->departure_date = $request->departure_date;
            $orderPlan->save();

            // 4. Create a Transaction Log for the 'Ship Out' activity
            ContainerTransaction::create([
                'container_order_plan_id' => $orderPlan->id,
                'house_bl' => $orderPlan->house_bl,
                'user_id' => Auth::id(),
                'yard_location_id' => $stock->yard_location_id, // Record the location it shipped out from
                'activity_type' => 'Ship Out',
                'transaction_date' => date('Y-m-d H:i:s'),
                'remarks' => $request->remarks,
            ]);

            // 5. Remove the container from the stock
            $stock->delete();
        });

        return back()->with('success', 'Container has been shipped out successfully.');
    }
}
