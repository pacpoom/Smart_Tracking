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
        // เพิ่มเมนูย่อย Location Yard Master
        Menu::create(['title' => 'Location Yard Master', 'icon' => 'pin_drop', 'route' => 'yard-locations.index', 'permission_name' => 'view yard locations', 'parent_id' => $containerYard->id, 'order' => 1]);
        Menu::create(['title' => 'Container Mgt.', 'icon' => 'view_in_ar', 'route' => 'containers.index', 'permission_name' => 'view containers', 'parent_id' => $containerYard->id, 'order' => 2]);


    }
}
