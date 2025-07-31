<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        Menu::truncate(); // ล้างข้อมูลเก่าก่อน

        Menu::create(['title' => 'Dashboard', 'icon' => 'dashboard', 'route' => 'dashboard', 'order' => 1]);
        Menu::create(['title' => 'Profile', 'icon' => 'person', 'route' => 'profile.edit', 'order' => 99]);

        $management = Menu::create(['title' => 'Management', 'icon' => 'settings', 'route' => null, 'order' => 2]);

         // --- เพิ่มเมนู Vendor Management ---
        $vendorManagement = Menu::create(['title' => 'Vendor Management', 'icon' => 'store', 'route' => null, 'order' => 2]);
        Menu::create(['title' => 'Vendor Master', 'icon' => 'contacts_product', 'route' => 'vendors.index', 'permission_name' => 'view vendors', 'parent_id' => $vendorManagement->id, 'order' => 1]);
        // --- สิ้นสุดส่วนที่เพิ่ม ---

        Menu::create(['title' => 'User Management', 'icon' => 'group', 'route' => 'users.index', 'permission_name' => 'view users', 'parent_id' => $management->id, 'order' => 1]);
        Menu::create(['title' => 'Role Management', 'icon' => 'admin_panel_settings', 'route' => 'roles.index', 'permission_name' => 'view roles', 'parent_id' => $management->id, 'order' => 2]);
        Menu::create(['title' => 'Permission Management', 'icon' => 'policy', 'route' => 'permissions.index', 'permission_name' => 'view permissions', 'parent_id' => $management->id, 'order' => 3]);
        Menu::create(['title' => 'Menu Management', 'icon' => 'menu', 'route' => 'menus.index', 'permission_name' => 'manage menus', 'parent_id' => $management->id, 'order' => 4]);

         // --- สร้างเมนูหลัก Master Data ---
        $masterData = Menu::create(['title' => 'Master Data', 'icon' => 'database', 'route' => null, 'order' => 2]);
        Menu::create(['title' => 'Part Master', 'icon' => 'category', 'route' => 'parts.index', 'permission_name' => 'view parts', 'parent_id' => $masterData->id, 'order' => 1]);

        // --- สร้างเมนูหลัก Operations ---
        $operations = Menu::create(['title' => 'Operations', 'icon' => 'list_alt', 'route' => null, 'order' => 2]);
        Menu::create(['title' => 'Part Request', 'icon' => 'add_shopping_cart', 'route' => 'part-requests.create', 'permission_name' => 'create part requests', 'parent_id' => $operations->id, 'order' => 1]);
        Menu::create(['title' => 'Request List', 'icon' => 'receipt_long', 'route' => 'part-requests.index', 'permission_name' => 'view all part requests', 'parent_id' => $operations->id, 'order' => 2]);

         // --- สร้างเมนูหลัก Inventory ---
        $inventory = Menu::create(['title' => 'Inventory', 'icon' => 'inventory_2', 'route' => null, 'order' => 3]);
        Menu::create(['title' => 'Stock Management', 'icon' => 'warehouse', 'route' => 'stocks.index', 'permission_name' => 'view stock', 'parent_id' => $inventory->id, 'order' => 1]);

       $containerYard = Menu::create(['title' => 'Container Yard', 'icon' => 'warehouse', 'route' => null, 'order' => 3]);
        Menu::create(['title' => 'Container Receive', 'icon' => 'login', 'route' => 'container-receive.create', 'permission_name' => 'receive containers', 'parent_id' => $containerYard->id, 'order' => 1]);
        Menu::create(['title' => 'Container Ship Out', 'icon' => 'logout', 'route' => 'container-ship-out.index', 'permission_name' => 'ship out containers', 'parent_id' => $containerYard->id, 'order' => 2]);
        Menu::create(['title' => 'Container Tacking', 'icon' => 'photo_camera', 'route' => 'container-tacking.create', 'permission_name' => 'tack container photos', 'parent_id' => $containerYard->id, 'order' => 3]);
        // เพิ่มเมนูนี้
        Menu::create(['title' => 'Container Return', 'icon' => 'assignment_return', 'route' => 'container-return.index', 'permission_name' => 'return containers', 'parent_id' => $containerYard->id, 'order' => 3]);
        Menu::create(['title' => 'Tacking List', 'icon' => 'list', 'route' => 'container-tacking.index', 'permission_name' => 'view container tackings', 'parent_id' => $containerYard->id, 'order' => 4]);
        Menu::create(['title' => 'Container Stock', 'icon' => 'apps', 'route' => 'container-stocks.index', 'permission_name' => 'view container stock', 'parent_id' => $containerYard->id, 'order' => 5]);
        Menu::create(['title' => 'Change Location', 'icon' => 'multiple_stop', 'route' => 'container-change-location.index', 'permission_name' => 'change container location', 'parent_id' => $containerYard->id, 'order' => 6]);
        Menu::create(['title' => 'Transaction Log', 'icon' => 'history', 'route' => 'container-transactions.index', 'permission_name' => 'view container transactions', 'parent_id' => $containerYard->id, 'order' => 7]);
        Menu::create(['title' => 'Location Yard Master', 'icon' => 'pin_drop', 'route' => 'yard-locations.index', 'permission_name' => 'view yard locations', 'parent_id' => $containerYard->id, 'order' => 8]);
        Menu::create(['title' => 'Container Master', 'icon' => 'view_in_ar', 'route' => 'containers.index', 'permission_name' => 'view containers', 'parent_id' => $containerYard->id, 'order' => 9]);
        Menu::create(['title' => 'Container Order Plan', 'icon' => 'calendar_month', 'route' => 'container-order-plans.index', 'permission_name' => 'view container plans', 'parent_id' => $containerYard->id, 'order' => 10]);
        Menu::create(['title' => 'Display Dashboard', 'icon' => 'tv', 'route' => 'display.dashboard', 'permission_name' => 'view display dashboard', 'parent_id' => $management->id, 'order' => 5]);
        Menu::create(['title' => 'Container Pulling Plan', 'icon' => 'move_up', 'route' => 'container-pulling-plans.index', 'permission_name' => 'view pulling plans', 'parent_id' => $operations->id, 'order' => 3]);

    
    }
}
