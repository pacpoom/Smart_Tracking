<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

//                                                                      
class PackingListExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStrictNullComparison
{
    protected Builder $query;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'Plan No',
            'Container No',
            'Agent',
            'Material No',
            'Model',
            'Part Type',
            'Uloc',
            'Pull Type',
            'Quantity',
            'Unit',
        ];
    }

    /**
     * @param mixed $row From the query result
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->plan_no ?? 'N/A',
            $row->container_no ?? 'N/A',
            $row->agent ?? 'N/A',
            $row->material_number ?? 'N/A',
            $row->model ?? 'N/A',
            $row->part_type ?? 'N/A',
            $row->uloc ?? 'N/A',
            $row->pull_type ?? 'N/A',
            (int)($row->Qty ?? 0), // <--- 3. แก้ไขตรงนี้ บังคับเป็น int
            $row->unit ?? 'N/A',
        ];
    }
}
