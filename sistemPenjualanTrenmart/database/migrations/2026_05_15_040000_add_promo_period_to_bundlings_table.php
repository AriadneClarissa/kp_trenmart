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
        Schema::table('bundlings', function (Blueprint $table) {
            if (!Schema::hasColumn('bundlings', 'promo_start_at')) {
                $table->dateTime('promo_start_at')->nullable()->after('description');
            }

            if (!Schema::hasColumn('bundlings', 'promo_end_at')) {
                $table->dateTime('promo_end_at')->nullable()->after('promo_start_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bundlings', function (Blueprint $table) {
            if (Schema::hasColumn('bundlings', 'promo_end_at')) {
                $table->dropColumn('promo_end_at');
            }

            if (Schema::hasColumn('bundlings', 'promo_start_at')) {
                $table->dropColumn('promo_start_at');
            }
        });
    }
};
