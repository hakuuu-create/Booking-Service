@props(['booking'])

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-dark text-white py-3 fw-bold d-flex justify-content-between">
        <span><i class="fas fa-user-circle me-2"></i>Data Pelanggan</span>
        <span class="badge bg-danger">{{ strtoupper($booking->vehicle_type) }}</span>
    </div>
    <div class="card-body p-4">
        <div class="mb-3 d-flex align-items-center">
            <div class="bg-light rounded-circle p-3 me-3 text-secondary">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <small class="text-muted d-block">Nama Pemilik</small>
                <span class="fw-bold text-dark">{{ $booking->customer_name }}</span>
            </div>
        </div>
        
        <div class="mb-3 d-flex align-items-center">
            <div class="bg-light rounded-circle p-3 me-3 text-secondary">
                <i class="fas fa-motorcycle"></i>
            </div>
            <div>
                <small class="text-muted d-block">No. Polisi</small>
                <span class="fw-bold text-dark font-monospace">{{ $booking->plate_number }}</span>
            </div>
        </div>

        <div class="mb-0 d-flex align-items-center">
            <div class="bg-light rounded-circle p-3 me-3 text-secondary">
                <i class="fas fa-tools"></i>
            </div>
            <div>
                <small class="text-muted d-block">Layanan Awal</small>
                <span class="fw-bold text-danger">{{ $booking->service->name }}</span>
                <br>
                <small class="text-muted">Biaya Jasa: Rp {{ number_format($booking->service->price, 0, ',', '.') }}</small>
                <input type="hidden" id="baseServicePrice" value="{{ $booking->service->price }}">
            </div>
        </div>
    </div>
</div>