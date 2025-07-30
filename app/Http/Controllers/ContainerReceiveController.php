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

    public function create()
    {
        $pendingPlans = ContainerOrderPlan::where('status', 1)->with('container')->get();
        $locations = YardLocation::where('is_active', true)->get();

        return view('container-receive.create', compact('pendingPlans', 'locations'));
    }

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

            // 1. สร้าง Record ใน Container Stock พร้อมกับ status = 1 (Full)
            ContainerStock::create([
                'container_order_plan_id' => $request->container_order_plan_id,
                'yard_location_id' => $request->yard_location_id,
                'status' => 1, // 1 = Full
                'checkin_date' => $request->checkin_date,
                'remarks' => $request->remarks,
            ]);

            // 2. อัปเดตสถานะของ Order Plan เป็น "Received" (2)
            $plan->status = 2;
            $plan->checkin_date = $request->checkin_date;
            $plan->save();

            // 3. สร้าง Transaction Log
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
