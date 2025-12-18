@extends('layouts.app')

@section('title', 'Pengaturan Profil')

@section('content')
<style>
    /* --- Profile Specific Styles --- */
    .profile-cover {
        background: linear-gradient(135deg, var(--honda-red) 0%, #a30000 100%);
        height: 120px;
        border-radius: 16px 16px 0 0;
        position: relative;
    }
    
    .profile-avatar-container {
        position: absolute;
        bottom: -40px;
        left: 50%;
        transform: translateX(-50%);
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: white;
        padding: 4px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-color: #f8f9fa;
        color: var(--honda-red);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 800;
        border: 2px solid #eee;
    }

    .settings-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        overflow: hidden;
    }

    .form-control-modern {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        transition: all 0.2s;
    }
    .form-control-modern:focus {
        background-color: #fff;
        border-color: var(--honda-red);
        box-shadow: 0 0 0 4px rgba(204, 0, 0, 0.1);
    }
    
    .section-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 1rem;
        border-bottom: 1px solid #eee;
        padding-bottom: 0.5rem;
    }
</style>

<div class="container py-5">
    
    {{-- Header Sederhana --}}
    <div class="mb-4">
        <h3 class="fw-bold text-dark m-0">Pengaturan Akun</h3>
        <p class="text-muted small">Kelola informasi profil dan keamanan akun Anda.</p>
    </div>

    {{-- Alert Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center alert-dismissible fade show">
            <div class="bg-success rounded-circle p-2 me-3 text-white d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                <i class="fas fa-check small"></i>
            </div>
            <div><strong>Berhasil!</strong> {{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        
        {{-- KOLOM KIRI: KARTU PROFIL --}}
        <div class="col-lg-4">
            <div class="settings-card bg-white h-100 pb-4">
                {{-- Cover & Avatar --}}
                <div class="profile-cover">
                    <div class="profile-avatar-container">
                        <div class="profile-avatar">
                            <div class="avatar-placeholder">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Info User --}}
                <div class="text-center mt-5 pt-3 px-4">
                    <h5 class="fw-bold text-dark mb-1">{{ $user->name }}</h5>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    
                    <span class="badge {{ $user->role == 'admin' ? 'bg-danger' : 'bg-secondary' }} rounded-pill px-3 py-2 mb-3">
                        <i class="fas {{ $user->role == 'admin' ? 'fa-user-shield' : 'fa-user' }} me-1"></i>
                        {{ ucfirst($user->role) }}
                    </span>

                    <hr class="mx-4 my-3">

                    <div class="row text-center">
                        <div class="col-12">
                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Bergabung Sejak</small>
                            <span class="fw-bold text-dark">
                                {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: FORM EDIT --}}
        <div class="col-lg-8">
            <div class="settings-card bg-white p-4 p-md-5">
                
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    {{-- BAGIAN 1: INFORMASI DASAR --}}
                    <div class="mb-5">
                        <h6 class="section-title"><i class="fas fa-id-card me-2"></i>Informasi Dasar</h6>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-bold small text-secondary">Nama Lengkap</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 border"><i class="fas fa-user text-muted"></i></span>
                                    <input id="name" type="text" class="form-control form-control-modern border-start-0 ps-0 @error('name') is-invalid @enderror" 
                                           name="name" value="{{ old('name', $user->name) }}" required autocomplete="name">
                                </div>
                                @error('name')
                                    <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-bold small text-secondary">Alamat Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 border"><i class="fas fa-envelope text-muted"></i></span>
                                    <input id="email" type="email" class="form-control form-control-modern border-start-0 ps-0 @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">
                                </div>
                                @error('email')
                                    <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Jika Anda sudah menambahkan kolom 'phone' di database, hilangkan komentar di bawah ini --}}
                            {{-- 
                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-secondary">Nomor WhatsApp</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 border"><i class="fab fa-whatsapp text-success"></i></span>
                                    <input type="text" class="form-control form-control-modern border-start-0 ps-0" 
                                           name="phone" value="{{ old('phone', $user->phone ?? '') }}" placeholder="08xxxxxxxxxx">
                                </div>
                            </div> 
                            --}}
                        </div>
                    </div>

                    {{-- BAGIAN 2: KEAMANAN --}}
                    <div class="mb-4">
                        <h6 class="section-title"><i class="fas fa-lock me-2"></i>Keamanan & Password</h6>
                        <div class="alert alert-light border-0 d-flex align-items-center mb-3">
                            <i class="fas fa-info-circle text-muted me-3 fs-4"></i>
                            <div class="small text-muted">
                                Kosongkan kolom password di bawah ini jika Anda tidak ingin mengubah password saat ini.
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-bold small text-secondary">Password Baru</label>
                                <input id="password" type="password" class="form-control form-control-modern @error('password') is-invalid @enderror" 
                                       name="password" autocomplete="new-password" placeholder="Minimal 6 karakter">
                                @error('password')
                                    <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-bold small text-secondary">Konfirmasi Password</label>
                                <input id="password_confirmation" type="password" class="form-control form-control-modern" 
                                       name="password_confirmation" autocomplete="new-password" placeholder="Ulangi password">
                            </div>
                        </div>
                    </div>

                    {{-- TOMBOL AKSI --}}
                    <div class="d-flex justify-content-end pt-3 border-top">
                        <a href="{{ url()->previous() }}" class="btn btn-light rounded-pill px-4 me-2 fw-bold text-secondary">Batal</a>
                        <button type="submit" class="btn btn-danger rounded-pill px-5 fw-bold shadow">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
@endsection