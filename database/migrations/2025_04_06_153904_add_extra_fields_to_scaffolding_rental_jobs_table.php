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
        Schema::table('scaffolding_rental_jobs', function (Blueprint $table) {
            $table->string('rental_period')->nullable();
            $table->string('roof_area')->nullable();
            $table->string('concrete_thickness')->nullable();
            $table->string('floor_number')->nullable();
            $table->string('roof_height')->nullable();
            $table->string('engineering_drawings_images')->nullable();
            $table->boolean('concrete_thickness_varies')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scaffolding_rental_jobs', function (Blueprint $table) {
            $table->dropColumn([
                'rental_period',
                'roof_area',
                'concrete_thickness',
                'floor_number',
                'roof_height',
                'engineering_drawings_images',
            ]);
        });
    }
};
