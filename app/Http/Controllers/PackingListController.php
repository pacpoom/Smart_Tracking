<?php

namespace App\Http\Controllers;

use App\Models\PackingList; // 1. Import Model PackingList
use Illuminate\Http\Request;
// 2. ลบ DB Facade ออกไป เพราะเราจะใช้ Eloquent Model แทน
// use Illuminate\Support\Facades\DB; 
use App\Exports\PackingListExport;
use Maatwebsite\Excel\Facades\Excel;

class PackingListController extends Controller
{
    
    public function index(Request $request)
    {
        // สร้าง query เริ่มต้นโดยเรียกใช้ฟังก์ชัน getPlanReportData() จาก Model
        $query = PackingList::getPlanReportData();

        // เพิ่มเงื่อนไขการค้นหาข้อมูลตามฟอร์มในหน้า View
        if ($request->filled('plan_no')) {
            $query->where('plans.plan_no', 'like', '%' . $request->plan_no . '%');
        }
        if ($request->filled('container_no')) {
            $query->where('containers.container_no', 'like', '%' . $request->container_no . '%');
        }
        if ($request->filled('material_no')) {
            $query->where('materials.material_number', 'like', '%' . $request->material_no . '%');
        }

        // หมายเหตุ: การ Export อาจจะต้องมีการปรับปรุงเพิ่มเติมเพื่อให้รองรับข้อมูลชุดใหม่
        if ($request->has('export')) {
            // return Excel::download(new YourNewExport($query->get()), 'plan_report.xlsx');
        }

        // ดึงข้อมูลและแบ่งหน้า (Paginate)
        $packingLists = $query->paginate(20)->withQueryString();

        return view('packing-list.index', compact('packingLists'));
    }

    public function export(Request $request)
    {
        return Excel::download(new PackingListExport($request->all()), 'packing-list.xlsx');
    }
}
