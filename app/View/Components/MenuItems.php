<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class MenuItems extends Component
{
    public $menu = [];

    public function __construct()
    {
        $this->menu = $this->getAuthorizedMenus();
    }

    private function getAuthorizedMenus()
    {
        $user = Auth::user();
        if (!$user) {
            return collect();
        }

        // Fetch top-level menus with all their descendants recursively
        $menus = Menu::whereNull('parent_id')->with('childrenRecursive')->orderBy('order')->get();

        // Filter menus recursively and set active state
        return $this->processMenus($menus, $user);
    }

    private function processMenus($menus, $user)
    {
        return $menus->filter(function ($menu) use ($user) {
            // Filter children first
            if ($menu->childrenRecursive->isNotEmpty()) {
                $menu->children = $this->processMenus($menu->childrenRecursive, $user);
            } else {
                $menu->children = collect();
            }

            // Check permission for the current menu item
            $hasPermission = empty($menu->permission_name) || $user->can($menu->permission_name);

            if (!$hasPermission) {
                return false;
            }

            // A parent menu should be visible if it has a direct route or if it has visible children
            if (is_null($menu->route) && $menu->children->isEmpty()) {
                return false;
            }

            // Set active state
            $isActiveParent = $menu->route && \Illuminate\Support\Facades\Route::has($menu->route) && request()->routeIs($menu->route);
            $hasActiveChild = $menu->children->contains('isActive', true);
            $menu->isActive = $isActiveParent || $hasActiveChild;

            return true;
        });
    }

    public function render(): View|Closure|string
    {
        return view('components.menu-items');
    }
}
