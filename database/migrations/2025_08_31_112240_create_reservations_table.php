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
        Schema::create('reservations', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id');
            $table->date('reservation_date');
            $table->dateTime('check_in_date_time');
            $table->dateTime('check_out_date_time');
            $table->unsignedSmallInteger('no_of_days_stay');
            $table->unsignedSmallInteger('no_of_guest');

            $table->decimal('entrance_fee_per_pax', 10, 2)->nullable();

            $table->json('pets')->nullable();
            $table->string('sheet_color')->nullable();
            $table->string('valid_id')->nullable();

            $table->dateTime('accept_term_date');
            $table->foreignId('accommodation_id')->constrained('accommodations')->onDelete('cascade');
            $table->enum('status', ['PENDING', 'APPROVED', 'DONE', 'CANCELLED', 'DECLINED'])->default('PENDING');

            $table->decimal('accommodation_price', 10, 2)->default(0);

            $table->unsignedSmallInteger('discount_applied')->nullable();

            $table->decimal('total_entrance_fee', 10, 2)->default(0);

            $table->decimal('total_price', 10, 2);

            $table->decimal('paid_balance', 10, 2)->default(0);
            $table->decimal('remaining_balance', 10, 2)->default(0);

            $table->string('paymongo_source_id')->nullable();

            $table->unsignedTinyInteger('rebook_count')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
