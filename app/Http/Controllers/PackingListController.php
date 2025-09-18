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
        $dateFrom = $request->input('delivery_date_from');
        $dateTo = $request->input('delivery_date_to');

        if ($dateFrom && $dateTo) {
            $query->whereBetween('delivery_date', [$dateFrom, $dateTo]);
        } elseif ($dateFrom) {
            $query->whereDate('delivery_date', $dateFrom);
        }

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
            set_time_limit(300);

            return Excel::download(new PackingListExport($query), 'packing_list.xlsx');
        }
        $packing_lists = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();

        return view('packing-list.index', compact('packing_lists'));
    }
}
