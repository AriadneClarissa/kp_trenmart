<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bundlings', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: Paket Hemat Alat Tulis
            $table->decimal('total_normal_price', 15, 2); // Subtotal otomatis
            $table->decimal('bundling_price', 15, 2); // Harga promo yang diinput admin
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bundlings');
    }
};