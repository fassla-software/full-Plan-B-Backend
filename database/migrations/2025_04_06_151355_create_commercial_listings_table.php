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
        Schema::create('commercial_listings', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('logo')->nullable();
            $table->string('phone');
            $table->string('whatsapp');
            $table->string('email');
            $table->text('description');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commercial_listings');
    }
};
