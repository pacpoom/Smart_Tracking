<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:view permissions|create permissions|edit permissions|delete permissions', ['only' => ['index','store']]);
        // $this->middleware('permission:create permissions', ['only' => ['create','store']]);
        // $this->middleware('permission:edit permissions', ['only' => ['edit','update']]);
        // $this->middleware('permission:delete permissions', ['only' => ['destroy']]);
    }

    public function index()
    {
        // เปลี่ยนจาก get() เป็น paginate(10)
        $permissions = Permission::orderBy('name')->paginate(10);
        return view('permissions.index', compact('permissions'));
    }

    // ... โค้ดส่วนที่เหลือเหมือนเดิม ...

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:permissions,name']);
        Permission::create(['name' => $request->name]);
        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate(['name' => 'required|string|unique:permissions,name,'.$permission->id]);
        $permission->update(['name' => $request->name]);
        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:permissions,id',
        ]);

        Permission::whereIn('id', $request->ids)->delete();

        return redirect()->route('permissions.index')->with('success', 'Selected permissions have been deleted successfully.');
    }
}
