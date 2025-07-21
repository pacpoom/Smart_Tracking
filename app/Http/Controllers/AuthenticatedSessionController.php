<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest; // เราจะสร้างไฟล์นี้ในขั้นตอนถัดไป

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