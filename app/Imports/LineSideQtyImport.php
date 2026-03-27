<?php

namespace App\Imports;

use App\Models\Material;
use App\Models\WarehouseStock;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class LineSideQtyImport implements ToModel, WithHeadingRow
{
    /**
     * Constructor จะทำงานก่อนการ import ข้อมูล
     * เราจะตั้งค่า line_side_qty ทั้งหมดเป็น 0 ที่นี่
     */
    public function __construct()
    {
        // นี่คือส่วนที่เพิ่มเข้ามา: อัปเดตทุกแถวใน WarehouseStock ให้ line_side_qty เป็น 0
        WarehouseStock::query()->update(['line_side_qty' => 0]);
    }

    /**
    * @param array $row
    *
    * @return void
    */
    public function model(array $row)
    {
        // Find the material by its name from the 'material' column in the Excel file.
        $material = Material::where('material_number', $row['material'])->first();

        // If a matching material is found, update its line_side_qty.
        if ($material) {
            // updateOrCreate will find a WarehouseStock record by material_id
            // or create a new one if it doesn't exist.
            // ตอนนี้มันจะอัปเดตจากค่า 0 ที่เราตั้งไว้ หรือสร้างใหม่ด้วยค่าที่ถูกต้องจากไฟล์
            WarehouseStock::updateOrCreate(
                ['material_id' => $material->id],
                ['line_side_qty' => $row['line_side_qty']]
            );
        } else {
            // Log a warning if a material from the file is not found in the database.
            Log::warning('Material not found during Line Side Qty import: ' . $row['material']);
        }
    }
}
