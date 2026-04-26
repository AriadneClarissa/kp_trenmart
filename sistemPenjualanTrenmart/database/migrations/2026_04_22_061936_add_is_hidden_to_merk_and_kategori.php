<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Reverse the migrations.
     */
    public function up(): void
    {
        Schema::table('merk', function (Blueprint $table) {
            $table->boolean('is_hidden')->default(false)->after('nama_merk');
        });
        Schema::table('kategori', function (Blueprint $table) {
            $table->boolean('is_hidden')->default(false)->after('nama_kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('merk', function (Blueprint $table) {
            $table->dropColumn('is_hidden');
        });
        Schema::table('kategori', function (Blueprint $table) {
            $table->dropColumn('is_hidden');
        });
    }
};
