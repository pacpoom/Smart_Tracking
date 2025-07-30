<?php

namespace App\Http\Controllers;

use App\Models\ContainerOrderPlan;
use App\Models\ContainerPullingPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'plan_type' => 'required|in:All,Pull', // เพิ่ม validation
        ]);

        $data = $request->all();
        $data['pulling_plan_no'] = ContainerPullingPlan::generatePullingPlanNumber();
        $data['user_id'] = Auth::id();
        $data['status'] = 1; // Planned

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
            'pulling_order' => 'required|integer',
            'plan_type' => 'required|in:all,pull', // เพิ่ม validation
        ]);
        
        $data = $request->all();

        if ($containerPullingPlan->pulling_date->format('Y-m-d') != $request->pulling_date) {
            $lastOrder = ContainerPullingPlan::where('pulling_date', $request->pulling_date)->max('pulling_order');
            $data['pulling_order'] = $lastOrder + 1;
        }
        
        $containerPullingPlan->update($data);
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
}
