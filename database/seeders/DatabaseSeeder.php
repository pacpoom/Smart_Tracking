<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // เรียกใช้งาน Seeder ตามลำดับที่ถูกต้อง
        // 1. สร้าง Permissions และ Roles ก่อน
        // 2. สร้าง Users และกำหนด Roles ที่สร้างไว้แล้ว
         $this->call([
            PermissionSeeder::class,
            UserSeeder::class,
            MenuSeeder::class,
            StockSeeder::class, // ตรวจสอบว่ามี StockSeeder ด้วย
        ]);
    }
}