@extends('layouts.app')

@section('title', 'Detail Booking #' . $booking->id)

@section('content')
<style>
    /* --- Styles Khusus Halaman Detail --- */
    .detail-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        overflow: hidden;
        background: white;
    }
    
    /* Header Gelap untuk Kartu Pelanggan */
    .card-header-dark {
        background: linear-gradient(135deg, #212529 0%, #343a40 100%);
        color: white;
        padding: 1.25rem 1.5rem;
    }

    .detail-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #888;
        font-weight: 700;
        margin-bottom: 0.3rem;
    }
    .detail-value {
        font-size: 1.05rem;
        color: #212529;
        font-weight: 600;
    }
    
    /* Plate Number Badge */
    .plate-badge {
        background: #000;
        color: #fff;
        padding: 6px 12px;
        border-radius: 8px;
        font-family: 'Consolas', monospace;
        letter-spacing: 1px;
        border: 2px solid #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        display: inline-block;
    }

    /* Status Badges */
    .status-badge-lg {
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }
    .status-pending { background-color: #fff3cd; color: #856404; }
    .status-approved { background-color: #cff4fc; color: #055160; }
    .status-on_progress { background-color: #cfe2ff; color: #084298; }
    .status-done { background-color: #d1e7dd; color: #0f5132; }
    .status-cancelled { background-color: #e2e3e5; color: #41464b; }

    /* Timeline Stepper */
    .timeline-step {
        text-align: center;
        position: relative;
        flex: 1;
    }
    .timeline-dot {
        width: 30px; height: 30px;
        background: #e9ecef;
        color: #adb5bd;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 8px auto;
        font-size: 0.8rem;
        z-index: 2; position: relative;
    }
    .timeline-step.active .timeline-dot {
        background: var(--honda-red);
        color: white;
        box-shadow: 0 0 0 4px rgba(204, 0, 0, 0.15);
    }
    .timeline-step.completed .timeline-dot {
        background: #198754;
        color: white;
    }
    .timeline-line {
        position: absolute;
        top: 15px;
        left: 0; right: 0;
        height: 2px;
        background: #e9ecef;
        z-index: 1;
    }
    .timeline-step:first-child .timeline-line { left: 50%; }
    .timeline-step:last-child .timeline-line { right: 50%; }
</style>

<div class="container py-4">
    
    {{-- HEADER HALAMAN --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <div class="text-muted small fw-bold text-uppercase mb-1">
                <i class="fas fa-hashtag me-1"></i>Booking ID: {{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
            </div>
            <h2 class="fw-bold text-dark m-0">Detail Pengerjaan</h2>
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('booking.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
            <button class="btn btn-dark rounded-pill px-4 fw-bold" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Cetak
            </button>
            @if(in_array($booking->status, ['approved', 'on_progress']))
                <a href="{{ route('advisor.show', $booking->id) }}" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">
                    <i class="fas fa-tools me-2"></i>Proses Servis
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        
        {{-- ================= KOLOM KIRI (DATA PELANGGAN & STATUS) ================= --}}
        <div class="col-lg-4">
            
            {{-- 1. KARTU DATA PELANGGAN --}}
            <div class="detail-card mb-4">
                <div class="card-header-dark">
                    <h6 class="m-0 fw-bold"><i class="fas fa-user-circle me-2"></i>Data Pelanggan</h6>
                </div>
                <div class="p-4">
                    {{-- Avatar & Nama --}}
                    <div class="d-flex align-items-center mb-4 pb-4 border-bottom">
                        <div class="bg-light rounded-circle p-3 me-3 text-secondary border">
                            <i class="fas fa-user fa-lg"></i>
                        </div>
                        <div>
                            <div class="detail-label">Nama Pemilik</div>
                            <div class="detail-value">{{ $booking->customer_name }}</div>
                        </div>
                    </div>

                    {{-- Info Lain --}}
                    <div class="mb-3">
                        <div class="detail-label">Kontak WhatsApp</div>
                        <div class="d-flex align-items-center mt-1">
                            <span class="me-2 fw-bold">{{ $booking->customer_whatsapp ?? '-' }}</span>
                            @if($booking->customer_whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $booking->customer_whatsapp)) }}" 
                                   target="_blank" class="btn btn-success btn-sm rounded-pill px-3">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="detail-label">Plat Nomor</div>
                            <div class="mt-1"><span class="plate-badge">{{ $booking->plate_number }}</span></div>
                        </div>
                        <div class="col-6">
                            <div class="detail-label">Tipe</div>
                            <div class="mt-1 fw-bold text-uppercase">{{ $booking->vehicle_type }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. KARTU STATUS PENGERJAAN (Dipindah ke Bawah Pelanggan) --}}
            <div class="detail-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-secondary m-0">Status Pengerjaan</h6>
                </div>
                
                @php
                    $statusClass = match($booking->status) {
                        'pending' => 'status-pending',
                        'approved' => 'status-approved',
                        'on_progress' => 'status-on_progress',
                        'done' => 'status-done',
                        'cancelled' => 'status-cancelled',
                        default => ''
                    };
                    $iconStatus = match($booking->status) {
                        'pending' => 'fa-clock',
                        'approved' => 'fa-check-circle',
                        'on_progress' => 'fa-cog fa-spin',
                        'done' => 'fa-flag-checkered',
                        'cancelled' => 'fa-times-circle',
                        default => 'fa-info-circle'
                    };
                @endphp

                <div class="text-center py-3 bg-light rounded-3 mb-3">
                    <div class="status-badge-lg {{ $statusClass }}">
                        <i class="fas {{ $iconStatus }} me-2"></i> {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                    </div>
                </div>

                <form action="{{ route('booking.updateStatus', $booking->id) }}" method="POST">
                    @csrf
                    <label class="detail-label mb-2">Update Status:</label>
                    <div class="input-group">
                        <select name="status" class="form-select">
                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $booking->status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="on_progress" {{ $booking->status == 'on_progress' ? 'selected' : '' }}>On Progress</option>
                            <option value="done" {{ $booking->status == 'done' ? 'selected' : '' }}>Done</option>
                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <button type="submit" class="btn btn-dark"><i class="fas fa-save"></i></button>
                    </div>
                </form>
            </div>

        </div>

        {{-- ================= KOLOM KANAN (INFORMASI LAYANAN) ================= --}}
        <div class="col-lg-8">
            <div class="detail-card h-100 p-4">
                <h5 class="fw-bold text-dark mb-4 pb-2 border-bottom">
                    <i class="fas fa-clipboard-list text-danger me-2"></i>Informasi Layanan
                </h5>
                
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <div class="detail-label">Jenis Layanan</div>
                        <div class="detail-value text-danger fs-5">
                            {{ $booking->service->name ?? 'Layanan Tidak Ditemukan' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Estimasi Biaya Awal</div>
                        <div class="detail-value">
                            Rp {{ number_format($booking->service->price ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Tanggal Booking</div>
                        <div class="detail-value">
                            <i class="far fa-calendar-alt me-2 text-muted"></i>
                            {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-label">Jam Kedatangan</div>
                        <div class="detail-value">
                            <i class="far fa-clock me-2 text-muted"></i>
                            {{ \Carbon\Carbon::parse($booking->booking_date)->format('H:i') }} WIB
                        </div>
                    </div>
                </div>

                {{-- Timeline Stepper --}}
                <div class="bg-light p-4 rounded-4 mt-auto">
                    <h6 class="fw-bold text-secondary mb-4 text-center">Timeline Pengerjaan</h6>
                    <div class="d-flex justify-content-between position-relative px-2">
                        @php 
                            $steps = ['pending', 'approved', 'on_progress', 'done']; 
                            $currentIndex = array_search($booking->status, $steps);
                            if($currentIndex === false && $booking->status == 'cancelled') $currentIndex = -1;
                        @endphp

                        @foreach($steps as $index => $step)
                            @php
                                $isCompleted = $index <= $currentIndex;
                                $isActive = $index == $currentIndex;
                                $stepLabel = match($step) {
                                    'pending' => 'Masuk',
                                    'approved' => 'Disetujui',
                                    'on_progress' => 'Servis',
                                    'done' => 'Selesai',
                                };
                            @endphp
                            <div class="timeline-step {{ $isActive ? 'active' : '' }} {{ $isCompleted ? 'completed' : '' }}">
                                <div class="timeline-line"></div>
                                <div class="timeline-dot">
                                    <i class="fas {{ $isCompleted ? 'fa-check' : 'fa-circle' }}"></i>
                                </div>
                                <div class="mt-2 small fw-bold {{ $isActive ? 'text-dark' : 'text-muted' }}">{{ $stepLabel }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection