<!DOCTYPE html>
<html>
<head>
    <title>Booking Service</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    @yield('content')
    <!-- Tambahkan Font Awesome untuk Ikon (Ditempatkan di sini jika tidak ada di app.blade.php) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLMDJzLlQ36k2UaYJtU378F7xXvP+sN4fFw/U6/A2uB4aU+M+F1U+A9P7y+W+W+Z+Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Perlu CSS Kustom untuk efek "Soft Badge" -->
<style>
    /* Contoh implementasi Soft Badge jika tidak menggunakan Bootstrap kit lanjutan */
    .badge-soft-warning { background-color: #fff3cd; }
    .badge-soft-info { background-color: #cff4fc; }
    .badge-soft-primary { background-color: #cfe2ff; }
    .badge-soft-success { background-color: #d1e7dd; }
    .badge-soft-danger { background-color: #f8d7da; }
    /* Menambahkan padding ke body agar konten tidak tertutup fixed navbar */
    body { padding-top: 70px; }
</style>

<!-- ========================================================================= -->
<!-- NAVBAR ADMIN KEKINIAN (FIXED TOP) -->
<!-- ========================================================================= -->
{{-- Hanya tampilkan navbar ini jika user sudah login DAN rolenya admin --}}
@auth
    @if(Auth::user()->role === 'admin')
        <nav class="navbar navbar-expand-lg navbar-danger bg-danger shadow-sm fixed-top">
            <div class="container">
                <a class="navbar-brand fw-bold text-white" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tools me-2 text-white"></i> Admin Panel
                </a>
                {{-- Menambahkan class border dan border-light pada tombol toggler --}}
                <button class="navbar-toggler border border-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('admin.dashboard') ? 'active text-white fw-bold' : 'text-white' }}" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt me-1 text-white"></i> Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('booking.index') ? 'active text-white fw-bold' : 'text-white' }}" href="{{ route('booking.index') }}"><i class="fas fa-book me-1 text-white"></i> Semua Booking</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('customers.index') ? 'active text-white fw-bold' : 'text-white' }}" href="{{ route('customers.index') }}"><i class="fas fa-users me-1 text-white"></i> Customer</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('advisor.create') ? 'active text-white fw-bold' : 'text-white' }}" href="{{ route('advisor.create') }}"><i class="fas fa-user-tie me-1 text-white"></i> Advisor</a>
                        </li>
                    </ul>

                    <ul class="navbar-nav ms-2">
                        <li class="nav-item">
                            <a class="btn btn-outline-light btn-sm" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> Keluar
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    @endif
@endauth

{{-- ========================================================================= --}}
{{-- NAVBAR CUSTOMER (JIKA ROLE ADALAH CUSTOMER) --}}
{{-- ========================================================================= --}}
@auth
    @if(Auth::user()->role === 'customer')
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm fixed-top"> {{-- Bisa diganti warnanya misal bg-primary --}}
            <div class="container">
                <a class="navbar-brand" href="{{ route('customers.dashboard') }}">
                    <i class="fas fa-motorcycle me-2"></i> Bengkel Servis
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#customerNavbarNav" aria-controls="customerNavbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="customerNavbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            {{-- Link ke Dashboard Customer (Selamat Datang & Riwayat) --}}
                            <a class="nav-link {{ Route::is('customers.dashboard') ? 'active fw-bold' : '' }}" href="{{ route('customers.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                             {{-- Link ke Form Booking --}}
                            <a class="nav-link {{ Route::is('booking.create') ? 'active fw-bold' : '' }}" href="{{ route('booking.create') }}">
                                <i class="fas fa-calendar-plus me-1"></i> Buat Booking
                            </a>
                        </li>
                        {{-- Bisa tambahkan link Riwayat terpisah jika ada halamannya --}}
                        {{-- <li class="nav-item">
                            <a class="nav-link" href="#">Riwayat Booking</a>
                        </li> --}}
                    </ul>

                    <ul class="navbar-nav ms-2">
                         {{-- Tombol Logout --}}
                        <li class="nav-item">
                            <a class="btn btn-outline-danger btn-sm" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form-customer').submit();">
                                <i class="fas fa-sign-out-alt"></i> Keluar
                            </a>
                            <form id="logout-form-customer" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    @endif
@endauth
{{-- AKHIR BLOK NAVBAR CUSTOMER --}}
</body>
</html>
