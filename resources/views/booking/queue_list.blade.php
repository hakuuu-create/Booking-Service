@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 fw-bold border-bottom border-danger pb-2 text-danger">
        <i class="fas fa-stream me-2"></i>Daftar Antrian Hari Ini
    </h1>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-lg border-danger-subtle rounded-4">
        <div class="card-header bg-danger text-white fw-bold py-3">
            Antrian Servis - {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}
        </div>
        <div class="card-body p-0">
            @if($queueBookings->isEmpty())
                <div class="text-center p-5">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h4 class="text-muted">Tidak Ada Antrian</h4>
                    <p class="text-muted">Belum ada booking yang masuk antrian hari ini.</p>
                </div>
            @else
                <div class="table-responsive">
                    {{-- Menambahkan table-hover dan table-striped --}}
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="py-3 px-4 text-center">No. Antrian</th>
                                <th scope="col" class="py-3 px-4">Nama Customer</th>
                                <th scope="col" class="py-3 px-4">Service</th>
                                <th scope="col" class="py-3 px-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Loop data booking asli --}}
                            @foreach($queueBookings as $booking)
                                <tr>
                                    <td class="px-4 text-center">
                                        {{-- Nomor antrian dibuat menonjol dengan badge merah --}}
                                        <span class="badge bg-danger rounded-pill fs-5 px-3 py-2">
                                            #{{ $booking->queue_number }}
                                        </span>
                                    </td>
                                    <td class="px-4 fw-bold">{{ $booking->customer_name }}</td>
                                    <td class="px-4">{{ $booking->service->name ?? '-' }}</td>
                                    <td class="px-4 text-center">
                                        @php
                                            // Logika warna badge status
                                            $statusClass = [
                                                'pending'     => 'bg-warning text-dark',
                                                'approved'    => 'bg-info text-dark',
                                                'on_progress' => 'bg-primary',
                                                // Status 'done' dan 'cancelled' seharusnya tidak muncul di sini
                                                // berdasarkan query controller, tapi ditambahkan untuk jaga-jaga
                                                'done'        => 'bg-success',
                                                'cancelled'   => 'bg-danger',
                                            ][strtolower($booking->status)] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge rounded-pill {{ $statusClass }}">
                                            {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        {{-- Menampilkan Paginasi di dalam Card Footer --}}
        @if ($queueBookings->hasPages())
        <div class="card-footer bg-light border-0">
            {{ $queueBookings->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Memastikan JS Bootstrap dimuat --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection