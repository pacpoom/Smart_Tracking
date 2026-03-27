<?php

namespace App\Http\Controllers;

use App\Models\ContainerOrderPlan;
use App\Models\ContainerPullingPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
// --- เพิ่มส่วนนี้ ---
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ContainerPullingPlanTemplateExport;
use App\Imports\ContainerPullingPlanImport;

// --- สิ้นสุดส่วนที่เพิ่ม ---

class ContainerPullingPlanController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:view pulling plans|create pulling plans|edit pulling plans|delete pulling plans', ['only' => ['index']]);
        // $this->middleware('permission:create pulling plans', ['only' => ['create', 'store']]);
        // $this->middleware('permission:edit pulling plans', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:delete pulling plans', ['only' => ['destroy', 'bulkDestroy']]);
    }

    public function index(Request $request)
    {
        $query = ContainerPullingPlan::with(['containerOrderPlan.container', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('pulling_plan_no', 'like', '%' . $search . '%')
                  ->orWhereHas('containerOrderPlan.container', function ($subQ) use ($search) {
                      $subQ->where('container_no', 'like', '%' . $search . '%');
                  });
            });
        }

        $pullingPlans = $query->latest()->paginate(10);
        return view('container-pulling-plans.index', compact('pullingPlans'));
    }

    public function create()
    {
        return view('container-pulling-plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'container_order_plan_id' => 'required|exists:container_order_plans,id',
            'pulling_date' => 'required|date',
            'destination' => 'nullable|string|max:255',
            'plan_type' => 'required|in:All,Pull',
        ]);

        $data = $request->all();
        $data['pulling_plan_no'] = ContainerPullingPlan::generatePullingPlanNumber();
        $data['user_id'] = Auth::id();
        $data['status'] = 1; // Planned

        // Generate pulling_order for the given date
        $lastOrder = ContainerPullingPlan::where('pulling_date', $request->pulling_date)->max('pulling_order');
        $data['pulling_order'] = $lastOrder + 1;

        ContainerPullingPlan::create($data);
        return redirect()->route('container-pulling-plans.index')->with('success', 'Pulling plan created successfully.');
    }

    public function edit(ContainerPullingPlan $containerPullingPlan)
    {
        return view('container-pulling-plans.edit', compact('containerPullingPlan'));
    }

    public function update(Request $request, ContainerPullingPlan $containerPullingPlan)
    {
        $request->validate([
            'container_order_plan_id' => 'required|exists:container_order_plans,id',
            'pulling_date' => 'required|date',
            'destination' => 'nullable|string|max:255',
            'status' => 'required|integer|in:1,2,3',
            'pulling_order' => 'required|integer|min:1',
            'plan_type' => 'required|in:All,Pull',
        ]);
        
        if ($containerPullingPlan->pulling_date->format('Y-m-d') != $request->pulling_date) {
            return back()->with('error', 'Changing the date and order at the same time is not supported. Please change them separately.');
        }

        DB::transaction(function () use ($request, $containerPullingPlan) {
            $newOrder = $request->pulling_order;
            $oldOrder = $containerPullingPlan->pulling_order;
            $pullingDate = $request->pulling_date;

            // Only perform swap logic if the order number has actually changed.
            if ($newOrder != $oldOrder) {
                // Find the plan that currently occupies the target order number.
                $otherPlan = ContainerPullingPlan::where('pulling_date', $pullingDate)
                                                 ->where('pulling_order', $newOrder)
                                                 ->first();

                // To prevent unique constraint violation, we first set the current plan's order to a temporary null value.
                $containerPullingPlan->update(['pulling_order' => null]);

                // If another plan exists at the target spot, move it to the old spot of the current plan.
                if ($otherPlan) {
                    $otherPlan->update(['pulling_order' => $oldOrder]);
                }
            }
            
            // Now, update the current plan with all the new data.
            $containerPullingPlan->update($request->all());
        });

        return redirect()->route('container-pulling-plans.index')->with('success', 'Pulling plan updated successfully.');
    }

    public function destroy(ContainerPullingPlan $containerPullingPlan)
    {
        $containerPullingPlan->delete();
        return redirect()->route('container-pulling-plans.index')->with('success', 'Pulling plan deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:container_pulling_plans,id',
        ]);
        ContainerPullingPlan::whereIn('id', $request->ids)->delete();
        return redirect()->route('container-pulling-plans.index')->with('success', 'Selected pulling plans have been deleted successfully.');
    }

        /**
     * Generate a PDF report for a specific pulling date.
     */
    public function printReport(Request $request)
    {
        $request->validate([
            'pulling_date' => 'required|date',
            'shop' => 'nullable|string|in:SKD,MOQ,KD,BA,EA', // Added shop validation
        ]);

        $pullingDate = $request->pulling_date;
        $shop = $request->shop; // Get shop from request

        $query = ContainerPullingPlan::with(['containerOrderPlan.container', 'containerOrderPlan.containerStock.yardLocation'])
            ->whereDate('pulling_date', $pullingDate);

        // Filter by shop if it is provided
        $query->when($shop, function ($q) use ($shop) {
            return $q->where('shop', $shop);
        });

        $plans = $query->orderBy('pulling_order', 'asc')->get();

        if ($plans->isEmpty()) {
            return back()->with('error', 'No pulling plans found for the selected date' . ($shop ? ' and shop' : '') . '.');
        }

        // Pass 'shop' variable to the PDF view
        $pdf = Pdf::loadView('container-pulling-plans.report_pdf', compact('plans', 'pullingDate', 'shop'));
        
        // Add shop to the PDF filename if it exists
        $filename = 'pulling_report_' . $pullingDate . ($shop ? '_' . $shop : '') . '.pdf';
        return $pdf->stream($filename);
    }

    public function show()
    {
        return redirect()->route('container-pulling-plans.index')->with('error', '555');
    }

    // --- เพิ่ม function ใหม่ 2 function นี้ ---

    /**
     * Download the Excel template for importing pulling plans.
     */
    public function downloadTemplate()
    {
        return Excel::download(new ContainerPullingPlanTemplateExport, 'container_pulling_plan_template.xlsx');
    }

    /**
     * Import pulling plans from an Excel file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new ContainerPullingPlanImport(Auth::id()), $request->file('import_file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             $errorMessages = [];
             foreach ($failures as $failure) {
                 // Sửa lỗi: Đảm bảo $failure->errors() là mảng trước khi implode
                 $errors = is_array($failure->errors()) ? implode(', ', $failure->errors()) : 'Unknown error';
                 // แก้ไข: ลบ 'GA' ที่ไม่จำเป็นออก
                 $errorMessages[] = 'Row ' . $failure->row() . ': ' . $errors;
             }
             return back()->with('error', 'Import failed. Please check the following errors: ' . implode(' | ', $errorMessages));
        } catch (\Exception $e) {
            return back()->with('error', 'An unexpected error occurred during import: ' . $e->getMessage());
        }

        return redirect()->route('container-pulling-plans.index')->with('success', 'Container pulling plans imported successfully.');
    }
    // --- สิ้นสุดส่วนที่เพิ่ม ---
}