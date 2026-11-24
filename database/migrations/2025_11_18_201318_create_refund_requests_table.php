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
        Schema::create('refund_requests', function (Blueprint $table) {
            $table->id();
            $table->string('refund_id');
            $table->string('reservation_id');
            $table->decimal('refund_amount', 10, 2);
            $table->enum('mode', ['walk-in', 'gcash']);
            $table->string('gcash_number')->nullable();
            $table->string('screen_shot')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', ['PENDING', 'COMPLETED'])->default('PENDING');
            $table->timestamps();

            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund_requests');
    }
};
