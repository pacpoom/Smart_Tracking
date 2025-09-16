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
            'Detention',
            'Expired Date',
            'Aging (Days)',

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

        // Logic สำหรับ Detention 
        $detentionTime = $stock->containerOrderPlan?->remaining_free_time;

        if ($isOwner) {
            $detentionTime = 'N/A'; // ถ้าเป็น Owner จะไม่มี Detention
        } elseif (is_numeric($detentionTime)) {
            $detentionTime = $detentionTime . ' days';
        }

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
            $detentionTime,
            $stock->expired_date ? $stock->expired_date->format('Y-m-d') : 'N/A',
            $stock->aging_days,

        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1:1')->getFont()->setBold(true);
        return [];
    }
}
