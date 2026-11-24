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
        Schema::create('reschedule_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reservation_id');
            $table->dateTime('new_check_in_date_time');
            $table->dateTime('new_check_out_date_time');
            $table->text('reason');
            $table->enum('status', ['PENDING', 'APPROVED', 'DENIED'])->default('PENDING');
            $table->timestamps();

            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reschedule_requests');
    }
};
