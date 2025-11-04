<?php

namespace App\Http\Controllers;

use App\Models\ContainerPullingPlan;
use App\Models\ContainerStock;
use App\Models\ContainerTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ContainerPickingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // ดึงแผนการหยิบ (Pulling Plan) เฉพาะของวันนี้ และมีสถานะเป็น 'Pending' (หรือสถานะเริ่มต้นอื่นๆ ที่คุณใช้)
        // พร้อมดึงข้อมูล Container และ Location ที่เกี่ยวข้องมาด้วย
        $todayPlans = ContainerPullingPlan::query()
            ->whereDate('pulling_date', Carbon::today())
            ->where('status', 'Pending') // สมมติว่า 'Pending' คือสถานะ "รอหยิบ"
            ->with([
                'containerOrderPlan.container', // ดึงข้อมูลตู้
                'containerOrderPlan.containerStock.yardLocation' // ดึงข้อมูลสต็อกและ Location ปัจจุบัน
            ])
            ->orderBy('pulling_order', 'asc') // จัดลำดับตาม order
            ->get();

        // ส่งข้อมูลไปยัง View
        return view('container-picking.index', ['pickingPlans' => $todayPlans]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // ตรวจสอบว่ามี container_pulling_plan_id ส่งมาหรือไม่
        $request->validate([
            'container_pulling_plan_id' => 'required|exists:container_pulling_plans,id',
        ]);

        // ใช้ DB Transaction เพื่อความถูกต้องของข้อมูล
        DB::beginTransaction();
        try {
            $planId = $request->container_pulling_plan_id;
            $user = Auth::user();

            // 1. ค้นหา Plan และ Container ที่เกี่ยวข้อง
            $plan = ContainerPullingPlan::with('containerOrderPlan.container')
                ->findOrFail($planId);

            // ตรวจสอบสถานะซ้ำ เผื่อมีการหยิบไปแล้ว
            if ($plan->status !== 'Pending') {
                return redirect()->back()->with('error', 'This container has already been picked or is not ready.');
            }

            // 2. ค้นหา Container Stock
            $containerId = $plan->containerOrderPlan->container_id;
            $stock = ContainerStock::where('container_id', $containerId)->firstOrFail();
            $oldLocationId = $stock->yard_location_id;

            // 3. อัปเดต ContainerPullingPlan
            $plan->status = 'Picking'; // หรือ 'In-Progress'
            $plan->picked_by_user_id = $user->id; // สมมติว่ามีคอลัมน์นี้
            $plan->picked_at = Carbon::now(); // สมมติว่ามีคอลัมน์นี้
            $plan->save();

            // 4. อัปเดต ContainerStock
            // ย้ายตู้คอนเทนเนอร์ออกจาก Yard (ตั้ง Location เป็น null หรือย้ายไปที่ 'DOCK')
            $stock->yard_location_id = null; // หรือ ID ของ 'DOCK'
            $stock->status = 'Picking'; // อัปเดตสถานะใน Stock
            $stock->save();

            // 5. สร้าง ContainerTransaction
            ContainerTransaction::create([
                'container_id' => $containerId,
                'transaction_type' => 'PICKING',
                'from_location_id' => $oldLocationId,
                'to_location_id' => null, // หรือ ID ของ 'DOCK'
                'reference_id' => $plan->id,
                'reference_type' => get_class($plan),
                'transaction_by' => $user->id,
                'transaction_at' => Carbon::now(),
            ]);

            DB::commit();

            return redirect()->route('container-picking.index')->with('success', 'Container ' . $plan->containerOrderPlan->container->container_no . ' picked successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error('Picking failed: ' . $e->getMessage()); // ควรมีการ Log error
            return redirect()->back()->with('error', 'An error occurred during picking: ' . $e->getMessage());
        }
    }
}
