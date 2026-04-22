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
    Schema::create('produk', function (Blueprint $table) {
        $table->string('kd_produk')->primary(); // PK: Kd_produk
        $table->string('nama_produk');
        $table->text('deskripsi')->nullable();
        $table->string('satuan'); // Pcs, Pak, Dus
        $table->decimal('harga_jual_umum', 12, 2);
        $table->decimal('harga_jual_langganan', 12, 2);
        $table->integer('stok_tersedia');
        $table->string('gambar')->nullable();
        $table->string('status'); // Tersedia / Habis
        
        // Relasi (Foreign Key) sesuai ERD
        $table->string('kd_kategori');
        $table->string('kd_merk');
        
        // Menghubungkan FK ke tabel referensi
        $table->foreign('kd_kategori')->references('kd_kategori')->on('kategori')->onDelete('cascade');
        $table->foreign('kd_merk')->references('kd_merk')->on('merk')->onDelete('cascade');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
