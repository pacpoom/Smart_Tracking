<?php

namespace App\Http\Controllers;

use App\Models\ContainerStock;
use App\Models\ContainerExchange;
use App\Models\ContainerTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon; // 1. เพิ่ม use statement นี้

class ContainerExchangeController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:exchange containers', ['only' => ['create', 'store']]);
        // $this->middleware('permission:view container exchanges', ['only' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 2. กำหนดค่าเริ่มต้นและรับค่าวันที่จากฟอร์ม
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $query = ContainerExchange::with([
            'sourceStock.containerOrderPlan.container',
            'destinationStock.containerOrderPlan.container',
            'user'
        ]);

        // 3. กรองข้อมูลตามช่วงวันที่
        $query->whereBetween('exchange_date', [$startDate, $endDate]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('sourceStock.containerOrderPlan.container', function ($subQ) use ($search) {
                    $subQ->where('container_no', 'like', '%' . $search . '%');
                })
                ->orWhereHas('destinationStock.containerOrderPlan.container', function ($subQ) use ($search) {
                    $subQ->where('container_no', 'like', '%' . $search . '%');
                });
            });
        }

        $exchanges = $query->latest()->paginate(10);
        
        // 4. ส่งค่าวันที่กลับไปที่ View
        return view('container-exchange.index', compact('exchanges', 'startDate', 'endDate'));
    }

    public function create()
    {
        return view('container-exchange.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'source_container_stock_id' => 'required|exists:container_stocks,id',
            'destination_container_stock_id' => 'required|exists:container_stocks,id|different:source_container_stock_id',
            'remarks' => 'nullable|string',
        ]);

        $sourceStock = ContainerStock::find($request->source_container_stock_id);
        $destinationStock = ContainerStock::find($request->destination_container_stock_id);

        $order_plan_id =DB::table('container_order_plans')->where('id', $destinationStock->container_order_plan_id);

        DB::table('container_stocks')
        ->where('id', $request->source_container_stock_id)
        ->update(['container_id' => $order_plan_id->value('container_id')]);


        DB::transaction(function () use ($sourceStock, $destinationStock, $request) {
            // // 1. Swap the container_order_plan_id
            $sourcePlanId = $sourceStock->container_order_plan_id;
            $destinationPlanId = $destinationStock->container_order_plan_id;

            // $sourceStock->update(['container_order_plan_id' => $destinationPlanId]);
            // $destinationStock->update(['container_order_plan_id' => $sourcePlanId]);

            // 2. Set destination stock status to Full (1)
            $destinationStock->update(['status' => 1]);
            $sourceStock->update(['status' => 3]);
            // 3. Create a history log in container_exchanges
            ContainerExchange::create([
                'source_container_stock_id' => $sourceStock->id,
                'destination_container_stock_id' => $destinationStock->id,
                'user_id' => Auth::id(),
                'exchange_date' => now(),
                'remarks' => $request->remarks,
            ]);

            // 4. Insert into transaction log for both containers
            ContainerTransaction::create([
                'container_order_plan_id' => $sourcePlanId,
                'house_bl' => $sourceStock->containerOrderPlan->house_bl,
                'user_id' => Auth::id(),
                'yard_location_id' => $destinationStock->yard_location_id,
                'activity_type' => 'Exchange (To)',
                'transaction_date' => now(),
                'remarks' => 'Exchanged with ' . $destinationStock->containerOrderPlan->container->container_no,
            ]);

            ContainerTransaction::create([
                'container_order_plan_id' => $destinationPlanId,
                'house_bl' => $destinationStock->containerOrderPlan->house_bl,
                'user_id' => Auth::id(),
                'yard_location_id' => $sourceStock->yard_location_id,
                'activity_type' => 'Exchange (From)',
                'transaction_date' => now(),
                'remarks' => 'Exchanged with ' . $sourceStock->containerOrderPlan->container->container_no,
            ]);
        });

        return back()->with('success', 'Containers exchanged successfully.');
    }
}
