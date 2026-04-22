<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::table('merk', function (Blueprint $table) {
        $table->boolean('is_hidden')->default(false)->after('nama_merk');
    });
    Schema::table('kategori', function (Blueprint $table) {
        $table->boolean('is_hidden')->default(false)->after('nama_kategori');
    });
    }
};
