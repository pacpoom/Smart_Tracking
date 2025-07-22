<?php

namespace App\Exports;

use App\Models\ContainerOrderPlan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ContainerOrderPlanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection()
    {
        return $this->query->get();
    }

    public function headings(): array
    {
        return [
            'Plan No.',
            'Container No.',
            'House B/L',
            'Model',
            'Type',
            'ETA Date',
            'Check-in Date',
            'Status',
        ];
    }

    public function map($plan): array
    {
        $statusText = match ($plan->status) {
            1 => 'Pending',
            2 => 'Received',
            3 => 'Shipped Out',
            default => 'Unknown',
        };

        return [
            $plan->plan_no,
            $plan->container->container_no,
            $plan->house_bl,
            $plan->model,
            $plan->type,
            $plan->eta_date?->format('Y-m-d'),
            $plan->checkin_date?->format('Y-m-d'),
            $statusText,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1:1')->getFont()->setBold(true);
        $lastRow = $this->collection()->count() + 1;
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A1:H'.$lastRow)->applyFromArray($styleArray);
        return [];
    }
}
