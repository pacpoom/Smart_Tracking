<?php

namespace App\Http\Controllers;

use App\Models\ContainerStock;
use App\Models\ContainerTransaction;
use App\Models\ContainerOrderPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
        // 1. หาค่า ID ล่าสุดของแต่ละตู้ เพื่อให้แสดงตู้ไม่ซ้ำซ้อนกัน
        // ลบ "as max_id" ออก เพื่อให้ whereIn สามารถประมวลผลเป็น where id in (select max(id) ...) ได้อย่างถูกต้อง
        $subQuery = ContainerStock::select(DB::raw('MAX(id)'))
            ->where('status', '!=', 4)
            ->groupBy('container_id');

        // ดึงข้อมูล stock ที่เป็น ID ล่าสุด
        $query = ContainerStock::whereIn('id', $subQuery)
            ->with(['containerOrderPlan.container', 'yardLocation', 'container']); // Eager load relationships

        // 2. การค้นหา: ถ้ามีการส่ง search มาจาก dropdown หรือพิมพ์ค้นหา
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // ปรับ whereHas nested ให้ปลอดภัยขึ้น
                $q->whereHas('containerOrderPlan', function ($q2) use ($search) {
                    $q2->whereHas('container', function ($q4) use ($search) {
                        $q4->where('container_no', 'like', '%' . $search . '%');
                    });
                })->orWhereHas('container', function($q3) use ($search) {
                    $q3->where('container_no', 'like', '%' . $search . '%');
                });
            });
        }
        
        // เรียงลำดับจากใหม่ไปเก่า
        $stocks = $query->orderBy('id', 'desc')->paginate(6); 

        return view('container-return.index', compact('stocks'));
    }
    
    /**
     * Search for containers for Select2 AJAX.
     */
    public function search(Request $request)
    {
        $search = $request->term;

        // สำหรับ AJAX Search ดึงข้อมูลโดยไม่ต้องใช้ whereIn(SubQuery) ที่ซับซ้อน 
        // อาศัย Collection unique ในการกรองตู้ที่ไม่ซ้ำ เพื่อให้ค้นหาได้รวดเร็วขึ้น
        $stocks = ContainerStock::with(['containerOrderPlan.container', 'container'])
            ->where('status', '!=', 4)
            ->where(function($q) use ($search) {
                $q->whereHas('containerOrderPlan', function ($q2) use ($search) {
                    $q2->whereHas('container', function ($q4) use ($search) {
                        $q4->where('container_no', 'like', '%' . $search . '%');
                    });
                })->orWhereHas('container', function($q3) use ($search) {
                    $q3->where('container_no', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('id', 'desc')
            ->get()
            ->unique('container_id') // กรองให้เหลือตู้ละ 1 รายการ
            ->take(20); // จำกัดผลลัพธ์

        $formattedStocks = $stocks->map(function ($stock) {
            // ดึงเลขตู้คอนเทนเนอร์
            $containerNo = $stock->container->container_no ?? ($stock->containerOrderPlan->container->container_no ?? 'Unknown');
            
            return [
                'id' => $containerNo, // คืนค่าหมายเลขตู้เพื่อนำไปใส่เป็น Value ตอนกด Search
                'text' => $containerNo,
            ];
        })->values(); // reset keys หลังจากการใช้ unique()

        return response()->json(['results' => $formattedStocks]);
    }

    /**
     * Process the container return.
     */
    public function store(Request $request)
    {
        // ตรวจสอบว่ามีข้อมูล container_id ส่งมา
        $request->validate([
            'container_id' => 'required',
        ]);

        $containerIdToReturn = $request->container_id;

        DB::transaction(function () use ($containerIdToReturn) {
            
            // หา order_plan และ stock ที่เกี่ยวข้องเพื่อเอาไปบันทึก transaction
            $orderPlan = ContainerOrderPlan::where('container_id', $containerIdToReturn)->orderBy('id', 'desc')->first();
            $stock = ContainerStock::where('container_id', $containerIdToReturn)->orderBy('id', 'desc')->first();

            // 1. อัปเดตสถานะของ Order Plan ทุกรายการที่เกี่ยวข้องกับ container_id นี้เป็น Return (4)
            ContainerOrderPlan::where('container_id', $containerIdToReturn)
                ->update(['status' => 4]);

            // 2. Create a Transaction Log for the 'Return' activity
            if ($orderPlan) {
                ContainerTransaction::create([
                    'container_order_plan_id' => $orderPlan->id,
                    'house_bl' => $orderPlan->house_bl,
                    'user_id' => Auth::id(),
                    'yard_location_id' => $stock ? $stock->yard_location_id : null, // Record the location it was returned from
                    'activity_type' => 'Return',
                    'transaction_date' => now(),
                    'remarks' => 'Container returned to owner.',
                ]);
            }

            // 3. Delete all container stock records with the same container_id
            ContainerStock::where('container_id', $containerIdToReturn)->delete();
        });

        return back()->with('success', 'Container has been returned successfully.');
    }
}