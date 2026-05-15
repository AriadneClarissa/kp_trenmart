<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set default 250 for satuan containing 'pcs'
        DB::table('produk')
            ->where(function($q){ $q->whereNull('stok_minimal')->orWhere('stok_minimal', 0); })
            ->whereRaw("LOWER(COALESCE(satuan, '')) LIKE ?", ['%pcs%'])
            ->update(['stok_minimal' => 250]);

        // Set default 10 for satuan containing 'lusin' or 'dozen'
        DB::table('produk')
            ->where(function($q){ $q->whereNull('stok_minimal')->orWhere('stok_minimal', 0); })
            ->whereRaw("LOWER(COALESCE(satuan, '')) LIKE ?", ['%lusin%'])
            ->update(['stok_minimal' => 10]);

        DB::table('produk')
            ->where(function($q){ $q->whereNull('stok_minimal')->orWhere('stok_minimal', 0); })
            ->whereRaw("LOWER(COALESCE(satuan, '')) LIKE ?", ['%dozen%'])
            ->update(['stok_minimal' => 10]);

        // Any remaining unset stok_minimal keep as 0
        DB::table('produk')
            ->whereNull('stok_minimal')
            ->orWhere('stok_minimal', 0)
            ->update(['stok_minimal' => 0]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed; leave values as-is. (Alternatively set to 0)
    }
};
