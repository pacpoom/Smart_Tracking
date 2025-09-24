<?php

namespace App\Exports;

use App\Models\WarehouseStock;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class WarehouseStockExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
    * @return \Illuminate\Database\Eloquent\Builder
    */
    public function query()
    {
        return $this->query;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Material Number',
            'Material Name',
            'Model',
            'Part Type',
            'ULOC',
            'Pull Type',
            'Line Side',
            'Quantity',
            'Unit',
        ];
    }

    /**
     * @param WarehouseStock $stock
     * @return array
     */
    public function map($stock): array
    {
        return [
            $stock->material?->material_number ?? 'N/A',
            $stock->material?->material_name ?? 'N/A',
            $stock->material?->primaryPfep?->model ?? 'N/A',
            $stock->material?->primaryPfep?->part_type ?? 'N/A',
            $stock->material?->primaryPfep?->uloc ?? 'N/A',
            $stock->material?->primaryPfep?->pull_type ?? 'N/A',
            $stock->material?->primaryPfep?->line_side ?? 'N/A',
            $stock->qty,
            $stock->material?->unit ?? 'N/A',
        ];
    }
}
