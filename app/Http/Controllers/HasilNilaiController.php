<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kriteria;
use App\Models\User;
use App\Models\Penilaian;
use Illuminate\Support\Facades\DB;

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

            // Simpan nilai rata-rata per kriteria (dan kumpulkan untuk hitung total)
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

            // 🔥 HITUNG RATA-RATA DARI SEMUA NILAI KRITERIA
            if (count($nilaiKriteria) > 0) {
                $rataRataSemua = round(array_sum($nilaiKriteria) / count($nilaiKriteria), 2);
            } else {
                $rataRataSemua = '-';
            }

            $row['rata_rata_semua_kriteria'] = $rataRataSemua;

            $data[] = $row;
        }

        return view('hasilNilai.index', compact('data', 'kriterias'));
    }
}
