@extends('layouts.app')

@section('content')
<div class=" container py-5">
    <div class="row justify-content-center">
        <div class=" col-md-8">

            {{-- Notifikasi --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-lg border-danger-subtle rounded-4">
                <div class="card-header bg-danger text-white fw-bold fs-5 text-center">
                    Detail Booking #{{ $booking->id }}
                </div>
                <div class="card-body p-4">
                    {{-- Detail Booking --}}
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Nama Customer:</span>
                            <span class="fw-bold">{{ $booking->customer_name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">No. Polisi:</span>
                            <span class="fw-bold text-uppercase">{{ $booking->plate_number }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Jadwal:</span>
                            <span>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y, H:i') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Jenis Servis:</span>
                            <span>{{ $booking->service->name ?? '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Status Saat Ini:</span>
                            @php
                                $statusClass = [
                                    'pending'     => 'bg-warning text-dark',
                                    'approved'    => 'bg-info text-dark',
                                    'on_progress' => 'bg-primary',
                                    'done'        => 'bg-success',
                                    'cancelled'   => 'bg-danger',
                                ][strtolower($booking->status)] ?? 'bg-secondary';
                            @endphp
                            <span class="badge rounded-pill {{ $statusClass }}">{{ str_replace('_', ' ', ucfirst($booking->status)) }}</span>
                        </li>
                    </ul>

                    {{-- Form Update Status --}}
                    <form action="{{ route('booking.updateStatus', $booking->id) }}" method="POST" class="mt-3">
                        @csrf
                        <label for="status" class="form-label fw-bold">Ubah Status Booking:</label>
                        <div class="input-group">
                            <select name="status" class="form-select">
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $booking->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="on_progress" {{ $booking->status == 'on_progress' ? 'selected' : '' }}>On Progress</option>
                                <option value="done" {{ $booking->status == 'done' ? 'selected' : '' }}>Done</option>
                                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit" class="btn btn-danger">Update</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-light text-center">
                    <a href="{{ route('booking.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Booking
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection