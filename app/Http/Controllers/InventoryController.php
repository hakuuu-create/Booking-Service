<?php

namespace App\Http\Controllers;

use App\Models\SparePart;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Menampilkan daftar barang (Index) dengan fitur Search & Filter
     */
    public function index(Request $request)
    {
        // Mulai query
        $query = SparePart::query();

        // Logika Pencarian / Filter
        if ($request->has('search') && $request->search != '') {
            $keyword = $request->search;
            
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('part_number', 'like', '%' . $keyword . '%');
            });
        }

        // Urutkan: Stok menipis paling atas, lalu barang terbaru
        // Agar admin langsung 'aware' jika ada barang mau habis
        $parts = $query->orderBy('stock', 'asc')
                       ->latest()
                       ->paginate(12) // 12 item per halaman (cocok untuk grid 3 atau 4 kolom)
                       ->withQueryString(); // Agar parameter search tetap ada saat pindah halaman

        return view('inventory.index', compact('parts'));
    }

    /**
     * Menyimpan barang baru ke database
     */
    public function store(Request $request)
    {
        // Validasi Input
        $request->validate([
            'part_number' => 'required|unique:spare_parts,part_number|max:50',
            'name'        => 'required|string|max:255',
            'price_buy'   => 'required|numeric|min:0',
            'price_sell'  => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'unit'        => 'required|string|in:pcs,btl,set,ltr', // Sesuaikan dengan option di view
        ]);

        // Simpan Data
        SparePart::create([
            'part_number' => strtoupper($request->part_number), // Paksa huruf besar
            'name'        => $request->name,
            'price_buy'   => $request->price_buy,
            'price_sell'  => $request->price_sell,
            'stock'       => $request->stock,
            'unit'        => $request->unit,
        ]);

        return redirect()->back()->with('success', 'Barang berhasil ditambahkan ke gudang!');
    }

    /**
     * Mengupdate data barang (Kode Part tidak boleh diubah)
     */
    public function update(Request $request, $id)
    {
        $part = SparePart::findOrFail($id);

        // Validasi (Tanpa part_number karena readonly)
        $request->validate([
            'name'       => 'required|string|max:255',
            'price_buy'  => 'required|numeric|min:0',
            'price_sell' => 'required|numeric|min:0',
            'stock'      => 'required|integer|min:0',
        ]);

        // Update Data
        $part->update([
            'name'       => $request->name,
            'price_buy'  => $request->price_buy,
            'price_sell' => $request->price_sell,
            'stock'      => $request->stock,
            // 'unit' kita biarkan tetap (atau tambahkan input di modal jika ingin bisa diubah)
        ]);

        return redirect()->back()->with('success', 'Informasi barang berhasil diperbarui!');
    }

    /**
     * Menghapus barang
     */
    public function destroy($id)
    {
        $part = SparePart::findOrFail($id);
        
        $part->delete();

        return redirect()->back()->with('success', 'Barang telah dihapus dari gudang.');
    }
}