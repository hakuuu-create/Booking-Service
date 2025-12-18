@extends('layouts.app')

@section('title', 'Gudang Sparepart')

@section('content')
<style>
    /* --- CSS UNTUK INVENTORY PAGE --- */

    /* 1. Horizontal Filter Bar (Snackbar Style) */
    .filter-bar-container {
        overflow-x: auto;
        white-space: nowrap;
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
        padding-top: 5px;
        padding-bottom: 5px;
    }
    .filter-bar-container::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera */
    }
    
    .filter-chip {
        display: inline-block;
        padding: 8px 20px;
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 50px;
        color: #555;
        font-weight: 600;
        font-size: 0.9rem;
        margin-right: 10px;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    
    .filter-chip:hover {
        background-color: #f8f9fa;
        border-color: #ccc;
        color: #333;
        transform: translateY(-1px);
    }

    .filter-chip.active {
        background-color: var(--honda-red);
        border-color: var(--honda-red);
        color: white;
        box-shadow: 0 4px 10px rgba(204, 0, 0, 0.2);
    }

    /* 2. Product Card Modern & Overlay Effect */
    .product-card {
        border: none;
        border-radius: 16px;
        background: #fff;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        height: 100%;
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    /* Tombol Titik Tiga (Kebab Menu) */
    .btn-card-menu {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 10;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(4px);
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        color: #333;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        opacity: 0; /* Hidden by default */
        cursor: pointer;
    }

    .product-card:hover .btn-card-menu {
        opacity: 1;
    }

    .btn-card-menu:hover {
        background: var(--honda-red);
        color: white;
    }

    /* Overlay Menu (Muncul saat tombol titik tiga diklik) */
    .card-overlay-menu {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.6); /* Putih transparan */
        backdrop-filter: blur(8px); /* EFEK BLUR KACA */
        z-index: 20;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 15px;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        transform: scale(1.1);
    }

    /* Class aktif untuk memunculkan overlay */
    .product-card.menu-active .card-overlay-menu {
        opacity: 1;
        visibility: visible;
        transform: scale(1);
    }

    /* Tombol di dalam Overlay */
    .overlay-btn {
        width: 150px;
        padding: 10px;
        border-radius: 50px;
        font-weight: bold;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateY(20px);
        transition: 0.3s;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }

    .product-card.menu-active .overlay-btn {
        transform: translateY(0);
    }

    /* Tombol Close Overlay (X) */
    .btn-close-overlay {
        position: absolute;
        top: 10px;
        right: 10px;
        background: transparent;
        border: none;
        font-size: 1.5rem;
        color: #555;
        cursor: pointer;
    }
    .btn-close-overlay:hover { color: var(--honda-red); }

    .btn-red {
    background-color: #dc3545; /* merah */
    border-color: #dc3545;
    }

    .btn-red:hover {
        background-color: #bb2d3b;
        border-color: #bb2d3b;
    }


    /* Icon Placeholder Box */
    .product-icon-box {
        height: 120px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
        font-size: 3rem;
        position: relative;
    }

    .stock-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        font-size: 0.75rem;
        padding: 5px 12px;
        border-radius: 8px;
        font-weight: 700;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(4px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .low-stock {
        color: #dc3545;
        border: 1px solid #f8d7da;
    }
    .safe-stock {
        color: #198754;
        border: 1px solid #d1e7dd;
    }

    .part-code {
        font-family: 'Consolas', monospace;
        font-size: 0.75rem;
        color: #888;
        letter-spacing: 1px;
    }
</style>

<div class="container-fluid py-4 px-4">
    
    {{-- HEADER & SEARCH --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold m-0 text-dark"><i class="fas fa-warehouse me-2 text-danger"></i>Inventory</h4>
            <small class="text-muted">Manajemen stok dan harga barang.</small>
        </div>
        
        <div class="d-flex gap-2">
            <form action="{{ route('inventory.index') }}" method="GET" class="d-flex">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-3"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 rounded-end-pill" 
                           placeholder="Cari sparepart..." value="{{ request('search') }}" style="max-width: 250px;">
                </div>
            </form>
            <button class="btn btn-danger rounded-circle shadow-sm d-flex align-items-center justify-content-center" 
                    style="width: 40px; height: 40px;"
                    data-bs-toggle="modal" data-bs-target="#modalCreate" title="Tambah Barang">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>

    {{-- FILTER BAR (SNACKBAR STYLE) --}}
    <div class="mb-4">
        <div class="filter-bar-container">
            {{-- Tombol SEMUA --}}
            <a href="{{ route('inventory.index') }}" 
               class="filter-chip {{ !request('category') ? 'active' : '' }}">
               <i class="fas fa-th-large me-1"></i> Semua
            </a>
            
            {{-- Kategori Items --}}
            <a href="{{ route('inventory.index', ['category' => 'oli']) }}" 
               class="filter-chip {{ request('category') == 'oli' ? 'active' : '' }}">
               <i class="fas fa-oil-can me-1"></i> Oli
            </a>
            
            <a href="{{ route('inventory.index', ['category' => 'ban']) }}" 
               class="filter-chip {{ request('category') == 'ban' ? 'active' : '' }}">
               <i class="fas fa-compact-disc me-1"></i> Ban
            </a>

            <a href="{{ route('inventory.index', ['category' => 'cvt']) }}" 
                class="filter-chip {{ request('category') == 'cvt' ? 'active' : '' }}">
                <i class="fas fa-cog me-1"></i> CVT Parts
             </a>

            <a href="{{ route('inventory.index', ['category' => 'busi']) }}" 
               class="filter-chip {{ request('category') == 'busi' ? 'active' : '' }}">
               <i class="fas fa-bolt me-1"></i> Busi & Kelistrikan
            </a>
        
            <a href="{{ route('inventory.index', ['category' => 'sparepart']) }}" 
               class="filter-chip {{ request('category') == 'sparepart' ? 'active' : '' }}">
               <i class="fas fa-tools me-1"></i> Sparepart
            </a>
        </div>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center alert-dismissible fade show">
            <i class="fas fa-check-circle me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- GRID CARD CONTENT --}}
    <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4">
        
        @forelse($parts as $part)
            {{-- Helper untuk Icon otomatis --}}
            @php
                $icon = 'fa-box-open';
                $color = 'text-secondary';
                // Logika icon berdasarkan kategori atau nama
                if($part->category == 'oli' || str_contains(strtolower($part->name), 'oli')) { $icon = 'fa-oil-can'; $color = 'text-warning'; }
                elseif($part->category == 'ban' || str_contains(strtolower($part->name), 'ban')) { $icon = 'fa-compact-disc'; $color = 'text-dark'; }
                elseif($part->category == 'cvt' || str_contains(strtolower($part->name), 'cvt')) { $icon = 'fa-cog'; $color = 'text-secondary'; }
                elseif($part->category == 'busi' || str_contains(strtolower($part->name), 'busi')) { $icon = 'fa-bolt'; $color = 'text-warning'; }
                elseif($part->category == 'sparepart' || str_contains(strtolower($part->name), 'kampas')) { $icon = 'fa-tools'; $color = 'text-danger'; }
            @endphp

            <div class="col">
                <div class="product-card h-100 shadow-sm" id="card-{{ $part->id }}">
                    
                    {{-- 1. TOMBOL TITIK TIGA (Kiri Atas) --}}
                    <button class="btn-card-menu" onclick="toggleMenu({{ $part->id }})">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
        
                    {{-- 2. OVERLAY MENU (Edit & Hapus di Tengah, Background Blur) --}}
                    <div class="card-overlay-menu" id="overlay-{{ $part->id }}">
                        <button class="btn-close-overlay" onclick="toggleMenu({{ $part->id }})">&times;</button>
                        
                        {{-- Tombol Edit --}}
                        <button class="overlay-btn btn-light text-primary" 
                                data-bs-toggle="modal" data-bs-target="#modalEdit{{ $part->id }}">
                            <i class="fas fa-pen me-2"></i> Edit Barang
                        </button>
        
                        {{-- Tombol Hapus (Pemicu Custom Alert Component) --}}
                        <button class="overlay-btn btn-red text-white mt-2" 
                                onclick="showDeleteAlert({{ $part->id }}, '{{ $part->name }}')">
                            <i class="fas fa-trash me-2"></i> Hapus
                        </button>
                    </div>

                    {{-- 3. Visual Header --}}
                    <div class="product-icon-box">
                        <i class="fas {{ $icon }} {{ $color }}"></i>
                        <div class="stock-badge {{ $part->stock <= 5 ? 'low-stock' : 'safe-stock' }}">
                            <i class="fas {{ $part->stock <= 5 ? 'fa-exclamation-circle' : 'fa-check-circle' }} me-1"></i>
                            {{ $part->stock }} {{ $part->unit }}
                        </div>
                    </div>

                    {{-- 4. Card Body --}}
                    <div class="card-body pt-3 pb-2 px-3">
                        <div class="part-code mb-1">{{ $part->part_number }}</div>
                        <h6 class="fw-bold text-dark mb-2 text-truncate" title="{{ $part->name }}">{{ $part->name }}</h6>
                        
                        <div class="row align-items-end mt-3">
                            <div class="col">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Harga Jual</small>
                                <div class="fw-bold text-danger fs-5">
                                    Rp {{ number_format($part->price_sell, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto text-end">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Modal</small>
                                <small class="text-secondary fw-bold">Rp {{ number_format($part->price_buy, 0, ',', '.') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MODAL EDIT (Include inside loop) --}}
            <div class="modal fade" id="modalEdit{{ $part->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content border-0 rounded-4">
                        <div class="modal-header border-bottom-0 pb-0">
                            <h5 class="modal-title fw-bold">Edit Barang</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('inventory.update', $part->id) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Kode Part</label>
                                    <input type="text" class="form-control bg-light" value="{{ $part->part_number }}" readonly>
                                    <small class="text-muted" style="font-size: 0.7rem">*Kode part tidak dapat diubah</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Nama Barang</label>
                                    <input type="text" name="name" class="form-control" value="{{ $part->name }}" required>
                                </div>
                                <div class="row g-2">
                                    <div class="col-6 mb-3">
                                        <label class="form-label small fw-bold">Harga Beli</label>
                                        <input type="number" name="price_buy" class="form-control" value="{{ $part->price_buy }}" required>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label small fw-bold">Harga Jual</label>
                                        <input type="number" name="price_sell" class="form-control" value="{{ $part->price_sell }}" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Stok Saat Ini</label>
                                    <input type="number" name="stock" class="form-control" value="{{ $part->stock }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Kategori</label>
                                    <select name="category" class="form-select" required>
                                        <option value="">Pilih Kategori...</option>
                                        <option value="oli" {{ $part->category == 'oli' ? 'selected' : '' }}>Oli / Pelumas</option>
                                        <option value="ban" {{ $part->category == 'ban' ? 'selected' : '' }}>Ban (Tire)</option>
                                        <option value="cvt" {{ $part->category == 'cvt' ? 'selected' : '' }}>CVT & Transmisi</option>
                                        <option value="sparepart" {{ $part->category == 'sparepart' ? 'selected' : '' }}>Sparepart Umum</option>
                                        <option value="busi" {{ $part->category == 'busi' ? 'selected' : '' }}>Kelistrikan / Busi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold w-100">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        @empty
            <div class="col-12 text-center py-5">
                <div class="bg-light rounded-circle d-inline-flex p-4 mb-3 text-secondary">
                    <i class="fas fa-box-open fa-3x"></i>
                </div>
                <h5 class="fw-bold text-secondary">Data Tidak Ditemukan</h5>
                <p class="text-muted">Coba kata kunci lain atau tambahkan barang baru.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-5 d-flex justify-content-center">
        {{ $parts->withQueryString()->links() }}
    </div>
</div>

{{-- MODAL CREATE (Tambah Barang) --}}
<div class="modal fade" id="modalCreate" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Barang Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('inventory.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Kode Part</label>
                        <input type="text" name="part_number" class="form-control text-uppercase" placeholder="CONTOH: 123-AHM-001" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Nama Barang</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Oli MPX 2" required>
                    </div>
                    <div class="row g-2">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">Harga Beli (Modal)</label>
                            <input type="number" name="price_buy" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">Harga Jual</label>
                            <input type="number" name="price_sell" class="form-control" required>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">Stok Awal</label>
                            <input type="number" name="stock" class="form-control" value="0" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">Satuan</label>
                            <select name="unit" class="form-select">
                                <option value="pcs">Pcs</option>
                                <option value="btl">Botol</option>
                                <option value="set">Set</option>
                                <option value="ltr">Liter</option>
                            </select>
                        </div>
                    </div>
                    {{-- Input Kategori Baru --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Kategori</label>
                        <select name="category" class="form-select" required>
                            <option value="">Pilih Kategori...</option>
                            <option value="oli">Oli / Pelumas</option>
                            <option value="ban">Ban (Tire)</option>
                            <option value="cvt">CVT & Transmisi</option>
                            <option value="sparepart">Sparepart Umum</option>
                            <option value="busi">Kelistrikan / Busi</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold w-100">Simpan ke Gudang</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- CUSTOM DELETE ALERT COMPONENT --}}
<div class="modal fade" id="deleteAlertComponent" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg text-center p-4">
            
            {{-- Icon Alert Animasi Sederhana --}}
            <div class="mb-3">
                <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <i class="fas fa-exclamation-triangle fa-3x"></i>
                </div>
            </div>

            <h4 class="fw-bold mb-2">Hapus Barang Ini?</h4>
            <p class="text-muted mb-4">
                Anda akan menghapus <span id="deleteItemName" class="fw-bold text-dark"></span>.<br>
                Tindakan ini tidak dapat dibatalkan.
            </p>

            <div class="d-flex justify-content-center gap-2">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">
                    Batal
                </button>
                
                {{-- Form Delete sesungguhnya ada di sini --}}
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">
                        Ya, Hapus
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- SCRIPT INTERAKSI --}}
<script>
    // Fungsi untuk Toggle Menu Blur
    function toggleMenu(id) {
        var card = document.getElementById('card-' + id);
        
        // Cek apakah card ini sedang aktif
        if (card.classList.contains('menu-active')) {
            card.classList.remove('menu-active');
        } else {
            // Tutup semua menu lain dulu biar rapi
            document.querySelectorAll('.product-card').forEach(function(el) {
                el.classList.remove('menu-active');
            });
            // Buka menu yang diklik
            card.classList.add('menu-active');
        }
    }

    // Fungsi untuk Memunculkan Component Delete Alert
    function showDeleteAlert(id, name) {
        // 1. Set Nama Barang di Modal
        document.getElementById('deleteItemName').innerText = '"' + name + '"';
        
        // 2. Set Action URL pada Form Delete
        var url = "{{ route('inventory.destroy', ':id') }}";
        url = url.replace(':id', id);
        document.getElementById('deleteForm').action = url;

        // 3. Tampilkan Modal Bootstrap
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteAlertComponent'));
        deleteModal.show();
    }

    // Tutup menu jika klik di luar area
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.product-card')) {
            document.querySelectorAll('.product-card').forEach(function(el) {
                el.classList.remove('menu-active');
            });
        }
    });
</script>
@endsection