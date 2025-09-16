<?php

namespace App\Http\Controllers;

use App\Models\ContainerStock;
use App\Models\ContainerOrderPlan;
use Illuminate\Http\Request;
use App\Exports\ContainerStockExport;
use Maatwebsite\Excel\Facades\Excel;

class ContainerStockController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:view container stock');
        // $this->middleware('permission:export container stock');
    }

    public function index(Request $request)
    {
        // 1. สร้าง Query หลัก
        $query = ContainerStock::with([
            'Container',
            'vendor',
            'yardLocation',
            'containerOrderPlan',
            'containerOrderPlan.container'
        ]);

        // 2. การค้นหาด้วย Plan No. หรือ Container No.
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('Container', function ($subQ) use ($search) {
                    $subQ->where('container_no', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('containerOrderPlan', function ($subQ) use ($search) {
                        $subQ->where('plan_no', 'like', '%' . $search . '%');
                    });
            });
        }

        // 3. ค้นหาตามช่วงวันที่ Check-in Date
        if ($request->filled('checkin_date_from')) {
            $query->whereDate('checkin_date', '>=', $request->checkin_date_from);
        }
        if ($request->filled('checkin_date_to')) {
            $query->whereDate('checkin_date', '<=', $request->checkin_date_to);
        }

        // 4. ค้นหาตามอายุตู้ (Detention)
        if ($request->filled('detention_days')) {
            $days = (int) $request->detention_days;
            $query->whereNotNull('checkin_date')->whereRaw('DATEDIFF(CURDATE(), checkin_date) >= ?', [$days]);
        }

        // 5. จัดการการ Export โดยใช้ Query ที่มีเงื่อนไขทั้งหมด
        if ($request->has('export')) {
            return Excel::download(new ContainerStockExport($query), 'container_stock.xlsx');
        }

        // ดึงข้อมูลและแสดงผลพร้อมกับ query string เดิม
        $stocks = $query->latest('id')->paginate(10)->withQueryString();

        return view('container-stocks.index', compact('stocks'));
    }

    public function export(Request $request)
    {
        // สร้าง Query หลัก (เหมือนกับใน index)
        $query = ContainerStock::with([
            'Container',
            'vendor',
            'yardLocation',
            'containerOrderPlan',
            'containerOrderPlan.container'
        ]);

        // การค้นหาด้วย Plan No. หรือ Container No. (เหมือนกับใน index)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('Container', function ($subQ) use ($search) {
                    $subQ->where('container_no', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('containerOrderPlan', function ($subQ) use ($search) {
                        $subQ->where('plan_no', 'like', '%' . $search . '%');
                    });
            });
        }

        // (จำเป็นต้องใส่เงื่อนไขใหม่ที่นี่ด้วยเพื่อให้ Export จาก link เดิมทำงานได้)
        // ค้นหาตามช่วงวันที่ Check-in Date
        if ($request->filled('checkin_date_from')) {
            $query->whereDate('checkin_date', '>=', $request->checkin_date_from);
        }
        if ($request->filled('checkin_date_to')) {
            $query->whereDate('checkin_date', '<=', $request->checkin_date_to);
        }

        // ค้นหาตามอายุตู้ (Detention)
        if ($request->filled('detention_days')) {
            $days = (int) $request->detention_days;
            $query->whereNotNull('checkin_date')->whereRaw('DATEDIFF(CURDATE(), checkin_date) >= ?', [$days]);
        }

        return Excel::download(new ContainerStockExport($query), 'container_stock.xlsx');
    }

    // คงฟังก์ชัน search1 เดิมไว้
    public function search1(Request $request)
    {
        $search = $request->term;
        $plans = ContainerOrderPlan::with('container')
            ->whereHas('containerStock')
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query->where('plan_no', 'LIKE', "%{$search}%")
                        ->orWhere('house_bl', 'LIKE', "%{$search}%")
                        ->orWhereHas('container', function ($q) use ($search) {
                            $q->where('container_no', 'LIKE', "%{$search}%");
                        });
                }
            })
            ->limit(15)
            ->get();

        $formatted_plans = [];
        foreach ($plans as $plan) {
            $formatted_plans[] = ['id' => $plan->container->id, 'text' => $plan->container->container_no . ' (Plan: ' . $plan->plan_no . ')'];
        }
        return response()->json($formatted_plans);
    }

    // คงฟังก์ชัน search เดิมไว้
    public function search(Request $request)
    {
        $search = $request->term;
        $query = ContainerStock::with(['containerOrderPlan.container']);
        if ($search) {
            $query->whereHas('containerOrderPlan', function ($q) use ($search) {
                $q->where('plan_no', 'LIKE', "%{$search}%")
                    ->orWhereHas('container', function ($subQ) use ($search) {
                        $subQ->where('container_no', 'LIKE', "%{$search}%");
                    });
            });
        }
        $stocks = $query->limit(15)->get();
        $formatted_stocks = [];
        foreach ($stocks as $stock) {
            if ($stock->containerOrderPlan && $stock->containerOrderPlan->container) {
                $formatted_stocks[] = ['id' => $stock->id, 'text' => $stock->containerOrderPlan->container->container_no . ' (Plan: ' . $stock->containerOrderPlan->plan_no . ')'];
            }
        }
        return response()->json($formatted_stocks);
    }
}
