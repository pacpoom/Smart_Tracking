<?php

namespace App\Http\Controllers;

use App\Models\Container;
use App\Models\ContainerOrderPlan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\ContainerOrderPlanExport;
use App\Imports\ContainerOrderPlanImport;
use App\Exports\ContainerOrderPlanTemplateExport;
use Maatwebsite\Excel\Facades\Excel; // 1. เพิ่ม use statement นี้

class ContainerOrderPlanController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:view container plans|create container plans|edit container plans|delete container plans', ['only' => ['index', 'export']]);
        // $this->middleware('permission:create container plans', ['only' => ['create', 'store', 'downloadTemplate', 'import']]);
        // $this->middleware('permission:edit container plans', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:delete container plans', ['only' => ['destroy', 'bulkDestroy']]);
    }

    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $query = ContainerOrderPlan::with('container');
        $query->whereBetween('eta_date', [$startDate, $endDate]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('house_bl', 'like', '%' . $search . '%')
                  ->orWhere('plan_no', 'like', '%' . $search . '%')
                  ->orWhereHas('container', function ($subQ) use ($search) {
                      $subQ->where('container_no', 'like', '%' . $search . '%');
                  });
            });
        }

        $plans = $query->latest()->paginate(10);
        
        return view('container-order-plans.index', compact('plans', 'startDate', 'endDate'));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $query = ContainerOrderPlan::with('container');
        $query->whereBetween('eta_date', [$startDate, $endDate]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('house_bl', 'like', '%' . $search . '%')
                  ->orWhere('plan_no', 'like', '%' . $search . '%')
                  ->orWhereHas('container', function ($subQ) use ($search) {
                      $subQ->where('container_no', 'like', '%' . $search . '%');
                  });
            });
        }

        return Excel::download(new ContainerOrderPlanExport($query), 'container_order_plans.xlsx');
    }

    public function create()
    {
        return view('container-order-plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'container_no' => 'required|string|max:255',
            'eta_date' => 'nullable|date',
            'checkin_date' => 'nullable|date|after_or_equal:eta_date',
        ]);

        $container = Container::firstOrCreate(
            ['container_no' => $request->container_no]
        );

        $data = $request->except('container_no');
        $data['container_id'] = $container->id;
        $data['status'] = 1;
        $data['plan_no'] = ContainerOrderPlan::generatePlanNumber();

        ContainerOrderPlan::create($data);
        return redirect()->route('container-order-plans.index')->with('success', 'Container order plan created successfully.');
    }

    public function edit(ContainerOrderPlan $containerOrderPlan)
    {
        return view('container-order-plans.edit', compact('containerOrderPlan'));
    }

    public function update(Request $request, ContainerOrderPlan $containerOrderPlan)
    {
        $request->validate([
            'container_no' => 'required|string|max:255',
            'eta_date' => 'nullable|date',
            'checkin_date' => 'nullable|date|after_or_equal:eta_date',
        ]);
        
        $container = Container::firstOrCreate(
            ['container_no' => $request->container_no]
        );
        
        $data = $request->except(['status', 'container_no']);
        $data['container_id'] = $container->id;
        
        $containerOrderPlan->update($data);
        return redirect()->route('container-order-plans.index')->with('success', 'Container order plan updated successfully.');
    }

    public function destroy(ContainerOrderPlan $containerOrderPlan)
    {
        $containerOrderPlan->delete();
        return redirect()->route('container-order-plans.index')->with('success', 'Container order plan deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:container_order_plans,id',
        ]);
        ContainerOrderPlan::whereIn('id', $request->ids)->delete();
        return redirect()->route('container-order-plans.index')->with('success', 'Selected plans have been deleted successfully.');
    }
    
    public function downloadTemplate()
    {
        return Excel::download(new ContainerOrderPlanTemplateExport, 'container_order_plan_template.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new ContainerOrderPlanImport, $request->file('import_file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             $errorMessages = [];
             foreach ($failures as $failure) {
                 $errorMessages[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
             }
             return back()->with('error', 'Import failed. Please check the following errors: ' . implode(' | ', $errorMessages));
        }

        return redirect()->route('container-order-plans.index')->with('success', 'Container order plans imported successfully.');
    }
}
