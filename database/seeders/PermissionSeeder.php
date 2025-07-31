<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions for the application
        $permissions = [
            // User Management
            'view users',
            'manage users',

            // Role Management
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',

            // Permission Management
            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',

            // Menu Management
            'manage menus',

            // Vendor Management
            'view vendors',
            'create vendors',
            'edit vendors',
            'delete vendors',

            // Part Master
            'view parts',
            'create parts',
            'edit parts',
            'delete parts',

            // Part Request
            'create part requests',
            'view all part requests',
            'approve part requests',
            'view part request dashboard',

            // Stock Management
            'view stock',
            'adjust stock',
            'create stock',
            
            // Special Permissions
            'super_admin',

            // --- เพิ่มกลุ่มใหม่: Yard Locations ---
            'view yard locations',
            'create yard locations',
            'edit yard locations',
            'delete yard locations',

            'view containers',
            'create containers',
            'edit containers',
            'delete containers',

            // --- เพิ่มกลุ่มใหม่: Container Order Plans ---
            'view container plans',
            'create container plans',
            'edit container plans',
            'delete container plans',

            // --- เพิ่ม permission นี้ ---
            'receive containers',
            'view container stock',
            'export container stock',

            'view container transactions',
            'change container location',
            
            'ship out containers',
            'view yard dashboard',

            'tack container photos',
            'view container tackings',
            'view display dashboard',

            'add damage photos',
            'delete container tackings',
            'download tacking photos',

            'view pulling plans',
            'create pulling plans',
            'edit pulling plans',
            'delete pulling plans',
            'export container transactions',
            'return containers',
            
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create a user role and assign basic permissions
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->givePermissionTo([
            'create part requests',
        ]);

        // Create an admin role and assign all permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());
    }
}
