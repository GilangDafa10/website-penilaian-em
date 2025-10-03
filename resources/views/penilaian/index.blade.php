@extends('layouts.admin') {{-- Sesuaikan nama layout jika berbeda --}}

@section('content')
<div class="container-fluid">
    <h2>Daftar Target Penilaian</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($daftarTarget->isEmpty())
    <div class="alert alert-info">Tidak ada target penilaian tersedia.</div>
    @else
    <div class="row">
        @foreach($daftarTarget as $target)
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('penilaian.show', $target->nim) }}" class="text-decoration-none text-black">
                        <h5 class="card-title">{{ $target->nama }}</h5>
                    </a>
                    <p class="card-text">
                        <strong>NIM:</strong> {{ $target->nim }}<br>
                        <strong>Role:</strong> {{ $target->role->nama_role }}<br>
                        <strong>Divisi:</strong> {{ $target->jabatan->divisi ?? '–' }}
                    </p>
                    <a href="{{ route('penilaian.create', $target->nim) }}" class="btn btn-primary">
                        Beri Penilaian
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection