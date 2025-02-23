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
        Schema::create('crane_rents', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();

            $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->string('model')->nullable();
            $table->string('type')->nullable();
            $table->float('boom_length')->nullable();
            $table->float('truck_load_capacity')->nullable();
            $table->float('load_at_max_arm_height')->nullable();
            $table->float('load_at_max_arm_distance')->nullable();
            $table->string('current_location')->nullable();
            $table->json('additional_equipment_images')->nullable();
            $table->string('vehicle_license_front')->nullable();
            $table->string('vehicle_license_back')->nullable();
            $table->string('driver_license_front')->nullable();
            $table->string('driver_license_back')->nullable();
            $table->text('custom_conditions')->nullable();
            $table->json('load_data_documents')->nullable();
            $table->integer('installation_time')->nullable(); // Time in minutes or hours
            $table->float('base_area_required')->nullable(); // Area in square meters
            $table->float('maximum_height')->nullable();
            $table->float('maximum_load_capacity')->nullable();
            $table->json('insurance_documents')->nullable();
            $table->float('actual_load_at_max_distance')->nullable();
            $table->json('operator_qualification_documents')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crane_rents');
    }
};
