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
        Schema::create('generator_rental_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();

            $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('long', 10, 7)->nullable();
            $table->string('generator_power')->nullable();
            $table->string('work_site_location')->nullable(); // Required work site location
            $table->string('hour')->nullable();
            $table->string('day')->nullable();
            $table->string('month')->nullable();
            $table->decimal('search_radius')->nullable(); // Search range for equipment around the work site
            $table->date('max_arrival_date')->nullable(); // Latest date for equipment arrival
            $table->date('max_offer_deadline')->nullable(); // Latest date to receive offers
            $table->boolean('isStopped')->default(0)->comment('1=the request is stopped receiving offers 0=request not stopped');
            $table->boolean('isSeen')->default(0)->comment('1=seen 0=unseen');
            $table->text('additional_requirements')->nullable(); // Additional conditions or work details
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generator_rental_jops');
    }
};
