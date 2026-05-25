<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipping_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('shipping_settings', 'flat_rate')) {
                $table->integer('flat_rate')->default(15000)->after('id');
            }
        });

        if (Schema::hasColumn('shipping_settings', 'price_per_km')) {
            DB::table('shipping_settings')->update([
                'flat_rate' => DB::raw('price_per_km'),
            ]);
        }

        Schema::table('shipping_settings', function (Blueprint $table) {
            if (Schema::hasColumn('shipping_settings', 'free_limit')) {
                $table->dropColumn('free_limit');
            }

            if (Schema::hasColumn('shipping_settings', 'price_per_km')) {
                $table->dropColumn('price_per_km');
            }
        });
    }

    public function down(): void
    {
        Schema::table('shipping_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('shipping_settings', 'free_limit')) {
                $table->float('free_limit')->default(1.0)->after('id');
            }

            if (! Schema::hasColumn('shipping_settings', 'price_per_km')) {
                $table->integer('price_per_km')->default(2000)->after('free_limit');
            }

            if (Schema::hasColumn('shipping_settings', 'flat_rate')) {
                $table->dropColumn('flat_rate');
            }
        });
    }
};