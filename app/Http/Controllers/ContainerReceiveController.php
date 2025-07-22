<?php

namespace App\Http\Controllers;

use App\Models\ContainerOrderPlan;
use App\Models\ContainerStock;
use App\Models\YardLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContainerReceiveController extends Controller
{
    function __construct()
    {
        //$this->middleware('permission:receive containers');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ดึงเฉพาะ Order Plans ที่มีสถานะเป็น Pending (1)
        $pendingPlans = ContainerOrderPlan::where('status', 1)->with('container')->get();
        $locations = YardLocation::where('is_active', true)->get();

        return view('container-receive.create', compact('pendingPlans', 'locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'container_order_plan_id' => 'required|exists:container_order_plans,id',
            'yard_location_id' => 'required|exists:yard_locations,id',
            'checkin_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            // 1. สร้าง Record ใน Container Stock
            ContainerStock::create([
                'container_order_plan_id' => $request->container_order_plan_id,
                'yard_location_id' => $request->yard_location_id,
                'checkin_date' => $request->checkin_date,
                'remarks' => $request->remarks,
            ]);

            // 2. อัปเดตสถานะของ Order Plan เป็น "Received" (2)
            $plan = ContainerOrderPlan::find($request->container_order_plan_id);
            $plan->status = 2; // 2 = Received
            $plan->checkin_date = $request->checkin_date; // อัปเดต checkin_date ใน plan ด้วย
            $plan->save();
        });

        return redirect()->route('container-receive.create')->with('success', 'Container received successfully.');
    }
}
