<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('spare_parts', function (Blueprint $table) {
            $table->id();
            
            // Identitas Barang
            $table->string('part_number')->unique(); // Kode Part (Contoh: 08232-2MA-K0LN1)
            $table->string('name');                  // Nama Barang (Contoh: Oli MPX 2)
            
            // Keuangan (Integer agar aman tanpa koma desimal)
            $table->integer('price_buy');            // Harga Beli / Modal
            $table->integer('price_sell');           // Harga Jual ke Konsumen
            
            // Stok & Fisik
            $table->integer('stock')->default(0);    // Jumlah Stok
            $table->string('unit')->default('pcs');  // Satuan (pcs, btl, set)
            $table->string('location')->nullable();  // Lokasi Rak (Opsional: Rak A1, Laci 2)
            
            // Keterangan Tambahan (Opsional)
            $table->text('description')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spare_parts');
    }
};