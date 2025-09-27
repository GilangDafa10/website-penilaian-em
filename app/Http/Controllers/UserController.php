<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['jabatan', 'role'])->paginate(10);
        return view('admin.userControl.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $jabatans = Jabatan::all();
        $roles = Role::all();
        return view('admin.userControl.create', compact('jabatans', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'string|unique:users,id_user',
            'nim' => 'required|string|unique:users,nim',
            'nama' => 'required|string',
            'password' => 'required|string|min:6',
            'role_id' => 'required|integer',
            'id_jabatan' => 'required|integer',
        ]);

        User::create([
            'id_user' => $request->id_user,
            'nim' => $request->nim,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
            'id_jabatan' => $request->id_jabatan,
        ]);

        return redirect()->route('admin.userControl.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_user)
    {
        $user = User::findOrFail($id_user);
        return view('admin.userControl.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id_user)
    {
        $jabatans = Jabatan::all();
        $roles = Role::all();
        $user = User::findOrFail($id_user);
        return view('admin.userControl.edit', compact('user', 'jabatans', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_user)
    {
        // dd($request->all());
        // Validasi data
        $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|unique:users,nim,' . $id_user . ',id_user',
            'password' => 'nullable|string|min:6',
            'id_jabatan' => 'required|exists:jabatans,id_jabatan',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($id_user);

        // Siapkan data untuk update
        $data = [
            'nama' => $request->nama,
            'nim' => $request->nim,
            'id_jabatan' => $request->id_jabatan,
            'role_id' => $request->role_id,
        ];

        // Jika password diisi, hash dan tambahkan
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        // Simpan perubahan
        $user->update($data);

        // Redirect ke halaman daftar dengan pesan sukses
        return redirect()->route('admin.userControl.index')->with('success', 'Pengguna berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_user)
    {
        $user = User::findOrFail($id_user);
        $user->delete();
        return redirect()->route('admin.userControl.index')->with('success', 'User deleted successfully.');
    }
}
