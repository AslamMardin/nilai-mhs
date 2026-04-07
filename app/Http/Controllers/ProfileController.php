<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
{
    return view('profile.edit', [
        'user' => auth()->user()
    ]);
}

public function update(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'namalengkap' => 'nullable|string|max:255',
        'email' => 'required|email'
    ]);
    // dd($request->all());
    $user = auth()->user();
    $user->update($request->only('name','namalengkap','email'));

    return back()->with('success', 'Profil berhasil diperbarui');
}

  

public function editPassword()
{
    return view('profile.password');
}

public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'password' => 'required|min:6|confirmed',
    ]);

    $user = auth()->user();

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Password lama salah']);
    }

    $user->update([
        'password' => Hash::make($request->password)
    ]);

    return back()->with('success', 'Password berhasil diganti');
}
}