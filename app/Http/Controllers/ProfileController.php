<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'max:1024'], // 1MB Max
        ]);

        $user = $request->user();

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $path = $request->file('photo')->store('profile-photos', 'public');

        $user->forceFill([
            'profile_photo_path' => $path,
        ])->save();

        return back()->with('status', 'profile-photo-updated');
    }

    /**
     * Serve the user's profile photo.
     */
    public function showPhoto()
    {
        $user = Auth::user();

        if (!$user->profile_photo_path || !Storage::disk('public')->exists($user->profile_photo_path)) {
            // ถ้าไม่มีรูป ให้ Redirect ไปที่รูปเริ่มต้น
            return redirect($user->defaultProfilePhotoUrl());
        }

        // ส่งไฟล์รูปภาพกลับไปให้เบราว์เซอร์โดยตรง
        return response()->file(storage_path('app/public/' . $user->profile_photo_path));
    }
}
