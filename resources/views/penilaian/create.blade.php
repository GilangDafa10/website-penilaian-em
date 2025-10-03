@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2>Beri Penilaian untuk {{ $target->nama }}</h2>
    <p><strong>NIM:</strong> {{ $target->nim }}</p>

    <form action="{{ route('penilaian.store') }}" method="POST">
        @csrf
        <input type="hidden" name="nim_dinilai" value="{{ $target->nim }}">

        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="row">
            @foreach($kriteria as $k)
            <div class="col-md-6 mb-3">
                <label class="form-label">{{ $k->nama_kriteria }}</label>
                <select name="nilai[{{ $k->id_kriteria }}]" class="form-select" required>
                    <option value="">Pilih Skor</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                </select>
            </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-success">Simpan Penilaian</button>
        <a href="{{ route('penilaian.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection