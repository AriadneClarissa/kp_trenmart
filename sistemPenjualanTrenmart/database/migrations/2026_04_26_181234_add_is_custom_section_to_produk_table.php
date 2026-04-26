<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            // Menambahkan kolom boolean dengan nilai default false
            // placed after is_highlight agar urutannya rapi di database
            $table->boolean('is_custom_section')->default(false)->after('is_highlight');
        });
    }

    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            // Menghapus kolom jika migrasi di-rollback
            $table->dropColumn('is_custom_section');
        });
    }
};