<?php

namespace App\Http\Controllers;

use App\Models\ProductionPlan;
use App\Models\VcMaster;
use App\Models\Bom;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductionPlanExport;

class ProductionPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProductionPlan::with(['user', 'vcMaster'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('plan_no', 'like', "%{$search}%")
                ->orWhereHas('vcMaster', function ($q) use ($search) {
                    $q->where('vc_code', 'like', "%{$search}%");
                });
        }

        $productionPlans = $query->paginate(15)->withQueryString();

        return view('production-plans.index', compact('productionPlans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vc_masters = VcMaster::orderBy('vc_code')->get();
        return view('production-plans.create', compact('vc_masters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        set_time_limit(300);

        $request->validate([
            'vc_master_id' => 'required|exists:vc_master,id',
            'production_order' => 'required|integer|min:1',
            'production_date' => 'required|date',
        ]);

        $vcMaster = VcMaster::findOrFail($request->vc_master_id);
        $productionOrder = $request->production_order;

        $boms = Bom::where('vc_code_id', $vcMaster->id)->with('childMaterial')->get();

        $details = $boms->map(function ($bom) use ($productionOrder) {
            if (!$bom->childMaterial) {
                return null;
            }
            $requiredQty = $bom->qty * $productionOrder;
            $stock = WarehouseStock::where('material_id', $bom->material_id)->first();
            $stockQty = $stock ? $stock->qty : 0;
            $lineSideQty = $stock ? $stock->line_side_qty : 0;
            $cyQty = $stock ? $stock->cy_qty : 0;

            return [
                'material_id' => $bom->material_id,
                'material_number' => $bom->childMaterial->material_number,
                'material_name' => $bom->childMaterial->material_name,
                'bom_qty' => $bom->qty,
                'required_qty' => $requiredQty,
                'stock_qty' => $stockQty,
                'line_side_qty' => $lineSideQty,
                'cy_qty' => $cyQty,
            ];
        })->filter()->values()->all();

        ProductionPlan::create([
            'plan_no' => ProductionPlan::generatePlanNumber(),
            'vc_master_id' => $request->vc_master_id,
            'production_order' => $productionOrder,
            'production_date' => $request->production_date,
            'details' => $details, // Store as an array, Eloquent will handle JSON encoding
            'status' => 'planned',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('production-plans.index')->with('success', 'Production plan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductionPlan $productionPlan)
    {
        $productionPlan->load(['user', 'vcMaster']);
        
        // Thanks to the 'details' => 'array' cast in the model,
        // $productionPlan->details is already a PHP array. No need to json_decode.
        $details = $productionPlan->details;

        return view('production-plans.show', compact('productionPlan', 'details'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductionPlan $productionPlan)
    {
        $vcMasters = VcMaster::orderBy('vc_code')->get();
        return view('production-plans.edit', compact('productionPlan', 'vcMasters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductionPlan $productionPlan)
    {
        $request->validate([
            'vc_master_id' => 'required|exists:vc_master,id',
            'production_order' => 'required|integer|min:1',
            'production_date' => 'required|date',
        ]);

        $vcMaster = VcMaster::findOrFail($request->vc_master_id);
        $productionOrder = $request->production_order;

        $boms = Bom::where('vc_code_id', $vcMaster->id)->with('childMaterial')->get();

        $details = $boms->map(function ($bom) use ($productionOrder) {
            if (!$bom->childMaterial) {
                return null;
            }
            $requiredQty = $bom->qty * $productionOrder;
            $stock = WarehouseStock::where('material_id', $bom->material_id)->first();
            $stockQty = $stock ? $stock->qty : 0;
            $cyQty = $stock ? $stock->cy_qty : 0;

            return [
                'material_id' => $bom->material_id,
                'material_number' => $bom->childMaterial->material_number,
                'material_name' => $bom->childMaterial->material_name,
                'bom_qty' => $bom->qty,
                'required_qty' => $requiredQty,
                'stock_qty' => $stockQty,
                'line_side_qty' => $lineSideQty,
                'cy_qty' => $cyQty,
            ];
        })->filter()->values()->all();

        $productionPlan->update([
            'vc_master_id' => $request->vc_master_id,
            'production_order' => $productionOrder,
            'production_date' => $request->production_date,
            'details' => $details, // Store as an array, Eloquent will handle JSON encoding
        ]);

        return redirect()->route('production-plans.index')->with('success', 'Production plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductionPlan $productionPlan)
    {
        $productionPlan->delete();
        return redirect()->route('production-plans.index')->with('success', 'Production plan deleted successfully.');
    }

    /**
     * Export production plans to CSV.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        $query = ProductionPlan::with(['user', 'vcMaster'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('plan_no', 'like', "%{$search}%")
                ->orWhereHas('vcMaster', function ($q) use ($search) {
                    $q->where('vc_code', 'like', "%{$search}%");
                });
        }

        return Excel::download(new ProductionPlanExport($query), 'production_plans.csv');
    }


    public function getBom(Request $request)
    {
        set_time_limit(300);

        $request->validate([
            'vc_code_id' => 'required|integer|exists:vc_master,id',
            'production_order' => 'required|integer|min:1',
        ]);

        $productionOrder = $request->production_order;

        $boms = Bom::where('vc_code_id', $request->vc_code_id)
            ->with(['childMaterial.primaryPfep']) // Eager load relations
            ->get();

        $details = $boms->map(function ($bom) use ($productionOrder) {
            if (!$bom->childMaterial) {
                return null;
            }

            $requiredQty = $bom->qty * $productionOrder;
            $stock = WarehouseStock::where('material_id', $bom->material_id)->first();
            $stockQty = $stock ? $stock->qty : 0;
            $cyQty = $stock ? $stock->cy_qty : 0;
            $lineSideQty = $stock ? $stock->line_side_qty : 0; // ดึงค่า line_side_qty
            $balance = ($stockQty + $cyQty + $lineSideQty) - $requiredQty; // เพิ่ม line_side_qty ในการคำนวณ

            return [
                'material_number' => $bom->childMaterial->material_number,
                'material_name' => $bom->childMaterial->material_name,
                'model' => $bom->childMaterial->primaryPfep->model ?? 'N/A', // Get model from primary PFEP
                'bom_qty' => (float) $bom->qty,
                'required_qty' => $requiredQty,
                'stock_qty' => $stockQty,
                'cy_qty' => $cyQty,
                'line_side_qty' => $lineSideQty, // เพิ่ม line_side_qty ในผลลัพธ์
                'balance' => $balance,
            ];
        })->filter();

        return response()->json($details->values());
    }
}