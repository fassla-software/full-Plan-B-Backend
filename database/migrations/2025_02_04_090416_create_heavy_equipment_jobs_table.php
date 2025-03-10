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
        Schema::create('heavy_equipment_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('heavy_equipment_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->string('work_site_location')->nullable(); // Required work site location
            $table->decimal('search_radius')->nullable(); // Search range for equipment around the work site
            $table->date('max_arrival_date')->nullable(); // Latest date for equipment arrival
            $table->date('max_offer_deadline')->nullable(); // Latest date to receive offers
            $table->string('size'); // Size
            $table->json('attachments')->nullable(); // Attachments
            $table->text('flatbed_load_description')->nullable(); // Description of the required load for transport
            $table->string('flatbed_loading_location'); // Loading location for transport
            $table->string('flatbed_destination_location'); // Destination of the load for transport
            $table->string('asphalt_scraper_movement'); // Movement type of the asphalt scraper
            $table->boolean('safety_compliant')->default(false); // Meets safety standards
            $table->boolean('environmental_compliant')->default(false); // Meets environmental regulations
            $table->boolean('has_night_lighting')->default(false); // Has night lighting
            $table->text('additional_requirements')->nullable(); // Additional conditions or work details
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heavy_equipment_jobs');
    }
};
