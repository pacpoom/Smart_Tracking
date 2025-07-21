<?php

namespace App\Http\Controllers;

use App\Models\Container;
use Illuminate\Http\Request;

class ContainerController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:view containers|create containers|edit containers|delete containers', ['only' => ['index']]);
        // $this->middleware('permission:create containers', ['only' => ['create', 'store']]);
        // $this->middleware('permission:edit containers', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:delete containers', ['only' => ['destroy', 'bulkDestroy']]);
    }

    public function index(Request $request)
    {
        $query = Container::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('container_no', 'like', '%' . $search . '%')
                  ->orWhere('size', 'like', '%' . $search . '%');
        }

        $containers = $query->paginate(10);
        return view('containers.index', compact('containers'));
    }

    public function create()
    {
        return view('containers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'container_no' => 'required|string|unique:containers,container_no',
            'size' => 'nullable|string|max:255',
            'agent' => 'nullable|string|max:255', // เพิ่ม validation
        ]);
        Container::create($request->all());
        return redirect()->route('containers.index')->with('success', 'Container created successfully.');
    }

    public function edit(Container $container)
    {
        return view('containers.edit', compact('container'));
    }

    public function update(Request $request, Container $container)
    {
        $request->validate([
            'container_no' => 'required|string|unique:containers,container_no,'.$container->id,
            'size' => 'nullable|string|max:255',
            'agent' => 'nullable|string|max:255', // เพิ่ม validation
        ]);
        $container->update($request->all());
        return redirect()->route('containers.index')->with('success', 'Container updated successfully.');
    }

    public function destroy(Container $container)
    {
        $container->delete();
        return redirect()->route('containers.index')->with('success', 'Container deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:containers,id',
        ]);
        Container::whereIn('id', $request->ids)->delete();
        return redirect()->route('containers.index')->with('success', 'Selected containers have been deleted successfully.');
    }
}
