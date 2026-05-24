<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Kriteria;
use App\Models\User;
use App\Models\Penilaian;

class HasilNilaiController extends Controller
{
    public function index()
    {
        $kriterias = Kriteria::all();
        $userIds = Penilaian::distinct()->pluck('id_dinilai');
        $users = User::whereIn('id_user', $userIds)->get();

        $data = [];

        foreach ($users as $user) {
            $row = [
                'nama' => $user->nama,
                'tanggal' => Penilaian::where('id_dinilai', $user->id_user)
                    ->orderBy('tanggal_penilaian', 'desc')
                    ->first()?->tanggal_penilaian ?? '-',
            ];

            $nilaiKriteria = [];

            foreach ($kriterias as $kriteria) {
                $avg = Penilaian::where('id_dinilai', $user->id_user)
                    ->where('id_kriteria', $kriteria->id_kriteria)
                    ->avg('skor');

                $nilai = $avg ? round($avg, 2) : null;
                $row[$kriteria->nama_kriteria] = $nilai !== null ? $nilai : '-';
                if ($nilai !== null) {
                    $nilaiKriteria[] = $nilai;
                }
            }

            $row['rata_rata_semua_kriteria'] =
                count($nilaiKriteria) > 0
                ? round(array_sum($nilaiKriteria) / count($nilaiKriteria), 2)
                : '-';

            $data[] = $row;
        }

        return Inertia::render('MainContent/HasilNilai', [
            'data' => $data,
            'kriterias' => $kriterias,
        ]);
    }
}
