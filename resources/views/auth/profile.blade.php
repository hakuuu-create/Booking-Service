@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6"> {{-- Sedikit lebih lebar untuk form --}}

            {{-- Menampilkan notifikasi sukses jika ada --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Card dengan styling admin (merah) --}}
            <div class="card shadow-lg border-danger-subtle rounded-4">
                <div class="card-header bg-danger text-white text-center py-3">
                    <h3 class="fw-bold mb-0"><i class="fas fa-user-edit me-2"></i>Edit Profil Pengguna</h3> {{-- Menyesuaikan judul header --}}
                </div>
                <div class="card-body p-4 p-md-5"> {{-- Menambah padding --}}

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <hr class="my-4"> {{-- Pemisah visual --}}

                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru (Opsional)</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password" placeholder="Kosongkan jika tidak ingin ganti">
                             <div class="form-text">Minimal 6 karakter.</div>
                            @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" autocomplete="new-password" placeholder="Ulangi password baru">
                            {{-- Error konfirmasi biasanya ditangani bersama error password --}}
                        </div>

                        <div class="d-grid mt-4">
                            {{-- Mengubah warna tombol menjadi merah (danger) --}}
                            <button type="submit" class="btn btn-danger btn-lg fw-bold">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Memastikan JS Bootstrap dimuat --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection