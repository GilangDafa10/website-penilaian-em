<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penilaian;
use App\Models\Kriteria;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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
                $daftarTarget = User::with('jabatan', 'role')
                    ->where('id_user', '!=', $user->id_user)
                    ->where('role_id', '!=', 5)
                    ->where('role_id', '!=', 6)
                    ->get();
                break;

            case 3: // Kabid
                if ($bidangUser) {
                    $daftarTarget = User::with('jabatan', 'role')
                        ->where('id_user', '!=', $user->id_user)
                        ->where(function ($query) use ($bidangUser) {
                            $query->whereHas('jabatan', function ($q) use ($bidangUser) {
                                $q->where('bidang', $bidangUser);
                            })
                                ->orWhereIn('role_id', [1, 2]);
                        })
                        ->get();
                }
                break;

            case 4: // Kadiv
                if ($bidangUser || $divisiUser) {
                    $daftarTarget = User::with('jabatan', 'role')
                        ->where('id_user', '!=', $user->id_user)
                        ->where(function ($query) use ($bidangUser, $divisiUser) {
                            $query->where('role_id', 3)
                                ->whereHas('jabatan', fn($q) => $q->where('bidang', $bidangUser ?? $divisiUser));

                            $query->orWhere(function ($q) use ($divisiUser) {
                                $q->where('role_id', 5)
                                    ->whereHas('jabatan', fn($j) => $j->where('divisi', $divisiUser));
                            });

                            $query->orWhereIn('role_id', [1, 2]);
                        })
                        ->get();
                }
                break;

            case 5: // Staff
                if ($divisiUser || $bidangUser) {
                    $daftarTarget = User::with('jabatan', 'role')
                        ->where('id_user', '!=', $user->id_user)
                        ->where(function ($query) use ($divisiUser, $bidangUser) {
                            $query->where('role_id', 4)
                                ->whereHas('jabatan', fn($q) => $q->where('divisi', $divisiUser));

                            $query->orWhere(function ($q) use ($bidangUser) {
                                $q->where('role_id', 3)
                                    ->whereHas('jabatan', fn($j) => $j->where('bidang', $bidangUser));
                            });

                            $query->orWhere(function ($q) use ($divisiUser) {
                                $q->where('role_id', 5)
                                    ->whereHas('jabatan', fn($j) => $j->where('divisi', $divisiUser));
                            });

                            $query->orWhereIn('role_id', [1, 2]);
                        })
                        ->get();
                }
                break;
        }

        // Tambahkan informasi status penilaian untuk setiap target
        $daftarTargetWithStatus = $daftarTarget->map(function ($target) use ($user) {
            $sudahDinilai = Penilaian::where('id_penilai', $user->id_user)
                ->where('id_dinilai', $target->id_user)
                ->exists();

            $target->sudah_dinilai = $sudahDinilai;
            return $target;
        });

        return Inertia::render('Penilaian/Index', [
            'daftarTarget' => $daftarTargetWithStatus,
            'auth' => [
                'user' => User::with('role', 'jabatan')->find($user->id_user),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($nim_dinilai)
    {
        $target = User::where('nim', $nim_dinilai)
            ->with('jabatan', 'role')
            ->firstOrFail();

        $kriteria = Kriteria::all();

        return Inertia::render('Penilaian/Create', [
            'target' => $target,
            'kriteria' => $kriteria,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nim_dinilai' => 'required|exists:users,nim',
            'nilai' => 'required|array|min:1',
            'nilai.*' => 'required|integer|between:1,5',
        ]);

        $targetUser = User::where('nim', $request->nim_dinilai)->firstOrFail();

        if ($user->id_user === $targetUser->id_user) {
            return back()->withErrors(['error' => 'Anda tidak dapat menilai diri sendiri.']);
        }

        // Cek apakah sudah pernah menilai user ini
        $sudahPernilai = Penilaian::where('id_penilai', $user->id_user)
            ->where('id_dinilai', $targetUser->id_user)
            ->exists();

        if ($sudahPernilai) {
            return back()->withErrors(['error' => 'Anda sudah pernah menilai ' . $targetUser->nama . '. Penilaian hanya dapat dilakukan sekali.']);
        }

        foreach ($request->nilai as $kriteriaId => $skor) {
            Penilaian::create([
                'id_penilai' => $user->id_user,
                'id_dinilai' => $targetUser->id_user,
                'id_kriteria' => $kriteriaId,
                'skor' => $skor,
                'tanggal_penilaian' => now(),
            ]);
        }

        return redirect()
            ->route('penilaian.index', $targetUser->nim)
            ->with('success', 'Penilaian berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $nim_dinilai)
    {
        $target = User::where('nim', $nim_dinilai)
            ->with('jabatan', 'role')
            ->firstOrFail();

        $kriteriaList = Kriteria::orderBy('id_kriteria')->get();

        $penilaian = Penilaian::with(['penilai', 'kriteria'])
            ->where('id_dinilai', $target->id_user)
            ->get();

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

        return Inertia::render('Penilaian/Show', [
            'target' => $target,
            'kriteriaList' => $kriteriaList,
            'dataPivot' => $dataPivot,
        ]);
    }
}
