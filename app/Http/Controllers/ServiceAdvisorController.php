<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ServiceAdvisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // Import yang benar dan bersih

class ServiceAdvisorController extends Controller
{
    // Menampilkan halaman kerja Advisor
    public function show($id = null)
    {
        // --- 1. BUAT DATA DUMMY BOOKING ---
        // Kita pakai stdClass (Objek Kosong) agar tidak perlu konek Database
        $booking = new \stdClass();
        $booking->id = 1; // ID Palsu
        $booking->customer_name = 'Budi (Data Dummy)';
        $booking->plate_number = 'B 1234 TES';
        $booking->vehicle_type = 'matic';
        $booking->status = 'approved'; // Coba status: approved, on_progress, atau done
        
        // Buat Data Dummy Service (Nested)
        $service = new \stdClass();
        $service->name = 'Servis Lengkap (Dummy)';
        $service->price = 100000;
        
        // Masukkan service ke dalam booking
        $booking->service = $service;

        // --- 2. BUAT DATA DUMMY ADVISOR ---
        $advisor = new \stdClass();
        $advisor->customer_complaint = 'Rem belakang bunyi cit-cit (Ini teks dummy)';
        $advisor->jobs = 'Cek CVT, Ganti Oli';
        
        // Data Dummy Sparepart (JSON)
        // Ini agar tabel cart tidak error saat di-load
        $advisor->spareparts = json_encode([
            ['name' => 'Oli MPX 2', 'price' => 55000, 'qty' => 1],
            ['name' => 'Busi NGK', 'price' => 15000, 'qty' => 1]
        ]);

        // --- 3. KIRIM KE VIEW ---
        return view('advisor.show', compact('booking', 'advisor'));
    }

    // Method Create (Opsional, jika Anda butuh halaman terpisah selain show)
    // Jika tidak dipakai, bisa dihapus.
    public function create()
    {
        $bookings = \App\Models\Booking::with('service')->get();
        return view('advisor.create', compact('bookings'));
    }

    // Menyimpan atau Mengupdate Data Pengerjaan (Logika Utama)
    public function update(Request $request, Booking $booking)
    {
        // 1. Validasi Input
        $request->validate([
            'customer_complaint' => 'nullable|string',
            'jobs'               => 'nullable|string',
            'spareparts'         => 'nullable|json', // Pastikan input adalah JSON string valid
            'total_estimation'   => 'required|numeric|min:0',
            'status'             => 'required|in:approved,on_progress,done,cancelled',
        ]);

        // Gunakan Transaction agar data konsisten
        DB::transaction(function () use ($request, $booking) {
            
            // 2. Hitung Total Biaya Sparepart dari JSON
            // Decode JSON menjadi Array PHP
            $parts = json_decode($request->spareparts, true);
            
            // Jika JSON invalid atau null, default ke array kosong
            if (!is_array($parts)) {
                $parts = [];
            }

            $partCost = 0;
            foreach($parts as $part) {
                // Pastikan key 'price' dan 'qty' ada untuk menghindari error
                $price = isset($part['price']) ? (int)$part['price'] : 0;
                $qty   = isset($part['qty']) ? (int)$part['qty'] : 0;
                $partCost += ($price * $qty);
            }

            // 3. Update atau Buat Data di tabel service_advisors
            ServiceAdvisor::updateOrCreate(
                ['booking_id' => $booking->id], // Kunci pencarian (Foreign Key)
                [
                    'customer_complaint' => $request->customer_complaint,
                    'jobs'               => $request->jobs,
                    'spareparts'         => $request->spareparts, // Simpan JSON mentah ke database
                    'estimation_cost'    => $booking->service->price, // Harga jasa diambil dari Master Service
                    'estimation_parts'   => $partCost, // Total harga sparepart yang dihitung
                    'total_estimation'   => $request->total_estimation, // Grand total dari form (Jasa + Part)
                    'advisor_notes'      => 'Diupdate oleh admin', // Catatan default (opsional)
                ]
            );

            // 4. Update Status di tabel bookings
            $booking->update([
                'status' => $request->status
            ]);
        });

        // 5. Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data pengerjaan berhasil disimpan!');
    }

    // Mencetak Invoice/PKB ke PDF
    public function print($id)
    {
        // Pastikan Anda memuat relasi booking dan service untuk ditampilkan di PDF
        $advisor = ServiceAdvisor::with('booking.service')->findOrFail($id);

        // Decode JSON sparepart agar bisa di-looping di view PDF
        if (is_string($advisor->spareparts)) {
            $advisor->spareparts = json_decode($advisor->spareparts, true);
        }

        // Load View PDF
        $pdf = Pdf::loadView('advisor.print', compact('advisor'))
                  ->setPaper('A4', 'portrait');

        // Stream PDF ke browser
        return $pdf->stream('service_advisor_'.$advisor->id.'.pdf');
    }
}