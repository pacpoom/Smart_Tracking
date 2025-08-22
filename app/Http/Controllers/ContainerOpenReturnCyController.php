<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContainerPullingPlan;
use App\Models\ContainerStock;
use App\Models\ContainerTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ContainerOpenReturnCyController extends Controller
{
    /**
     * แสดงรายการ order pulling ที่รอการยืนยัน
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // คิวรี่ข้อมูลแผนการดึงตู้คอนเทนเนอร์ที่สถานะ 'CONFIRMED'
        $query = ContainerPullingPlan::where('status', 'CONFIRMED')
            ->with(['container', 'vendor']);

        // กรองข้อมูลตามประเภทที่เลือก (All หรือ Pull)
        $openType = $request->get('open_type', 'All'); // ค่าเริ่มต้นคือ 'All'

        if ($openType === 'Pull') {
            // 'Pull' หมายถึงแผนที่มีการระบุตู้คอนเทนเนอร์แล้ว
            $query->whereNotNull('container_id');
        }

        $pullingPlans = $query->latest()->get();

        return view('container_open_return_cy.index', compact('pullingPlans', 'openType'));
    }

    /**
     * จัดการการยืนยัน order pulling ที่เลือก
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'pulling_plan_ids' => 'required|array|min:1',
            'pulling_plan_ids.*' => 'exists:container_pulling_plans,id',
        ]);

        DB::beginTransaction();
        try {
            $selectedPlanIds = $request->input('pulling_plan_ids');

            foreach ($selectedPlanIds as $planId) {
                $plan = ContainerPullingPlan::with('container')->find($planId);

                // ตรวจสอบว่าแผนยังอยู่ในสถานะที่ถูกต้อง
                if ($plan && $plan->status === 'CONFIRMED') {

                    // อัปเดตสต็อกคอนเทนเนอร์
                    $stock = ContainerStock::where('container_id', $plan->container_id)->first();
                    if ($stock) {
                        $stock->update([
                            'status' => 'AV', // Available
                            'date_in' => now(),
                            'vendor_id' => $plan->vendor_id,
                            'location_id' => null, // เคลียร์ location หรือตั้งเป็นค่าเริ่มต้น
                        ]);
                    } else {
                        // หากไม่มีสต็อก ให้สร้างใหม่
                        ContainerStock::create([
                            'container_id' => $plan->container_id,
                            'status' => 'AV',
                            'date_in' => now(),
                            'vendor_id' => $plan->vendor_id,
                        ]);
                    }

                    // สร้าง Transaction Log
                    ContainerTransaction::create([
                        'container_id' => $plan->container_id,
                        'activity' => 'OPEN-RETURN-CY',
                        'date' => now(),
                        'vendor_id' => $plan->vendor_id,
                        'container_pulling_plan_id' => $plan->id,
                        'user_id' => Auth::id(),
                    ]);

                    // อัปเดตสถานะของ Pulling Plan
                    $plan->update(['status' => 'COMPLETED']);
                }
            }

            DB::commit();
            return redirect()->route('container-open-return-cy.index')->with('success', 'ยืนยัน Container Open-ReturnCy สำเร็จ');

        } catch (\Exception $e) {
            DB::rollBack();
            // สามารถ Log error ไว้เพื่อตรวจสอบเพิ่มเติมได้
            // Log::error('Container Open-ReturnCy Error: ' . $e->getMessage());
            return redirect()->route('container-open-return-cy.index')->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }
}
