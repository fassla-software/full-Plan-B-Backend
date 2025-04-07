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
        Schema::table('generator_rental_jobs', function (Blueprint $table) {
            $table->string('rental_status')->default('renting');
            $table->boolean('no_harm_in_good_health')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('generator_rental_jobs', function (Blueprint $table) {
            $table->dropColumn([
                'rental_status',
                'no_harm_in_good_health',
            ]);
        });
    }
};
