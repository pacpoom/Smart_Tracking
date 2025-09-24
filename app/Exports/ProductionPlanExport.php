<?php

namespace App\Exports;

use App\Models\ProductionPlan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductionPlanExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $query;

    public function __construct($query)
    {
        // Eager load relationships for efficiency
        $this->query = $query->with(['user', 'vcMaster']);
    }

    public function collection()
    {
        $plans = $this->query->get();
        $rows = [];

        foreach ($plans as $plan) {
            if (!empty($plan->details) && is_array($plan->details)) {
                foreach ($plan->details as $index => $detail) {
                    $stockQty = $detail['stock_qty'] ?? 0;
                    $requiredQty = $detail['required_qty'] ?? 0;
                    $balance = $stockQty - $requiredQty;

                    // For the first detail row, print the plan info. For subsequent rows, leave it blank for readability.
                    $rows[] = [
                        'plan_no' => $index === 0 ? $plan->plan_no : '',
                        'vc_code' => $index === 0 ? ($plan->vcMaster->vc_code ?? 'N/A') : '',
                        'model' => $index === 0 ? ($plan->vcMaster->model ?? 'N/A') : '',
                        'production_order' => $index === 0 ? $plan->production_order : '',
                        'production_date' => $index === 0 ? $plan->production_date->format('Y-m-d') : '',
                        'status' => $index === 0 ? ucfirst($plan->status) : '',
                        'created_by' => $index === 0 ? ($plan->user->name ?? 'N/A') : '',
                        'created_at' => $index === 0 ? $plan->created_at->format('Y-m-d H:i:s') : '',
                        'material_number' => $detail['material_number'] ?? 'N/A',
                        'material_name' => $detail['material_name'] ?? 'N/A',
                        'bom_qty' => $detail['bom_qty'] ?? 0,
                        'required_qty' => $requiredQty,
                        'stock_qty' => $stockQty,
                        'balance' => $balance,
                    ];
                }
            } else {
                // Add a single row for the plan if it has no details
                 $rows[] = [
                    'plan_no' => $plan->plan_no,
                    'vc_code' => $plan->vcMaster->vc_code ?? 'N/A',
                    'model' => $plan->vcMaster->model ?? 'N/A',
                    'production_order' => $plan->production_order,
                    'production_date' => $plan->production_date->format('Y-m-d'),
                    'status' => ucfirst($plan->status),
                    'created_by' => $plan->user->name ?? 'N/A',
                    'created_at' => $plan->created_at->format('Y-m-d H:i:s'),
                    'material_number' => 'No details available.',
                    'material_name' => '',
                    'bom_qty' => '',
                    'required_qty' => '',
                    'stock_qty' => '',
                    'balance' => '',
                ];
            }
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return [
            'Plan No',
            'VC Code',
            'Model',
            'Production Order',
            'Production Date',
            'Status',
            'Created By',
            'Created At',
            'Material Number',
            'Material Name',
            'BOM Qty',
            'Required Qty',
            'Stock Qty',
            'Balance',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the first row (headings) to be bold.
        $sheet->getStyle('1:1')->getFont()->setBold(true);
        return [];
    }
}