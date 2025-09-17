<?php

namespace App\Exports;

use App\Models\PackingList;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PackingListExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        // กำหนดหัวข้อคอลัมน์ในไฟล์ Excel ให้ตรงกับหน้าเว็บล่าสุด
        return [
            'Storage Location',
            'Item Number',
            'Delivery Order',
            'Delivery Item Number',
            'Delivery Date',
            'Container No',
            'Agent',
            'Material Number',
            'Material Name',
            'Unit',
            'Case Number',
            'Box ID',
            'Quantity',
        ];
    }

    /**
     * @param PackingList $list
     * @return array
     */
    public function map($list): array
    {
        // กำหนดข้อมูลที่จะใส่ในแต่ละแถวของ Excel
        return [
            $list->storage_location,
            $list->item_number,
            $list->delivery_order,
            $list->delivery_item_number,
            $list->delivery_date ? $list->delivery_date->format('Y-m-d') : null,
            $list->container?->container_no,
            $list->container?->agent,
            $list->material?->material_number,
            $list->material?->material_name,
            $list->material?->unit,
            $list->case_number,
            $list->box_id,
            $list->quantity,
        ];
    }
}
