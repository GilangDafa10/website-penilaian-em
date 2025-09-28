@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Dashboard Admin</h2>

    <!-- Statistik Card -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-users me-2"></i> Pengguna</h5>
                    <p class="card-text display-6">1,248</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-box me-2"></i> Produk</h5>
                    <p class="card-text display-6">327</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-shopping-cart me-2"></i> Pesanan</h5>
                    <p class="card-text display-6">89</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-exclamation-circle me-2"></i> Pending</h5>
                    <p class="card-text display-6">12</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Konten Tambahan -->
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Aktivitas Terbaru</h5>
        </div>
        <div class="card-body">
            <p>Belum ada aktivitas baru.</p>
            <small class="text-muted">Terakhir diperbarui: {{ now()->format('d M Y H:i') }}</small>
        </div>
    </div>
</div>
@endsection