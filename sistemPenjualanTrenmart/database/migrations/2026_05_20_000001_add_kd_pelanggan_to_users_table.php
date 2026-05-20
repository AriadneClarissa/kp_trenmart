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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'kd_pelanggan')) {
                $table->string('kd_pelanggan', 50)->nullable()->unique()->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'kd_pelanggan')) {
                $table->dropUnique(['kd_pelanggan']);
                $table->dropColumn('kd_pelanggan');
            }
        });
    }
};