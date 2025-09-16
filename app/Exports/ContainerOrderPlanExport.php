<?php

namespace App\Exports;

use App\Models\ContainerOrderPlan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ContainerOrderPlanExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $query;

    public function __construct($query)
    {
        // Eager load the 'container' relationship for efficiency
        $this->query = $query->with('container');
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {

        return [
            'Plan No.',
            'Container No.',
            'Model',
            'Type',
            'House BL',
            'Agent',
            'Depot',
            'ETA Date',
            'Free Time',
            'Expiration Date',
            'Check-in Date',
            'Departure Date',
            'Status',
        ];
    }

    /**
     * @param ContainerOrderPlan $plan
     * @return array
     */
    public function map($plan): array
    {
        $statusText = $plan->status == 1 ? 'Active' : 'Inactive';


        return [
            $plan->plan_no,
            $plan->container->container_no ?? 'N/A',
            $plan->model,
            $plan->type,
            $plan->house_bl,
            $plan->container->agent ?? 'N/A',
            $plan->container->depot ?? 'N/A',
            $plan->eta_date?->format('Y-m-d'),
            $plan->free_time,
            $plan->expiration_date?->format('Y-m-d'),
            $plan->checkin_date?->format('Y-m-d'),
            $plan->departure_date?->format('Y-m-d'),
            $statusText,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1:1')->getFont()->setBold(true);
        // You can add more styling here if needed
        return [];
    }
}
