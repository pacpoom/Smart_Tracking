<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ContainerOrderPlanTemplateExport implements WithHeadings, ShouldAutoSize
{
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'container_no',
            'model',
            'type',
            'house_bl',
            'eta_date',
            'free_time',
            'depot',
            'agent',
            'week_lot',
            'checkin_date',
        ];
    }
}
