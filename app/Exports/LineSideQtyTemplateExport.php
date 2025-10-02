<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LineSideQtyTemplateExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // This provides an example row in the exported template file.
        // You can leave this empty if you prefer a blank template.
        return collect([
            ['MATERIAL-EXAMPLE-001', 123],
        ]);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Defines the header row of the excel file.
        return [
            'material',
            'line_side_qty',
        ];
    }
}