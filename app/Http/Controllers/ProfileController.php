<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function editPassword()
    {
        return view('profile.ganti-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => ['required'],
            'password_baru' => ['required', 'min:6', 'confirmed'],
        ],[
            'confirmed' => 'Confirmasi Password Baru tidak sama',
            'min' => 'Password baru minimal 6 Huruf'
        ]);

        $user = Auth::user();

        // Cek password lama
        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai!');
        }

        // Cegah password baru sama dengan lama
        if (Hash::check($request->password_baru, $user->password)) {
            return back()->with('error', 'Password baru tidak boleh sama dengan password lama!');
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password_baru)
        ]);

        // Optional: Logout paksa setelah ganti password
        Auth::logout();

        return redirect('/login')->with('success', 'Password berhasil diganti. Silakan login kembali.');
    }
}