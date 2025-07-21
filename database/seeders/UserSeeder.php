<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // --- ส่วนที่แก้ไข ---
        // ค้นหา Role ที่ถูกสร้างไว้แล้วโดย PermissionSeeder
        $adminRole = Role::findByName('admin');
        $userRole = Role::findByName('user');

        // สร้าง Admin User (ถ้ายังไม่มี) แล้วกำหนด Role
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password')
            ]
        )->assignRole($adminRole);

        // สร้าง Regular User (ถ้ายังไม่มี) แล้วกำหนด Role
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password')
            ]
        )->assignRole($userRole);
    }
}