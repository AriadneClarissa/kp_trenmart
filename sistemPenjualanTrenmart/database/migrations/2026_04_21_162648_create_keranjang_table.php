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
    // Nama tabel diubah menjadi tunggal: 'keranjang'
    Schema::create('keranjang', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->unsignedBigInteger('kd_produk'); 
        $table->integer('jumlah')->default(1);
        $table->timestamps();

        // Relasi ke tabel produk (sesuai PK di tabel produk kamu)
        $table->foreign('kd_produk')->references('kd_produk')->on('produk');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keranjang');
    }
};
