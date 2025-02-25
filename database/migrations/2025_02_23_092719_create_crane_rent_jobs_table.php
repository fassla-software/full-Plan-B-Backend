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
        Schema::create('crane_rent_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();

            $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->string('worksite_location')->nullable();
            $table->string('hour')->nullable();
            $table->string('day')->nullable();
            $table->string('month')->nullable();
            $table->float('search_range_around_worksite')->nullable();
            $table->string('max_arrival_date')->nullable();
            $table->string('max_offer_deadline')->nullable();
            $table->text('additional_requirements')->nullable();
            $table->integer('number_of_loading_points')->nullable()->default(1);
            $table->string('load_image')->nullable();
            $table->float('load_weight')->nullable();
            $table->string('load_location')->nullable();
            $table->integer('number_of_load_destinations')->nullable()->default(1);
            $table->string('unloading_location')->nullable();
            $table->string('load_start_time')->nullable();
            $table->float('search_range_around_loading_location')->nullable();
            $table->string('offer_submission_deadline')->nullable();
            $table->float('required_height')->nullable();
            $table->float('required_load')->nullable();
            $table->integer('furniture_lifting_to_floor')->nullable();
            $table->boolean('safety_compliant')->default(false);
            $table->boolean('environmental_compliant')->default(false);
            $table->boolean('has_night_lighting')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crane_rent_jobs');
    }
};
