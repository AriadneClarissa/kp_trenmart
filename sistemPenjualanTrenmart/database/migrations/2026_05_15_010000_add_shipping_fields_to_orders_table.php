<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('shipping_address')->nullable()->after('pickup_method');
            $table->decimal('shipping_distance_km', 8, 2)->nullable()->after('shipping_address');
            $table->decimal('shipping_cost', 15, 2)->default(0)->after('shipping_distance_km');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_address', 'shipping_distance_km', 'shipping_cost']);
        });
    }
};
