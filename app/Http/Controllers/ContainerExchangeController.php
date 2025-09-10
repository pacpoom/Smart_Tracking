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

        // แก้ไข: เปลี่ยนการ eager load ความสัมพันธ์ให้สอดคล้องกับการ Join ตาราง containers
        $query = ContainerExchange::with([
            'user'
        ]);
        
        // เพิ่ม Join เพื่อเข้าถึงข้อมูล containers โดยตรง
        $query->join('container_stocks AS source_stock', 'container_exchanges.source_container_stock_id', '=', 'source_stock.id')
              ->join('container_stocks AS destination_stock', 'container_exchanges.destination_container_stock_id', '=', 'destination_stock.id')
              ->join('containers AS source_container', 'source_stock.container_id', '=', 'source_container.id')
              ->join('containers AS destination_container', 'destination_stock.container_id', '=', 'destination_container.id')
              ->select('container_exchanges.*', 
                       'source_container.container_no AS source_container_no',
                       'destination_container.container_no AS destination_container_no');

        // 3. กรองข้อมูลตามช่วงวันที่
        $query->whereBetween('exchange_date', [$startDate, $endDate]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('source_container.container_no', 'like', '%' . $search . '%')
                  ->orWhere('destination_container.container_no', 'like', '%' . $search . '%');
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
    
    // เพิ่มฟังก์ชัน showPhoto เพื่อแสดงรูปภาพตาม route ใหม่
    public function showPhoto(ContainerExchangePhoto $photo)
    {
        if (!Storage::disk('public')->exists($photo->photo_path)) {
            abort(404);
        }
        return response()->file(storage_path('app/public/' . $photo->photo_path));
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
                'status' => 3 // Empty
            ]);

            // 2. อัปเดตข้อมูลของ destination stock
            $destinationStock->update([
                'status' => 1 // Full
            ]);

            // 3. Create a history log in container_exchanges
            $containerExchange = ContainerExchange::create([
                'source_container_stock_id' => $sourceStock->container_id,
                'destination_container_stock_id' => $destinationStock->container_id,
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

            // ContainerTransaction::create([
            //     'container_order_plan_id' => $destinationStock->container_order_plan_id,
            //     'house_bl' => $destinationStock->containerOrderPlan->house_bl,
            //     'user_id' => Auth::id(),
            //     'yard_location_id' => $sourceStock->yard_location_id,
            //     'activity_type' => 'Exchange (From)',
            //     'transaction_date' => now(),
            //     'remarks' => 'Exchanged with ' . $sourceStock->container->container_no,
            // ]);
        });

        return back()->with('success', 'Containers exchanged successfully.');
    }
}
