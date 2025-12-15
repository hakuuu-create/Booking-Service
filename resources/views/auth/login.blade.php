@extends('layouts.app')

@section('title', 'Masuk - Bengkel Resmi Honda')

@section('content')
<style>
    /* --- CUSTOM LOGIN STYLES --- */
    :root {
        --honda-red: #CC0000;
        --honda-dark-red: #990000;
    }

    body {
        background-color: #f8f9fa;
        overflow-x: hidden;
    }

    /* Navbar Khusus Login (Transparan ke Putih) */
    .auth-navbar {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid #eee;
        height: 70px;
    }

    /* Container Utama Full Height */
    .login-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding-top: 70px; /* Offset Navbar */
        margin-top: 70px
    }

    /* Card Modern Split */
    .login-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        overflow: hidden;
        width: 100%;
        max-width: 1000px;
        min-height: 600px;
        border: none;
    }

    /* Bagian Gambar (Kiri) */
    .login-image-side {
        background: url('https://images.unsplash.com/photo-1558981403-c5f9899a28bc?q=80&w=800&auto=format&fit=crop') no-repeat center center;
        background-size: cover;
        position: relative;
        display: flex;
        align-items: flex-end;
        padding: 40px;
    }
    
    .login-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(to top, rgba(204, 0, 0, 0.9), rgba(0,0,0,0.2));
    }

    .login-caption {
        position: relative;
        z-index: 2;
        color: white;
    }

    /* Bagian Form (Kanan) */
    .login-form-side {
        padding: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    /* Input Fields */
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

    /* Tombol Login */
    .btn-login {
        background: linear-gradient(to right, var(--honda-red), var(--honda-dark-red));
        border: none;
        color: white;
        padding: 12px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s;
    }
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(204, 0, 0, 0.3);
        color: white;
    }

    /* Social / Divider (Opsional) */
    .divider-text {
        display: flex;
        align-items: center;
        text-align: center;
        color: #aaa;
        margin: 20px 0;
    }
    .divider-text::before, .divider-text::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #eee;
    }
    .divider-text::before { margin-right: 10px; }
    .divider-text::after { margin-left: 10px; }
</style>

{{-- 1. NAVBAR (Navigasi Kembali ke Home) --}}
<nav class="navbar fixed-top auth-navbar">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ url('/') }}">
            <img src="https://upload.wikimedia.org/wikipedia/commons/3/38/Honda.svg" alt="Honda" height="24" class="me-2">
            <span class="text-danger">AHASS</span> <span class="text-dark">Service</span>
        </a>
        <a href="{{ route('register') }}" class="btn btn-outline-danger rounded-pill px-4 fw-bold small">
            Daftar Akun
        </a>
    </div>
</nav>

{{-- 2. LOGIN CONTENT --}}
<div class="login-wrapper p-3">
    <div class="card login-card">
        <div class="row g-0 h-100">
            
            {{-- Kolom Kiri: Gambar Visual --}}
            <div class="col-lg-6 d-none d-lg-block login-image-side">
                <div class="login-overlay"></div>
                <div class="login-caption">
                    <h3 class="fw-bold display-6 mb-2">Selamat Datang Kembali!</h3>
                    <p class="mb-0 opacity-75">Masuk untuk memantau status servis motor Anda dan nikmati kemudahan booking online.</p>
                </div>
            </div>

            {{-- Kolom Kanan: Form --}}
            <div class="col-lg-6 login-form-side">
                <div class="text-center mb-4">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-user-lock fs-3"></i>
                    </div>
                    <h3 class="fw-bold text-dark">Login Akun</h3>
                    <p class="text-muted small">Silakan masukkan email dan password Anda.</p>
                </div>

                {{-- Alert Error --}}
                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-3 d-flex align-items-center mb-4" role="alert">
                        <i class="fas fa-exclamation-circle me-2 fs-5"></i>
                        <div class="small">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    
                    {{-- Input Email --}}
                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                        <label for="email">Alamat Email</label>
                    </div>

                    {{-- Input Password --}}
                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                        <label for="password">Password</label>
                    </div>

                    {{-- Remember Me & Forgot Password --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label text-muted small" for="remember">
                                Ingat Saya
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-danger small text-decoration-none fw-bold">Lupa Password?</a>
                        @endif
                    </div>

                    {{-- Tombol Login --}}
                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-login shadow">
                            Masuk Sekarang <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>

                    <div class="divider-text small">atau</div>

                    <div class="text-center">
                        <span class="text-muted small">Belum punya akun?</span>
                        <a href="{{ route('register') }}" class="text-danger fw-bold text-decoration-none ms-1">Daftar Sekarang</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection