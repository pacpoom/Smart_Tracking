<?php

// 1. แก้ไข use statement ให้ชี้ไปที่ Controller ที่เราสร้างขึ้น
use App\Http\Controllers\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

// กลุ่ม Route สำหรับผู้ที่ยังไม่ได้ Login (Guest)
Route::middleware('guest')->group(function () {
    // Route สำหรับแสดงหน้าฟอร์ม Login
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    // Route สำหรับรับข้อมูลจากฟอร์ม Login
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    /*
    |--------------------------------------------------------------------------
    | หมายเหตุ: Route อื่นๆ เช่น Register, Forgot Password ถูกลบออกไปก่อน
    | เพราะเรายังไม่ได้สร้าง Controller และ View สำหรับส่วนเหล่านั้น
    | เพื่อป้องกันไม่ให้โปรแกรมเกิด Error ครับ
    |--------------------------------------------------------------------------
    */
});

// กลุ่ม Route สำหรับผู้ที่ Login แล้ว (Authenticated)
Route::middleware('auth')->group(function () {
    // Route สำหรับการ Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});