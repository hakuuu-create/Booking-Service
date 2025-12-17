@if(session('success') || session('error'))
    <div class="alert alert-{{ session('success') ? 'success' : 'danger' }} border-0 shadow-sm rounded-3 d-flex align-items-center mb-4 alert-dismissible fade show" role="alert">
        <div class="bg-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="fas {{ session('success') ? 'fa-check-circle text-success' : 'fa-exclamation-triangle text-danger' }} fs-5"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0">{{ session('success') ? 'Berhasil!' : 'Terjadi Kesalahan!' }}</h6>
            <small>{{ session('success') ?? session('error') }}</small>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif