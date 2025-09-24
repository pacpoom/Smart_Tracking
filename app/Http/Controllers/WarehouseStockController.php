<?php

namespace App\Http\Controllers;

use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\WarehouseStockExport;
use Maatwebsite\Excel\Facades\Excel;

class WarehouseStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 25);

        // Translate the SQL query to Eloquent
        // Eager load relationships to prevent N+1 queries
        $query = WarehouseStock::with(['material.primaryPfep']);

        // Handle search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('material', function ($q) use ($search) {
                $q->where('material_number', 'like', '%' . $search . '%')
                  ->orWhere('material_name', 'like', '%' . $search . '%');
            });
        }
        
        // Paginate the results
        $stocks = $query->latest('id')->paginate($perPage)->withQueryString();

        return view('warehouse-stock.index', compact('stocks', 'perPage'));
    }

    /**
     * Export data to CSV file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        // Reuse the same query logic from the index method
        $query = WarehouseStock::with(['material.primaryPfep']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('material', function ($q) use ($search) {
                $q->where('material_number', 'like', '%' . $search . '%')
                  ->orWhere('material_name', 'like', '%' . $search . '%');
            });
        }

        return Excel::download(new WarehouseStockExport($query->latest('id')), 'warehouse_stock.csv');
    }
}