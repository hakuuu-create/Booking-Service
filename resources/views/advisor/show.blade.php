@extends('layouts.app')

@section('title', 'Work Order #' . $booking->id)

@section('content')
<div class="container-fluid py-4 px-4">

    {{-- HEADER & STEPPER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold m-0 text-dark">Work Order (PKB)</h4>
            <small class="text-muted">No. Referensi: #BOOK-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-light border fw-bold text-muted">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <button type="button" class="btn btn-dark fw-bold" onclick="window.print()">
                <i class="fas fa-print me-1"></i> Cetak Invoice
            </button>
        </div>
    </div>

    {{-- KOMPONEN STEPPER (Status Pengerjaan) --}}
    <x-advisor.progress-stepper :status="$booking->status" />

    <form action="{{ route('advisor.update', $booking->id) }}" method="POST" id="workOrderForm">
        @csrf
        @method('PUT')

        <div class="row g-4 mt-2">
            
            {{-- KOLOM KIRI: INFO & ANALISA --}}
            <div class="col-lg-4">
                {{-- Info Customer & Motor --}}
                <x-advisor.info-card :booking="$booking" />

                {{-- Input Keluhan & Analisa --}}
                <div class="card border-0 shadow-sm rounded-4 mt-4">
                    <div class="card-header bg-white py-3 fw-bold border-bottom">
                        <i class="fas fa-clipboard-list text-danger me-2"></i> Analisa Mekanik
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Keluhan Konsumen</label>
                            <textarea name="customer_complaint" class="form-control bg-light" rows="3" placeholder="Contoh: Rem bunyi cit-cit...">{{ $advisor->customer_complaint ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Analisa Kerusakan / Pengerjaan</label>
                            <textarea name="jobs" class="form-control" rows="4" placeholder="Detail pengerjaan teknis...">{{ $advisor->jobs ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: SPAREPART & BIAYA --}}
            <div class="col-lg-8">
                
                {{-- KERANJANG SPAREPART (Cart System) --}}
                <x-advisor.sparepart-cart :initial-parts="$advisor->spareparts ?? '[]'" />

                {{-- SUMMARY BIAYA --}}
                <div class="card border-0 shadow-sm rounded-4 mt-4 bg-light">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Update Status Pengerjaan</label>
                                <select name="status" class="form-select border-danger">
                                    <option value="approved" {{ $booking->status == 'approved' ? 'selected' : '' }}>Menunggu (Approved)</option>
                                    <option value="on_progress" {{ $booking->status == 'on_progress' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                                    <option value="done" {{ $booking->status == 'done' ? 'selected' : '' }}>Selesai (Done)</option>
                                </select>
                            </div>
                            <div class="col-md-6 text-end">
                                <small class="text-uppercase text-muted fw-bold">Total Estimasi Biaya</small>
                                <h2 class="fw-bold text-danger m-0" id="grandTotalDisplay">Rp 0</h2>
                                <input type="hidden" name="total_estimation" id="grandTotalInput" value="0">
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-danger btn-lg rounded-pill px-5 fw-bold shadow">
                                <i class="fas fa-save me-2"></i> Simpan Pengerjaan
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection