<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('keranjang', function (Blueprint $table) {
            // Tambahkan kolom, sesuaikan tipe datanya (misal: integer/bigInteger)
            $table->unsignedBigInteger('bundling_id')->nullable()->after('kd_produk');
        });
    }

    public function down()
    {
        Schema::table('keranjang', function (Blueprint $table) {
            $table->dropColumn('bundling_id');
        });
    }
};
