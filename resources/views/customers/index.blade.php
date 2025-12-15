@extends('layouts.app')

@section('title', 'Manajemen Customer')

@section('content')

<style>
    /* Styling khusus untuk Avatar Inisial */
    .avatar-circle {
        width: 40px;
        height: 40px;
        background-color: #f8f9fa;
        color: #cc0000;
        font-weight: 700;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        border: 2px solid #ffecec;
    }

    /* Table Styling Modern */
    .table-modern thead th {
        background-color: #f8f9fa;
        color: #6c757d;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #eee;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }

    .table-modern tbody td {
        vertical-align: middle;
        padding-top: 1rem;
        padding-bottom: 1rem;
        color: #495057;
    }

    .table-hover tbody tr:hover {
        background-color: #fff5f5; /* Highlight merah sangat muda saat hover */
    }
    
    .search-input {
        border-radius: 20px;
        border: 1px solid #ddd;
        padding-left: 20px;
    }
    .search-input:focus {
        border-color: #cc0000;
        box-shadow: 0 0 0 0.2rem rgba(204, 0, 0, 0.15);
    }
</style>

<div class="container-fluid py-4 px-md-5">

    {{-- HEADER SECTION --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="fw-bold text-dark m-0">
                <i class="fas fa-users text-danger me-2"></i>Data Pelanggan
            </h2>
            <p class="text-muted small mb-0 mt-1">Kelola data pelanggan dan riwayat servis mereka.</p>
        </div>
        
        {{-- Search Bar (Visual UI) --}}
        <div class="mt-3 mt-md-0">
            <form action="" method="GET" class="d-flex">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 rounded-start-pill text-muted">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 rounded-end-pill ps-0" placeholder="Cari nama atau email..." style="max-width: 250px;">
                </div>
            </form>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    @if ($customers->isEmpty())
        <div class="card shadow border-0 rounded-4">
            <div class="card-body text-center p-5">
                <div class="mb-3">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-user-slash fa-2x text-muted"></i>
                    </div>
                </div>
                <h5 class="fw-bold text-secondary">Belum ada data pelanggan</h5>
                <p class="text-muted mb-0">Data pelanggan akan muncul setelah mereka melakukan registrasi.</p>
            </div>
        </div>
    @else
        <div class="card shadow border-0 rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4" width="5%">No</th>
                                <th width="30%">Nama Lengkap</th>
                                <th width="25%">Kontak</th>
                                <th class="text-center" width="15%">Statistik</th>
                                <th class="text-end pe-4" width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $index => $customer)
                                <tr>
                                    <td class="ps-4 text-muted fw-bold">{{ $loop->iteration + ($customers->firstItem() ? $customers->firstItem() - 1 : 0) }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            {{-- Avatar Initials --}}
                                            <div class="avatar-circle me-3 shadow-sm">
                                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $customer->name }}</div>
                                                <div class="small text-muted">Member sejak {{ $customer->created_at->format('M Y') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="mb-1"><i class="far fa-envelope text-secondary me-2" style="width:15px"></i>{{ $customer->email }}</span>
                                            
                                            @php
                                                $whatsappNumber = '-';
                                                if($customer->bookings->isNotEmpty()) {
                                                    $whatsappNumber = $customer->bookings->first()->customer_whatsapp;
                                                }
                                            @endphp

                                            @if($whatsappNumber != '-')
                                                <span class="text-success small fw-medium">
                                                    <i class="fab fa-whatsapp me-2" style="width:15px"></i>{{ $whatsappNumber }}
                                                </span>
                                            @else
                                                <span class="text-muted small fst-italic ms-1">
                                                    <i class="fas fa-phone-slash me-2" style="width:15px"></i>No. WA belum ada
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="p-2 rounded bg-light border d-inline-block">
                                            <div class="small text-muted text-uppercase" style="font-size: 0.7rem;">Total Booking</div>
                                            <div class="fw-bold text-danger fs-5">{{ $customer->bookings->count() }}</div>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        @if ($customer->bookings->isNotEmpty())
                                            <a href="{{ route('customers.bookings', ['email' => $customer->email, 'whatsapp' => $whatsappNumber]) }}" 
                                               class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold"
                                               data-bs-toggle="tooltip" title="Lihat Riwayat Servis">
                                                <i class="fas fa-history me-1"></i> Detail
                                            </a>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2 rounded-pill">
                                                <small>Belum Aktif</small>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination Footer --}}
            @if ($customers->hasPages())
            <div class="card-footer bg-white border-top-0 py-3">
                <div class="d-flex justify-content-end">
                    {{ $customers->links() }}
                </div>
            </div>
            @endif
        </div>
    @endif
</div>
@endsection