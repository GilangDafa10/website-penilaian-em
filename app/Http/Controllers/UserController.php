<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\Role;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['jabatan', 'role'])->paginate(20);

        return Inertia::render('UserControl/Index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        $jabatans = Jabatan::all();
        $roles = Role::all();

        return Inertia::render('UserControl/Create', [
            'jabatans' => $jabatans,
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'string|unique:users,id_user',
            'nim' => 'required|string|unique:users,nim',
            'nama' => 'required|string',
            'password' => 'required|string|min:6',
            'role_id' => 'required|integer|exists:roles,id',
            'id_jabatan' => 'required|integer|exists:jabatans,id_jabatan',
        ]);

        User::create([
            'id_user' => $request->id_user,
            'nim' => $request->nim,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
            'id_jabatan' => $request->id_jabatan,
        ]);

        return redirect()->route('userControl.index')
            ->with('success', 'User created successfully.');
    }

    public function show(string $id_user)
    {
        $user = User::with(['jabatan', 'role'])->findOrFail($id_user);

        return Inertia::render('UserControl/Show', [
            'user' => $user,
        ]);
    }

    public function edit(string $id_user)
    {
        $user = User::findOrFail($id_user);
        $jabatans = Jabatan::all();
        $roles = Role::all();

        return Inertia::render('UserControl/Edit', [
            'user' => $user,
            'jabatans' => $jabatans,
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, string $id_user)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|unique:users,nim,' . $id_user . ',id_user',
            'password' => 'nullable|string|min:6',
            'id_jabatan' => 'required|exists:jabatans,id_jabatan',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($id_user);

        $data = [
            'nama' => $request->nama,
            'nim' => $request->nim,
            'id_jabatan' => $request->id_jabatan,
            'role_id' => $request->role_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('userControl.index')
            ->with('success', 'Pengguna berhasil diperbarui!');
    }

    public function destroy(string $id_user)
    {
        $user = User::findOrFail($id_user);
        $user->delete();

        return redirect()->route('userControl.index')
            ->with('success', 'User deleted successfully.');
    }
}
