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
        $jabatan = $user->jabatan;
        $bidangUser = optional($jabatan)->bidang;
        $divisiUser = optional($jabatan)->divisi;

        $daftarTarget = collect();

        switch ($role) {
            case 6: // Admin
                // Tidak menilai siapa pun
                break;

            case 1: // Kahim
            case 2: // Wakahim
                // Bisa menilai SEMUA kecuali Staff (role_id = 5) dan diri sendiri
                $daftarTarget = User::with('jabatan', 'role')
                    ->where('id_user', '!=', $user->id_user)
                    ->where('role_id', '!=', 5) // exclude staff
                    ->where('role_id', '!=', 6) // exclude admin (opsional, tapi aman)
                    ->get();
                break;

            case 3: // Kabid
                // Bisa menilai:
                // - Semua user (kecuali diri sendiri) yang memiliki bidang SAMA
                // - Kahim & Wakahim (role 1,2)
                if ($bidangUser) {
                    $daftarTarget = User::with('jabatan', 'role')
                        ->where('id_user', '!=', $user->id_user)
                        ->where(function ($query) use ($bidangUser) {
                            // User dengan bidang sama (bisa Kabid, Kadiv, Staff — asal bidangnya cocok)
                            $query->whereHas('jabatan', function ($q) use ($bidangUser) {
                                $q->where('bidang', $bidangUser);
                            })
                                // PLUS Kahim & Wakahim
                                ->orWhereIn('role_id', [1, 2]);
                        })
                        ->get();
                }
                break;

            case 4: // Kadiv
                // Bisa menilai:
                // - Kabid yang bidangnya SAMA dengan divisi Kadiv? 
                //   → Tapi ini ambigu! Biasanya: **divisi berada di bawah bidang**
                //   Misal: Bidang "Kemahasiswaan" → Divisi "Olahraga", "Seni"
                //   Maka: Kadiv "Olahraga" berada di bawah Kabid "Kemahasiswaan"
                //
                // Jadi, asumsi kita:
                // - `divisi` punya relasi implisit ke `bidang` via struktur organisasi
                // - TAPI di database, kita tidak punya relasi eksplisit
                //
                // 🚨 MASALAH: Bagaimana tahu Kabid mana yang "menaungi" divisi ini?
                //
                // Solusi sementara (jika tidak ada relasi eksplisit):
                // → Asumsikan **nama bidang = nama divisi induk**, atau
                // → Tambahkan kolom `bidang_id` di divisi, atau
                // → Simpan `bidang` juga di divisi (redundan tapi praktis)
                //
                // Untuk sekarang, asumsikan: **divisi punya kolom `bidang` juga**
                // atau: **Kabid dan Kadiv/Staff menggunakan `bidang` yang sama**
                //
                // Jika tidak, logika ini tidak bisa jalan.
                //
                // ✅ Asumsi: `jabatan.divisi` dan `jabatan.bidang` keduanya ada,
                // dan untuk Kadiv/Staff, `bidang` juga diisi sesuai bidang induknya.
                //
                // Maka:
                if ($bidangUser || $divisiUser) {
                    $daftarTarget = User::with('jabatan', 'role')
                        ->where('id_user', '!=', $user->id_user)
                        ->where(function ($query) use ($bidangUser, $divisiUser) {
                            // 1. Kabid dengan bidang sama
                            $query->where('role_id', 3)
                                ->whereHas('jabatan', fn($q) => $q->where('bidang', $bidangUser ?? $divisiUser));

                            // 2. Staff dengan divisi sama
                            $query->orWhere(function ($q) use ($divisiUser) {
                                $q->where('role_id', 5)
                                    ->whereHas('jabatan', fn($j) => $j->where('divisi', $divisiUser));
                            });

                            // 3. Kahim & Wakahim
                            $query->orWhereIn('role_id', [1, 2]);
                        })
                        ->get();
                }
                break;

            case 5: // Staff
                // Bisa menilai:
                // - Kadiv di divisi yang sama
                // - Kabid di bidang yang sama
                // - Kahim & Wakahim
                if ($divisiUser || $bidangUser) {
                    $daftarTarget = User::with('jabatan', 'role')
                        ->where('id_user', '!=', $user->id_user)
                        ->where(function ($query) use ($divisiUser, $bidangUser) {
                            // Kadiv (role 4) dengan divisi sama
                            $query->where('role_id', 4)
                                ->whereHas('jabatan', fn($q) => $q->where('divisi', $divisiUser));

                            // Kabid (role 3) dengan bidang sama
                            $query->orWhere(function ($q) use ($bidangUser) {
                                $q->where('role_id', 3)
                                    ->whereHas('jabatan', fn($j) => $j->where('bidang', $bidangUser));
                            });

                            // 2. Staff dengan divisi sama
                            $query->orWhere(function ($q) use ($divisiUser) {
                                $q->where('role_id', 5)
                                    ->whereHas('jabatan', fn($j) => $j->where('divisi', $divisiUser));
                            });

                            // Kahim & Wakahim
                            $query->orWhereIn('role_id', [1, 2]);
                        })
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
        // Cari user berdasarkan NIM (bukan ID)
        $target = User::where('nim', $nim_dinilai)->firstOrFail();

        // Ambil semua kriteria
        $kriteriaList = Kriteria::orderBy('id_kriteria')->get();

        // Ambil semua penilaian untuk user ini
        $penilaian = Penilaian::with(['penilai', 'kriteria'])
            ->where('id_dinilai', $target->id_user) // tetap pakai id_dinilai (foreign key ke users.id_user)
            ->get();

        // Transformasi ke format pivot
        $dataPivot = $penilaian
            ->groupBy('id_penilai')
            ->map(function ($items) {
                $first = $items->first();
                return [
                    'penilai' => $first->penilai->nama,
                    'tanggal' => $first->created_at->format('d-m-Y'),
                    'kriteria' => $items->pluck('skor', 'kriteria.nama_kriteria')->toArray(),
                ];
            })
            ->values()
            ->all();

        return view('penilaian.show', compact('target', 'kriteriaList', 'dataPivot'));
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
