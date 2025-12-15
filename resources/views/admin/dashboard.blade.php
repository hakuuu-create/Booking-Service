@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<style>
    /* --- STATS CARD MODERN --- */
    .stat-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: none;
        border-radius: 16px;
        background: #fff;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }

    .icon-box {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
    }

    /* --- QUEUE LIST STYLING --- */
    .queue-item {
        transition: background-color 0.2s;
        border-left: 5px solid transparent;
        border-radius: 12px !important;
        margin-bottom: 10px;
        border: 1px solid #f0f0f0;
    }

    .queue-item:hover {
        background-color: #fcfcfc;
        border-color: #e0e0e0;
        border-left-color: #cc0000; /* Honda Red Hover */
        transform: translateX(5px);
    }

    .queue-number-box {
        width: 50px;
        height: 50px;
        background-color: #f8f9fa;
        color: #cc0000;
        font-weight: 800;
        font-size: 1.4rem;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #eee;
    }
</style>

<div class="container-fluid py-4 px-md-4">

    {{-- 1. HEADER SECTION (Greeting) --}}
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h6 class="text-uppercase text-muted small fw-bold mb-1">Overview Laporan</h6>
            <h2 class="fw-bold text-dark m-0">Dashboard Admin</h2>
            <p class="text-muted mb-0 mt-1">
                Halo, {{ Auth::user()->name }}! Berikut statistik bengkel hari ini, 
                <strong>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</strong>.
            </p>
        </div>
        <div class="d-none d-md-block">
            <span class="badge bg-white text-dark border shadow-sm px-3 py-2 rounded-pill">
                <i class="far fa-clock me-2 text-danger"></i> Jam Operasional: 08:00 - 17:00
            </span>
        </div>
    </div>

    {{-- 2. STATS WIDGETS (Grid 3 Kolom) --}}
    <div class="row g-4 mb-5">
        {{-- Card 1: Total Booking --}}
        <div class="col-md-4">
            <div class="card stat-card shadow-sm h-100 p-3">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-danger bg-opacity-10 text-danger me-4">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Total Booking Hari Ini</h6>
                        <h2 class="fw-bolder mb-0 text-dark display-6">{{ $totalBookingsToday ?? 0 }}</h2>
                        <small class="text-success fw-bold"><i class="fas fa-arrow-up me-1"></i> Transaksi</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Menunggu (Pending) --}}
        <div class="col-md-4">
            <div class="card stat-card shadow-sm h-100 p-3">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning me-4">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Perlu Konfirmasi</h6>
                        <h2 class="fw-bolder mb-0 text-dark display-6">{{ $pendingBookings ?? 0 }}</h2>
                        <small class="text-muted">Menunggu persetujuan</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Total Customer --}}
        <div class="col-md-4">
            <div class="card stat-card shadow-sm h-100 p-3">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-info bg-opacity-10 text-info me-4">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase small fw-bold mb-1">Total Pelanggan</h6>
                        <h2 class="fw-bolder mb-0 text-dark display-6">{{ $registeredCustomers ?? 0 }}</h2>
                        <small class="text-info fw-bold">Terdaftar di sistem</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. ACTIVE QUEUE LIST (Antrian) --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow border-0 rounded-4">
                <div class="card-header bg-white border-bottom py-4 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-1 text-dark">
                            <i class="fas fa-stream text-danger me-2"></i>Antrian Servis Hari Ini
                        </h5>
                        <small class="text-muted">Daftar kendaraan yang dijadwalkan masuk hari ini.</small>
                    </div>
                    <a href="{{ route('booking.index') }}" class="btn btn-outline-danger rounded-pill fw-bold btn-sm px-4">
                        Kelola Semua <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="card-body p-4">
                    @if ($queueBookings->isEmpty())
                        <div class="text-center py-5 rounded-3 bg-light border border-dashed">
                            <div class="mb-3">
                                <i class="fas fa-mug-hot fa-3x text-secondary opacity-50"></i>
                            </div>
                            <h5 class="text-secondary fw-bold">Belum Ada Antrian</h5>
                            <p class="text-muted small mb-0">Belum ada booking yang masuk untuk hari ini.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach ($queueBookings as $booking)
                                <a href="{{ route('booking.show', $booking->id) }}" class="list-group-item list-group-item-action queue-item p-3 shadow-sm text-decoration-none">
                                    <div class="row align-items-center">
                                        {{-- Kolom 1: Nomor Antrian --}}
                                        <div class="col-auto">
                                            <div class="queue-number-box">
                                                {{ $booking->queue_number }}
                                            </div>
                                        </div>

                                        {{-- Kolom 2: Info Utama --}}
                                        <div class="col ms-2">
                                            <div class="d-flex align-items-center mb-1">
                                                <h6 class="mb-0 fw-bold text-dark me-2">{{ $booking->customer_name }}</h6>
                                                <span class="badge bg-light text-secondary border fw-normal">{{ $booking->vehicle_type }}</span>
                                            </div>
                                            <div class="text-muted small">
                                                <i class="fas fa-tools me-1 text-danger"></i> {{ $booking->service->name ?? 'Service Umum' }}
                                                <span class="mx-2">•</span>
                                                <i class="far fa-clock me-1"></i> {{ \Carbon\Carbon::parse($booking->booking_date)->format('H:i') }}
                                            </div>
                                        </div>

                                        {{-- Kolom 3: Status & Indikator --}}
                                        <div class="col-auto text-end">
                                            @php
                                                $statusConfig = [
                                                    'pending'     => ['color' => 'warning', 'icon' => 'fa-clock', 'text' => 'Menunggu'],
                                                    'approved'    => ['color' => 'info', 'icon' => 'fa-thumbs-up', 'text' => 'Siap'],
                                                    'on_progress' => ['color' => 'primary', 'icon' => 'fa-cog fa-spin', 'text' => 'Dikerjakan'],
                                                    'done'        => ['color' => 'success', 'icon' => 'fa-check', 'text' => 'Selesai'],
                                                ];
                                                $st = $statusConfig[strtolower($booking->status)] ?? ['color' => 'secondary', 'icon' => 'fa-circle', 'text' => $booking->status];
                                            @endphp
                                            
                                            <div class="d-flex flex-column align-items-end">
                                                <span class="badge bg-{{ $st['color'] }}-subtle text-{{ $st['color'] }}-emphasis rounded-pill border border-{{ $st['color'] }}-subtle px-3 py-2 mb-2">
                                                    <i class="fas {{ $st['icon'] }} me-1"></i> {{ $st['text'] }}
                                                </span>
                                                <small class="text-muted" style="font-size: 0.75rem;">
                                                    Klik untuk detail <i class="fas fa-chevron-right ms-1"></i>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection