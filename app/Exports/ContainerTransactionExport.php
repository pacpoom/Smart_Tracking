<?php

namespace App\Exports;

use App\Models\ContainerTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ContainerTransactionExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Container No.',
            'House B/L',
            'Activity',
            'Location',
            'User',
            'Transaction Date',
            'Remarks',
        ];
    }

    /**
     * @param ContainerTransaction $transaction
     * @return array
     */
    public function map($transaction): array
    {
        return [
            $transaction->containerOrderPlan?->container?->container_no ?? 'N/A',
            $transaction->house_bl,
            $transaction->activity_type,
            $transaction->yardLocation?->location_code ?? 'N/A',
            $transaction->user?->name ?? 'N/A',
            $transaction->transaction_date?->format('Y-m-d H:i:s'),
            $transaction->remarks,
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Style the first row (headings) to be bold.
        $sheet->getStyle('1:1')->getFont()->setBold(true);

        // Apply borders to all cells
        $lastRow = $this->collection()->count() + 1;
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A1:G'.$lastRow)->applyFromArray($styleArray);

        return [];
    }
}
