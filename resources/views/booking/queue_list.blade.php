@extends('layouts.app')

@section('title', 'Daftar Antrian Hari Ini')

@section('content')
<style>
    /* --- Modern Queue Styles --- */
    .queue-header-card {
        background: linear-gradient(135deg, var(--honda-red) 0%, #a30000 100%);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: 0 10px 20px rgba(204, 0, 0, 0.2);
    }
    
    .queue-stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        transition: transform 0.2s;
        display: flex;
        align-items: center;
    }
    .queue-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px rgba(0,0,0,0.05);
    }

    .stat-icon-wrapper {
        width: 60px; height: 60px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.75rem;
        margin-right: 1rem;
    }

    .queue-table-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .queue-badge-lg {
        font-size: 1.1rem;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 700;
        min-width: 60px;
        display: inline-block;
        text-align: center;
        background: #f8f9fa;
        color: var(--honda-dark);
        border: 2px solid #e9ecef;
    }

    /* Status Colors */
    .status-dot {
        height: 10px; width: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
    }
    .dot-pending { background-color: #ffc107; }
    .dot-approved { background-color: #0dcaf0; }
    .dot-on_progress { background-color: #0d6efd; box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.2); }
    
    /* Current Date Badge */
    .date-badge {
        background: rgba(255,255,255,0.2);
        padding: 6px 16px;
        border-radius: 50px;
        backdrop-filter: blur(4px);
        font-weight: 500;
        font-size: 0.9rem;
    }
</style>

<div class="container-fluid py-4 px-4">
    
    {{-- 1. HEADER UTAMA (HERO SECTION) --}}
    <div class="queue-header-card d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div class="position-relative z-1">
            <div class="d-inline-flex align-items-center date-badge mb-3">
                <i class="far fa-calendar-alt me-2"></i>
                {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}
            </div>
            <h2 class="fw-bold mb-1"><i class="fas fa-stream me-2"></i>Antrian Service</h2>
            <p class="m-0 opacity-75">Pantau antrian kendaraan pelanggan secara real-time.</p>
        </div>
        
        {{-- Hiasan Background Absrak --}}
        <i class="fas fa-clipboard-list position-absolute text-white opacity-10" style="font-size: 10rem; right: -20px; bottom: -30px; transform: rotate(-15deg);"></i>
    </div>

    {{-- 2. RINGKASAN STATUS (STATISTICS) --}}
    <div class="row g-4 mb-4">
        {{-- Total Antrian --}}
        <div class="col-md-4">
            <div class="queue-stat-card">
                <div class="stat-icon-wrapper bg-danger bg-opacity-10 text-danger">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark m-0">{{ $queueBookings->total() }}</h5>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Total Antrian</small>
                </div>
            </div>
        </div>
        
        {{-- Sedang Dikerjakan --}}
        <div class="col-md-4">
            <div class="queue-stat-card">
                <div class="stat-icon-wrapper bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-tools"></i>
                </div>
                <div>
                    {{-- Hitung manual untuk demo, idealnya dari controller --}}
                    <h5 class="fw-bold text-dark m-0">
                        {{ $queueBookings->where('status', 'on_progress')->count() }}
                    </h5>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Sedang Dikerjakan</small>
                </div>
            </div>
        </div>

        {{-- Menunggu --}}
        <div class="col-md-4">
            <div class="queue-stat-card">
                <div class="stat-icon-wrapper bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark m-0">
                        {{ $queueBookings->where('status', 'pending')->count() }}
                    </h5>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Menunggu Giliran</small>
                </div>
            </div>
        </div>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center alert-dismissible fade show">
            <div class="bg-success rounded-circle p-2 me-3 text-white d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                <i class="fas fa-check small"></i>
            </div>
            <div><strong>Berhasil!</strong> {{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 3. TABEL ANTRIAN MODERN --}}
    <div class="card queue-table-card">
        <div class="card-header bg-white py-3 px-4 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="m-0 fw-bold text-dark">Daftar Urutan</h5>
                
                {{-- Legend Status Kecil --}}
                <div class="d-none d-md-flex gap-3 small">
                    <div class="d-flex align-items-center"><span class="status-dot dot-on_progress"></span> Dikerjakan</div>
                    <div class="d-flex align-items-center"><span class="status-dot dot-approved"></span> Persiapan</div>
                    <div class="d-flex align-items-center"><span class="status-dot dot-pending"></span> Menunggu</div>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            @if($queueBookings->isEmpty())
                <div class="text-center py-5">
                    <div class="bg-light rounded-circle d-inline-flex p-4 mb-3 text-secondary">
                        <i class="fas fa-clipboard-check fa-4x"></i>
                    </div>
                    <h5 class="fw-bold text-secondary">Antrian Kosong</h5>
                    <p class="text-muted">Belum ada kendaraan yang masuk antrian hari ini.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 px-4 text-center" width="10%">No.</th>
                                <th class="py-3 px-4" width="25%">Pelanggan</th>
                                <th class="py-3 px-4" width="25%">Kendaraan</th>
                                <th class="py-3 px-4" width="20%">Layanan</th>
                                <th class="py-3 px-4 text-center" width="20%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($queueBookings as $booking)
                                @php
                                    $rowClass = $booking->status == 'on_progress' ? 'bg-primary bg-opacity-10' : '';
                                    $numClass = $booking->status == 'on_progress' ? 'bg-primary text-white border-primary' : '';
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    {{-- Nomor Antrian --}}
                                    <td class="px-4 text-center">
                                        <span class="queue-badge-lg {{ $numClass }}">
                                            {{ $booking->queue_number }}
                                        </span>
                                    </td>
                                    
                                    {{-- Pelanggan --}}
                                    <td class="px-4">
                                        <div class="fw-bold text-dark">{{ $booking->customer_name }}</div>
                                        <div class="small text-muted">
                                            <i class="far fa-clock me-1"></i> {{ \Carbon\Carbon::parse($booking->booking_date)->format('H:i') }} WIB
                                        </div>
                                    </td>

                                    {{-- Kendaraan --}}
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded p-2 me-3 text-secondary border">
                                                <i class="fas fa-motorcycle"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark font-monospace">{{ $booking->plate_number }}</div>
                                                <div class="small text-muted text-uppercase">{{ $booking->vehicle_type }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Layanan --}}
                                    <td class="px-4">
                                        <span class="text-secondary fw-medium">{{ $booking->service->name ?? '-' }}</span>
                                    </td>

                                    {{-- Status Badge Modern --}}
                                    <td class="px-4 text-center">
                                        @php
                                            $statusConfig = [
                                                'pending'     => ['color' => 'warning', 'label' => 'Menunggu', 'icon' => 'fa-clock'],
                                                'approved'    => ['color' => 'info', 'label' => 'Persiapan', 'icon' => 'fa-check'],
                                                'on_progress' => ['color' => 'primary', 'label' => 'Dikerjakan', 'icon' => 'fa-tools fa-spin'],
                                            ];
                                            $config = $statusConfig[$booking->status] ?? ['color' => 'secondary', 'label' => $booking->status, 'icon' => 'fa-info'];
                                        @endphp
                                        
                                        <span class="badge rounded-pill bg-{{ $config['color'] }} bg-opacity-10 text-{{ $config['color'] }} border border-{{ $config['color'] }} px-3 py-2">
                                            <i class="fas {{ $config['icon'] }} me-1"></i> {{ $config['label'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        @if ($queueBookings->hasPages())
            <div class="card-footer bg-white border-top py-3 d-flex justify-content-center">
                {{ $queueBookings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection