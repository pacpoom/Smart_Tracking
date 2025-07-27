<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\DB; // 1. เพิ่ม use statement นี้

class AuthenticatedSessionController extends Controller
{
    /**
     * แสดงหน้าฟอร์ม Login
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * จัดการกับการยืนยันตัวตน
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        // --- เพิ่มส่วนนี้เข้ามา ---
        // 2. บันทึก Session ID ใหม่ลงในฐานข้อมูล
        $user = Auth::user();
        DB::table('users')
            ->where('id', $user->id)
            ->update(['session_id' => $request->session()->getId()]);
        // --- สิ้นสุดส่วนที่เพิ่ม ---

        return redirect()->intended('/dashboard');
    }

    /**
     * ทำการ Logout
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
