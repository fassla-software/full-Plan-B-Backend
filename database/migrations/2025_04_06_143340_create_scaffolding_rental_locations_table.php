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
        Schema::create('scaffolding_rental_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scaffolding_rental_id')->constrained('scaffolding_rentals')->onDelete('cascade');
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('long', 10, 7)->nullable();
            $table->string('current_equipment_location')->nullable(); // موقع المعدة الحالي
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scaffolding_rental_locations');
    }
};
