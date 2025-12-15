@extends('layouts.app')

@section('title', 'Riwayat Booking Customer')

@section('content')
<style>
    /* Styling khusus untuk Plat Nomor */
    .license-plate {
        font-family: 'Consolas', 'Monaco', monospace;
        background-color: #1a1a1a; /* Hitam Plat */
        color: #fff;
        padding: 4px 10px;
        border-radius: 6px;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        font-weight: 700;
        letter-spacing: 1px;
        display: inline-block;
        min-width: 100px;
        text-align: center;
    }

    .table-admin thead th {
        background-color: #f8f9fa;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 15px;
        border-bottom: 2px solid #eee;
    }

    .table-admin tbody td {
        vertical-align: middle;
        padding: 15px;
        color: #444;
    }
    
    .status-badge {
        font-size: 0.8rem;
        padding: 6px 12px;
        border-radius: 30px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
</style>

<div class="container py-5">
    
    {{-- HEADER & NAVIGATION --}}
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <a href="{{ route('customers.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block hover-danger">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Pelanggan
            </a>
            <h2 class="fw-bold text-dark">
                <i class="fas fa-history text-danger me-2"></i>Riwayat Servis
            </h2>
            <p class="text-muted mb-0">Daftar lengkap riwayat booking untuk pelanggan ini.</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <div class="card d-inline-block border-0 shadow-sm bg-danger text-white">
                <div class="card-body py-2 px-4">
                    <span class="d-block small opacity-75">Total Transaksi</span>
                    <span class="fs-4 fw-bold">{{ $bookings->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-body p-0">
            @if($bookings->isEmpty())
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-clipboard-list fa-3x text-light"></i>
                    </div>
                    <h5 class="fw-bold text-secondary">Tidak ada data booking</h5>
                    <p class="text-muted">Pelanggan ini belum pernah melakukan booking servis.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-admin table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">No. Polisi</th>
                                <th>Jadwal Booking</th>
                                <th>Layanan / Keluhan</th>
                                <th>Status Pengerjaan</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                                <tr>
                                    {{-- Kolom Plat Nomor --}}
                                    <td class="ps-4">
                                        <div class="license-plate">
                                            {{ strtoupper($booking->plate_number) }}
                                        </div>
                                    </td>

                                    {{-- Kolom Tanggal --}}
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded p-2 me-3 text-center border" style="min-width: 50px;">
                                                <small class="d-block fw-bold text-danger">{{ \Carbon\Carbon::parse($booking->booking_date)->format('M') }}</small>
                                                <span class="fs-5 fw-bold text-dark">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d') }}</span>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l') }}</div>
                                                <small class="text-muted">
                                                    <i class="far fa-clock me-1"></i>{{ \Carbon\Carbon::parse($booking->booking_date)->format('H:i') }} WIB
                                                </small>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Kolom Service --}}
                                    <td>
                                        <span class="d-block fw-bold text-dark">{{ $booking->service->name ?? 'Service Umum' }}</span>
                                        @if($booking->complaint)
                                            <small class="text-muted d-block text-truncate" style="max-width: 200px;" title="{{ $booking->complaint }}">
                                                <i class="fas fa-comment-alt me-1 text-secondary"></i> "{{ $booking->complaint }}"
                                            </small>
                                        @endif
                                    </td>

                                    {{-- Kolom Status --}}
                                    <td>
                                        @php
                                            $statusStyle = [
                                                'pending'     => ['bg' => 'bg-warning-subtle', 'text' => 'text-warning-emphasis', 'icon' => 'fa-hourglass-half'],
                                                'approved'    => ['bg' => 'bg-info-subtle', 'text' => 'text-info-emphasis', 'icon' => 'fa-calendar-check'],
                                                'on_progress' => ['bg' => 'bg-primary-subtle', 'text' => 'text-primary-emphasis', 'icon' => 'fa-tools fa-spin'],
                                                'done'        => ['bg' => 'bg-success-subtle', 'text' => 'text-success-emphasis', 'icon' => 'fa-check-circle'],
                                                'cancelled'   => ['bg' => 'bg-danger-subtle', 'text' => 'text-danger-emphasis', 'icon' => 'fa-ban'],
                                            ];
                                            $curr = $statusStyle[strtolower($booking->status)] ?? ['bg' => 'bg-secondary', 'text' => 'text-white', 'icon' => 'fa-question'];
                                        @endphp
                                        
                                        <span class="status-badge {{ $curr['bg'] }} {{ $curr['text'] }}">
                                            <i class="fas {{ $curr['icon'] }}"></i> {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                        </span>
                                    </td>

                                    {{-- Kolom Aksi --}}
                                    <td class="text-end pe-4">
                                        <a href="{{ route('booking.history.detail', $booking->id) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-bold shadow-sm">
                                            Detail <i class="fas fa-chevron-right ms-1"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        
        {{-- Pagination (Jika ada) --}}
        @if(method_exists($bookings, 'links'))
            <div class="card-footer bg-white py-3">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection