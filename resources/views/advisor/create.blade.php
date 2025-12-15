@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-danger-subtle rounded-4">
                <div class="card-header bg-danger text-white text-center py-3">
                    <h3 class="fw-bold mb-0">Form Service Advisor</h3>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('advisor.store') }}" method="POST">
                        @csrf

                        {{-- Bagian Utama --}}
                        <div class="mb-4">
                            <label for="booking_id" class="form-label fw-bold">Pilih Booking Customer</label>
                            <select name="booking_id" id="booking_id" class="form-select form-select-lg" onchange="updateService()">
                                <option value="">-- Pilih Berdasarkan Plat Nomor --</option>
                                @foreach($bookings as $b)
                                    <option value="{{ $b->id }}"
                                            data-service="{{ $b->service->name }}"
                                            data-price="{{ $b->service->price }}">
                                        {{ $b->plate_number }} - {{ $b->customer_name }} ({{ $b->service->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Detail Servis & Biaya (Readonly) --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="service_name" class="form-label">Jenis Service</label>
                                <input type="text" id="service_name" class="form-control bg-light" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="estimation_cost" class="form-label">Estimasi Biaya Service</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="estimation_cost" id="estimation_cost" class="form-control bg-light" readonly>
                                </div>
                            </div>
                        </div>

                        {{-- Input Tambahan dari Advisor --}}
                        <div class="mb-3">
                            <label for="spareparts" class="form-label">Suku Cadang Tambahan</label>
                            <textarea name="spareparts" id="spareparts" class="form-control" rows="3" placeholder="Contoh: Busi|25000, Oli|50000"></textarea>
                            <div class="form-text">Format: Nama Suku Cadang|Harga. Pisahkan dengan koma jika lebih dari satu.</div>
                        </div>

                        <div class="mb-3">
                            <label for="customer_complaint" class="form-label">Keluhan Konsumen</label>
                            <textarea name="customer_complaint" id="customer_complaint" class="form-control" rows="3" placeholder="Jelaskan keluhan yang disampaikan oleh konsumen..."></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="advisor_notes" class="form-label">Analisa & Catatan Service Advisor</label>
                            <textarea name="advisor_notes" id="advisor_notes" class="form-control" rows="3" placeholder="Jelaskan hasil analisa dan catatan tambahan..."></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger btn-lg fw-bold">
                                <i class="fas fa-print me-2"></i>Simpan & Cetak
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script JS asli tidak diubah --}}
<script>
function updateService() {
    let select = document.getElementById('booking_id');
    let selected = select.options[select.selectedIndex];
    let service = selected.getAttribute('data-service') || '';
    let price = selected.getAttribute('data-price') || 0;
    document.getElementById('service_name').value = service;
    document.getElementById('estimation_cost').value = price;
}
</script>

@endsection