@props(['initialParts' => '[]'])

<div class="card border-0 shadow-sm rounded-4" x-data="sparepartCart()">
    <div class="card-header bg-white py-3 fw-bold border-bottom d-flex justify-content-between align-items-center">
        <span><i class="fas fa-box-open text-danger me-2"></i> Penggunaan Sparepart</span>
        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" onclick="addItem()">
            <i class="fas fa-plus"></i> Tambah Item
        </button>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="partsTable">
                <thead class="bg-light text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-4">Nama Barang</th>
                        <th width="150">Harga (Rp)</th>
                        <th width="100">Qty</th>
                        <th width="150" class="text-end">Subtotal</th>
                        <th width="50"></th>
                    </tr>
                </thead>
                <tbody id="cartBody">
                    </tbody>
            </table>
        </div>
        
        <div id="emptyCartMsg" class="text-center py-4 text-muted" style="display: none;">
            <small>Belum ada sparepart yang ditambahkan.</small>
        </div>
    </div>

    <input type="hidden" name="spareparts" id="sparepartsJson">
</div>

{{-- SCRIPT LOGIC CART --}}
<script>
    // Data Dummy Barang (Nanti bisa ambil dari DB Inventory)
    const inventoryItems = [
        { name: 'Oli MPX 1', price: 55000 },
        { name: 'Oli MPX 2', price: 60000 },
        { name: 'Kampas Rem Depan', price: 45000 },
        { name: 'Kampas Rem Belakang', price: 35000 },
        { name: 'V-Belt Beat/Vario', price: 125000 },
        { name: 'Roller Set', price: 65000 },
        { name: 'Busi NGK', price: 15000 },
        { name: 'Filter Udara', price: 40000 },
    ];

    let cart = {!! $initialParts !!}; // Load data awal dari DB jika ada
    if(!Array.isArray(cart)) cart = [];

    // Fungsi Render Tabel
    function renderCart() {
        const tbody = document.getElementById('cartBody');
        const emptyMsg = document.getElementById('emptyCartMsg');
        const jsonInput = document.getElementById('sparepartsJson');
        const baseServicePrice = parseFloat(document.getElementById('baseServicePrice').value) || 0;
        const totalDisplay = document.getElementById('grandTotalDisplay');
        const totalInput = document.getElementById('grandTotalInput');

        tbody.innerHTML = '';
        let totalParts = 0;

        if(cart.length === 0) {
            emptyMsg.style.display = 'block';
        } else {
            emptyMsg.style.display = 'none';
            cart.forEach((item, index) => {
                const subtotal = item.price * item.qty;
                totalParts += subtotal;

                const row = `
                    <tr>
                        <td class="ps-4">
                            <input type="text" class="form-control form-control-sm border-0 bg-transparent fw-bold" 
                                value="${item.name}" readonly>
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm" 
                                value="${item.price}" onchange="updateItem(${index}, 'price', this.value)">
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm text-center" 
                                value="${item.qty}" min="1" onchange="updateItem(${index}, 'qty', this.value)">
                        </td>
                        <td class="text-end fw-bold text-dark">
                            Rp ${subtotal.toLocaleString('id-ID')}
                        </td>
                        <td class="text-end pe-3">
                            <button type="button" class="btn btn-link text-danger p-0" onclick="removeItem(${index})">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        }

        // Simpan ke Hidden Input (JSON)
        jsonInput.value = JSON.stringify(cart);

        // Update Grand Total (Jasa + Sparepart)
        const grandTotal = baseServicePrice + totalParts;
        totalDisplay.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
        totalInput.value = grandTotal;
    }

    // Fungsi Tambah Item Baru
    window.addItem = function() {
        // Logika sederhana: Menambahkan baris kosong / default
        // Di sistem riil, ini bisa membuka Modal Pencarian Barang
        // Untuk demo, kita ambil barang random atau kosong
        cart.push({ name: 'Sparepart Baru (Edit Nama)', price: 0, qty: 1 });
        renderCart();
    };

    // Fungsi Update Item (Harga/Qty)
    window.updateItem = function(index, field, value) {
        cart[index][field] = parseFloat(value) || 0;
        renderCart();
    };

    // Fungsi Hapus Item
    window.removeItem = function(index) {
        cart.splice(index, 1);
        renderCart();
    };

    // Jalankan saat load
    document.addEventListener('DOMContentLoaded', () => {
        renderCart();
    });
</script>