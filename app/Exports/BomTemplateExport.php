<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;

class BomTemplateExport implements WithHeadings
{
    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            'vc_code',
            'material_number',
            'qty',
        ];
    }
}

