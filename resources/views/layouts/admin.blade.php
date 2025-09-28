<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - {{ config('app.name') }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome (opsional, untuk ikon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            padding-top: 70px;
            background-color: #343a40;
            color: white;
        }

        .sidebar .nav-link {
            color: #adb5bd;
            padding: 12px 20px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: #495057;
        }

        .main-content {
            margin-left: 150px;
            padding: 20px;
        }

        .navbar-brand span {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <!-- Navbar Atas -->
    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container-fluid">
            <button class="btn btn-outline-light d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand d-none d-md-block" href="{{ route('admin.dashboard') }}">
                <span>{{ config('app.name') }}</span> <small>Admin</small>
            </a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">Halo, {{ Auth::user()->nama ?? 'Admin' }}</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Sidebar (Navigasi Admin) -->
    <div class="sidebar d-md-block collapse" id="sidebar">
        <div class="list-group list-group-flush">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.userControl.index') }}" class="nav-link">
                <i class="fas fa-users me-2"></i> Pengguna
            </a>
            <a href="#" class="nav-link">
                <i class="fas fa-box me-2"></i> Produk
            </a>
            <a href="#" class="nav-link">
                <i class="fas fa-chart-line me-2"></i> Laporan
            </a>
            <a href="#" class="nav-link">
                <i class="fas fa-cog me-2"></i> Pengaturan
            </a>
        </div>
    </div>

    <!-- Konten Utama -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>