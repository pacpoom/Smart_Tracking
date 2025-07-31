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
}
