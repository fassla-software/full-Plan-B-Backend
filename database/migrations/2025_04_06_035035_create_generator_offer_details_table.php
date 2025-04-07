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
        Schema::create('generator_offer_details', function (Blueprint $table) {
            $table->id();
            $table->string('model')->nullable();
            $table->string('generator_power')->nullable();
            $table->string('max_number_of_continues_operating_houres')->default(0);
            $table->string('number_of_daily_operating_houres')->default(0);
            $table->string('generator_images')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generator_offer_details');
    }
};
