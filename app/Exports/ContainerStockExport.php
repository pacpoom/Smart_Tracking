<?php

namespace App\Exports;

use App\Models\ContainerOrderPlan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ContainerStockExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
            'Plan No.',
            'Container No.',
            'House BL', // เพิ่มคอลัมน์นี้
            'Size',
            'Model', // เพิ่มคอลัมน์นี้
            'Type',  // เพิ่มคอลัมน์นี้
            'Current Location',
            'Check-in Date',
            'ETA Date',
            'Expiration Date',
            'Remaining Free Time',
        ];
    }

    /**
     * @param ContainerOrderPlan $stockPlan
     * @return array
     */
    public function map($stockPlan): array
    {
        $remainingTime = $stockPlan->remaining_free_time;
        if ($remainingTime !== 'Expired' && $remainingTime !== 'N/A') {
            $remainingTime .= ' days';
        }

        return [
            $stockPlan->plan_no,
            $stockPlan->container->container_no,
            $stockPlan->house_bl, // เพิ่มข้อมูลนี้
            $stockPlan->container->size,
            $stockPlan->model, // เพิ่มข้อมูลนี้
            $stockPlan->type,  // เพิ่มข้อมูลนี้
            $stockPlan->containerStock->yardLocation->location_code ?? 'N/A',
            $stockPlan->checkin_date?->format('Y-m-d'),
            $stockPlan->eta_date?->format('Y-m-d'),
            $stockPlan->expiration_date?->format('Y-m-d'),
            $remainingTime,
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Style the first row (headings) to be bold.
        $sheet->getStyle('1:1')->getFont()->setBold(true);

        // Apply borders to all cells
        $lastRow = $this->collection()->count() + 1;
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        // แก้ไข: ปรับ range ให้ครอบคลุมคอลัมน์ใหม่ (A1:J)
        $sheet->getStyle('A1:K'.$lastRow)->applyFromArray($styleArray);

        return [];
    }
}
