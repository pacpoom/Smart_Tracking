<?php

namespace App\Http\Controllers;

use App\Models\PackingList;
use Illuminate\Http\Request;

class PackingListController extends Controller
{
    /**
     * ดึงข้อมูลจาก packing_list (mysql_second)
     * เฉพาะคอลัมน์ที่ระบุ: storage_location, delivery_order, container, case_number, box_id, temp_material, quantity
     */
    public function index(Request $request)
    {
        // เลือกเฉพาะคอลัมน์ที่ต้องการ
        $query = PackingList::select(
            'id', // ควรเลือก id มาด้วยสำหรับการจัดการ
            'storage_location',
            'delivery_order',
            'container',
            'case_number',
            'box_id',
            'temp_material',
            'quantity'
        );

        // ใช้ Scope Filter ที่มีอยู่ในการกรองข้อมูล (ถ้ามี search params ส่งมา)
        $packingLists = $query->filter($request)->paginate(50); // หรือใช้ ->get() ถ้าไม่ต้องการแบ่งหน้า

        // ส่งข้อมูลกลับไป หรือส่งไปที่ View
        // return view('packing_list.index', compact('packingLists'));
        
        // เพื่อการทดสอบ แสดงเป็น JSON
        return response()->json($packingLists);
    }
}