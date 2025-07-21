<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    function __construct()
    {
        //  $this->middleware('permission:view roles|create roles|edit roles|delete roles', ['only' => ['index','store']]);
        //  $this->middleware('permission:create roles', ['only' => ['create','store']]);
        //  $this->middleware('permission:edit roles', ['only' => ['edit','update']]);
        //  $this->middleware('permission:delete roles', ['only' => ['destroy']]);
    }

    public function index()
    {
        // เปลี่ยนจาก get() เป็น paginate(10)
        $roles = Role::with('permissions')->paginate(10);
        return view('roles.index', compact('roles'));
    }

    // ... โค้ดส่วนที่เหลือเหมือนเดิม ...
    
    public function create()
    {
        $permissions = Permission::get();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name'
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success','Role created successfully.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::get();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,'.$role->id
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success','Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success','Role deleted successfully');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:roles,id',
        ]);

        Role::whereIn('id', $request->ids)->delete();

        return redirect()->route('roles.index')->with('success', 'Selected roles have been deleted successfully.');
    }

}
