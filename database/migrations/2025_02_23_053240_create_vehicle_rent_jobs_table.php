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
        Schema::create('vehicle_rent_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();

            $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->string('required_work_location')->nullable();
            $table->integer('required_load_capacity')->nullable();
            $table->string('required_rental_duration')->nullable(); // Duration in days
            $table->decimal('search_radius')->nullable(); // Radius in kilometers
            $table->string('max_arrival_date')->nullable();
            $table->string('max_offer_deadline')->nullable();
            $table->text('work_description')->nullable();
            $table->boolean('safety_compliant')->default(false);
            $table->boolean('environmental_compliant')->default(false);
            $table->boolean('has_night_lighting')->default(false);
            $table->string('vehicle_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_rent_jobs');
    }
};
