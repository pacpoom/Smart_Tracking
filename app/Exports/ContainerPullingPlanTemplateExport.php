<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class ContainerPullingPlanTemplateExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Trả về một hàng ví dụ
        return collect([
            ['CONTAINER-NO-123', 'Pull', 1, '20251029'],
        ]);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Cột theo yêu cầu
        return [
            'Container No.',
            'Plan Type(All,Pull)',
            'Pulling Order',
            'Pulling Date(yyyyMMdd)',
        ];
    }
}
