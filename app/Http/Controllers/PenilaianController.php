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
        $divisiId = $user->divisi_id;

        $daftarTarget = collect();

        switch ($role) {
            case 'admin':
                $daftarTarget = collect();
                break;
            case 'kahim':
            case 'wakahim':
                // Bisa menilai semua user, kecuali admin & staff
                $daftarTarget = User::whereNotIn('role_id', ['admin', 'staff'])->get();
                break;
            case 'kabid':
                // Bisa menilai kadiv & staff di divisi yang sama
                $daftarTarget = User::whereIn('role_id', ['kadiv', 'staff', 'kahim', 'wakahim'])
                    ->where('divisi_id', $divisiId)
                    ->get();
                break;
            case 'kadiv':
                // Bisa menilai staff di divisi yang sama
                $daftarTarget = User::whereIn('role_id', ['staff', 'kahim', 'wakahim'])
                    ->where('divisi_id', $divisiId)
                    ->get();
                break;
            case 'staff':
                // Bisa menilai staff lain di divisi yang sama
                $daftarTarget = User::whereIn('role_id', ['staff', 'kahim', 'wakahim', 'kabid', 'kadiv'])
                    ->where('divisi_id', $divisiId)
                    ->where('id', '!=', $user->id)
                    ->get();
                break;
        }

        return view('penilaian.index', compact('daftarTarget', 'role'));
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
        $user = Auth::user();
        $request->validate(
            [
                'nim_dinilai' => 'required|exists:users,nim',
                'skor' => 'required|array',
            ]
        );

        $nimPenilai = $user->nim;
        $nimDinilai = $request->input('nim_dinilai');

        if ($nimPenilai === $nimDinilai) {
            return back()->withErrors(['error' => 'Anda tidak dapat menilai diri sendiri.']);
        }

        foreach ($request->nilai as $kriteriaId => $skor) {
            Penilaian::create(
                [
                    'nim_penilai' => $nimPenilai,
                    'nim_dinilai' => $nimDinilai,
                    'id_kriteria' => $kriteriaId,
                    'skor' => $skor,
                    'tanggal_penilaian' => now(),
                ],
            );
        }

        return redirect()->route('penilaian.index')->with('success', 'Penilaian berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $target = User::where('nim', $id)->firstOrFail();

        $penilaian = Penilaian::where('nim_dinilai', $id)
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
