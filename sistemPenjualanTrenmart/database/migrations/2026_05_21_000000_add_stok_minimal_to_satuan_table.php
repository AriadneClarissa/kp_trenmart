<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('satuan', 'stok_minimal')) {
            Schema::table('satuan', function (Blueprint $table) {
                $table->integer('stok_minimal')->default(0)->after('nama_satuan');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('satuan', 'stok_minimal')) {
            Schema::table('satuan', function (Blueprint $table) {
                $table->dropColumn('stok_minimal');
            });
        }
    }
};