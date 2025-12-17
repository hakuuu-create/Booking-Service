<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SparePartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\SparePart::insert([
            [
                'part_number' => 'AHM-OIL-MPX2',
                'name' => 'Oli MPX 2 (Matic) 0.8L',
                'price_buy' => 45000,
                'price_sell' => 55000,
                'stock' => 50,
                'unit' => 'btl',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'part_number' => 'K25-900',
                'name' => 'Kampas Rem Depan (Beat/Vario)',
                'price_buy' => 35000,
                'price_sell' => 55000,
                'stock' => 20,
                'unit' => 'set',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'part_number' => 'NGK-MR9C',
                'name' => 'Busi NGK MR9C-9N',
                'price_buy' => 20000,
                'price_sell' => 35000,
                'stock' => 4, // Stok tipis (akan muncul badge merah)
                'unit' => 'pcs',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'part_number' => 'IRC-80/90-14',
                'name' => 'Ban Luar IRC 80/90-14 Tubeless',
                'price_buy' => 190000,
                'price_sell' => 235000,
                'stock' => 10,
                'unit' => 'pcs',
                'created_at' => now(), 'updated_at' => now(),
            ]
        ]);
    }
}
