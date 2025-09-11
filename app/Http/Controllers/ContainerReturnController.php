<?php

namespace App\Http\Controllers;

use App\Models\ContainerStock;
use App\Models\ContainerTransaction;
use App\Models\ContainerOrderPlan;
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
        // Fetch Container Stocks that are not 'Returned' (status != 4)
        $query = ContainerStock::where('status', '!=', 4)
            ->with(['containerOrderPlan.container', 'yardLocation', 'container']); // Eager load relationships

        // แก้ไขการค้นหา: ถ้ามีการส่ง container_id มาจาก search dropdown
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
     * Search for containers for Select2 AJAX.
     */
    public function search(Request $request)
    {

        // ค้นหาเฉพาะตู้ที่ยังไม่ถูก Return (4)

        $search = $request->term;

        // แก้ไข: ใช้ join และ group by container_no
        $query = ContainerStock::join('containers', 'container_stocks.container_id', '=', 'containers.id')
                    ->where('containers.container_no', 'LIKE', "%{$search}%")
                    ->groupBy('containers.container_no')
                    ->select(
                        'container_stocks.container_id',
                        'containers.container_no as text'
                    );
        
        $stocks = $query->limit(15)->get();
        
        $formatted_stocks = [];
        foreach ($stocks as $stock) {
            if ($stock->container) {
                $formatted_stocks[] = [
                    'id' => $stock->container_id, // ใช้ container_id เป็น ID เพื่อใช้ในการลบ
                    'text' => $stock->container->container_no,
                ];
            }
        }
        
        return response()->json($formatted_stocks);
    }

    /**
     * Process the container return.
     */
     public function returnContainer(Request $request, ContainerStock $stock)
    {
        DB::transaction(function () use ($request, $stock) {
            $orderPlan = $stock->containerOrderPlan;
            $containerIdToReturn = $stock->container_id;

            if (!$orderPlan) {
                // This is a safeguard in case the relationship is broken
                // ใช้ ValidationException เพื่อให้สามารถส่ง error กลับไปหน้า view ได้
                throw ValidationException::withMessages(['general' => 'Associated Order Plan not found for this stock item.']);
            }

            // 1. อัปเดตสถานะของ Order Plan ทุกรายการที่เกี่ยวข้องกับ container_id นี้
            ContainerOrderPlan::where('container_id', $containerIdToReturn)
                ->update(['status' => 4]);

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

            // 3. Delete all container stock records with the same container_id
            if ($containerIdToReturn) {
                ContainerStock::where('container_id', $containerIdToReturn)->delete();
            } else {
                // Fallback for safety, though container_id should exist
                $stock->delete();
            }
        });

        return back()->with('success', 'Container has been returned successfully.');
    }
}
