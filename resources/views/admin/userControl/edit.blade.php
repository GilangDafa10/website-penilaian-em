@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-user-edit me-2"></i>Edit Pengguna</h3>
        <a href="{{ route('admin.userControl.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.userControl.update', $user->id_user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror"
                            id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required>
                        @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="nim" class="form-label">NIM</label>
                        <input type="text" class="form-control @error('nim') is-invalid @enderror"
                            id="nim" name="nim" value="{{ old('nim', $user->nim) }}" required>
                        @error('nim')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password Baru (opsional)</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" name="password">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengganti password.</small>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="id_jabatan" class="form-label">Jabatan</label>
                        <select class="form-select @error('id_jabatan') is-invalid @enderror"
                            id="id_jabatan" name="id_jabatan" required>
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach($jabatans as $jabatan)
                            <option value="{{ $jabatan->id_jabatan }}"
                                {{ (old('id_jabatan') ?? $user->id_jabatan) == $jabatan->id_jabatan ? 'selected' : '' }}>
                                {{ $jabatan->nama_jabatan }} ({{ $jabatan->divisi_bidang }})
                            </option>
                            @endforeach
                        </select>
                        @error('id_jabatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="role_id" class="form-label">Role</label>
                        <select class="form-select @error('role_id') is-invalid @enderror"
                            id="role_id" name="role_id" required>
                            <option value="">-- Pilih Role --</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->role_id ? 'selected' : '' }}>
                                {{ $role->nama_role }}
                            </option>
                            @endforeach
                        </select>
                        @error('role_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-grid mt-3">
                    <button type="submit" class="btn btn-warning text-white">
                        <i class="fas fa-sync-alt me-1"></i> Perbarui Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection