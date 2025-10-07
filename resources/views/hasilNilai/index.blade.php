@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Hasil Penilaian (Rata-rata per Kriteria)</h2>
    <table class="table table-bordered table-striped">
        <thead class="bg-dark text-white">
            <tr>
                <th>No</th>
                <th>Orang yang Dinilai</th>
                @foreach($kriterias as $kriteria)
                <th>{{ $kriteria->nama_kriteria }}</th>
                @endforeach
                <th class="bg-primary text-white">Rata-rata</th> {{-- Kolom baru --}}
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row['nama'] }}</td>
                @foreach($kriterias as $kriteria)
                <td>{{ $row[$kriteria->nama_kriteria] }}</td>
                @endforeach
                <td class="fw-bold text-primary">{{ $row['rata_rata_semua_kriteria'] }}</td> {{-- Kolom baru --}}
                <td>{{ $row['tanggal'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection