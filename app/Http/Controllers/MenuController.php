<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class MenuController extends Controller
{
    function __construct()
    {
        //$this->middleware('permission:manage menus', ['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy', 'bulkDestroy']]);
    }

    public function index()
    {
        // แก้ไข: เปลี่ยนจาก get() เป็น paginate(10)
        $menus = Menu::whereNull('parent_id')->orderBy('order')->paginate(10);
        return view('menus.index', compact('menus'));
    }

    public function create()
    {
        $permissions = Permission::pluck('name', 'name')->all();
        $parentMenus = Menu::all();
        return view('menus.create', compact('permissions', 'parentMenus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'required|integer',
        ]);

        Menu::create($request->all());

        return redirect()->route('menus.index')->with('success', 'Menu item created successfully.');
    }

    public function edit(Menu $menu)
    {
        $permissions = Permission::pluck('name', 'name')->all();
        $parentMenus = Menu::where('id', '!=', $menu->id)->get();
        return view('menus.edit', compact('menu', 'permissions', 'parentMenus'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'required|integer',
        ]);

        if ($request->parent_id == $menu->id) {
            return back()->withErrors(['parent_id' => 'A menu cannot be its own parent.'])->withInput();
        }

        $menu->update($request->all());

        return redirect()->route('menus.index')->with('success', 'Menu item updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'Menu item deleted successfully.');
    }

    /**
     * Remove multiple resources from storage.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:menus,id',
        ]);

        Menu::whereIn('id', $request->ids)->delete();

        return redirect()->route('menus.index')->with('success', 'Selected menu items have been deleted successfully.');
    }
}
