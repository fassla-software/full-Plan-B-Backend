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
        Schema::create('generator_rentals', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();

            $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->string('model')->nullable();
            $table->string('generator_power')->nullable();
            $table->string('maximum_number_of_continuous_operating_hours')->nullable();
            $table->year('year_of_manufacture')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('long', 10, 7)->nullable();
            $table->text('special_rental_conditions')->nullable(); // اضف الشروط الخاصة بتأجير هذه المعدة
            $table->string('current_generator_location')->nullable(); // موقع المعدة الحالي
            $table->string('generator_image')->nullable(); // صورة المولد
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generator_rentals');
    }
};
