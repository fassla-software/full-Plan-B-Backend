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
        Schema::create('vehicle_rents', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->unsignedBigInteger('category_id');
            $table->decimal('vehicle_load', 8, 2)->nullable();
            $table->string('model', 50)->nullable();
            $table->string('current_vehicle_location')->nullable();
            $table->string('vehicle_license_front_image')->nullable();
            $table->string('vehicle_license_back_image')->nullable();
            $table->string('driver_license_front_image')->nullable();
            $table->string('driver_license_back_image')->nullable();
            $table->json('additional_vehicle_images')->nullable(); // Store multiple images as JSON
            $table->text('comment')->nullable();
            $table->boolean('has_tank_discharge_pump')->nullable();
            $table->boolean('has_band_sprinkler_bar')->nullable();
            $table->boolean('has_discharge_pump_with_liters_meter')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_rents');
    }
};
