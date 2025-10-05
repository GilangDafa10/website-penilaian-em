<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-card {
            max-width: 800px;
            margin: 2rem auto;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, .075);
        }
    </style>
</head>

<body class="bg-light">
    <div class="container py-4">
        <div class="profile-card bg-white rounded p-4">
            <h2 class="mb-4">Profil Pengguna</h2>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Informasi Profil -->
            <div class="row mb-4">
                <div class="col-md-3"><strong>Nama</strong></div>
                <div class="col-md-9">{{ $user->nama }}</div>
            </div>
            <div class="row mb-4">
                <div class="col-md-3"><strong>NIM</strong></div>
                <div class="col-md-9">{{ $user->nim ?? '-' }}</div>
            </div>
            <div class="row mb-4">
                <div class="col-md-3"><strong>Role</strong></div>
                <div class="col-md-9">{{ ucfirst($user->role->nama_role ?? 'Mahasiswa') }}</div>
            </div>
            <div class="row mb-4">
                <div class="col-md-3"><strong>Jabatan</strong></div>
                <div class="col-md-9">{{ $user->jabatan->nama_jabatan ?? '-' }}</div>
            </div>
            <div class="row mb-4">
                <div class="col-md-3"><strong>Divisi</strong></div>
                <div class="col-md-9">{{ $user->jabatan->divisi ?? '-' }}</div>
            </div>
            <div class="row mb-4">
                <div class="col-md-3"><strong>Bidang</strong></div>
                <div class="col-md-9">{{ $user->jabatan->bidang ?? '-' }}</div>
            </div>

            <hr class="my-4">

            <!-- Form Ubah Password -->
            <h4 class="mb-3">Ubah Password</h4>
            <form action="{{ route('userProfil.update-password') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="current_password" class="form-label">Password Saat Ini</label>
                    <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                        id="current_password" name="current_password" required>
                    @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label">Password Baru</label>
                    <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                        id="new_password" name="new_password" required minlength="6">
                    @error('new_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control"
                        id="new_password_confirmation" name="new_password_confirmation" required>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary">Perbarui Password</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Kembali ke Profil</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>