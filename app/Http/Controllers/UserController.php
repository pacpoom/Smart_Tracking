<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    function __construct()
    {
        // กำหนดสิทธิ์ในการเข้าถึงแต่ละฟังก์ชัน
        // $this->middleware('permission:view users|manage users', ['only' => ['index']]);
        // $this->middleware('permission:manage users', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }

    /**
     * แสดงหน้า User Management พร้อมแบ่งหน้า
     */
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * แสดงฟอร์มสำหรับสร้าง User ใหม่
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('users.create', compact('roles'));
    }

    /**
     * บันทึก User ใหม่ลงฐานข้อมูล
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roles' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->roles);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * แสดงฟอร์มสำหรับแก้ไขข้อมูล User
     */
    public function edit(User $user)
    {
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name')->first(); // ดึง Role แรกที่ User มี

        return view('users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * อัปเดตข้อมูล User
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|confirmed|min:8',
            'roles' => 'required'
        ]);

        $input = $request->except(['password']);
        if ($request->filled('password')) {
            $input['password'] = Hash::make($request->password);
        }

        $user->update($input);
        $user->syncRoles($request->roles);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * ลบ User ออกจากระบบ
     */
    public function destroy(User $user)
    {
        // ป้องกันการลบ user ที่กำลัง login อยู่
        if ($user->id == auth()->id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete yourself.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id',
        ]);

        // ป้องกันการลบ user ที่กำลัง login อยู่
        $idsToDelete = array_diff($request->ids, [auth()->id()]);

        if (count($idsToDelete) > 0) {
            User::whereIn('id', $idsToDelete)->delete();
        }

        $message = 'Selected users have been deleted successfully.';
        if (count($request->ids) !== count($idsToDelete)) {
            $message .= ' You cannot delete yourself.';
        }

        return redirect()->route('users.index')->with('success', $message);
    }
}
