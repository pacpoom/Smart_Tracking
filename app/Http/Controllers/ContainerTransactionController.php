<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Models\ContainerTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon; // 1. เพิ่ม use statement นี้

class ContainerTransactionController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:view container transactions');
        // $this->middleware('permission:export container transactions', ['only' => ['export']]);
    }

    public function index(Request $request)
    {
        // 2. กำหนดค่าเริ่มต้นและรับค่าวันที่จากฟอร์ม
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $query = ContainerTransaction::with(['containerOrderPlan.container', 'user', 'yardLocation']);

        // 3. กรองข้อมูลตามช่วงวันที่
        $query->whereBetween('transaction_date', [$startDate, $endDate]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('containerOrderPlan.container', function ($q) use ($search) {
                $q->where('container_no', 'like', '%' . $search . '%');
            });
        }

        $transactions = $query->latest('transaction_date')->paginate(15);
        
        // 4. ส่งค่าวันที่กลับไปที่ View
        return view('container-transactions.index', compact('transactions', 'startDate', 'endDate'));
    }

    /**
     * Export container transaction data to an Excel file.
     */
    public function export(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $query = ContainerTransaction::with(['containerOrderPlan.container', 'user', 'yardLocation']);

        $query->whereBetween('transaction_date', [$startDate, $endDate]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('containerOrderPlan.container', function ($q) use ($search) {
                $q->where('container_no', 'like', '%' . $search . '%');
            });
        }

        return Excel::download(new \App\Exports\ContainerTransactionExport($query), 'container_transactions.xlsx');
    }
}
