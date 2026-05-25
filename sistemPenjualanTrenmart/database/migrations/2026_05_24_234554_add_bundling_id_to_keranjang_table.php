<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('keranjang', 'bundling_id')) {
            Schema::table('keranjang', function (Blueprint $table) {
                $table->unsignedBigInteger('bundling_id')->nullable()->after('kd_produk');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('keranjang', 'bundling_id')) {
            Schema::table('keranjang', function (Blueprint $table) {
                $table->dropColumn('bundling_id');
            });
        }
    }
};
