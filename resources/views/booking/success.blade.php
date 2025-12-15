@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-sm border-success rounded-4 text-center">
                <div class="card-header bg-success text-white py-3">
                    <h3 class="fw-bold mb-0"><i class="fas fa-check-circle me-2"></i>Booking Berhasil!</h3>
                </div>
                <div class="card-body p-4 p-md-5">

                    <h5 class="mb-3">Nomor Antrian Anda:</h5>
                    {{-- Tampilkan Nomor Antrian Besar --}}
                    <h1 class="display-1 fw-bolder text-danger mb-4">{{ $booking->queue_number }}</h1>

                    {{-- Detail Booking --}}
                    <div class="card bg-light border-0 rounded-3 mb-4 text-start">
                        <div class="card-body small">
                            <p class="mb-2"><strong><i class="fas fa-wrench me-2 text-muted"></i>Jenis Service:</strong> {{ $booking->service->name }}</p>
                            @php
                                $bookingTime = \Carbon\Carbon::parse($booking->booking_date);
                                // Hitung estimasi selesai (misal 75 menit dari waktu booking)
                                $endTime = $bookingTime->copy()->addMinutes(75)->format('H:i');
                            @endphp
                            <p class="mb-2"><strong><i class="fas fa-clock me-2 text-muted"></i>Waktu Booking:</strong> {{ $bookingTime->format('d M Y, H:i') }} WIB</p>
                            <p class="mb-0"><strong><i class="fas fa-hourglass-half me-2 text-muted"></i>Estimasi Layanan:</strong> {{ $bookingTime->format('H:i') }} - {{ $endTime }} WIB</p>
                        </div>
                    </div>

                    {{-- Status Booking --}}
                    <p class="mb-4">
                        <span class="text-muted me-2">Status:</span>
                        @php
                            $statusClass = [
                                'pending'     => 'bg-warning text-dark',
                                'approved'    => 'bg-info text-dark',
                                'on_progress' => 'bg-primary',
                                'done'        => 'bg-success',
                                'cancelled'   => 'bg-danger',
                            ][strtolower($booking->status)] ?? 'bg-secondary';
                        @endphp
                        <span class="badge rounded-pill fs-6 {{ $statusClass }}">
                            {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                        </span>
                    </p>

                    <p class="text-muted small mb-4">
                        Silakan datang ~15 menit sebelum waktu estimasi layanan.<br>
                        Notifikasi WhatsApp akan dikirimkan saat giliran Anda mendekat.
                    </p>

                    {{-- Tombol Aksi --}}
                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                        {{-- <a href="{{ route('booking.queue') }}" class="btn btn-outline-danger">Lihat Daftar Antrian</a> --}}
                        <a href="{{ route('booking.queue') }}" class="btn btn-primary">Lihat Daftar Antrian</a>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-danger">Kembali ke Dashboard</a>
                        {{-- Mengarahkan ke dashboard customer --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Memastikan Font Awesome dan JS Bootstrap dimuat --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLMDJzLlQ36k2UaYJtU378F7xXvP+sN4fFw/U6/A2uB4aU+M+F1U+A9P7y+W+W+Z+Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection