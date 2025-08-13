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
            'Original Container No.',
            'Current Container No.', // เพิ่มคอลัมน์นี้
            'House BL.', // เพิ่มคอลัมน์นี้
            'Size',
            'Current Location',
            'Stock Status',
            'ETA Date',
            'Check-in Date',
            'Expiration Date', // เพิ่มคอลัมน์นี้
            'Remaining Free Time',
        ];
    }

    /**
     * @param ContainerOrderPlan $stockPlan
     * @return array
     */
    public function map($stock): array
    {
        $stockStatus = match ($stock->status) {
            1 => 'Full',
            2 => 'Partial',
            3 => 'Empty',
            default => 'Unknown',
        };

        $remainingTime = $stock->containerOrderPlan?->remaining_free_time;
        if ($remainingTime !== 'Expired' && $remainingTime !== 'N/A') {
            $remainingTime .= ' days';
        }

        return [
            $stock->containerOrderPlan?->plan_no ?? 'N/A',
            $stock->containerOrderPlan?->container?->container_no ?? 'N/A',
            $stock->Container->container_no ?? $stock->containerOrderPlan?->container?->container_no, // ใช้ข้อมูลจาก Container หรือจาก ContainerOrderPlan
            $stock->containerOrderPlan?->house_bl ?? 'N/A', // เพิ่มข้อมูลนี้
            $stock->containerOrderPlan?->container?->size ?? 'N/A',
            $stock->yardLocation?->location_code ?? 'N/A',
            $stockStatus,
            $stock->containerOrderPlan?->eta_date?->format('Y-m-d') ?? 'N/A',
            $stock->checkin_date?->format('Y-m-d'),
            $stock->containerOrderPlan?->expiration_date?->format('Y-m-d'), // เพิ่มข้อมูลนี้
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
