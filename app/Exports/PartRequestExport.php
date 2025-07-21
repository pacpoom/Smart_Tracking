<?php

namespace App\Exports;

use App\Models\PartRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // 1. เพิ่ม use statement นี้
use Maatwebsite\Excel\Concerns\WithStyles;      // 2. เพิ่ม use statement นี้
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet; // 3. เพิ่ม use statement นี้

class PartRequestExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Part Number',
            'Part Name (EN)',
            'Requested By',
            'Quantity',
            'Required Date',
            'Delivery Date',
            'Arrival Date',
            'Status',
            'Reason',
        ];
    }

    /**
     * @param PartRequest $request
     * @return array
     */
    public function map($request): array
    {
        return [
            $request->id,
            $request->part->part_number,
            $request->part->part_name_eng,
            $request->user->name,
            $request->quantity,
            $request->required_date?->format('Y-m-d'),
            $request->delivery_date?->format('Y-m-d'),
            $request->arrival_date?->format('Y-m-d'),
            ucfirst($request->status),
            $request->reason,
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // ทำตัวหนาที่แถวแรก (Header)
        $sheet->getStyle('1:1')->getFont()->setBold(true);

        // ใส่เส้นขอบให้กับข้อมูลทั้งหมด
        $lastRow = $this->collection()->count() + 1;
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A1:J'.$lastRow)->applyFromArray($styleArray);

        return [];
    }
}
