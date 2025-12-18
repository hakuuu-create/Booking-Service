@extends('layouts.app')

@section('title', 'Daftar Booking')

@section('content')
<style>
    :root {
        --honda-red: #CC0000;
        --honda-dark: #1a1a1a;
        --bg-soft: #f8f9fa;
    }

    /* --- Stat Cards --- */
    .stat-card {
        border: none;
        border-radius: 16px;
        transition: transform 0.2s;
        background: white;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px rgba(0,0,0,0.05);
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* --- Modern Table --- */
    .table-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        overflow: hidden;
        border: 1px solid #eee;
    }
    
    .table thead th {
        background-color: #fcfcfc;
        color: #6c757d;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #f0f0f0;
        padding: 1rem 1.5rem;
    }

    .table tbody td {
        padding: 1.2rem 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #f8f9fa;
        color: #495057;
        font-size: 0.95rem;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    .table tbody tr:hover {
        background-color: #fffbfb; /* Merah sangat muda saat hover */
    }

    /* --- Elements --- */
    .avatar-initial {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #ffeaea;
        color: var(--honda-red);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 12px;
    }

    .plate-number {
        font-family: 'Consolas', monospace;
        font-weight: 700;
        background: #212529;
        color: #fff;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.85rem;
        letter-spacing: 1px;
    }

    .status-select {
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        border: 1px solid #e9ecef;
        padding: 0.4rem 1rem;
        cursor: pointer;
    }
    
    .status-select:focus {
        border-color: var(--honda-red);
        box-shadow: 0 0 0 0.2rem rgba(204, 0, 0, 0.1);
    }

    /* Status Colors */
    .status-pending { background-color: #fff3cd; color: #856404; }
    .status-approved { background-color: #cff4fc; color: #055160; }
    .status-on_progress { background-color: #cfe2ff; color: #084298; }
    .status-done { background-color: #d1e7dd; color: #0f5132; }
    .status-cancelled { background-color: #e2e3e5; color: #41464b; }
</style>

<div class="container-fluid px-4 py-4">

    {{-- 1. STATISTIK RINGKAS (Dashboard Mini) --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card p-3 d-flex align-items-center">
                <div class="stat-icon bg-danger bg-opacity-10 text-danger me-3">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Total Booking</small>
                    <h4 class="m-0 fw-bold">{{ $bookings->total() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card p-3 d-flex align-items-center">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning me-3">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Menunggu (Pending)</small>
                    {{-- Note: Jika ingin hitungan real, sebaiknya dipass dari controller, ini contoh static dari data yg ada di page --}}
                    <h4 class="m-0 fw-bold">{{ \App\Models\Booking::where('status', 'pending')->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card p-3 d-flex align-items-center">
                <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                    <i class="fas fa-check-double"></i>
                </div>
                <div>
                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Selesai Hari Ini</small>
                    <h4 class="m-0 fw-bold">{{ \App\Models\Booking::where('status', 'done')->whereDate('updated_at', now())->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <a href="{{ route('booking.create') }}" class="btn btn-danger w-100 h-100 d-flex align-items-center justify-content-center rounded-4 shadow fw-bold">
                <i class="fas fa-plus-circle me-2 fa-lg"></i> Buat Booking Baru
            </a>
        </div>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center alert-dismissible fade show">
            <div class="bg-success rounded-circle p-2 me-3 text-white d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                <i class="fas fa-check small"></i>
            </div>
            <div>
                <strong>Berhasil!</strong> {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 2. HEADER & FILTER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-end align-items-md-center mb-3">
        <div>
            <h4 class="fw-bold m-0 text-dark">Data Booking Service</h4>
            <p class="text-muted small m-0">Kelola antrian dan jadwal servis pelanggan.</p>
        </div>
        
        <form action="{{ route('booking.index') }}" method="GET" class="mt-3 mt-md-0">
            <div class="input-group shadow-sm rounded-pill overflow-hidden bg-white border">
                <span class="input-group-text bg-white border-0 ps-3"><i class="fas fa-search text-muted"></i></span>
                <input type="text" name="search_plate" class="form-control border-0 shadow-none" 
                       placeholder="Cari Plat Nomor / Nama..." value="{{ request('search_plate') }}" style="min-width: 250px;">
                <button type="submit" class="btn btn-dark px-4 rounded-pill m-1">Cari</button>
            </div>
        </form>
    </div>

    {{-- 3. TABEL DATA --}}
    <div class="table-container">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th width="25%">Pelanggan</th>
                        <th width="20%">Kendaraan</th>
                        <th width="20%">Waktu Booking</th>
                        <th width="15%" class="text-center">Status Pengerjaan</th>
                        <th width="10%" class="text-center">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    @php
                        $bookingTime = \Carbon\Carbon::parse($booking->booking_date);
                        $startTime = $bookingTime->copy()->addMinutes(15);
                        $endTime = $bookingTime->copy()->addMinutes(75);
                        $isOver = now()->greaterThan($endTime);
                        
                        // Warna Status untuk Dropdown
                        $statusClass = match($booking->status) {
                            'pending' => 'status-pending',
                            'approved' => 'status-approved',
                            'on_progress' => 'status-on_progress',
                            'done' => 'status-done',
                            'cancelled' => 'status-cancelled',
                            default => ''
                        };
                    @endphp
                    <tr>
                        {{-- Kolom Pelanggan --}}
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-initial flex-shrink-0">
                                    {{ substr($booking->customer_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $booking->customer_name }}</div>
                                    <div class="small text-muted"><i class="fab fa-whatsapp text-success me-1"></i> {{ $booking->customer_whatsapp ?? '-' }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Kolom Kendaraan --}}
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="me-3 text-secondary opacity-50">
                                    
                                </div>
                                <div>
                                    <span class="plate-number">{{ $booking->plate_number }}</span>
                                    <div class="small text-muted mt-1 text-uppercase fw-bold">{{ $booking->vehicle_type }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Kolom Waktu --}}
                        <td>
                            <div class="fw-bold text-dark">{{ $bookingTime->format('d M Y') }}</div>
                            <div class="small text-secondary">
                                <i class="far fa-clock me-1"></i> {{ $bookingTime->format('H:i') }} WIB
                            </div>
                            
                            {{-- Alert Estimasi --}}
                            @if($isOver && !in_array($booking->status, ['done', 'cancelled']))
                                <div class="badge bg-danger bg-opacity-10 text-danger mt-1 border border-danger border-opacity-25">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Lewat Estimasi
                                </div>
                            @endif
                        </td>

                        {{-- Kolom Status (Dropdown Langsung) --}}
                        <td class="text-center">
                            <form action="{{ route('booking.updateStatus', $booking->id) }}" method="POST">
                                @csrf
                                <select name="status" class="form-select form-select-sm status-select {{ $statusClass }}" onchange="this.form.submit()">
                                    <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $booking->status == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="on_progress" {{ $booking->status == 'on_progress' ? 'selected' : '' }}>Dikerjakan</option>
                                    <option value="done" {{ $booking->status == 'done' ? 'selected' : '' }}>Selesai</option>
                                    <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Batal</option>
                                </select>
                            </form>
                        </td>

                        {{-- Kolom Aksi --}}
                        <td class="text-center">
                            <a href="{{ route('booking.show', $booking->id) }}" class="btn btn-light text-secondary btn-sm rounded-circle shadow-sm" title="Lihat Detail" data-bs-toggle="tooltip">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                            
                            {{-- Tombol Advisor (Jika Status Approved/On Progress) --}}
                            @if(in_array($booking->status, ['approved', 'on_progress']))
                            <a href="{{ route('advisor.show', $booking->id) }}" class="btn btn-danger btn-sm rounded-circle shadow-sm ms-1" title="Proses Servis">
                                <i class="fas fa-tools"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-muted opacity-50 mb-3">
                                <i class="fas fa-clipboard-list fa-4x"></i>
                            </div>
                            <h6 class="fw-bold text-secondary">Belum ada data booking.</h6>
                            <p class="text-muted small">Silakan buat booking baru atau gunakan filter pencarian lain.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    @if ($bookings->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $bookings->links() }}
    </div>
    @endif

</div>
@endsection