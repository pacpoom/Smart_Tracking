<?php

namespace App\Http\Controllers;

use App\Models\ContainerOrderPlan;
use App\Models\ContainerStock;
use App\Models\YardLocation;
use App\Models\ContainerTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        // 1. ดึงเฉพาะ Order Plans ที่มีสถานะเป็น Pending (1)
        // หมายเหตุ: หากมี Plan จำนวนมากในอนาคต เราควรเปลี่ยนส่วนนี้เป็น AJAX เช่นกัน
        $pendingPlans = ContainerOrderPlan::where('status', 1)->with('container')->get();
        
        // 2. ไม่ต้องดึงข้อมูล $locations มาที่นี่แล้ว
        return view('container-receive.create', compact('pendingPlans'));
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
            $plan = ContainerOrderPlan::find($request->container_order_plan_id);

            ContainerStock::create([
                'container_order_plan_id' => $request->container_order_plan_id,
                'container_id' => $plan->container_id, // Assuming the plan has a container_id
                'yard_location_id' => $request->yard_location_id,
                'status' => 1, // 1 = Full
                'checkin_date' => $request->checkin_date,
                'remarks' => $request->remarks,
            ]);

            $plan->status = 2; // 2 = Received
            $plan->checkin_date = $request->checkin_date;
            $plan->save();

            ContainerTransaction::create([
                'container_order_plan_id' => $plan->id,
                'house_bl' => $plan->house_bl,
                'user_id' => Auth::id(),
                'yard_location_id' => $request->yard_location_id,
                'activity_type' => 'Receive',
                'transaction_date' => now(),
                'remarks' => $request->remarks,
            ]);
        });

        return redirect()->route('container-receive.create')->with('success', 'Container received successfully.');
    }
}
