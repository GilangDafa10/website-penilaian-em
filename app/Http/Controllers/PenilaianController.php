<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penilaian;
use App\Models\Kriteria;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $role = $user->role_id;
        $divisiBidang = optional($user->jabatan)->bidang;

        $daftarTarget = collect();

        switch ($role) {
            case 6: // Admin
                // Admin tidak menilai
                break;

            case 1: // Kahim
            case 2: // Wakahim
                // Bisa menilai SEMUA (kecuali admin & diri sendiri)
                $daftarTarget = User::with('jabatan', 'role')
                    ->where('id_user', '!=', $user->id_user)
                    ->whereNotIn('role_id', [5, 6]) // hanya exclude admin
                    ->get();
                break;

            case 3: // Kabid
                // Bisa menilai kahim, wakahim, kadiv & staff di divisi yang sama
                if ($divisiBidang) {
                    $daftarTarget = User::with('jabatan', 'role')
                        ->where('id_user', '!=', $user->id_user)
                        ->whereHas('jabatan', function ($query) use ($divisiBidang) {
                            $query->where('bidang', $divisiBidang);
                        })->orWhereIn('role_id', [1, 2])
                        ->get();
                }
                break;

            case 4: // Kadiv
                // Bisa menilai SEMUA di divisi yang sama (termasuk Kabid, Staff, bahkan Kahim/Wakahim jika masuk divisi — tapi biasanya tidak)
                if ($divisiBidang) {
                    $daftarTarget = User::with('jabatan', 'role')
                        ->where('id_user', '!=', $user->id_user)
                        ->whereHas('jabatan', function ($query) use ($divisiBidang) {
                            $query->where('bidang', $divisiBidang);
                        })->orWhereIn('role_id', [1, 2])
                        ->get();
                }
                break;

            case 5: // Staff
                // Bisa menilai SEMUA di divisi yang sama (termasuk Kadiv, Kabid, dll)
                if ($divisiBidang) {
                    $daftarTarget = User::with('jabatan', 'role')
                        ->where('id_user', '!=', $user->id_user)
                        ->whereHas('jabatan', function ($query) use ($divisiBidang) {
                            $query->where('bidang', $divisiBidang);
                        })->orWhereIn('role_id', [1, 2])
                        ->get();
                }
                break;
        }

        return view('penilaian.index', compact('daftarTarget'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($nim_dinilai)
    {
        $target = User::where('nim', $nim_dinilai)->firstOrFail();
        $kriteria = Kriteria::all();

        return view('penilaian.create', compact('target', 'kriteria'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user(); // user yang sedang login (penilai)

        $request->validate([
            'nim_dinilai' => 'required|exists:users,nim',
            'nilai' => 'required|array|min:1',
            'nilai.*' => 'required|integer|between:1,5',
        ]);

        // Cari user yang dinilai berdasarkan NIM
        $targetUser = User::where('nim', $request->nim_dinilai)->firstOrFail();
        // dd($user->id_user, $user->nim, $targetUser->id_user, $targetUser->nim);

        // Cegah menilai diri sendiri (berdasarkan ID, bukan NIM)
        if ($user->id_user === $targetUser->id_user) {
            return back()->withErrors(['error' => 'Anda tidak dapat menilai diri sendiri.']);
        }

        foreach ($request->nilai as $kriteriaId => $skor) {
            Penilaian::create([
                'id_penilai' => $user->id_user,        // ✅ simpan ID user penilai
                'id_dinilai' => $targetUser->id_user,  // ✅ simpan ID user yang dinilai
                'id_kriteria' => $kriteriaId,
                'skor' => $skor,
                'tanggal_penilaian' => now(),
            ]);
        }

        return redirect()->route('penilaian.show', $targetUser->nim)->with('success', 'Penilaian berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $nim_dinilai)
    {
        $target = User::where('nim', $nim_dinilai)->firstOrFail();

        $penilaian = Penilaian::where('id_dinilai', $target->id_user)
            ->with('kriteria', 'penilai')
            ->get();

        return view('penilaian.show', compact('target', 'penilaian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
