@extends('layouts.admin') {{-- Sesuaikan nama layout jika berbeda --}}

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-users me-2"></i>Manajemen Pengguna</h3>
        <a href="{{ route('admin.userControl.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Pengguna
        </a>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th scope="col">NIM</th>
                            <th scope="col">Bidang</th>
                            <th scope="col">Divisi</th>
                            <th scope="col">Role</th>
                            <th scope="col" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                        <tr>
                            <th scope="row">
                                {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                            </th>
                            <td>{{ $user->nama }}</td>
                            <td>{{ $user->nim ?? '-' }}</td>
                            <td>
                                @if($user->is_admin)
                                <span class="badge bg-danger">Admin</span>
                                @else
                                <span class="badge bg-success">{{ $user->jabatan->bidang }}</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_admin)
                                <span class="badge bg-danger">Admin</span>
                                @else
                                <span class="badge bg-success">{{ $user->jabatan->divisi }}</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_admin)
                                <span class="badge bg-danger">Admin</span>
                                @else
                                <span class="badge bg-success">{{ $user->role->nama_role }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.userControl.edit', $user->id_user) }}" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <!-- Trigger Modal -->
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id_user }}">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <!-- Modal Konfirmasi Hapus -->
                                <div class="modal fade" id="deleteModal{{ $user->id_user }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $user->id_user }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $user->id_user }}">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin menghapus pengguna <strong>{{ $user->nama }}</strong>?<br>
                                                Tindakan ini <strong>tidak dapat dibatalkan</strong>.
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>

                                                <!-- Form Hapus di Dalam Modal -->
                                                <form action="{{ route('admin.userControl.destroy', $user->id_user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-check-circle me-1"></i> Yakin, Hapus!
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada pengguna ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination (hanya muncul jika ada halaman lebih dari 1) -->
            @if ($users->hasPages())
            <div class="d-flex justify-content-end mt-3">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Yakin ingin menghapus pengguna ini? Tindakan ini tidak bisa dibatalkan.')) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection