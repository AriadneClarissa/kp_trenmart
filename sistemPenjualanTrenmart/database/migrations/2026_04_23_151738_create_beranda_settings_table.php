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
    Schema::create('beranda_settings', function (Blueprint $table) {
        $table->id();
        $table->string('key')->unique(); // Contoh: 'judul_terbaru'
        $table->text('value')->nullable(); // Isinya: 'Koleksi Alat Tulis Terbaru'
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beranda_settings');
    }
};
