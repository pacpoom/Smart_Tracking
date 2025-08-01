<?php

namespace App\Http\Controllers;

use App\Models\ContainerStock;
use App\Models\ContainerOrderPlan;
use Illuminate\Http\Request;
use App\Exports\ContainerStockExport; // 1. เพิ่ม use statement นี้
use Maatwebsite\Excel\Facades\Excel;   // 2. เพิ่ม use statement นี้

class ContainerStockController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:view container stock');
        // $this->middleware('permission:export container stock', ['only' => ['export']]); // 3. เพิ่ม middleware นี้
    }

    public function index(Request $request)
    {
        // 2. แก้ไข Query ให้ดึงข้อมูลจาก ContainerStock
        $query = ContainerStock::with(['containerOrderPlan.container', 'yardLocation']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('containerOrderPlan', function ($subQ) use ($search) {
                    $subQ->where('plan_no', 'like', '%' . $search . '%');
                })
                ->orWhereHas('containerOrderPlan.container', function ($subQ) use ($search) {
                    $subQ->where('container_no', 'like', '%' . $search . '%');
                })
                ->orWhereHas('yardLocation', function ($subQ) use ($search) {
                    $subQ->where('location_code', 'like', '%' . $search . '%');
                });
            });
        }

        $stocks = $query->latest()->paginate(10);

        return view('container-stocks.index', compact('stocks'));
    }

    /**
     * Export container stock data to an Excel file.
     */
    public function export(Request $request)
    {
        // 3. แก้ไข Query ในฟังก์ชัน Export ด้วย
        $query = ContainerStock::with(['containerOrderPlan.container', 'yardLocation']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('containerOrderPlan', function ($subQ) use ($search) {
                    $subQ->where('plan_no', 'like', '%' . $search . '%');
                })
                ->orWhereHas('containerOrderPlan.container', function ($subQ) use ($search) {
                    $subQ->where('container_no', 'like', '%' . $search . '%');
                })
                ->orWhereHas('yardLocation', function ($subQ) use ($search) {
                    $subQ->where('location_code', 'like', '%' . $search . '%');
                });
            });
        }

        return Excel::download(new ContainerStockExport($query), 'container_stock.xlsx');
    }
    
    public function search1(Request $request)
    {
        $search = $request->term;

        // แก้ไข: ค้นหา Order Plan ที่มีข้อมูลอยู่ในตาราง container_stocks
        $plans = ContainerOrderPlan::with('container')
                    ->whereHas('containerStock') // <-- นี่คือส่วนที่แก้ไข
                    ->where(function($query) use ($search) {
                        if ($search) {
                            $query->where('plan_no', 'LIKE', "%{$search}%")
                                  ->orWhere('house_bl', 'LIKE', "%{$search}%")
                                  ->orWhereHas('container', function($q) use ($search) {
                                      $q->where('container_no', 'LIKE', "%{$search}%");
                                  });
                        }
                    })
                    ->limit(15)
                    ->get();

        $formatted_plans = [];
        foreach ($plans as $plan) {
            $formatted_plans[] = [
                'id' => $plan->container->id,
                'text' => $plan->container->container_no . ' (Plan: ' . $plan->plan_no . ')'
            ];
        }

        return response()->json($formatted_plans);
    }
    
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
                $formatted_stocks[] = [
                    'id' => $stock->id,
                    'text' => $stock->containerOrderPlan->container->container_no . ' (Plan: ' . $stock->containerOrderPlan->plan_no . ')'
                ];
            }
        }

        return response()->json($formatted_stocks);
    }
}
