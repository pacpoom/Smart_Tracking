<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MonitoringPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get the search values from the request
        $vcCode = $request->input('vc_code');
        $planQty = $request->input('plan_qty', 1); // Get Plan Qty, default to 1

        // Start building the query using Laravel's Query Builder
        $query = DB::table('vc_master as T1')
            ->select(
                'T1.vc_code',
                'T1.option_code',
                'T1.model',
                'T1.color',
                'T4.material_number',
                'T4.material_name',
                'T4.unit',
                'T2.qty as Usage_Qty',
                'T3.qty as WH_Qty',
                'T3.line_side_qty',
                'T3.cy_qty',
                'T2.id as bom_id' // Added bom_id to uniquely identify the row for updates
            )
            ->leftJoin('bom as T2', 'T1.id', '=', 'T2.vc_code_id')
            ->leftJoin('warehouse_stock as T3', 'T2.material_id', '=', 'T3.material_id')
            ->leftJoin('material as T4', 'T2.material_id', '=', 'T4.id');

        // Apply the filter if a vc_code is provided
        if (!empty($vcCode)) {
            $query->where('T1.vc_code', 'LIKE', '%' . $vcCode . '%');
        }

        // Paginate the results
        $data = $query->paginate(20)->appends($request->except('page'));

        // Return the view with the data
        return view('monitoring-plan.index', compact('data', 'vcCode', 'planQty'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'bom_id' => 'required|integer|exists:bom,id',
            'usage_qty' => 'required|numeric|min:0',
        ]);

        try {
            DB::table('bom')
                ->where('id', $request->bom_id)
                ->update(['qty' => $request->usage_qty]);

            return response()->json(['success' => true, 'message' => 'Usage Qty updated successfully.']);
        } catch (Exception $e) {
            Log::error('Error updating Usage Qty: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update Usage Qty.'], 500);
        }
    }

    public function exportCsv(Request $request)
    {
        $vcCode = $request->input('vc_code');
        $planQty = $request->input('plan_qty', 1);

        $query = DB::table('vc_master AS T1')
            ->select(
                'T1.vc_code', 'T1.option_code', 'T1.model', 'T1.color',
                'T4.material_number', 'T4.material_name', 'T4.unit',
                'T2.qty AS Usage_Qty',
                'T3.qty AS WH_Qty', 'T3.line_side_qty', 'T3.cy_qty'
            )
            ->leftJoin('bom AS T2', 'T1.id', '=', 'T2.vc_code_id')
            ->leftJoin('warehouse_stock AS T3', 'T2.material_id', '=', 'T3.material_id')
            ->leftJoin('material AS T4', 'T2.material_id', '=', 'T4.id')
            ->whereNotNull('T2.id');

        if ($vcCode) {
            $query->where('T1.vc_code', 'LIKE', '%' . $vcCode . '%');
        }

        $data = $query->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="monitoring_plan_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function() use ($data, $planQty) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM to make it compatible with Excel
            fputs($file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));

            // Add Header Row
            fputcsv($file, [
                'VC Code', 'Option Code', 'Model', 'Color', 'Material Number', 'Material Name',
                'Unit', 'BOM Qty / Unit', 'Total Usage Qty', 'WH Qty', 'Line Side Qty',
                'CY Qty', 'Balance', 'Status',
            ]);

            // Add Data Rows
            foreach ($data as $item) {
                $totalUsage = ($item->Usage_Qty ?? 0) * $planQty;
                $totalStock = ($item->WH_Qty ?? 0) + ($item->line_side_qty ?? 0) + ($item->cy_qty ?? 0);
                $balance = $totalStock - $totalUsage;
                $status = ($balance >= 0) ? 'OK' : 'Not Enough';

                fputcsv($file, [
                    $item->vc_code,
                    $item->option_code,
                    $item->model,
                    $item->color,
                    $item->material_number,
                    $item->material_name,
                    $item->unit,
                    number_format($item->Usage_Qty ?? 0, 3, '.', ''),
                    number_format($totalUsage, 3, '.', ''),
                    number_format($item->WH_Qty ?? 0, 3, '.', ''),
                    number_format($item->line_side_qty ?? 0, 3, '.', ''),
                    number_format($item->cy_qty ?? 0, 3, '.', ''),
                    number_format($balance, 3, '.', ''),
                    $status,
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}