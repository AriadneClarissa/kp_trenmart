<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total', 15, 2)->default(0);
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->string('pickup_method')->nullable();
            $table->string('payment_status')->default('pending'); // pending, waiting_confirmation, confirmed
            $table->string('order_status')->default('new'); // new, processing, completed, cancelled
            $table->string('payment_proof')->nullable();
            $table->timestamps();

            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
