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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->string('reservation_id');
            $table->enum('payment_method', ['gcash', 'walk-in', 'bank']);
            $table->decimal('amount', 10, 2);
            $table->decimal('change_amount', 10, 2)->default(0.00);
            $table->enum('payment_status', ['pending', 'paid', 'partial', 'failed'])->default('pending');
            $table->string('recorded_by')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
