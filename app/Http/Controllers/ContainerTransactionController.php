<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Models\ContainerTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon; 

class ContainerTransactionController extends Controller
{

    public function index(Request $request)
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

        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }

        $transactions = $query->latest('transaction_date')->paginate(15)->withQueryString();
        
        $activities = ContainerTransaction::select('activity_type')->distinct()->whereNotNull('activity_type')->pluck('activity_type');
        
        return view('container-transactions.index', compact('transactions', 'startDate', 'endDate', 'activities'));
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

        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }

        return Excel::download(new \App\Exports\ContainerTransactionExport($query), 'container_transactions.xlsx');
    }
}