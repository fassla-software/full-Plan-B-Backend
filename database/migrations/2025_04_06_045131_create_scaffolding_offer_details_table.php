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
        Schema::create('scaffolding_offer_details', function (Blueprint $table) {
            $table->id();
            $table->string('time_required_for_on_site_installation')->nullable();
            $table->string('scaffolding_images')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scaffolding_offer_details');
    }
};
