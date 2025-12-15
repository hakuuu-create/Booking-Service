@extends('layouts.app')

@section('content')
{{-- Style kustom untuk tema merah --}}
<style>
    .table-danger-light {
        --bs-table-bg: #f8d7da;
        --bs-table-striped-bg: #f1c2c5;
        --bs-table-hover-bg: #eac4c7;
    }
    .btn-outline-danger:hover {
        color: #fff;
    }
    .page-item.active .page-link {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    .page-link {
        color: #dc3545;
    }
    .page-link:hover {
        color: #a71d2a;
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-danger border-bottom border-danger pb-2 mb-0">
            <i class="fas fa-book-open me-2"></i>Daftar Booking
        </h1>
        <a href="{{ route('booking.create') }}" class="btn btn-danger">
            <i class="fas fa-plus me-2"></i>Booking Baru
        </a>
    </div>

    {{-- FORM PENCARIAN --}}
    {{-- <div class="mb-3">
        <form action="{{ route('booking.index') }}" method="GET" class="d-flex">
            <input type="text" name="search_plate" class="form-control me-2" placeholder="Cari berdasarkan Plat Nomor..." value="{{ request('search_plate') }}">
            <button type="submit" class="btn btn-danger"><i class="fas fa-search"></i> Cari</button>
            {{-- Tombol Reset (opsional) --}}
            {{-- @if(request('search_plate'))
                <a href="{{ route('booking.index') }}" class="btn btn-outline-secondary ms-2">Reset</a>
            @endif
        </form>
    </div>
    {{-- Akhir Form Pencarian --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-lg border-danger-subtle rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                {{-- Menggunakan class table-danger-light untuk tema merah --}}
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-danger">
                        <tr>
                            <th scope="col" class="py-3 px-4">Nama</th>
                            <th scope="col" class="py-3 px-4">Motor</th>
                            <th scope="col" class="py-3 px-4">Tanggal</th>
                            <th scope="col" class="py-3 px-4">Estimasi</th>
                            <th scope="col" class="py-3 px-4 text-center">Status</th>
                            <th scope="col" class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        @php
                            $bookingTime = \Carbon\Carbon::parse($booking->booking_date);
                            $startTime = $bookingTime->copy()->addMinutes(15);
                            $endTime = $bookingTime->copy()->addMinutes(75);
                            $isOver = now()->greaterThan($endTime);
                        @endphp
                        <tr>
                            <td class="px-4 align-middle">{{ $booking->customer_name }}</td>
                            <td class="px-4 align-middle text-uppercase">{{ $booking->vehicle_type }} - {{ $booking->plate_number }}</td>
                            <td class="px-4 align-middle">{{ $bookingTime->format('d M Y, H:i') }}</td>
                            <td class="px-4 align-middle">
                                {{ $startTime->format('H:i') }} - {{ $endTime->format('H:i') }} WIB
                                @if($isOver && !in_array($booking->status, ['done', 'cancelled']))
                                    <br><span class="badge bg-danger mt-1">Lewat Estimasi!</span>
                                @endif
                            </td>
                            <td class="px-4 align-middle text-center">
                                {{-- Form select untuk update status cepat --}}
                                <form action="{{ route('booking.updateStatus', $booking->id) }}" method="POST">
                                    @csrf
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ $booking->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="on_progress" {{ $booking->status == 'on_progress' ? 'selected' : '' }}>On Progress</option>
                                        <option value="done" {{ $booking->status == 'done' ? 'selected' : '' }}>Done</option>
                                        <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-4 align-middle text-center">
                                <a href="{{ route('booking.show', $booking->id) }}" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-search me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Saat ini tidak ada data booking yang tersedia.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($bookings->hasPages())
            <div class="card-footer bg-light border-0">
                {{ $bookings->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection