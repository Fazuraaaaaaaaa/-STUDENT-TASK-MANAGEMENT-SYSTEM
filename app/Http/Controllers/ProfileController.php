<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit() { return view('profile.edit', ['user' => auth()->user()]); }

    public function update(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:100', 'email' => 'required|email|max:255|unique:users,email,'.auth()->id()]);
        auth()->user()->update($data);
        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate(['current_password' => 'required', 'password' => ['required','confirmed',Password::defaults()]]);
        abort_unless(Hash::check($data['current_password'], auth()->user()->password), 422, 'Kata sandi saat ini salah.');
        auth()->user()->update(['password' => $data['password']]);
        return back()->with('success', 'Kata sandi berhasil diperbarui.');
    }
}
