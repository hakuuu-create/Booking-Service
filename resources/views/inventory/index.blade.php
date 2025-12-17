@extends('layouts.app')

@section('title', 'Gudang Sparepart')

@section('content')
<style>
    /* --- CUSTOM STYLES FOR INVENTORY --- */
    
    /* 1. Horizontal Filter Bar (Snackbar Style) */
    .filter-bar-container {
        overflow-x: auto;
        white-space: nowrap;
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
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

    /* 2. Product Card Modern */
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

    /* Action Buttons Overlay (Muncul saat hover) */
    .card-actions {
        position: absolute;
        top: 10px;
        left: 10px;
        display: flex;
        gap: 5px;
        opacity: 0;
        transition: 0.2s;
    }
    .product-card:hover .card-actions {
        opacity: 1;
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
            {{-- Logic Filter: Menggunakan parameter search URL --}}
            <a href="{{ route('inventory.index') }}" 
               class="filter-chip {{ !request('search') ? 'active' : '' }}">
               <i class="fas fa-th-large me-1"></i> Semua
            </a>
            
            <a href="{{ route('inventory.index', ['search' => 'Oli']) }}" 
               class="filter-chip {{ request('search') == 'Oli' ? 'active' : '' }}">
               <i class="fas fa-oil-can me-1"></i> Oli
            </a>
            
            <a href="{{ route('inventory.index', ['search' => 'Ban']) }}" 
               class="filter-chip {{ request('search') == 'Ban' ? 'active' : '' }}">
               <i class="fas fa-compact-disc me-1"></i> Ban
            </a>
            
            <a href="{{ route('inventory.index', ['search' => 'Kampas']) }}" 
               class="filter-chip {{ request('search') == 'Kampas' ? 'active' : '' }}">
               <i class="fas fa-stop-circle me-1"></i> Kampas Rem
            </a>

            <a href="{{ route('inventory.index', ['search' => 'Filter']) }}" 
               class="filter-chip {{ request('search') == 'Filter' ? 'active' : '' }}">
               <i class="fas fa-filter me-1"></i> Filter
            </a>

            <a href="{{ route('inventory.index', ['search' => 'Busi']) }}" 
               class="filter-chip {{ request('search') == 'Busi' ? 'active' : '' }}">
               <i class="fas fa-bolt me-1"></i> Busi
            </a>

            <a href="{{ route('inventory.index', ['search' => 'Roller']) }}" 
                class="filter-chip {{ request('search') == 'Roller' ? 'active' : '' }}">
                <i class="fas fa-cog me-1"></i> CVT Parts
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
                if(str_contains(strtolower($part->name), 'oli')) { $icon = 'fa-oil-can'; $color = 'text-warning'; }
                elseif(str_contains(strtolower($part->name), 'ban')) { $icon = 'fa-compact-disc'; $color = 'text-dark'; }
                elseif(str_contains(strtolower($part->name), 'kampas')) { $icon = 'fa-stop-circle'; $color = 'text-danger'; }
                elseif(str_contains(strtolower($part->name), 'aki')) { $icon = 'fa-car-battery'; $color = 'text-primary'; }
                elseif(str_contains(strtolower($part->name), 'busi')) { $icon = 'fa-bolt'; $color = 'text-warning'; }
                elseif(str_contains(strtolower($part->name), 'belt')) { $icon = 'fa-sync-alt'; $color = 'text-dark'; }
            @endphp

            <div class="col">
                <div class="product-card h-100">
                    {{-- Overlay Action Buttons --}}
                    <div class="card-actions">
                        <button class="btn btn-sm btn-light shadow-sm rounded-circle" 
                                data-bs-toggle="modal" data-bs-target="#modalEdit{{ $part->id }}" title="Edit">
                            <i class="fas fa-pen text-primary small"></i>
                        </button>
                        <form action="{{ route('inventory.destroy', $part->id) }}" method="POST" onsubmit="return confirm('Hapus barang ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-light shadow-sm rounded-circle" title="Hapus">
                                <i class="fas fa-trash text-danger small"></i>
                            </button>
                        </form>
                    </div>

                    {{-- Visual Header --}}
                    <div class="product-icon-box">
                        <i class="fas {{ $icon }} {{ $color }}"></i>
                        <div class="stock-badge {{ $part->stock <= 5 ? 'low-stock' : 'safe-stock' }}">
                            <i class="fas {{ $part->stock <= 5 ? 'fa-exclamation-circle' : 'fa-check-circle' }} me-1"></i>
                            {{ $part->stock }} {{ $part->unit }}
                        </div>
                    </div>

                    {{-- Card Body --}}
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
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold w-100">Simpan ke Gudang</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection