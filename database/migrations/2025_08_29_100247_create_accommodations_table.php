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
        Schema::create('accommodations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->unsignedInteger('pax');
            $table->time('check_in_time');
            $table->time('check_out_time');
            $table->unsignedInteger('room_count');
            $table->string('image')->nullable();
            $table->enum('tour_type', ['day', 'night', null])->nullable();
            $table->decimal('price', 8, 2);
            $table->unsignedSmallInteger('discount')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('accommodation_gallery', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accommodation_id')->constrained('accommodations')->onDelete('cascade');
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodations');
        Schema::dropIfExists('accommodation_gallery');
    }
};
