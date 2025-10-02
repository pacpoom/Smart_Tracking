<?php

namespace App\Http\Controllers;

use App\Models\Bom;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BomImport;
use App\Exports\BomTemplateExport;

class BomController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 25);

        $query = Bom::with(['vcMaster', 'childMaterial']);


        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('vcMaster', function ($q) use ($search) {
                $q->where('vc_code', 'like', '%' . $search . '%');
            });
        }

        $boms = $query->orderBy('id', 'desc')->paginate($perPage);

        return view('bom.index', compact('boms', 'perPage'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new BomImport, $request->file('file'));

            return redirect()->route('bom.index')->with('success', 'นำเข้าข้อมูล BOM สำเร็จ');

        } catch (\Exception $e) {
            return redirect()->route('bom.index')->with('error', 'เกิดข้อผิดพลาดระหว่างการนำเข้า: ' . $e->getMessage());
        }
    }

    public function exportTemplate()
    {
        return Excel::download(new BomTemplateExport, 'bom_template.xlsx');
    }
}
