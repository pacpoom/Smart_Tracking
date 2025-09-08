<?php

namespace App\Http\Controllers;

use App\Models\ContainerStock;
use App\Models\ContainerExchange;
use App\Models\ContainerTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Models\ContainerExchangePhoto;
use Illuminate\Support\Facades\Storage; // เพิ่ม Storage facade

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

    public function show(ContainerExchange $containerExchange)
    {
        // โหลดความสัมพันธ์ที่จำเป็นเพื่อแสดงผล
        $containerExchange->load([
            'sourceStock.containerOrderPlan.container',
            'destinationStock.containerOrderPlan.container',
            'user',
            'photos'
        ]);
        
        return view('container-exchange.show', compact('containerExchange'));
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
            'photos' => 'nullable|array',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            $sourceStock = ContainerStock::find($request->source_container_stock_id);
            $destinationStock = ContainerStock::find($request->destination_container_stock_id);
            
            // ใช้ Container ID ของ Source Stock ก่อนที่จะถูกอัปเดต
            $oldSourceContainerId = $sourceStock->container_id;

            // 1. อัปเดตข้อมูลของ source stock
            $sourceStock->update([
                'container_id' => $destinationStock->container_id,
                'container_order_plan_id' => $destinationStock->container_order_plan_id,
                'status' => 3 // Empty
            ]);

            // 2. อัปเดตข้อมูลของ destination stock
            $destinationStock->update([
                'container_id' => $oldSourceContainerId,
                'container_order_plan_id' => $sourceStock->container_order_plan_id,
                'status' => 1 // Full
            ]);

            // 3. Create a history log in container_exchanges
            $containerExchange = ContainerExchange::create([
                'source_container_stock_id' => $sourceStock->id,
                'destination_container_stock_id' => $destinationStock->id,
                'user_id' => Auth::id(),
                'exchange_date' => now(),
                'remarks' => $request->remarks,
            ]);

            // 4. Handle photo uploads
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $type => $photo) {
                    if ($photo && $photo->isValid()) {
                        $path = $photo->store('photos/container_exchanges', 'public');
                        ContainerExchangePhoto::create([
                            'container_exchange_id' => $containerExchange->id,
                            'photo_type' => $type,
                            'photo_path' => $path,
                        ]);
                    }
                }
            }

            // 5. Insert into transaction log for both containers
            ContainerTransaction::create([
                'container_order_plan_id' => $sourceStock->container_order_plan_id,
                'house_bl' => $sourceStock->containerOrderPlan->house_bl,
                'user_id' => Auth::id(),
                'yard_location_id' => $destinationStock->yard_location_id,
                'activity_type' => 'Exchange (To)',
                'transaction_date' => now(),
                'remarks' => 'Exchanged with ' . $destinationStock->container->container_no,
            ]);

            ContainerTransaction::create([
                'container_order_plan_id' => $destinationStock->container_order_plan_id,
                'house_bl' => $destinationStock->containerOrderPlan->house_bl,
                'user_id' => Auth::id(),
                'yard_location_id' => $sourceStock->yard_location_id,
                'activity_type' => 'Exchange (From)',
                'transaction_date' => now(),
                'remarks' => 'Exchanged with ' . $sourceStock->container->container_no,
            ]);
        });

        return back()->with('success', 'Containers exchanged successfully.');
    }
}
