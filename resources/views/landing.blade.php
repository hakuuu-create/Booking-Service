@extends('layouts.app')

@section('title', 'Bengkel Resmi Honda - Booking Servis Online')

@section('content')
<style>
    /* --- CUSTOM STYLES --- */
    :root {
        --honda-red: #CC0000;
        --honda-dark-red: #990000;
    }

    /* Navbar Khusus Landing Page (Jika user belum login) */
    .landing-navbar {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    }
    .nav-link-custom {
        font-weight: 600;
        color: #333 !important;
        margin: 0 10px;
        position: relative;
    }
    .nav-link-custom:hover, .nav-link-custom.active {
        color: var(--honda-red) !important;
    }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(120deg, var(--honda-red) 0%, var(--honda-dark-red) 100%);
        color: white;
        padding-top: 140px; /* Offset for fixed navbar */
        padding-bottom: 100px;
        border-radius: 0 0 50px 50px;
        position: relative;
        overflow: hidden;
    }
    .hero-pattern {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background-image: radial-gradient(rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 30px 30px;
        opacity: 0.5;
    }

    /* Feature & Service Cards */
    .hover-card {
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border: 1px solid #eee;
    }
    .hover-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(204, 0, 0, 0.1) !important;
        border-color: var(--honda-red);
    }

    /* Estimator Box */
    .estimator-box {
        margin-top: -60px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.1);
        position: relative;
        z-index: 10;
        border-top: 6px solid var(--honda-red);
    }

    /* Step Circles */
    .step-circle {
        width: 60px;
        height: 60px;
        background: #ffe6e6;
        color: var(--honda-red);
        font-weight: 800;
        font-size: 1.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem auto;
        transition: 0.3s;
    }
    .step-card:hover .step-circle {
        background: var(--honda-red);
        color: white;
    }

    /* Footer Styling */
    .main-footer {
        background-color: #1a1a1a;
        color: #b0b0b0;
        padding-top: 80px;
        padding-bottom: 30px;
    }
    .footer-title {
        color: white;
        font-weight: 700;
        margin-bottom: 1.5rem;
        position: relative;
        padding-bottom: 10px;
    }
    .footer-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 40px;
        height: 3px;
        background-color: var(--honda-red);
    }
    .footer-link {
        color: #b0b0b0;
        text-decoration: none;
        display: block;
        margin-bottom: 10px;
        transition: 0.2s;
    }
    .footer-link:hover {
        color: white;
        padding-left: 5px;
    }
    .contact-item {
        display: flex;
        margin-bottom: 15px;
        align-items: flex-start;
    }
    .contact-icon {
        color: var(--honda-red);
        margin-right: 15px;
        margin-top: 5px;
    }
</style>

{{-- 1. NAVBAR (Khusus Landing Page / Guest) --}}
{{-- Navbar ini akan muncul jika user belum login, atau menggantikan navbar default layout --}}
<nav class="navbar navbar-expand-lg fixed-top landing-navbar">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
            {{-- Logo Honda Wing Merah --}}
            <img src="https://upload.wikimedia.org/wikipedia/commons/3/38/Honda.svg" alt="Honda" height="30" class="me-2">
            <span class="text-danger">AHASS</span> <span class="text-dark">Service</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#landingNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="landingNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link nav-link-custom" href="#layanan">Layanan</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom" href="#keunggulan">Keunggulan</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom" href="#alur">Cara Booking</a></li>
                
                @auth
                    <li class="nav-item ms-lg-3">
                        <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('customers.dashboard') }}" class="btn btn-danger rounded-pill px-4 fw-bold">
                            Dashboard Saya
                        </a>
                    </li>
                @else
                    <li class="nav-item ms-lg-3">
                        <a href="{{ route('login') }}" class="fw-bold text-dark text-decoration-none me-3">Masuk</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="btn btn-outline-danger rounded-pill px-4 fw-bold">Daftar</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

{{-- 2. HERO SECTION --}}
<section class="hero-section">
    <div class="hero-pattern"></div>
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0">
                <span class="badge bg-white text-danger px-3 py-2 rounded-pill fw-bold mb-3 shadow">
                    <i class="fas fa-star me-1"></i> Bengkel Resmi Terpercaya
                </span>
                <h1 class="display-4 fw-bold mb-3">
                    Rawat Motor Anda,<br>Tanpa Antri Lama.
                </h1>
                <p class="fs-5 mb-4 text-white-50">
                    Nikmati kemudahan booking servis dari rumah. Teknisi bersertifikat, suku cadang asli, dan garansi pelayanan terbaik.
                </p>
                <div class="d-flex justify-content-center justify-content-lg-start gap-3">
                    <a href="{{ route('booking.create') }}" class="btn btn-light text-danger fw-bold btn-lg rounded-pill shadow px-4">
                        <i class="fas fa-calendar-check me-2"></i> Booking Sekarang
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                {{-- Gambar Motor (Placeholder transparan) --}}
                <img src="https://i.pinimg.com/1200x/11/af/c6/11afc699df33049810ef1e8de0cc5ef3.jpg" 
                     alt="Motor Honda" class="img-fluid" style="filter: drop-shadow(0 20px 40px rgba(0,0,0,0.4)); max-height: 450px;">
            </div>
        </div>
    </div>
</section>

{{-- 3. ESTIMATOR BIAYA (UX Feature) --}}
<div class="container">
  <div class="row justify-content-center">
      <div class="col-xl-11"> {{-- Diperlebar sedikit (col-xl-11) agar muat 4 kolom --}}
          <div class="estimator-box p-4 p-md-5">
              <form id="estimatorForm">
                  <div class="row align-items-center g-3">
                      {{-- Kolom 1: Judul --}}
                      <div class="col-lg-3 col-md-12 mb-3 mb-lg-0">
                          <h4 class="fw-bold text-dark m-0"><i class="fas fa-calculator text-danger me-2"></i>Cek Biaya</h4>
                          <p class="text-muted small m-0">Estimasi harga transparan.</p>
                      </div>

                      {{-- Kolom 2: Pilih Motor --}}
                      <div class="col-lg-3 col-md-6">
                          <label class="form-label small fw-bold text-secondary ms-1">1. Tipe Motor</label>
                          <select id="bikeSelect" class="form-select form-select-lg border-danger shadow-sm" style="font-size: 0.95rem;">
                              <option value="" selected>-- Pilih Model --</option>
                              <option value="matic">Matic (Beat/Vario)</option>
                              <option value="sport">Sport (CBR/CB150)</option>
                              <option value="bebek">Bebek (Supra/Revo)</option>
                          </select>
                      </div>

                      {{-- Kolom 3: Pilih Service (BARU) --}}
                      <div class="col-lg-3 col-md-6">
                          <label class="form-label small fw-bold text-secondary ms-1">2. Jenis Service</label>
                          <select id="serviceSelect" class="form-select form-select-lg border-danger shadow-sm" style="font-size: 0.95rem;" disabled>
                              <option value="" selected>-- Pilih Jasa --</option>
                              <option value="servis_ringan">Servis Ringan / Tune Up</option>
                              <option value="ganti_oli">Ganti Oli + Filter</option>
                              <option value="servis_cvt">Servis CVT / Rantai</option>
                              <option value="servis_lengkap">Servis Lengkap (Paket)</option>
                          </select>
                      </div>

                      {{-- Kolom 4: Harga --}}
                      <div class="col-lg-3 col-md-12 text-center text-lg-end mt-4 mt-lg-0">
                          <div class="bg-light p-3 rounded-3 border h-100 d-flex flex-column justify-content-center">
                              <small class="text-uppercase text-muted fw-bold d-block" style="font-size: 0.7rem;">Estimasi Biaya</small>
                              <h3 class="fw-bold text-danger m-0" id="priceDisplay">Rp -</h3>
                          </div>
                      </div>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div>

{{-- 4. KEUNGGULAN (Why Us) --}}
<section id="keunggulan" class="py-5 mt-4">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-danger fw-bold text-uppercase ls-1">Kenapa Kami?</h6>
            <h2 class="fw-bold">Standar Kualitas AHASS</h2>
        </div>
        
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="hover-card p-4 rounded-4 bg-white h-100 text-center">
                    <i class="fas fa-user-shield fa-3x text-danger mb-3"></i>
                    <h5 class="fw-bold">Teknisi Ahli</h5>
                    <p class="text-muted small">Tersertifikasi resmi oleh Astra Honda Motor.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="hover-card p-4 rounded-4 bg-white h-100 text-center">
                    <i class="fas fa-box-open fa-3x text-danger mb-3"></i>
                    <h5 class="fw-bold">Sparepart Asli</h5>
                    <p class="text-muted small">Jaminan 100% Honda Genuine Parts (HGP).</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="hover-card p-4 rounded-4 bg-white h-100 text-center">
                    <i class="fas fa-bolt fa-3x text-danger mb-3"></i>
                    <h5 class="fw-bold">Servis Cepat</h5>
                    <p class="text-muted small">Layanan Pit Express untuk ganti oli & part.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="hover-card p-4 rounded-4 bg-white h-100 text-center">
                    <i class="fas fa-chair fa-3x text-danger mb-3"></i>
                    <h5 class="fw-bold">Nyaman</h5>
                    <p class="text-muted small">Ruang tunggu AC, Wi-Fi, dan minuman gratis.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 5. MENU LAYANAN --}}
<section id="layanan" class="py-5 bg-light">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h6 class="text-danger fw-bold text-uppercase">Menu Servis</h6>
                <h2 class="fw-bold">Pilih Perawatan Motor</h2>
            </div>
            <a href="{{ route('booking.create') }}" class="btn btn-outline-danger rounded-pill fw-bold d-none d-md-inline-block">Lihat Semua</a>
        </div>

        <div class="row g-4">
            {{-- Card 1 --}}
            <div class="col-md-4">
                <div class="card hover-card border-0 rounded-4 overflow-hidden h-100">
                    <img src="https://images.unsplash.com/photo-1626847037657-fd3622613ce3?q=80&w=600&auto=format&fit=crop" class="card-img-top" alt="Servis Berkala" style="height: 220px; object-fit: cover;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold">Servis Berkala</h5>
                        <p class="text-muted small mb-4">Pengecekan lengkap 15 poin (Rem, CVT, Injeksi, Kelistrikan) untuk menjaga performa.</p>
                        <a href="{{ route('booking.create') }}" class="btn btn-danger w-100 rounded-pill fw-bold">Booking</a>
                    </div>
                </div>
            </div>
            {{-- Card 2 --}}
            <div class="col-md-4">
                <div class="card hover-card border-0 rounded-4 overflow-hidden h-100">
                    <img src="https://images.unsplash.com/photo-1487754180451-c456f719a1fc?q=80&w=600&auto=format&fit=crop" class="card-img-top" alt="Ganti Oli" style="height: 220px; object-fit: cover;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold">Ganti Oli & Part</h5>
                        <p class="text-muted small mb-4">Penggantian AHM Oil, Kampas Rem, Ban, atau Gear Set. Cepat dan presisi.</p>
                        <a href="{{ route('booking.create') }}" class="btn btn-danger w-100 rounded-pill fw-bold">Booking</a>
                    </div>
                </div>
            </div>
            {{-- Card 3 --}}
            <div class="col-md-4">
                <div class="card hover-card border-0 rounded-4 overflow-hidden h-100">
                    <img src="https://images.unsplash.com/photo-1530026367247-f2752402772d?q=80&w=600&auto=format&fit=crop" class="card-img-top" alt="Perbaikan Berat" style="height: 220px; object-fit: cover;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold">Perbaikan Berat</h5>
                        <p class="text-muted small mb-4">Solusi masalah mesin, turun mesin, atau perbaikan pasca kecelakaan.</p>
                        <a href="{{ route('booking.create') }}" class="btn btn-danger w-100 rounded-pill fw-bold">Booking</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 6. ALUR BOOKING (3 Steps) --}}
<section id="alur" class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Mudahnya Booking Servis</h2>
            <p class="text-muted">Cukup 3 langkah sederhana, motor siap dirawat.</p>
        </div>

        <div class="row g-5 justify-content-center">
            <div class="col-md-4 text-center">
                <div class="step-card">
                    <div class="step-circle mx-auto">1</div>
                    <h5 class="fw-bold">Pilih Jadwal</h5>
                    <p class="text-muted small">Tentukan jenis motor, servis yang diinginkan, serta tanggal dan jam kedatangan.</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="step-card">
                    <div class="step-circle mx-auto">2</div>
                    <h5 class="fw-bold">Isi Data Diri</h5>
                    <p class="text-muted small">Masukkan nama dan WhatsApp agar kami mudah menghubungi Anda.</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="step-card">
                    <div class="step-circle mx-auto">3</div>
                    <h5 class="fw-bold">Datang ke Bengkel</h5>
                    <p class="text-muted small">Tunjukkan bukti booking pada Service Advisor. Tanpa antri administrasi!</p>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('booking.create') }}" class="btn btn-danger btn-lg rounded-pill px-5 fw-bold shadow hover-scale">
                Mulai Booking Sekarang
            </a>
        </div>
    </div>
</section>

{{-- 7. TESTIMONI --}}
<section class="py-5 bg-light">
    <div class="container py-5">
        <h2 class="fw-bold text-center mb-5">Kata Pelanggan Kami</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="bg-white p-4 rounded-4 shadow-sm h-100">
                    <div class="text-warning mb-2"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p class="fst-italic text-muted small">"Akhirnya ada booking online. Gak perlu lagi nunggu dari pagi buta. Pelayanan ramah banget."</p>
                    <div class="d-flex align-items-center mt-3">
                        <div class="fw-bold text-dark">Andi Pratama</div>
                        <span class="mx-2 text-muted">|</span>
                        <small class="text-muted">Vario 150</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-4 rounded-4 shadow-sm h-100">
                    <div class="text-warning mb-2"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p class="fst-italic text-muted small">"Estimasi biayanya transparan. Mekanik menjelaskan dengan detail apa yang perlu diganti."</p>
                    <div class="d-flex align-items-center mt-3">
                        <div class="fw-bold text-dark">Siti Rahma</div>
                        <span class="mx-2 text-muted">|</span>
                        <small class="text-muted">Beat Street</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-white p-4 rounded-4 shadow-sm h-100">
                    <div class="text-warning mb-2"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i></div>
                    <p class="fst-italic text-muted small">"Ruang tunggunya nyaman. Pengerjaan CBR saya rapi dan bersih. Recommended!"</p>
                    <div class="d-flex align-items-center mt-3">
                        <div class="fw-bold text-dark">Bayu Aji</div>
                        <span class="mx-2 text-muted">|</span>
                        <small class="text-muted">CBR 150R</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 8. FOOTER (Dengan Maps & Contact) --}}
<footer class="main-footer">
    <div class="container">
        <div class="row g-5">
            {{-- Brand Info --}}
            <div class="col-lg-4">
                <a class="navbar-brand fw-bold d-flex align-items-center text-white mb-3" href="#">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/3/38/Honda.svg" alt="Honda" height="30" class="me-2" style="filter: brightness(0) invert(1);">
                    <span>AHASS Service</span>
                </a>
                <p class="small text-secondary mb-4">
                    Bengkel resmi sepeda motor Honda dengan standar pelayanan terbaik. Kami siap merawat performa motor Anda agar selalu prima.
                </p>
                <div class="d-flex gap-3">
                    <a href="#" class="text-secondary fs-5"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-secondary fs-5"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-secondary fs-5"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="col-lg-2 col-md-6">
                <h5 class="footer-title">Tautan</h5>
                <a href="#layanan" class="footer-link">Layanan Servis</a>
                <a href="#keunggulan" class="footer-link">Tentang Kami</a>
                <a href="{{ route('booking.create') }}" class="footer-link">Booking Online</a>
                <a href="{{ route('login') }}" class="footer-link">Login Member</a>
            </div>

            {{-- Contact Info --}}
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-title">Hubungi Kami</h5>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt contact-icon"></i>
                    <span class="small">Jl. Raya Otomotif No. 88, Jakarta Selatan, DKI Jakarta 12345</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone-alt contact-icon"></i>
                    <span class="small">(021) 7890-1234 <br> 0812-3456-7890 (WA)</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope contact-icon"></i>
                    <span class="small">service@ahass-bengkel.com</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-clock contact-icon"></i>
                    <span class="small">Senin - Sabtu: 08:00 - 17:00<br>Minggu: 09:00 - 14:00</span>
                </div>
            </div>

            {{-- Maps --}}
            <div class="col-lg-3">
                <h5 class="footer-title">Lokasi</h5>
                <div class="rounded-3 overflow-hidden border border-secondary" style="height: 180px;">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.3!2d106.8!3d-6.2!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTInMDAuMCJTIDEwNsKwNDgnMDAuMCJF!5e0!3m2!1sen!2sid!4v1600000000000!5m2!1sen!2sid" 
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
                <a href="https://maps.google.com" target="_blank" class="btn btn-outline-light btn-sm w-100 mt-2 rounded-pill">
                    <i class="fas fa-location-arrow me-1"></i> Buka Google Maps
                </a>
            </div>
        </div>

        <div class="border-top border-secondary mt-5 pt-4 text-center">
            <small class="text-secondary">&copy; {{ date('Y') }} AHASS Service Center. All rights reserved.</small>
        </div>
    </div>
</footer>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bikeSelect = document.getElementById('bikeSelect');
        const serviceSelect = document.getElementById('serviceSelect');
        const priceDisplay = document.getElementById('priceDisplay');
        
        // Data Harga (Matrix: Tipe Motor -> Jenis Service -> Harga)
        // Harga ini bisa disesuaikan dengan database Anda nanti
        const pricingData = {
            'matic': {
                'servis_ringan': 45000,
                'ganti_oli': 20000, // Jasa ganti oli saja
                'servis_cvt': 35000,
                'servis_lengkap': 85000
            },
            'bebek': {
                'servis_ringan': 40000,
                'ganti_oli': 15000,
                'servis_cvt': 25000, // Ganti Gear Set/Rantai
                'servis_lengkap': 75000
            },
            'sport': {
                'servis_ringan': 60000,
                'ganti_oli': 25000,
                'servis_cvt': 40000, // Service Rantai & Gear
                'servis_lengkap': 120000
            }
        };

        // Fungsi Update Harga
        function updatePrice() {
            const bikeType = bikeSelect.value;
            const serviceType = serviceSelect.value;

            // Efek loading kecil (opacity)
            priceDisplay.style.opacity = '0.5';

            setTimeout(() => {
                if (bikeType && serviceType && pricingData[bikeType][serviceType]) {
                    const price = pricingData[bikeType][serviceType];
                    // Format Rupiah
                    priceDisplay.innerHTML = 'Rp ' + price.toLocaleString('id-ID');
                    priceDisplay.classList.remove('text-muted');
                    priceDisplay.classList.add('text-danger');
                } else {
                    priceDisplay.innerHTML = 'Rp -';
                    priceDisplay.classList.add('text-muted');
                    priceDisplay.classList.remove('text-danger');
                }
                priceDisplay.style.opacity = '1';
            }, 150);
        }

        // Event Listener untuk Tipe Motor
        bikeSelect.addEventListener('change', function() {
            // Aktifkan dropdown service jika motor sudah dipilih
            if (this.value) {
                serviceSelect.disabled = false;
            } else {
                serviceSelect.disabled = true;
                serviceSelect.value = ""; // Reset service
            }
            updatePrice();
        });

        // Event Listener untuk Jenis Service
        serviceSelect.addEventListener('change', function() {
            updatePrice();
        });
    });
</script>
@endsection