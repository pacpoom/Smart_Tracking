<?php

namespace App\Exports;

use App\Models\ContainerStock;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class ContainerStockExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
        return [
            'Plan No.',
            'Original Container No.',
            'Current Container No.',
            'House BL.',
            'model',
            'type',
            'Owner / Rental',
            'Agent',
            'Depot',
            'Current Location',
            'Stock Status',
            'ETA Date',
            'Check-in Date',
            'Expiration Date',
            'Remaining Free Time',
        ];
    }

    /**
     * @param ContainerStock $stock
     * @return array
     */
    public function map($stock): array
    {
        // --- ส่วนของการเตรียมข้อมูล ---
        $stockStatus = match ($stock->status) {
            1 => 'Full',
            2 => 'Partial',
            3 => 'Empty',
            default => 'Unknown'
        };

        $ownerRental = 'N/A';
        $isOwner = false;
        if (isset($stock->Container->container_owner)) {
            $isOwner = ($stock->Container->container_owner == 1);
            $ownerRental = $isOwner ? 'Owner' : 'Rental';
        }

        // ================== START: LOGIC ที่แก้ไขและง่ายขึ้น ==================

        // 1. ดึงค่าที่คำนวณแล้วจาก Model มาโดยตรง (Model จะคืนค่า Expired, N/A, หรือตัวเลข)
        $remainingTimeValue = $stock->containerOrderPlan?->remaining_free_time;

        // 2. กำหนดค่าที่จะแสดงผลเริ่มต้น
        $finalDisplayTime = $remainingTimeValue;

        // 3. จัดการการแสดงผล
        if ($isOwner) {
            $finalDisplayTime = 0;
        } elseif (is_numeric($remainingTimeValue)) {
            $finalDisplayTime = $remainingTimeValue . ' days';
        }

        // =================== END: LOGIC ที่แก้ไขและง่ายขึ้น ===================

        return [
            $stock->containerOrderPlan?->plan_no ?? 'N/A',
            $stock->containerOrderPlan?->container?->container_no ?? 'N/A',
            $stock->Container->container_no ?? 'N/A',
            $stock->containerOrderPlan?->house_bl ?? 'N/A',
            $stock->containerOrderPlan?->model ?? 'N/A',
            $stock->containerOrderPlan?->type ?? 'N/A',
            $ownerRental,
            $stock->Container->agent ?? 'N/A',
            $stock->Container?->depot ?? 'N/A',
            $stock->yardLocation?->location_code ?? 'N/A',
            $stockStatus,
            $stock->containerOrderPlan?->eta_date?->format('Y-m-d') ?? 'N/A',
            $stock->checkin_date?->format('Y-m-d'),
            $stock->containerOrderPlan?->expiration_date?->format('Y-m-d') ?? 'N/A',
            $finalDisplayTime,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1:1')->getFont()->setBold(true);
        // เนื่องจากใช้ FromQuery เราไม่สามารถนับแถวเพื่อใส่ Border ได้โดยตรง
        // หากจำเป็นต้องใส่เส้นขอบจริงๆ อาจต้องใช้วิธีอื่นที่ซับซ้อนกว่า
        return [];
    }
}
