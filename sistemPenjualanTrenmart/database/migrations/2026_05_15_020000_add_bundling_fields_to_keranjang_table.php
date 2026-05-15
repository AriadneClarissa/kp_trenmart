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
        Schema::table('keranjang', function (Blueprint $table) {
            if (!Schema::hasColumn('keranjang', 'harga_at_time')) {
                $table->decimal('harga_at_time', 12, 2)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keranjang', function (Blueprint $table) {
            if (Schema::hasColumn('keranjang', 'harga_at_time')) {
                $table->dropColumn('harga_at_time');
            }
        });
    }
};
