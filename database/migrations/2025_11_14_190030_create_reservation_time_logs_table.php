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
        Schema::create('reservation_time_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_id')->unique();
            $table->string('reservation_id');
            $table->dateTime('date_and_time_in')->nullable();
            $table->dateTime('date_and_time_out')->nullable();
            $table->enum('status', ['late', 'on time'])->nullable();
            $table->decimal('additional_fee', 8, 2)->default(0);
            $table->boolean('is_paid')->default(false);
            $table->string('log_by')->nullable();
            $table->timestamps();

            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
            $table->foreign('log_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_time_logs');
    }
};
