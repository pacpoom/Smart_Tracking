<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductionPlan;
use App\Models\WarehouseStock;
use Illuminate\Support\Facades\DB;

class ProductionPlanMonitorController extends Controller
{
    /**
     * Display the real-time monitoring view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('production-plans.monitor');
    }

    /**
     * Fetch real-time data for the monitoring dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        // Get plans for today or in the future that are not yet completed
        $plans = ProductionPlan::with('vcMaster')
            ->where('production_date', '>=', now()->format('Y-m-d'))
            ->where('status', '!=', 'completed')
            ->orderBy('production_date', 'asc')
            ->get();

        $monitoringData = [];

        foreach ($plans as $plan) {
            $details = $plan->details;
            $materialShortages = [];
            $isShortage = false;

            if (is_array($details)) {
                foreach ($details as $detail) {
                    $materialId = $detail['material_id'] ?? null;
                    if (!$materialId) {
                        continue;
                    }

                    // Get the current stock for the material
                    $stock = WarehouseStock::where('material_id', $materialId)->first();
                    $whQty = $stock->qty ?? 0;
                    $cyQty = $stock->cy_qty ?? 0;
                    $lineSideQty = $stock->line_side_qty ?? 0;
                    $currentStock = $whQty + $cyQty + $lineSideQty;
                    $requiredQty = $detail['required_qty'] ?? 0;
                    $balance = $currentStock - $requiredQty;

                    if ($balance < 0) {
                        $isShortage = true;
                        $materialShortages[] = [
                            'material_number' => $detail['material_number'],
                            'material_name' => $detail['material_name'],
                            'required_qty' => round($requiredQty, 3),
                            'current_stock' => round($currentStock, 3),
                            'wh_qty' => round($whQty, 3),
                            'cy_qty' => round($cyQty, 3),
                            'line_side_qty' => round($lineSideQty, 3),
                            'balance' => round($balance, 3),
                        ];
                    }
                }
            }

            $monitoringData[] = [
                'plan_no' => $plan->plan_no,
                'vc_code' => $plan->vcMaster->vc_code,
                'model' => $plan->vcMaster->model,
                'production_order' => $plan->production_order,
                'production_date' => $plan->production_date,
                'status' => $plan->status,
                'is_shortage' => $isShortage,
                'shortages' => $materialShortages,
            ];
        }

        return response()->json($monitoringData);
    }
}

