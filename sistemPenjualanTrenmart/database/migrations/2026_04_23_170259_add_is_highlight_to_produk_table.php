<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::table('produk', function (Blueprint $table) {
        // Cek dulu apakah kolom is_highlight sudah ada secara tidak sengaja
        if (!Schema::hasColumn('produk', 'is_highlight')) {
            $table->boolean('is_highlight')->default(false)->after('status');
        }
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('produk', function (Blueprint $table) {
        // Ini untuk menghapus kolom jika migrasi di-rollback
        $table->dropColumn('is_highlight');
    });
}
};
