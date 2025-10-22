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

        // ดึงข้อมูลและแบ่งหน้า (Paginate)
        // **เพิ่ม orderBy ที่นี่สำหรับหน้า Index ด้วย**
        $packingLists = $query->orderBy('plans.id')->paginate(20)->withQueryString(); // <--- เพิ่ม orderBy

        return view('packing-list.index', compact('packingLists'));
    }

    public function export(Request $request)
    {
        // สร้าง query เริ่มต้น
        $query = PackingList::getPlanReportData();

        // ใช้เงื่อนไขการค้นหาเดิมจาก request ที่ส่งมาจากลิงก์
        if ($request->filled('plan_no')) {
            $query->where('plans.plan_no', 'like', '%' . $request->plan_no . '%');
        }
        if ($request->filled('container_no')) {
            $query->where('containers.container_no', 'like', '%' . $request->container_no . '%');
        }
        if ($request->filled('material_no')) {
            $query->where('materials.material_number', 'like', '%' . $request->material_no . '%');
        }

        // **เพิ่ม orderBy ที่นี่สำหรับ Export**
        $query->orderBy('plans.id'); // <--- เพิ่มบรรทัดนี้

        // ส่ง query builder ที่กรองและเรียงลำดับแล้วไปให้ Export class
        return Excel::download(new PackingListExport($query), 'packing-list.xlsx');
    }
}
