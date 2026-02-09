<?php

namespace App\Http\Controllers;

use App\Models\PackingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PackingListController extends Controller
{
    /**
     * ดึงข้อมูลจาก packing_list (mysql_second)
     */
    public function index(Request $request)
    {
        // กำหนดจำนวนรายการต่อหน้า (Default 50)
        $perPage = $request->input('per_page', 50);
        $allowedPerPage = [50, 100, 500, 2000];
        
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 50;
        }

        // เลือกเฉพาะคอลัมน์ที่ต้องการ
        $query = PackingList::select(
            'id',
            'storage_location',
            'receive_flg',
            'delivery_order',
            'container',
            'case_number',
            'box_id',
            'temp_material',
            'quantity'
        );

        // ใช้ Scope Filter ที่มีอยู่
        $packingLists = $query->filter($request)->paginate($perPage);

        return view('packing-list.index', compact('packingLists', 'perPage'));
    }

    /**
     * Export ข้อมูลเป็น CSV ตามเงื่อนไขการค้นหา
     */
    public function export(Request $request)
    {
        $fileName = 'packing-list-' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // Callback function สำหรับเขียนข้อมูลลงใน stream (เพื่อรองรับข้อมูลจำนวนมาก)
        $callback = function() use ($request) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel UTF-8 compatibility
            fputs($file, "\xEF\xBB\xBF");

            // CSV Header
            fputcsv($file, [
                'Storage Location', 
                'receive_flg',
                'Delivery Order', 
                'Container', 
                'Case Number', 
                'Box ID', 
                'Temp Material', 
                'Quantity'
            ]);

            // Query ข้อมูล (ใช้ chunk เพื่อประหยัด Memory กรณีข้อมูลเยอะ)
            $query = PackingList::select(
                'storage_location',
                'receive_flg',
                'delivery_order',
                'container',
                'case_number',
                'box_id',
                'temp_material',
                'quantity'
            );

            $query->filter($request)->chunk(1000, function($rows) use ($file) {
                foreach ($rows as $row) {
                    fputcsv($file, [
                        $row->storage_location,
                        $row->receive_flg,
                        $row->delivery_order,
                        $row->container,
                        $row->case_number,
                        $row->box_id,
                        $row->temp_material,
                        $row->quantity
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}