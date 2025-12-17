<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MasterDataSeeder extends Seeder
{
    public function run()
    {
        // ==========================================
        // 1. SEEDER USERS (Admin & Customer Realistis)
        // ==========================================
        
        // Admin Bengkel
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin AHASS',
            'email' => 'admin@ahass.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Customer 1: Budi (Pengguna Matic)
        $userBudi = DB::table('users')->insertGetId([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '085678901234', // WA Aktif dummy
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Customer 2: elisa (Pengguna Bebek)
        $userelisa = DB::table('users')->insertGetId([
            'name' => 'elisa',
            'email' => 'elisa@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '081345678901',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ==========================================
        // 2. SEEDER SERVICES (Layanan & Harga Asli)
        // ==========================================
        
        $services = [
            ['name' => 'Ganti Oli MPX 2 (Matic)', 'price' => 55000],
            ['name' => 'Servis Ringan / Tune Up', 'price' => 45000],
            ['name' => 'Servis CVT (Pembersihan)', 'price' => 35000],
            ['name' => 'Servis Lengkap (Paket)', 'price' => 85000],
            ['name' => 'Ganti Kampas Rem Depan', 'price' => 25000],
            ['name' => 'Servis Injeksi', 'price' => 65000],
        ];

        foreach ($services as $service) {
            DB::table('services')->insert(array_merge($service, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // Ambil ID Service untuk relasi ke booking
        $svcOli = DB::table('services')->where('name', 'Ganti Oli MPX 2 (Matic)')->value('id');
        $svcLengkap = DB::table('services')->where('name', 'Servis Lengkap (Paket)')->value('id');

        // ==========================================
        // 3. SEEDER BOOKINGS (Data Transaksi)
        // ==========================================

        // Booking 1: Selesai (Done) - Budi Service Lengkap
        $bookingDoneId = DB::table('bookings')->insertGetId([
            'user_id' => $userBudi,
            'service_id' => $svcLengkap,
            'customer_name' => 'Budi Santoso',
            'customer_whatsapp' => '085678901234',
            'vehicle_type' => 'matic',
            'plate_number' => 'N 1234 AB',
            'booking_date' => Carbon::yesterday()->format('Y-m-d 09:00:00'), // Kemarin jam 9
            'status' => 'done',
            'quota' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Booking 2: Menunggu (Approved) - elisa Ganti Oli (Hari ini)
        $bookingApprovedId = DB::table('bookings')->insertGetId([
            'user_id' => $userelisa,
            'service_id' => $svcOli,
            'customer_name' => 'elisa',
            'customer_whatsapp' => '081345678901',
            'vehicle_type' => 'bebek',
            'plate_number' => 'B 4567 XYZ',
            'booking_date' => Carbon::today()->format('Y-m-d 10:00:00'), // Hari ini jam 10
            'status' => 'approved',
            'quota' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Booking 3: Baru Masuk (Pending) - Budi Servis Lagi (Besok)
        DB::table('bookings')->insert([
            'user_id' => $userBudi,
            'service_id' => $svcOli,
            'customer_name' => 'Budi Santoso',
            'customer_whatsapp' => '085678901234',
            'vehicle_type' => 'matic',
            'plate_number' => 'N 1234 AB',
            'booking_date' => Carbon::tomorrow()->format('Y-m-d 08:00:00'), // Besok jam 8
            'status' => 'pending',
            'quota' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ==========================================
        // 4. SEEDER SERVICE ADVISOR (PKB / Work Order)
        // ==========================================
        
        // Membuat data PKB untuk Booking 1 (Yang statusnya DONE)
        
        // JSON Sparepart yang dipakai
        $spareparts = json_encode([
            [
                'name' => 'Kampas Rem Depan (K25)', 
                'price' => 45000, 
                'qty' => 1
            ],
            [
                'name' => 'Oli Gardan', 
                'price' => 15000, 
                'qty' => 1
            ]
        ]);

        DB::table('service_advisors')->insert([
            'booking_id' => $bookingDoneId,
            
            // Keluhan & Pekerjaan Nyata
            'customer_complaint' => 'Tarikan awal berat dan rem depan bunyi cit-cit.',
            'jobs' => '1. Cek Rollers & V-Belt (Aman)\n2. Ganti Kampas Rem Depan\n3. Ganti Oli Gardan\n4. Pembersihan Filter Udara',
            
            // Sparepart
            'spareparts' => $spareparts,
            
            // Kalkulasi Biaya (Jasa 85rb + Part 60rb)
            'estimation_cost' => 85000, // Harga Jasa Paket Lengkap
            'estimation_parts' => 60000, // 45rb + 15rb
            'total_estimation' => 145000, // Total
            
            'advisor_notes' => 'Disarankan ganti V-Belt 3000km lagi.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}