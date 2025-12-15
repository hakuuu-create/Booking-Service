@extends('layouts.app')

@section('title', 'Dashboard Pelanggan')

@section('content')
<style>
    /* --- HERO SECTION --- */
    .hero-section {
        background: linear-gradient(120deg, #cc0000 0%, #8a0000 100%);
        border-radius: 20px;
        position: relative;
        overflow: hidden;
        color: white;
    }
    
    .hero-bg-pattern {
        position: absolute;
        top: 0;
        right: 0;
        width: 60%;
        height: 100%;
        background-image: url('https://img.freepik.com/free-photo/motorcycle-mechanic-checking-engine_342744-118.jpg?t=st=1710000000~exp=1710003600~hmac=xyz'); /* Placeholder Image */
        background-size: cover;
        background-position: center;
        opacity: 0.15; /* Membuat gambar menyatu dengan warna merah */
        mask-image: linear-gradient(to left, rgba(0,0,0,1), rgba(0,0,0,0));
        -webkit-mask-image: linear-gradient(to left, rgba(0,0,0,1), rgba(0,0,0,0));
    }

    /* --- SERVICE CARDS (PREMIUM LOOK) --- */
    .service-card {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        height: 100%;
        position: relative;
    }

    .service-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(204, 0, 0, 0.15) !important;
    }

    .service-img-wrapper {
        height: 180px;
        overflow: hidden;
        position: relative;
    }

    .service-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .service-card:hover .service-img-wrapper img {
        transform: scale(1.1); /* Efek Zoom saat hover */
    }

    .service-price-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(255, 255, 255, 0.95);
        color: #cc0000;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 30px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        font-size: 0.9rem;
        z-index: 2;
    }

    .service-body {
        padding: 1.5rem;
    }

    .service-title {
        font-weight: 700;
        font-size: 1.15rem;
        margin-bottom: 0.5rem;
        color: #1a1a1a;
    }

    .service-desc {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2; /* Batasi teks jadi 2 baris */
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* --- STATUS BADGES --- */
    .badge-modern {
        padding: 8px 12px;
        font-weight: 500;
        letter-spacing: 0.3px;
        border-radius: 8px;
    }
</style>

{{-- 1. HERO BANNER --}}
<div class="row mb-5">
    <div class="col-12">
        <div class="hero-section shadow p-5 d-flex align-items-center">
            <div class="hero-bg-pattern"></div> {{-- Background Image Overlay --}}
            
            <div class="position-relative z-1 col-lg-7">
                <span class="badge bg-white text-danger mb-3 px-3 py-2 rounded-pill fw-bold text-uppercase shadow-sm">
                    <i class="fas fa-star me-1"></i> Member Priority
                </span>
                <h1 class="display-5 fw-bold mb-3">Motor Sehat, Hati Tenang!</h1>
                <p class="fs-5 mb-4 text-white-50">Selamat datang kembali, <strong>{{ $user->name }}</strong>. Jangan biarkan performa motor Anda menurun. Jadwalkan servis rutin hari ini.</p>
                
                @if(Route::has('booking.create'))
                <a href="{{ route('booking.create') }}" class="btn btn-light text-danger fw-bold px-4 py-3 rounded-pill shadow-lg hover-scale">
                    <i class="fas fa-calendar-check me-2"></i> Booking Servis Sekarang
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- 2. KATALOG LAYANAN (VISUAL GALLERY) --}}
<div class="mb-5">
    <div class="row align-items-center mb-4">
        <div class="col">
            <h3 class="fw-bold text-dark m-0">
                <span class="text-danger border-start border-4 border-danger ps-3">Pilihan</span> Layanan Servis
            </h3>
            <p class="text-muted ms-4 mt-1 small">Pilih paket perawatan terbaik untuk kendaraan Anda</p>
        </div>
        <div class="col-auto">
             {{-- Bisa ditambah filter kategori jika perlu --}}
        </div>
    </div>

    <div class="row g-4">
        @php
        // LOGIKA PERBAIKAN:
        // Cek apakah variabel $services ada DAN datanya lebih dari 0.
        // Jika tidak ada atau kosong, pakai data palsu (Mockup).
        
        if (isset($services) && count($services) > 0) {
            $mockServices = $services;
        } else {
            $mockServices = [
                (object)[
                    'id' => 1,
                    'name' => 'Ganti Oli & Tune Up', 
                    'price' => 65000, 
                    'desc' => 'Paket hemat untuk performa harian. Ganti oli mesin resmi, cek busi, dan penyetelan stasioner.', 
                    'image' => 'https://i.pinimg.com/736x/8a/70/1e/8a701eb74619d896d0a962e1e89d2d06.jpg'
                ],
                (object)[
                    'id' => 2,
                    'name' => 'Servis CVT Matic', 
                    'price' => 85000, 
                    'desc' => 'Hilangkan getaran (gredek) pada tarikan awal. Pembersihan area CVT, roller, dan v-belt.', 
                    'image' => 'https://i.pinimg.com/736x/c8/bc/76/c8bc76f067150bfbb11710c1bce39516.jpg'
                ],
                (object)[
                    'id' => 3,
                    'name' => 'Servis Injeksi Total', 
                    'price' => 120000, 
                    'desc' => 'Pembersihan throttle body dan injektor menggunakan cairan khusus untuk efisiensi BBM maksimal.', 
                    'image' => 'https://images.unsplash.com/photo-1487754180451-c456f719a1fc?auto=format&fit=crop&q=80&w=600'
                ],
                (object)[
                    'id' => 4,
                    'name' => 'Overhaul / Turun Mesin', 
                    'price' => 350000, 
                    'desc' => 'Perbaikan berat untuk mengembalikan kompresi dan tenaga mesin seperti baru lagi.', 
                    'image' => 'https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?auto=format&fit=crop&q=80&w=600'
                ],
            ];
        }
    @endphp

        @foreach($mockServices as $service)
        <div class="col-md-6 col-lg-3">
            <div class="card service-card shadow-sm h-100">
                {{-- Gambar Service --}}
                <div class="service-img-wrapper">
                    {{-- Badge Harga di atas gambar --}}
                    <div class="service-price-badge">
                        Rp {{ number_format($service->price, 0, ',', '.') }}
                    </div>
                    
                    {{-- Jika ada field image di database, gunakan. Jika tidak, pakai placeholder --}}
                    <img src="{{ $service->image ?? 'https://placehold.co/600x400/cc0000/FFF?text=Service+Honda' }}" 
                         alt="{{ $service->name }}">
                </div>

                <div class="service-body d-flex flex-column h-100">
                    <h5 class="service-title">{{ $service->name }}</h5>
                    <p class="service-desc">{{ $service->desc ?? 'Perawatan profesional standar bengkel resmi Honda.' }}</p>
                    
                    <div class="mt-auto">
                        @if(Route::has('booking.create'))
                        <a href="{{ route('booking.create', ['service_id' => $service->id]) }}" class="btn btn-outline-danger w-100 rounded-pill fw-bold">
                            Pilih Layanan <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                        @else
                        <button class="btn btn-secondary w-100 rounded-pill" disabled>Segera Hadir</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- 3. RIWAYAT BOOKING SECTION --}}
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white py-3 border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-history text-secondary me-2"></i>Status Booking Anda
                    </h5>
                    @if(!$bookings->isEmpty())
                    <a href="#" class="text-decoration-none text-danger fw-bold small">Lihat Semua History &rarr;</a>
                    @endif
                </div>
            </div>
            
            <div class="card-body p-0">
                @if ($bookings->isEmpty())
                    <div class="text-center py-5 bg-light rounded-bottom-4">
                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486747.png" alt="Empty" width="80" class="mb-3 opacity-50">
                        <h6 class="fw-bold text-secondary">Belum ada jadwal servis</h6>
                        <p class="text-muted small mb-0">Motor Anda kangen diservis, nih!</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 text-secondary text-uppercase small">Waktu</th>
                                    <th class="text-secondary text-uppercase small">Layanan</th>
                                    <th class="text-secondary text-uppercase small">Plat Nomor</th>
                                    <th class="text-secondary text-uppercase small">Status</th>
                                    <th class="text-end pe-4 text-secondary text-uppercase small">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bookings as $booking)
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-bold text-dark d-block">{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }}</span>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($booking->booking_date)->format('H:i') }} WIB</small>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-danger">{{ $booking->service->name ?? 'Servis Berkala' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace">{{ $booking->plate_number }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusMap = [
                                                'pending' => ['bg' => 'warning-subtle', 'text' => 'warning', 'icon' => 'fa-clock'],
                                                'approved' => ['bg' => 'info-subtle', 'text' => 'info', 'icon' => 'fa-thumbs-up'],
                                                'on_progress' => ['bg' => 'primary-subtle', 'text' => 'primary', 'icon' => 'fa-cog fa-spin'],
                                                'done' => ['bg' => 'success-subtle', 'text' => 'success', 'icon' => 'fa-check'],
                                                'cancelled' => ['bg' => 'danger-subtle', 'text' => 'danger', 'icon' => 'fa-times'],
                                            ];
                                            $st = $statusMap[strtolower($booking->status)] ?? ['bg' => 'secondary-subtle', 'text' => 'secondary', 'icon' => 'fa-circle'];
                                        @endphp
                                        <span class="badge-modern bg-{{ $st['bg'] }} text-{{ $st['text'] }} border border-{{ $st['text'] }}">
                                            <i class="fas {{ $st['icon'] }} me-1"></i> {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('booking.show', $booking->id) }}" class="btn btn-sm btn-outline-secondary rounded-circle">
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
             @if ($bookings->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection