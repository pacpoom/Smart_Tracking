<?php

namespace App\Http\Controllers;

use App\Models\PackingList;
use Illuminate\Http\Request;
use App\Exports\PackingListExport;
use Maatwebsite\Excel\Facades\Excel;

class PackingListController extends Controller
{
    public function index(Request $request)
    {
        $query = PackingList::with(['container', 'material']);

        // --- แก้ไข Logic การค้นหาตามวันที่ ---
        $dateFrom = $request->input('delivery_date_from');
        $dateTo = $request->input('delivery_date_to');

        if ($dateFrom && $dateTo) {
            // กรณีที่กรอกทั้งสองวัน: ให้ค้นหาเป็นช่วงเวลา
            $query->whereBetween('delivery_date', [$dateFrom, $dateTo]);
        } elseif ($dateFrom) {
            // กรณีที่กรอกแค่วันที่เริ่มต้น: ให้ค้นหาเฉพาะวันที่นั้นๆ
            $query->whereDate('delivery_date', $dateFrom);
        }

        // --- Logic การค้นหาอื่นๆ (เหมือนเดิม) ---
        if ($request->filled('container_no')) {
            $searchContainer = $request->container_no;
            $query->whereHas('container', function ($q) use ($searchContainer) {
                $q->where('container_no', 'like', '%' . $searchContainer . '%');
            });
        }

        if ($request->filled('material_number')) {
            $searchMaterial = $request->material_number;
            $query->whereHas('material', function ($q) use ($searchMaterial) {
                $q->where('material_number', 'like', '%' . $searchMaterial . '%');
            });
        }

        if ($request->has('export')) {
            return Excel::download(new PackingListExport($query), 'packing_list.xlsx');
        }

        $packing_lists = $query->latest()->paginate(20)->withQueryString();

        return view('packing-list.index', compact('packing_lists'));
    }
}
