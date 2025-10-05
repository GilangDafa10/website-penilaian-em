@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2>Hasil Penilaian: {{ $target->nama }}</h2>
    <p><strong>NIM:</strong> {{ $target->nim ?? '-' }}</p>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Penilai</th>
                    @foreach($kriteriaList as $kriteria)
                    <th>{{ $kriteria->nama_kriteria }}</th>
                    @endforeach
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataPivot as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $row['penilai'] }}</td>
                    @foreach($kriteriaList as $kriteria)
                    <td class="text-center">
                        {{ $row['kriteria'][$kriteria->nama_kriteria] ?? '-' }}
                    </td>
                    @endforeach
                    <td>{{ $row['tanggal'] }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ 3 + $kriteriaList->count() }}" class="text-center">
                        Belum ada data penilaian.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection