<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserProfilController extends Controller
{
    // Menampilkan halaman profil
    public function show()
    {
        $userId = Auth::id();
        $user = User::with(['jabatan', 'role'])->find($userId);

        return Inertia::render('UserProfil/Show', [
            'user' => $user,
            'success' => session('success'),
        ]);
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $userId = Auth::id();
        $user = User::find($userId);
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui!');
    }
}
