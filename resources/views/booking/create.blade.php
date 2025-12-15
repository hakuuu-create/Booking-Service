@extends('layouts.app')

@section('title', 'Booking Service Honda')

@section('content')
<style>
    /* --- VARIABEL WARNA & UTILITIES --- */
    :root {
        --honda-red: #CC0000;
        --honda-light-red: #fff5f5;
        --text-primary: #1a1a1a;
        --text-secondary: #6c757d;
        --border-color: #e9ecef;
    }

    body {
        background-color: #fff;
        overflow-x: hidden; /* Mencegah scroll horizontal */
    }

    /* --- LAYOUT KIRI (SIDEBAR GAMBAR) --- */
    .booking-sidebar {
        background: url('https://images.unsplash.com/photo-1558981403-c5f9899a28bc?q=80&w=1000&auto=format&fit=crop') no-repeat center center;
        background-size: cover;
        min-height: 100vh;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 3rem;
    }

    .sidebar-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(204, 0, 0, 0.95) 0%, rgba(0, 0, 0, 0.4) 100%);
        z-index: 1;
    }

    .sidebar-content {
        position: relative;
        z-index: 2;
        color: white;
    }

    /* --- LAYOUT KANAN (FORMULIR) --- */
    .booking-content {
        padding: 3rem 4rem;
        background-color: #fff;
        min-height: 100vh;
    }

    /* --- KOMPONEN FORM MODERN --- */
    
    /* 1. Section Header */
    .form-section {
        margin-bottom: 2.5rem;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 2rem;
    }
    .form-section:last-child { border-bottom: none; }
    
    .section-title {
        font-weight: 800;
        font-size: 1.1rem;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .step-badge {
        background-color: var(--honda-red);
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        font-weight: bold;
        margin-right: 12px;
    }

    /* 2. Selection Cards (Radio Button Replacement) */
    .hidden-radio {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* Label Pembungkus Card */
    .selection-label {
        cursor: pointer;
        display: block;
        height: 100%;
    }

    .selection-card {
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 1.25rem;
        transition: all 0.2s ease-in-out;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        background-color: #fff;
        position: relative;
        overflow: hidden;
    }

    .selection-card:hover {
        border-color: #adb5bd;
        background-color: #f8f9fa;
    }

    /* State: CHECKED */
    .hidden-radio:checked + .selection-label .selection-card {
        border-color: var(--honda-red);
        background-color: var(--honda-light-red);
        box-shadow: 0 4px 12px rgba(204, 0, 0, 0.15);
    }

    /* Checkmark Icon (Muncul saat dipilih) */
    .selection-card::after {
        content: '\f00c'; /* FontAwesome Check */
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        position: absolute;
        top: 0;
        right: 0;
        background: var(--honda-red);
        color: white;
        padding: 4px 10px;
        border-bottom-left-radius: 10px;
        font-size: 0.8rem;
        display: none;
    }
    .hidden-radio:checked + .selection-label .selection-card::after {
        display: block;
    }

    /* Icon di dalam Card */
    .card-icon {
        font-size: 2rem;
        margin-bottom: 0.8rem;
        color: var(--text-secondary);
        transition: color 0.2s;
    }
    .hidden-radio:checked + .selection-label .card-icon {
        color: var(--honda-red);
    }

    /* 3. Time Slots (Grid Tombol) */
    .time-slot {
        padding: 10px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        text-align: center;
        font-weight: 600;
        color: var(--text-secondary);
        background: #fff;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.9rem;
    }
    .time-slot:hover {
        background-color: #f1f3f5;
        border-color: #ced4da;
    }
    .hidden-radio:checked + .time-label .time-slot {
        background-color: var(--honda-red);
        color: white;
        border-color: var(--honda-red);
    }
    .hidden-radio:disabled + .time-label .time-slot {
        background-color: #e9ecef;
        color: #adb5bd;
        cursor: not-allowed;
        text-decoration: line-through;
    }

    /* 4. Input Fields */
    .form-control {
        padding: 0.8rem 1rem;
        border-radius: 10px;
        border: 1px solid #ced4da;
        background-color: #f8f9fa;
    }
    .form-control:focus {
        background-color: #fff;
        border-color: var(--honda-red);
        box-shadow: 0 0 0 4px rgba(204, 0, 0, 0.1);
    }
    .form-label {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    /* 5. Sticky Footer Action */
    .sticky-footer {
        position: sticky;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(8px);
        padding: 1.5rem 0;
        border-top: 1px solid var(--border-color);
        margin-top: 2rem;
        z-index: 10;
    }

    /* Responsive adjustments */
    @media (max-width: 991.98px) {
        .booking-sidebar { display: none; } /* Sembunyikan gambar di mobile */
        .booking-content { padding: 2rem 1.5rem; }
    }
</style>

<div class="container-fluid p-0">
    <div class="row g-0">
        
        {{-- ================= KOLOM KIRI (GAMBAR) ================= --}}
        <div class="col-lg-4 col-xl-3 d-none d-lg-block">
            <div class="booking-sidebar">
                <div class="sidebar-overlay"></div>
                <div class="sidebar-content">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/38/Honda.svg/2560px-Honda.svg.png" 
                         alt="Honda Logo" style="filter: brightness(0) invert(1); height: 40px; margin-bottom: 1.5rem;">
                    <h2 class="fw-bold mb-3">Booking Servis Mudah & Cepat.</h2>
                    <p class="opacity-75 mb-4">Jadwalkan perawatan motor Anda sekarang. Tanpa antri, standar resmi AHASS.</p>
                    <div class="small opacity-75">
                        &copy; {{ date('Y') }} Bengkel Resmi Honda
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= KOLOM KANAN (FORMULIR) ================= --}}
        <div class="col-lg-8 col-xl-9">
            <div class="booking-content">
                
                {{-- Tombol Kembali Mobile --}}
                <div class="d-lg-none mb-4">
                    <a href="{{ url('/') }}" class="text-decoration-none text-muted fw-bold">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>

                @php
                    $user = Illuminate\Support\Facades\Auth::user();
                @endphp

                {{-- CEK KUOTA --}}
                @if(isset($todayActive) && $todayActive >= 50)
                    <div class="text-center py-5 mt-5">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex p-4 mb-4">
                            <i class="fas fa-calendar-times fa-3x"></i>
                        </div>
                        <h2 class="fw-bold">Kuota Hari Ini Penuh</h2>
                        <p class="text-muted">Mohon maaf, antrian hari ini sudah mencapai batas. Silakan lakukan booking untuk besok.</p>
                        <a href="{{ url('/') }}" class="btn btn-outline-dark rounded-pill fw-bold px-4 mt-2">Kembali ke Beranda</a>
                    </div>
                @else

                {{-- HEADER FORM --}}
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div>
                        <h2 class="fw-bold text-dark m-0">Formulir Booking</h2>
                        <p class="text-muted m-0">Isi data lengkap di bawah ini.</p>
                    </div>
                    <a href="{{ route('customers.dashboard') }}" class="btn btn-light rounded-pill border fw-bold text-muted px-4 d-none d-md-block">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                </div>

                {{-- ALERT ERROR --}}
                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle fs-4 me-3"></i>
                        <div>
                            <strong>Ups! Mohon periksa kembali.</strong>
                            <small class="d-block">Pastikan semua kolom bertanda wajib diisi.</small>
                        </div>
                    </div>
                @endif

                <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
                    @csrf

                    {{-- 1. JENIS KENDARAAN --}}
                    <div class="form-section">
                        <div class="section-title">
                            <div class="step-badge">1</div> Pilih Jenis Motor
                        </div>
                        <div class="row g-3 row-cols-2 row-cols-md-4">
                            <div class="col">
                                <input type="radio" name="vehicle_type" id="type_matic" value="matic" class="hidden-radio" required>
                                <label for="type_matic" class="selection-label">
                                    <div class="selection-card">
                                        <i class="fas fa-motorcycle card-icon"></i>
                                        <span class="fw-bold d-block">Matic</span>
                                        <small class="text-muted" style="font-size: 0.75rem;">Beat/Vario</small>
                                    </div>
                                </label>
                            </div>
                            <div class="col">
                                <input type="radio" name="vehicle_type" id="type_bebek" value="bebek" class="hidden-radio">
                                <label for="type_bebek" class="selection-label">
                                    <div class="selection-card">
                                        <i class="fas fa-feather-alt card-icon"></i>
                                        <span class="fw-bold d-block">Bebek</span>
                                        <small class="text-muted" style="font-size: 0.75rem;">Supra/Revo</small>
                                    </div>
                                </label>
                            </div>
                            <div class="col">
                                <input type="radio" name="vehicle_type" id="type_sport" value="sport" class="hidden-radio">
                                <label for="type_sport" class="selection-label">
                                    <div class="selection-card">
                                        <i class="fas fa-fighter-jet card-icon"></i>
                                        <span class="fw-bold d-block">Sport</span>
                                        <small class="text-muted" style="font-size: 0.75rem;">CBR/CB150</small>
                                    </div>
                                </label>
                            </div>
                            <div class="col">
                                <input type="radio" name="vehicle_type" id="type_cup" value="cup" class="hidden-radio">
                                <label for="type_cup" class="selection-label">
                                    <div class="selection-card">
                                        <i class="fas fa-history card-icon"></i>
                                        <span class="fw-bold d-block">Classic</span>
                                        <small class="text-muted" style="font-size: 0.75rem;">C70/Astrea</small>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- 2. PAKET SERVIS --}}
                    <div class="form-section">
                        <div class="section-title">
                            <div class="step-badge">2</div> Pilih Layanan Servis
                        </div>
                        <div class="row g-3">
                            @foreach(\App\Models\Service::all() as $service)
                            <div class="col-md-6 col-xl-4">
                                <input type="radio" name="service_id" id="service_{{ $service->id }}" 
                                       value="{{ $service->id }}" class="hidden-radio"
                                       data-price="{{ $service->price }}" required>
                                
                                <label for="service_{{ $service->id }}" class="selection-label">
                                    <div class="selection-card align-items-start text-start p-3">
                                        <div class="d-flex justify-content-between w-100 mb-2">
                                            <span class="fw-bold text-dark">{{ $service->name }}</span>
                                            {{-- Icon Logic Sederhana --}}
                                            @if(str_contains(strtolower($service->name), 'oli')) <i class="fas fa-oil-can text-danger"></i>
                                            @elseif(str_contains(strtolower($service->name), 'cvt')) <i class="fas fa-cogs text-danger"></i>
                                            @elseif(str_contains(strtolower($service->name), 'ban')) <i class="fas fa-compact-disc text-danger"></i>
                                            @else <i class="fas fa-tools text-danger"></i>
                                            @endif
                                        </div>
                                        <div class="mt-auto pt-2 border-top w-100 d-flex justify-content-between align-items-center">
                                            <span class="text-danger fw-bold">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                                            <small class="text-muted" style="font-size: 0.7rem;">Estimasi</small>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- 3. JADWAL (TANGGAL & JAM) --}}
                    <div class="form-section">
                        <div class="section-title">
                            <div class="step-badge">3</div> Rencana Kedatangan
                        </div>
                        <div class="row g-4">
                            <div class="col-md-5">
                                <label class="form-label">Pilih Tanggal</label>
                                <input type="date" id="date_picker" class="form-control" 
                                       min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label">Pilih Jam (Estimasi Masuk)</label>
                                <div class="row g-2">
                                    @php
                                        $timeSlots = [
                                            '08:00' => '08:00', '09:00' => '09:00', '10:00' => '10:00', 
                                            '11:00' => '11:00', '13:00' => '13:00', '14:00' => '14:00', '15:00' => '15:00'
                                        ];
                                    @endphp
                                    @foreach($timeSlots as $val => $label)
                                    <div class="col-3 col-md-3">
                                        <input type="radio" name="time_slot" id="time_{{ $val }}" value="{{ $val }}" class="hidden-radio" required>
                                        <label for="time_{{ $val }}" class="time-label w-100">
                                            <div class="time-slot">{{ $label }}</div>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="form-text mt-2 small"><i class="fas fa-info-circle me-1"></i>Jam 12:00 - 13:00 Istirahat.</div>
                            </div>
                        </div>
                        
                        {{-- Hidden Input untuk digabung oleh JS --}}
                        <input type="hidden" name="booking_date" id="final_booking_date">
                    </div>

                    {{-- 4. DATA PEMILIK --}}
                    <div class="form-section">
                        <div class="section-title">
                            <div class="step-badge">4</div> Konfirmasi Kontak
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Pemilik</label>
                                <input type="text" name="customer_name" class="form-control bg-light" 
                                       value="{{ $user->name }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nomor Polisi</label>
                                <input type="text" name="plate_number" class="form-control text-uppercase fw-bold" 
                                       placeholder="CONTOH: B 1234 XYZ" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">WhatsApp Aktif</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="fab fa-whatsapp text-success"></i></span>
                                    <input type="number" name="customer_whatsapp" class="form-control border-start-0" 
                                           value="{{ old('customer_whatsapp', $user->phone ?? '') }}" placeholder="08xxxxxxxxxx" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Hidden Fields --}}
                    <input type="hidden" name="quota" value="1">
                    <input type="hidden" name="user_id" value="{{ $user->id }}">

                    {{-- STICKY FOOTER (TOTAL & SUBMIT) --}}
                    <div class="sticky-footer">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <small class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">Estimasi Biaya</small>
                                <div class="fs-3 fw-bold text-dark" id="totalDisplay">Rp 0</div>
                            </div>
                            <div class="col-6 text-end">
                                <button type="submit" class="btn btn-danger btn-lg rounded-pill px-5 fw-bold shadow hover-scale" onclick="prepareDate()">
                                    Booking <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const serviceRadios = document.querySelectorAll('input[name="service_id"]');
        const totalDisplay = document.getElementById('totalDisplay');
        const bookingForm = document.getElementById('bookingForm');

        // 1. Format Rupiah
        const formatRupiah = (number) => {
            return new Intl.NumberFormat('id-ID', { 
                style: 'currency', 
                currency: 'IDR', 
                minimumFractionDigits: 0 
            }).format(number);
        }

        // 2. Update Harga Real-time
        serviceRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if(this.checked) {
                    const price = this.getAttribute('data-price');
                    // Efek Fade
                    totalDisplay.style.opacity = 0;
                    setTimeout(() => {
                        totalDisplay.innerText = formatRupiah(price);
                        totalDisplay.style.color = '#CC0000'; 
                        totalDisplay.style.opacity = 1;
                    }, 200);
                }
            });
        });

        // 3. Logic Gabung Tanggal + Jam -> Input Hidden
        window.prepareDate = function() {
            const dateVal = document.getElementById('date_picker').value;
            const timeSlot = document.querySelector('input[name="time_slot"]:checked');
            const hiddenInput = document.getElementById('final_booking_date');

            if (dateVal && timeSlot) {
                // Format: YYYY-MM-DD HH:mm:ss
                hiddenInput.value = dateVal + ' ' + timeSlot.value + ':00';
            }
        };

        // 4. Validasi Tambahan saat Submit
        if(bookingForm) {
            bookingForm.addEventListener('submit', function(e) {
                prepareDate(); // Jalankan fungsi prepare
                
                // Pastikan time slot dipilih
                const timeSlot = document.querySelector('input[name="time_slot"]:checked');
                if(!timeSlot) {
                    e.preventDefault();
                    alert("Silakan pilih Jam Kedatangan terlebih dahulu.");
                    return;
                }
            });
        }
    });
</script>
@endsection