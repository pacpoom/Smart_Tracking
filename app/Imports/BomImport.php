<?php

namespace App\Imports;

use App\Models\Bom;
use App\Models\Material;
use App\Models\VcMaster;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class BomImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // ตรวจสอบว่ามี vc_code หรือไม่ ถ้าไม่มีให้ข้าม
        if (empty($row['vc_code'])) {
            return null;
        }

        // ค้นหาหรือสร้าง VcMaster ใหม่ถ้ายังไม่มี
        // โดยจะกำหนดค่า default สำหรับ field อื่นๆ ที่ not-null
        $vcMaster = VcMaster::firstOrCreate(
            ['vc_code' => $row['vc_code']],
            ['option_code' => '', 'model' => '', 'color' => '']
        );

        // ค้นหา material จาก material_number
        $material = Material::where('material_number', $row['material_number'])->first();

        // หากไม่พบ material หรือ qty ไม่ถูกต้อง ให้ข้ามการนำเข้าแถวนี้
        if (!$material || !is_numeric($row['qty'])) {
            return null;
        }

        // อัปเดตข้อมูลถ้ามีอยู่แล้ว หรือสร้างใหม่ถ้ายังไม่มี
        // โดยจะตรวจสอบจาก vc_code_id และ material_id
        return Bom::updateOrCreate(
            [
                'vc_code_id'   => $vcMaster->id,
                'material_id' => $material->id,
            ],
            [
                'qty' => $row['qty'],
            ]
        );
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}