@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Hasil Penilaian: {{ $target->nama }}</h2>
    <p><strong>NIM:</strong> {{ $target->nim }}</p>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($penilaian->isEmpty())
    <div class="alert alert-warning">Belum ada penilaian untuk pengguna ini.</div>
    @else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Penilai</th>
                <th>Kriteria</th>
                <th>Skor</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penilaian as $p)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $p->penilai->nama ?? '–' }}</td>
                <td>{{ $p->kriteria->nama_kriteria ?? '–' }}</td>
                <td>{{ $p->skor }}</td>
                <td>{{ $p->tanggal_penilaian ? \Carbon\Carbon::parse($p->tanggal_penilaian)->format('d-m-Y') : '–' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <a href="{{ route('penilaian.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection