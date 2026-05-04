<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            // Kita hapus perintah ->after() agar kolom ditambahkan di urutan paling akhir saja
            $table->string('foto_2')->nullable();
            $table->string('foto_3')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn(['foto_2', 'foto_3']);
        });
    }
};