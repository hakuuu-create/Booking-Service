@extends('layouts.app')

@section('title', 'Daftar Akun - Bengkel Resmi Honda')

@section('content')
<style>
    /* --- CUSTOM AUTH STYLES (Sama dengan Login) --- */
    :root {
        --honda-red: #CC0000;
        --honda-dark-red: #990000;
    }

    body {
        background-color: #f8f9fa;
        overflow-x: hidden;
    }

    /* Navbar Khusus Auth */
    .auth-navbar {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid #eee;
        height: 70px;
    }

    /* Container Utama */
    .login-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding-top: 80px; /* Offset Navbar */
        margin-top: 70px;
        padding-bottom: 40px;
    }

    /* Card Modern */
    .login-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        overflow: hidden;
        width: 100%;
        max-width: 1000px;
        border: none;
        display: flex; /* Flex container untuk equal height */
    }

    /* Bagian Gambar (Visual Side) */
    .login-image-side {
        /* Gambar berbeda untuk Register agar segar */
        background: url('https://images.unsplash.com/photo-1625043484555-48548a666235?q=80&w=800&auto=format&fit=crop') no-repeat center center;
        background-size: cover;
        position: relative;
        display: flex;
        align-items: flex-end;
        padding: 40px;
        min-height: 600px;
    }
    
    .login-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(to top, rgba(204, 0, 0, 0.85), rgba(0,0,0,0.3));
    }

    .login-caption {
        position: relative;
        z-index: 2;
        color: white;
    }

    /* Bagian Form */
    .login-form-side {
        padding: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background-color: #fff;
    }

    /* Floating Labels Styling */
    .form-floating > .form-control {
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        padding-left: 20px;
    }
    
    .form-floating > .form-control:focus {
        border-color: var(--honda-red);
        box-shadow: 0 0 0 0.25rem rgba(204, 0, 0, 0.1);
    }
    
    .form-floating > label {
        padding-left: 20px;
        color: #6c757d;
    }

    /* Tombol Register */
    .btn-register {
        background: linear-gradient(to right, var(--honda-red), var(--honda-dark-red));
        border: none;
        color: white;
        padding: 12px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s;
    }
    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(204, 0, 0, 0.3);
        color: white;
    }

    .divider-text {
        display: flex;
        align-items: center;
        text-align: center;
        color: #aaa;
        margin: 20px 0;
        font-size: 0.85rem;
    }
    .divider-text::before, .divider-text::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #eee;
    }
    .divider-text::before { margin-right: 10px; }
    .divider-text::after { margin-left: 10px; }
</style>

{{-- 1. NAVBAR FIXED --}}
<nav class="navbar fixed-top auth-navbar">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ url('/') }}">
            <img src="https://upload.wikimedia.org/wikipedia/commons/3/38/Honda.svg" alt="Honda" height="24" class="me-2">
            <span class="text-danger">AHASS</span> <span class="text-dark">Service</span>
        </a>
        <span class="text-muted small d-none d-sm-block">Sudah punya akun?</span>
        <a href="{{ route('login') }}" class="btn btn-outline-danger rounded-pill px-4 fw-bold small ms-2">
            Masuk
        </a>
    </div>
</nav>

{{-- 2. REGISTER WRAPPER --}}
<div class="login-wrapper p-3">
    <div class="card login-card">
        <div class="row g-0 h-100">
            
            {{-- KOLOM KIRI: Visual (Hidden on Mobile) --}}
            <div class="col-lg-5 d-none d-lg-block login-image-side">
                <div class="login-overlay"></div>
                <div class="login-caption">
                    <h2 class="fw-bold display-6 mb-3">Bergabunglah Bersama Kami!</h2>
                    <p class="mb-4 opacity-90 text-white">
                        <i class="fas fa-check-circle me-2"></i> Booking servis tanpa antri<br>
                        <i class="fas fa-check-circle me-2"></i> Pantau status pengerjaan real-time<br>
                        <i class="fas fa-check-circle me-2"></i> Riwayat servis tercatat rapi
                    </p>
                    <small class="opacity-75">&copy; {{ date('Y') }} Bengkel Resmi Honda</small>
                </div>
            </div>

            {{-- KOLOM KANAN: Form Register --}}
            <div class="col-lg-7 login-form-side">
                <div class="mb-4">
                    <h3 class="fw-bold text-dark mb-1">Buat Akun Baru</h3>
                    <p class="text-muted small">Lengkapi data diri Anda untuk memulai.</p>
                </div>

                {{-- Alert Error --}}
                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-3 d-flex align-items-center mb-4 p-3" role="alert">
                        <i class="fas fa-exclamation-triangle me-3 fs-4"></i>
                        <div class="small lh-sm">
                            <strong>Ups! Ada kesalahan input:</strong>
                            <ul class="mb-0 ps-3 mt-1">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('register.post') }}" method="POST">
                    @csrf
                    
                    {{-- Input Nama --}}
                    <div class="form-floating mb-3">
                        <input type="text" name="name" class="form-control" id="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required autofocus>
                        <label for="name">Nama Lengkap</label>
                    </div>

                    {{-- Input Email --}}
                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" value="{{ old('email') }}" required>
                        <label for="email">Alamat Email</label>
                    </div>

                    <div class="row g-2">
                        {{-- Input Password --}}
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                                <label for="password">Password</label>
                            </div>
                        </div>

                        {{-- Input Konfirmasi Password --}}
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Ulangi Password" required>
                                <label for="password_confirmation">Ulangi Password</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" value="" id="agreeTerms" required>
                        <label class="form-check-label small text-muted" for="agreeTerms">
                            Saya menyetujui <a href="#" class="text-danger text-decoration-none">Syarat & Ketentuan</a> yang berlaku.
                        </label>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-register shadow">
                            Daftar Sekarang <i class="fas fa-user-plus ms-2"></i>
                        </button>
                    </div>

                    <div class="divider-text">Sudah memiliki akun?</div>

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="btn btn-outline-dark rounded-pill px-4 btn-sm fw-bold">
                            Login di sini
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection