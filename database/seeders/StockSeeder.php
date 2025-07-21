<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Part;
use App\Models\Stock;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ดึง Part ทั้งหมดที่ยังไม่มีข้อมูลในตาราง Stock
        $partsWithoutStock = Part::whereDoesntHave('stock')->get();

        foreach ($partsWithoutStock as $part) {
            Stock::create([
                'part_id' => $part->id,
                'qty' => 0, // กำหนดค่าเริ่มต้นเป็น 0
            ]);
        }
    }
}
