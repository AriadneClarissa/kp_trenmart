<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('bundling_items', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('bundling_id');
        
        // Sesuaikan dengan model Produk Anda: kd_produk adalah string
        $table->string('product_id'); 
        
        $table->integer('quantity')->default(1);
        $table->decimal('price_at_snapshot', 15, 2);
        $table->timestamps();

        // Relasi ke tabel bundlings
        $table->foreign('bundling_id')->references('id')->on('bundlings')->onDelete('cascade');
        
        // Relasi ke tabel produk (sesuai model Anda)
        $table->foreign('product_id')->references('kd_produk')->on('produk')->onDelete('cascade');
    });
}

    public function down(): void
    {
        Schema::dropIfExists('bundling_items');
    }
};